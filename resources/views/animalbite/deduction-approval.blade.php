@extends('layouts.app')

@push('styles')
<style>
    .approval-header {
        color: #2953e8;
        letter-spacing: 2px;
        font-weight: 700;
        text-transform: uppercase;
    }
    .nav-tabs .nav-link {
        color: #64748b;
        font-weight: 600;
        border: none;
        border-bottom: 3px solid transparent;
        padding: 12px 20px;
    }
    .nav-tabs .nav-link.active {
        color: #2953e8;
        background: transparent;
        border-bottom: 3px solid #2953e8;
    }
    .request-card {
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }
    .request-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="approval-header mb-0">Deduction Approval</h2>
        </div>

        <ul class="nav nav-tabs mb-4" id="approvalTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="pending-tab" data-bs-toggle="tab" href="#pending" role="tab">
                    Pending <span class="badge bg-primary ms-2">{{ $pending->count() }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="history-tab" data-bs-toggle="tab" href="#history" role="tab">
                    History
                </a>
            </li>
        </ul>

        <div class="tab-content" id="approvalTabsContent">
            <!-- Pending Tab -->
            <div class="tab-pane fade show active" id="pending" role="tabpanel">
                @if($pending->isEmpty())
                    <div class="text-center py-5">
                        <i class="fa fa-check-circle fa-4x text-success mb-3"></i>
                        <h4 class="text-muted">No pending deduction requests!</h4>
                        <p>Everything is up to date.</p>
                    </div>
                @else
                    @foreach($pending as $request)
                        <div class="card request-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <span class="badge bg-warning text-dark mb-2">
                                            PENDING DEDUCTION
                                        </span>
                                        <h5 class="mb-1">
                                            Amount: ₱ {{ number_format($request->amount, 2) }}
                                        </h5>
                                        <p class="text-muted small mb-0">
                                            <i class="fa fa-building me-1"></i> {{ $request->branch }} | 
                                            <i class="fa fa-user me-1"></i> Released by: {{ $request->released_by }} | 
                                            <i class="fa fa-clock me-1"></i> {{ \Carbon\Carbon::parse($request->created_at)->format('M d, Y h:i A') }}
                                        </p>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <form action="{{ route('animal-bite.deduction-approval.approve', $request->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm px-3">
                                                <i class="fa fa-check me-1"></i> Approve
                                            </button>
                                        </form>
                                        <form action="{{ route('animal-bite.deduction-approval.decline', $request->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-danger btn-sm px-3">
                                                <i class="fa fa-times me-1"></i> Decline
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <div class="bg-light p-3 rounded mb-4">
                                    <h6 class="small font-weight-bold mb-2 text-primary">REASON / DESCRIPTION:</h6>
                                    <p class="mb-0 italic text-dark">{{ $request->description }}</p>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <h6 class="text-muted small mb-3 uppercase tracking-wider font-weight-bold">DETAILS</h6>
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <div class="p-2 border rounded bg-white" style="min-height: 60px;">
                                                    <label class="d-block small text-muted mb-0 uppercase" style="font-size: 10px;">Released To</label>
                                                    <span class="font-weight-bold small">{{ $request->released_to }}</span>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="p-2 border rounded bg-white" style="min-height: 60px;">
                                                    <label class="d-block small text-muted mb-0 uppercase" style="font-size: 10px;">Date Needed</label>
                                                    <span class="font-weight-bold small">{{ \Carbon\Carbon::parse($request->date)->format('M d, Y') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <!-- History Tab -->
            <div class="tab-pane fade" id="history" role="tabpanel">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover text-center">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Branch</th>
                                        <th>Released By</th>
                                        <th>Released To</th>
                                        <th>Amount</th>
                                        <th>Reason</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($history as $h)
                                        <tr>
                                            <td class="small">{{ \Carbon\Carbon::parse($h->created_at)->format('M d, Y h:i A') }}</td>
                                            <td>{{ $h->branch }}</td>
                                            <td>{{ $h->released_by }}</td>
                                            <td>{{ $h->released_to }}</td>
                                            <td class="font-weight-bold">₱ {{ number_format($h->amount, 2) }}</td>
                                            <td class="text-start small" style="max-width: 200px;">{{ $h->description }}</td>
                                            <td>
                                                @if($h->status === 'approved')
                                                    <span class="badge bg-success">APPROVED</span>
                                                @else
                                                    <span class="badge bg-danger">DECLINED</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
