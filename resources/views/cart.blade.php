@extends('layouts.app')

@push('scripts')
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        const isCartNotEmpty = () => {
            let items = mCart._getItems();
            return items != null ? Object.keys(items).length : 0;
        }

        const setEmptyView = () => {
            document.getElementById('itemContainer').innerHTML = `<div class="md:col-span-2 flex flex-col gap-2 justify-center items-center">
                        <img src="{{ asset('images/empty_cart.png') }}" alt="">
                        <h2 class="text-2xl font-bold text-gray-800">Your Cart is Empty</h2>
                        <p class="text-gray-400 text-center">Looks like you haven't not added anything to your cart yet</p>
                        <a href="{{ route('products') }}" 
                        class="mt-5 bg-violet-600 text-white font-bold text-center px-2 py-1 rounded-full shadow">Continue Shopping</a>
                    </div>`;
        }

        const removeItem = (e, id) => {
            mCart.remove(id);
            e.parentElement.parentElement.parentElement.remove();

            isCartNotEmpty() ? null : setEmptyView();
        }

        const applyCoupon = () => {
            let discountCode = document.getElementById('discount_code');
            if (discountCode.value == '' || discountCode.value.length == 0) return;

            axios.post(`${window.location.href}/coupon`, {
                    code: discountCode.value
                })
                .then((res) => {
                    let coupon = res.data;
                    let subtotal = mCart.getSubTotal();

                    if (coupon.min_cart_amount != '' && coupon.min_cart_amount > subtotal) {
                        cuteToast({
                            type: "error",
                            message: `Coupon Active above to $${coupon.min_cart_amount} Cart amount`,
                        })
                        return;
                    }

                    // Apply Coupon Code
                    let discount = 0;
                    if (coupon.type == 'Fixed') {
                        discount = coupon.value;
                    } else {
                        discount = ((coupon.value / 100) * subtotal).toFixed(2);
                    }
                    document.getElementById('discount_amount').textContent = discount;
                    document.getElementById('discount_msg').textContent = discount;
                    document.getElementById('total').textContent = subtotal - discount;

                })
                .catch((error) => {
                    discountCode.value = '';
                    cuteToast({
                        type: "error",
                        message: error.response.data.message,
                    })
                })
        }

        if (isCartNotEmpty()) {
            setTimeout(() => {
                let items = mCart._getItems();
                let ids = Object.keys(items);

                axios.get(`${window.location.href}/products?ids=${ids}`)
                    .then((res) => {
                        let html = '';
                        res.data.forEach(item => {
                            let qty = mCart.getQty(item.id);
                            html += `<div class="flex gap-4">
                                    <div class="bg-gray-100 rounded shadow p-2">
                                        <img class="w-20" src="${'/storage/'+item.product.oldest_image.path}" alt="">
                                    </div>
                                    <div class="flex flex-col gap-0.5">
                                        <h3 class="text-lg font-medium text-gray-800">${item.product.title}</h3>
                                        <div class="text-gray-400 text-sm flex items-center gap-2">
                                            <p class="flex items-center gap-1">
                                                Color:
                                                <span style="background-color: ${item.color.code}" class="w-4 h-4 rounded-full">&nbsp;</span>
                                            </p>
                                            <p>Size:  ${item.size.code}</p>
                                        </div>
                                        <p class="text-black text-lg">
                                            $<span class="itemPrice">${item.selling_price}</span>x <span class="qty">${qty}</span> = <span class="font-bold">$<span class="itemTotalPrice">${item.selling_price*qty}</span></span>
                                        </p>
                                        <div class="flex items-center gap-6">
                                            <div class="flex items-center justify-center gap-1">
                                                <i onClick="mCart.manageQty(this,'${item.id}', -1, '${item.stock}')" class='text-gray-400 bx bx-minus-circle text-xl cursor-pointer'></i>
                                                <span class="border border-gray-400 px-3 leading-none">${qty}</span>
                                                <i onClick="mCart.manageQty(this,'${item.id}', 1, '${item.stock}')" class='text-green-400 bx bx-plus-circle text-xl cursor-pointer'></i>
                                            </div>
                                            <button onClick="removeItem(this, '${item.id}')" class="text-gray-400 uppercase">Remove</button>
                                        </div>
                                    </div>
                                </div>`;
                        });
                        document.getElementById('itemContainer').innerHTML = html;
                        mCart.updatePrice();
                    })
                    .catch((error) => {
                        cuteToast({
                            type: "error",
                            message: error.message,
                        })
                    });

            }, 250);
        } else {
            setEmptyView();
        }

        const checkout = () => {
            if (!isCartNotEmpty()) return;

            let items = mCart._getItems();
            let is_address = document.getElementById('addresses').querySelector('input[name=address]:checked');

            if (!is_address) {
                cuteToast({
                    type: 'error',
                    message: 'Please select delivery address'
                });
                return;
            }

            let address = is_address.value;
            let coupon_code = null;

            let discountCode = document.getElementById('discount_code');
            if (discountCode.value != '' && discountCode.value.length != 0) {
                coupon_code = discountCode.value;
            };

            axios.post("{{ route('payment.init') }}", {
                    address,
                    coupon_code,
                    items
                })
                .then((res) => {
                    mCart.empty();
                    mCart.updatePrice();
                    setEmptyView();
                    let data = res.data;
                    openRazorpay(data.id, data.key, data.amount, data.razorpay_order_id);
                })
                .catch((error) => {
                    cuteToast({
                        type: 'error',
                        message: error.message
                    });
                })

        }

        @auth
        const openRazorpay = (id, key, amount, razorpay_order_id) => {
            var options = {
                'key': key,
                'amount': amount,
                'currency': 'INR',
                'name': "{{ config('app.name') }}",
                'description': "Buy product form {{ config('app.name') }}",
                'image': `${window.location.origin}/images/logo.png`,
                'order_id': razorpay_order_id,
                'callback_url': `${window.location.origin}/payment/verify/${id}`,
                'prefill': {
                    'name': "{{ auth()->user()->full_name }}",
                    'email': "{{ auth()->user()->email }}",
                    'contact': "{{ auth()->user()->mobile }}",
                },
                'theme': {
                    'color': '#00bb0d'
                },
            };

            var rzp1 = new Razorpay(options);
            rzp1.on('payment.failed', (response) => {
                axios.post("{{ route('payment.fail') }}", {
                        razorpay_order_id
                    })
                    .then((res) => {
                        window.location.href =
                            "{{ route('account.index', ['tab' => 'orders', 'msg' => 'Payment Failed!']) }}";
                    })
                    .catch((error) => {
                        window.location.reload();
                    })
            })
            rzp1.open();
        }
        @endauth
    </script>
@endpush

@section('body_content')
    <section class="px-6 md:px-20 mt-6 min-h-screen">
        <h1 class="text-5xl font-bold text-center drop-shadow-md text-black py-12">Shopping Cart</h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- Left Side --}}
            <div class="md:col-span-2">
                {{-- Delivery Addresses --}}
                <h3 class="text-gray-700 text-lg font-medium">Delivery Addresses</h3>
                <div class="grid grid-cols-1 md:grid-cols-6 gap-4 mb-5">
                    <div id="addresses"
                        class="md:col-span-5 flex gap-4 overflow-x-auto pt-2 scrollbar-thin scrollbar-thumb-gray-400 scrollbar-h-1">
                        @forelse ($addresses as $item)
                            <label for="address_{{ $item->id }}" class="shrink-0 w-72 relative">
                                <input type="radio" @checked($item->is_default_address) name="address"
                                    id="address_{{ $item->id }}" value="{{ $item->id }}" class="hidden peer" />
                                <div
                                    class="p-2 border border-slate-300 peer-checked:border-violet-600 rounded-md cursor-pointer">
                                    <div class="flex justify-between items-center">
                                        <span class="text-black font-bold">{{ $item->full_name }}</span>
                                        <a href="{{ route('address.edit', $item->id) }}"
                                            class="text-gray-400 cursor-pointer"><i class='bx bx-pencil'></i> Edit</a>
                                    </div>
                                    <p class="text-gray-400 text-sm leading-4">{{ $item->full_address }}</p>
                                    <p class="text-gray-600 text-sm">Mobile No: +91 {{ $item->mobile_no }}</p>
                                </div>
                                <i
                                    class='hidden peer-checked:block absolute -top-3 -right-2 bx bxs-check-circle text-xl text-violet-600 bg-white'></i>
                            </label>
                        @empty
                            <div class="border w-full py-10 flex justify-center rounded-md items-center">
                                <button type="button" class="text-violet-500 font-medium"
                                    onclick="toggleLoginPopup()">Login
                                    to continue</button>
                            </div>
                        @endforelse
                    </div>
                    @auth
                        <a href="{{ route('address.create') }}"
                            class="bg-slate-300 text-gray-400 cursor-pointer px-2 pt-2 md:px-4 rounded-md shrink-0 flex flex-col items-center justify-center">
                            <i class='bx bxs-plus-circle text-lg'></i>
                            <span class="text-sm">Add Address</span>
                        </a>
                    @else
                        <button type="button" onclick="toggleLoginPopup()"
                            class="bg-slate-300 text-gray-400 cursor-pointer px-2 pt-2 md:px-4 rounded-md shrink-0 flex flex-col items-center justify-center">
                            <i class='bx bxs-plus-circle text-lg'></i>
                            <span class="text-sm">Add Address</span>
                        </button>
                    @endauth
                </div>
                {{-- Delivery Addresses End --}}

                <div id="itemContainer" class="grid grid-cols-1 md:grid-cols-2 gap-5">

                </div>
            </div>
            {{-- Left Side End --}}

            {{-- Right Side --}}
            <div>
                <div class="bg-white rounded-md shadow-md p-2">
                    <h3 class="mb-3 text-black font-medium uppercase">Order Details</h3>

                    <div class="relative mb-2 px-2 py-1.5 border border-slate-300 rounded-md">
                        <label class="absolute -top-3.5 left-5 text-slate-300 bg-white px-1">Discount Code</label>
                        <div class="flex justify-between">
                            <input type="text" name="discount_code" id="discount_code" placeholder="Enter Discount Code"
                                class="w-full focus:outline-none">
                            <button type="button" onclick="applyCoupon()"
                                class="text-violet-600 font-medium">Apply</button>
                        </div>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Subtotal</span>
                        <span class="text-gray-800 font-bold">$<span id="subtotal">0</span></span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Shipping cost</span>
                        <span class="text-gray-800 font-bold">$0</span>
                    </div>

                    <div class="mb-2 flex justify-between items-center">
                        <span class="text-gray-400">Discount</span>
                        <span class="text-violet-600 font-bold">$<span id="discount_amount">0</span></span>
                    </div>

                    <div class="mb-1 flex justify-between items-center">
                        <span class="text-gray-400">Total</span>
                        <span class="text-gray-800 font-bold">$<span id="total">0</span></span>
                    </div>

                    <div class="flex justify-between items-center bg-green-100 px-2 py-1 rounded-md">
                        <span class="text-green-500">Your total Savings amount on <br> this order</span>
                        <span class="text-green-500 font-bold">$<span id="discount_msg">0</span></span>
                    </div>

                    @auth
                        <button type="button" onclick="checkout()"
                            class="mt-5 bg-violet-600 text-white font-bold text-center w-full py-1 rounded shadow">Checkout</button>
                    @else
                        <button type="button" onclick="toggleLoginPopup()"
                            class="mt-5 bg-violet-600 text-white font-bold text-center w-full py-1 rounded shadow">Checkout</button>
                    @endauth

                </div>
            </div>
            {{-- Right Side End --}}

        </div>

        {{-- <div>
            <h3 class="mb-4 text-gray-700 text-lg font-medium">Payment Method</h3>

            <div class="flex flex-wrap gap-3">
                <label for="" class="border border-slate-300 rounded p-2">
                    <input type="radio" name="payment_method" id="" class="hidden peer">
                    <span class="text-gray-400 font-medium uppercase">Pay On Delivery</span>
                </label>

                <label for="" class="border border-slate-300 rounded py-2 px-6">
                    <input type="radio" name="payment_method" id="" class="hidden peer">
                    <span class="text-gray-400 font-medium uppercase">UPI</span>
                </label>

                <label for="" class="border border-slate-300 rounded p-2">
                    <input type="radio" name="payment_method" id="" class="hidden peer">
                    <span class="text-gray-400 font-medium uppercase">Paytm</span>
                </label>
            </div>
        </div> --}}
    </section>
@endsection
