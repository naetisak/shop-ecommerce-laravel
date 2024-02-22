<?php

namespace App\Http\Controllers\Dpanel;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        $data = Banner::paginate(20);

        return view('dpanel.banner', compact('data'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:banners',
            'path' => 'required',
        ]);

        $data = new Banner();
        $data->name = $request->name;
        $data->link = $request->link;
        $data->path = $request->file('path')->store('media','public');
        $data->is_active = 1;
        $data->save();

        return back()->withSuccess('Created Successfully');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:banners,name,' . $id
        ]);

        $data =  Banner::find($id);
        $data->name = $request->name;
        $data->link = $request->link;
        $data->is_active = $request->is_active;
        if($request->path){
            Storage::disk('public')->delete($data->path);
            $data->path = $request->file('path')->store('media','public');
        }
        $data->save();

        return back()->withSuccess('Updated Successfully');
    }

    public function updateStatus($id, $status)
    {
        Banner::find($id)->update(['is_active' => $status]);

        return back()->withSuccess('Status change successfully');
    }
}
