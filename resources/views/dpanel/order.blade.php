@extends('dpanel.layouts.app')

@section('title', 'Order Details')

@push('scripts')
    <script>

        const updateStatus = (e, id) =>{
            window.location.href = `${window.location.origin}/dpanel/order/status/${id}/${e.value}`;
        }
    </script>
@endpush

@section('body_content')
    <div class="grid grid-cols-1 md:grid-cols-3 rounded-l pl-2 mb-3 ">
        <p>Order ID: <span class="font-medium">#{{str_pad($order->id, 8, '0', STR_PAD_LEFT)}}</span></p>
        <p>Order Status: <span class="font-medium"><select onchange="updateStatus(this,'{{$order->id}}')"
            class="border rounded focus:outline-none">
           <option value="PENDING" @selected($order->status == 'PENDING')>PENDING</option>
           <option value="PAIN OUT" @selected($order->status == 'PAIN OUT')>PAIN OUT</option>
           <option value="DISPATCHED" @selected($order->status == 'DISPATCHED')>DISPATCHED</option>
           <option value="ON WAY" @selected($order->status == 'ON WAY')>ON WAY</option>
           <option value="DELIVERED" @selected($order->status == 'DELIVERED')>DELIVERED</option>
       </select></span></p>
        <p>Payment Status: <span class="font-medium">{{$order->payment_status}}</span></p>
        <p>Name: <span class="font-medium">{{$order->items[0]->variant->product->title}}</span></p>
        <p>Discount: <span class="font-medium">${{$order->discount_amount}}</span></p>
        <p>Payable: <span class="font-medium">${{$order->total_amount - $order->discount_amount}}</span></p>
    </div>
    
    <div class="w-full flex flex-col">
        <div class="overflow-x-auto">
            <div class="align-middle inline-block min-w-full">
                <div class=" shadow overflow-hidden border border-gray-400 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-600">
                        <thead class="bg-gray-800">
                            <tr>

                                <th scope="col" 
                                    class="pl-3 py-3 text-left w-12 text-xs font-medium text-gray-200 tracking-wider">
                                    #
                                </th>

                                <th scope="col" 
                                    class="pl-3 py-3 text-left text-xs font-medium text-gray-200 tracking-wider">
                                    Color
                                </th>

                                <th scope="col" 
                                    class="pl-3 py-3 text-left text-xs font-medium text-gray-200 tracking-wider">
                                    Size
                                </th>

                                <th scope="col" 
                                    class="pl-3 py-3 text-left text-xs font-medium text-gray-200 tracking-wider">
                                    Qty
                                </th>

                                <th scope="col" 
                                    class="pl-3 py-3 text-left text-xs font-medium text-gray-200 tracking-wider">
                                    Price
                                </th>

                                <th scope="col" 
                                    class="pl-3 py-3 text-left text-xs font-medium text-gray-200 tracking-wider">
                                    MRP
                                </th>

                            </tr>
                        </thead>

                         <tbody class="bg-gray-700 divide-y divide-gray-600">
                            @foreach ($order->items as $item)
                                <tr>
                                    <td class="pl-3 py-1">
                                        <div class="text-sm text-gray-200">
                                            {{ $loop->iteration}}
                                        </div>
                                    </td>

                                    <td class="pl-3 py-3">
                                        <div class="text-sm text-gray-200 flex items-center gap-2">
                                            {{$item->variant->color->name}}
                                            <span class="w-4 h-4 rounnded-full flex" 
                                            style="background-color: {{$item->variant->color->code}}">&nbsp;</span>
                                        </div>
                                    </td>

                                    <td class="pl-3 py-3">
                                        <div class="text-sm text-gray-200">{{$item->variant->size->name}}({{$item->variant->size->code}})</div>
                                    </td>

                                    <td class="pl-3 py-3">
                                        <div class="text-sm text-gray-200">{{$item->qty}}</div>
                                    </td>

                                    <td class="pl-3 py-3">
                                        <div class="text-sm text-gray-200">${{$item->price}}</div>
                                    </td>

                                    <td class="pl-3 py-3">
                                        <div class="text-sm text-gray-200">{{$item->mrp}}</div>
                                    </td>

                                
                                </tr>
                            @endforeach

                         </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    
@endsection
