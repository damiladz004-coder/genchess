@php
    $whatsAppLink = 'https://wa.me/2348078462223?text=' . urlencode('Hello Genchess, I want to book a free trial.');
@endphp
<section class="public-cta py-10 border-t border-b border-[#d7c5a7] bg-gradient-to-r from-[#2f1f14] to-[#523624] text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <h3 class="text-2xl font-display">Start Your Child's Genchess Journey</h3>
            <p class="text-sm text-amber-100/90">Build focus, confidence, and smart thinking through structured chess learning.</p>
        </div>
        <div class="flex w-full md:w-auto flex-col sm:flex-row items-stretch sm:items-center gap-3">
            <a href="{{ route('chess.communities.homes') }}#booking-form" class="w-full sm:w-auto inline-flex items-center justify-center rounded-lg px-4 py-2 font-semibold bg-[#f5d18c] dark:bg-[#f2cc82] text-[#2f1f14] dark:text-[#2f1f14] hover:bg-[#efc26f] transition">Book a Free Trial</a>
            <a href="{{ $whatsAppLink }}" target="_blank" rel="noopener" class="w-full sm:w-auto inline-flex items-center justify-center rounded-lg px-4 py-2 font-semibold text-white bg-purple-900 border border-purple-300/40 hover:bg-purple-950 transition">Chat on WhatsApp</a>
        </div>
    </div>
</section>
