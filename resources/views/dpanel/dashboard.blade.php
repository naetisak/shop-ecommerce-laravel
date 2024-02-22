@extends('dpanel.layouts.app')

@section('title', 'Dashboard')

@push('css')
    <x-dpanel::chart.js>
        <script src="//code.highcharts.com/modules/cylinder.js"></script> {{-- For Funnel & Pyramid Chart --}}
        <script src="//code.highcharts.com/modules/funnel3d.js"></script> {{-- For Funnel & Pyramid Chart --}}
        <script src="//code.highcharts.com/modules/pyramid3d.js"></script> {{-- For Pyramid Chart --}}
        <script src="https://code.highcharts.com/modules/networkgraph.js"></script> {{-- For Graph Tree Chart --}}
    </x-dpanel::chart.js>
@endpush

@section('body_content')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        <div class="bg-white rounded-md flex shadow-lg w-full overflow-hidden">
            <span class="p-3 bg-violet-500 flex items-center">
                <i class='bx bx-message-alt-detail text-3xl text-white'></i>
            </span>
            <div class="p-3">
                <p class="font-medium">Brands</p>
                <small class="text-gray-400">{{$data['brands']}}</small>
            </div>
        </div>

        <div class="bg-white rounded-md flex shadow-lg w-full overflow-hidden">
            <span class="p-3 bg-yellow-500 flex items-center">
                <i class='bx bx-message-alt-detail text-3xl text-white'></i>
            </span>
            <div class="p-3">
                <p class="font-medium">Categories</p>
                <small class="text-gray-400">{{$data['categories']}}</small>
            </div>
        </div>
        <div class="bg-white rounded-md flex shadow-lg w-full overflow-hidden">
            <span class="p-3 bg-green-500 flex items-center">
                <i class='bx bx-message-alt-detail text-3xl text-white'></i>
            </span>
            <div class="p-3">
                <p class="font-medium">Products</p>
                <small class="text-gray-400">{{$data['products']}}</small>
            </div>
        </div>
        <div class="bg-white rounded-md flex shadow-lg w-full overflow-hidden">
            <span class="p-3 bg-red-500 flex items-center">
                <i class='bx bx-message-alt-detail text-3xl text-white'></i>
            </span>
            <div class="p-3">
                <p class="font-medium">Coupons</p>
                <small class="text-gray-400">{{$data['coupons']}}</small>
            </div>
        </div>
        <div class="bg-white rounded-md flex shadow-lg w-full overflow-hidden">
            <span class="p-3 bg-orange-500 flex items-center">
                <i class='bx bx-message-alt-detail text-3xl text-white'></i>
            </span>
            <div class="p-3">
                <p class="font-medium">Orders</p>
                <small class="text-gray-400">{{$data['orders']}}</small>
            </div>
        </div>

    </div>

@endsection
