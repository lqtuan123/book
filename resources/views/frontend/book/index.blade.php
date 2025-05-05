@extends('frontend.layouts.master')
@section('css')
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

        .book-card {
            transition: all 0.3s ease;
        }

        .book-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .category-filter.active {
            background-color: #3B82F6;
            color: white;
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
            animation: fadeIn 0.3s ease;
        }

        /* Modal Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        /* Dropzone styling */
        .dropzone {
            border: 2px dashed #3B82F6;
            border-radius: 0.375rem;
            padding: 1.5rem;
            text-align: center;
            background: #F9FAFB;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .dropzone:hover {
            background: #EFF6FF;
        }

        .dropzone .dz-message {
            margin: 1.5rem 0;
            font-size: 0.875rem;
            color: #6B7280;
        }

        .dropzone .dz-preview {
            margin: 0.5rem;
        }

        .dropzone .dz-preview .dz-image {
            border-radius: 0.375rem;
            overflow: hidden;
        }

        .dropzone .dz-preview .dz-remove {
            color: #EF4444;
            margin-top: 0.5rem;
            font-size: 0.75rem;
            font-weight: 500;
            text-decoration: none;
        }

        .dropzone .dz-preview .dz-remove:hover {
            text-decoration: underline;
        }

        /* Tom Select styling */
        .ts-wrapper {
            border-radius: 0.375rem;
        }

        .ts-control {
            border-color: #D1D5DB !important;
            padding: 0.5rem !important;
            border-radius: 0.375rem !important;
            box-shadow: none !important;
        }

        .ts-control:focus {
            border-color: #3B82F6 !important;
            box-shadow: 0 0 0 1px #3B82F6 !important;
        }

        .ts-dropdown {
            border-radius: 0.375rem !important;
            border-color: #D1D5DB !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
        }
        
        .ts-dropdown .option.active {
            background-color: #EFF6FF !important;
            color: #1E40AF !important;
        }

        .ts-dropdown .option:hover {
            background-color: #F3F4F6 !important;
        }

        .ts-dropdown .create {
            color: #2563EB !important;
        }

        .ts-wrapper.multi .ts-control > div {
            background-color: #EFF6FF !important;
            color: #1E40AF !important;
            border-radius: 9999px !important;
            margin: 0.125rem !important;
            padding: 0.125rem 0.5rem !important;
            border: none !important;
        }

        .ts-wrapper.multi .ts-control > div .remove {
            border-left: none !important;
            padding-left: 0.25rem !important;
            color: #1E40AF !important;
        }

        .ts-wrapper.plugin-remove_button .item .remove {
            border-left: none !important;
            padding-left: 0.25rem !important;
            color: #1E40AF !important;
        }

        @media (min-width: 1024px) {
            .lg\:px-4 {
                padding-right: 0 !important;
            }
        }

        @media (max-width: 1024px) {
            .filters-container {
                display: none;
            }

            .sidebar {
                display: none;
            }

            .main-content {
                width: 100% !important;
                padding: 0 !important;
            }

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
                display: none;
            }

            .sidebar.collapsed {
                width: 0;
            }

            /* Make book cards smaller on mobile for better fit */
            .book-card .p-4 {
                padding: 0.5rem;
            }

            .book-card h3 {
                font-size: 0.875rem;
            }

            .book-card p {
                font-size: 0.75rem;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
   
    <main class="container mx-auto py-6">
        <div class="flex flex-col lg:flex-row">
            <!-- Left Sidebar -->
            <aside id="left-sidebar" class="sidebar lg:w-1/5 lg:pr-4 mb-6 lg:mb-0">
                <div class="bg-white rounded-lg shadow-sm p-4">
                    <!-- Sidebar Toggle Button (Mobile) -->
                    <button id="sidebar-toggle"
                        class="lg:hidden w-full bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-md mb-4 flex items-center justify-between">
                        <span>Bộ lọc sách</span>
                        <i class="fas fa-bars"></i>
                    </button>

                    <!-- Category Filter -->
                    <div class="mb-6">
                        <h3 class="font-bold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-list-ul mr-2 text-blue-500"></i>
                            Thể loại
                        </h3>
                        <ul class="space-y-2">
                            <li>
                                <a href="{{ route('front.book.index') }}"
                                    class="flex items-center text-gray-700 hover:text-blue-600 {{ request()->routeIs('front.book.index') && !request()->has('book_types') ? 'text-blue-600 font-medium' : '' }}">
                                    <i class="fas fa-book mr-2 text-sm"></i>
                                    Tất cả sách
                                    <span
                                        class="ml-auto bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded-full">{{ \App\Modules\Book\Models\Book::count() }}</span>
                                </a>
                            </li>
                            @foreach ($booktypes as $type)
                                <li>
                                    <a href="{{ route('front.book.byType', $type->slug) }}"
                                        class="flex items-center text-gray-700 hover:text-blue-600 {{ request()->is('front/book/type/' . $type->slug) ? 'text-blue-600 font-medium' : '' }}">
                                        <i class="{{ $type->icon ?? 'fas fa-book' }} mr-2 text-sm"></i>
                                        {{ $type->title }}
                                        <span
                                            class="ml-auto bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded-full">{{ $type->books_count ?? $type->books()->count() }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Advanced Search -->
                    <div class="mb-6">
                        <h3 class="font-bold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-search-plus mr-2 text-blue-500"></i>
                            Tìm kiếm nâng cao
                        </h3>
                        <form action="{{ route('frontend.book.advanced-search') }}" method="GET" class="space-y-3">
                            <div>
                                <label class="block text-sm text-gray-700 mb-1">Tiêu đề sách</label>
                                <input type="text" name="book_title" value="{{ request('book_title') }}"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm text-gray-700 mb-1">Danh mục sách</label>
                                <select name="book_type_id"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                                    <option value="">Tất cả</option>
                                    @foreach ($booktypes as $type)
                                        <option value="{{ $type->id }}"
                                            {{ request('book_type_id') == $type->id ? 'selected' : '' }}>
                                            {{ $type->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm text-gray-700 mb-1">Mô tả</label>
                                <input type="text" name="summary" value="{{ request('summary') }}"
                                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500"
                                    placeholder="Tìm kiếm trong phần mô tả sách...">
                            </div>
                            <button type="submit"
                                class="w-full bg-blue-500 text-white py-2 rounded-md text-sm hover:bg-blue-600 transition">
                                Áp dụng bộ lọc
                            </button>
                        </form>
                    </div>

                    <!-- Popular Tags -->
                    <div>
                        <h3 class="font-bold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-tags mr-2 text-blue-500"></i>
                            Tags phổ biến
                        </h3>
                        <div class="flex flex-wrap gap-2">
                            @php
                                $popularTags = \App\Models\Tag::withCount('books')
                                    ->orderBy('books_count', 'desc')
                                    ->limit(10)
                                    ->get();
                            @endphp

                            @foreach ($popularTags as $tag)
                                <a href="{{ route('front.book.search', ['tags[]' => $tag->id]) }}"
                                    class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-3 py-1 rounded-full text-xs">
                                    {{ $tag->title }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Main Content Area -->
            <div id="main-content" class="main-content lg:w-4/5 lg:px-4">
                <!-- Top Bar with Search -->
                <div class="bg-white rounded-lg shadow-sm p-4 mb-6 filters-container">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <h2 class="text-lg font-bold text-gray-800 mb-4 md:mb-0">Bộ lọc sách</h2>
                        @auth
                        <button id="create-book-btn" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md flex items-center">
                            <i class="fas fa-plus mr-2"></i> Đăng sách
                        </button>
                        @else
                        <a href="{{ route('front.login') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md flex items-center">
                            <i class="fas fa-plus mr-2"></i> Đăng nhập để đăng sách
                        </a>
                        @endauth
                    </div>

                    <!-- Category Filters -->
                    <div class="mt-4">
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('front.book.index') }}"
                                class="category-filter bg-gray-100 hover:bg-blue-500 hover:text-white px-4 py-2 rounded-full text-sm transition {{ request()->routeIs('front.book.index') && !request()->has('book_types') ? 'active bg-blue-500 text-white' : '' }}">
                                <i class="fas fa-brain mr-1"></i> Tất cả
                            </a>
                            @foreach ($booktypes as $booktype)
                                <a href="{{ route('front.book.byType', $booktype->slug) }}"
                                    class="category-filter bg-gray-100 hover:bg-blue-500 hover:text-white px-4 py-2 rounded-full text-sm transition {{ request()->is('front/book/type/' . $booktype->slug) ? 'active bg-blue-500 text-white' : '' }}">
                                    <i class="{{ $booktype->icon ?? 'fas fa-book' }} mr-1"></i> {{ $booktype->title }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Book Grid Section -->
                <section class="mb-8">
                    <div class="flex flex-col sm:flex-row justify-between items-center mb-6 bg-gray-50 p-4 rounded-lg shadow-sm">
                        <h2 class="text-xl font-bold text-gray-800 mb-3 sm:mb-0">
                            @if (isset($bookType))
                                {{ $bookType->title }}
                            @else
                                Tất cả sách
                            @endif
                        </h2>
                        <div class="w-full sm:w-auto">
                            <div class="flex flex-wrap justify-center sm:justify-end gap-2">
                                <span class="text-sm text-gray-500 self-center mr-2 hidden sm:inline-block">Sắp xếp theo:</span>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'latest']) }}" 
                                   class="sort-btn {{ request('sort') == '' || request('sort') == 'latest' ? 'bg-blue-500 text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }} 
                                          py-1.5 px-3 rounded-md text-sm font-medium transition-colors duration-200 flex items-center">
                                    <i class="fas fa-clock mr-1.5"></i>Mới nhất
                                </a>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'views']) }}" 
                                   class="sort-btn {{ request('sort') == 'views' ? 'bg-blue-500 text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }} 
                                          py-1.5 px-3 rounded-md text-sm font-medium transition-colors duration-200 flex items-center">
                                    <i class="fas fa-eye mr-1.5"></i>Lượt đọc
                                </a>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'title_asc']) }}" 
                                   class="sort-btn {{ request('sort') == 'title_asc' ? 'bg-blue-500 text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }} 
                                          py-1.5 px-3 rounded-md text-sm font-medium transition-colors duration-200 flex items-center">
                                    <i class="fas fa-sort-alpha-down mr-1.5"></i>A-Z
                                </a>
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'title_desc']) }}" 
                                   class="sort-btn {{ request('sort') == 'title_desc' ? 'bg-blue-500 text-white' : 'bg-white text-gray-700 hover:bg-gray-100' }} 
                                          py-1.5 px-3 rounded-md text-sm font-medium transition-colors duration-200 flex items-center">
                                    <i class="fas fa-sort-alpha-down-alt mr-1.5"></i>Z-A
                                </a>
                                <div class="ml-2 sm:ml-4 bg-blue-100 text-blue-800 py-1.5 px-3 rounded-md text-sm font-medium flex items-center">
                                    <i class="fas fa-book mr-1.5"></i>{{ $books->total() }} sách
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Book List -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @forelse($books as $book)
                            <div class="book-card bg-white rounded-lg overflow-hidden shadow-sm transition cursor-pointer flex"
                                data-book-id="{{ $book->id }}">
                                <div class="relative h-36 w-24 flex-shrink-0">
                                    <img src="{{ $book->photo ? $book->photo : asset('images/no-image.jpg') }}"
                                        alt="{{ $book->title }}" class="h-full w-full object-cover">
                                    <div
                                        class="absolute top-1 right-1 bg-yellow-400 text-white text-xs font-bold px-1 py-0.5 rounded-full flex items-center">
                                        <i class="fas fa-star mr-0.5 text-xs"></i>
                                        {{ number_format($book->vote_average ?? 0, 1) }}
                                    </div>
                                    @if (isset($book->is_bookmarked) && $book->is_bookmarked)
                                        <div class="absolute top-1 left-1 text-yellow-400 text-sm">
                                            <i class="fas fa-bookmark"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="p-3 flex-grow flex flex-col">
                                    <div class="flex justify-between items-start">
                                        <h3 class="font-medium text-gray-800">{{ $book->title }}</h3>
                                        <div class="text-xs text-gray-500 ml-2 flex-shrink-0">
                                            <i class="fas fa-eye mr-1"></i> {{ $book->views ?? 0 }}
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-500">{{ $book->user->name ?? 'Unknown' }}</p>
                                    <p class="text-sm text-gray-600 mt-1 mb-2 overflow-hidden line-clamp-2"
                                        style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                        {{ $book->summary ?? 'Không có mô tả' }}
                                    </p>
                                    <div class="flex flex-wrap mt-auto">
                                        <a href="{{ route('front.book.read', $book->id) }}"
                                            class="text-blue-500 hover:text-blue-700 text-sm bg-blue-50 px-3 py-1 rounded-md mr-2 mb-1">
                                            <i class="fas fa-book-open mr-1"></i> Đọc
                                        </a>
                                        @if ($book->has_audio)
                                            <a href="{{ route('front.book.show', ['id' => $book->slug, 'format' => 'audio']) }}"
                                                class="text-green-500 hover:text-green-700 text-sm bg-green-50 px-3 py-1 rounded-md mr-2 mb-1">
                                                <i class="fas fa-headphones mr-1"></i> Nghe
                                            </a>
                                        @endif
                                        @php
                                            $hasResources = false;
                                            if (!empty($book->resources)) {
                                                if (is_string($book->resources)) {
                                                    $resourcesData = json_decode($book->resources, true);
                                                    $hasResources = !empty($resourcesData['resource_ids']);
                                                } else if (is_array($book->resources) && isset($book->resources['resource_ids'])) {
                                                    $hasResources = !empty($book->resources['resource_ids']);
                                                }
                                            }
                                        @endphp
                                        @if ($hasResources)
                                            <button type="button" 
                                                class="download-resources-btn text-purple-500 hover:text-purple-700 text-sm bg-purple-50 px-3 py-1 rounded-md mr-2 mb-1"
                                                data-id="{{ $book->id }}">
                                                <i class="fas fa-download mr-1"></i> Tải
                                            </button>
                                        @endif
                                        <button type="button" 
                                            class="bookmark-btn text-{{ isset($book->is_bookmarked) && $book->is_bookmarked ? 'red' : 'gray' }}-500 hover:text-{{ isset($book->is_bookmarked) && $book->is_bookmarked ? 'red' : 'gray' }}-700 text-sm bg-{{ isset($book->is_bookmarked) && $book->is_bookmarked ? 'red' : 'gray' }}-50 px-3 py-1 rounded-md mr-2 mb-1"
                                            data-id="{{ $book->id }}" 
                                            data-code="book">
                                            <i class="{{ isset($book->is_bookmarked) && $book->is_bookmarked ? 'fas' : 'far' }} fa-heart mr-1"></i> Thích
                                        </button>
                                        <a href="{{ route('front.book.show', $book->slug) }}"
                                            class="text-gray-500 hover:text-gray-700 text-sm bg-gray-50 px-3 py-1 rounded-md mb-1">
                                            <i class="fas fa-info-circle mr-1"></i> Chi tiết
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 col-span-2">
                                <p class="text-gray-500">Không tìm thấy sách nào.</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    <nav class="flex items-center justify-between border-t border-gray-200 pt-6 mt-8">
                        <div class="hidden sm:block">
                            <p class="text-sm text-gray-700">
                                Hiển thị
                                <span class="font-medium">{{ $books->firstItem() ?? 0 }}</span>
                                đến
                                <span class="font-medium">{{ $books->lastItem() ?? 0 }}</span>
                                của
                                <span class="font-medium">{{ $books->total() }}</span> kết quả
                            </p>
                        </div>
                        <div class="flex-1 flex justify-between sm:justify-end">
                            {{ $books->appends(request()->except('page'))->links() }}
                        </div>
                    </nav>
                </section>
            </div>
        </div>
    </main>

    

    <!-- Scroll to Top Button -->
    <button id="scroll-to-top"
        class="fixed bottom-6 right-6 bg-blue-500 text-white w-12 h-12 rounded-full shadow-lg flex items-center justify-center opacity-0 invisible transition">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Create Book Modal -->
    <div id="create-book-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl w-full">
                <div class="flex justify-between items-center px-6 py-4 bg-gray-50 border-b">
                    <h3 class="text-xl font-bold text-gray-800" id="modal-title">Đăng sách mới</h3>
                    <button type="button" id="close-create-book-modal" class="text-gray-400 hover:text-gray-500">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="px-6 py-4 max-h-[80vh] overflow-y-auto">
                    <form id="create-book-form" action="{{ route('front.book.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tên sách <span class="text-red-500">*</span></label>
                            <input type="text" name="title" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Ảnh bìa</label>
                            <div class="dropzone" id="bookImageDropzone" data-url="{{ route('public.upload.avatar') }}"></div>
                            <input type="hidden" name="photo" id="uploadedBookImage">
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Thông tin</label>
                            <textarea name="summary" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nội dung</label>
                            <textarea name="content" rows="5" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tài liệu đính kèm <span class="text-red-500">*</span></label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                <div class="space-y-1 text-center">
                                    <i class="fas fa-file-upload text-gray-400 text-3xl mb-2"></i>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="document-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                            <span>Tải lên tài liệu</span>
                                            <input id="document-upload" name="document[]" type="file" class="sr-only" multiple required>
                                        </label>
                                        <p class="pl-1">hoặc kéo thả vào đây</p>
                                    </div>
                                    <p class="text-xs text-gray-500">
                                        Chấp nhận các định dạng: PDF, DOCX, JPG, PNG, MP3, MP4
                                    </p>
                                    <div id="selected-files" class="mt-2 text-sm text-gray-500"></div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="book_type_id" class="block text-sm font-medium text-gray-700 mb-2">Loại sách <span class="text-red-500">*</span></label>
                                <select name="book_type_id" id="book_type_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                    <option value="">Chọn loại sách</option>
                                    @foreach ($booktypes as $bookType)
                                        <option value="{{ $bookType->id }}">{{ $bookType->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Trạng thái</label>
                                <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                                    <option value="active">Hoạt động</option>
                                    <option value="inactive">Không hoạt động</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-4">
                            <label for="book-tags" class="block text-sm font-medium text-gray-700 mb-2">Tags</label>
                            <select id="book-tags" name="tag_ids[]" multiple placeholder="Chọn hoặc tạo tags..." class="form-control">
                                @php
                                    $tags = \App\Models\Tag::where('status', 'active')->orderBy('title', 'ASC')->get();
                                @endphp
                                @foreach ($tags as $tag)
                                    <option value="{{ $tag->id }}">{{ $tag->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-6 flex justify-end space-x-3 border-t pt-4">
                            <button type="button" id="cancel-create-book" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                                Hủy
                            </button>
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                Đăng sách
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Download Resources Modal -->
    <div id="download-resources-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <div class="flex justify-between items-center px-6 py-4 bg-gray-50 border-b">
                    <h3 class="text-xl font-bold text-gray-800" id="resources-modal-title">Tài liệu đính kèm</h3>
                    <button type="button" id="close-download-resources-modal" class="text-gray-400 hover:text-gray-500">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="px-6 py-4">
                    <div id="resources-list" class="mb-4">
                        <div class="text-center py-8">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Đang tải...</span>
                            </div>
                            <p class="mt-2 text-gray-600">Đang tải danh sách tài liệu...</p>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end space-x-3 border-t pt-4">
                        <button type="button" id="cancel-download-resources" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                            Đóng
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Ngăn Dropzone khởi tạo tự động
            Dropzone.autoDiscover = false;

            // BỎ các event listener cho dropdown user, để sử dụng cái đã thiết lập trong header.blade.php
            // KHÔNG ghi đè các event handlers từ header

            // Mobile menu toggle (chỉ khi không tồn tại từ trước)
            if (document.getElementById('mobile-menu-button') && !document.getElementById('mobile-menu-button')
                .hasAttribute('data-event-attached')) {
                document.getElementById('mobile-menu-button').setAttribute('data-event-attached', 'true');
                document.getElementById('mobile-menu-button').addEventListener('click', function() {
                    const mobileMenu = document.getElementById('mobile-menu');
                    if (mobileMenu) mobileMenu.classList.toggle('hidden');
                });
            }

            // Sort dropdown toggle
            if (document.getElementById('sort-button')) {
                document.getElementById('sort-button').addEventListener('click', function(e) {
                    e.stopPropagation();
                    const dropdown = document.getElementById('sort-dropdown');
                    if (dropdown) dropdown.classList.toggle('active');
                });
            }

            // Filter dropdown toggle
            if (document.getElementById('filter-button')) {
                document.getElementById('filter-button').addEventListener('click', function(e) {
                    e.stopPropagation();
                    const dropdown = document.getElementById('filter-dropdown');
                    if (dropdown) dropdown.classList.toggle('active');
                });
            }

            // Category filter active state
            const categoryFilters = document.querySelectorAll('.category-filter');
            categoryFilters.forEach(filter => {
                filter.addEventListener('click', function() {
                    categoryFilters.forEach(f => f.classList.remove('active', 'bg-blue-500',
                        'text-white'));
                    this.classList.add('active', 'bg-blue-500', 'text-white');
                });
            });

            // Sidebar toggle for mobile
            if (document.getElementById('sidebar-toggle')) {
                document.getElementById('sidebar-toggle').addEventListener('click', function() {
                    const sidebar = document.getElementById('left-sidebar');
                    const mainContent = document.getElementById('main-content');
                    if (sidebar) sidebar.classList.toggle('collapsed');
                    if (mainContent) mainContent.classList.toggle('expanded');
                });
            }

            // Scroll to top button
            const scrollToTopBtn = document.getElementById('scroll-to-top');
            if (scrollToTopBtn) {
                window.addEventListener('scroll', function() {
                    if (window.pageYOffset > 300) {
                        scrollToTopBtn.classList.remove('opacity-0', 'invisible');
                        scrollToTopBtn.classList.add('opacity-100', 'visible');
                    } else {
                        scrollToTopBtn.classList.remove('opacity-100', 'visible');
                        scrollToTopBtn.classList.add('opacity-0', 'invisible');
                    }
                });

                scrollToTopBtn.addEventListener('click', function() {
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                });
            }

            // Quick View Modal functionality
            const quickViewModal = document.getElementById('quick-view-modal');
            const closeQuickViewBtn = document.getElementById('close-quick-view');
            const modalLoading = document.getElementById('modal-loading');
            const modalContent = document.getElementById('modal-content');

            // Get all quick view buttons
            const quickViewBtns = document.querySelectorAll('.quick-view-btn');

            // Function to create action buttons
            function createActionButtons(data) {
                const actionContainer = document.getElementById('modal-book-action-buttons');
                actionContainer.innerHTML = '';

                // Read button - always show
                const readBtn = document.createElement('a');
                readBtn.href = `/front/book/read/${data.id}`;
                readBtn.className =
                    'bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-md flex items-center';
                readBtn.innerHTML = '<i class="fas fa-book-open mr-2"></i> Đọc sách';
                actionContainer.appendChild(readBtn);

                // Audio button - only if available
                if (data.has_audio) {
                    const audioBtn = document.createElement('a');
                    audioBtn.href = `/front/book/show/${data.slug}?format=audio`;
                    audioBtn.className =
                        'bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-md flex items-center';
                    audioBtn.innerHTML = '<i class="fas fa-headphones mr-2"></i> Nghe audio';
                    actionContainer.appendChild(audioBtn);
                }

                // Details button - always show
                const detailsBtn = document.createElement('a');
                detailsBtn.href = `/front/book/show/${data.slug}`;
                detailsBtn.className =
                    'bg-gray-100 hover:bg-gray-200 text-gray-800 px-6 py-2 rounded-md flex items-center';
                detailsBtn.innerHTML = '<i class="fas fa-info-circle mr-2"></i> Chi tiết';
                actionContainer.appendChild(detailsBtn);

                // Bookmark button - only if user is logged in
                if (data.can_bookmark) {
                    const bookmarkBtn = document.createElement('button');
                    bookmarkBtn.type = 'button';
                    bookmarkBtn.className =
                        'bookmark-btn bg-gray-100 hover:bg-gray-200 text-gray-800 px-6 py-2 rounded-md flex items-center';
                    bookmarkBtn.setAttribute('data-book-id', data.id);
                    bookmarkBtn.innerHTML = data.is_bookmarked ?
                        '<i class="fas fa-bookmark mr-2"></i> Đã lưu' :
                        '<i class="far fa-bookmark mr-2"></i> Lưu lại';

                    // Add click handler for bookmark button
                    bookmarkBtn.addEventListener('click', function() {
                        const bookId = this.getAttribute('data-book-id');
                        fetch('/front/book/bookmark', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                        .getAttribute('content')
                                },
                                body: JSON.stringify({
                                    book_id: bookId
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.status) {
                                    // Toggle the bookmark icon and text
                                    if (data.action === 'added') {
                                        bookmarkBtn.innerHTML =
                                            '<i class="fas fa-bookmark mr-2"></i> Đã lưu';
                                    } else {
                                        bookmarkBtn.innerHTML =
                                            '<i class="far fa-bookmark mr-2"></i> Lưu lại';
                                    }
                                } else {
                                    alert('Có lỗi xảy ra. Vui lòng thử lại sau.');
                                }
                            })
                            .catch(error => {
                                console.error('Error bookmarking:', error);
                                alert('Có lỗi xảy ra. Vui lòng thử lại sau.');
                            });
                    });

                    actionContainer.appendChild(bookmarkBtn);
                }
            }

            // Add click event to each quick view button
            quickViewBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    // Get the book card
                    const bookCard = this.closest('.book-card');

                    // Get book ID for AJAX request
                    const bookId = bookCard.getAttribute('data-book-id');

                    // Temporarily update modal with basic information from card
                    const bookTitle = bookCard.querySelector('h3').innerText;
                    const bookAuthor = bookCard.querySelector('p').innerText;
                    const bookCover = bookCard.querySelector('img').getAttribute('src');

                    document.getElementById('modal-book-title').innerText = bookTitle;
                    document.getElementById('modal-book-author').innerText = bookAuthor;
                    document.getElementById('modal-book-cover').setAttribute('src', bookCover);

                    // Clear previous content
                    document.getElementById('modal-book-stars').innerHTML = '';
                    document.getElementById('modal-book-rating').innerText = '';
                    document.getElementById('modal-book-tags').innerHTML = '';
                    document.getElementById('modal-book-summary').innerText = 'Đang tải...';
                    document.getElementById('modal-book-action-buttons').innerHTML = '';

                    // Show modal with loading state
                    quickViewModal.classList.add('active');
                    modalLoading.classList.remove('hidden');
                    modalContent.classList.add('opacity-50');

                    // Prevent scrolling on the body
                    document.body.style.overflow = 'hidden';

                    // Fetch book details with AJAX
                    fetch(`/api/books/${bookId}/details`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('Book data:', data); // For debugging

                            // Hide loading spinner, show content
                            modalLoading.classList.add('hidden');
                            modalContent.classList.remove('opacity-50');

                            // Update modal content with actual data
                            document.getElementById('modal-book-title').innerText = data
                                .title || bookTitle;
                            document.getElementById('modal-book-author').innerText = data
                                .author || bookAuthor;
                            document.getElementById('modal-book-cover').setAttribute('src', data
                                .photo || bookCover);

                            // Update rating and stars
                            const starsContainer = document.getElementById('modal-book-stars');
                            starsContainer.innerHTML = '';

                            const rating = data.vote_average || 0;
                            document.getElementById('modal-book-rating').innerText =
                                `${parseFloat(rating).toFixed(1)} (${data.vote_count || 0} đánh giá)`;

                            // Generate stars
                            for (let i = 1; i <= 5; i++) {
                                const star = document.createElement('i');
                                if (i <= rating) {
                                    star.className = 'fas fa-star';
                                } else if (i - 0.5 <= rating) {
                                    star.className = 'fas fa-star-half-alt';
                                } else {
                                    star.className = 'far fa-star';
                                }
                                starsContainer.appendChild(star);
                            }

                            // Update view count
                            if (data.views !== undefined) {
                                document.getElementById('modal-book-views').innerHTML =
                                    `<i class="fas fa-eye mr-1"></i> ${data.views} lượt xem`;
                            }

                            // Update tags
                            const tagsContainer = document.getElementById('modal-book-tags');
                            tagsContainer.innerHTML = '';

                            if (data.tags && data.tags.length > 0) {
                                data.tags.forEach(tag => {
                                    const tagSpan = document.createElement('span');
                                    tagSpan.className =
                                        'bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs';
                                    tagSpan.innerText = tag.title;
                                    tagsContainer.appendChild(tagSpan);
                                });
                            } else if (data.book_type) {
                                const tagSpan = document.createElement('span');
                                tagSpan.className =
                                    'bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs';
                                tagSpan.innerText = data.book_type;
                                tagsContainer.appendChild(tagSpan);
                            } else {
                                const tagSpan = document.createElement('span');
                                tagSpan.className = 'text-gray-500 text-sm';
                                tagSpan.innerText = 'Không có thẻ';
                                tagsContainer.appendChild(tagSpan);
                            }

                            // Update summary
                            document.getElementById('modal-book-summary').innerText = data
                                .summary || 'Không có mô tả cho sách này.';

                            // Update metadata fields
                            const updateMetadataField = (containerId, valueId, value) => {
                                const container = document.getElementById(containerId);
                                if (value) {
                                    document.getElementById(valueId).innerText = value;
                                    container.classList.remove('hidden');
                                } else {
                                    container.classList.add('hidden');
                                }
                            };

                            updateMetadataField('modal-book-publisher-container',
                                'modal-book-publisher', data.publisher);
                            updateMetadataField('modal-book-year-container', 'modal-book-year',
                                data.published_year);
                            updateMetadataField('modal-book-pages-container',
                                'modal-book-pages', data.pages);
                            updateMetadataField('modal-book-language-container',
                                'modal-book-language', data.language);

                            // Create action buttons
                            createActionButtons(data);
                        })
                        .catch(error => {
                            console.error('Error fetching book details:', error);

                            // Hide loading spinner, show content with basic info
                            modalLoading.classList.add('hidden');
                            modalContent.classList.remove('opacity-50');

                            // Show error message
                            document.getElementById('modal-book-summary').innerText =
                                'Không thể tải thông tin sách. Vui lòng thử lại sau.';

                            // Create minimal action buttons with available data
                            const actionContainer = document.getElementById(
                                'modal-book-action-buttons');
                            actionContainer.innerHTML = '';

                            // Details button - always show
                            const detailsBtn = document.createElement('a');
                            detailsBtn.href =
                            `/front/book/show/${bookId}`; // Fallback to using ID
                            detailsBtn.className =
                                'bg-gray-100 hover:bg-gray-200 text-gray-800 px-6 py-2 rounded-md flex items-center';
                            detailsBtn.innerHTML =
                                '<i class="fas fa-info-circle mr-2"></i> Chi tiết';
                            actionContainer.appendChild(detailsBtn);
                        });
                });
            });

            // Close modal when clicking the close button
            if (closeQuickViewBtn) {
                closeQuickViewBtn.addEventListener('click', function() {
                    quickViewModal.classList.remove('active');
                    document.body.style.overflow = 'auto';

                    // Clear content after closing
                    setTimeout(() => {
                        document.getElementById('modal-book-summary').innerText = '';
                        document.getElementById('modal-book-action-buttons').innerHTML = '';
                    }, 300);
                });
            }

            // Close modal when clicking outside the modal content
            if (quickViewModal) {
                quickViewModal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        this.classList.remove('active');
                        document.body.style.overflow = 'auto';
                    }
                });
            }

            // Close modal with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && quickViewModal && quickViewModal.classList.contains('active')) {
                    quickViewModal.classList.remove('active');
                    document.body.style.overflow = 'auto';
                }
            });
            
            // ===== BOOK CREATION MODAL SCRIPT =====
            // Xử lý modal đăng sách mới
            const createBookBtn = document.getElementById('create-book-btn');
            const createBookModal = document.getElementById('create-book-modal');
            const closeCreateBookModalBtn = document.getElementById('close-create-book-modal');
            const cancelCreateBookBtn = document.getElementById('cancel-create-book');
            const createBookForm = document.getElementById('create-book-form');
            const documentUpload = document.getElementById('document-upload');
            const selectedFilesContainer = document.getElementById('selected-files');
            
            // Mở modal đăng sách
            if (createBookBtn) {
                createBookBtn.addEventListener('click', function() {
                    createBookModal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                    
                    // Khởi tạo TomSelect cho tags
                    setTimeout(() => {
                        if (!window.bookTagsSelect && document.getElementById('book-tags')) {
                            console.log('Initializing TomSelect...');
                            try {
                                window.bookTagsSelect = new TomSelect('#book-tags', {
                                    maxItems: null,
                                    valueField: 'id',
                                    labelField: 'title',
                                    searchField: 'title',
                                    create: function(input) {
                                        console.log('Creating new tag:', input);
                                        return {
                                            id: 'new_' + input,
                                            title: input
                                        };
                                    },
                                    createFilter: function(input) {
                                        return input.length >= 2;
                                    },
                                    createOnBlur: true,
                                    plugins: ['remove_button'],
                                    onInitialize: function(){
                                        console.log('TomSelect initialized successfully');
                                    }
                                });
                            } catch(err) {
                                console.error('TomSelect initialization error:', err);
                            }
                        }
                    }, 200);
                    
                    // Khởi tạo Dropzone cho upload ảnh
                    setTimeout(() => {
                        if (document.getElementById('bookImageDropzone')) {
                            console.log('Initializing Dropzone...');
                            try {
                                // Xóa instance cũ nếu có
                                if (window.bookImageDropzone) {
                                    try {
                                        if (typeof window.bookImageDropzone.destroy === 'function') {
                                            window.bookImageDropzone.destroy();
                                        }
                                    } catch (e) {
                                        console.error('Lỗi khi hủy Dropzone cũ:', e);
                                    }
                                }
                                
                                // Đảm bảo rằng Dropzone.autoDiscover = false để tránh tự động khởi tạo
                                Dropzone.autoDiscover = false;
                                
                                window.bookImageDropzone = new Dropzone('#bookImageDropzone', {
                                    url: "{{ route('public.upload.avatar') }}",
                                    paramName: "photo",
                                    maxFilesize: 5,
                                    acceptedFiles: 'image/*',
                                    addRemoveLinks: true,
                                    dictDefaultMessage: "Kéo thả ảnh vào đây hoặc nhấp để chọn",
                                    dictRemoveFile: "Xóa ảnh",
                                    thumbnailWidth: 150,
                                    thumbnailHeight: 150,
                                    maxFiles: 1,
                                    headers: {
                                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                                    },
                                    success: function(file, response) {
                                        console.log('Upload success:', response);
                                        if (response && response.link) {
                                            document.getElementById('uploadedBookImage').value = response.link;
                                        } else if (response && response.url) {
                                            document.getElementById('uploadedBookImage').value = response.url;
                                        } else {
                                            console.error('Upload response lacks link or url:', response);
                                            alert('Tải lên thành công nhưng không nhận được đường dẫn ảnh');
                                        }
                                    },
                                    removedfile: function(file) {
                                        document.getElementById('uploadedBookImage').value = '';
                                        if (file.previewElement) {
                                            file.previewElement.remove();
                                        }
                                    },
                                    error: function(file, errorMessage) {
                                        console.error("Dropzone error:", errorMessage);
                                        console.log("File causing error:", file);
                                        alert("Lỗi tải lên: " + (typeof errorMessage === 'string' ? errorMessage : JSON.stringify(errorMessage)));
                                    },
                                    init: function() {
                                        console.log('Dropzone initialized successfully');
                                        
                                        this.on("addedfile", function(file) {
                                            console.log("File added:", file);
                                        });
                                        
                                        this.on("sending", function(file, xhr, formData) {
                                            console.log("Sending file...");
                                            formData.append("_token", "{{ csrf_token() }}");
                                            
                                            // Thêm event listener để theo dõi quá trình request
                                            xhr.onreadystatechange = function() {
                                                if (xhr.readyState === 4) {
                                                    console.log('Response status:', xhr.status);
                                                    console.log('Response text:', xhr.responseText);
                                                    if (xhr.status !== 200) {
                                                        console.error('Server error:', xhr.status, xhr.statusText);
                                                        try {
                                                            const response = JSON.parse(xhr.responseText);
                                                            console.error('Server error details:', response);
                                                        } catch (e) {
                                                            console.error('Unable to parse response:', e);
                                                        }
                                                    }
                                                }
                                            };
                                        });
                                        
                                        this.on("complete", function(file) {
                                            console.log("Upload complete for file:", file.name);
                                        });
                                    }
                                });
                            } catch(err) {
                                console.error('Dropzone initialization error:', err);
                            }
                        }
                    }, 300);
                });
            }
            
            // Đóng modal đăng sách
            function closeCreateBookModal() {
                createBookModal.classList.add('hidden');
                document.body.style.overflow = 'auto';
                
                // Reset form
                if (createBookForm) createBookForm.reset();
                
                // Reset Dropzone
                if (window.bookImageDropzone) {
                    if (typeof window.bookImageDropzone.removeAllFiles === 'function') {
                        window.bookImageDropzone.removeAllFiles(true);
                    } else {
                        console.log('removeAllFiles không khả dụng, thử các phương pháp thay thế');
                        // Phương pháp thay thế: xóa các phần tử DOM của Dropzone
                        const dropzoneElement = document.getElementById('bookImageDropzone');
                        if (dropzoneElement) {
                            const previewElements = dropzoneElement.querySelectorAll('.dz-preview');
                            previewElements.forEach(el => el.remove());
                        }
                    }
                }
                
                // Reset TomSelect
                if (window.bookTagsSelect) {
                    window.bookTagsSelect.clear();
                }
                
                // Reset selected files
                if (selectedFilesContainer) {
                    selectedFilesContainer.innerHTML = '';
                }
                
                // Reset hidden input
                document.getElementById('uploadedBookImage').value = '';
            }
            
            if (closeCreateBookModalBtn) {
                closeCreateBookModalBtn.addEventListener('click', closeCreateBookModal);
            }
            
            if (cancelCreateBookBtn) {
                cancelCreateBookBtn.addEventListener('click', closeCreateBookModal);
            }
            
            // Đóng modal khi click ngoài content
            if (createBookModal) {
                createBookModal.addEventListener('click', function(e) {
                    if (e.target === this || e.target.classList.contains('fixed')) {
                        closeCreateBookModal();
                    }
                });
            }
            
            // Hiển thị tên file khi người dùng chọn file
            if (documentUpload) {
                documentUpload.addEventListener('change', function() {
                    if (selectedFilesContainer) {
                        selectedFilesContainer.innerHTML = '';
                        
                        if (this.files.length > 0) {
                            const fileList = document.createElement('ul');
                            fileList.className = 'list-disc pl-5 text-left';
                            
                            for (let i = 0; i < this.files.length; i++) {
                                const file = this.files[i];
                                const fileItem = document.createElement('li');
                                fileItem.className = 'text-blue-600';
                                fileItem.textContent = file.name + ' (' + formatFileSize(file.size) + ')';
                                fileList.appendChild(fileItem);
                            }
                            
                            selectedFilesContainer.appendChild(fileList);
                        }
                    }
                });
            }
            
            // Format file size
            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }
            
            // Xử lý drag & drop cho khu vực tải lên tài liệu
            const dropArea = document.querySelector('.border-dashed');
            if (dropArea && documentUpload) {
                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                    dropArea.addEventListener(eventName, preventDefaults, false);
                });
                
                function preventDefaults(e) {
                    e.preventDefault();
                    e.stopPropagation();
                }
                
                ['dragenter', 'dragover'].forEach(eventName => {
                    dropArea.addEventListener(eventName, function() {
                        this.classList.add('border-blue-500', 'bg-blue-50');
                    });
                });
                
                ['dragleave', 'drop'].forEach(eventName => {
                    dropArea.addEventListener(eventName, function() {
                        this.classList.remove('border-blue-500', 'bg-blue-50');
                    });
                });
                
                dropArea.addEventListener('drop', function(e) {
                    const dt = e.dataTransfer;
                    const files = dt.files;
                    documentUpload.files = files;
                    
                    // Trigger change event
                    const event = new Event('change');
                    documentUpload.dispatchEvent(event);
                });
            }
            
            // Submit form với progress indicator
            if (createBookForm) {
                createBookForm.addEventListener('submit', function(e) {
                    const submitBtn = this.querySelector('button[type="submit"]');
                    
                    // Validate form
                    if (!this.checkValidity()) {
                        return;
                    }
                    
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Đang xử lý...';
                    }
                });
            }

            // Xử lý nút yêu thích sách
            document.querySelectorAll('.bookmark-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const bookId = this.getAttribute('data-id');
                    const itemCode = this.getAttribute('data-code');
                    const button = this;
                    
                    fetch('{{ route('front.book.bookmark') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            item_id: bookId,
                            item_code: itemCode
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Cập nhật giao diện
                            if (data.isBookmarked) {
                                button.classList.remove('text-gray-500', 'hover:text-gray-700', 'bg-gray-50');
                                button.classList.add('text-red-500', 'hover:text-red-700', 'bg-red-50');
                                button.querySelector('i').classList.remove('far');
                                button.querySelector('i').classList.add('fas');
                            } else {
                                button.classList.remove('text-red-500', 'hover:text-red-700', 'bg-red-50');
                                button.classList.add('text-gray-500', 'hover:text-gray-700', 'bg-gray-50');
                                button.querySelector('i').classList.remove('fas');
                                button.querySelector('i').classList.add('far');
                            }
                        } else {
                            // Nếu chưa đăng nhập, chuyển hướng đến trang đăng nhập
                            if (data.msg === 'Bạn phải đăng nhập') {
                                window.location.href = '{{ route('front.login') }}';
                            } else {
                                alert(data.msg || 'Có lỗi xảy ra, vui lòng thử lại sau.');
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Lỗi:', error);
                        alert('Đã xảy ra lỗi khi xử lý yêu cầu.');
                    });
                });
            });

            // Thêm hiệu ứng hover cho các nút sắp xếp
            document.querySelectorAll('.sort-btn').forEach(btn => {
                btn.addEventListener('mouseenter', function() {
                    if (!this.classList.contains('bg-blue-500')) {
                        this.classList.add('shadow-sm');
                    }
                });
                
                btn.addEventListener('mouseleave', function() {
                    this.classList.remove('shadow-sm');
                });
            });

            // Xử lý nút tải xuống tài liệu
            const downloadResourcesBtns = document.querySelectorAll('.download-resources-btn');
            const downloadResourcesModal = document.getElementById('download-resources-modal');
            const closeDownloadResourcesBtn = document.getElementById('close-download-resources-modal');
            const cancelDownloadResourcesBtn = document.getElementById('cancel-download-resources');
            const resourcesList = document.getElementById('resources-list');
            
            // Mở modal tải xuống tài liệu
            downloadResourcesBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const bookId = this.getAttribute('data-id');
                    downloadResourcesModal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                    
                    // Fetch tài liệu của sách
                    fetch(`/api/books/${bookId}/resources`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            // Hiển thị danh sách tài liệu
                            displayResources(data.resources, bookId);
                        })
                        .catch(error => {
                            console.error('Lỗi khi tải tài liệu:', error);
                            resourcesList.innerHTML = `
                                <div class="alert alert-danger">
                                    <i class="fas fa-exclamation-circle mr-2"></i>
                                    Có lỗi xảy ra khi tải danh sách tài liệu. Vui lòng thử lại sau.
                                </div>
                            `;
                        });
                });
            });
            
            // Đóng modal tải xuống tài liệu
            function closeDownloadResourcesModal() {
                downloadResourcesModal.classList.add('hidden');
                document.body.style.overflow = 'auto';
                resourcesList.innerHTML = `
                    <div class="text-center py-8">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Đang tải...</span>
                        </div>
                        <p class="mt-2 text-gray-600">Đang tải danh sách tài liệu...</p>
                    </div>
                `;
            }
            
            if (closeDownloadResourcesBtn) {
                closeDownloadResourcesBtn.addEventListener('click', closeDownloadResourcesModal);
            }
            
            if (cancelDownloadResourcesBtn) {
                cancelDownloadResourcesBtn.addEventListener('click', closeDownloadResourcesModal);
            }
            
            // Đóng modal khi click ngoài content
            if (downloadResourcesModal) {
                downloadResourcesModal.addEventListener('click', function(e) {
                    if (e.target === this || e.target.classList.contains('fixed')) {
                        closeDownloadResourcesModal();
                    }
                });
            }
            
            // Hiển thị danh sách tài liệu
            function displayResources(resources, bookId) {
                if (!resources || resources.length === 0) {
                    resourcesList.innerHTML = `
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle mr-2"></i>
                            Không có tài liệu nào khả dụng cho sách này.
                        </div>
                    `;
                    return;
                }
                
                let html = '<div class="rounded-lg border border-gray-200 overflow-hidden">';
                html += '<ul class="divide-y divide-gray-200">';
                
                resources.forEach(resource => {
                    let icon = resource.icon_class || 'fas fa-file';
                    let bgColor = 'bg-gray-100';
                    
                    // Xác định màu nền dựa trên icon
                    if (icon.includes('pdf')) {
                        bgColor = 'bg-red-100';
                    } else if (icon.includes('word')) {
                        bgColor = 'bg-blue-100';
                    } else if (icon.includes('excel')) {
                        bgColor = 'bg-green-100';
                    } else if (icon.includes('powerpoint')) {
                        bgColor = 'bg-orange-100';
                    } else if (icon.includes('image')) {
                        bgColor = 'bg-purple-100';
                    } else if (icon.includes('audio')) {
                        bgColor = 'bg-pink-100';
                    } else if (icon.includes('video')) {
                        bgColor = 'bg-indigo-100';
                    } else if (icon.includes('archive')) {
                        bgColor = 'bg-yellow-100';
                    } else if (icon.includes('code')) {
                        bgColor = 'bg-cyan-100';
                    } else if (icon.includes('alt')) {
                        bgColor = 'bg-gray-100';
                    }
                    
                    // Nếu là link YouTube, hiển thị icon YouTube
                    if (resource.link_code === 'youtube') {
                        icon = 'fab fa-youtube';
                        bgColor = 'bg-red-100';
                    }
                    
                    // Hiển thị định dạng file dễ đọc
                    let fileType = resource.file_type || 'Không xác định';
                    if (fileType.includes('application/pdf')) {
                        fileType = 'PDF Document';
                    } else if (fileType.includes('application/msword') || fileType.includes('application/vnd.openxmlformats-officedocument.wordprocessingml.document')) {
                        fileType = 'Word Document';
                    } else if (fileType.includes('application/vnd.ms-excel') || fileType.includes('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')) {
                        fileType = 'Excel Spreadsheet';
                    } else if (fileType.includes('application/vnd.ms-powerpoint') || fileType.includes('application/vnd.openxmlformats-officedocument.presentationml.presentation')) {
                        fileType = 'PowerPoint Presentation';
                    } else if (fileType.includes('image/')) {
                        fileType = fileType.replace('image/', 'Hình ảnh ');
                    } else if (fileType.includes('audio/')) {
                        fileType = fileType.replace('audio/', 'Âm thanh ');
                    } else if (fileType.includes('video/')) {
                        fileType = fileType.replace('video/', 'Video ');
                    }
                    
                    html += `
                        <li class="p-4 hover:bg-gray-50">
                            <div class="flex items-start space-x-4">
                                <div class="${bgColor} text-gray-700 p-2 rounded-lg">
                                    <i class="${icon} text-lg"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">
                                        ${resource.title || 'Tài liệu không có tiêu đề'}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        ${fileType}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        ${resource.file_size ? formatFileSize(resource.file_size) : 'Không có thông tin kích thước'}
                                    </p>
                                </div>
                                <div>
                                    ${resource.link_code === 'youtube' ? 
                                        `<a href="${resource.url}" target="_blank" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                            <i class="fas fa-external-link-alt mr-1"></i> Xem trên YouTube
                                        </a>` : 
                                        (resource.is_downloadable !== false ? 
                                            `<a href="${resource.url}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm font-medium" download>
                                                <i class="fas fa-download mr-1"></i> Tải xuống
                                            </a>` :
                                            `<a href="${resource.url}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                <i class="fas fa-external-link-alt mr-1"></i> Mở liên kết
                                            </a>`
                                        )
                                    }
                                </div>
                            </div>
                        </li>
                    `;
                });
                
                html += '</ul></div>';
                resourcesList.innerHTML = html;
            }
            
            // Format file size
            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
@endsection
