<?php

namespace App\Http\Controllers\Dpanel;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $data = Category::paginate(20);

        return view('dpanel.category', compact('data'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories'
        ]);

        $data = new Category();
        $data->name = $request->name;
        $data->slug = Str::slug($request->name);
        $data->save();

        return back()->withSuccess('New Category Added Successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:categories,name,' . $id
        ]);

        $data =  Category::find($id);
        $data->name = $request->name;
        $data->slug = Str::slug($request->name);
        $data->is_active = $request->is_active;
        $data->save();

        return back()->withSuccess('Category Updated Successfully.');
    }
}
