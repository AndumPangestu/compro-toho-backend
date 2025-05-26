@extends('layouts.app')

@section('title', 'User Management')

@section('content')
    <h1>{{ isset($user) ? (isset($viewMode) ? 'View User' : 'Edit User') : 'Create User' }}</h1>
    <hr>
    <div class="card shadow mb-4">
        <div class="card-body">
            @if (isset($viewMode) && $viewMode)
                <h3>User Details</h3>
                <p><strong>Name:</strong> {{ $user->name }}</p>
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Phone:</strong> {{ $user->phone }}</p>
                <p><strong>Role:</strong> {{ ucfirst($user->role) }}</p>
                <p><strong>Created At:</strong> {{ $user->created_at->format('d M Y, H:i') }}</p>
                <br>
                <a href="{{ route('users.index') }}" class="btn btn-secondary mt-3">Back to List</a>
            @else
                <form method="POST" action="{{ isset($user) ? route('users.update', $user->id) : route('users.store') }}">
                    @csrf
                    @if (isset($user))
                        @method('PUT')
                    @endif
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" class="form-control" name="name" id="name"
                            placeholder="Enter Full Name..." value="{{ isset($user) ? $user->name : old('name') }}"
                            required>
                        @error('name')
                            <span class="text-danger"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" id="email"
                            placeholder="Enter Email..." value="{{ isset($user) ? $user->email : old('email') }}" required>
                        @error('email')
                            <span class="text-danger"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" class="form-control" name="phone" id="phone"
                            placeholder="Enter Phone Number..." value="{{ isset($user) ? $user->phone : old('phone') }}">
                        @error('phone')
                            <span class="text-danger"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select class="form-control" name="role" id="role" required>
                            <option value="">Select Role</option>
                            <option value="admin"
                                {{ isset($user) && $user->role == 'admin' ? 'selected' : (old('role') == 'admin' ? 'selected' : '') }}>
                                Admin</option>
                        </select>
                        @error('role')
                            <span class="text-danger"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <div class="d-flex justify-content-end">
                            @if (isset($user))
                                <button type="button" class="btn btn-primary" onclick="togglePasswordForm()">Ubah
                                    Password</button>
                            @endif
                        </div>
                    </div>

                    <div id="password-form-container">
                        @if (!isset($user))
                            {{-- Jika $user tidak ada, langsung tampilkan form --}}
                            <div id="password-form">
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" name="password" id="password"
                                        placeholder="Enter Password..." required>
                                    @error('password')
                                        <span class="text-danger"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="password_confirmation">Confirm Password</label>
                                    <input type="password" class="form-control" name="password_confirmation"
                                        id="password_confirmation" placeholder="Confirm Password..." required>
                                    @error('password_confirmation')
                                        <span class="text-danger"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                        @endif
                    </div>
                    <div id="password-form-container"></div>
                    <button type="submit" class="btn btn-primary btn-block">
                        {{ isset($user) ? 'Update User' : 'Create User' }}
                    </button>
                </form>
            @endif
        </div>
    </div>
@endsection

<script>
    function togglePasswordForm() {
        var container = document.getElementById("password-form-container");
        if (container.innerHTML.trim() === "") {
            container.innerHTML = `
                <div id="password-form">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="password" id="password"
                            placeholder="Enter Password..." required>
                        @error('password')
                        <span class="text-danger"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <input type="password" class="form-control" name="password_confirmation" id="password_confirmation"
                            placeholder="Confirm Password..." required>
                        @error('password_confirmation')
                        <span class="text-danger"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                </div>
            `;
        } else {
            container.innerHTML = "";
        }
    }
</script>
