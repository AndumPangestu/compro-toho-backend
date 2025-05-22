@extends('layouts.app')

@section('title', 'General Report Management')

@section('content')

    <h1>General Report</h1>
    <hr>
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="POST" action="{{ isset($report) ? route('reports.update', $report->id) : route('reports.store') }}"
                enctype="multipart/form-data">
                @csrf
                @if (isset($report))
                    @method('PUT')
                @endif
                <div class="form-group">
                    <label for="online_funds">Online Fundraising</label>
                    <input type="number" class="form-control" name="online_funds" id="online_funds"
                        placeholder="Enter Online Fundraising..."
                        value="{{ isset($report) ? $report->online_funds : old('online_funds') }}" required>
                    @error('online_funds')
                        <span class="text-danger"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="offline_funds">Offline Fundraising</label>
                    <input type="number" class="form-control" name="offline_funds" id="offline_funds"
                        placeholder="Enter Offline Fundraising..."
                        value="{{ isset($report) ? $report->offline_funds : old('offline_funds') }}" required>
                    @error('offline_funds')
                        <span class="text-danger"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>


                <div class="form-group">
                    <label for="donor_count">Donor Count</label>
                    <input type="number" class="form-control" name="donor_count" id="donor_count"
                        placeholder="Enter Donor Count..."
                        value="{{ isset($report) ? $report->donor_count : old('donor_count') }}" required>
                    @error('donor_count')
                        <span class="text-danger"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="active_program">Active Program</label>
                    <input type="text" class="form-control" name="active_program" id="active_program"
                        placeholder="Enter Active Program..."
                        value="{{ isset($report) ? $report->active_program : old('active_program') }}" required>
                    @error('active_program')
                        <span class="text-danger"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="beneficiary_count">Beneficiary Count</label>
                    <input type="number" class="form-control" name="beneficiary_count" id="beneficiary_count"
                        placeholder="Enter Beneficiary Count..."
                        value="{{ isset($report) ? $report->beneficiary_count : old('beneficiary_count') }}" required>
                    @error('beneficiary_count')
                        <span class="text-danger"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="coverage_area">Coverage Area</label>
                    <input type="text" class="form-control" name="coverage_area" id="coverage_area"
                        placeholder="Enter Coverage Area..."
                        value="{{ isset($report) ? $report->coverage_area : old('coverage_area') }}" required>
                    @error('coverage_area')
                        <span class="text-danger"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary btn-block">
                    {{ isset($report) ? 'Update Report' : 'Submit Report' }}
                </button>
            </form>
        </div>
    </div>
@endsection
