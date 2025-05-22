@extends('layouts.app')

@push('styles')
<!-- jQuery (Versi penuh, bukan slim) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Summernote (Versi terbaru yang lebih stabil) -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.js"></script>
@endpush

@section('title', 'Donation Management')

@section('content')
<h1>{{ isset($donation) ? (isset($viewMode) ? 'View Donation' : 'Edit Donation') : 'Create Donation' }}</h1>
<hr>
<div class="card shadow mb-4">
    <div class="card-body">
        @if (isset($viewMode) && $viewMode)
        <h3>Donation Details</h3>
        <p><strong>Title:</strong> {{ $donation->title }}</p>
        <p><strong>Find Usage Details:</strong> {{ $donation->fund_usage_details }}</p>
        <p><strong>Donation Description:</strong> {{ $donation->description }}</p>
        <p><strong>Distribution Information:</strong> {!! $donation->distribution_information !!}</p>
        <p><strong>Target Amount:</strong> {{ number_format($donation->target_amount, 2) }}</p>
        <p><strong>Collected Amount:</strong> {{ number_format($donation->collected_amount, 2) }}</p>
        <p><strong>Start Date:</strong> {{ $donation->start_date }}</p>
        <p><strong>End Date:</strong> {{ $donation->end_date }}</p>
        <p><strong>location:</strong> {{ $donation->location ?? '-' }}</p>
        <p><strong>Category:</strong> {{ $donation->category->name ?? 'Uncategorized' }}</p>
        <p><strong>Highlight:</strong> {{ $donation->put_on_highlight ? 'Yes' : 'No' }}</p>
        <p><strong>Created At:</strong> {{ $donation->created_at }}</p>
        <p><strong>Images:</strong></p>
        <img src="{{ $donation->getFirstMediaUrl('donations') }}" alt="Donation Image" class="img-thumbnail"
            width="300">
        <br>
        <a href="{{ route('donations.index') }}" class="btn btn-secondary mt-3">Back to List</a>
        @else
        <form method="POST"
            action="{{ isset($donation) ? route('donations.update', $donation->id) : route('donations.store') }}"
            enctype="multipart/form-data">
            @csrf
            @if (isset($donation))
            @method('PUT')
            @endif
            <div class="form-group">
                <label for="title">Donation Title</label>
                <input type="text" class="form-control" name="title" id="title" placeholder="Enter Donation Title..."
                    value="{{ isset($donation) ? $donation->title : old('title') }}" required>
                @error('title')
                <span class="text-danger"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
            <div class="form-group">
                <label for="fund_usage_details">Fund Usage Detail</label>
                <textarea class="form-control" name="fund_usage_details" id="fund_usage_details"
                    placeholder="Enter Donation fund usage details..."
                    rows="3">{{ old('fund_usage_details', $donation->fund_usage_details ?? '') }}</textarea>
                @error('fund_usage_details')
                <span class="text-danger"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" name="description" id="description"
                    placeholder="Enter Donation Description..."
                    rows="3">{{ old('description', $donation->description ?? '') }}</textarea>
                @error('description')
                <span class="text-danger"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
            <div class="form-group">
                <label for="distribution_information">Distribution Information</label>
                <textarea class="form-control" name="distribution_information"
                    id="summernote">{{ isset($donation) ? $donation->distribution_information : old('distribution_information') }}</textarea>

                @error('distribution_information')
                <span class="text-danger"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
            <div class="form-group">
                <label for="target_amount">Target Amount</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Rp</span>
                    </div>
                    <input type="text" class="form-control" id="target_amount_display">
                </div>

                <input type="hidden" name="target_amount" id="target_amount"
                    value="{{ isset($donation) ? $donation->target_amount : old('target_amount') }}" required>

                @error('target_amount')
                <span class="text-danger"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
            <div class="form-group">
                <label for="start_date">Start Date</label>
                <input type="date" class="form-control" name="start_date" id="start_date"
                    value="{{ isset($donation) ? $donation->start_date->format('Y-m-d') : old('start_date') }}"
                    required>

                @error('start_date')
                <span class="text-danger"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
            <div class="form-group">
                <label for="end_date">End Date</label>
                <input type="date" class="form-control" name="end_date" id="end_date"
                    value="{{ isset($donation) ? $donation->end_date->format('Y-m-d') : old('end_date') }}" required>

                @error('end_date')
                <span class="text-danger"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" class="form-control" name="location" id="location"
                    value="{{ isset($donation) ? $donation->location : old('location') }}">
                @error('location')
                <span class="text-danger"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
            <div class="form-group">
                <label for="category_id">Category</label>
                <select class="form-control" name="category_id" id="category_id" required>
                    <option value="">Select Category</option>
                    @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ isset($donation) && $donation->category_id == $category->id
                        ? 'selected'
                        : (old('category_id') == $category->id
                        ? 'selected'
                        : '') }}>
                        {{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id')
                <span class="text-danger"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
            <div class="form-group">
                <label for="put_on_highlight">Highlight</label>
                <select class="form-control" name="put_on_highlight" id="put_on_highlight">
                    <option value="0" {{ isset($donation) && !$donation->put_on_highlight ? 'selected' : '' }}>No
                    </option>
                    <option value="1" {{ isset($donation) && $donation->put_on_highlight ? 'selected' : '' }}>
                        Yes</option>
                </select>
                @error('put_on_highlight')
                <span class="text-danger"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
            @if (!isset($donation))
            <div class="form-group">
                <label>
                    <input type="checkbox" name="share_via_email" value="1">
                    Share this donation via email
                </label>
            </div>
            @endif
            <div class="form-group">
                <label for="image">Upload Donation Image</label>
                <input type="file" class="form-control-file" name="image" id="image" accept="image/*"
                    onchange="previewImage(event)">
                @error('image')
                <span class="text-danger">{{ $message }}</span>
                @enderror
                <div class="mt-3 position-relative" style="display: inline-block;">
                    <img id="image-preview"
                        src="{{ isset($donation) ? $donation->getFirstMediaUrl('donations') : '#' }}"
                        alt="Image Preview" class="img-thumbnail {{ isset($donation) ? '' : 'd-none' }}" width="300">
                    <button type="button" id="remove-image" class="btn btn-danger btn-sm d-none position-absolute"
                        style="top: 5px; right: 5px; border-radius: 50%; width: 25px; height: 25px; padding: 0; display: flex; align-items: center; justify-content: center;"
                        onclick="removeImage()">&times;</button>
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-block">
                {{ isset($donation) ? 'Update Donation' : 'Submit Donation' }}
            </button>
        </form>
        @endif
    </div>
</div>

<script>
    $('#summernote').summernote({
            tabsize: 2,
            height: 120,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
</script>
@endsection

<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const imgElement = document.getElementById('image-preview');
            const removeButton = document.getElementById('remove-image');
            imgElement.src = reader.result;
            imgElement.classList.remove('d-none'); // Menampilkan gambar preview
            removeButton.classList.remove('d-none'); // Menampilkan tombol remove
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    function removeImage() {
        const imgElement = document.getElementById('image-preview');
        const removeButton = document.getElementById('remove-image');
        const fileInput = document.getElementById('image');

        imgElement.src = '#';
        imgElement.classList.add('d-none'); // Sembunyikan gambar
        removeButton.classList.add('d-none'); // Sembunyikan tombol remove
        fileInput.value = ''; // Kosongkan input file
    }
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let targetAmountInput = document.getElementById("target_amount");
        let targetAmountDisplay = document.getElementById("target_amount_display");

        function formatRupiah(angka) {
            return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function cleanNumber(value) {
            return value.replace(/\D/g, ''); // Hanya angka
        }

        function updateDisplay() {
            let value = cleanNumber(targetAmountDisplay.value);
            targetAmountDisplay.value = value ? formatRupiah(value) : "";
            targetAmountInput.value = value; // Set ke input hidden
        }

        // Saat user mengetik di input display, update hidden input
        targetAmountDisplay.addEventListener("input", updateDisplay);
        targetAmountDisplay.addEventListener("change", updateDisplay);

        // Set tampilan saat halaman dimuat jika ada nilai lama
        if (targetAmountInput.value) {
            targetAmountDisplay.value = formatRupiah(targetAmountInput.value);
        }
    });
</script>
