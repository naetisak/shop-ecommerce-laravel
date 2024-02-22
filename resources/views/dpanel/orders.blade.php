@extends('dpanel.layouts.app')

@section('title', 'Orders')

@push('scripts')
    <script>
        const updateStatus = (e, id) =>{
            window.location.href = `${window.location.origin}/dpanel/order/status/${id}/${e.value}`;
            }
    </script>
@endpush

@section('body_content')
    <div class="bg-gray-800 flex justify-between items-center rounded-l pl-2 mb-3 ">
        <p class="text-white font-medium text-lg ">Orders</p>
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
                                    Coupon
                                </th>

                                <th scope="col" 
                                    class="pl-3 py-3 text-left text-xs font-medium text-gray-200 tracking-wider">
                                    Total
                                </th>

                                <th scope="col" 
                                    class="pl-3 py-3 text-left text-xs font-medium text-gray-200 tracking-wider">
                                    Discount
                                </th>

                                <th scope="col" 
                                    class="pl-3 py-3 text-left text-xs font-medium text-gray-200 tracking-wider">
                                    Pay
                                </th>

                                <th scope="col" 
                                    class="pl-3 py-3 text-left text-xs font-medium text-gray-200 tracking-wider">
                                    Payment Status
                                </th>


                                <th scope="col" 
                                    class="px-3 py-3 text-text-center text-xs font-medium text-gray-200 tracking-wider">
                                    Action
                                </th>
                            </tr>
                        </thead>

                         <tbody class="bg-gray-700 divide-y divide-gray-600">
                            @foreach ($data as $item)
                                <tr>
                                    <td class="pl-3 py-1">
                                        <div class="text-sm text-gray-200">
                                            {{$data->perPage() * ($data->currentPage() - 1) + $loop->iteration}}
                                        </div>
                                    </td>

                                    <td class="pl-3 py-3">
                                        <div class="text-sm text-gray-200">{{$item->coupon->code ?? ''}}</div>
                                    </td>

                                    <td class="pl-3 py-3">
                                        <div class="text-sm text-gray-200">${{$item->total_amount}}</div>
                                    </td>

                                    <td class="pl-3 py-3">
                                        <div class="text-sm text-gray-200">${{$item->discount_amount}}</div>
                                    </td>

                                    <td class="pl-3 py-3">
                                        <div class="text-sm text-gray-200">${{$item->total_amount - $item->discount_amount}}</div>
                                    </td>

                                    <td class="pl-3 py-3">
                                        <div class="text-sm text-gray-200">{{$item->payment_status}}</div>
                                    </td>

                                    <td class="flex px-4 py-2 gap-2 justify-center text-lg">
                                        <select onchange="updateStatus(this,'{{$item->id}}')"
                                             class="border rounded focus:outline-none">
                                            <option value="PENDING" @selected($item->status == 'PENDING')>PENDING</option>
                                            <option value="PAIN OUT" @selected($item->status == 'PAIN OUT')>PAIN OUT</option>
                                            <option value="DISPATCHED" @selected($item->status == 'DISPATCHED')>DISPATCHED</option>
                                            <option value="ON WAY" @selected($item->status == 'ON WAY')>ON WAY</option>
                                            <option value="DELIVERED" @selected($item->status == 'DELIVERED')>DELIVERED</option>
                                        </select>
                                        <a href="{{ route('dpanel.order.show', $item->id) }}"
                                            class="bg-blue-100 w-6 h-6 rounded-full flex justify-center items-center">
                                            <i class='bx bx-show text-xl text-blue-500'></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach

                         </tbody>
                    </table>
                </div>
            </div>
        </div>
        {{$data->links()}}
    </div>

    
@endsection
