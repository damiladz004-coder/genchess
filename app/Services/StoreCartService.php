<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Collection;

class StoreCartService
{
    public function add(Product $product, int $quantity, array $options = []): void
    {
        $quantity = max(1, $quantity);
        $options = $this->normalizeOptions($options);

        if (auth()->check()) {
            $this->addForAuthenticatedUser($product, $quantity, $options);
            return;
        }

        $this->addForGuest($product, $quantity, $options);
    }

    public function update(string $lineKey, int $quantity): void
    {
        if (auth()->check()) {
            $item = CartItem::whereKey((int) $lineKey)
                ->whereHas('cart', fn ($query) => $query->where('user_id', auth()->id()))
                ->first();
            if (!$item) {
                return;
            }

            if ($quantity < 1) {
                $item->delete();
                return;
            }

            $item->update(['quantity' => $quantity]);
            return;
        }

        $cart = session()->get('store_cart', []);
        if (!isset($cart[$lineKey])) {
            return;
        }

        if ($quantity < 1) {
            unset($cart[$lineKey]);
        } else {
            $cart[$lineKey]['quantity'] = $quantity;
        }

        session()->put('store_cart', $cart);
    }

    public function remove(string $lineKey): void
    {
        $this->update($lineKey, 0);
    }

    public function clear(): void
    {
        if (auth()->check()) {
            $cart = $this->getOrCreateUserCart();
            $cart->items()->delete();
            return;
        }

        session()->forget('store_cart');
    }

    public function summary(): array
    {
        $items = auth()->check() ? $this->itemsForAuthUser() : $this->itemsForGuest();

        $subtotal = $items->sum(fn (array $item) => $item['line_total_kobo']);

        return [
            'items' => $items,
            'subtotal_kobo' => (int) $subtotal,
            'count' => (int) $items->sum(fn (array $item) => $item['quantity']),
        ];
    }

    protected function addForAuthenticatedUser(Product $product, int $quantity, array $options): void
    {
        $cart = $this->getOrCreateUserCart();
        $optionsHash = $this->optionsHash($options);

        $item = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->where('options_hash', $optionsHash)
            ->first();

        if ($item) {
            $item->increment('quantity', $quantity);
            return;
        }

        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => $quantity,
            'unit_price_kobo' => $product->price_kobo,
            'options_json' => $options,
            'options_hash' => $optionsHash,
        ]);
    }

    protected function addForGuest(Product $product, int $quantity, array $options): void
    {
        $cart = session()->get('store_cart', []);
        $lineKey = $product->id . ':' . $this->optionsHash($options);

        if (isset($cart[$lineKey])) {
            $cart[$lineKey]['quantity'] += $quantity;
        } else {
            $cart[$lineKey] = [
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price_kobo' => $product->price_kobo,
                'options' => $options,
            ];
        }

        session()->put('store_cart', $cart);
    }

    protected function itemsForAuthUser(): Collection
    {
        $cart = $this->getOrCreateUserCart();

        return $cart->items()
            ->with('product')
            ->get()
            ->map(function (CartItem $item): array {
                if (!$item->product) {
                    return [];
                }

                $lineTotal = (int) $item->quantity * (int) $item->unit_price_kobo;

                return [
                    'line_key' => (string) $item->id,
                    'product' => $item->product,
                    'quantity' => (int) $item->quantity,
                    'unit_price_kobo' => (int) $item->unit_price_kobo,
                    'line_total_kobo' => $lineTotal,
                    'options' => $item->options_json ?? [],
                ];
            })
            ->filter(fn (array $line) => !empty($line))
            ->values();
    }

    protected function itemsForGuest(): Collection
    {
        $sessionCart = session()->get('store_cart', []);
        if (empty($sessionCart)) {
            return collect();
        }

        $productIds = collect($sessionCart)->pluck('product_id')->unique()->values();
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        return collect($sessionCart)
            ->map(function (array $line, string $lineKey) use ($products): array {
                $product = $products->get($line['product_id'] ?? null);
                if (!$product) {
                    return [];
                }

                $qty = max(1, (int) ($line['quantity'] ?? 1));
                $unit = (int) ($line['unit_price_kobo'] ?? $product->price_kobo);

                return [
                    'line_key' => $lineKey,
                    'product' => $product,
                    'quantity' => $qty,
                    'unit_price_kobo' => $unit,
                    'line_total_kobo' => $qty * $unit,
                    'options' => $line['options'] ?? [],
                ];
            })
            ->filter(fn (array $line) => !empty($line))
            ->values();
    }

    protected function getOrCreateUserCart(): Cart
    {
        return Cart::firstOrCreate(
            ['user_id' => auth()->id()],
            ['session_id' => session()->getId(), 'currency' => 'NGN']
        );
    }

    protected function normalizeOptions(array $options): array
    {
        $allowed = [
            'size' => $options['size'] ?? null,
            'color' => $options['color'] ?? null,
            'logo_path' => $options['logo_path'] ?? null,
        ];

        return array_filter($allowed, fn ($v) => $v !== null && $v !== '');
    }

    protected function optionsHash(array $options): string
    {
        ksort($options);
        return hash('sha256', json_encode($options));
    }
}
