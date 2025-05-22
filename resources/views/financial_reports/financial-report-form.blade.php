@extends('layouts.app')

@section('title', 'Financial Report Management')

@section('content')
    <h1>Financial Reports</h1>
    <hr>
    <div class="card shadow mb-4">
        <div class="card-body">
            @if (isset($report) && isset($viewMode))
                <p><strong>Title:</strong> {{ $report->title }}</p>
                <p><strong>Year:</strong> {{ $report->year }}</p>
                @if ($report->hasMedia('financial_reports'))
                    <a href="{{ $report->getFirstMediaUrl('financial_reports') }}" class="btn btn-success" target="_blank"
                        rel="noopener noreferrer" download>
                        Download Current File
                    </a>
                @endif
                <br>
                <a href="{{ route('financial-reports.index') }}" class="btn btn-secondary mt-3">Back to List</a>
            @else
                <form method="POST"
                    action="{{ isset($report) ? route('financial-reports.update', $report->id) : route('financial-reports.store') }}"
                    enctype="multipart/form-data">
                    @csrf
                    @if (isset($report))
                        @method('PUT')
                    @endif
                    <div class="form-group">
                        <label for="title">Report Title</label>
                        <input type="text" class="form-control" name="title" id="title"
                            placeholder="Enter Report Title..." value="{{ isset($report) ? $report->title : old('title') }}"
                            required>
                        @error('title')
                            <span class="text-danger"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="year">Year</label>
                        <input type="number" class="form-control" name="year" id="year" placeholder="Enter Year..."
                            value="{{ isset($report) ? $report->year : old('year') }}" required min="1900"
                            max="{{ date('Y') }}">
                        @error('year')
                            <span class="text-danger"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="file">Upload Report File</label>
                        <input type="file" class="form-control-file" name="file" id="file"
                            accept=".pdf,.doc,.docx,.xls,.xlsx,.csv" required>
                        @error('file')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        @if (isset($report) && $report->hasMedia('financial_reports'))
                            <div class="mt-3">
                                <a href="{{ $report->getFirstMediaUrl('financial_reports') }}" class="btn btn-success"
                                    target="_blank" rel="noopener noreferrer" download>
                                    Download Current File
                                </a>
                            </div>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">
                        {{ isset($report) ? 'Update Report' : 'Submit Report' }}
                    </button>
                </form>
            @endif
        </div>
    </div>
@endsection
