<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta id="csrf-token" name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="codize">

    <title> @yield('page_title') | {{ config('app.name') }} </title>

    @include('partials.inc_top')
    <!-- Include Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="{{ in_array(Route::currentRouteName(), ['payments.invoice', 'marks.tabulation', 'marks.show', 'ttr.manage', 'ttr.show']) ? 'sidebar-xs' : '' }}">

{{-- @include('partials.top_menu') --}}
<!-- Navigation Bar -->
<nav class="bg-slate-600 p-4 text-white">
    <div class="container mx-auto flex justify-between items-center">
        <h1 class="text-lg font-bold hover:cursor-pointer" onClick="window.open('/', '_self')">
                STUDENT INFORMATION SYSTEM
        </h1>
        <div>
            <a href="/enroll" class="bg-green-700 hover:bg-green-800 text-white font-bold py-2 px-4 rounded mr-2">
                Enroll Now
            </a>
            <a href="/login" class="bg-blue-700 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded">
                Login
            </a>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="container mx-auto mt-8">
<div class="page-content">
    {{-- @include('partials.menu') --}}
    <div class="content-wrapper">
        {{-- @include('partials.header') --}}

        <div class="content">
            {{--Error Alert Area--}}
            @if($errors->any())
                <div class="alert alert-danger border-0 alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>

                        @foreach($errors->all() as $er)
                            <span><i class="icon-arrow-right5"></i> {{ $er }}</span> <br>
                        @endforeach

                </div>
            @endif
            <div id="ajax-alert" style="display: none"></div>

            @yield('content')
        </div>


    </div>
</div>

@include('partials.inc_bottom')
@yield('scripts')
    <!-- Footer -->
    <hr class="mt-10">
    <footer class="text-center p-4 mt-8">
        © 2023 <span class="text-blue-600">STUDENT INFORMATION SYSTEM</span> 
    </footer>
</body>
</html>
