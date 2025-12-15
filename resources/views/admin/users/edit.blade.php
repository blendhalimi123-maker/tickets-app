@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="mb-0">Edit User: {{ $user->name }}</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('users.update', $user) }}" id="userForm">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label d-block">User Role</label>
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <span class="badge bg-primary">User</span>
                                    <span class="mx-2">⇄</span>
                                    <span class="badge bg-danger">Admin</span>
                                </div>
                                
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="roleSwitch" 
                                        name="role" value="admin" {{ $user->role === 'admin' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="roleSwitch">
                                        <span id="roleText">{{ $user->role === 'admin' ? 'Admin' : 'User' }}</span>
                                    </label>
                                </div>
                            </div>
                            <small class="text-muted">Toggle to change user role.</small>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password (leave blank to keep current)</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation">
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary" id="updateBtn">Update User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleSwitch = document.getElementById('roleSwitch');
    const roleText = document.getElementById('roleText');
    const updateBtn = document.getElementById('updateBtn');
    const originalRole = "{{ $user->role }}"; // 'admin' or 'user'
    
    roleSwitch.addEventListener('change', function() {
        roleText.textContent = this.checked ? 'Admin' : 'User';
    });
    
    updateBtn.addEventListener('click', function(e) {
        const currentRole = roleSwitch.checked ? 'admin' : 'user';
        
        if (originalRole !== currentRole) {
            const newRole = currentRole === 'admin' ? 'Admin' : 'User';
            const oldRole = originalRole === 'admin' ? 'Admin' : 'User';
            
            if (!confirm(`Are you sure you want to change this user from ${oldRole} to ${newRole} role?`)) {
                e.preventDefault();
                roleSwitch.checked = originalRole === 'admin';
                roleText.textContent = originalRole === 'admin' ? 'Admin' : 'User';
                return false;
            }
        }
        
        return true;
    });
});
</script>
@endsection