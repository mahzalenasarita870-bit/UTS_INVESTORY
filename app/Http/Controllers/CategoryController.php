<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->get();
        return view('categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $columns = Schema::getColumnListing('categories');
        $nameField = in_array('nama_kategori', $columns) ? 'nama_kategori' : (in_array('nama', $columns) ? 'nama' : 'name');

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Category::create([
            $nameField => $request->name
        ]);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function update(Request $request, Category $category)
    {
        $columns = Schema::getColumnListing('categories');
        $nameField = in_array('nama_kategori', $columns) ? 'nama_kategori' : (in_array('nama', $columns) ? 'nama' : 'name');

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category->update([
            $nameField => $request->name
        ]);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus!');
    }
}