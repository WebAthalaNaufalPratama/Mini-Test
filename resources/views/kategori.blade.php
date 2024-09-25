<x-app-layout>
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12 mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="text-primary fw-bold">Daftar Kategori</h4>
                    <button class="btn btn-success" id="createNewKategori">
                        <i class="fas fa-plus-circle me-2"></i> Tambah Kategori
                    </button>
                </div>
            </div>

            <!-- Main Card for the Table -->
            <div class="col-lg-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Kategori</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover yajra-datatable">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Kategori</th>
                                    <th style="width: 15%;">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Create/Edit Kategori -->
    <div class="modal fade" id="kategoriModal" tabindex="-1" aria-labelledby="kategoriModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="kategoriModalLabel">Tambah Kategori</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="kategoriForm">
                        @csrf
                        <input type="hidden" id="kategori_id">
                        <div class="mb-3">
                            <label for="nama_kategori" class="form-label">Nama Kategori</label>
                            <input type="text" class="form-control" id="nama_kategori" name="nama_kategori" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save me-2"></i> Simpan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('css')
    <link rel="stylesheet" href="{{ asset('path/to/datatables.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"> <!-- Font Awesome Icons -->
    <style>
        .table th, .table td {
            vertical-align: middle;
        }
        .modal-header {
            background-color: #007bff;
            color: white;
        }
        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.1);
        }
        .btn-success {
            background-color: #28a745;
            border: none;
            transition: background-color 0.3s ease;
        }
        .btn-success:hover {
            background-color: #218838;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .btn-close {
            background-color: transparent;
            border: none;
        }
    </style>
    @endpush

    @push('script')
    <script src="{{ asset('path/to/jquery.js') }}"></script>
    <script src="{{ asset('path/to/datatables.js') }}"></script>

    <script>
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            let table = $('.yajra-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('kategori.data') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'nama_kategori', name: 'nama_kategori'},
                    {
                        data: 'action', 
                        name: 'action', 
                        orderable: false, 
                        searchable: false,
                        render: function (data, type, row) {
                            return `
                                <button class="btn btn-warning btn-sm edit" data-id="${row.id}">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="btn btn-danger btn-sm delete" data-id="${row.id}">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </button>`;
                        }
                    }
                ]
            });

            $('#createNewKategori').click(function () {
                $('#kategoriForm').trigger("reset");
                $('#kategori_id').val('');
                $('#kategoriModalLabel').text("Tambah Kategori");
                $('#kategoriModal').modal('show');
            });

            $('#kategoriForm').on('submit', function (e) {
                e.preventDefault();
                let formData = new FormData(this);
                let kategoriId = $('#kategori_id').val();
                let url = kategoriId ? "{{ route('kategori.update', ':id') }}".replace(':id', kategoriId) : "{{ route('kategori.store') }}";

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        $('#kategoriModal').modal('hide');
                        table.ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            title: 'Sukses!',
                            text: data.message,
                        });
                    },
                    error: function (data) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: "Error: " + data.responseJSON.message,
                        });
                    }
                });
            });

            // Edit Kategori
            $('body').on('click', '.edit', function () {
                let kategoriId = $(this).data('id');
                $.get("{{ route('kategori.edit', ':id') }}".replace(':id', kategoriId), function (data) {
                    $('#kategoriModalLabel').text("Edit Kategori");
                    $('#kategori_id').val(data.id);
                    $('#nama_kategori').val(data.nama_kategori);
                    $('#kategoriModal').modal('show');
                }).fail(function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Error retrieving category data.',
                    });
                });
            });

            // Delete Kategori
            $('body').on('click', '.delete', function () {
                let kategoriId = $(this).data("id");
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data ini akan dihapus!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "DELETE",
                            url: "{{ route('kategori.index') }}" + '/' + kategoriId,
                            success: function (data) {
                                table.ajax.reload();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Terhapus!',
                                    text: data.message,
                                });
                            },
                            error: function (data) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: "Error: " + data.responseJSON.message,
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
    @endpush
</x-app-layout>
