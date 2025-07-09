@extends('layouts.app')

@section('title', 'Office Location Management')

@section('content')
    <h1>Office Location</h1>
    <hr>
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-end mb-3"> <a href="{{ route('office-locations.create') }}"
                    class="btn btn-primary"> <i class="fas fa-plus"></i> Tambah Data </a>
            </div>
            <div class="table-responsive">
                <table id="teamTable" class="table table-hover w-100 p-3">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Position</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#teamTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                autoWidth: false,
                lengthMenu: [5, 10, 25, 50],
                pagingType: "simple_numbers",
                ajax: "{{ route('office-locations.data') }}",
                columns: [{
                        data: 'id',
                        name: 'id',
                        orderable: true,
                        searchable: false
                    },
                    {
                        data: 'image',
                        name: 'image',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name',
                        orderable: true
                    },
                    {
                        data: 'address',
                        name: 'address',
                        orderable: true,
                        searchable: false
                    },
                    {
                        data: 'position',
                        name: 'position',
                        orderable: true,
                        searchable: false
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        orderable: true,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [
                    [3, 'desc']
                ]
            });
        });
    </script>
    <script>
        $(document).on('click', '.btn-delete', function(e) {
            e.preventDefault(); // Mencegah aksi default tombol

            var bannerId = $(this).data('id'); // Ambil ID dari data-id
            var form = $(this).closest('form'); // Ambil form yang terkait

            Swal.fire({
                title: 'Apakah Anda yakin ingin menghapus data ini?',
                text: "Anda tidak akan dapat mengembalikan data ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Tidak, batalkan!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Submit form setelah konfirmasi
                }
            });
        });
    </script>
@endpush
