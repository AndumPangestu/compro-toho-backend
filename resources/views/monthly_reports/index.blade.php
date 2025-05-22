@extends('layouts.app')

@section('title', 'Monthly Report Management')

@section('content')
    <h1>Monthly Reports</h1>
    <hr>
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-end mb-3">
                <a href="{{ route('monthly-reports.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add Monthly Report
                </a>
            </div>
            <div class="table-responsive">
                <table id="monthlyReportTable" class="table table-hover w-100 p-3">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Year</th>
                            <th>Month</th>
                            <th>Total Expenses</th>
                            <th>Category</th>
                            <th>File</th>
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
            $('#monthlyReportTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                autoWidth: false,
                lengthMenu: [5, 10, 25, 50],
                pagingType: "simple_numbers",
                ajax: "{{ route('monthly-reports.data') }}",
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
                        data: 'year',
                        name: 'year',
                        orderable: true
                    },
                    {
                        data: 'month',
                        name: 'month',
                        orderable: true
                    },
                    {
                        data: 'total_expenses',
                        name: 'total_expenses',
                        orderable: true
                    },
                    {
                        data: 'category',
                        name: 'category',
                        orderable: true
                    },
                    {
                        data: 'file',
                        name: 'report_file',
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
                    [2, 'desc'],
                    [3, 'desc']
                ]
            });
        });
    </script>

    <script>
        $(document).on('click', '.btn-delete', function(e) {
            e.preventDefault();
            var reportId = $(this).data('id');
            var form = $(this).closest('form');

            Swal.fire({
                title: 'Are you sure you want to delete this report?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    </script>
@endpush
