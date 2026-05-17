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
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm border-start border-primary border-4" style="background-color: #e3f2fd; border-radius: 12px;">
            <div class="card-body d-flex justify-content-between align-items-center py-3">
                <div>
                    <h5 class="text-primary font-weight-bold mb-0" style="letter-spacing: 0.5px;">Daily Starting Opening Cash</h5>
                    <p class="text-muted small mb-0">The initial cash float balance for today</p>
                </div>
                <div class="d-flex align-items-center">
                    <span class="h4 text-primary font-weight-bold mb-0 me-3" style="font-size: 22px;">₱ {{ number_format($openingCash, 2) }}</span>
                    @if($isEditable)
                        <button class="btn btn-primary btn-sm rounded shadow-sm px-3 py-2" data-bs-toggle="modal" data-bs-target="#editStartingCashModal" style="border-radius: 8px; font-weight: 600;">
                            <i class="fa fa-edit me-1"></i>Edit Starting Cash
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

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
<!-- Edit Starting Cash Modal -->
@if($isEditable)
<div class="modal fade" id="editStartingCashModal" tabindex="-1" aria-labelledby="editStartingCashModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 15px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title text-primary font-weight-bold" id="editStartingCashModalLabel" style="font-size: 18px; letter-spacing: 0.5px;">EDIT DAILY STARTING CASH</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('animal-bite.update-opening-cash') }}" method="POST">
                @csrf
                <div class="modal-body py-4">
                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-muted small mb-2">STARTING OPENING CASH AMOUNT</label>
                        <div class="input-group" style="box-shadow: 0 2px 5px rgba(0,0,0,0.05); border-radius: 8px; overflow: hidden;">
                            <span class="input-group-text bg-light border-end-0 text-dark font-weight-bold" style="font-size: 16px; border: 1px solid #cbd5e1;">₱</span>
                            <input type="number" step="0.01" name="opening_cash" class="form-control border-start-0 text-dark font-weight-bold" style="font-size: 16px; height: 45px; border: 1px solid #cbd5e1;" value="{{ $openingCash }}" required>
                        </div>
                        <span class="text-muted small d-block mt-2"><i class="fa fa-info-circle me-1"></i>Enter the daily cash float balance available when starting the clinic operations.</span>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded px-4" data-bs-dismiss="modal" style="border-radius: 8px;">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded px-4" style="border-radius: 8px;">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

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
