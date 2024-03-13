@extends('layouts.app')

@push('scripts')
    <x-url-generator-js /> <!-- เรียกใช้งานคอมโพเนนต์ x-url-generator-js -->
    <script>
        let currentImage = 0; // กำหนดตัวแปร currentImage สำหรับเก็บค่าลำดับรูปภาพปัจจุบัน
        let color_id = '{{ request()->c ?? null }}'; // รับค่า parameter c จาก URL query string หากไม่มีให้กำหนดเป็น null
        let size_id = '{{ request()->s ?? null }}'; // รับค่า parameter s จาก URL query string หากไม่มีให้กำหนดเป็น null

        // ฟังก์ชันสำหรับเลือกสี
        const selectColor = (cid) => {
            c = color_id ? null : cid; // ตรวจสอบว่ามีการเลือกสีหรือไม่ หากมีให้เลือกสีอื่นๆ หากไม่มีให้กำหนดเป็น null
            window.location.href = generateUrl({ c }); // เรียกใช้งานฟังก์ชัน generateUrl เพื่อสร้าง URL ใหม่
        }

        // ฟังก์ชันสำหรับเลือกขนาด
        const selectSize = (sid) => {
            s = size_id ? null : sid; // ตรวจสอบว่ามีการเลือกขนาดหรือไม่ หากมีให้เลือกขนาดอื่นๆ หากไม่มีให้กำหนดเป็น null
            window.location.href = generateUrl({ s }); // เรียกใช้งานฟังก์ชัน generateUrl เพื่อสร้าง URL ใหม่
        }

        // ฟังก์ชันสำหรับดูภาพสินค้า
        const viewImage = (e, index) => {
            currentImage = index; // กำหนดค่า currentImage เป็นลำดับภาพปัจจุบัน
            document.getElementById('bigImage').src = e.querySelector('img').src; // แสดงภาพใหญ่ที่เลือกด้วย currentImage
        }

        // ฟังก์ชันสำหรับการเลื่อนภาพถัดไปหรือก่อนหน้า
        const nextPrevious = (index) => {
            i = currentImage + index; // คำนวณค่าลำดับภาพถัดไปหรือก่อนหน้า
            let images = document.getElementById('images').querySelectorAll('img'); // เลือกภาพทั้งหมด
            if (i >= images.length || i < 0) return; // ตรวจสอบว่าค่าลำดับภาพเกินขอบเขตหรือไม่
            currentImage = i; // กำหนดค่า currentImage ใหม่
            let arr = [];
            images.forEach(element => arr.push(element.src)); // สร้างอาเรย์เก็บ URL ของภาพ
            document.getElementById('bigImage').src = arr[currentImage]; // แสดงภาพใหญ่ที่เลือกด้วย currentImage
        }

        // ฟังก์ชันสำหรับเพิ่มสินค้าลงในตะกร้า
        const addToCart = () => {
            let count = '{{ $product->variant->count() }}'; // นับจำนวนตัวเลือกสินค้า
            if (count != 1) { // ถ้าไม่มีตัวเลือกเพียงตัวเดียว
                cuteToast({
                    type: 'info',
                    message: 'Please select color & size' // แสดงข้อความเตือนให้เลือกสีและขนาด
                })
                return;
            }
            let variantId = '{{ $product->variant[0]->id }}'; // รหัสตัวเลือกสินค้า
            if (!mCart.isInCart(variantId)) { // ตรวจสอบว่าสินค้าอยู่ในตะกร้าแล้วหรือไม่
                mCart.add(variantId, 1); // เพิ่มสินค้าลงในตะกร้า
                cuteToast({
                    type: 'success',
                    message: 'Added In Cart' // แสดงข้อความเตือนว่าเพิ่มสินค้าลงในตะกร้าเรียบร้อย
                })
            }
            document.getElementById('add_to_cart_btn').innerHTML = 'Added In Cart'; // ปรับเปลี่ยนข้อความบนปุ่มเป็น "Added In Cart"
            cartCount(); // อัปเดตจำนวนสินค้าในตะกร้า
            return true; // ส่งค่าคืนว่าการเพิ่มสินค้าลงในตะกร้าเสร็จสมบูรณ์
        }

        // ฟังก์ชันสำหรับการซื้อสินค้าทันที
        const buyNow = () => {
            if (addToCart()) { // เรียกใช้ฟังก์ชัน addToCart และตรวจสอบว่าสินค้าถูกเพิ่มลงในตะกร้าเรียบร้อย
                window.location.href = "{{ route('cart') }}"; // แสดงหน้าตะกร้าสินค้า
            }
        }

        // ฟังก์ชันสำหรับตรวจสอบว่ามีสินค้าในตะกร้าหรือไม่และทำการเปลี่ยนข้อความบนปุ่มให้เป็น "Added In Cart" หากมี
        @if ($product->variant->count() == 1)
            let variantId = '{{ $product->variant[0]->id }}';
            if (mCart.isInCart(variantId)) document.getElementById('add_to_cart_btn').innerHTML = 'Added In Cart';
        @endif
    </script>
@endpush

@section('body_content')
<section class="px-6 md:px-20 mt-6">
    <div class="flex flex-wrap md:flex-nowrap gap-6 justify-center">

        {{-- Left --}}
        <div class="shrink-0 w-full md:w-auto flex flex-col-reverse md:flex-row gap-4">
            <div id="images" class="flex md:flex-col gap-2 pb-1 md:pb-0 max-h-96 overflow-y-auto">
                {{-- วนลูปเพื่อแสดงรูปภาพสินค้า --}}
                @foreach ($product->image as $image)
                    <div onclick="viewImage(this, {{ $image->id }})"
                        class="bg-white rounded-md shadow p-1 cursor-pointer w-14 h-14 md:w-12 md:h-12 lg:w-16 lg:h-16">
                        <img class="w-full h-full object-cover" src="{{ asset('storage/' . $image->path) }}" alt="" />
                    </div>
                @endforeach
            </div>
            <div class="h-auto md:h-96 relative bg-white rounded-md shadow-md p-3">
                {{-- รูปภาพหลัก --}}
                <img id="bigImage" class="w-full h-full object-contain" src="{{ asset('storage/' . $product->image[0]->path) }}" alt="">
                {{-- ปุ่มเลื่อนรูปภาพ --}}
                <span onclick="nextPrevious(-1)" class="absolute top-1/2 left-1 bg-white rounded-full w-8 h-8 md:w-10 md:h-10 lg:w-12 lg:h-12 shadow flex items-center justify-center">
                    <i class='bx bx-chevron-left text-xl text-gray-400 hover:text-violet-600 duration-200 cursor-pointer'></i>
                </span>
                <span onclick="nextPrevious(1)" class="absolute top-1/2 right-1 bg-white rounded-full w-8 h-8 md:w-10 md:h-10 lg:w-12 lg:h-12 shadow flex items-center justify-center">
                    <i class='bx bx-chevron-right text-xl text-gray-400 hover:text-violet-600 duration-200 cursor-pointer'></i>
                </span>
            </div>
        </div>
        {{-- Left End --}}

        {{-- Right --}}
        <div class="w-full md:w-1/3 flex flex-col gap-4 bg-white p-6 rounded-md shadow-md">
            {{-- แสดงส่วนข้อมูลของสินค้า --}}
            <div class="flex gap-3">
                @php
                    $discount = (($product->variant[0]->mrp - $product->variant[0]->selling_price) / $product->variant[0]->mrp) * 100;
                @endphp
                <span class="bg-red-500 text-white rounded px-2 text-xs">{{ round($discount, 2) }}% Off</span>
                <span class="text-gray-400 text-sm"><i class='bx bx-star'></i> 4.5</span>
            </div>
            <h2 class="text-lg font-medium text-gray-800">{{ $product->title }}</h2>
            <div class="text-sm text-gray-800">
                <p><span class="text-gray-400">SKU:</span> {{ $product->variant[0]->sku }}</p>
                <p><span class="text-gray-400">Brand:</span> {{ $product->brand->name }}</p>
            </div>
            {{-- แสดงราคา --}}
            <div>
                <span class="text-rose-500 font-bold text-xl">${{ $product->variant[0]->selling_price }}</span>
                <sub class="text-gray-400"><strike>${{ $product->variant[0]->mrp }}</strike></sub>
            </div>
            {{-- เลือกสี --}}
            <div class="mt-4">
                <p class="text-gray-400">Colors:</p>
                <div class="flex gap-1">
                    {{-- วนลูปเพื่อแสดงสีที่สินค้ามี --}}
                    @foreach ($product->variant as $item)
                        <span onclick="selectColor('{{ $item->color->id }}')"
                            style="background-color: {{ $item->color->code }}"
                            class="w-5 h-5 cursor-pointer rounded-full">&nbsp;</span>
                    @endforeach
                </div>
            </div>
            {{-- เลือกขนาด --}}
            <div>
                <p class="text-gray-400">Sizes:</p>
                <div class="flex gap-1 text-gray-400 text-sm">
                    {{-- วนลูปเพื่อแสดงขนาดที่สินค้ามี --}}
                    @foreach ($product->variant as $item)
                        <span onclick="selectSize('{{ $item->size->id }}')"
                            class="flex justify-center cursor-pointer items-center rounded-full border border-gray-400 px-2 py-1">{{ $item->size->code }}</span>
                    @endforeach
                </div>
                <a href="#" class="text-gray-400 text-xs">Size Guide</a>
            </div>
            {{-- ปุ่มเพิ่มสินค้าในตะกร้าและซื้อทันที --}}
            <div class="flex items-center gap-4 mt-4">
                <button onclick="toggleWishlist(this, '{{ $product->id }}')" class="bg-white shadow-md rounded-full w-7 h-7 flex items-center justify-center">
                    <i class='bx  {{ $product->has_favorited ? 'bxs-heart text-red-500' : 'bx-heart' }} text-xl'></i>
                </button>
                <button onclick="addToCart()" id="add_to_cart_btn"
                    class="border border-violet-600 rounded w-28 text-center drop-shadow font-medium text-violet-600 py-0.5">Add
                    to Cart</button>
                <button onclick="buyNow()"
                    class="border border-violet-600 rounded w-28 text-center drop-shadow font-medium text-white bg-violet-600 py-0.5">Buy
                    Now</button>
            </div>
        </div>
        {{-- Right End --}}
    </div>
    {{-- แสดงคำอธิบายสินค้า --}}

    <div class="mt-6">
        <h3 class="text-lg text-gray-400 font-medium my-6">Product Description</h3>
        {{-- แสดงข้อมูล description ของสินค้า --}}
        <div class="text-gray-600">
            {{ $product->description }}
        </div>
    </div>



    {{-- แสดงสินค้าแนะนำ --}}
    <section class="mt-6">
        <h3 class="text-gray-800 font-medium mb-2">Featured Product</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 justify-center">
            <!-- วนลูปเพื่อแสดงสินค้าแนะนำ -->
            @foreach ($products as $item)
                @if ($item->variant->isNotEmpty())
                    <x-product.card1 :product="$item" />
                @endif
            @endforeach
        </div>
    </section>
</section>
@endsection
