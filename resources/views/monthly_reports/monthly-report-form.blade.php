@extends('layouts.app')

@section('title', 'Monthly Report Management')

@section('content')
    <h1>Monthly Reports</h1>
    <hr>
    <div class="card shadow mb-4">
        <div class="card-body">
            @if (isset($report) && isset($viewMode))
                <p><strong>Title:</strong> {{ $report->title }}</p>
                <p><strong>Year:</strong> {{ $report->year }}</p>
                <p><strong>Month:</strong> {{ $report->month }}</p>
                <p><strong>Total Expenses:</strong> {{ number_format($report->total_expenses, 2) }}</p>
                <p><strong>Category:</strong> {{ $report->category->name }}</p>
                <br>
                <a href="{{ route('monthly-reports.index') }}" class="btn btn-secondary mt-3">Back to List</a>
            @else
                <form method="POST"
                    action="{{ isset($report) ? route('monthly-reports.update', $report->id) : route('monthly-reports.store') }}">
                    @csrf
                    @if (isset($report))
                        @method('PUT')
                    @endif

                    <div class="form-group">
                        <label for="title">Report Title</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" name="title"
                            id="title" placeholder="Enter Report Title..."
                            value="{{ isset($report) ? $report->title : old('title') }}" required>
                        @error('title')
                            <span class="text-danger"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="year">Year</label>
                        <input type="number" class="form-control @error('year') is-invalid @enderror" name="year"
                            id="year" placeholder="Enter Year..."
                            value="{{ isset($report) ? $report->year : old('year') }}" required min="1900"
                            max="{{ date('Y') }}">
                        @error('year')
                            <span class="text-danger"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="month">Month</label>
                        <select class="form-control @error('month') is-invalid @enderror" name="month" id="month"
                            required>
                            @foreach (['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $month)
                                <option value="{{ $month }}"
                                    {{ (isset($report) && $report->month == $month) || old('month') == $month ? 'selected' : '' }}>
                                    {{ $month }}
                                </option>
                            @endforeach
                        </select>
                        @error('month')
                            <span class="text-danger"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="total_expenses">Total Expenses</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input type="text" class="form-control @error('total_expenses') is-invalid @enderror"
                                id="total_expenses_display">
                        </div>

                        <input type="hidden" name="total_expenses" id="total_expenses"
                            value="{{ isset($report) ? $report->total_expenses : old('total_expenses') }}" required>

                        @error('total_expenses')
                            <span class="text-danger"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="category">Category</label>
                        <select class="form-control @error('category_id') is-invalid @enderror" name="category_id"
                            id="category" required>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ (isset($report) && $report->category_id == $category->id) || old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <span class="text-danger"><strong>{{ $message }}</strong></span>
                        @enderror
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
        let collectedFundsInput = document.getElementById("total_expenses");
        let collectedFundsDisplay = document.getElementById("total_expenses_display");

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
