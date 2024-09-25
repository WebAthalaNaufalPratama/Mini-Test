<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\Buku;
use DataTables;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class BukuController extends Controller
{

    public function getCounts(): JsonResponse
    {
        $categoryCount = Kategori::count();
        $bookCount = Buku::count();

        return response()->json([
            'categories' => $categoryCount,
            'books' => $bookCount,
        ]);
    }
    public function index()
    {
        $kategoris = Kategori::all();
        return view('buku', compact('kategoris'));
    }

    public function getData(Request $req)
    {
        $query = Buku::with('kategori'); 
        if ($req->has('kategori_id') && $req->kategori_id != '') {
            $query->where('kategori_id', $req->kategori_id);
        }

        $data = $query->latest()->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row) {
                $btn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm" data-id="'.$row->id.'">Edit</a>';
                $btn .= ' <a href="javascript:void(0)" class="delete btn btn-danger btn-sm" data-id="'.$row->id.'">Delete</a>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }


    public function store(Request $request)
    {
        $request->validate([
            'nama_buku' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust as necessary
        ]);

        $book = new Buku();
        $book->nama_buku = $request->nama_buku;
        $book->kategori_id = $request->kategori_id;

        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('images', 'public');
            $book->gambar = $path;
        }

        $book->save();

        return response()->json(['message' => 'Buku berhasil ditambahkan']);
    }

    // Update method
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_buku' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust as necessary
        ]);

        $book = Buku::findOrFail($id);
        $book->nama_buku = $request->nama_buku;
        $book->kategori_id = $request->kategori_id;

        if ($request->hasFile('gambar')) {
            // Optionally delete the old image
            if ($book->gambar) {
                Storage::disk('public')->delete($book->gambar);
            }

            $path = $request->file('gambar')->store('images', 'public');
            $book->gambar = $path;
        }

        $book->save();

        return response()->json(['message' => 'Buku berhasil diperbarui']);
    }

    public function edit($id)
    {
        $buku = Buku::find($id);
        return response()->json($buku);
    }


    public function destroy($id)
    {
        $buku = Buku::find($id);
        if ($buku) {
            $buku->delete();
            return response()->json(['message' => 'Buku berhasil dihapus!']);
        } else {
            return response()->json(['message' => 'Buku tidak ditemukan!'], 404);
        }
    }
}
