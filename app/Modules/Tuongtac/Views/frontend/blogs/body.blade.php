@extends('frontend.layouts.master')
@section('css')
    {{-- <link rel="stylesheet" href="{{ asset('frontend/assets/css/custom8.css') }}" type="text/css" /> --}}
    {{-- <link rel="stylesheet" href="{{ asset('frontend/assets_f/custom-group.css') }}" type="text/css" /> --}}
    <!-- FilePond CSS -->
    <!-- Dropzone CSS -->
    @yield('topcss')
    <style>
        .dropdown-menu {
            display: none;
            opacity: 0;
            visibility: hidden;
            transition: all 0.2s ease;
        }

        .dropdown-menu.active {
            display: block;
            opacity: 1;
            visibility: visible;
        }

        /* Dropdown menu styles */
        .post-dropdown .dropdown-menu {
            transition: all 0.3s ease;
            transform: translateY(-10px);
            opacity: 0;
        }

        .post-dropdown .dropdown-menu.active,
        .post-dropdown .dropdown-menu:not(.hidden) {
            transform: translateY(0);
            opacity: 1;
            display: block;
        }

        /* Post action button styles */
        .post-action-btn {
            transition: all 0.2s ease;
        }

        .post-action-btn:hover {
            color: #3b82f6 !important;
        }

        .post-action-btn.active {
            color: #3b82f6 !important;
        }

        /* Emoji picker styles */
        #emoji-picker {
            transition: all 0.2s ease;
        }

        .emoji-btn {
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .emoji-btn:hover {
            transform: scale(1.2);
        }

        .post-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .mobile-menu {
            display: none;
        }

        .mobile-menu.active {
            display: flex;
        }

        .sidebar {
            transition: all 0.3s ease;
        }

        .sidebar.collapsed {
            width: 0;
            overflow: hidden;
            padding: 0;
            margin: 0;
        }

        .main-content.expanded {
            width: 100%;
        }

        #main-content {
            max-width: 692.8px;
            width: 100%;
        }

        .loading-spinner {
            display: none;
        }

        .loading-spinner.active {
            display: block;
        }

        .quick-view-modal {
            display: none;
        }

        .quick-view-modal.active {
            display: flex;
        }

        .tag:hover {
            transform: scale(1.05);
        }

        .comment-input:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
        }

        @media (max-width: 768px) {
            .mobile-menu {
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: white;
                z-index: 50;
                padding: 1rem;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
            }

            .sidebar.collapsed {
                width: 0;
            }

            .right-sidebar {
                display: none;
            }

            .right-sidebar-mobile {
                display: block;
            }
        }

        @media (min-width: 769px) {
            .right-sidebar-mobile {
                display: none;
            }
        }

        .emoji-picker {
            display: none;
            position: absolute;
            bottom: 100%;
            right: 0;
            z-index: 10;
        }

        .emoji-picker.active {
            display: block;
        }


        /* aaaaaaaaaaaaaaa */
        /* Popup Modal - Global */
        .popup-modal {
            position: fixed;
            inset: 0;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            transition: all 0.3s ease;
        }

        .popup-modal.hidden {
            display: none;
        }

        .popup-content {
            background: #fff;
            border-radius: 12px;
            width: 90%;
            max-width: 800px;
            max-height: 90vh;
            overflow-y: auto;
            padding: 24px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            position: relative;
        }

        .close-popup {
            position: absolute;
            top: 16px;
            right: 16px;
            background: transparent;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #555;
            transition: color 0.2s;
        }

        .close-popup:hover {
            color: #222;
        }

        /* Post Dropdown */
        .post-dropdown {
            position: relative;
        }

        .post-dropdown .dropdown-toggle {
            background: white;
            border-radius: 9999px;
            padding: 6px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            cursor: pointer;
        }

        .post-dropdown .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            margin-top: 8px;
            width: 192px;
            /* 48 * 4 px */
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
            z-index: 50;
            overflow: hidden;
        }

        .post-dropdown:hover .dropdown-menu,
        .post-dropdown .dropdown-toggle:focus+.dropdown-menu {
            display: block;
        }

        .dropdown-menu a,
        .dropdown-menu button {
            display: block;
            width: 100%;
            text-align: left;
            padding: 10px 16px;
            font-size: 14px;
            color: #333;
            background: none;
            border: none;
            cursor: pointer;
        }

        .dropdown-menu a:hover,
        .dropdown-menu button:hover {
            background-color: #f7fafc;
        }

        /* Scroll To Top Button */
        #scroll-to-top {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background-color: #3b82f6;
            /* blue-500 */
            color: white;
            width: 48px;
            height: 48px;
            border-radius: 9999px;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 999;
        }

        #scroll-to-top.show {
            opacity: 1;
            visibility: visible;
        }

        
    </style>
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')
    {{-- @include('frontend.layouts.page_title') --}}
    
    <body class="bg-gray-50 font-sans">
        <main class="container mx-auto px-4 py-6 flex flex-col lg:flex-row">


            <!-- Left Menu -->
            @include('Tuongtac::frontend.blogs.left-partial')

            <!-- Main Content -->

            @yield('inner-content')


            <!-- Right Menu -->
            @include('Tuongtac::frontend.blogs.right-partial')


            <div id="spinner" style="display: none;">
                <div class="spinner"></div>
            </div>
           
        </main>

        <script>
            var csrfToken = '{{ csrf_token() }}';
        </script>

        <!-- Social Interactions JavaScript -->
        @socialInteractions

        <!-- Additional Scripts -->
        @yield('botscript')
    </body>
@endsection

