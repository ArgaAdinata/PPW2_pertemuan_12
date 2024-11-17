<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    @include('auth.layouts')
    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <h2>Detail Buku</h2>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        @if($buku->photo)
                            <img src="{{ route('buku.poto', $buku->photo) }}"
                                 alt="{{ $buku->judul }}"
                                 class="img-fluid">
                        @else
                            <p>Tidak ada gambar</p>
                        @endif
                    </div>
                    <div class="col-md-8">
                        <h3>{{ $buku->judul }}</h3>
                        <p><strong>Penulis:</strong> {{ $buku->penulis }}</p>
                        <p><strong>Harga:</strong> {{ "Rp. ".number_format($buku->harga, 2, ',', '.') }}</p>
                        <p><strong>Tanggal Terbit:</strong> {{ \Carbon\Carbon::parse($buku->tgl_terbit)->format('d-m-Y') }}</p>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="{{ url('/buku') }}" class="btn btn-secondary">Kembali</a>
                @auth
                    <a href="{{ route('buku.edit', $buku->id) }}" class="btn btn-primary">Edit</a>
                @endauth
            </div>
        </div>
    </div>
</body>
</html>
