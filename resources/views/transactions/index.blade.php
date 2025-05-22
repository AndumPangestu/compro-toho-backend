@extends('layouts.app')

@section('title', 'Transaction Management')

@section('content')
    <h1>Transactions</h1>
    <hr>
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table id="transactionTable" class="table table-hover w-100 p-3">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>ID</th>
                            <th>Donation</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Amount</th>
                            <th>Transaction Status</th>
                            <th>Updated At</th>
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
            $('#transactionTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                autoWidth: false,
                lengthMenu: [5, 10, 25, 50],
                pagingType: "simple_numbers",
                ajax: "{{ route('transactions.data') }}",
                columns: [{
                        data: 'id',
                        name: 'id',
                        orderable: true,
                        searchable: false
                    },
                    {
                        data: 'donation',
                        name: 'donation',
                        orderable: true
                    },
                    {
                        data: 'name',
                        name: 'name',
                        orderable: true
                    },
                    {
                        data: 'email',
                        name: 'email',
                        orderable: true
                    },
                    {
                        data: 'amount',
                        name: 'amount',
                        orderable: true,
                        searchable: false
                    },
                    {
                        data: 'transaction_status',
                        name: 'transaction_status',
                        orderable: true
                    },
                    {
                        data: 'updated_at',
                        name: 'updated_at',
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
                    [6, 'desc']
                ]
            });
        });
    </script>
    <script>
        $(document).on('click', '.btn-delete', function(e) {
            e.preventDefault();
            var transactionId = $(this).data('id');
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
