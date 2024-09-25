<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;
use DataTables;

class KategoriController extends Controller
{
    public function index()
    {
        return view('kategori');
    }

    public function getData(Request $req)
    {
        if ($req->ajax()) {
            $data = Kategori::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    $btn = '<a href="javascript:void(0)" class="edit btn btn-primary btn-sm" data-id="' . $row->id . '">Edit</a>';
                    $btn .= ' <a href="javascript:void(0)" class="delete btn btn-danger btn-sm" data-id="' . $row->id . '">Delete</a>';
                    return $btn;
                })                
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $request->validate(['nama_kategori' => 'required|string|max:255']);
        $kategori = Kategori::create($request->all());
        return response()->json(['message' => 'Kategori created successfully!', 'kategori' => $kategori]);
    }

    public function edit($id)
    {
        $kategori = Kategori::find($id);
        return response()->json($kategori);
    }

    public function update(Request $request, $id)
    {
        $request->validate(['nama_kategori' => 'required|string|max:255']);
        $kategori = Kategori::find($id);
        $kategori->update($request->all());
        return response()->json(['message' => 'Kategori updated successfully!']);
    }

    public function destroy($id)
    {
        Kategori::destroy($id);
        return response()->json(['message' => 'Kategori deleted successfully!']);
    }
}
