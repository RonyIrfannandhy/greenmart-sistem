<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function createDynamic()
    {
        return view('products.create_dynamic'); 
    }
    public function storeDynamic(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'products' => 'required|array|min:1|max:5',
            'products.*.name' => 'required|string|max:255',
            'products.*.categories' => 'nullable|array|max:3',
            'products.*.categories.*.name' => 'required_with:products.*.categories|string|max:255',
            'products.*.categories.*.image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
        ], [
            'products.required' => 'Minimal harus ada satu produk.',
            'products.array' => 'Data produk harus berupa array.',
            'products.min' => 'Minimal harus ada satu produk.',
            'products.max' => 'Maksimal hanya 5 produk yang bisa ditambahkan.',
            'products.*.name.required' => 'Nama produk tidak boleh kosong (untuk produk ke-:position).',
            'products.*.name.string' => 'Nama produk harus berupa teks (untuk produk ke-:position).',
            'products.*.name.max' => 'Nama produk maksimal 255 karakter (untuk produk ke-:position).',

            'products.*.categories.array' => 'Data kategori harus berupa array (untuk produk ke-:position).',
            'products.*.categories.max' => 'Setiap produk maksimal memiliki 3 kategori (untuk produk ke-:position).',

            'products.*.categories.*.name.required_with' => 'Nama kategori tidak boleh kosong jika blok kategori ditambahkan (untuk produk ke-:position, kategori ke-:category_position).',
            'products.*.categories.*.name.string' => 'Nama kategori harus berupa teks.',
            'products.*.categories.*.name.max' => 'Nama kategori maksimal 255 karakter.',

            'products.*.categories.*.image.image' => 'File kategori harus berupa gambar.',
            'products.*.categories.*.image.mimes' => 'Format gambar kategori harus JPG, JPEG, atau PNG.',
            'products.*.categories.*.image.max' => 'Ukuran gambar kategori maksimal 2MB.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        try {
            if ($request->has('products')) {
                foreach ($request->products as $productData) {
                    // Buat produk baru
                    $newProduct = Product::create([
                        'name' => $productData['name']
                    ]);

                    // Jika ada kategori untuk produk ini
                    if (isset($productData['categories'])) {
                        foreach ($productData['categories'] as $categoryData) {
                            $imagePath = null;
                            // Jika ada file gambar yang diunggah untuk kategori ini
                            if (isset($categoryData['image']) && $categoryData['image']->isValid()) {
                                $imagePath = $categoryData['image']->store('category_images', 'public');
                            }

                            $newProduct->categories()->create([
                                'name' => $categoryData['name'],
                                'image_path' => $imagePath
                            ]);
                        }
                    }
                }
            }
            return redirect()->route('products.create.dynamic')->with('success', 'Produk dan kategori berhasil disimpan!');
        } catch (\Exception $e) {
            Log::error('Error saat menyimpan produk: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.')->withInput();
        }
    }
}