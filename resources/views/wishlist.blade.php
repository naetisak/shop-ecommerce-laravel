@extends('layouts.app')

@section('body_content')
    <section class="px-6 md:px-20 mt-6 min-h-screen">
        <h1 class="text-5xl font-bold text-center drop-shadow-md text-black py-12">Wishlist</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            @forelse ($data as $item)
                <div class="flex gap-4">
                    <div class="bg-gray-100 rounded shadow p-2">
                        <img class="w-20" src="{{ asset('storage/' . $item->oldestImage->path) }}" alt="">
                    </div>
                    <div class="flex flex-col gap-0.5">
                        <h3 class="text-lg font-medium text-gray-800">{{ $item->title }}</h3>
                        <div class="text-gray-400 text-sm flex items-center gap-2">
                            <p class="flex items-center gap-1">
                                Color:
                                <span style="background-color: {{ $item->latestVariant->color->code }}"
                                    class="w-4 h-4 rounded-full">&nbsp;</span>
                            </p>
                            <p>Size: {{ $item->latestVariant->size->code }}</p>
                        </div>
                        <p class="text-black text-lg font-bold">${{ $item->latestVariant->selling_price }}
                            <sub class="text-sm font-normal text-red-500">${{ $item->latestVariant->mrp }}
                                @php
                                    $discount = round((($item->latestVariant->mrp - $item->latestVariant->selling_price) / $item->latestVariant->mrp) * 100, 2);
                                @endphp
                                <span class="text-green-400">({{ $discount }}% off)</span>
                            </sub>
                        </p>
                        <div class="flex items-center gap-6">
                            <a href="{{ route('product_detail', $item->slug) }}" 
                                class="text-violet-600 font-bold uppercase">Buy Now</a>


                            <button onclick="toggleWishlist(this, '{{ $item->id }}', true)"
                                class="text-gray-400 uppercase">Remove</button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="md:col-span-3 flex flex-col justify-center items-center gap-3">
                    <img src="{{ asset('images/empty_cart.png') }}" alt="">
                    <h1 class="text-2xl font-bold text-gray-800">Your wishlist is empty</h1>
                    <p class="text-gray-400"></p>
                </div>
            @endforelse
        </div>
    </section>
@endsection
