@extends('layouts.app')

@section('title', 'Contact Management')

@section('content')
    <h1>Contact</h1>
    <hr>
    <div class="card shadow mb-4">
        <div class="card-body">

            <div class="table-responsive">
                <table id="Contact" class="table table-hover w-100 p-3">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>ID</th>
                            <th>Created</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone Number</th>
                            <th>Subject</th>
                            <th>Message</th>
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
            $('#Contact').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                autoWidth: false,
                lengthMenu: [10, 25, 50],
                pagingType: "simple_numbers",
                ajax: "{{ route('contact.data') }}",
                columns: [{
                        data: 'id',
                        name: 'id',
                        orderable: true,
                        searchable: false
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        orderable: true,
                    },
                    {
                        data: 'name',
                        name: 'name',
                        orderable: true,
                    },
                    {
                        data: 'email',
                        name: 'email',
                        orderable: true,
                        searchable: false
                    },
                    {
                        data: 'phone_number',
                        name: 'phone_number',
                        orderable: true,
                        searchable: false
                    },
                    {
                        data: 'subject',
                        name: 'subject',
                        orderable: true,
                        searchable: false
                    },
                    {
                        data: 'message',
                        name: 'message',
                        orderable: false,
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
                    [4, 'desc']
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
