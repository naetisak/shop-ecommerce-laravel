<?php

namespace App\Http\Controllers\Dpanel;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index(){
        $data = Brand::paginate(20);

        return view('dpanel.brand',compact('data'));
    }
    public function store(Request $request){

        $request->validate([
            'name'=>'required|unique:brands'
        ]);

        $data = new Brand();
        $data->name = $request->name;
        $data->slug = Str::slug($request->name);
        $data->is_active = true;
        $data->save();

        return back()->withSuccess('New Brand Added Successfully');
    }

    public function update(Request $request, $id){
        $request->validate([
            'name'=>'required|unique:brands,name,' . $id
        ]);

        $data = Brand::find($id);
        $data->name = $request->name;
        $data->slug = Str::slug($request->name);
        $data->is_active = $request->is_active;
        $data->save();

        return back()->withSuccess('Brand Updated Successfully');
    }
}
