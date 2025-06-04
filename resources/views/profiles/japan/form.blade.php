@extends('layouts.app')

@section('title', 'Japan Profile Management')

@push('styles')
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet">
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<style>
    .image-preview {
        position: relative;
        margin-right: 10px;
    }

    .image-preview img {
        width: 150px;
        height: auto;
    }

    .remove-btn {
        position: absolute;
        top: 5px;
        right: 5px;
        background-color: red;
        color: white;
        border: none;
        border-radius: 50%;
        width: 25px;
        height: 25px;
        font-weight: bold;
        cursor: pointer;
    }
</style>
@endpush

@section('content')

<h1>Profile</h1>
<hr>
<div class="card shadow mb-4">
    <div class="card-body">
        <form method="POST" action="{{ route('profiles.japan.store') }}" enctype="multipart/form-data">
            @csrf

            {{-- Name --}}
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" name="name" id="name"
                    value="{{ old('name', $profileData->name ?? '') }}" required>
                @error('name') <span class="text-danger"><strong>{{ $message }}</strong></span> @enderror
            </div>

            {{-- Description --}}
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" name="description" id="description" rows="3"
                    required>{{ old('description', $profileData->description ?? '') }}</textarea>
                @error('description') <span class="text-danger"><strong>{{ $message }}</strong></span> @enderror
            </div>

            {{-- Established --}}
            <div class="form-group">
                <label for="established">Established Date</label>
                <input type="date" class="form-control" name="established" id="established"
                    value="{{ old('established', $profileData->established ?? '') }}" required>
                @error('established') <span class="text-danger"><strong>{{ $message }}</strong></span> @enderror
            </div>

            {{-- Address --}}
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" class="form-control" name="address" id="address"
                    value="{{ old('address', $profileData->address ?? '') }}" required>
                @error('address') <span class="text-danger"><strong>{{ $message }}</strong></span> @enderror
            </div>

            {{-- Employees --}}
            <div class="form-group">
                <label for="employees">Total Employees</label>
                <input type="number" class="form-control" name="employees" id="employees"
                    value="{{ old('employees', $profileData->employees ?? '') }}" required>
                @error('employees') <span class="text-danger"><strong>{{ $message }}</strong></span> @enderror
            </div>

            {{-- Chairman --}}
            <div class="form-group">
                <label for="chairman">Chairman</label>
                <input type="text" class="form-control" name="chairman" id="chairman"
                    value="{{ old('chairman', $profileData->chairman ?? '') }}" required>
                @error('chairman') <span class="text-danger"><strong>{{ $message }}</strong></span> @enderror
            </div>

            {{-- President --}}
            <div class="form-group">
                <label for="president">President</label>
                <input type="text" class="form-control" name="president" id="president"
                    value="{{ old('president', $profileData->president ?? '') }}" required>
                @error('president') <span class="text-danger"><strong>{{ $message }}</strong></span> @enderror
            </div>

            {{-- Domestic Group --}}
            <div class="form-group">
                <label>Domestic Group</label>
                <div id="domestic-group-container">
                    @php
                    $domesticGroups = old('domestic_group') ?? ($profileData->domestic_group ?? [['name' => '']]);
                    @endphp
                    @foreach ($domesticGroups as $index => $group)
                    <div class="input-group mb-2">
                        <input type="text" name="domestic_group[{{ $index }}][name]" class="form-control"
                            placeholder="Group Name" value="{{ old(" domestic_group.$index.name") ?? ($group['name'] ??
                            $group->name ?? '') }}" required>
                        <div class="input-group-append">
                            @if ($loop->first)
                            <button class="btn btn-success" type="button" onclick="addDomesticGroup()">+</button>
                            @else
                            <button class="btn btn-danger" type="button"
                                onclick="this.parentElement.parentElement.remove()">−</button>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Overseas Group --}}
            <div class="form-group">
                <label>Overseas Group</label>
                <div id="overseas-group-container">
                    @php
                    $overseasGroups = old('overseas_group') ?? ($profileData->overseas_group ?? [['name' => '']]);
                    @endphp
                    @foreach ($overseasGroups as $index => $group)
                    <div class="input-group mb-2">
                        <input type="text" name="overseas_group[{{ $index }}][name]" class="form-control"
                            placeholder="Group Name" value="{{ old(" overseas_group.$index.name") ?? ($group['name'] ??
                            $group->name ?? '') }}" required>
                        <div class="input-group-append">
                            @if ($loop->first)
                            <button class="btn btn-success" type="button" onclick="addOverseasGroup()">+</button>
                            @else
                            <button class="btn btn-danger" type="button"
                                onclick="this.parentElement.parentElement.remove()">−</button>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Upload Image --}}
            <div class="form-group">
                <label for="image">Upload Images</label>
                <input type="file" class="form-control-file" name="image[]" id="image" accept="image/*" multiple
                    onchange="previewImages(event)">
                @error('image') <span class="text-danger"><strong>{{ $message }}</strong></span> @enderror
                @error('image.*') <span class="text-danger"><strong>{{ $message }}</strong></span> @enderror
            </div>

            {{-- Image Preview --}}
            <div id="image-preview-container" class="d-flex flex-wrap gap-2 mt-3">
                @if (isset($profile) && $profile->getMedia('japan_profiles')->isNotEmpty())
                @foreach ($profile->getMedia('japan_profiles') as $media)
                <div class="image-preview position-relative">
                    <img class="img-thumbnail" src="{{ $media->getFullUrl() }}" alt="Image" style="max-width: 150px;">
                </div>
                @endforeach
                @endif
            </div>
            <button type="submit" class="btn btn-primary btn-block">Update Profile</button>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let domesticIndex = {{ count($domesticGroups) }};
    let overseasIndex = {{ count($overseasGroups) }};

    function addDomesticGroup() {
        const container = document.getElementById('domestic-group-container');
        const group = document.createElement('div');
        group.classList.add('input-group', 'mb-2');
        group.innerHTML = `
            <input type="text" name="domestic_group[${domesticIndex}][name]" class="form-control" placeholder="Group Name" required>
            <div class="input-group-append">
                <button class="btn btn-danger" type="button" onclick="this.parentElement.parentElement.remove()">−</button>
            </div>
        `;
        container.appendChild(group);
        domesticIndex++;
    }

    function addOverseasGroup() {
        const container = document.getElementById('overseas-group-container');
        const group = document.createElement('div');
        group.classList.add('input-group', 'mb-2');
        group.innerHTML = `
            <input type="text" name="overseas_group[${overseasIndex}][name]" class="form-control" placeholder="Group Name" required>
            <div class="input-group-append">
                <button class="btn btn-danger" type="button" onclick="this.parentElement.parentElement.remove()">−</button>
            </div>
        `;
        container.appendChild(group);
        overseasIndex++;
    }

    function previewImages(event) {
        const files = event.target.files;
        const container = document.getElementById('image-preview-container');
        container.innerHTML = '';

        Array.from(files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function (e) {


                const previewWrapper = document.createElement('div');
                previewWrapper.classList.add('image-preview');

                const img = document.createElement('img');
                img.classList.add('img-thumbnail');
                img.src = e.target.result;

                const removeBtn = document.createElement('button');
                removeBtn.innerText = '×';
                removeBtn.classList.add('remove-btn');
                removeBtn.onclick = () => {
                    previewWrapper.remove();
                    document.getElementById('image').value = '';
                    container.innerHTML = '';
                };

                previewWrapper.appendChild(img);
                previewWrapper.appendChild(removeBtn);
                container.appendChild(previewWrapper);
            };

            reader.readAsDataURL(file);
        });
    }
</script>
@endpush
