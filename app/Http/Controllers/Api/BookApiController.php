<?php

namespace App\Http\Controllers\Api;

use App\Models\Buku;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\BookResource;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class BookApiController extends Controller
{
    public function index()
    {
        $books = Buku::latest()->paginate(5);
        return new BookResource(true, 'List Data Buku', $books);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required',
            'penulis' => 'required',
            'harga' => 'required|numeric',
            'tgl_terbit' => 'required|date'
        ]);

        if ($validator->fails()) {
            return new BookResource(false, 'Validasi Error', $validator->errors());
        }

        $book = Buku::create($request->all());
        return new BookResource(true, 'Data Buku Berhasil Ditambahkan', $book);
    }

    public function show($id)
    {
        $book = Buku::find($id);
        if ($book) {
            return new BookResource(true, 'Detail Data Buku', $book);
        }
        return new BookResource(false, 'Data Buku Tidak Ditemukan', null);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required',
            'penulis' => 'required',
            'harga' => 'required|numeric',
            'tgl_terbit' => 'required|date'
        ]);

        if ($validator->fails()) {
            return new BookResource(false, 'Validasi Error', $validator->errors());
        }

        $book = Buku::find($id);
        if ($book) {
            $book->update($request->all());
            return new BookResource(true, 'Data Buku Berhasil Diupdate', $book);
        }
        return new BookResource(false, 'Data Buku Tidak Ditemukan', null);
    }

    public function destroy($id)
    {
        $book = Buku::find($id);
        if ($book) {
            $book->delete();
            return new BookResource(true, 'Data Buku Berhasil Dihapus', null);
        }
        return new BookResource(false, 'Data Buku Tidak Ditemukan', null);
    }
}
