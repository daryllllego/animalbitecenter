@extends('layouts.app')

@push('styles')
<style>
    .tracking-card {
        border-radius: 15px;
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        transition: transform 0.3s ease;
        background: white;
    }
    .tracking-card:hover {
        transform: translateY(-5px);
    }
    .tracking-title {
        font-size: 12px;
        text-transform: uppercase;
        font-weight: 700;
        color: #64748b;
        letter-spacing: 1.5px;
        margin-bottom: 10px;
    }
    .tracking-value {
        font-size: 28px;
        font-weight: 800;
        color: #1e293b;
    }
    .tracking-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 15px;
    }
    .bg-soft-primary { background: #e0e7ff; color: #4338ca; }
    .bg-soft-success { background: #dcfce7; color: #15803d; }
    .bg-soft-danger { background: #fee2e2; color: #b91c1c; }
    .bg-soft-warning { background: #fef3c7; color: #b45309; }
    .bg-soft-info { background: #e0f2fe; color: #0369a1; }

    .summary-table th {
        background: #f8fafc;
        color: #475569;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 12px;
        padding: 15px;
    }
    .summary-table td {
        padding: 15px;
        vertical-align: middle;
        font-weight: 500;
    }
    .variance-positive { color: #15803d; background: #dcfce7; }
    .variance-negative { color: #b91c1c; background: #fee2e2; }
    .variance-neutral { color: #475569; background: #f1f5f9; }
    
    .last-child-no-border:last-child {
        border-right: none !important;
    }
    
    /* Ensure scrolling is enabled */
    body, html {
        overflow-y: auto !important;
        height: auto !important;
    }
    .content-body {
        overflow-y: auto !important;
    }
</style>
@endpush

@section('content')
@php
    $denominations = [
        'denom_1000' => ['val' => 1000, 'label' => '₱ 1,000 Bill'],
        'denom_500' => ['val' => 500, 'label' => '₱ 500 Bill'],
        'denom_200' => ['val' => 200, 'label' => '₱ 200 Bill'],
        'denom_100' => ['val' => 100, 'label' => '₱ 100 Bill'],
        'denom_50' => ['val' => 50, 'label' => '₱ 50 Bill'],
        'denom_20' => ['val' => 20, 'label' => '₱ 20 Bill'],
        'coin_20' => ['val' => 20, 'label' => '₱ 20 Coin'],
        'denom_10' => ['val' => 10, 'label' => '₱ 10 Coin'],
        'denom_5' => ['val' => 5, 'label' => '₱ 5 Coin'],
        'denom_1' => ['val' => 1, 'label' => '₱ 1 Coin'],
    ];
@endphp
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="font-w600 text-primary mb-0">CASH ON HAND TRACKING</h2>
            <p class="text-muted">Daily Automated Cash Summary for {{ \Carbon\Carbon::parse($selectedDate)->format('F d, Y') }}</p>
        </div>
        <div class="badge bg-primary px-4 py-2" style="font-size: 16px; border-radius: 10px;">
            <i class="fa fa-calendar me-2"></i>{{ \Carbon\Carbon::parse($selectedDate)->format('M d, Y') }}
        </div>
    </div>
</div>



<div class="row g-2">
    <!-- Opening Cash -->
    <div class="col-xl-2 col-sm-4 col-6 mb-2">
        <div class="card tracking-card h-100">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="tracking-icon bg-soft-primary mb-0 me-2" style="width: 35px; height: 35px; min-width: 35px;">
                        <i class="fa fa-door-open" style="font-size: 14px;"></i>
                    </div>
                    <div>
                        <div class="tracking-title mb-0" style="font-size: 9px; letter-spacing: 0.5px;">Opening</div>
                        <div class="tracking-value text-primary" style="font-size: 15px;">₱{{ number_format($openingAmount, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Cash Sales -->
    <div class="col-xl-2 col-sm-4 col-6 mb-2">
        <div class="card tracking-card h-100">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="tracking-icon bg-soft-success mb-0 me-2" style="width: 35px; height: 35px; min-width: 35px;">
                        <i class="fa fa-plus-circle" style="font-size: 14px;"></i>
                    </div>
                    <div>
                        <div class="tracking-title mb-0" style="font-size: 9px; letter-spacing: 0.5px;">Sales</div>
                        <div class="tracking-value text-success" style="font-size: 15px;">₱{{ number_format($totalCashSales, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Deductions -->
    <div class="col-xl-2 col-sm-4 col-6 mb-2">
        <div class="card tracking-card h-100">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="tracking-icon bg-soft-danger mb-0 me-2" style="width: 35px; height: 35px; min-width: 35px;">
                        <i class="fa fa-minus-circle" style="font-size: 14px;"></i>
                    </div>
                    <div>
                        <div class="tracking-title mb-0" style="font-size: 9px; letter-spacing: 0.5px;">Deductions</div>
                        <div class="tracking-value text-danger" style="font-size: 15px;">₱{{ number_format($totalDeductions, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Expected Closing -->
    <div class="col-xl-2 col-sm-4 col-6 mb-2">
        <div class="card tracking-card h-100">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="tracking-icon bg-soft-warning mb-0 me-2" style="width: 35px; height: 35px; min-width: 35px;">
                        <i class="fa fa-equals" style="font-size: 14px;"></i>
                    </div>
                    <div>
                        <div class="tracking-title mb-0" style="font-size: 9px; letter-spacing: 0.5px;">Expected</div>
                        <div class="tracking-value text-warning" style="font-size: 15px;">₱{{ number_format($expectedCash, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Actual Closing -->
    <div class="col-xl-2 col-sm-4 col-6 mb-2">
        <div class="card tracking-card h-100">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="tracking-icon bg-soft-info mb-0 me-2" style="width: 35px; height: 35px; min-width: 35px;">
                        <i class="fa fa-door-closed" style="font-size: 14px;"></i>
                    </div>
                    <div>
                        <div class="tracking-title mb-0" style="font-size: 9px; letter-spacing: 0.5px;">Actual Cash</div>
                        <div class="tracking-value text-info" style="font-size: 15px;">{{ $closingAmount !== null ? '₱' . number_format($closingAmount, 2) : 'Not Closed' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Variance -->
    <div class="col-xl-2 col-sm-4 col-6 mb-2">
        <div class="card tracking-card h-100">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    @if($closingAmount === null)
                        <div class="tracking-icon bg-soft-secondary mb-0 me-2" style="width: 35px; height: 35px; min-width: 35px;">
                            <i class="fa fa-info-circle text-muted" style="font-size: 14px;"></i>
                        </div>
                        <div>
                            <div class="tracking-title mb-0" style="font-size: 9px; letter-spacing: 0.5px;">Variance</div>
                            <div class="tracking-value text-muted" style="font-size: 15px;">Pending</div>
                        </div>
                    @elseif($variance > 0)
                        <div class="tracking-icon bg-soft-success mb-0 me-2" style="width: 35px; height: 35px; min-width: 35px;">
                            <i class="fa fa-caret-up" style="font-size: 16px;"></i>
                        </div>
                        <div>
                            <div class="tracking-title mb-0" style="font-size: 9px; letter-spacing: 0.5px;">Over (Variance)</div>
                            <div class="tracking-value text-success" style="font-size: 15px;">+₱{{ number_format($variance, 2) }}</div>
                        </div>
                    @elseif($variance < 0)
                        <div class="tracking-icon bg-soft-danger mb-0 me-2" style="width: 35px; height: 35px; min-width: 35px;">
                            <i class="fa fa-caret-down" style="font-size: 16px;"></i>
                        </div>
                        <div>
                            <div class="tracking-title mb-0" style="font-size: 9px; letter-spacing: 0.5px;">Short (Variance)</div>
                            <div class="tracking-value text-danger" style="font-size: 15px;">₱{{ number_format($variance, 2) }}</div>
                        </div>
                    @else
                        <div class="tracking-icon bg-soft-info mb-0 me-2" style="width: 35px; height: 35px; min-width: 35px;">
                            <i class="fa fa-check-circle" style="font-size: 14px;"></i>
                        </div>
                        <div>
                            <div class="tracking-title mb-0" style="font-size: 9px; letter-spacing: 0.5px;">Balanced</div>
                            <div class="tracking-value text-dark" style="font-size: 15px;">₱0.00</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-lg-8">
        <!-- Yellow Box: Daily Cash Tally -->
        <div class="card tracking-card mb-4" style="border-top: 4px solid #ffc107; height: auto !important; flex: none !important;">
            <div class="card-body p-4">
                <h5 class="font-w700 text-dark mb-4" style="font-size: 14px; letter-spacing: 1px;">
                    <i class="fa fa-list-ul text-warning me-2"></i>CASH DENOMINATION BREAKDOWN
                </h5>
                <div class="row">
                    <!-- Left: Opening Tally -->
                    <div class="col-md-6 border-end">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                                <h6 class="font-w700 text-primary mb-0"><i class="fa fa-door-open me-2"></i>OPENING CASH COUNT</h6>
                                @if($selectedBranch !== 'All Branches')
                                    <button class="btn btn-xs btn-outline-primary btn-rounded py-0 px-2 ms-2" style="font-size: 11px;" data-bs-toggle="modal" data-bs-target="#editOpeningModal">
                                        <i class="fa fa-edit me-1"></i>Edit
                                    </button>
                                @endif
                            </div>
                            <span class="badge bg-soft-primary px-3 py-1 rounded">Total: ₱{{ number_format($openingAmount, 2) }}</span>
                        </div>
                        <div class="pe-md-3">
                            <div class="d-flex justify-content-between align-items-center mb-3 bg-light p-2 rounded" style="font-size: 12px;">
                                <span class="text-muted"><i class="fa fa-user-edit text-primary me-1"></i>Recorded By:</span>
                                <span class="font-w700 text-primary">{{ ($openingTally && isset($openingTally->nurse_on_duty) && $openingTally->nurse_on_duty) ? $openingTally->nurse_on_duty : 'Not Recorded' }}</span>
                            </div>
                            @foreach($denominations as $key => $denom)
                                @php
                                    $qty = $openingTally ? ($openingTally->{$key} ?? 0) : 0;
                                @endphp
                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                    <span class="text-muted fw-bold">{{ $denom['label'] }}</span>
                                    <div class="text-end">
                                        <span class="font-w800 text-primary h6 mb-0">{{ number_format($qty) }}</span>
                                        <small class="text-muted ms-1">pcs</small>
                                        <span class="badge bg-light text-dark ms-2" style="font-size: 11px;">₱ {{ number_format($denom['val'] * $qty) }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <!-- Right: Closing Tally -->
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-center mb-3 ps-md-3">
                            <div class="d-flex align-items-center">
                                <h6 class="font-w700 text-danger mb-0"><i class="fa fa-door-closed me-2"></i>CLOSING CASH COUNT</h6>
                                @if($selectedBranch !== 'All Branches')
                                    <button class="btn btn-xs btn-outline-danger btn-rounded py-0 px-2 ms-2" style="font-size: 11px;" data-bs-toggle="modal" data-bs-target="#editClosingModal">
                                        <i class="fa fa-edit me-1"></i>Edit
                                    </button>
                                @endif
                            </div>
                            <span class="badge bg-soft-danger px-3 py-1 rounded">Total: ₱{{ number_format($closingAmount, 2) }}</span>
                        </div>
                        <div class="ps-md-3">
                            <div class="d-flex justify-content-between align-items-center mb-3 bg-light p-2 rounded" style="font-size: 12px;">
                                <span class="text-muted"><i class="fa fa-user-edit text-danger me-1"></i>Recorded By:</span>
                                <span class="font-w700 text-danger">{{ ($closingTally && isset($closingTally->nurse_on_duty) && $closingTally->nurse_on_duty) ? $closingTally->nurse_on_duty : 'Not Recorded' }}</span>
                            </div>
                            @foreach($denominations as $key => $denom)
                                @php
                                    $qty = $closingTally ? ($closingTally->{$key} ?? 0) : 0;
                                @endphp
                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                    <span class="text-muted fw-bold">{{ $denom['label'] }}</span>
                                    <div class="text-end">
                                        <span class="font-w800 text-danger h6 mb-0">{{ number_format($qty) }}</span>
                                        <small class="text-muted ms-1">pcs</small>
                                        <span class="badge bg-light text-dark ms-2" style="font-size: 11px;">₱ {{ number_format($denom['val'] * $qty) }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card tracking-card" style="height: auto !important; flex: none !important;">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 font-w700">DETAILED CASH FLOW</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table summary-table mb-0">
                        <thead>
                            <tr>
                                <th>Category</th>
                                <th class="text-end">Amount</th>
                                <th class="text-end">Running Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <span class="badge bg-soft-primary me-2"><i class="fa fa-arrow-right"></i></span>
                                    Starting Balance (Opening)
                                </td>
                                <td class="text-end">₱ {{ number_format($openingAmount, 2) }}</td>
                                <td class="text-end font-w700">₱ {{ number_format($openingAmount, 2) }}</td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="badge bg-soft-success me-2"><i class="fa fa-plus"></i></span>
                                    Total Cash Sales Today
                                </td>
                                <td class="text-end text-success">+ ₱ {{ number_format($totalCashSales, 2) }}</td>
                                <td class="text-end font-w700">₱ {{ number_format($openingAmount + $totalCashSales, 2) }}</td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="badge bg-soft-danger me-2"><i class="fa fa-minus"></i></span>
                                    Total Deductions Today
                                </td>
                                <td class="text-end text-danger">- ₱ {{ number_format($totalDeductions, 2) }}</td>
                                <td class="text-end font-w700 text-primary">₱ {{ number_format($expectedCash, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 align-self-start">
        <div class="card tracking-card bg-primary text-white overflow-hidden mb-3 shadow-lg" style="height: auto !important; flex: none !important;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="text-white mb-0 font-w700" style="letter-spacing: 1px;">DAILY SUMMARY</h5>
                    <i class="fa fa-chart-line text-white-50 fa-2x"></i>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-end">
                        <span class="text-white-50 small fw-bold">OPENING CASH</span>
                        <h4 class="text-white mb-0 font-w700">₱ {{ number_format($openingAmount, 2) }}</h4>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-end">
                        <span class="text-white-50 small fw-bold">TOTAL CASH SALES</span>
                        <h4 class="text-white mb-0 font-w700 text-success">+ ₱ {{ number_format($totalCashSales, 2) }}</h4>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-end">
                        <span class="text-white-50 small fw-bold">TOTAL DEDUCTIONS</span>
                        <h4 class="text-white mb-0 font-w700 text-danger">- ₱ {{ number_format($totalDeductions, 2) }}</h4>
                    </div>
                </div>
                
                <hr style="background: rgba(255,255,255,0.3); height: 2px;" class="my-4">
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-end">
                        <span class="text-white-50 small fw-bold">EXPECTED CLOSING</span>
                        <h4 class="text-white mb-0 font-w700">₱ {{ number_format($expectedCash, 2) }}</h4>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-end">
                        <span class="text-white-50 small fw-bold">ACTUAL CLOSING</span>
                        <h4 class="text-white mb-0 font-w700">{{ $closingAmount !== null ? '₱ ' . number_format($closingAmount, 2) : 'Not Closed' }}</h4>
                    </div>
                </div>
                
                <div class="p-3 rounded mt-4" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2);">
                    <div class="text-center">
                        <span class="text-white-50 small fw-bold d-block mb-1">VARIANCE TALLY</span>
                        @if($closingAmount === null)
                            <h2 class="text-white mb-0 font-w800" style="font-size: 24px;">NO CLOSING COUNT</h2>
                            <small class="text-white-50 fw-bold"><i class="fa fa-info-circle me-1"></i>Awaiting Closing</small>
                        @elseif($variance > 0)
                            <h2 class="text-success mb-0 font-w800" style="font-size: 32px;">+₱ {{ number_format($variance, 2) }}</h2>
                            <small class="text-success fw-bold"><i class="fa fa-caret-up me-1"></i>Over (Cash Excess)</small>
                        @elseif($variance < 0)
                            <h2 class="text-danger mb-0 font-w800" style="font-size: 32px;">₱ {{ number_format($variance, 2) }}</h2>
                            <small class="text-danger fw-bold"><i class="fa fa-caret-down me-1"></i>Short (Cash Deficit)</small>
                        @else
                            <h2 class="text-white mb-0 font-w800" style="font-size: 32px;">₱ 0.00</h2>
                            <small class="text-white-50 fw-bold"><i class="fa fa-check-circle me-1"></i>Balanced</small>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($selectedBranch !== 'All Branches')
<!-- Edit Opening Modal -->
<div class="modal fade" id="editOpeningModal" tabindex="-1" aria-labelledby="editOpeningModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0" style="border-radius: 15px; box-shadow: 0 15px 30px rgba(0,0,0,0.1);">
            <div class="modal-header bg-primary text-white border-0" style="border-top-left-radius: 15px; border-top-right-radius: 15px;">
                <h5 class="modal-title font-w700 text-white" id="editOpeningModalLabel"><i class="fa fa-edit me-2"></i>EDIT OPENING DENOMINATIONS</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('animal-bite.cash-on-hand.store') }}" method="POST">
                @csrf
                <input type="hidden" name="shift" value="opening">
                <div class="modal-body p-4">
                    @php
                        $currentLoggedNurse = '';
                        if (session()->has('active_nurse_id')) {
                            $activeNurseUser = \App\Models\User::find(session('active_nurse_id'));
                            if ($activeNurseUser) {
                                $currentLoggedNurse = $activeNurseUser->name;
                            }
                        }
                        if (empty($currentLoggedNurse)) {
                            $currentLoggedNurse = auth()->user()->name ?? (auth()->user()->first_name . ' ' . auth()->user()->last_name);
                        }
                    @endphp
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="small font-weight-bold text-muted mb-1">NURSE ON DUTY (CURRENTLY LOGGED IN)</label>
                            <input type="text" name="nurse_on_duty" class="form-control" style="background-color: #e2e8f0; border-radius: 10px; font-weight: 600;" value="{{ $currentLoggedNurse }}" readonly>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered text-center cash-table align-middle mb-0">
                            <thead>
                                <tr class="bg-light">
                                    <th>DENOMINATION</th>
                                    <th>QUANTITY</th>
                                    <th>TOTAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($denominations as $key => $denom)
                                    @php
                                        $qty = $openingTally ? ($openingTally->{$key} ?? 0) : 0;
                                    @endphp
                                    <tr>
                                        <td class="align-middle fw-bold text-muted">{{ $denom['label'] }}</td>
                                        <td>
                                            <input type="number" name="{{ $key }}" class="form-control text-center mx-auto" style="width: 100px; border-radius: 8px;" value="{{ $qty > 0 ? $qty : '' }}" min="0" placeholder="0">
                                        </td>
                                        <td class="align-middle row-total fw-bold text-primary">₱ {{ number_format($denom['val'] * $qty, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        <label class="small font-weight-bold text-muted mb-1">REMARKS / TIME LOG</label>
                        <textarea name="remarks" class="form-control" rows="3" style="border-radius: 10px;" placeholder="Add any opening shift cash count remarks here...">{{ $openingTally->remarks ?? '' }}</textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0 d-flex justify-content-between p-3" style="border-bottom-left-radius: 15px; border-bottom-right-radius: 15px;">
                    <div>
                        <span class="text-muted fw-bold small">GRAND TOTAL:</span>
                        <input type="hidden" name="total_amount" class="grand-total-input" value="{{ $openingAmount ?? 0 }}">
                        <span class="grand-total-display h4 text-primary fw-bold ms-2">₱ {{ number_format($openingAmount ?? 0, 2) }}</span>
                    </div>
                    <div>
                        <button type="button" class="btn btn-outline-secondary" style="border-radius: 10px;" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4" style="border-radius: 10px;">Save Changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Closing Modal -->
<div class="modal fade" id="editClosingModal" tabindex="-1" aria-labelledby="editClosingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0" style="border-radius: 15px; box-shadow: 0 15px 30px rgba(0,0,0,0.1);">
            <div class="modal-header bg-danger text-white border-0" style="border-top-left-radius: 15px; border-top-right-radius: 15px;">
                <h5 class="modal-title font-w700 text-white" id="editClosingModalLabel"><i class="fa fa-edit me-2"></i>EDIT CLOSING DENOMINATIONS</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('animal-bite.cash-on-hand.store') }}" method="POST">
                @csrf
                <input type="hidden" name="shift" value="closing">
                <div class="modal-body p-4">
                    @php
                        $currentLoggedNurse = '';
                        if (session()->has('active_nurse_id')) {
                            $activeNurseUser = \App\Models\User::find(session('active_nurse_id'));
                            if ($activeNurseUser) {
                                $currentLoggedNurse = $activeNurseUser->name;
                            }
                        }
                        if (empty($currentLoggedNurse)) {
                            $currentLoggedNurse = auth()->user()->name ?? (auth()->user()->first_name . ' ' . auth()->user()->last_name);
                        }
                    @endphp
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="small font-weight-bold text-muted mb-1">NURSE ON DUTY (CURRENTLY LOGGED IN)</label>
                            <input type="text" name="nurse_on_duty" class="form-control" style="background-color: #e2e8f0; border-radius: 10px; font-weight: 600;" value="{{ $currentLoggedNurse }}" readonly>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered text-center cash-table align-middle mb-0">
                            <thead>
                                <tr class="bg-light">
                                    <th>DENOMINATION</th>
                                    <th>QUANTITY</th>
                                    <th>TOTAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($denominations as $key => $denom)
                                    @php
                                        $qty = $closingTally ? ($closingTally->{$key} ?? 0) : 0;
                                    @endphp
                                    <tr>
                                        <td class="align-middle fw-bold text-muted">{{ $denom['label'] }}</td>
                                        <td>
                                            <input type="number" name="{{ $key }}" class="form-control text-center mx-auto" style="width: 100px; border-radius: 8px;" value="{{ $qty > 0 ? $qty : '' }}" min="0" placeholder="0">
                                        </td>
                                        <td class="align-middle row-total fw-bold text-danger">₱ {{ number_format($denom['val'] * $qty, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        <label class="small font-weight-bold text-muted mb-1">REMARKS / TIME LOG</label>
                        <textarea name="remarks" class="form-control" rows="3" style="border-radius: 10px;" placeholder="Add any closing shift cash count remarks here...">{{ $closingTally->remarks ?? '' }}</textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0 d-flex justify-content-between p-3" style="border-bottom-left-radius: 15px; border-bottom-right-radius: 15px;">
                    <div>
                        <span class="text-muted fw-bold small">GRAND TOTAL:</span>
                        <input type="hidden" name="total_amount" class="grand-total-input" value="{{ $closingAmount ?? 0 }}">
                        <span class="grand-total-display h4 text-danger fw-bold ms-2">₱ {{ number_format($closingAmount ?? 0, 2) }}</span>
                    </div>
                    <div>
                        <button type="button" class="btn btn-outline-secondary" style="border-radius: 10px;" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger px-4" style="border-radius: 10px;">Save Changes</button>
                    </div>
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
            const containerId = $(this).closest('.modal').attr('id');
            calculateTotal(containerId);
        });
    });
</script>
@endpush

@endsection
