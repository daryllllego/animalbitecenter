@extends('layouts.app')

@push('styles')
<style>
    .cash-table th {
        background-color: #f8f9fa;
        color: #495057;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 13px;
        letter-spacing: 1px;
    }
    .cash-header {
        color: #2953e8;
        letter-spacing: 2px;
        text-align: center;
        padding: 15px 0;
        border-bottom: 2px solid #e9ecef;
        margin-bottom: 20px;
        font-weight: 700;
    }
    .total-row {
        font-weight: bold;
        color: #dc3545;
    }
    .nurse-duty-input {
        background-color: #f1f5f9;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        padding: 8px 15px;
        width: 100%;
        margin-top: 5px;
    }
    .nav-tabs {
        border-bottom: 2px solid #e2e8f0;
        margin-bottom: 20px;
    }
    .nav-tabs .nav-link {
        border: none;
        color: #64748b;
        font-weight: 600;
        padding: 12px 25px;
        transition: all 0.3s ease;
    }
    .nav-tabs .nav-link.active {
        color: #2953e8;
        border-bottom: 3px solid #2953e8;
        background: transparent;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <ul class="nav nav-tabs" id="cashTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="opening-tab" data-bs-toggle="tab" data-bs-target="#opening" type="button" role="tab" aria-controls="opening" aria-selected="true">OPENING CASH</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="closing-tab" data-bs-toggle="tab" data-bs-target="#closing" type="button" role="tab" aria-controls="closing" aria-selected="false">CLOSING CASH</button>
            </li>
        </ul>

        <div class="tab-content" id="cashTabsContent">
            <!-- Opening Tab -->
            <div class="tab-pane fade show active" id="opening" role="tabpanel" aria-labelledby="opening-tab">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('animal-bite.cash-on-hand.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="shift" value="opening">
                            <h3 class="cash-header">CASH ON HAND (OPENING)</h3>
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <label class="small font-weight-bold text-muted">NURSE ON DUTY</label>
                                    <input type="text" name="nurse_on_duty" class="nurse-duty-input" value="{{ ($opening && $opening->nurse_on_duty) ? $opening->nurse_on_duty : auth()->user()->first_name . ' ' . auth()->user()->last_name }}">
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered text-center cash-table">
                                    <thead>
                                        <tr>
                                            <th>DENOMINATION</th>
                                            <th>QUANTITY</th>
                                            <th>TOTAL</th>
                                            <th>TIME OF CASH COUNTING</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $denominations = [1000, 500, 200, 100, 50, 20, 10, 5, 1];
                                        @endphp
                                        @foreach ($denominations as $denom)
                                            @php
                                                $qty = $opening ? $opening->{'denom_' . $denom} : 0;
                                            @endphp
                                            <tr>
                                                <td class="align-middle">₱ {{ number_format($denom) }}</td>
                                                <td><input type="number" name="denom_{{ $denom }}" class="form-control text-center mx-auto" style="width: 100px;" value="{{ $qty }}"></td>
                                                <td class="align-middle row-total">₱ {{ number_format($denom * $qty, 2) }}</td>
                                                @if ($loop->first)
                                                    <td rowspan="9" class="p-0">
                                                        <textarea name="remarks" class="form-control border-0 h-100" style="min-height: 400px;" placeholder="Remarks or Time log...">{{ $opening->remarks ?? '' }}</textarea>
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                        <tr class="total-row table-light">
                                            <td colspan="2" class="text-end">GRAND TOTAL</td>
                                            <td>
                                                <input type="hidden" name="total_amount" class="grand-total-input" value="{{ $opening->total_amount ?? 0 }}">
                                                <span class="grand-total-display">₱ {{ number_format($opening->total_amount ?? 0, 2) }}</span>
                                            </td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-end mt-3">
                                <button type="submit" class="btn btn-primary px-4">SAVE OPENING CASH</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Closing Tab -->
            <div class="tab-pane fade" id="closing" role="tabpanel" aria-labelledby="closing-tab">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('animal-bite.cash-on-hand.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="shift" value="closing">
                            <h3 class="cash-header text-danger">CASH ON HAND (CLOSING)</h3>
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <label class="small font-weight-bold text-muted">NURSE ON DUTY</label>
                                    <input type="text" name="nurse_on_duty" class="nurse-duty-input" value="{{ ($closing && $closing->nurse_on_duty) ? $closing->nurse_on_duty : auth()->user()->first_name . ' ' . auth()->user()->last_name }}">
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered text-center cash-table">
                                    <thead>
                                        <tr>
                                            <th>DENOMINATION</th>
                                            <th>QUANTITY</th>
                                            <th>TOTAL</th>
                                            <th>TIME OF CASH COUNTING</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($denominations as $denom)
                                            @php
                                                $qty = $closing ? $closing->{'denom_' . $denom} : 0;
                                            @endphp
                                            <tr>
                                                <td class="align-middle">₱ {{ number_format($denom) }}</td>
                                                <td><input type="number" name="denom_{{ $denom }}" class="form-control text-center mx-auto" style="width: 100px;" value="{{ $qty }}"></td>
                                                <td class="align-middle row-total">₱ {{ number_format($denom * $qty, 2) }}</td>
                                                @if ($loop->first)
                                                    <td rowspan="9" class="p-0">
                                                        <textarea name="remarks" class="form-control border-0 h-100" style="min-height: 400px;" placeholder="Remarks or Time log...">{{ $closing->remarks ?? '' }}</textarea>
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                        <tr class="total-row table-light">
                                            <td colspan="2" class="text-end">GRAND TOTAL</td>
                                            <td>
                                                <input type="hidden" name="total_amount" class="grand-total-input" value="{{ $closing->total_amount ?? 0 }}">
                                                <span class="grand-total-display">₱ {{ number_format($closing->total_amount ?? 0, 2) }}</span>
                                            </td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-end mt-3">
                                <button type="submit" class="btn btn-danger px-4">SAVE CLOSING CASH</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
    $(document).ready(function() {
        function calculateTotal(containerId) {
            let grandTotal = 0;
            $(`#${containerId} tbody tr`).each(function() {
                const denomText = $(this).find('td:first').text().replace(/[^\d.-]/g, '');
                const denom = parseFloat(denomText);
                const qty = parseFloat($(this).find('input[type="number"]').val()) || 0;
                
                if (!isNaN(denom)) {
                    const rowTotal = denom * qty;
                    $(this).find('.row-total').text('₱ ' + rowTotal.toLocaleString(undefined, {minimumFractionDigits: 2}));
                    grandTotal += rowTotal;
                }
            });
            $(`#${containerId} .grand-total-display`).text('₱ ' + grandTotal.toLocaleString(undefined, {minimumFractionDigits: 2}));
            $(`#${containerId} .grand-total-input`).val(grandTotal);
        }

        $('input[type="number"]').on('input', function() {
            const containerId = $(this).closest('.tab-pane').attr('id');
            calculateTotal(containerId);
        });
    });
</script>
@endpush
@endsection
