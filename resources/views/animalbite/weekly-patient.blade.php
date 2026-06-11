@extends('layouts.app')

@push('styles')
<style>
    .kpi-card {
        border-radius: 12px;
        border: none;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        transition: transform 0.2s;
        position: relative;
        height: 100%;
    }
    .kpi-card:hover {
        transform: translateY(-5px);
    }
    .kpi-title {
        font-size: 11px;
        text-transform: uppercase;
        font-weight: 800;
        color: #64748b;
        letter-spacing: 1px;
    }
    .kpi-value {
        font-size: 24px;
        font-weight: 800;
        color: #1e293b;
        margin-top: 5px;
    }
    .bg-primary-light { background-color: #e3f2fd; }
</style>
@endpush

@section('content')
    <div class="row">
        <div class="col-12 mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h2 class="text-primary font-w600">WEEKLY PATIENT REPORT</h2>
                <p class="text-muted">Viewing: <span class="badge bg-primary px-3 py-2" style="font-size: 14px;">{{ $selectedBranch }}</span> for <span class="badge bg-info px-3 py-2" style="font-size: 14px;">Week {{ explode('-W', $week)[1] ?? '' }}, {{ explode('-W', $week)[0] ?? '' }}</span></p>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-xl-12">
            <div class="card shadow-sm" style="border-radius: 15px; border: none;">
                <div class="card-body">
                    <form action="{{ route('animal-bite.weekly-patient') }}" method="GET" class="row align-items-end">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <label class="form-label font-weight-bold">Select Week</label>
                            <input type="week" name="week" class="form-control" value="{{ $week }}" onchange="this.form.submit()">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="row mb-4">
        <div class="col-xl-12 col-sm-12 mb-4">
            <div class="card kpi-card bg-primary-light border-start border-primary border-4">
                <div class="card-body">
                    <div class="kpi-title">TOTAL PATIENTS THIS WEEK</div>
                    <div class="kpi-value">{{ number_format($totalPatients) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-xl-12">
            <div class="card shadow-sm" style="border-radius: 15px; border: none;">
                <div class="card-header bg-white border-0 py-4">
                    <h4 class="card-title text-primary mb-0"><i class="fas fa-calendar-day me-2"></i>Daily Breakdown</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-responsive-sm">
                            <thead>
                                <tr class="text-muted">
                                    <th>Date</th>
                                    <th class="text-end">Patients</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($results as $dayData)
                                <tr>
                                    <td><strong>{{ $dayData['date'] }}</strong></td>
                                    <td class="text-end font-weight-bold">{{ number_format($dayData['patients']) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light">
                                <tr class="font-weight-bold">
                                    <td>GRAND TOTAL</td>
                                    <td class="text-end">{{ number_format($totalPatients) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
