@extends('frontend.layouts.master1')

@section('css')
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/custom8.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('frontend/assets_f/custom-group.css') }}" type="text/css" />
    @yield('topcss')
    <style>
        .scroll-to-top {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 40px;
            height: 40px;
            background-color: var(--base-color);
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            font-size: 20px;
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .scroll-to-top:hover {
            background-color: var(--base-color);
            transform: scale(1.1);
        }

        @media screen and (max-width: 768px) {
            .post-tags {
                display: none;
            }
        }

        .post-tags span {
            display: inline-block;
            white-space: nowrap;
            margin-top: 2px;
        }

        /* Bổ sung để mở rộng main content */
        .mcontainer.dev {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .main-content {
            flex: 1;
            width: 100%;
            max-width: 100%;
            padding-left: 0;
            padding-right: 0;
        }
    </style>
@endsection

@section('content')
    <section class="hero-section position-relative padding-large"
        style="background-image: url('{{ asset('frontend/assets_f/images/banner-image-bg-1.jpg') }}');
    background-size: cover; background-repeat: no-repeat; background-position: center; height: 400px;">
        <div class="hero-content">
            <div class="container">
                <div class="row">
                    <div class="text-center">
                        <h1>Book</h1>
                        <div class="breadcrumbs">
                            <span class="item">
                                <a href="{{ route('home') }}">Home > </a>
                            </span>
                            <span class="item text-decoration-underline">Blogs</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section style="padding-top:0px">
        <div class="mcontainer dev">

            <!-- BỎ left-partial -->

            <!-- Main Content chiếm toàn bộ -->
            <main class="main-content">
                @yield('inner-content')
            </main>

            <!-- Right Menu -->
            @include('Tuongtac::frontend.group.right-partial')

        </div>
        <div id="spinner" style="display: none;">
            <div class="spinner"></div>
        </div>
        <button id="scrollToTopBtn" class="scroll-to-top" onclick="scrollToTop()">▲</button>
    </section>
@endsection

@section('footscripts')
    @yield('botscript')

    <script>
        window.addEventListener('scroll', function() {
            const scrollToTopBtn = document.getElementById('scrollToTopBtn');
            scrollToTopBtn.style.display = window.scrollY > 300 ? 'flex' : 'none';
        });

        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        function toggleMenu() {
            const menu = document.querySelector('.left-menu .menu');
            menu?.classList.toggle('active');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const icons = ['📌', '🔥', '✨', '🌟', '🎖️', '💎', '⚡', '💡'];
            document.querySelectorAll('.random-icon').forEach(icon => {
                icon.textContent = icons[Math.floor(Math.random() * icons.length)];
            });
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closePopup();
            }
        });

        function openPopup(postId) {
            const popup = document.getElementById('contentPopup');
            popup.style.display = 'flex';
            document.getElementById('popup-title').innerText = 'Đang tải...';
            document.getElementById('popup-body').innerText = 'Vui lòng chờ...';

            fetch(`/gettblog/${postId}`)
                .then(response => {
                    if (!response.ok) throw new Error('Lỗi khi tải nội dung');
                    return response.json();
                })
                .then(data => {
                    document.getElementById('popup-title').innerText = data.title;
                    document.getElementById('popup-body').innerHTML = data.content;
                })
                .catch(error => {
                    document.getElementById('popup-title').innerText = 'Lỗi';
                    document.getElementById('popup-body').innerText = 'Không thể tải nội dung bài viết.';
                    console.error(error);
                });
        }

        function closePopup() {
            document.getElementById('contentPopup').style.display = 'none';
        }
    </script>
@endsection
