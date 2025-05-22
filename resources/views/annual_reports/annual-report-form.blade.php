@extends('layouts.app')

@section('title', 'Annual Report Management')

@section('content')
    <h1>Annual Reports</h1>
    <hr>
    <div class="card shadow mb-4">
        <div class="card-body">
            @if (isset($report) && isset($viewMode))
                <p><strong>Title:</strong> {{ $report->title }}</p>
                <p><strong>Year:</strong> {{ $report->year }}</p>
                <p><strong>Collected Funds:</strong> {{ number_format($report->collected_funds, 2) }}</p>
                <p><strong>Donor Count:</strong> {{ $report->donor_count }}</p>
                <p><strong>Active Program Count:</strong> {{ $report->active_program_count }}</p>
                @if ($report->hasMedia('annual_reports'))
                    <a href="{{ $report->getFirstMediaUrl('annual_reports') }}" class="btn btn-success" target="_blank"
                        rel="noopener noreferrer" download>
                        Download Current File
                    </a>
                @endif
                <br>
                <a href="{{ route('annual-reports.index') }}" class="btn btn-secondary mt-3">Back to List</a>
            @else
                <form method="POST"
                    action="{{ isset($report) ? route('annual-reports.update', $report->id) : route('annual-reports.store') }}"
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
                        <label for="collected_funds">Collected Funds</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input type="text" class="form-control" id="collected_funds_display">
                        </div>

                        <input type="hidden" name="collected_funds" id="collected_funds"
                            value="{{ isset($report) ? $report->collected_funds : old('collected_funds') }}" required>

                        @error('collected_funds')
                            <span class="text-danger"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="donor_count">Donor Count</label>
                        <input type="number" class="form-control" name="donor_count" id="donor_count"
                            placeholder="Enter Donor Count..."
                            value="{{ isset($report) ? $report->donor_count : old('donor_count') }}" required
                            min="0">
                        @error('donor_count')
                            <span class="text-danger"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="active_program_count">Active Program Count</label>
                        <input type="number" class="form-control" name="active_program_count" id="active_program_count"
                            placeholder="Enter Active Program Count..."
                            value="{{ isset($report) ? $report->active_program_count : old('active_program_count') }}"
                            required min="0">
                        @error('active_program_count')
                            <span class="text-danger"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="file">Upload Report File</label>
                        <input type="file" class="form-control-file" name="file" id="file"
                            accept=".pdf,.doc,.docx,.xls,.xlsx,.csv">
                        @error('report_file')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror

                        @if (isset($report) && $report->hasMedia('annual_reports'))
                            <div class="mt-3">
                                <a href="{{ $report->getFirstMediaUrl('annual_reports') }}" class="btn btn-success"
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
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let collectedFundsInput = document.getElementById("collected_funds");
        let collectedFundsDisplay = document.getElementById("collected_funds_display");

        function formatRupiah(angka) {
            return angka.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function cleanNumber(value) {
            return value.replace(/\D/g, ''); // Hanya angka
        }

        function updateDisplay() {
            let value = cleanNumber(collectedFundsDisplay.value);
            collectedFundsDisplay.value = value ? formatRupiah(value) : "";
            collectedFundsInput.value = value; // Set ke input hidden
        }

        // Saat user mengetik di input display, update hidden input
        collectedFundsDisplay.addEventListener("input", updateDisplay);
        collectedFundsDisplay.addEventListener("change", updateDisplay);

        // Set tampilan saat halaman dimuat jika ada nilai lama
        if (collectedFundsInput.value.trim() !== "") {
            collectedFundsDisplay.value = formatRupiah(collectedFundsInput.value);
        }
    });
</script>
