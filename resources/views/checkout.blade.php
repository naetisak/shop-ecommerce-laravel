<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stripe Checkout</title>
    <script src="https://checkout.stripe.com/checkout.js"></script>
</head>
<body>

<button id="checkout-button">Checkout</button>

<script>
    document.getElementById('checkout-button').addEventListener('click', function() {
        var handler = StripeCheckout.configure({
            key: "{{ config('services.stripe.key') }}",
            image: "https://stripe.com/img/documentation/checkout/marketplace.png",
            locale: "auto",
            token: function(token) {
                // เมื่อชำระเงินสำเร็จ ให้ส่ง token ไปยังเซิร์ฟเวอร์ของคุณเพื่อดำเนินการต่อ
                fetch("{{ route('checkout') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        token: token.id,
                        amount: 5000, // จำนวนเงินในเซ็นต์ต
                        currency: "usd"
                    })
                })
                .then(response => {
                    // ประมวลผลตอบกลับ
                    if (response.ok) {
                        // ถ้าชำระเงินสำเร็จ
                        // นำผู้ใช้ไปยังหน้า Success
                        window.location.href = "{{ route('checkout.success') }}";
                    } else {
                        // ถ้ามีข้อผิดพลาดในการชำระเงิน
                        // นำผู้ใช้ไปยังหน้า Error หรือกลับไปยังหน้า Checkout
                        window.location.href = "{{ route('checkout.error') }}";
                    }
                });
            }
        });

        // เปิด Stripe Checkout ในหน้าต่าง Popup
        handler.open({
            name: "Your Company Name",
            description: "Product or Service Description",
            amount: 5000, // จำนวนเงินในเซ็นต์ต
            currency: "usd"
        });
    });
</script>

</body>
</html>
