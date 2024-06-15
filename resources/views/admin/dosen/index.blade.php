@extends('layouts.master')
@section('title', 'Data Dosen')

@section('content')
@include('sidebar.index')
<div style="min-height: 100vh;">
    <div class="container sticky-top">
        <header>
            <nav class="navbar navbar-expand-lg navbar-light bg-body mt-2 border-0 rounded shadow ">
                <div class="container-fluid">
                    <div>
                        <img class="navbar-brand mt-2" src="{{ asset('images/intech.png') }}" alt="image-intech" width="150px">
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="mx-2 mt-3">
                            <p class="fw-bold">{{ $user->name }}</p>
                        </div>
                        <div>
                            <img src="{{ asset('images/default_profile.png') }}" alt="user" class="rounded-circle" width="50px">
                        </div>
                    </div>
                </div>
            </nav>
        </header>
    </div>
    <div class="container">
        <div class="bg-body my-4 p-3 border-0 rounded shadow">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="my-2 fw-bold" style="color: #10439F;">Data Dosen</h3>
                </div>
                <div>
                    <a href="{{ route('admin_dosen.create') }}" class="btn btn-sm text-white rounded fw-bold" style="background-color: #10439F;">Tambah Dosen</a>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-start flex-wrap">
                <div class="table-responsive w-100">
                    @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <table class="table table-striped mt-3" id="datatable">
                        <thead class="text-center">
                            <tr>
                                <th>No</th>
                                <th>Image</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Kelas</th>
                                <th>Nomor Telepon</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        KTDatatablesDataSourceAjaxServer.init();
    });

    var table;
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        }
    });
    var KTDatatablesDataSourceAjaxServer = function() {
        var initTable1 = function() {
            table = $('#datatable');

            table = table.DataTable({
                paging: true,
                responsive: true,
                searchDelay: 500,
                processing: true,
                serverSide: false,
                "oLanguage": {
                    "sSearch": "Cari"
                },
                "searching": true,
                responsive: true,
                searchDelay: 500,
                processing: true,
                serverSide: true,
                "lengthMenu": [5, 10, 50, 100, 200, 500],
                ajax: {
                    url: "{{route('admin_dosen.datatable')}}",
                    type: "POST",
                },
                columns: [{
                        data: "DT_RowIndex",
                        name: "DT_RowIndex",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'image',
                        name: 'image',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row, meta) {
                            var imageSrc = data ? "{{ Storage::url('') }}" + data : "{{ asset('images/default_profile.png') }}";
                            return '<img src="' + imageSrc + '" alt="User Image" class="img-fluid rounded-circle" style="max-width: 50px; max-height: 50px;">';
                        }
                    },
                    {
                        data: 'name',
                        name: 'name',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'email',
                        name: 'email',
                        orderable: true,
                        searchable: true,
                        render: function(data, type, row, meta) {
                            return data.length > 8 ? data.substr(0, 8) + '...' : data;
                        }
                    },
                    {
                        data: 'kelas',
                        name: 'kelas',
                        orderable: true,
                        searchable: true,
                    },
                    {
                        data: 'no_telepon',
                        name: 'no_telepon',
                        orderable: true,
                        searchable: true,
                        render: function(data, type, row, meta) {
                            return data.length > 8 ? data.substr(0, 8) + '...' : data;
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                    },
                ],
                order: [
                    [1, 'asc']
                ],
            });
        };

        return {
            init: function() {
                initTable1();
            },
        };
    }();
    $('#datatable').on('click', '.btn-danger', function(e) {
        e.preventDefault();
        var form = $(this).closest('form');
        var id = form.data('id');

        if (confirm('Apakah Anda yakin ingin menghapus item ini?')) {
            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: {
                    _token: form.find('input[name="_token"]').val(),
                    _method: 'DELETE'
                },
                success: function(response) {
                    table.ajax.reload();
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }
    });
</script>
@endsection