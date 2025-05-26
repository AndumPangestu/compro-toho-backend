@extends('layouts.app')

@section('name', 'Team Management')

@section('content')

    <h1>
        @if (isset($viewMode) && $viewMode)
            View Team
        @elseif (isset($team))
            Edit Team
        @else
            Create Team
        @endif
    </h1>
    <hr>
    <div class="card shadow mb-4">
        <div class="card-body">
            @if (isset($viewMode) && $viewMode)
                <h3>Team Details</h3>
                <p><strong>Name:</strong> {{ $team->name }}</p>
                <p><strong>Description:</strong> {{ $team->description }}</p>
                <a href="{{ route('teams.index') }}" class="btn btn-secondary mt-3">Back to List</a>
            @else
                <form method="POST" action="{{ isset($team) ? route('teams.update', $team->id) : route('teams.store') }}"
                    enctype="multipart/form-data">
                    @csrf
                    @if (isset($team))
                        @method('PUT')
                    @endif
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Enter Name..."
                            value="{{ old('name', $team->name ?? '') }}" required>
                        @error('name')
                            <span class="text-danger"><strong>{{ $message }}</strong> </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="role">Role</label>
                        <input type="text" class="form-control" name="role" id="role" placeholder="Enter role..."
                            value="{{ old('role', $team->role ?? '') }}" required>
                        @error('role')
                            <span class="text-danger"><strong>{{ $message }}</strong> </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="position_number">Position Number</label>
                        <input type="number" class="form-control" name="position_number" id="position_number"
                            placeholder="Enter Position Number..."
                            value="{{ old('position_number', $team->position_number ?? '') }}" required>
                        @error('position_number')
                            <span class="text-danger"><strong>{{ $message }}</strong> </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="image">Image</label>
                        <input type="file" class="form-control" name="image" id="image"
                            placeholder="Enter Image..." value="{{ old('image', $team->image ?? '') }}">
                        @error('image')
                            <span class="text-danger"><strong>{{ $message }}</strong> </span>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">
                        {{ isset($team) ? 'Update Team' : 'Submit Team' }}
                    </button>
                </form>
            @endif
        </div>
    </div>
@endsection
