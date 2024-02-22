@extends('layouts.app')

@section('body_content')
    <section class="px-6 md:px-20 my-20">
        <form action="" method="POST" class="md:w-1/4 mx-auto">

            @csrf
            <h1 class="text-2xl font-bold text-center">Reset Password</h1>

            @if ($errors->any())
                <div class="bg-red-50 text-red-500 border border-red-500 rounded px-2 py-1  ">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <input type="hidden" name="token" value="{{ request()->token }}">
                <div class="relative border rounded mt-10">
                    <label class="text-gray-400 bg-white px-1 absolute -top-3 left-3">Password</label>
                    <input type="password" name="password" placeholder="enter your password"
                        class="w-full px-2 pt-1.5 placeholder-slate-300 bg-transparent focus:outline-none">
                </div>

                <div class="relative border rounded mt-5">
                    <label class="text-gray-400 bg-white px-1 absolute -top-3 left-3">Confirm Password</label>
                    <input type="password" name="password_confirmation" placeholder="re-enter your password"
                        class="w-full px-2 pt-1.5 placeholder-slate-300 bg-transparent focus:outline-none">
                </div>

                <button class="text-center bg-violet-500 rounded py-1 px-2 w-full mt-8 text-white font-medium">Update 
                    Password</button>

        </form>
    </section>
@endsection
