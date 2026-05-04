@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-xl-6 col-lg-8 mx-auto">
            <div class="card shadow-sm" style="border-radius: 15px; border: none;">
                <div class="card-header bg-primary text-white" style="border-radius: 15px 15px 0 0;">
                    <h4 class="card-title text-white mb-0"><i class="fas fa-lock me-2"></i>Change Your Password</h4>
                </div>
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <div class="profile-photo mb-3">
                            <img src="{{ asset('images/cebuABC.png') }}" class="img-fluid rounded-circle shadow-sm" alt="" style="width: 100px; height: 100px; object-fit: contain; border: 3px solid #f0f4f8;">
                        </div>
                        <h3 class="mb-1 text-black fw-bold">{{ $user->name }}</h3>
                        <p class="text-muted"><i class="fas fa-map-marker-alt me-1"></i> {{ $user->branch }}</p>
                    </div>

                    <form action="{{ route('profile.update-password') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Current Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-key text-muted"></i></span>
                                <input type="password" name="current_password" class="form-control border-start-0 ps-0" required placeholder="Enter current password">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-weight-bold">New Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-lock text-muted"></i></span>
                                <input type="password" name="new_password" class="form-control border-start-0 ps-0" required placeholder="At least 8 characters">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label font-weight-bold">Confirm New Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-shield-alt text-muted"></i></span>
                                <input type="password" name="new_password_confirmation" class="form-control border-start-0 ps-0" required placeholder="Repeat new password">
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg shadow" style="border-radius: 10px;">
                                <i class="fas fa-check-circle me-2"></i>Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
