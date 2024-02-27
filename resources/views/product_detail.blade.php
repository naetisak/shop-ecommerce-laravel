@extends('layouts.app')

@push('scripts')
    <x-url-generator-js />
    <script>
        let currentImage = 0;
        let color_id = '{{ request()->c ?? null }}';
        let size_id = '{{ request()->s ?? null }}';

        const selectColor = (cid) => {
            c = color_id ? null : cid;
            window.location.href = generateUrl({
                c
            })
        }
        const selectSize = (sid) => {
            s = size_id ? null : sid;
            window.location.href = generateUrl({
                s
            })
        }

        const viewImage = (e, index) => {

            currentImage = index;

            document.getElementById('bigImage').src = e.querySelector('img').src;
        }

        const nextPrevious = (index) => {

            i = currentImage + index;

            let images = document.getElementById('images').querySelectorAll('img');

            if (i >= images.length || i < 0) return;

            currentImage = i;

            let arr = [];

            images.forEach(element => arr.push(element.src));

            document.getElementById('bigImage').src = arr[currentImage];
        }

        const addToCart = () => {
            let count = '{{ $product->variant->count() }}';
            if (count != 1) {
                cuteToast({
                    type: 'info',
                    message: 'Please select color & size'
                })
                return;
            }

            let variantId = '{{ $product->variant[0]->id }}';
            if (!mCart.isInCart(variantId)) {
                mCart.add(variantId, 1);
                cuteToast({
                    type: 'success',
                    message: 'Added In Cart'
                })
            }

            document.getElementById('add_to_cart_btn').innerHTML = 'Added In Cart';
            cartCount();
            return true;
        }
        const buyNow = () => {
            if (addToCart()) {
                window.location.href = "{{ route('cart') }}";
            }
        }

        @if ($product->variant->count() == 1)
            let variantId = '{{ $product->variant[0]->id }}';

            if (mCart.isInCart(variantId)) document.getElementById('add_to_cart_btn').innerHTML = 'Added In Cart';
        @endif
    </script>
@endpush

@section('body_content')
<section class="px-6 md:px-20 mt-6">
    <div class="flex flex-wrap md:flex-nowrap gap-6 justify-center"> <!-- เพิ่มคลาส justify-center เพื่อจัดให้เนื้อหาอยู่ตรงกลาง -->

        {{-- Left --}}
        <div class="shrink-0 w-full md:w-auto flex flex-col-reverse md:flex-row gap-4">
            <div id="images" class="flex md:flex-col gap-2 pb-1 md:pb-0 max-h-96 overflow-y-auto">
                @foreach ($product->image as $image)
                    <div onclick="viewImage(this, {{ $image->id }})"
                        class="bg-white rounded-md shadow p-1 cursor-pointer w-14 h-14 md:w-12 md:h-12 lg:w-16 lg:h-16">
                        <img class="w-full h-full object-cover" src="{{ asset('storage/' . $image->path) }}" alt="" />
                    </div>
                @endforeach
            </div>
            <div class="h-auto md:h-96 relative bg-white rounded-md shadow-md p-3">
                <img id="bigImage" class="w-full h-full object-contain" src="{{ asset('storage/' . $product->image[0]->path) }}" alt="">
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
            <div class="flex gap-3">
                @php
                    $discount = (($product->variant[0]->mrp - $product->variant[0]->selling_price) / $product->variant[0]->mrp) * 100;
                @endphp
                <span class="bg-red-500 text-white rounded px-2 text-xs">{{ round($discount, 2) }}% Off</span>
                <span class="text-gray-400 text-sm"><i class='bx bx-star'></i> 4.5</span>
            </div>

            {{-- Name, SKU, Brand --}}
            <h2 class="text-lg font-medium text-gray-800">{{ $product->title }}</h2>
            <div class="text-sm text-gray-800">
                <p><span class="text-gray-400">SKU:</span> {{ $product->variant[0]->sku }}</p>
                <p><span class="text-gray-400">Brand:</span> {{ $product->brand->name }}</p>
            </div>

            {{-- Price --}}
            <div>
                <span class="text-rose-500 font-bold text-xl">₹{{ $product->variant[0]->selling_price }}</span>
                <sub class="text-gray-400"><strike>₹{{ $product->variant[0]->mrp }}</strike></sub>
            </div>

            {{-- Colors --}}
            <div class="mt-4"> <!-- เพิ่มคลาส mt-4 เพื่อเพิ่มระยะห่างด้านบน -->
                <p class="text-gray-400">Colors:</p>
                <div class="flex gap-1">
                    @foreach ($product->variant as $item)
                        <span onclick="selectColor('{{ $item->color->id }}')"
                            style="background-color: {{ $item->color->code }}"
                            class="w-5 h-5 cursor-pointer rounded-full">&nbsp;</span>
                    @endforeach
                </div>
            </div>

            {{-- Sizes --}}
            <div>
                <p class="text-gray-400">Sizes:</p>
                <div class="flex gap-1 text-gray-400 text-sm">
                    @foreach ($product->variant as $item)
                        <span onclick="selectSize('{{ $item->size->id }}')"
                            class="flex justify-center cursor-pointer items-center rounded-full border border-gray-400 px-2 py-1">{{ $item->size->code }}</span>
                    @endforeach
                </div>
                <a href="#" class="text-gray-400 text-xs">Size Guide</a>
            </div>


            {{-- Wishlist, Add to Cart and Buy Now --}}
            <div class="flex items-center gap-4 mt-4"> <!-- เพิ่มคลาส mt-4 เพื่อเพิ่มระยะห่างด้านบน -->
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

    {{-- Product Description --}}
    <div class="mt-6">
        <h3 class="text-lg text-gray-400 font-medium mb-2">Product Description</h3>
        <div class="text-gray-600">
            Lorem ipsum dolor sit amet consectetur, adipisicing elit. Ad tenetur consectetur aspernatur veritatis rem,
            asperiores necessitatibus corporis doloremque repellendus et cumque reprehenderit, nisi vitae autem iste
            quia temporibus, fugiat ea voluptates velit tempora quae! Cupiditate debitis eaque ex. Similique aperiam
            rerum inventore est fugiat, optio amet ratione sequi doloribus illum iure suscipit voluptatum ut repellat
            non, impedit, et harum? Ipsam porro deleniti voluptatum fugiat quibusdam. Blanditiis eos perspiciatis
            voluptatum. Deserunt praesentium fuga quisquam neque possimus, adipisci officiis. Sit quo a voluptas quidem
            minima debitis culpa aliquam voluptatibus repellendus repudiandae explicabo totam, quaerat odit, in alias
            facere ullam, commodi expedita fugit!
        </div>
    </div>

    <section class="mt-6">
        <h3 class="text-gray-800 font-medium mb-2">Featured Product</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 justify-center"> <!-- เพิ่มคลาส justify-center เพื่อจัดให้เนื้อหาอยู่ตรงกลาง -->
            @foreach ($products as $item)
                @if ($item->variant->isNotEmpty())
                    <x-product.card1 :product="$item" />
                @endif
            @endforeach
        </div>
    </section>

</section>
@endsection
