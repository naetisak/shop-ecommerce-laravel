<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Address; // เพิ่มการอ้างอิงคลาส Address

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'Product Name',
                        ],
                        'unit_amount' => 5000,
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => route('checkout_success'),
            'cancel_url' => route('checkout_cancel'),
        ]);

        return redirect($session->url);
    }

    public function success()
    {
        return view('checkout_success');
    }

    public function cancel()
    {
        return view('checkout_cancel');
    }

    public function store(Request $request)
    {
        // ตรวจสอบข้อมูลที่ส่งมา
        $request->validate([
            'address_id' => 'required|exists:addresses,id', // ตรวจสอบว่า address_id มีอยู่ในตาราง addresses หรือไม่
            'payment_slip' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // ตรวจสอบไฟล์รูปภาพที่ส่งมา
        ]);

        // ดึงข้อมูลที่ต้องการจาก request
        $addressId = $request->input('address_id');
        $paymentSlip = $request->file('payment_slip');

        // สร้างรหัสสำหรับ payment_slip
        $paymentSlipPath = $paymentSlip->store('payment_slips');

        // สร้างคำสั่งซื้อใหม่
        $order = new Order();
        $order->user_address_id = $addressId; // แก้ไขตรงนี้เป็น user_address_id
        $order->user_id = auth()->id(); // สำหรับระบบยืนยันตัวตน
        $order->payment_slip = $paymentSlipPath; // เก็บที่อยู่ของสลิปการชำระเงิน
        $order->save();

        // แสดงข้อความสำเร็จด้วย Session
        session()->flash('success', 'Your order has been placed successfully. Thank you for your purchase!');

        // ส่งผู้ใช้งานไปยังหน้า checkout_success
        return redirect()->route('checkout_success');
    }


}
