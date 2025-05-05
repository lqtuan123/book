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
            <div class="pl-4">
                @include('frontend.layouts.breadcrumb')
            </div>
            
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
                    {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
                    {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    @include('frontend.layouts.footer')

    <!-- Scripts -->
    @include('frontend.layouts.foot')

    @yield('scripts')
    
    <!-- Kiểm tra hiển thị modal đăng nhập từ URL parameter -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Lấy URL hiện tại
        const urlParams = new URLSearchParams(window.location.search);
        
        // Kiểm tra nếu có tham số login=true
        if (urlParams.get('login') === 'true') {
            // Hiển thị modal đăng nhập
            const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
            if (loginModal) {
                loginModal.show();
                
                // Xóa tham số login=true khỏi URL mà không reload trang
                const newUrl = window.location.pathname + 
                    window.location.search.replace(/[?&]login=true/, '').replace(/^&/, '?') + 
                    window.location.hash;
                window.history.replaceState({}, document.title, newUrl);
            }
        }
    });
    </script>
</body>

</html>
