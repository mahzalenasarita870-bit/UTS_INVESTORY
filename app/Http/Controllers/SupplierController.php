<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::all();
        return view('suppliers.index', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $columns = Schema::getColumnListing('suppliers');
        $addressInput = $request->input('address') ?? $request->input('alamat') ?? '';

        $dataToSave = [];

        // Isi Nama Supplier
        foreach (['name', 'nama_supplier', 'nama'] as $col) {
            if (in_array($col, $columns)) {
                $dataToSave[$col] = $request->input('name') ?? $request->input('nama_supplier');
            }
        }

        // Isi Phone
        foreach (['phone', 'no_hp', 'telepon', 'no_telp'] as $col) {
            if (in_array($col, $columns)) {
                $dataToSave[$col] = $request->input('phone') ?? $request->input('no_hp');
            }
        }

        // Isi Alamat ke SEMUA kolom alamat yang ada di tabel
        foreach (['address', 'alamat', 'lokasi', 'deskripsi'] as $col) {
            if (in_array($col, $columns)) {
                $dataToSave[$col] = $addressInput;
            }
        }

        Supplier::create($dataToSave);

        return redirect()->route('suppliers.index')->with('success', 'Data supplier berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);
        $columns = Schema::getColumnListing('suppliers');
        $addressInput = $request->input('address') ?? $request->input('alamat') ?? '';

        $dataToUpdate = [];

        foreach (['name', 'nama_supplier', 'nama'] as $col) {
            if (in_array($col, $columns)) {
                $dataToUpdate[$col] = $request->input('name') ?? $request->input('nama_supplier');
            }
        }

        foreach (['phone', 'no_hp', 'telepon', 'no_telp'] as $col) {
            if (in_array($col, $columns)) {
                $dataToUpdate[$col] = $request->input('phone') ?? $request->input('no_hp');
            }
        }

        foreach (['address', 'alamat', 'lokasi', 'deskripsi'] as $col) {
            if (in_array($col, $columns)) {
                $dataToUpdate[$col] = $addressInput;
            }
        }

        $supplier->update($dataToUpdate);

        return redirect()->route('suppliers.index')->with('success', 'Data supplier berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();

        return redirect()->route('suppliers.index')->with('success', 'Data supplier berhasil dihapus!');
    }
}