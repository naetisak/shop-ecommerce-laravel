<div class="bg-white rounded-lg shadow-lg p-3 relative">
    <a href="{{ route('product_detail', $product->slug) }}">
        <img class="mx-auto h-56 w-" src="{{ asset('storage/' . $product->image[0]->path) }}" alt="">
    </a>

    <div class="flex justify-between gap-3 my-3">
        <a href="{{ route('product_detail', $product->slug) }}" class="font-medium text-gray-800">{{ $product->title }}</a>
        <div class="flex flex-col items-end">
            <strong class="text-violet-600">${{ $product->variant[0]->selling_price }}</strong>
            <strike class="text-gray-400">$ {{ $product->variant[0]->mrp }}</strike>
        </div>
    </div>

    <div class="flex justify-between items-center mb-2">
        <div class="flex gap-1">
            @foreach ($product->variant as $item)
                <span style="background-color: {{ $item->color->code }}" class="w-5 h-5 rounded-full">&nbsp;</span>
            @endforeach
        </div>

        <div class="flex gap-1 text-gray-400 text-sm">
            @foreach ($product->variant as $item)
                <span class="flex justify-center items-center rounded-full border border-gray-400 px-2 py-1">{{ $item->size->code }}</span>
            @endforeach
        </div>
    </div>

    <div class="flex justify-between items-center">
        <span class="text-gray-400"><i class='bx bx-star'></i> 4.5</span>
        <a href="{{ route('product_detail', $product->slug) }}" class="text-violet-600 flex items-center font-bold"><i class='bx bx-cart-add text-2xl'></i> Buy Now</a>
    </div>

    <div class="absolute top-2 left-3 right-3 flex justify-between items-center">
        @php
            $discount = round((($product->variant[0]->mrp - $product->variant[0]->selling_price) / $product->variant[0]->mrp) * 100, 2);
        @endphp
        <span class="bg-red-500 text-white rounded px-2 py-1 text-xs font-medium">{{ $discount }}% Off</span>
        <button onclick="toggleWishlist(this, '{{ $product->id }}')" class="bg-white shadow-md rounded-full w-7 h-7 flex items-center justify-center">
            <i class='bx  {{ $product->has_favorited ? 'bxs-heart text-red-500' : 'bx-heart' }} text-xl'></i>
        </button>
    </div>
</div>
