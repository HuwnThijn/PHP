<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cosmetic;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CosmeticController extends Controller
{
    public function index()
    {
        $cosmetics = Cosmetic::with('category')->paginate(10);
        return view('admin.cosmetics.index', compact('cosmetics'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.cosmetics.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'id_category' => 'required|exists:categories,id_category',
            'rating' => 'nullable|numeric|min:0|max:5',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        $data['isHidden'] = $request->has('isHidden') ? 1 : 0;
        
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = 'cosmetic_' . time() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('public/cosmetics', $filename);
            $data['image'] = 'cosmetics/' . $filename;
        }

        Cosmetic::create($data);
        
        return redirect()->route('admin.cosmetics.index')->with('success', 'Mỹ phẩm đã được tạo thành công.');
    }

    public function edit($id)
    {
        $cosmetic = Cosmetic::findOrFail($id);
        $categories = Category::all();
        
        // Nếu là request AJAX, trả về JSON response
        if (request()->ajax()) {
            return response()->json([
                'cosmetic' => $cosmetic,
                'categories' => $categories
            ]);
        }
        
        return view('admin.cosmetics.edit', compact('cosmetic', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $cosmetic = Cosmetic::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'id_category' => 'required|exists:categories,id_category',
            'rating' => 'nullable|numeric|min:0|max:5',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        $data['isHidden'] = $request->has('isHidden') ? 1 : 0;
        
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            if ($cosmetic->image) {
                Storage::delete('public/' . $cosmetic->image);
            }
            
            $image = $request->file('image');
            $filename = 'cosmetic_' . time() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('public/cosmetics', $filename);
            $data['image'] = 'cosmetics/' . $filename;
        }

        $cosmetic->update($data);
        
        return redirect()->route('admin.cosmetics.index')->with('success', 'Mỹ phẩm đã được cập nhật thành công.');
    }

    public function destroy($id)
    {
        $cosmetic = Cosmetic::findOrFail($id);
        
        // Xóa ảnh khi xóa mỹ phẩm
        if ($cosmetic->image) {
            Storage::delete('public/' . $cosmetic->image);
        }
        
        $cosmetic->delete();
        
        return redirect()->route('admin.cosmetics.index')->with('success', 'Mỹ phẩm đã được xóa thành công.');
    }
}
