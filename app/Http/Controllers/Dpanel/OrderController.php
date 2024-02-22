<?php

namespace App\Http\Controllers\Dpanel;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(){

        $data = Order::query()
                ->with('coupon:id,code')
                ->latest()
                ->paginate(10);

        return view('dpanel.orders', compact('data'));
    }

    public function show($id)
    {
        $order = Order::with([
            'items.variant.color:id,name,code',
            'items.variant.size:id,name,code',
            'items.variant.product:id,title',
            'items.variant.product.oldestImage',
        ])
            ->where('id', $id)
            ->first();

            
            return view('dpanel.order', compact('order'));
    }

    public function updateStatus($id, $status)
    {
        Order::find($id)->update(['status' => $status]);

        return back()->withSuccess('Status change successfully');
    }
}
