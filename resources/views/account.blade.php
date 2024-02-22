@extends('layouts.app')

@push('scripts')
    <script>
        const activeTab = (id)=>{
            let tabContainer = document.getElementById('tabContainer').querySelectorAll('.tabContent');
            let tabLinks = document.getElementById('tabLinks').querySelectorAll('li');
            tabContainer.forEach(element => {
                element.classList.add('hidden');
            });
            tabLinks.forEach(element => {
                element.classList.remove('text-violet-600');
                element.classList.remove('underline');

            });

            document.getElementById(id).classList.remove('hidden');
            document.getElementById(`nav-${id}`).classList.add('text-violet-600');
            document.getElementById(`nav-${id}`).classList.add('underline');

            const url = new URL(window.location);
            url.searchParams.set('tab',id);
            window.history.pushState({},'',url);
        }

        @if(request()->tab)
            activeTab("{{ request()->tab }}")
        @endif
        
    </script>
@endpush


@section('body_content')
    <div class="px-6 md:min-h-screen md:px-20 mt-6 grid grid-cols-1 md:grid-cols-6 gap-4">
        <div>
            <ul class="flex md:flex-col flex-wrap justify-between gap-3 md:gap-1" id="tabLinks">
                <li id="nav-profile" onclick="activeTab('profile')" class="cursor-pointer text-violet-600 underline">My Profile</li>
                <li id="nav-orders" onclick="activeTab('orders')" class="cursor-pointer">My Order</li>
                <li id="nav-address" onclick="activeTab('address')" class="cursor-pointer">My Address</li>
                @auth
                    <li ><a href="{{route('logout')}}" class="flex">Logout</a></li>
                @endauth
                
            </ul>
        </div>

        {{-- Right Side --}}
        <div class="md:col-span-5">
            @auth
                <div id="tabContainer" class="grid grid-cols-1 gap-6">

                    {{-- My Profile --}}
                    <section id="profile" class="tabContent border border-slate-300 rounded px-4 pt-2 pb-4">
                        <h3 class="text-gray-900">Personal Information</h3>
                        <hr>

                        <form action="" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            @csrf
                            <div class="mt-4 relative border border-slate-300 rounded">
                                <label for="" class="absolute -top-3.5 left-3 bg-gray-50 px-1 text-gray-400">First
                                    Name</label>
                                <input type="text" name="first_name" value="{{ auth()->user()->first_name }}"
                                    class="mt-2 px-3 bg-transparent focus:outline-none w-full">
                            </div>
                            <div class="mt-4 relative border border-slate-300 rounded">
                                <label for="" class="absolute -top-3.5 left-3 bg-gray-50 px-1 text-gray-400">Last
                                    Name</label>
                                <input type="text" name="last_name" value="{{ auth()->user()->last_name }}"
                                    class="mt-2 px-3 bg-transparent focus:outline-none w-full">
                            </div>
                            <div class="mt-4 relative border border-slate-300 rounded">
                                <label for="" class="absolute -top-3.5 left-3 bg-gray-50 px-1 text-gray-400">Mobile
                                    Number</label>
                                <input type="tel" maxlength="10" name="mobile" value="{{ auth()->user()->mobile }}"
                                    class="mt-2 px-3 bg-transparent focus:outline-none w-full">
                            </div>
                            <div class="mt-4 relative border border-slate-300 rounded">
                                <label for="" class="absolute -top-3.5 left-3 bg-gray-50 px-1 text-gray-400">Email
                                    Address</label>
                                <input type="text" name="email" value="{{ auth()->user()->email }}"
                                    class="mt-2 px-3 bg-transparent focus:outline-none w-full">
                            </div>

                            <div></div>
                            <div>
                                <button
                                    class="bg-violet-500 rounded shadow py-1 text-center w-full text-white uppercase font-medium">Update</button>
                            </div>

                        </form>
                    </section>
                    {{-- My Profile End --}}

                    {{-- My Orders --}}
                    <section id="orders" class="tabContent hidden bg-gray-100 border border-slate-300 rounded px-4 pt-2 pb-4">
                        <h3 class="text-gray-900">My Orders</h3>
                        <hr class="mb-3">

                        <div class="grid grid-cols-1 gap-6">

                            @foreach ($orders as $order)
                                <div class="flex flex-col md:flex-row gap-3 justify-between items-center">
                                    <div>
                                        <div class="mb-1 flex flex-wrap gap-3">
                                            @foreach ($order->images as $image)
                                                <div class="bg-gray-100 rounded shadow p-2">
                                                    <img class="w-20" src="{{ asset('storage/' . $image) }}" alt="">
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="grid grid-cols-4 gap-4">
                                            <div class="flex flex-col text-gray-800 leading-5">
                                                <span class="font-medium">Order ID</span>
                                                <span>{{ str_pad($order->id, 8, '0', STR_PAD_LEFT) }}</span>
                                            </div>

                                            <div class="flex flex-col text-gray-800 leading-5">
                                                <span class="font-medium">Shipped Date</span>
                                                <span>{{ $order->created_at->format('d M, Y') }}</span>
                                            </div>

                                            <div class="flex flex-col text-gray-800 leading-5">
                                                <span class="font-medium">Total</span>
                                                <span>${{ $order->total_amount - $order->discount_amount }}</span>
                                            </div>

                                            <div class="flex flex-col text-gray-800 leading-5">
                                                <span class="font-medium">Status</span>
                                                <span
                                                    class="{{ str_replace(' ', '_', strtolower($order->status)) }}">{{ $order->status }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="shrink-0 bg-white flex flex-col gap-1">
                                        <a href="{{ route('order.show', $order->id) }}"
                                            class="border  border-slate-400 rounded-sm text-black font-medium uppercase px-4">View
                                            Order</a>

                                        @if (strtolower($order->status) == 'paid out')
                                            <button class="text-red-500">Cancel Order</button>
                                        @elseif (strtolower($order->status) == 'on_way')
                                            <button class="text-black">Track Order</button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach

                        </div>

                    </section>
                    {{-- My Orders End --}}

                    {{-- My Delivery Addresses --}}
                    <section id="address" class="tabContent hidden border border-slate-300 rounded px-4 pt-2 pb-4">
                        <h3 class="text-gray-900">My Delivery Addresses</h3>
                        <hr>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-3">

                            @foreach ($addresses as $item)
                                <div class="flex flex-col gap-1 p-2 rounded shadow bg-gray-100">
                                    <div class="flex justify-between items-center">
                                        <p class="text-gray-800 font-medium">{{ $item->full_name }}
                                            <small>({{ $item->tag }} Address)</small>
                                        </p>
                                        <a href="{{ route('address.edit', $item->id) }}"
                                            class="text-gray-400 hover:text-violet-600 duration-300 cursor-pointer"><i
                                                class='bx bx-pencil'></i>
                                            Edit</a>
                                    </div>
                                    <p class="text-gray-400 leading-5">{{ $item->full_address }}</p>
                                    <p class="text-gray-600">Mobile No: +66 {{ $item->mobile_no }}</p>
                                </div>
                            @endforeach

                            <a href="{{ route('address.create') }}"
                                class="flex flex-col items-center py-6 justify-center text-gray-400 gap-1 p-2 rounded shadow bg-gray-100">
                                <i class='bx bxs-plus-circle text-2xl'></i>
                                <p>Add New Address</p>
                            </a>

                        </div>

                    </section>
                    {{-- My Delivery Addresses End --}}
                </div>
            @else
                <div class="border w-full py-10 flex justify-center rounded-md items-center">
                    <button type="button" class="text-violet-500 font-medium" onclick="toggleLoginPopup()">Login
                        to access your account</button>
                </div>
            @endauth
        </div>
        {{-- Right Side End --}}
    </div>
@endsection