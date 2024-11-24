<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>
<body>
    @include('auth.layouts')
    <div class="container mt-4">
        <div class="d-flex justify-content-between mb-3">
            <h1>Daftar Buku</h1>
            @auth
                <a href="{{ route('buku.create')}}" class="btn btn-primary">Tambah Buku</a>
            @endauth
        </div>

        <table id="bukuTable" class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Id</th>
                    <th>Judul Buku</th>
                    <th>Penulis</th>
                    <th>Harga</th>
                    <th>Tanggal Terbit</th>
                    @auth
                        <th>Aksi</th>
                    @endauth
                </tr>
            </thead>
            <tbody id="booksList">
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            fetch('/api/books')
                .then(response => response.json())
                .then(result => {
                    const booksList = document.getElementById('booksList');
                    result.data.data.forEach((book, index) => {
                        const date = new Date(book.tgl_terbit);
                        const formattedDate = date.getDate().toString().padStart(2, '0') + '-' +
                                            (date.getMonth() + 1).toString().padStart(2, '0') + '-' +
                                            date.getFullYear();

                        let row = `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${book.judul}</td>
                                <td>${book.penulis}</td>
                                <td>Rp ${parseFloat(book.harga).toLocaleString('id-ID', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                                <td>${formattedDate}</td>
                        `;

                        @auth
                            row += `
                                <td>
                                    <form action="/buku/${book.id}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Yakin mau di hapus?')"
                                        type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                    <a href="/buku/${book.id}/edit" class="btn btn-primary btn-sm">Update</a>
                                    <a href="/buku/${book.id}" class="btn btn-info btn-sm">Detail</a>
                                </td>
                            `;
                        @endauth

                        row += `</tr>`;
                        booksList.innerHTML += row;
                    });

                    $('#bukuTable').DataTable({
                        "language": {
                            "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/id.json"
                        },
                        "pageLength": 10,
                        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
                        "columnDefs": [
                            @auth
                                { "orderable": false, "targets": 5 }
                            @endauth
                        ]
                    });
                })
                .catch(error => console.error('Error:', error));
        });
    </script>
</body>
</html>
