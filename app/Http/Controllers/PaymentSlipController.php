<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymentSlip;

class PaymentSlipController extends Controller
{
    public function upload(Request $request)
    {
        // ตรวจสอบว่ามีไฟล์ที่อัปโหลดมาหรือไม่
        if ($request->hasFile('payment_slip')) {
            // บันทึกไฟล์ภาพลงใน storage
            $file = $request->file('payment_slip');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('payment_slips', $fileName);

            // บันทึกข้อมูลลงในฐานข้อมูล
            $paymentSlip = new PaymentSlip();
            $paymentSlip->file_path = $fileName;
            $paymentSlip->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 400);
    }
}

