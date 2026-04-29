@extends('layouts.app')

@push('styles')
<link href="{{ asset('vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<style>
    .masterlist-header {
        color: #2953e8;
        letter-spacing: 2px;
        font-weight: 700;
        text-transform: uppercase;
    }
    .table-masterlist th {
        background-color: #f1f5f9;
        color: #334155;
        font-size: 12px;
        text-transform: uppercase;
        border-bottom: 2px solid #e2e8f0;
    }
    .table-masterlist td {
        font-size: 13px;
        vertical-align: middle;
    }
    .btn-add-patient {
        background-color: #2953e8;
        color: white;
        border-radius: 8px;
        padding: 10px 20px;
        font-weight: 600;
        border: none;
        transition: all 0.3s ease;
    }
    .btn-add-patient:hover {
        background-color: #1a45a1;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(41, 83, 232, 0.3);
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header border-0 pb-0 d-flex justify-content-between align-items-center">
                <h2 class="masterlist-header mb-0">PATIENT MANAGEMENT</h2>
                <button type="button" class="btn btn-add-patient" data-bs-toggle="modal" data-bs-target="#addPatientModal">
                    <i class="fa fa-plus me-2"></i>ADD NEW PATIENT
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="patientsTable" class="table table-bordered table-hover table-masterlist text-center w-100">
                        <thead>
                            <tr>
                                <th>NAME</th>
                                <th>AGE</th>
                                <th>GENDER</th>
                                <th>BARANGGAY</th>
                                <th>CITY</th>
                                <th>CONTACT NUMBER</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($patients as $patient)
                            <tr>
                                <td class="text-start font-weight-bold">{{ $patient->name }}</td>
                                <td>{{ $patient->age }}</td>
                                <td>{{ $patient->gender }}</td>
                                <td>{{ $patient->barangay }}</td>
                                <td>{{ $patient->city }}</td>
                                <td>{{ $patient->contact_number }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Patient Modal -->
<div class="modal fade" id="addPatientModal" tabindex="-1" aria-labelledby="addPatientModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-white" id="addPatientModalLabel">Add New Patient</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('animal-bite.patients.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" required placeholder="e.g. Juan Dela Cruz">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="age" class="form-label">Age</label>
                            <input type="number" class="form-control" id="age" name="age" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="gender" class="form-label">Gender</label>
                            <select class="form-control" id="gender" name="gender" required>
                                <option value="" selected disabled>Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="barangay" class="form-label">Barangay</label>
                        <input type="text" class="form-control" id="barangay" name="barangay" required>
                    </div>
                    <div class="mb-3">
                        <label for="city" class="form-label">City</label>
                        <input type="text" class="form-control" id="city" name="city" required>
                    </div>
                    <div class="mb-3">
                        <label for="contact_number" class="form-label">Contact Number</label>
                        <input type="text" class="form-control" id="contact_number" name="contact_number" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Patient</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#patientsTable').DataTable({
            "paging": true,
            "ordering": true,
            "info": true,
            "searching": true,
            "language": {
                "paginate": {
                    "next": '<i class="fa fa-angle-right"></i>',
                    "previous": '<i class="fa fa-angle-left"></i>' 
                }
            }
        });
    });
</script>
@endpush
