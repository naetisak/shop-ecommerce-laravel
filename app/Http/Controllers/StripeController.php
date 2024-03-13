<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Product; // เพิ่มการอ้างอิงคลาส Product
use App\Models\UserAddress;
use App\Models\Variant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class StripeController extends Controller
{

    public function index()
    {
        $addresses = [];

        if (auth()->check()) {
            $addresses = UserAddress::where('user_id', auth()->user()->id)->get();
        }

        return view('cart', compact('addresses'));
    }

    public function apiCartProducts(Request $request)
    {
        $ids = explode(',', $request->ids);

        $data = Variant::with('color:id,code', 'size:id,code', 'product:id,title', 'product.oldestImage')->whereIn('id', $ids)->get();

        return response()->json($data);
    }

    public function apiApplyCoupon(Request $request)
    {
        $data = Coupon::where('code', $request->code)
            ->whereDate('from_valid', '<=', Carbon::now())
            ->where(function ($q) {
                $q->whereDate('till_valid', '>=', Carbon::now())
                    ->orWhereNull('till_valid');
            })->first();

        abort_if(!$data, 404, 'Invalid or Expired Coupon Code');

        return response()->json($data);
    }

    public function session(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        // ดึงข้อมูลสินค้าจากฐานข้อมูล
        $products = Product::all();


        // สร้างอาร์เรย์เพื่อเก็บไอเท็มแถว
        $lineItems = [];

        // วนลูปผ่านทุกสินค้า
        foreach ($products as $product) {
            // ถ้าคุณมีความสัมพันธ์ระหว่างโมเดล Product และ Variant
            foreach ($product->variant as $variants) {
                // เพิ่มไอเท็มแถวสำหรับแต่ละ Variant
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'thb',
                        'product_data' => [
                            'name' => $product->title, // ชื่อสินค้า
                        ],
                        'unit_amount' => $variants->selling_price * 100, // ใช้ราคาขายจริงของ Variant และแปลงเป็นเซ็นต์
                    ],
                    'quantity' => 1,
                ];
            }
        }

        // ตรวจสอบว่ามีไอเท็มแถวหรือไม่
        if (empty($lineItems)) {
            // จัดการกรณีที่ไม่มีไอเท็มแถว เช่น การเปลี่ยนเส้นทางหรือแสดงข้อความข้อผิดพลาด
        }

        // สร้างเซสชันสำหรับการชำระเงิน
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout_success'),
            'cancel_url' => route('checkout_cancel'),
        ]);

        // นำผู้ใช้ไปยังหน้าชำระเงินของ Stripe
        return redirect()->away($session->url);
    }

    public function success()
    {
        return view('checkout_success');
    }

    public function cancel()
    {
        return view('checkout_cancel');
    }
}
