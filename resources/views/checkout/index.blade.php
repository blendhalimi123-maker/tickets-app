@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-7">
            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-4"> Payment Method</h4>
                    <form action="{{ route('checkout.process') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Name on Card</label>
                            <input type="text" name="card_name" class="form-control" placeholder="Blendi Halimi" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Card Number</label>
                            <input type="text" name="card_number" class="form-control" placeholder="1234 5678 1234 5678" maxlength="16" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">

                                <label class="form-label fw-semibold">Expiry Date</label>
                                <div class="d-flex gap-2">
                                    <select id="expiry-month" class="form-select" aria-label="Expiry month" required>
                                        <option value="">Month</option>
                                        <option value="01">Jan</option>
                                        <option value="02">Feb</option>
                                        <option value="03">Mar</option>
                                        <option value="04">Apr</option>
                                        <option value="05">May</option>
                                        <option value="06">Jun</option>
                                        <option value="07">Jul</option>
                                        <option value="08">Aug</option>
                                        <option value="09">Sep</option>
                                        <option value="10">Oct</option>
                                        <option value="11">Nov</option>
                                        <option value="12">Dec</option>
                                    </select>

                                    <select id="expiry-year" class="form-select" aria-label="Expiry year" required>
                                        <option value="">Year</option>
                                    </select>

                                    <input type="hidden" name="expiry" id="expiry-hidden">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">CVV</label>
                                <input type="text" name="cvv" class="form-control" placeholder="123" maxlength="3" required>
                            </div>
                        </div>

                        <div class="alert alert-info border-0 shadow-sm mt-3">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-info-circle-fill fs-4 me-3"></i>
                                <div>
                                    <strong>Demo Mode:</strong> You can enter any 16-digit number. 
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success btn-lg w-100 py-3 fw-bold mt-3">
                            Confirm and Pay ${{ number_format($total, 2) }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card shadow-sm border-0 rounded-3 bg-light">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4">Order Summary</h5>
                    @foreach($cartItems as $item)
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">
                                {{ $item->home_team }} vs {{ $item->away_team }}<br>
                                <span class="badge bg-secondary">Seat {{ $item->seat_number }}</span>
                            </span>
                            <span class="fw-bold">${{ number_format($item->price, 2) }}</span>
                        </div>
                    @endforeach
                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span>${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Service Fee</span>
                        <span>${{ number_format($serviceFee, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between border-top pt-3">
                        <span class="h5 fw-bold">Total</span>
                        <span class="h5 fw-bold text-primary">${{ number_format($total, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    (function(){
        const month = document.getElementById('expiry-month');
        const year = document.getElementById('expiry-year');
        const hidden = document.getElementById('expiry-hidden');

        const now = new Date();
        const start = now.getFullYear();
        const span = 15;
        for (let i = 0; i < span; i++) {
            const y = start + i;
            const opt = document.createElement('option');
            opt.value = String(y);
            opt.textContent = y;
            year.appendChild(opt);
        }

        function updateHidden(){
            const m = month.value;
            const y = year.value;
            if (!m || !y) {
                hidden.value = '';
                return;
            }
            const yy = String(y).slice(-2);
            hidden.value = m + '/' + yy;
        }

        month.addEventListener('change', updateHidden);
        year.addEventListener('change', updateHidden);

        const form = month.closest('form');
        if (form) {
            form.addEventListener('submit', function(e){
                updateHidden();
                if (!hidden.value) {
                    e.preventDefault();
                    month.focus();
                    alert('Please select expiry month and year');
                }
            });
        }
    })();
</script>

@endsection