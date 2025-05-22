@extends('layouts.app')

@section('title', 'Donation Management')

@section('content')
    <h1>Donations</h1>
    <hr>
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-end mb-3">
                <a href="{{ route('donations.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Data
                </a>
            </div>
            <div class="table-responsive">
                <table id="donationTable" class="table table-hover w-100 p-3">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Target Amount</th>
                            <th>Collected Amount</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>location</th>
                            <th>Highlight</th>
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
            $('#donationTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                autoWidth: false,
                lengthMenu: [5, 10, 25, 50],
                pagingType: "simple_numbers",
                ajax: "{{ route('donations.data') }}",
                columns: [{
                        data: 'id',
                        name: 'id',
                        orderable: true,
                        searchable: false
                    },
                    {
                        data: 'title',
                        name: 'title',
                        orderable: true
                    },
                    {
                        data: 'category',
                        name: 'category',
                        orderable: true
                    },
                    {
                        data: 'target_amount',
                        name: 'target_amount',
                        orderable: true,
                        searchable: false
                    },
                    {
                        data: 'collected_amount',
                        name: 'collected_amount',
                        orderable: true,
                        searchable: false
                    },
                    {
                        data: 'start_date',
                        name: 'start_date',
                        orderable: true,
                        searchable: false
                    },
                    {
                        data: 'end_date',
                        name: 'end_date',
                        orderable: true,
                        searchable: false
                    },
                    {
                        data: 'location',
                        name: 'location',
                        orderable: true
                    },
                    {
                        data: 'put_on_highlight',
                        name: 'put_on_highlight',
                        orderable: true,
                        searchable: false
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        orderable: true,
                        searchable: false
                    },
                    // { data: 'images', name: 'images', orderable: false, searchable: false, render: function(data) {
                    //         return data.map(img => `<img src="${img}" class="img-thumbnail" width="50">`).join(' ');
                    //     }
                    // },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [
                    [9, 'desc']
                ]
            });
        });
    </script>

    <script>
        $(document).on('click', '.btn-delete', function(e) {
            e.preventDefault();
            var donationId = $(this).data('id');
            var form = $(this).closest('form');

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
                    form.submit();
                }
            });
        });
    </script>
@endpush
