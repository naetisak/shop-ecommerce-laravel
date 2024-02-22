@extends('dpanel.layouts.app')

@section('title', 'Banners')

@push('scripts')
    <script>
        const editBanner = (id,name, link, status)=>{
            document.getElementById("edit-form").action = '/dpanel/banner/' + id;
            document.getElementById('banner-name').value = name;
            document.getElementById('banner-link').value = link;
            document.getElementById('status').value = status;
            showBottomSheet('bottomSheetUpdate')
        }

        const updateStatus = (e, id) =>{
            window.location.href = `${window.location.origin}/dpanel/banner/status/${id}/${e.value}`;
        }

    </script>
@endpush

@section('body_content')
    <div class="bg-gray-800 flex justify-between items-center rounded-l pl-2 mb-3 ">
        <p class="text-white font-medium text-lg ">Banners</p>
        <button onclick="showBottomSheet('bottomSheet')" class="bg-violet-500 text-white py-1 px-2 rounded-r ">Create</button>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 text-red-500 px-2 py-1 rounded border border-red-500 mb-3">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{$error}}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
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
                                    Image
                                </th>

                                <th scope="col" 
                                    class="pl-3 py-3 text-left text-xs font-medium text-gray-200 tracking-wider">
                                    Name
                                </th>

                                <th scope="col" 
                                    class="pl-3 py-3 text-left text-xs font-medium text-gray-200 tracking-wider">
                                    Link
                                </th>

                                <th scope="col" 
                                    class="pl-3 py-3 text-left text-xs font-medium text-gray-200 tracking-wider">
                                    Status
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
                                        <img class="w-20" src="{{ asset('storage/' . $item->path)}}" alt="">
                                    </td>

                                    <td class="pl-3 py-3">
                                        <div class="text-sm text-gray-200">{{$item->name}}</div>
                                    </td>

                                    <td class="pl-3 py-3">
                                        <div class="text-sm text-gray-200">{{$item->link}}</div>
                                    </td>

                                    <td class="pl-3 py-3">
                                        <select onchange="updateStatus(this,'{{$item->id}}')"
                                            class="border rounded focus:outline-none">
                                           <option value="1" @selected($item->is_active == 1)>Active</option>
                                           <option value="0" @selected($item->is_active == 0)>Not Active</option>
                                       </select>
                                    </td>

                                    <td class="flex px-4 py-3 justify-center text-lg">

                                        <button onclick="editBanner('{{$item->id}}','{{$item->name}}','{{$item->link}}','{{$item->is_active}}')"
                                        class="ml-1 text-blue-500 bg-blue-100 focus:outline-none border border-blue-500 rounded-full w-8 h-8 flex justify-center items-center">
                                        <i class="bx bx-edit"></i>
                                        </button>
                                        
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

    <x-dpanel::modal.bottom-sheet sheetId="bottomSheet" title="New Category">
        <div class="flex justify-center items-center min-h-[30vh] md:min-h-[50vh]">
            <form action="{{route('dpanel.banner.store')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 gap-3">
                    <div>
                        <label>Name <span class="text-red-500 font-bold">*</span></label>
                        <input type="text" name="name" maxlength="255" required placeholder="Enter  Name" 
                            class="w-full bg-transparent border border-gray-500 rounded py-0.5 px-2 focus:outline-none">
                    </div>

                    <div>
                        <label>Link </label>
                        <input type="url" name="link" maxlength="255" placeholder="Enter url"
                            class="w-full bg-transparent border border-gray-500 rounded py-0.5 px-2 focus:outline-none">
                    </div>

                    <div>
                        <label>Image <span class="text-red-500 font-bold">*</span></label>
                        <input type="file" name="image" required 
                            class="w-full bg-transparent border border-gray-500 rounded py-0.5 px-2 focus:outline-none">
                    </div>

                    <div class="text-center">
                        <button class="bg-indigo-500 text-center text-white py-1 px-2 rounded shadow-md uppercase">Create</button>

                    </div>

                </div>
            </form>
        </div>
    </x-dpanel::modal.bottom-sheet>

    <x-dpanel::modal.bottom-sheet sheetId="bottomSheetUpdate" title="Update Category">
        <div class="flex justify-center items-center min-h-[30vh] md:min-h-[50vh]">
            <form id="edit-form" action="" method="post" enctype="multipart/form-data">

                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div>
                        <label>Name <span class="text-red-500 font-bold">*</span></label>
                        <input type="text" name="name" id="banner-name" maxlength="255" required placeholder="Enter  Name" 
                            class="w-full bg-transparent border border-gray-500 rounded py-0.5 px-2 focus:outline-none">
                    </div>

                    <div>
                        <label>Link </label>
                        <input type="url" name="link" id="banner-link" maxlength="255" placeholder="Enter url"
                            class="w-full bg-transparent border border-gray-500 rounded py-0.5 px-2 focus:outline-none">
                    </div>

                    <div>
                        <label>Image </label>
                        <input type="file" name="image"  
                            class="w-full bg-transparent border border-gray-500 rounded py-0.5 px-2 focus:outline-none">
                    </div>

                    <div>
                        <label>Status</label>
                        <select name="is_active" id="status" 
                            class="w-full bg-transparent border border-gray-500 rounded py-0.5 px-2 focus:outline-none">
                            <option value="1">Active</option>
                            <option value="0">Not Active</option>
                        </select>
                    </div>

                    <div>
                        <label>&nbsp;</label>
                        <button class="w-full bg-indigo-500 text-center text-white py-1 px-2 rounded shadow-md uppercase">Update
                            Category</button>
                    </div>
                </div>
            </form>
        </div>
    </x-dpanel::modal.bottom-sheet>
    <x-dpanel::modal.bottom-sheet-js hideOnClickOutside="true" />
@endsection
