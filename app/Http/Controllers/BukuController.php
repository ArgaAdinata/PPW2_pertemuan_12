<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class BukuController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index()
    {
        $data_buku = Buku::all();
        return view('buku.index', compact('data_buku'));
    }

    public function search(Request $request)
    {
        Paginator::useBootstrapFive();
        $batas = 5;
        $cari = $request->kata;
        $data_buku = Buku::where('judul', 'like', "%" . $cari . "%")
            ->orwhere('penulis', 'like', "%" . $cari . "%")
            ->paginate($batas);
        $jumlah_buku = Buku::count();
        $no = $batas * ($data_buku->currentPage() - 1);
        return view('buku.index', compact('jumlah_buku', 'data_buku', 'no', 'cari'));
    }

    public function create()
    {
        return view('buku.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string',
            'penulis' => 'required|string|max:30',
            'harga' => 'required|numeric',
            'tgl_terbit' => 'required|date',
            'photo' => 'image|nullable|max:1999',
        ]);

        if($request->hasFile('photo')) {
            $filenameWithExt = $request->file('photo')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('photo')->getClientOriginalExtension();
            $filenameSimpan = $filename . '_' . time() . '.' . $extension;
            $filenameSquare = $filename . '_square_' . time() . '.' . $extension;

            $request->file('photo')->storeAs('photos', $filenameSimpan);
            
            $manager = new ImageManager(new Driver());
            $image = $manager->read($request->file('photo'));
            $image->cover(300, 300);
            Storage::put('photos/' . $filenameSquare, $image->toJpeg());
        }

        $buku = new Buku();
        $buku->judul = $request->judul;
        $buku->penulis = $request->penulis;
        $buku->harga = $request->harga;
        $buku->tgl_terbit = $request->tgl_terbit;
        $buku->photo = $filenameSimpan ?? null;
        $buku->photo_square = $filenameSquare ?? null;
        $buku->save();

        return redirect('/buku')->with('pesan', 'Data Buku Berhasil Disimpan');
    }

    public function edit(string $id)
    {
        $buku = Buku::findOrFail($id);
        return view('buku.edit', compact('buku'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'judul' => 'required|string',
            'penulis' => 'required|string|max:30',
            'harga' => 'required|numeric',
            'tgl_terbit' => 'required|date',
        ]);

        $buku = Buku::findOrFail($id);
        $buku->judul = $request->judul;
        $buku->penulis = $request->penulis;
        $buku->harga = $request->harga;
        $buku->tgl_terbit = $request->tgl_terbit;
        $buku->save();

        return redirect('/buku')->with('pesan', 'Data Buku Berhasil Diupdate');
    }

    public function destroy(string $id)
    {
        $buku = Buku::findOrFail($id);
        $buku->delete();

        return redirect('/buku')->with('pesan', 'Data Buku Berhasil Dihapus');
    }

    public function show($id)
    {
        $buku = Buku::findOrFail($id);
        return view('buku.show', compact('buku'));
    }

    public function getPhoto(string $filename)
    {
        return Storage::get('photos/' . $filename);
    }

}
