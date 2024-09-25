<x-app-layout>
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12 mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="text-primary">Daftar Buku</h4>
                    <!-- Filter Dropdown -->
                    <select id="filterKategori" class="form-select w-auto">
                        <option value="">All Categories</option>
                        @foreach ($kategoris as $kategori)
                            <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-success" id="createNewBook">
                        <i class="fas fa-plus-circle me-2"></i> Tambah Buku
                    </button>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <table class="table table-striped table-bordered yajra-datatable">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Buku</th>
                                    <th>Kategori</th>
                                    <th>Gambar</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Create/Edit Book -->
    <div class="modal fade" id="bookModal" tabindex="-1" aria-labelledby="bookModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bookModalLabel">Tambah Buku</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="bookForm" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="book_id">
                        <div class="mb-3">
                            <label for="nama_buku" class="form-label">Nama Buku</label>
                            <input type="text" class="form-control" id="nama_buku" name="nama_buku" required>
                        </div>
                        <div class="mb-3">
                            <label for="kategori_id" class="form-label">Kategori</label>
                            <select class="form-select" id="kategori_id" name="kategori_id" required>
                                @foreach ($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="gambar" class="form-label">Gambar</label>
                            <input type="file" class="form-control" id="gambar" name="gambar">
                            <div id="currentImageContainer" class="mt-2">
                                <label>Gambar Saat Ini:</label><br>
                                <img id="currentImage" src="" alt="Current Book Image" style="width: 100px; height: auto; display: none;">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('css')
    <link rel="stylesheet" href="{{ asset('path/to/datatables.css') }}">
    <style>
        .table th, .table td {
            vertical-align: middle;
        }
        .modal-header {
            background-color: #007bff;
            color: white;
        }
        .btn-success {
            background-color: #28a745;
            border: none;
        }
        .btn-success:hover {
            background-color: #218838;
        }
        .btn-primary {
            background-color: #007bff;
        }
        .card {
            border-radius: 0.5rem;
        }
        .fas {
            margin-right: 5px;
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
                ajax: {
                    url: "{{ route('buku.data') }}",
                    data: function (d) {
                        d.kategori_id = $('#filterKategori').val();
                    }
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'nama_buku', name: 'nama_buku'},
                    {data: 'kategori.nama_kategori', name: 'kategori.nama_kategori'},
                    {
                        data: 'gambar', 
                        name: 'gambar',
                        render: function(data, type, row) {
                            return data ? `<img src="{{ asset('storage') }}/${data}" alt="Book Image" style="width: 100px; height: auto;">` : 'No Image';
                        }
                    },
                    {
                        data: 'action', 
                        name: 'action', 
                        orderable: false, 
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                                <button class="btn btn-sm btn-warning edit" data-id="${row.id}">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="btn btn-sm btn-danger delete" data-id="${row.id}">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </button>`;
                        }
                    },
                ]
            });

            $('#filterKategori').change(function () {
                table.ajax.reload(); 
            });

            $('#createNewBook').click(function () {
                $('#bookForm').trigger("reset");
                $('#book_id').val('');
                $('#currentImage').hide(); 
                $('#bookModalLabel').text("Tambah Buku");
                $('#bookModal').modal('show');
            });

            $('#bookForm').on('submit', function (e) {
                e.preventDefault();
                let formData = new FormData(this);
                let bookId = $('#book_id').val();
                let url = bookId ? "{{ route('buku.update', ':id') }}".replace(':id', bookId) : "{{ route('buku.store') }}";

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        $('#bookModal').modal('hide');
                        table.ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            title: 'Sukses!',
                            text: data.message,
                        });
                    },
                    error: function (xhr) {
                        let errorMessage = 'Error: ';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            for (const [key, value] of Object.entries(xhr.responseJSON.errors)) {
                                errorMessage += value.join(' ') + ' ';
                            }
                        } else {
                            errorMessage += xhr.responseJSON.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: errorMessage,
                        });
                    }
                });
            });

            $('body').on('click', '.edit', function () {
                let bookId = $(this).data('id');
                $.get("{{ route('buku.edit', ':id') }}".replace(':id', bookId), function (data) {
                    $('#bookModalLabel').text("Edit Buku");
                    $('#book_id').val(data.id);
                    $('#nama_buku').val(data.nama_buku);
                    $('#kategori_id').val(data.kategori_id);

                    if (data.gambar) {
                        $('#currentImage').attr('src', `{{ asset('storage') }}/${data.gambar}`);
                        $('#currentImage').show();
                    } else {
                        $('#currentImage').hide();
                    }
                    
                    $('#bookModal').modal('show');
                }).fail(function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Error retrieving book data.',
                    });
                });
            });

            $('body').on('click', '.delete', function () {
                let bookId = $(this).data('id');
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
                            url: "{{ route('buku.destroy', ':id') }}".replace(':id', bookId),
                            success: function (data) {
                                table.ajax.reload();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Terhapus!',
                                    text: data.message,
                                });
                            },
                            error: function (xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'Error: ' + xhr.responseJSON.message,
                                });
                            }
                        });
                    }
                });
            });

            $('#gambar').change(function (e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (event) {
                        $('#currentImage').attr('src', event.target.result).show();
                    };
                    reader.readAsDataURL(file);
                } else {
                    $('#currentImage').hide();
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
