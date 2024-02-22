<ul class="mt-1 text-gray-800">
    @foreach ($categories as $category)
        <li>{{$category->name}}</li>
    @endforeach
</ul>