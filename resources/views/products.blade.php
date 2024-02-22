@extends('layouts.app')

@push('css')
    <link rel="stylesheet" href="{{asset('css/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/owl.theme.default.min.css')}}">
@endpush

@push('scripts')
    <script src="{{asset('js/owl.carousel.min.js')}}"></script>
    <x-url-generator-js />
    <script>
        $(document).ready(function(){
            $(".owl-carousel").owlCarousel({
                loop:true,
                margin:10,
                nav:false,
                dots:false,
                responsiveClass:true,
                responsive:{
                    0:{
                        items:1,
                    },
                    600:{
                        items:1,
                    },
                    1000:{
                        items:1,
                    }
                }
            });
        });

        const sortBy = (e) => {
            let sb = e.value;
            window.location.href = generateUrl({
                sb
            });
        }

        const search = () => {
            let k = document.getElementById('search_input').value;
            window.location.href = generateUrl({
                k
            });
        }

        const applyFilter = () => {
            let form = document.getElementById('filter-form');
            let formData = new FormData(form);
            let obj = {};

            for (const [key, value] of formData) {
                if (obj.hasOwnProperty(key)) {
                    obj = {
                        ...obj,
                        [key]: `${obj[key]},${value}`
                    }
                } else {
                    obj = {
                        ...obj,
                        [key]: value
                    }
                }
            }

            window.location.href = generateUrl(obj);
        }
    </script>
@endpush

@section('body_content')

    <div>
        <div class="owl-carousel h-min">
            @foreach ($banners as $banner)
            <a href="{{ $banner->link ?? '#' }}"><img src="{{asset('storage/' . $banner->path)}}" alt=""></a>   
            @endforeach
        </div>
    </div>

    <section class="px-6 md:px-20 mt-6">

        <section class="mt-6 grid grid-cols-1 md:grid-cols-5 gap-6">
            {{-- Filters --}}
            <div>
                <form id="filter-form" class="w-full md:w-auto p-3 rounded border border-slate-300">
                    <h3 class="text-xl font-bold text-violet-600 uppercase">Filters</h3>
    
                    {{-- Price --}}
                    <div>
                        <h4 class="text-gray-800 font-medium mb-1">Price</h4>
                        <div class="flex justify-between items-center gap-4">
                            <div class="bg-gray-300 rounded p-1 flex justify-between items-center gap-2">
                                <span class="text-gray-400">From</span>
                                <div class="flex">
                                    <input type="text" name="min" pattern="[0-9]+" value="{{request()->min}}"
                                         class="w-7 bg-transparent focus:outline-none text-right">
                                    <span class="text-gray-400">$</span>
                                </div>
                            </div>
    
                            <div class="bg-gray-300 rounded p-1 flex justify-between items-center gap-3 ">
                                <span class="text-gray-400">Up to</span>
                                <div class="flex">
                                    <input type="text" name="max" pattern="[0-9]+" value="{{request()->max}}"
                                         class="w-7 bg-transparent focus:outline-none text-right">
                                    <span class="text-gray-400">$</span>
                                </div>
                            </div>
                        </div> 
                    </div>
                    <hr class="mt-2">
    
                    {{-- Size --}}
                    <div>
                        <h4 class="text-gray-800 font-medium mb-1">Size</h4>
                        <ul class="text-gray-400 text-sm">
                            @foreach ($sizes as $item)
                                <li class="flex gap-2">
                                    <input type="checkbox" name="size" id="size-{{$item->id}}" 
                                    value="{{$item->id}}"
                                    @if (request()->size) @checked(in_array($item->id, explode(',', urldecode(request()->size)))) @endif> 
                                    <label class="cursor-pointer" for="size-{{$item->id}}">{{$item->name}}({{$item->code}})</label>
                                </li>
                            @endforeach
                            <input type="hidden" name="size" value="">
                        </ul>
                    </div>
                    <hr class="mt-2">
    
                    {{-- Color --}}
                    <div>
                        <h4 class="text-gray-800 font-medium mb-1">Color</h4>
                        <ul class="text-gray-400 text-sm flex flex-col gap-2">
                            @foreach ($colors as $item)
                                <li class="flex gap-2">
                                    <input type="checkbox" name="color" id="color-{{$item->id}}" 
                                        value="{{$item->id}}"
                                        @if (request()->color) @checked(in_array($item->id, explode(',', urldecode(request()->color)))) @endif>
                                    <label class="cursor-pointer flex gap-1" for="color-{{$item->id}}">
                                        <span style="background-color: {{ $item->code }}" 
                                            class="w-4 h-4 flex rounded-full">&nbsp;</span> {{$item->name}}
                                    </label>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <hr class="my-2">
    
                    <div class="flex items-center justify-between">
                        <button type="button" onclick="applyFilter()" 
                            class="bg-violet-600 rounded-md text-white font-medium uppercase text-center py-0.5 px-4">
                            Apply Filter</button>
                        <a href="{{route('products')}}"><img class="w-7 h-7" 
                                src="{{asset('dpanel/images/refresh.png')}}" alt=""></a>
                    </div>
                </form>  
            </div>

            {{-- Products --}}
            <div class="md:col-span-4 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2 flex items-center px-1.5 text-sm rounded border border-slate-300">
                    <span class="w-6 border-r border-slate-300">
                        <i class='bx bx-search text-xl text-gray-400'></i>
                    </span>
                    <input type="search" id="search_input" placeholder="Search 10000+ Products"
                        value="{{ request()->k }}" class="py-1 pl-1.5 w-full bg-transparent focus:outline-none">
                    <button onclick="search()" class="text-violet-500">Search</button>
                </div>
                <div class="flex items-center px-1.5 text-sm rounded border border-slate-300">
                    <span class="w-6 border-r border-slate-300">
                        <i class='bx bx-filter text-xl text-gray-400'></i>
                    </span>
                    <select onchange="sortBy(this)" class="py-1 pl-1.5 w-full bg-transparent focus:outline-none">
                        <option value="">Featured</option>
                        <option value="price_asc" @selected(request()->sb == 'price_asc')>Price: Low to High</option>
                        <option value="price_desc" @selected(request()->sb == 'price_desc')>Price: High to Low</option>
                        <option value="desc" @selected(request()->sb == 'desc')>Newest Arrivals</option>

                    </select>

                </div>

                @forelse ($products as $item)
                    @if ($item->variant->isNotEmpty())
                        <x-product.card1 :product="$item" />
                    @endif
                @empty
                    <div class="md:col-span-3 flex flex-col justify-center items-center gap-3">
                        <img src="{{ asset('images/result-not-found.png') }}" alt="">
                        <h1 class="text-2xl font-bold text-gray-800">Result Not Found!</h1>
                        <p class="text-gray-400">Try to search with another query.</p>
                    </div>
                @endforelse
                <div class="md:col-span-3">
                    {{ $products->links() }}
                </div>
            </div>
        </section>

    </section>
@endsection