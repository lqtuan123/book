<?php

$detail = \App\Models\SettingDetail::find(1);
$user = auth()->user();

?>
<!DOCTYPE html>
<html lang="vi">

<head>
    @include('frontend.layouts.head')
   
</head>

<body class="bg-gray-50 font-sans">

    <!-- Header Navbar -->
    @include('frontend.layouts.header')
    
    <!-- Main Content -->
    <main class="pt-2 pb-8">
        {{-- @yield('banner') --}}

        <div class="container ">
            
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    @include('frontend.layouts.footer')

    <!-- Scripts -->
    @include('frontend.layouts.foot')

    @yield('scripts')
</body>

</html>
