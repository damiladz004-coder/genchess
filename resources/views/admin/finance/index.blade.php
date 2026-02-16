<x-app-layout>
    <div class="space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <h1 class="text-3xl gc-heading">Finance Tracking</h1>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.finance.export', request()->only('school_id','term','session','status')) }}" class="gc-btn-secondary">
                    Export CSV
                </a>
                <a href="{{ route('admin.dashboard') }}" class="gc-btn-secondary">
                    Back to Dashboard
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="gc-panel p-3 border-emerald-200 bg-emerald-50 text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="gc-panel p-3 border-rose-200 bg-rose-50 text-rose-700">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
            <div class="gc-panel p-4">
                <div class="text-xs text-slate-500 uppercase tracking-wide">Total Due</div>
                <div class="text-2xl font-bold text-brand-800">{{ $totalDue }}</div>
            </div>
            <div class="gc-panel p-4">
                <div class="text-xs text-slate-500 uppercase tracking-wide">Total Paid</div>
                <div class="text-2xl font-bold text-emerald-700">{{ $totalPaid }}</div>
            </div>
            <div class="gc-panel p-4">
                <div class="text-xs text-slate-500 uppercase tracking-wide">Outstanding</div>
                <div class="text-2xl font-bold text-rose-700">{{ $outstanding }}</div>
            </div>
            <div class="gc-panel p-4">
                <div class="text-xs text-slate-500 uppercase tracking-wide">Overdue Schools</div>
                <div class="text-2xl font-bold text-amber-700">{{ $overdueCount }}</div>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.finance.index') }}" class="gc-panel p-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">School</label>
                    <select name="school_id">
                        <option value="">All</option>
                        @foreach($schools as $school)
                            <option value="{{ $school->id }}" @if(request('school_id') == $school->id) selected @endif>
                                {{ $school->school_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Term</label>
                    <select name="term">
                        <option value="">All</option>
                        @foreach($terms as $term)
                            <option value="{{ $term }}" @if(request('term') == $term) selected @endif>
                                {{ $term }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Session</label>
                    <select name="session">
                        <option value="">All</option>
                        @foreach($sessions as $session)
                            <option value="{{ $session }}" @if(request('session') == $session) selected @endif>
                                {{ $session }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Status</label>
                    <select name="status">
                        <option value="">All</option>
                        <option value="pending" @if(request('status') == 'pending') selected @endif>Pending</option>
                        <option value="partial" @if(request('status') == 'partial') selected @endif>Partial</option>
                        <option value="paid" @if(request('status') == 'paid') selected @endif>Paid</option>
                    </select>
                </div>
            </div>
            <div class="mt-3 flex gap-2">
                <button type="submit" class="gc-btn-primary">Apply</button>
                <a href="{{ route('admin.finance.index') }}" class="gc-btn-secondary">Reset</a>
            </div>
        </form>

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
            <div class="gc-panel p-4">
                <h2 class="text-lg font-semibold mb-3">Set School Pricing</h2>
                <form method="POST" action="{{ route('admin.finance.pricing.store') }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">School</label>
                        <select name="school_id" required>
                            <option value="">Select school</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}">{{ $school->school_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1">Term</label>
                            <select name="term" required id="pricing-term">
                                <option value="">Select term</option>
                                @foreach($terms as $term)
                                    <option value="{{ $term }}">{{ $term }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1">Session</label>
                            <select name="session" required id="pricing-session">
                                <option value="">Select session</option>
                                @foreach($sessions as $session)
                                    <option value="{{ $session }}">{{ $session }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1">Per Student Amount</label>
                            <input type="number" name="per_student_amount" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1">Currency</label>
                            <input name="currency" value="NGN" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Notes</label>
                        <textarea name="notes" rows="2"></textarea>
                    </div>
                    <button type="submit" class="gc-btn-primary">Save Pricing</button>
                </form>
            </div>

            <div class="gc-panel p-4">
                <h2 class="text-lg font-semibold mb-3">Generate Term Payment (Auto)</h2>
                <p class="text-sm text-slate-600 mb-3">
                    Uses current student count for the school and pricing for the term/session.
                </p>
                <form method="POST" action="{{ route('admin.finance.generate') }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">School</label>
                        <select name="school_id" required>
                            <option value="">Select school</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}">{{ $school->school_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1">Term</label>
                            <select name="term" required>
                                <option value="">Select term</option>
                                @foreach($terms as $term)
                                    <option value="{{ $term }}">{{ $term }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1">Session</label>
                            <select name="session" required>
                                <option value="">Select session</option>
                                @foreach($sessions as $session)
                                    <option value="{{ $session }}">{{ $session }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1">Term Start Date</label>
                            <input type="date" name="term_start_date" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1">Term End Date</label>
                            <input type="date" name="term_end_date" required>
                        </div>
                    </div>
                    <button type="submit" class="gc-btn-primary">Generate Payment</button>
                </form>
            </div>

            <div class="gc-panel p-4">
                <h2 class="text-lg font-semibold mb-3">Generate Payments (All Schools)</h2>
                <p class="text-sm text-slate-600 mb-3">
                    Creates term payment records for every school with pricing set.
                </p>
                @if(session('bulk_preview'))
                    @php $preview = session('bulk_preview'); @endphp
                    <div class="mb-3 bg-slate-50 border border-slate-200 rounded-lg p-3 text-sm">
                        <div class="font-medium mb-1">Preview</div>
                        <div>Term: {{ $preview['term'] }} - Session: {{ $preview['session'] }}</div>
                        <div>Creatable: {{ $preview['creatable'] }}</div>
                        <div>Skipped (no pricing): {{ $preview['skipped_no_pricing'] }}</div>
                        <div>Skipped (existing payment): {{ $preview['skipped_existing'] }}</div>
                        <details class="mt-2">
                            <summary class="cursor-pointer font-medium">School Lists</summary>
                            <div class="mt-2 grid md:grid-cols-3 gap-3">
                                <div>
                                    <div class="font-medium">Will Create</div>
                                    @if(!empty($preview['creatable_list']))
                                        <ul class="list-disc pl-5">
                                            @foreach($preview['creatable_list'] as $name)
                                                <li>{{ $name }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <div class="text-slate-500">None</div>
                                    @endif
                                </div>
                                <div>
                                    <div class="font-medium">No Pricing</div>
                                    @if(!empty($preview['skipped_no_pricing_list']))
                                        <ul class="list-disc pl-5">
                                            @foreach($preview['skipped_no_pricing_list'] as $name)
                                                <li>{{ $name }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <div class="text-slate-500">None</div>
                                    @endif
                                </div>
                                <div>
                                    <div class="font-medium">Existing Payment</div>
                                    @if(!empty($preview['skipped_existing_list']))
                                        <ul class="list-disc pl-5">
                                            @foreach($preview['skipped_existing_list'] as $name)
                                                <li>{{ $name }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <div class="text-slate-500">None</div>
                                    @endif
                                </div>
                            </div>
                        </details>
                    </div>
                @endif
                <form method="POST" action="{{ route('admin.finance.generate.bulk.preview') }}" class="space-y-3" id="bulk-preview-form">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1">Term</label>
                            <select name="term" required>
                                <option value="">Select term</option>
                                @foreach($terms as $term)
                                    <option value="{{ $term }}">{{ $term }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1">Session</label>
                            <select name="session" required>
                                <option value="">Select session</option>
                                @foreach($sessions as $session)
                                    <option value="{{ $session }}">{{ $session }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1">Term Start Date</label>
                            <input type="date" name="term_start_date" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1">Term End Date</label>
                            <input type="date" name="term_end_date" required>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="submit" class="gc-btn-secondary">Preview</button>
                        <button type="button" class="gc-btn-primary" id="bulk-generate-btn">
                            Generate for All Schools
                        </button>
                    </div>
                </form>
            </div>

            <div class="gc-panel p-4">
                <h2 class="text-lg font-semibold mb-3">Record Payment</h2>
                <p class="text-sm text-slate-600 mb-3">
                    Installments are auto-calculated: 50% due 3 weeks after resumption, balance due 1 week before term ends.
                </p>
                <form method="POST" action="{{ route('admin.finance.payments.store') }}" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">School</label>
                        <select name="school_id" required id="payment-school">
                            <option value="">Select school</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}">{{ $school->school_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1">Term</label>
                            <select name="term" required id="payment-term">
                                <option value="">Select term</option>
                                @foreach($terms as $term)
                                    <option value="{{ $term }}">{{ $term }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1">Session</label>
                            <select name="session" required id="payment-session">
                                <option value="">Select session</option>
                                @foreach($sessions as $session)
                                    <option value="{{ $session }}">{{ $session }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1">Term Start Date</label>
                            <input type="date" name="term_start_date" required id="term-start">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1">Term End Date</label>
                            <input type="date" name="term_end_date" required id="term-end">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1">Student Count</label>
                            <input type="number" name="student_count" required id="student-count">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1">Per Student Amount</label>
                            <input type="number" name="per_student_amount" required id="per-student-amount" readonly class="bg-slate-100">
                            <p class="text-xs text-slate-500 mt-1">
                                Rate is locked to the pricing agreement for the selected term/session.
                            </p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Amount Paid</label>
                        <input type="number" name="amount_paid" required id="amount-paid">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm text-slate-600">
                        <div>
                            <div>Total Due (auto)</div>
                            <div id="total-due-preview">-</div>
                        </div>
                        <div>
                            <div>Installments (auto)</div>
                            <div id="installments-preview">-</div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1">Due Date</label>
                            <input type="date" name="due_date">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1">Reference</label>
                            <input name="reference">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Notes</label>
                        <textarea name="notes" rows="2"></textarea>
                    </div>
                    <button type="submit" class="gc-btn-primary">Record Payment</button>
                </form>
            </div>
        </div>

        <div class="gc-panel p-4">
            <h2 class="text-lg font-semibold mb-3">Current Pricing Agreements</h2>
            @if($pricings->isEmpty())
                <p class="text-slate-600">No pricing records yet.</p>
            @else
                <div class="overflow-x-auto">
                    <table>
                        <thead>
                            <tr>
                                <th>School</th>
                                <th>Term</th>
                                <th>Session</th>
                                <th>Rate</th>
                                <th>Currency</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pricings as $pricing)
                                <tr>
                                    <td>{{ $pricing->school->school_name ?? 'N/A' }}</td>
                                    <td>{{ $pricing->term }}</td>
                                    <td>{{ $pricing->session }}</td>
                                    <td>{{ $pricing->per_student_amount }}</td>
                                    <td>{{ $pricing->currency }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <div class="gc-panel p-4">
            <h2 class="text-lg font-semibold mb-3">Recent Payments</h2>
            @if($payments->isEmpty())
                <p class="text-slate-600">No payments recorded yet.</p>
            @else
                <div class="overflow-x-auto">
                    <table>
                        <thead>
                            <tr>
                                <th>School</th>
                                <th>Term</th>
                                <th>Session</th>
                                <th>Students</th>
                                <th>Rate</th>
                                <th>Total Due</th>
                                <th>Paid</th>
                                <th>Status</th>
                                <th>Installments</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $payment)
                                @php
                                    $isOverdue = $payment->second_due_date
                                        ? ($payment->second_due_date->isPast() && $payment->amount_paid < $payment->total_due)
                                        : false;
                                    $statusClass = $payment->status === 'paid'
                                        ? 'text-emerald-700'
                                        : ($payment->status === 'partial' ? 'text-amber-700' : 'text-slate-700');
                                @endphp
                                <tr>
                                    <td>{{ $payment->school->school_name ?? 'N/A' }}</td>
                                    <td>{{ $payment->term }}</td>
                                    <td>{{ $payment->session }}</td>
                                    <td>{{ $payment->student_count }}</td>
                                    <td>{{ $payment->per_student_amount }}</td>
                                    <td>{{ $payment->total_due }}</td>
                                    <td>{{ $payment->amount_paid }}</td>
                                    <td>
                                        <span class="{{ $statusClass }}">{{ ucfirst($payment->status) }}</span>
                                        @if($isOverdue)
                                            <span class="text-rose-600 ml-2">(Overdue)</span>
                                        @endif
                                    </td>
                                    <td class="text-sm text-slate-600">
                                        @if($payment->first_due_date && $payment->second_due_date)
                                            1st: {{ $payment->first_amount }} due {{ $payment->first_due_date->format('Y-m-d') }}<br>
                                            2nd: {{ $payment->second_amount }} due {{ $payment->second_due_date->format('Y-m-d') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

<script>
    const pricingIndex = @json($pricingIndex);

    function findPricing() {
        const schoolId = document.getElementById('payment-school').value;
        const term = document.getElementById('payment-term').value;
        const session = document.getElementById('payment-session').value;
        if (!schoolId || !term || !session) {
            return null;
        }
        return pricingIndex.find(p =>
            String(p.school_id) === String(schoolId) &&
            p.term === term &&
            p.session === session
        );
    }

    function applyPricing() {
        const pricing = findPricing();
        if (pricing) {
            document.getElementById('per-student-amount').value = pricing.per_student_amount;
            recalcPreview();
        } else {
            document.getElementById('per-student-amount').value = '';
            recalcPreview();
        }
    }

    function recalcPreview() {
        const count = parseInt(document.getElementById('student-count').value || '0', 10);
        const rate = parseInt(document.getElementById('per-student-amount').value || '0', 10);
        const totalDue = count * rate;
        const totalEl = document.getElementById('total-due-preview');
        totalEl.textContent = totalDue > 0 ? totalDue : '-';

        const start = document.getElementById('term-start').value;
        const end = document.getElementById('term-end').value;
        const installmentsEl = document.getElementById('installments-preview');
        if (start && end && totalDue > 0) {
            const startDate = new Date(start + 'T00:00:00');
            const endDate = new Date(end + 'T00:00:00');
            const firstDue = new Date(startDate.getTime() + (21 * 24 * 60 * 60 * 1000));
            const secondDue = new Date(endDate.getTime() - (7 * 24 * 60 * 60 * 1000));
            const firstAmount = Math.floor(totalDue / 2);
            const secondAmount = totalDue - firstAmount;
            const fmt = d => d.toISOString().slice(0, 10);
            installmentsEl.textContent = `1st: ${firstAmount} due ${fmt(firstDue)} - 2nd: ${secondAmount} due ${fmt(secondDue)}`;
        } else {
            installmentsEl.textContent = '-';
        }
    }

    ['payment-school', 'payment-term', 'payment-session'].forEach(id => {
        document.getElementById(id).addEventListener('change', applyPricing);
    });

    ['student-count', 'per-student-amount', 'term-start', 'term-end'].forEach(id => {
        document.getElementById(id).addEventListener('input', recalcPreview);
    });

    const bulkGenerateBtn = document.getElementById('bulk-generate-btn');
    if (bulkGenerateBtn) {
        bulkGenerateBtn.addEventListener('click', () => {
            const form = document.getElementById('bulk-preview-form');
            const ok = confirm('Generate payment records for all schools with pricing? This will skip existing records.');
            if (!ok) return;

            form.action = '{{ route('admin.finance.generate.bulk') }}';
            form.submit();
        });
    }
</script>
