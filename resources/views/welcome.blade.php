@extends('layouts.app')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/owl.theme.default.min.css') }}">
@endpush

@push('scripts')
    <script src="{{asset('js/owl.carousel.min.js')}}"></script>
    <script>
        $(document).ready(function(){
            $(".banner-carousel").owlCarousel({
                loop:true,
                margin:10,
                nav:false,
                dots:false,
                responsiveClass:true,
                responsive:{
                    0:{
                        items: 1,
                    },
                    600:{
                        items: 1,
                    },
                    1000:{
                        items: 1,
                    }
                }
            });
        });
        $(document).ready(function() {
            $(".coupon-carousel").owlCarousel({
                loop: {{ $coupons->count() <= 7 ? 0 : 1 }},
                margin: 10,
                nav: false,
                dots: false,
                autoplay: true,
                autoplayTimeout: 2000,
                autoplayHoverPause: true,
                responsiveClass: false,
                responsive: {
                    0: {
                        items: 1,
                    },
                    600: {
                        items: 1,
                    },
                    1000: {
                        items: 7,
                    }
                }
            });
        });
    </script>
@endpush

@section('body_content')
    <div>
        <div class="banner-carousel owl-carousel h-min">
            @foreach ($banners as $banner)
            <a href="{{ $banner->link ?? '#' }}"><img src="{{asset('storage/' . $banner->path)}}" alt=""></a>   
            @endforeach
        </div>
    </div>

    <section class="px-6 md:px-20 mt-6">
        <h3 class="text-gray-800 font-medium mb-2">Flash Sale</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            @foreach ($products as $item)
                @if ($item->variant->isNotEmpty())
                    <x-product.card1 :product="$item" />
                @endif
            @endforeach
        </div>
    </section>

    <section class="px-6 md:px-20 mt-10 mb-6">
        <div class="coupon-carousel owl-carousel flex flex-wrap gap-6">
            @foreach ($coupons as $item)
                <div class="bg-white rounded-md shadow mb-2 flex justify-between items-center gap-3">
                    <div class="flex flex-col pl-3 py-1">
                        <span class="text-gray-400 leading-5">New Coupon</span>
                        <strong class="text-orange-500">#{{ $item->code }}</strong>
                    </div>

                    <div class="bg-violet-600 w-14 font-medium text-white p-3 rounded-r-md">
                        @if ($item->type == 'Percentage')
                            {{$item->value}}% Off
                        @else
                            ${{$item->value}} Off
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <section class="px-6 md:px-20 mt-8">
        <div class="flex items-center justify-between">
            <div class="flex gap-2">
                <h3 class="text-gray-800 font-medium underline mb-2">Best Seller</h3>
                <h3 class="text-gray-800 font-medium mb-2">New Product</h3>
            </div>
            <a href="{{ route('products') }}" class="text-violet-600 font-medium mb-2">All Product</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            @foreach ($products as $item)
                @if ($item->variant->isNotEmpty())
                    <x-product.card1 :product="$item" />
                @endif
            @endforeach
        </div>
    </section>
@endsection