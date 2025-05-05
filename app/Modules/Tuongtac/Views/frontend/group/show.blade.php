{{-- @extends('frontend.layouts.master') --}}
@extends('Tuongtac::frontend.blogs.body')
@section('title', $group->title ?? 'Nhóm học tập')

@php
    // Chuẩn bị dữ liệu cho yêu cầu tham gia
    $formattedRequests = [];
    if (isset($joinRequests) && count($joinRequests) > 0) {
        foreach ($joinRequests as $req) {
            if (isset($req->user)) {
                $formattedRequests[] = [
                    'id' => $req->id,
                    'user' => [
                        'id' => $req->user->id,
                        'full_name' => $req->user->full_name ?? ($req->user->name ?? 'N/A'),
                        'photo' => $req->user->photo ?? null,
                    ],
                    'created_at_human' => isset($req->created_at) ? $req->created_at->diffForHumans() : 'Vừa xong',
                ];
            }
        }
    }
@endphp

@section('css')
    <!-- Dropzone CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css">
    <!-- Tom Select CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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

        .group-banner {
            height: 300px;
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .group-banner-edit-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            opacity: 0;
            background-color: rgba(255, 255, 255, 0.8);
            color: #333;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity 0.3s ease;
            z-index: 30;
        }

        .group-banner:hover .group-banner-edit-btn {
            opacity: 1;
        }

        .group-avatar-container {
            position: relative;
            min-width: 128px;
            min-height: 128px;
            width: 128px;
            height: 128px;
            margin-top: -64px;
            margin-right: 16px;
            flex-shrink: 0;
        }

        .group-avatar {
            width: 100%;
            height: 100%;
            border: 5px solid white;
            border-radius: 50%;
            object-fit: cover;
            background-color: white;
        }

        .group-avatar-edit-btn {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0;
            background-color: rgba(255, 255, 255, 0.8);
            color: #333;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity 0.3s ease;
            z-index: 20;
        }

        .group-avatar-container:hover .group-avatar-edit-btn {
            opacity: 1;
        }

        .group-info {
            flex-grow: 1;
            padding-top: 10px;
        }

        .mobile-sidebar-toggle {
            display: none;
        }

        /* CSS cho Right Sidebar */
        .right-sidebar {
            transition: all 0.3s ease;
            position: relative;
        }

        .right-sidebar .bg-white {
            position: sticky;
            top: 20px;
        }

        .right-sidebar-mobile {
            display: none;
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
        }

        .right-sidebar-mobile::-webkit-scrollbar {
            height: 6px;
        }

        .right-sidebar-mobile::-webkit-scrollbar-thumb {
            background-color: rgba(0, 0, 0, 0.2);
            border-radius: 3px;
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

            .mobile-sidebar-toggle {
                display: block;
            }

            .group-banner {
                height: 200px;
            }

            .group-avatar-container {
                width: 100px;
                height: 100px;
                min-width: 100px;
                min-height: 100px;
                margin-top: -50px;
            }

            .group-actions-mobile {
                display: flex;
            }

            .group-actions-desktop {
                display: none;
            }
        }

        @media (min-width: 769px) {
            .right-sidebar-mobile {
                display: none;
            }

            .group-actions-mobile {
                display: none;
            }

            .group-actions-desktop {
                display: flex;
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

        .member-request-item:hover {
            background-color: #f8fafc;
        }
    </style>
@endsection

@section('content')
    {{-- group --}}

    <!-- Group Banner Section -->
    <section class="group-banner bg-gradient-to-r from-blue-500 to-blue-600 relative">
        <div class="absolute inset-0 bg-black bg-opacity-30"></div>
        
        @if($group->cover_photo)
            <div class="absolute inset-0 overflow-hidden">
                <img src="{{ asset($group->cover_photo) }}" 
                     class="w-full h-full object-cover" alt="Banner nhóm">
            </div>
        @endif
        
        <!-- Banner Edit Button -->
        @if(Auth::check() && Auth::id() == $group->author_id)
        <button id="edit-cover-btn" class="group-banner-edit-btn">
            <i class="fas fa-pencil-alt"></i>
        </button>
        @endif
        
        <div class="container mx-auto px-4 relative z-10 h-full flex items-end pb-8">
            <div class="flex flex-col md:flex-row items-start md:items-end w-full">
                <div class="flex items-end">
                    <div class="group-avatar-container">
                        <img src="{{ $group->photo ? asset($group->photo) : asset('images/lego-head.png') }}"
                            alt="{{ $group->title }}" class="group-avatar">
                        @if(Auth::check() && Auth::id() == $group->author_id)
                        <button id="edit-photo-btn" class="group-avatar-edit-btn">
                            <i class="fas fa-pencil-alt"></i>
                        </button>
                        @endif
                    </div>
                    <div class="group-info mb-4">
                        <!-- Thêm thông tin loại nhóm vào phần thông tin nhóm trên banner -->
                        <div class="flex items-center">
                            <h1 class="text-3xl font-bold text-white">{{ $group->title }}</h1>
                            
                            <span style="margin-left: 0.75rem; padding: 0.25rem 0.5rem; border-radius: 0.25rem; color: white; background-color: {{ $group->is_private ? '#dc3545' : '#28a745' }};">
                                {{ $group->is_private ? 'Riêng tư' : 'Công khai' }}
                            </span>
                            
                            @if(isset($group->groupType))
                            <span style="margin-left: 0.75rem; padding: 0.25rem 0.5rem; border-radius: 0.25rem; color: white; background-color: #17a2b8;">
                                <i class="fas fa-tag mr-1"></i> {{ $group->groupType->title }}
                            </span>
                            @endif
                        </div>
                        <p class="text-white mt-2">{{ $group->description }}</p>
                        <div class="flex items-center text-white text-sm mt-2">
                            @if ($isMember || !$group->is_private)
                                <span class="flex items-center mr-4">
                                    <i class="fas fa-users mr-1"></i>
                                    {{ count(json_decode($group->members ?? '[]', true)) }}
                                    thành viên
                                </span>
                                <span class="flex items-center mr-4">
                                    <i class="fas fa-newspaper mr-1"></i>
                                    {{ isset($posts) ? (method_exists($posts, 'total') ? $posts->total() : count($posts)) : 0 }}
                                    bài viết
                                </span>
                            @else
                                <span class="flex items-center mr-4">
                                    <i class="fas fa-lock mr-1"></i> Nhóm riêng tư
                                </span>
                            @endif
                            <span class="flex items-center">
                                <i class="fas fa-calendar-alt mr-1"></i> Thành lập
                                {{ date('d/m/Y', strtotime($group->created_at)) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Group Actions (Desktop) -->
                <div class="group-actions-desktop ml-auto space-x-3 mb-4">
                    @if (Auth::check())
                        @if ($isMember)
                            <a href="javascript:void(0);" onclick="openCreatePostModal()"
                                class="bg-white text-blue-600 px-4 py-2 rounded-md flex items-center font-medium hover:bg-gray-100 whitespace-nowrap">
                                <i class="fas fa-plus mr-2"></i> Đăng bài
                            </a>
                            @if (Auth::id() == $group->author_id)
                            <form action="{{ route('group.destroy', $group->id) }}" method="POST" class="inline" onsubmit="return confirm('Bạn có chắc chắn muốn giải tán nhóm này không? Hành động này không thể hoàn tác.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="bg-red-500 text-white px-4 py-2 rounded-md flex items-center font-medium hover:bg-red-600 whitespace-nowrap">
                                    <i class="fas fa-trash-alt mr-2"></i> Giải tán nhóm
                                </button>
                            </form>
                            @else
                            <form action="{{ route('group.leave', $group->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit"
                                    class="bg-red-500 text-white px-4 py-2 rounded-md flex items-center font-medium hover:bg-red-600 whitespace-nowrap">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Rời nhóm
                                </button>
                            </form>
                            @endif
                        @else
                            <form action="{{ route('group.join', $group->id) }}" method="POST" class="inline">
                                @csrf
                                @if (isset($joinRequest) && $joinRequest && $joinRequest->status == 'pending')
                                    <button type="button"
                                        class="bg-yellow-500 text-white px-4 py-2 rounded-md flex items-center font-medium whitespace-nowrap"
                                        disabled>
                                        <i class="fas fa-clock mr-2"></i> Đang chờ duyệt
                                    </button>
                                @else
                                    <button type="submit"
                                        class="bg-white text-blue-600 px-4 py-2 rounded-md flex items-center font-medium hover:bg-gray-100 whitespace-nowrap">
                                        <i class="fas fa-user-plus mr-2"></i>
                                        {{ $group->is_private ? 'Yêu cầu tham gia' : 'Tham gia nhóm' }}
                                    </button>
                                @endif
                            </form>
                        @endif
                        @if (Auth::id() == $group->author_id)
                            <a href="{{ route('group.edit', $group->id) }}"
                                class="bg-gray-200 text-gray-800 px-4 py-2 rounded-md flex items-center font-medium hover:bg-gray-300 whitespace-nowrap">
                                <i class="fas fa-cog mr-2"></i> Chỉnh sửa
                            </a>
                        @endif
                    @else
                        <a href="{{ route('front.login') }}"
                            class="bg-white text-blue-600 px-4 py-2 rounded-md flex items-center font-medium hover:bg-gray-100 whitespace-nowrap">
                            <i class="fas fa-sign-in-alt mr-2"></i> Đăng nhập để tham gia
                        </a>
                    @endif
                </div>

            </div>
        </div>
    </section>

    <!-- Group Actions (Mobile) -->
    <div class="group-actions-mobile bg-white shadow-sm py-2 px-4">
        <div class="flex justify-between">
            @if (Auth::check() && $isMember)
                <a href="javascript:void(0);" onclick="openCreatePostModal()"
                    class="text-blue-600 flex flex-col items-center text-xs">
                    <i class="fas fa-plus text-lg mb-1"></i>
                    <span>Đăng bài</span>
                </a>
                @if (Auth::id() == $group->author_id)
                <form action="{{ route('group.destroy', $group->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn giải tán nhóm này không? Hành động này không thể hoàn tác.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-500 flex flex-col items-center text-xs">
                        <i class="fas fa-trash-alt text-lg mb-1"></i>
                        <span>Giải tán</span>
                    </button>
                </form>
                @else
                <form action="{{ route('group.leave', $group->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="text-red-500 flex flex-col items-center text-xs">
                        <i class="fas fa-sign-out-alt text-lg mb-1"></i>
                        <span>Rời nhóm</span>
                    </button>
                </form>
                @endif
                @if (Auth::id() == $group->author_id)
                    <a href="{{ route('group.edit', $group->id) }}"
                        class="text-gray-600 flex flex-col items-center text-xs">
                        <i class="fas fa-cog text-lg mb-1"></i>
                        <span>Chỉnh sửa</span>
                    </a>
                @endif
            @elseif(Auth::check())
                <form action="{{ route('group.join', $group->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="text-blue-600 flex flex-col items-center text-xs">
                        <i class="fas fa-user-plus text-lg mb-1"></i>
                        <span>{{ $group->is_private ? 'Yêu cầu tham gia' : 'Tham gia' }}</span>
                    </button>
                </form>
            @else
                <a href="{{ route('front.login') }}" class="text-blue-600 flex flex-col items-center text-xs">
                    <i class="fas fa-sign-in-alt text-lg mb-1"></i>
                    <span>Đăng nhập</span>
                </a>
            @endif
            <button id="mobile-group-menu" class="text-gray-600 flex flex-col items-center text-xs">
                <i class="fas fa-ellipsis-h text-lg mb-1"></i>
                <span>Thêm</span>
            </button>
        </div>
    </div>

    <!-- Mobile Group Menu Dropdown -->
    <div id="mobile-group-dropdown" class="mobile-menu hidden bg-white shadow-md">
        <a href="#" class="block px-4 py-3 border-b border-gray-100 text-gray-700 hover:bg-gray-50">
            <i class="fas fa-info-circle mr-2"></i> Giới thiệu nhóm
        </a>
        <a href="#" class="block px-4 py-3 border-b border-gray-100 text-gray-700 hover:bg-gray-50">
            <i class="fas fa-users mr-2"></i> Thành viên
        </a>
        <a href="#" class="block px-4 py-3 border-b border-gray-100 text-gray-700 hover:bg-gray-50">
            <i class="fas fa-star mr-2"></i> Bài viết nổi bật
        </a>
        <a href="#" class="block px-4 py-3 text-gray-700 hover:bg-gray-50">
            <i class="fas fa-tags mr-2"></i> Chủ đề
        </a>
    </div>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-6 flex flex-col lg:flex-row">
        <!-- Left Sidebar -->
        <aside id="left-sidebar" class="sidebar lg:w-1/5 lg:pr-4 mb-6 lg:mb-0">
            <div class="bg-white rounded-lg shadow-sm p-4">
                <!-- Sidebar Toggle Button (Mobile) -->
                <button id="sidebar-toggle"
                    class="lg:hidden w-full bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-md mb-4 flex items-center justify-between">
                    <span>Thông tin nhóm</span>
                    <i class="fas fa-bars"></i>
                </button>

                <!-- Group Info -->
                <div class="mb-6">
                    <h3 class="font-bold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                        Giới thiệu nhóm
                    </h3>
                    <p class="text-gray-700 text-sm">
                        {{ $group->description }}
                    </p>
                    <div class="mt-3">
                        @if(isset($group->groupType))
                        <div class="flex items-center text-gray-600 text-sm mb-1">
                            <i class="fas fa-tag mr-2"></i>
                            <span>Loại nhóm: 
                                <span class="text-blue-500">{{ $group->groupType->title }}</span>
                            </span>
                        </div>
                        @endif
                        <div class="flex items-center text-gray-600 text-sm mb-1">
                            <i class="fas fa-user-shield mr-2"></i>
                            <span>Quản trị viên:
                                <a href="#" class="text-blue-500 hover:underline">
                                    {{ $group->author ? $group->author->full_name : 'Nguyễn Văn A' }}
                                </a>
                            </span>
                        </div>
                        <div class="flex items-center text-gray-600 text-sm">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            <span>Ngày tạo: {{ date('d/m/Y', strtotime($group->created_at)) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Group Members -->
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-3">
                        <h3 class="font-bold text-gray-800 flex items-center">
                            <i class="fas fa-users mr-2 text-blue-500"></i>
                            Thành viên
                            {{ $isMember || !$group->is_private ? '(' . count(json_decode($group->members ?? '[]', true)) . ')' : '' }}
                        </h3>
                        @if ($isMember || !$group->is_private)
                            <a href="javascript:void(0);" onclick="switchTab('members')" class="text-blue-500 text-sm hover:underline">Xem tất cả</a>
                        @endif
                    </div>

                    <div class="grid grid-cols-3 gap-3">
                        @if ($isMember || !$group->is_private)
                            @if (isset($members) && count($members) > 0)
                                @foreach ((is_array($members) ? collect($members) : $members)->take(6) as $member)
                                    <a href="#" class="flex flex-col items-center">
                                        <img src="{{ $member->photo ?? asset('images/default-avatar.jpg') }}"
                                            alt="{{ $member->full_name ?? 'Thành viên' }}"
                                            class="w-12 h-12 rounded-full object-cover mb-1">
                                        <span
                                            class="text-xs text-gray-700 text-center truncate w-full">{{ $member->full_name ?? ($member->name ?? 'Thành viên') }}</span>
                                    </a>
                                @endforeach
                            @else
                                <p class="col-span-3 text-center text-gray-500 text-sm">Chưa có thành viên</p>
                            @endif
                        @else
                            <p class="col-span-3 text-center text-gray-500 text-sm">
                                <i class="fas fa-lock mr-1"></i> Danh sách thành viên chỉ hiển thị cho thành viên nhóm
                            </p>
                        @endif
                    </div>
                </div>

                <!-- Member Requests (for private groups) -->
                @if (Auth::check() &&
                        (Auth::id() == $group->author_id || in_array(Auth::id(), json_decode($group->moderators ?? '[]', true))))
                    <div id="member-requests" class="mb-6">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="font-bold text-gray-800 flex items-center">
                                <i class="fas fa-user-clock mr-2 text-blue-500"></i>
                                Yêu cầu tham gia ({{ isset($joinRequests) ? count($joinRequests) : 0 }})
                            </h3>
                            <a href="javascript:void(0);" onclick="switchTab('requests')" 
                                class="text-blue-500 text-sm hover:underline">Xem tất cả</a>
                        </div>

                        <div class="space-y-2">
                            @if (isset($joinRequests) && (is_array($joinRequests) ? count($joinRequests) : $joinRequests->count()) > 0)
                                @foreach ((is_array($joinRequests) ? collect($joinRequests) : $joinRequests)->take(2) as $request)
                                    @if ($request && isset($request->user))
                                        <div
                                            class="member-request-item flex items-center justify-between p-2 rounded hover:bg-gray-50">
                                            <div class="flex items-center">
                                                <img src="{{ $request->user->photo ?? asset('images/default-avatar.jpg') }}"
                                                    alt="{{ $request->user->full_name ?? 'User' }}"
                                                    class="w-8 h-8 rounded-full object-cover mr-2">
                                                <div>
                                                    <h4 class="text-sm font-medium text-gray-800">
                                                        {{ $request->user->full_name ?? ($request->user->name ?? 'N/A') }}
                                                    </h4>
                                                    <p class="text-xs text-gray-500">
                                                        {{ isset($request->created_at) && $request->created_at ? $request->created_at->diffForHumans() : 'Vừa xong' }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="flex space-x-1">
                                                <form action="{{ route('group.approve-member', ['id' => $group->id]) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="user_id"
                                                        value="{{ $request->user->id }}">
                                                    <button type="submit"
                                                        class="text-xs bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('group.reject-member', ['id' => $group->id]) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="user_id"
                                                        value="{{ $request->user->id }}">
                                                    <button type="submit"
                                                        class="text-xs bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                <p class="text-gray-500 text-sm">Không có yêu cầu mới</p>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Group Rules -->
                <div>
                    <h3 class="font-bold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-gavel mr-2 text-blue-500"></i>
                        Nội quy nhóm
                    </h3>
                    <ul class="text-gray-700 text-sm space-y-2">
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                            <span>Không spam, quảng cáo không liên quan</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                            <span>Tôn trọng các thành viên khác</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                            <span>Chia sẻ kiến thức hữu ích</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                            <span>Không chia sẻ nội dung vi phạm bản quyền</span>
                        </li>
                    </ul>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div id="main-content" class="main-content lg:w-3/5 lg:px-4">
            <!-- Tabs Navigation -->
            <div class="bg-white rounded-lg shadow-sm mb-6">
                <div class="flex border-b">
                    <button id="posts-tab" onclick="switchTab('posts')" class="tab-button px-6 py-3 border-b-2 border-blue-500 text-blue-600 font-medium">
                        <i class="fas fa-newspaper mr-2"></i> Bài viết
                    </button>
                    <button id="members-tab" onclick="switchTab('members')" class="tab-button px-6 py-3 border-b-2 border-transparent hover:text-blue-500 hover:border-blue-200 font-medium">
                        <i class="fas fa-users mr-2"></i> Tất cả thành viên
                    </button>
                    @if (Auth::check() && (Auth::id() == $group->author_id || in_array(Auth::id(), json_decode($group->moderators ?? '[]', true))))
                    <button id="requests-tab" onclick="switchTab('requests')" class="tab-button px-6 py-3 border-b-2 border-transparent hover:text-blue-500 hover:border-blue-200 font-medium">
                        <i class="fas fa-user-clock mr-2"></i> Yêu cầu tham gia <span class="bg-red-500 text-white text-xs rounded-full px-2 py-0.5 ml-1">{{ isset($joinRequests) ? count($joinRequests) : 0 }}</span>
                    </button>
                    @endif
                </div>
            </div>

            <!-- Tab Content -->
            <div id="tab-content">
                <!-- Posts Tab (Default) -->
                <div id="posts-content" class="tab-content">
            <!-- Search and Filter -->
           

            <!-- Create Post -->
            @if (Auth::check() && $isMember)
                <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
                    <div class="flex items-start">
                        <img src="{{ Auth::user()->photo ?? asset('images/default-avatar.jpg') }}" alt="User"
                            class="w-10 h-10 rounded-full object-cover mr-3">
                        <div class="flex-1">
                            <input type="text" placeholder="Tìm kiếm bài viết trong nhóm..."
                                class="w-full bg-gray-100 rounded-full px-4 py-2 text-sm focus:outline-none mb-3"
                                ">
                            <div class="flex justify-between">
                                <div class="flex space-x-3">
                                    <a href="javascript:void(0);" onclick="openCreatePollModal()"
                                        class="flex items-center text-gray-500 hover:bg-gray-100 px-3 py-1 rounded">
                                        <i class="fas fa-poll text-yellow-500 mr-1"></i>
                                        <span class="text-sm">Khảo sát</span>
                                    </a>
                                </div>
                                <a href="javascript:void(0);" onclick="openCreatePostModal()"
                                    class="bg-blue-500 text-white px-4 py-1 rounded text-sm hover:bg-blue-600">
                                    Đăng
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Post Feed -->
            <section class="mb-8">
                <div id="post-feed" class="space-y-6">
                    @php
                        if (isset($posts)) {
                            echo '<!-- DEBUG: Có ' . $posts->count() . ' bài viết -->';
                        } else {
                            echo "<!-- DEBUG: Không tìm thấy biến \$posts -->";
                        }

                        if (isset($isMember)) {
                            echo '<!-- DEBUG: isMember = ' . ($isMember ? 'true' : 'false') . ' -->';
                        }

                        if (isset($group)) {
                            echo '<!-- DEBUG: group->is_private = ' . $group->is_private . ' -->';
                            echo '<!-- DEBUG: group->type = ' . ($group->type ?? 'null') . ' -->';
                        }
                    @endphp

                    @if ($isMember || !$group->is_private)
                        @if ((isset($sortedContent) && $sortedContent->count() > 0) || (isset($posts) && $posts->count() > 0) || (isset($polls) && count($polls) > 0))
                            <!-- Hiển thị bài viết và khảo sát theo thời gian tạo -->
                            @if (isset($sortedContent) && $sortedContent->count() > 0)
                                @foreach ($sortedContent as $content)
                                    @if ($content->content_type == 'post')
                                        <!-- Post Card - giữ nguyên code hiển thị bài viết -->
                                        <div class="post-card bg-white rounded-lg overflow-hidden shadow-sm transition cursor-pointer p-4">
                                            <!-- Nội dung bài viết - giữ nguyên như cũ -->
                                            <div class="flex items-start mb-4">
                                                <img src="{{ $content->author->photo ?? asset('images/default-avatar.jpg') }}"
                                                    alt="{{ $content->author->full_name ?? 'Author' }}"
                                                    class="w-10 h-10 rounded-full object-cover mr-3">
                                                <div>
                                                    <h3 class="font-medium text-gray-800">
                                                        {{ $content->author->full_name ?? ($content->author->name ?? 'Unknown Author') }}
                                                    </h3>
                                                    <p class="text-xs text-gray-500">
                                                        {{ $content->created_at ? \Carbon\Carbon::parse($content->created_at)->diffForHumans() : '' }}
                                                        · <i class="fas fa-users text-xs"></i> {{ $group->title }}</p>
                                                </div>
                                                <div class="ml-auto flex space-x-2">
                                                    @if (Auth::check() && (Auth::id() == $content->user_id || Auth::user()->role == 'admin'))
                                                        <a href="{{ route('front.tblogs.edit', $content->id) }}"
                                                            class="text-gray-600 hover:text-blue-600 px-2 py-1 rounded"
                                                            title="Chỉnh sửa">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form action="{{ route('front.tblogs.destroy', $content->id) }}"
                                                            method="POST" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="text-gray-600 hover:text-red-600 px-2 py-1 rounded"
                                                                title="Xóa"
                                                                onclick="return confirm('Bạn có chắc muốn xóa bài viết này?');">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                        <button class="text-gray-600 hover:text-yellow-600 px-2 py-1 rounded"
                                                            title="Ẩn/Hiện">
                                                            <i class="fas fa-eye-slash"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="mb-4">
                                                <p class="text-gray-800 mb-3">
                                                    {{ Str::limit(strip_tags($content->title ?? ''), 300) }}</p>
                                                @if ($content->tags && count($content->tags) > 0)
                                                    <div class="flex flex-wrap gap-2 mb-3">
                                                        @foreach ($content->tags as $tag)
                                                            <a href="{{ route('front.tblogs.tag', $tag->slug) }}"
                                                                class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">
                                                                #{{ $tag->title }}
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                @endif

                                                @if ($content->photo)
                                                    @php
                                                        $photos = is_string($content->photo)
                                                            ? json_decode($content->photo, true)
                                                            : $content->photo;
                                                    @endphp
                                                    @if (is_array($photos) && !empty($photos))
                                                        <img src="{{ $photos[0] }}" alt="{{ $content->title }}"
                                                            class="w-full h-auto rounded-lg">
                                                    @endif
                                                @endif

                                                @if (isset($content->meta) && is_array($content->meta) && isset($content->meta['poll']))
                                                    <!-- Hiển thị nội dung khảo sát nhúng (giữ nguyên code cũ) -->
                                                @endif
                                            </div>

                                            <!-- Phần tương tác (like, comment, share) - giữ nguyên code cũ -->
                                            <div
                                                class="flex items-center justify-between text-gray-500 border-t border-b border-gray-100 py-2 mb-3">
                                                <div class="flex items-center">
                                                    <div class="flex items-center">
                                                        <i class="fas fa-thumbs-up text-blue-500 mr-1"></i>
                                                        <span class="text-xs"
                                                            id="like-count-{{ $content->id }}">{{ $content->likes_count ?? 0 }}</span>
                                                    </div>
                                                    <div class="flex items-center ml-4">
                                                        <i class="fas fa-comment text-gray-400 mr-1"></i>
                                                        <span class="text-xs">{{ $content->comments_count ?? 0 }}</span>
                                                    </div>
                                                </div>
                                                <div class="text-xs">{{ $content->shares_count ?? 0 }} lượt chia sẻ</div>
                                            </div>

                                            <div class="flex justify-between border-b border-gray-100 pb-3 mb-3">
                                                <button id="like-btn-{{ $content->id }}"
                                                    onclick="reactToPost({{ $content->id }}, 'tblog', 'Like')"
                                                    class="flex items-center justify-center w-1/4 py-1 text-gray-500 hover:bg-gray-100 rounded {{ isset($content->user_has_liked) && $content->user_has_liked ? 'text-blue-600' : '' }}"
                                                    style="{{ isset($content->user_reaction) && $content->user_has_liked ? 'color: #2078f4' : '' }}">
                                                    <i
                                                        class="{{ isset($content->user_has_liked) && $content->user_has_liked ? 'fas' : 'far' }} fa-thumbs-up mr-2"></i>
                                                    Thích
                                                </button>
                                                <button onclick="toggleCommentBox({{ $content->id }}, 'tblog')"
                                                    class="flex items-center justify-center w-1/4 py-1 text-gray-500 hover:bg-gray-100 rounded">
                                                    <i class="far fa-comment mr-2"></i> Bình luận
                                                </button>
                                                <button
                                                    onclick="sharePost({{ $content->id }}, 'tblog', '{{ $content->slug ?? '' }}')"
                                                    class="flex items-center justify-center w-1/4 py-1 text-gray-500 hover:bg-gray-100 rounded">
                                                    <i class="fas fa-share mr-2"></i> Chia sẻ
                                                </button>
                                                <button id="bookmark-btn-{{ $content->id }}" onclick="toggleBookmark({{ $content->id }}, 'tblog')"
                                                    class="flex items-center justify-center w-1/4 py-1 text-gray-500 hover:bg-gray-100 rounded {{ isset($content->is_bookmarked) && $content->is_bookmarked ? 'text-red-500' : '' }}">
                                                    <i class="{{ isset($content->is_bookmarked) && $content->is_bookmarked ? 'fas' : 'far' }} fa-heart mr-2"></i>
                                                    Yêu thích
                                                </button>
                                            </div>

                                            <div class="flex items-center">
                                                <img src="{{ auth()->user()->photo ?? 'https://randomuser.me/api/portraits/women/44.jpg' }}"
                                                    alt="User" class="w-8 h-8 rounded-full object-cover mr-2">
                                                <div class="relative flex-1">
                                                    <input type="text" id="comment-input-{{ $content->id }}"
                                                        placeholder="Viết bình luận..."
                                                        class="comment-input w-full bg-gray-100 rounded-full px-4 py-2 text-sm focus:outline-none">
                                                    <div
                                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 flex space-x-1">
                                                        <button class="text-gray-400 hover:text-gray-600 emoji-trigger"
                                                            onclick="addEmoji({{ $content->id }})">
                                                            <i class="far fa-smile"></i>
                                                        </button>
                                                        <button class="text-gray-400 hover:text-gray-600"
                                                            onclick="submitComment({{ $content->id }}, 'tblog')">
                                                            <i class="fas fa-paper-plane"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Comment Box - This div will be shown/hidden with toggleCommentBox() -->
                                            <div id="comment-box-{{ $content->id }}"
                                                class="comment-box bg-white rounded-lg shadow-sm p-4 mt-3" style="display: none;">
                                                <div id="comments-container-{{ $content->id }}" class="space-y-3">
                                                    <!-- Comments will be loaded here dynamically -->
                                                    <div class="text-center text-gray-500 text-sm py-2">
                                                        <i class="fas fa-spinner fa-spin mr-2"></i> Đang tải bình luận...
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <!-- Poll Card - hiển thị khảo sát -->
                                        <div class="poll-card bg-white rounded-lg overflow-hidden shadow-sm transition p-4 mb-4 poll-container" data-poll-id="{{ $content->id }}">
                                            <div class="flex items-start justify-between mb-4">
                                                <div class="flex items-start">
                                                    <img src="{{ $content->creator->photo ?? asset('images/default-avatar.jpg') }}"
                                                        alt="{{ $content->creator->full_name ?? 'Creator' }}"
                                                        class="w-10 h-10 rounded-full object-cover mr-3">
                                                    <div>
                                                        <h3 class="font-medium text-gray-800">
                                                            {{ $content->creator->full_name ?? ($content->creator->name ?? 'Unknown Creator') }}
                                                        </h3>
                                                        <p class="text-xs text-gray-500">
                                                            {{ \Carbon\Carbon::parse($content->created_at)->diffForHumans() }}
                                                            · <i class="fas fa-chart-pie text-xs"></i> Khảo sát
                                                            @if($content->expires_at)
                                                                · <i class="fas fa-clock text-xs"></i> Hết hạn: {{ \Carbon\Carbon::parse($content->expires_at)->format('d/m/Y H:i') }}
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                                
                                                <!-- Thêm nút sửa và xóa khảo sát -->
                                                @if(Auth::check() && (isset($content->created_by) && Auth::id() == $content->created_by || isset($content->creator) && isset($content->creator->id) && Auth::id() == $content->creator->id))
                                                    <div class="flex space-x-2">
                                                        <a href="{{ route('polls.edit', $content->id) }}" 
                                                           class="text-gray-600 hover:text-blue-600 px-2 py-1 rounded"
                                                           title="Chỉnh sửa">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form action="{{ route('polls.destroy', $content->id) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" 
                                                                    class="text-gray-600 hover:text-red-600 px-2 py-1 rounded"
                                                                    title="Xóa"
                                                                    onclick="return confirm('Bạn có chắc chắn muốn xóa khảo sát này?')">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                        <button class="text-gray-600 hover:text-yellow-600 px-2 py-1 rounded"
                                                                title="Ẩn/Hiện">
                                                            <i class="fas fa-eye-slash"></i>
                                                        </button>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="mb-4">
                                                <h4 class="font-medium text-gray-800 mb-2">📊 {{ $content->title }}</h4>
                                                <p class="text-gray-700 mb-4">{{ $content->question }}</p>
                                                
                                                @php
                                                    // Kiểm tra xem khảo sát đã hết hạn chưa
                                                    $isExpired = false;
                                                    if (isset($content->expires_at) && !empty($content->expires_at)) {
                                                        $isExpired = now()->gt(\Carbon\Carbon::parse($content->expires_at));
                                                    }
                                                @endphp

                                                @if($isExpired)
                                                    <!-- Hiển thị thông báo hết hạn -->
                                                    <div class="bg-yellow-100 text-yellow-800 px-4 py-3 rounded-lg mb-4">
                                                        <i class="fas fa-clock mr-2"></i> Khảo sát này đã kết thúc
                                                    </div>
                                                @endif
                                                
                                                @php
                                                    // Kiểm tra người dùng đã bỏ phiếu chưa
                                                    $hasVoted = false;
                                                    if (method_exists($content, 'hasUserVoted')) {
                                                        $hasVoted = $content->hasUserVoted(Auth::id());
                                                    } elseif (isset($content->user_has_voted)) {
                                                        $hasVoted = $content->user_has_voted;
                                                    } elseif (isset($content->votes) && Auth::check()) {
                                                        $hasVoted = collect($content->votes)->contains('user_id', Auth::id());
                                                    }
                                                @endphp

                                                @if(!$hasVoted && !$isExpired && Auth::check())
                                                    <!-- Form bình chọn khảo sát -->
                                                    <form class="poll-vote-form mb-3" data-poll-id="{{ $content->id }}">
                                                        @csrf
                                                        <input type="hidden" name="poll_id" value="{{ $content->id }}">
                                                        
                                                        <div class="space-y-2 mb-4">
                                                            @foreach($content->options as $i => $option)
                                                                <div class="flex items-center">
                                                                    <input type="radio" id="option-{{ $content->id }}-{{ $i }}" 
                                                                           name="option_index" value="{{ $i }}" 
                                                                           data-option-id="{{ $option->id }}"
                                                                           class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                                                    <label for="option-{{ $content->id }}-{{ $i }}" 
                                                                           class="ml-2 text-sm font-medium text-gray-700">
                                                                        {{ $option->option_text }}
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        
                                                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                            Bình chọn
                                                        </button>
                                                    </form>
                                                @elseif($hasVoted && !$isExpired && Auth::check())
                                                    <!-- Form thay đổi bình chọn (bắt đầu ẩn) -->
                                                    <form class="poll-change-form mb-3 hidden" data-poll-id="{{ $content->id }}">
                                                        @csrf
                                                        <input type="hidden" name="poll_id" value="{{ $content->id }}">
                                                        
                                                        <div class="space-y-2 mb-4">
                                                            @foreach($content->options as $i => $option)
                                                                <div class="flex items-center">
                                                                    <input type="radio" id="option-change-{{ $content->id }}-{{ $i }}" 
                                                                           name="option_index" value="{{ $i }}" 
                                                                           data-option-id="{{ $option->id }}"
                                                                           class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                                                                           {{ $userVote && isset($userVote->option_id) && $userVote->option_id == $option->id ? 'checked' : '' }}>
                                                                    <label for="option-change-{{ $content->id }}-{{ $i }}" 
                                                                           class="ml-2 text-sm font-medium text-gray-700">
                                                                        {{ $option->option_text }}
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        
                                                        <div class="flex space-x-2">
                                                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                                Cập nhật bình chọn
                                                            </button>
                                                            <button type="button" onclick="toggleChangeVoteForm('{{ $content->id }}')" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm font-medium hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                                                Hủy
                                                            </button>
                                                        </div>
                                                    </form>
                                                @endif
                                                
                                                <!-- Hiển thị kết quả khảo sát -->
                                                <div class="poll-results {{ !$hasVoted && !$isExpired && Auth::check() ? 'hidden' : '' }}" id="poll-results-{{ $content->id }}">
                                                    @if(isset($content->options) && (is_array($content->options) || is_object($content->options)))
                                                        @foreach($content->options as $i => $option)
                                                            @php
                                                                // Lấy số lượng vote và tỷ lệ phần trăm từ kết quả đã chuẩn bị từ controller
                                                                $voteInfo = null;
                                                                if (isset($content->results) && is_array($content->results)) {
                                                                    foreach ($content->results as $result) {
                                                                        if ((isset($result['id']) && isset($option->id) && $result['id'] == $option->id) ||
                                                                            (isset($result['id']) && isset($option['id']) && $result['id'] == $option['id'])) {
                                                                            $voteInfo = $result;
                                                                            break;
                                                                        }
                                                                    }
                                                                }
                                                                
                                                                // Lấy giá trị mặc định nếu không tìm thấy
                                                                $count = $voteInfo ? $voteInfo['count'] : (isset($option->votes_count) ? $option->votes_count : 0);
                                                                $percentage = $voteInfo ? $voteInfo['percentage'] : 0;
                                                                
                                                                // Nếu vẫn là 0 mà biết rằng có phiếu bầu, thì gọi votes trực tiếp
                                                                if ($count == 0 && isset($option->votes) && is_countable($option->votes)) {
                                                                    $count = count($option->votes);
                                                                    if ($content->total_votes > 0) {
                                                                        $percentage = round(($count / $content->total_votes) * 100, 1);
                                                                    }
                                                                }
                                                                
                                                                // Kiểm tra xem option hiện tại có phải là lựa chọn của người dùng không
                                                                $isUserOption = false;
                                                                if (Auth::check()) {
                                                                    // Kiểm tra từ user_vote trong content (nếu có)
                                                                    if (isset($content->user_vote) && isset($content->user_vote->option_id)) {
                                                                        $isUserOption = $content->user_vote->option_id == ($option->id ?? $option['id'] ?? 0);
                                                                    }
                                                                    // Hoặc kiểm tra từ biến $userVote (nếu có)
                                                                    elseif (isset($userVote) && isset($userVote->option_id)) {
                                                                        $isUserOption = $userVote->option_id == ($option->id ?? $option['id'] ?? 0);
                                                                    }
                                                                }
                                                            @endphp
                                                            
                                                            <div class="mb-3">
                                                                <div class="flex justify-between mb-1">
                                                                    <span class="text-sm font-medium">{{ $option->option_text ?? $option->text ?? 'Tùy chọn' }}</span>
                                                                    <span class="text-sm text-gray-500">
                                                                        {{ $percentage }}% ({{ $count }} phiếu)
                                                                    </span>
                                                                </div>
                                                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                                                    <div class="bg-blue-600 h-2.5 rounded-full" 
                                                                         style="width: {{ $percentage }}%">
                                                                    </div>
                                                                </div>
                                                                
                                                                @if($isUserOption)
                                                                    <div class="text-end">
                                                                        <small class="text-muted"><i class="fas fa-check-circle text-success"></i> Lựa chọn của bạn</small>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                    
                                                    <div class="text-sm text-gray-500 mt-2">
                                                        Tổng số phiếu: {{ $content->total_votes ?? 0 }}
                                                    </div>
                                                </div>
                                                
                                                <div class="flex justify-between mt-4">
                                                    @if($hasVoted && !$isExpired && Auth::check())
                                                        <button onclick="toggleChangeVoteForm('{{ $content->id }}')" class="text-blue-600 hover:underline text-sm">
                                                            <i class="fas fa-pen mr-1"></i> Đổi bình chọn
                                                        </button>
                                                    @else
                                                        <button onclick="togglePollResults('{{ $content->id }}')" class="text-blue-600 hover:underline text-sm">
                                                            <i class="fas fa-chart-bar mr-1"></i> {{ $hasVoted ? 'Ẩn kết quả' : 'Xem kết quả' }}
                                                        </button>
                                                    @endif
                                                    
                                                    <div class="flex space-x-2">
                                                        <!-- Nút xem người đã bình chọn -->
                                                        <button onclick="showVoters({{ $content->id }})" class="text-blue-600 hover:underline text-sm">
                                                            <i class="fas fa-users mr-1"></i> Xem người đã bình chọn
                                                        </button>
                                                        
                                                        <a href="{{ route('polls.show', $content->id) }}" class="text-blue-600 hover:underline text-sm">
                                                            <i class="fas fa-external-link-alt mr-1"></i> Chi tiết
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                <!-- Không có nội dung -->
                                
                            @endif
                        @else
                            <!-- Private Group Notice -->
                            <div id="no-content-notice" class="bg-white rounded-lg shadow-sm p-6 text-center">
                                <div class="text-blue-500 text-5xl mb-4">
                                    <i class="fas fa-info-circle"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800 mb-2">Chưa có bài viết nào</h3>
                                <p class="text-gray-600 mb-6">Hãy là người đầu tiên chia sẻ bài viết trong nhóm này!</p>
                                @if (Auth::check() && $isMember)
                                    <button onclick="openCreatePostModal()" class="bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600">
                                        <i class="fas fa-plus mr-2"></i> Tạo bài viết mới
                                    </button>
                                @elseif (Auth::check() && !$isMember)
                                    <form action="{{ route('group.join', $group->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600">
                                            <i class="fas fa-user-plus mr-2"></i> Tham gia nhóm để đăng bài
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('front.login') }}" class="bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600">
                                        <i class="fas fa-sign-in-alt mr-2"></i> Đăng nhập để tham gia
                                    </a>
                                @endif
                            </div>
                        @endif
                    @else
                        <!-- Private Group Notice -->
                        <div id="private-group-notice" class="bg-white rounded-lg shadow-sm p-6 text-center">
                            <div class="text-red-500 text-5xl mb-4">
                                <i class="fas fa-lock"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800 mb-2">Đây là nhóm riêng tư</h3>
                            <p class="text-gray-600 mb-6">Bạn cần tham gia nhóm để xem nội dung này. Hãy gửi yêu cầu tham
                                gia và chờ quản trị viên phê duyệt.</p>
                            @if (Auth::check())
                                @if (isset($joinRequest) && $joinRequest && $joinRequest->status == 'pending')
                                    <div class="bg-yellow-100 text-yellow-800 px-4 py-3 rounded-lg mb-4">
                                        <i class="fas fa-clock mr-2"></i> Yêu cầu tham gia của bạn đang chờ phê duyệt
                                    </div>
                                @else
                                    <form action="{{ route('group.join', $group->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600">
                                            <i class="fas fa-user-plus mr-2"></i> Gửi yêu cầu tham gia
                                        </button>
                                    </form>
                                @endif
                            @else
                                <a href="{{ route('front.login') }}"
                                    class="bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600">
                                    <i class="fas fa-sign-in-alt mr-2"></i> Đăng nhập để tham gia
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </section>
                </div>

                <!-- Members Tab -->
                <div id="members-content" class="tab-content hidden">
                    <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
                        <div class="mb-4">
                            <h2 class="text-xl font-bold text-gray-800 mb-4">Tất cả thành viên</h2>
                            
                            <div class="mb-4">
                                <input type="text" id="memberSearchInput" placeholder="Tìm kiếm thành viên..." 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Thành viên</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vai trò</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tham gia</th>
                                            @if (Auth::check() && (Auth::id() == $group->author_id || in_array(Auth::id(), json_decode($group->moderators ?? '[]', true))))
                                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Hành động</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody id="memberTableBody" class="bg-white divide-y divide-gray-200">
                                        <!-- Member list will be loaded here via JavaScript -->
                                        <tr>
                                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                                <i class="fas fa-spinner fa-spin mr-2"></i> Đang tải danh sách thành viên...
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Join Requests Tab -->
                <div id="requests-content" class="tab-content hidden">
                    <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
                        <div class="mb-4">
                            <h2 class="text-xl font-bold text-gray-800 mb-4">Yêu cầu tham gia nhóm</h2>
                            
                            <div class="mb-4">
                                <input type="text" id="requestSearchInput" placeholder="Tìm kiếm trong yêu cầu..." 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            
                            <div id="joinRequestsList" class="space-y-3">
                                <!-- Request list will be loaded via JavaScript -->
                                <div class="text-center text-gray-500 text-sm py-4">
                                    <i class="fas fa-spinner fa-spin mr-2"></i> Đang tải danh sách yêu cầu...
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Sidebar -->
        <aside class="sidebar right-sidebar lg:w-1/5 lg:pl-4">
            <div class="bg-white rounded-lg shadow-sm p-4">
                <!-- Top Posts -->
                <div class="mb-6">
                    <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-fire mr-2 text-red-500"></i>
                        Bài viết nổi bật
                    </h3>

                    <div class="space-y-4">
                        @forelse($topPosts as $post)
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-16 h-16 bg-gray-200 rounded overflow-hidden">
                                    <img src="{{ $post->thumbnail }}" alt="{{ $post->title }}"
                                        class="w-full h-full object-cover">
                                </div>
                                <div class="ml-3">
                                    <a href="{{ $post->url }}" class="hover:text-blue-600">
                                        <h3 class="text-sm font-medium text-gray-800 truncate">{{ $post->title }}</h3>
                                    </a>
                                    <div class="flex items-center text-xs text-gray-500 mt-1">
                                        <i class="fas fa-thumbs-up text-blue-500 mr-1"></i>
                                        <span>{{ $post->likes_count }} likes</span>
                                        <span class="mx-2">•</span>
                                        <i class="fas fa-comment text-green-500 mr-1"></i>
                                        <span>{{ $post->comments_count }} bình luận</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-sm text-gray-500 italic">Chưa có bài viết nổi bật</div>
                        @endforelse
                    </div>

                    @if (count($topPosts) > 0)
                        <a href="#" class="block text-center text-blue-500 text-sm mt-4 hover:text-blue-700">Xem tất
                            cả</a>
                    @endif
                </div>

                <!-- Top Members -->
                <div class="mb-6">
                    <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-user-friends mr-2 text-purple-500"></i>
                        Thành viên tích cực
                    </h3>

                    <div class="space-y-3">
                        @forelse($activeMembers as $member)
                            <div class="flex items-center">
                                <img src="{{ $member->photo ?: asset('backend/assets/dist/images/profile-6.jpg') }}"
                                    alt="{{ $member->name }}" class="w-10 h-10 rounded-full object-cover">
                                <div class="ml-3">
                                    <a href="{{ $member->url }}" class="hover:text-blue-600">
                                        <h4 class="text-sm font-medium text-gray-800">{{ $member->name }}</h4>
                                    </a>
                                    <p class="text-xs text-gray-500">{{ $member->blogs_count }} bài viết •
                                        {{ $member->interactions_count }} tương tác</p>
                                </div>
                            </div>
                        @empty
                            <div class="text-sm text-gray-500 italic">Chưa có thành viên tích cực</div>
                        @endforelse
                    </div>

                    @if (count($activeMembers) > 0)
                        <a href="#" class="block text-center text-blue-500 text-sm mt-4 hover:text-blue-700">Xem tất
                            cả</a>
                    @endif
                </div>

                <!-- Similar Groups - Hiển thị nhóm ngẫu nhiên -->
                <div class="mb-6">
                    <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-users mr-2 text-green-500"></i>
                        Nhóm tương tự
                    </h3>

                    <div class="space-y-3">
                        @php
                            // Lấy ngẫu nhiên 5 nhóm khác với nhóm hiện tại
                            $similarGroups = \App\Modules\Group\Models\Group::where('id', '!=', $group->id)
                                ->inRandomOrder()
                                ->take(5)
                                ->get();
                        @endphp

                        @forelse($similarGroups as $similarGroup)
                            <div class="flex items-center p-2 hover:bg-gray-50 rounded-lg transition-all duration-200">
                                <div class="flex-shrink-0 w-10 h-10 {{ !$similarGroup->photo ? 'bg-blue-100' : '' }} rounded-full flex items-center justify-center overflow-hidden border border-gray-200">
                                    @if($similarGroup->photo)
                                        <img src="{{ asset($similarGroup->photo) }}" class="w-full h-full object-cover" alt="{{ $similarGroup->title }}">
                                    @else
                                        <i class="fas fa-users text-blue-500"></i>
                                    @endif
                                </div>
                                <div class="ml-3 flex-grow">
                                    <a href="{{ route('group.show', $similarGroup->id) }}" class="hover:text-blue-600 transition duration-200">
                                        <h4 class="text-sm font-medium text-gray-800">{{ $similarGroup->title }}</h4>
                                    </a>
                                    <p class="text-xs text-gray-500">
                                        <i class="fas fa-user-friends text-gray-400 mr-1"></i>
                                        {{ count(json_decode($similarGroup->members ?? '[]', true)) }} thành viên
                                        <span class="mx-1">•</span>
                                        <span class="{{ $similarGroup->is_private ? 'text-red-500' : 'text-green-500' }}">
                                            <i class="fas {{ $similarGroup->is_private ? 'fa-lock' : 'fa-globe-asia' }} mr-1"></i>
                                            {{ $similarGroup->is_private ? 'Riêng tư' : 'Công khai' }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        @empty
                            <div class="text-sm text-gray-500 italic bg-gray-50 p-4 rounded-lg text-center">
                                <i class="fas fa-info-circle mr-2 text-blue-400"></i>
                                Không tìm thấy nhóm tương tự
                            </div>
                        @endforelse
                    </div>

                    <a href="{{ route('group.index') }}" class="block text-center text-blue-500 text-sm mt-4 hover:text-blue-700 hover:underline">
                        <i class="fas fa-arrow-right mr-1"></i> Xem thêm nhóm khác
                    </a>
                </div>

                <!-- Group Tags -->
                <div>
                    <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-tags mr-2 text-blue-500"></i>
                        Chủ đề nhóm
                    </h3>

                    <div class="flex flex-wrap gap-2">
                        <a href="#"
                            class="tag bg-gray-100 hover:bg-blue-100 text-gray-800 px-3 py-1 rounded-full text-xs transition">Lập
                            trình</a>
                        <a href="#"
                            class="tag bg-gray-100 hover:bg-blue-100 text-gray-800 px-3 py-1 rounded-full text-xs transition">JavaScript</a>
                        <a href="#"
                            class="tag bg-gray-100 hover:bg-blue-100 text-gray-800 px-3 py-1 rounded-full text-xs transition">React</a>
                        <a href="#"
                            class="tag bg-gray-100 hover:bg-blue-100 text-gray-800 px-3 py-1 rounded-full text-xs transition">Node.js</a>
                        <a href="#"
                            class="tag bg-gray-100 hover:bg-blue-100 text-gray-800 px-3 py-1 rounded-full text-xs transition">Clean
                            Code</a>
                        <a href="#"
                            class="tag bg-gray-100 hover:bg-blue-100 text-gray-800 px-3 py-1 rounded-full text-xs transition">Algorithm</a>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Right Sidebar for Mobile (Slider) -->
        <aside class="right-sidebar-mobile lg:w-1/5 lg:pl-4 mt-6">
            <div class="bg-white rounded-lg shadow-sm p-4 overflow-x-auto">
                <div class="flex space-x-4 w-max">
                    <!-- Top Posts Card -->
                    <div class="w-64 flex-shrink-0">
                        <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-fire mr-2 text-red-500"></i>
                            Bài viết nổi bật
                        </h3>

                        <div class="space-y-4">
                            @forelse($topPosts as $post)
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 w-16 h-16 bg-gray-200 rounded overflow-hidden">
                                        <img src="{{ $post->thumbnail }}" alt="{{ $post->title }}"
                                            class="w-full h-full object-cover">
                                    </div>
                                    <div class="ml-3">
                                        <a href="{{ $post->url }}" class="hover:text-blue-600">
                                            <h3 class="text-sm font-medium text-gray-800 truncate">{{ $post->title }}
                                            </h3>
                                        </a>
                                        <div class="flex items-center text-xs text-gray-500 mt-1">
                                            <i class="fas fa-thumbs-up text-blue-500 mr-1"></i>
                                            <span>{{ $post->likes_count }}</span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-sm text-gray-500 italic">Chưa có bài viết nổi bật</div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Top Members Card -->
                    <div class="w-64 flex-shrink-0">
                        <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-user-friends mr-2 text-purple-500"></i>
                            Thành viên tích cực
                        </h3>

                        <div class="space-y-3">
                            @forelse($activeMembers as $member)
                                <div class="flex items-center">
                                    <img src="{{ $member->photo ?: asset('backend/assets/dist/images/profile-6.jpg') }}"
                                        alt="{{ $member->name }}" class="w-10 h-10 rounded-full object-cover">
                                    <div class="ml-3">
                                        <a href="{{ $member->url }}" class="hover:text-blue-600">
                                            <h4 class="text-sm font-medium text-gray-800">{{ $member->name }}</h4>
                                        </a>
                                        <p class="text-xs text-gray-500">{{ $member->blogs_count }} bài viết</p>
                                    </div>
                                </div>
                            @empty
                                <div class="text-sm text-gray-500 italic">Chưa có thành viên tích cực</div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Similar Groups Card -->
                    <div class="w-64 flex-shrink-0">
                        <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-users mr-2 text-green-500"></i>
                            Nhóm tương tự
                        </h3>

                        <div class="space-y-3">
                            <div class="flex items-center">
                                <div
                                    class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-code text-blue-500"></i>
                                </div>
                                <div class="ml-3">
                                    <h4 class="text-sm font-medium text-gray-800">Frontend Developers</h4>
                                    <p class="text-xs text-gray-500">850 thành viên</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Group Tags Card -->
                    <div class="w-64 flex-shrink-0">
                        <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-tags mr-2 text-blue-500"></i>
                            Chủ đề nhóm
                        </h3>

                        <div class="flex flex-wrap gap-2">
                            <a href="#"
                                class="tag bg-gray-100 hover:bg-blue-100 text-gray-800 px-3 py-1 rounded-full text-xs transition">Lập
                                trình</a>
                            <a href="#"
                                class="tag bg-gray-100 hover:bg-blue-100 text-gray-800 px-3 py-1 rounded-full text-xs transition">JavaScript</a>
                            <a href="#"
                                class="tag bg-gray-100 hover:bg-blue-100 text-gray-800 px-3 py-1 rounded-full text-xs transition">React</a>
                        </div>
                    </div>
                </div>
            </div>
        </aside>
    </main>


    <!-- Scroll to Top Button -->
    <button id="scroll-to-top"
        class="fixed bottom-6 right-6 bg-blue-500 text-white w-12 h-12 rounded-full shadow-lg flex items-center justify-center opacity-0 invisible transition">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Thêm modal popup cho tạo bài viết mới -->
    <div id="createPostModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg w-full max-w-4xl max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center p-4 border-b">
                <h2 class="text-xl font-bold">Tạo bài viết mới trong nhóm</h2>
                <button onclick="closeCreatePostModal()" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="createPostContent" class="p-6">
                <div class="flex justify-center">
                    <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
                </div>
            </div>
        </div>
    </div>
   

    <!-- Avatar Update Modal -->
    <div id="photo-modal" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black opacity-50"></div>
            
            <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full mx-auto">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-gray-800">Cập nhật ảnh đại diện nhóm</h3>
                        <button type="button" id="close-photo-modal" class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <div class="mb-6">
                        <div class="flex flex-col items-center">
                            <div class="relative w-32 h-32 bg-gray-100 rounded-full overflow-hidden mb-4">
                                <img id="photo-preview-modal" src="{{ $group->photo ? asset($group->photo) : asset('images/lego-head.png') }}" alt="Group Avatar" class="w-full h-full object-cover">
                            </div>
                            
                            <p class="text-sm text-gray-600 mb-4">Tải lên ảnh đại diện mới cho nhóm</p>
                            
                            <label for="photo-file-input" class="bg-blue-100 text-blue-600 px-4 py-2 rounded-md font-medium hover:bg-blue-200 cursor-pointer transition">
                                <i class="fas fa-upload mr-2"></i> Chọn ảnh
                            </label>
                            <form id="photo-modal-form" action="{{ route('front.upload.avatar') }}" method="POST" enctype="multipart/form-data" class="hidden">
                                @csrf
                                <input type="file" name="photo" id="photo-file-input" accept="image/*">
                            </form>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" id="cancel-photo-btn" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-100">
                            Huỷ bỏ
                        </button>
                        <button type="button" id="save-photo-btn" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                            Lưu thay đổi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cover Photo Update Modal -->
    <div id="cover-modal" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black opacity-50"></div>
            
            <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full mx-auto">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-gray-800">Cập nhật ảnh bìa nhóm</h3>
                        <button type="button" id="close-cover-modal" class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <div class="mb-6">
                        <div class="flex flex-col items-center">
                            <div class="relative w-full h-48 bg-gray-100 rounded-lg overflow-hidden mb-4">
                                <div id="cover-preview-modal" class="w-full h-full bg-cover bg-center" 
                                    style="background-image: url('{{ $group->cover_photo ? asset($group->cover_photo) : asset('images/default-banner.jpg') }}');">
                                </div>
                            </div>
                            
                            <p class="text-sm text-gray-600 mb-4">Tải lên ảnh bìa mới cho nhóm</p>
                            
                            <label for="cover-file-input" class="bg-blue-100 text-blue-600 px-4 py-2 rounded-md font-medium hover:bg-blue-200 cursor-pointer transition">
                                <i class="fas fa-upload mr-2"></i> Chọn ảnh
                            </label>
                            <form id="cover-modal-form" action="{{ route('front.upload.banner') }}" method="POST" enctype="multipart/form-data" class="hidden">
                                @csrf
                                <input type="file" name="banner" id="cover-file-input" accept="image/*">
                            </form>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" id="cancel-cover-btn" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-100">
                            Huỷ bỏ
                        </button>
                        <button type="button" id="save-cover-btn" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                            Lưu thay đổi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal tạo khảo sát -->
    <div id="createPollModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-lg mx-4">
            <div class="flex justify-between items-center px-6 py-4 border-b">
                <h3 class="text-lg font-medium text-gray-900">Tạo khảo sát mới</h3>
                <button type="button" class="text-gray-400 hover:text-gray-500" onclick="closeCreatePollModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="createPollForm" action="{{ route('polls.store') }}" method="POST">
                @csrf
                <div class="px-6 py-4">
                    <!-- Group ID hidden input -->
                    <input type="hidden" name="group_id" value="{{ $group->id }}">
                    
                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Tiêu đề</label>
                        <input type="text" id="title" name="title" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div class="mb-4">
                        <label for="question" class="block text-sm font-medium text-gray-700 mb-1">Câu hỏi</label>
                        <textarea id="question" name="question" rows="3" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Các lựa chọn</label>
                        <div id="poll-options">
                            <div class="mb-2 flex items-center">
                                <input type="text" name="options[]" required placeholder="Lựa chọn 1"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div class="mb-2 flex items-center">
                                <input type="text" name="options[]" required placeholder="Lựa chọn 2"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                        <div class="mt-2 flex justify-between">
                            <button type="button" onclick="addPollOption()" class="text-blue-600 hover:text-blue-700 text-sm flex items-center">
                                <i class="fas fa-plus mr-1"></i> Thêm lựa chọn
                            </button>
                            <div class="text-gray-500 text-sm">Tối đa 5 lựa chọn</div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="expires_at" class="block text-sm font-medium text-gray-700 mb-1">Thời hạn (không bắt buộc)</label>
                        <input type="datetime-local" id="expires_at" name="expires_at"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
                
                <div class="px-6 py-4 bg-gray-50 text-right rounded-b-lg">
                    <button type="button" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 mr-2" onclick="closeCreatePollModal()">
                        Hủy
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700">
                        Tạo khảo sát
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Thêm Modal hiển thị danh sách người đã bình chọn -->
    <div id="votersModal" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50 hidden">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
            <div class="p-4 border-b flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">Người đã bình chọn</h3>
                <button onclick="closeVotersModal()" class="text-gray-500 hover:text-gray-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="votersContainer" class="p-4 max-h-96 overflow-y-auto">
                <div class="text-center py-4">
                    <i class="fas fa-spinner fa-spin mr-2"></i> Đang tải danh sách người bình chọn...
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <!-- Dropzone JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>

    <!-- Tom Select JS -->
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>

    <!-- CKEditor -->
    <script src="{{ asset('js/js/ckeditor.js') }}"></script>
    <script src="{{ asset('modules/tuongtac/social-interactions.js') }}"></script>
    <script>
        // Khai báo biến toàn cục
        let postImagesDropzone;
        let tagsSelect;
        let membersLoaded = false;
        let requestsLoaded = false;
        let activeTab = 'posts';
        
        // Biến chứa đường dẫn tới các tài nguyên tĩnh
        const defaultAvatarUrl = "{{ asset('images/default-avatar.jpg') }}";
        // URL của route
        const groupShowUrl = "{{ route('group.show', $group->id) }}";
        const loginUrl = "{{ route('front.login') }}";
        const groupApproveMemberUrl = "{{ route('group.approve-member', ['id' => $group->id]) }}";
        const groupRejectMemberUrl = "{{ route('group.reject-member', ['id' => $group->id]) }}";
        
        // Avatar và Cover Photo Modal Controls
        document.addEventListener('DOMContentLoaded', function() {
            const editPhotoBtn = document.getElementById('edit-photo-btn');
            const editCoverBtn = document.getElementById('edit-cover-btn');
            const photoModal = document.getElementById('photo-modal');
            const coverModal = document.getElementById('cover-modal');
            const closePhotoModal = document.getElementById('close-photo-modal');
            const closeCoverModal = document.getElementById('close-cover-modal');
            const cancelPhotoBtn = document.getElementById('cancel-photo-btn');
            const cancelCoverBtn = document.getElementById('cancel-cover-btn');
            const savePhotoBtn = document.getElementById('save-photo-btn');
            const saveCoverBtn = document.getElementById('save-cover-btn');
            const photoFileInput = document.getElementById('photo-file-input');
            const coverFileInput = document.getElementById('cover-file-input');
            const photoPreviewModal = document.getElementById('photo-preview-modal');
            const coverPreviewModal = document.getElementById('cover-preview-modal');
            
            // Photo Modal Events
            if (editPhotoBtn && photoModal) {
                // Open Photo Modal
                editPhotoBtn.addEventListener('click', function() {
                    photoModal.classList.remove('hidden');
                });
                
                // Close Photo Modal
                const closePhotoModalFn = function() {
                    photoModal.classList.add('hidden');
                    // Reset file input
                    if (photoFileInput) photoFileInput.value = '';
                };
                
                if (closePhotoModal) closePhotoModal.addEventListener('click', closePhotoModalFn);
                if (cancelPhotoBtn) cancelPhotoBtn.addEventListener('click', closePhotoModalFn);
                
                // Close on outside click
                photoModal.addEventListener('click', function(e) {
                    if (e.target === photoModal) {
                        closePhotoModalFn();
                    }
                });
            }
            
            // Cover Modal Events
            if (editCoverBtn && coverModal) {
                // Open Cover Modal
                editCoverBtn.addEventListener('click', function() {
                    coverModal.classList.remove('hidden');
                });
                
                // Close Cover Modal
                const closeCoverModalFn = function() {
                    coverModal.classList.add('hidden');
                    // Reset file input
                    if (coverFileInput) coverFileInput.value = '';
                };
                
                if (closeCoverModal) closeCoverModal.addEventListener('click', closeCoverModalFn);
                if (cancelCoverBtn) cancelCoverBtn.addEventListener('click', closeCoverModalFn);
                
                // Close on outside click
                coverModal.addEventListener('click', function(e) {
                    if (e.target === coverModal) {
                        closeCoverModalFn();
                    }
                });
            }
            
            // Photo Preview Update
            if (photoFileInput && photoPreviewModal) {
                photoFileInput.addEventListener('change', function() {
                    if (this.files && this.files[0]) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            photoPreviewModal.src = e.target.result;
                        }
                        reader.readAsDataURL(this.files[0]);
                    }
                });
            }
            
            // Cover Preview Update
            if (coverFileInput && coverPreviewModal) {
                coverFileInput.addEventListener('change', function() {
                    if (this.files && this.files[0]) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            coverPreviewModal.style.backgroundImage = `url('${e.target.result}')`;
                        }
                        reader.readAsDataURL(this.files[0]);
                    }
                });
            }
            
            // Save Photo
            if (savePhotoBtn) {
                savePhotoBtn.addEventListener('click', async function() {
                    if (!photoFileInput.files || !photoFileInput.files[0]) {
                        alert('Vui lòng chọn ảnh để tải lên');
                        return;
                    }
                    
                    const photoForm = document.getElementById('photo-modal-form');
                    const formData = new FormData();
                    formData.append('photo', photoFileInput.files[0]);
                    formData.append('_token', '{{ csrf_token() }}');
                    
                    try {
                        savePhotoBtn.disabled = true;
                        savePhotoBtn.innerHTML = 'Đang lưu...';
                        
                        console.log('Uploading group photo...');
                        
                        const response = await fetch('{{ route("front.upload.avatar") }}', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });
                        
                        const data = await response.json();
                        console.log('Photo response:', data);
                        
                        if (!response.ok || !data.status) {
                            throw new Error(data.message || 'Lỗi khi tải lên ảnh đại diện');
                        }
                        
                        if (data.url) {
                            console.log('Cập nhật ảnh đại diện nhóm:', data.url);
                            
                            // Cập nhật ảnh vào CSDL
                            const groupUpdateResponse = await fetch('{{ route("group.update", $group->id) }}', {
                                method: 'POST',
                                body: JSON.stringify({
                                    _token: '{{ csrf_token() }}',
                                    _method: 'PUT',
                                    photo: data.url,
                                    title: '{{ $group->title }}',
                                    type_code: '{{ $group->type_code }}',
                                    description: '{{ $group->description }}',
                                    is_private: {{ $group->is_private ? 'true' : 'false' }}
                                }),
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            });
                            
                            if (!groupUpdateResponse.ok) {
                                throw new Error('Không thể cập nhật ảnh đại diện nhóm');
                            }
                            
                            // Đóng modal và làm mới trang
                            photoModal.classList.add('hidden');
                            window.location.reload();
                        } else {
                            throw new Error('Không nhận được URL ảnh từ server');
                        }
                    } catch (error) {
                        console.error('Lỗi:', error);
                        alert('Có lỗi xảy ra: ' + error.message);
                    } finally {
                        savePhotoBtn.disabled = false;
                        savePhotoBtn.innerHTML = 'Lưu thay đổi';
                    }
                });
            }
            
            // Save Cover
            if (saveCoverBtn) {
                saveCoverBtn.addEventListener('click', async function() {
                    if (!coverFileInput.files || !coverFileInput.files[0]) {
                        alert('Vui lòng chọn ảnh để tải lên');
                        return;
                    }
                    
                    const coverForm = document.getElementById('cover-modal-form');
                    const formData = new FormData();
                    formData.append('banner', coverFileInput.files[0]); // Sử dụng 'banner' để phù hợp với bannerUpload
                    formData.append('_token', '{{ csrf_token() }}');
                    
                    try {
                        saveCoverBtn.disabled = true;
                        saveCoverBtn.innerHTML = 'Đang lưu...';
                        
                        console.log('Uploading group banner...');
                        
                        const response = await fetch('{{ route("front.upload.banner") }}', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });
                        
                        const data = await response.json();
                        console.log('Banner response:', data);
                        
                        if (!response.ok || !data.status) {
                            throw new Error(data.message || 'Lỗi khi tải lên ảnh bìa');
                        }
                        
                        if (data.url) {
                            console.log('Cập nhật ảnh bìa nhóm:', data.url);
                            
                            // Chi tiết dữ liệu gửi đi
                            const updateData = {
                                _token: '{{ csrf_token() }}',
                                _method: 'PUT',
                                cover_photo: data.url,
                                title: '{{ $group->title }}',
                                type_code: '{{ $group->type_code }}',
                                description: '{{ addslashes($group->description) }}',
                                is_private: {{ $group->is_private ? 'true' : 'false' }}
                            };
                            
                            console.log('Sending update data:', updateData);
                            
                            // Cập nhật ảnh vào CSDL
                            const groupUpdateResponse = await fetch('{{ route("group.update", $group->id) }}', {
                                method: 'POST',
                                body: JSON.stringify(updateData),
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            });
                            
                            if (!groupUpdateResponse.ok) {
                                throw new Error('Không thể cập nhật ảnh bìa nhóm');
                            }
                            
                            // Đóng modal và làm mới trang
                            coverModal.classList.add('hidden');
                            window.location.reload();
                        } else {
                            throw new Error('Không nhận được URL ảnh từ server');
                        }
                    } catch (error) {
                        console.error('Lỗi:', error);
                        alert('Có lỗi xảy ra: ' + error.message);
                    } finally {
                        saveCoverBtn.disabled = false;
                        saveCoverBtn.innerHTML = 'Lưu thay đổi';
                    }
                });
            }
        });

        // Hàm chuyển đổi tab
        function switchTab(tabName) {
            // Cập nhật biến activeTab
            activeTab = tabName;
            
            // Ẩn tất cả tab content
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.add('hidden');
            });
            
            // Hiển thị tab được chọn
            document.getElementById(tabName + '-content').classList.remove('hidden');
            
            // Cập nhật trạng thái active của các tab button
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('border-blue-500', 'text-blue-600');
                button.classList.add('border-transparent', 'hover:text-blue-500', 'hover:border-blue-200');
            });
            
            document.getElementById(tabName + '-tab').classList.remove('border-transparent', 'hover:text-blue-500', 'hover:border-blue-200');
            document.getElementById(tabName + '-tab').classList.add('border-blue-500', 'text-blue-600');
            
            // Tải dữ liệu cho tab nếu cần
            if (tabName === 'members' && !membersLoaded) {
                loadMembers();
            }
            
            if (tabName === 'requests' && !requestsLoaded) {
                loadJoinRequests();
            }
        }
        
        // Hàm tải danh sách thành viên
        function loadMembers() {
            const tableBody = document.getElementById('memberTableBody');
            
            // Hiển thị loading
            tableBody.innerHTML = `
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                        <i class="fas fa-spinner fa-spin mr-2"></i> Đang tải danh sách thành viên...
                    </td>
                </tr>
            `;
            
            // Dữ liệu thành viên từ PHP (thay vì gọi AJAX)
            try {
                // Sử dụng dữ liệu thành viên đã truyền từ controller
                const membersData = @json(isset($members) ? $members : []);
                
                // Debug thông tin thành viên
                console.log('Original members data:', membersData);
                
                const groupData = {
                    id: {{ $group->id }},
                    title: "{{ $group->title }}",
                    author_id: {{ $group->author_id }},
                    moderators: @json(json_decode($group->moderators ?? '[]', true))
                };
                
                // Debug thông tin nhóm
                console.log('Group data:', groupData);
                
                // Tạo object data giống như trả về từ API
                const data = {
                    members: membersData,
                    group: groupData
                };
                
                // Đánh dấu đã tải dữ liệu
                membersLoaded = true;
                
                // Render danh sách thành viên
                renderMemberList(data, tableBody);
                
                // Thiết lập chức năng tìm kiếm
                document.getElementById('memberSearchInput').addEventListener('keyup', function() {
                    const searchTerm = this.value.toLowerCase();
                    const rows = tableBody.getElementsByTagName('tr');
                    
                    for (let row of rows) {
                        const nameCell = row.querySelector('td:first-child');
                        if (nameCell) {
                            const name = nameCell.textContent.toLowerCase();
                            row.style.display = name.includes(searchTerm) ? '' : 'none';
                        }
                    }
                });
            } catch (error) {
                console.error('Error loading members:', error);
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-red-500">
                            Không thể tải danh sách thành viên. Vui lòng thử lại sau.
                        </td>
                    </tr>
                `;
            }
        }
        
        // Hàm tải danh sách yêu cầu tham gia
        function loadJoinRequests() {
            const requestsList = document.getElementById('joinRequestsList');
            
            // Hiển thị loading
            requestsList.innerHTML = `
                <div class="text-center text-gray-500 text-sm py-4">
                    <i class="fas fa-spinner fa-spin mr-2"></i> Đang tải danh sách yêu cầu...
                </div>
            `;
            
            // Sử dụng dữ liệu yêu cầu đã có sẵn từ server
            const joinRequestsData = @json($formattedRequests);
            
            // Đánh dấu đã tải dữ liệu
            requestsLoaded = true;
            
            // Render danh sách yêu cầu
            renderJoinRequests(joinRequestsData, requestsList);
            
            // Thiết lập chức năng tìm kiếm
            document.getElementById('requestSearchInput').addEventListener('keyup', function() {
                const searchTerm = this.value.toLowerCase();
                const items = requestsList.querySelectorAll('.member-request-item');
                
                items.forEach(item => {
                    const nameElement = item.querySelector('h4');
                    if (nameElement) {
                        const name = nameElement.textContent.toLowerCase();
                        item.style.display = name.includes(searchTerm) ? '' : 'none';
                    }
                });
            });
        }
        
        // Hàm render danh sách thành viên
        function renderMemberList(data, container) {
            // Nhúng các biến PHP an toàn vào JavaScript
            const isAdmin = @json(Auth::check() && Auth::user()->role === 'admin');
            const isGroupOwner = @json(Auth::id() == $group->author_id);
            const isGroupModerator = @json(Auth::check() && in_array(Auth::id(), json_decode($group->moderators ?? '[]', true)));
            const canManageMembers = isAdmin || isGroupOwner || isGroupModerator;
            
            // Log dữ liệu thành viên để debug
            console.log('Members data:', data.members);
            console.log('Group data:', data.group);
            console.log('Moderators from group data:', data.group.moderators);
            
            let rowsHtml = '';
            
            // Convert moderator ids to strings để đảm bảo so sánh đúng
            const moderatorIdsAsStrings = Array.isArray(data.group.moderators) 
                ? data.group.moderators.map(id => String(id)) 
                : [];
            
            console.log('Moderator IDs as strings:', moderatorIdsAsStrings);
            
            // Thêm chủ nhóm đầu tiên
            const owner = data.members.find(member => {
                // Tìm theo user_id hoặc id tùy vào dữ liệu có sẵn
                const memberId = member.user_id ?? member.id;
                return String(memberId) === String(data.group.author_id);
            });
            
            if (owner) {
                rowsHtml += addMemberRow(owner, 'Nhóm trưởng', 'yellow', canManageMembers);
            }
            
            // Thêm nhóm phó
            const moderators = data.members.filter(member => {
                // Tìm theo user_id hoặc id tùy vào dữ liệu có sẵn
                const memberId = member.user_id ?? member.id;
                const isModerator = moderatorIdsAsStrings.includes(String(memberId));
                
                // Log kiểm tra từng thành viên
                console.log(`Checking member ${memberId} (${member.name || member.full_name}): is moderator = ${isModerator}`);
                
                return String(memberId) !== String(data.group.author_id) && isModerator;
            });
            
            console.log('Found moderators:', moderators);
            
            moderators.forEach(member => {
                rowsHtml += addMemberRow(member, 'Nhóm phó', 'blue', canManageMembers);
            });
            
            // Thêm thành viên thường
            const regularMembers = data.members.filter(member => {
                // Tìm theo user_id hoặc id tùy vào dữ liệu có sẵn
                const memberId = member.user_id ?? member.id;
                return String(memberId) !== String(data.group.author_id) && 
                    !moderatorIdsAsStrings.includes(String(memberId));
            });
            
            console.log('Regular members:', regularMembers);
            
            regularMembers.forEach(member => {
                rowsHtml += addMemberRow(member, 'Thành viên', 'green', canManageMembers);
            });
            
            // Nếu không có thành viên
            if (rowsHtml === '') {
                rowsHtml = `
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                            Chưa có thành viên nào trong nhóm.
                        </td>
                    </tr>
                `;
            }
            
            container.innerHTML = rowsHtml;
        }
        
        // Hàm tạo hàng cho bảng thành viên
        function addMemberRow(member, role, colorClass, canManageMembers) {
            // Đảm bảo lấy đúng user_id - nếu member.user_id không tồn tại, sẽ dùng member.id
            const userId = member.user_id ?? member.id ?? 0;
            const photoUrl = member.photo || defaultAvatarUrl;
            const fullName = member.full_name || (member.name || 'N/A');
            
            // Xử lý ngày tham gia - có thể lấy từ nhiều nguồn
            let joinedAtValue = 'N/A';
            
            // Kiểm tra và lấy ngày tham gia từ các thuộc tính có thể có
            if (member.joined_at) {
                joinedAtValue = member.joined_at;
            } else if (member.created_at) {
                joinedAtValue = member.created_at;
            } else if (member.pivot && member.pivot.created_at) {
                joinedAtValue = member.pivot.created_at;
            } else if (member.group_member && member.group_member.created_at) {
                joinedAtValue = member.group_member.created_at;
            }
            
            // Format ngày tham gia nếu có
            let joinedAt = 'N/A';
            if (joinedAtValue !== 'N/A') {
                try {
                    const date = new Date(joinedAtValue);
                    if (!isNaN(date.getTime())) {
                        joinedAt = date.toLocaleDateString('vi-VN');
                    }
                } catch (e) {
                    console.error('Error formatting date:', e);
                }
            }
            
            // Log để debug
            console.log('Member data:', member);
            console.log('Using userId:', userId);
            console.log('Joined at value:', joinedAtValue);
            console.log('Formatted joined at:', joinedAt);
            
            // Nhúng các biến PHP an toàn vào JavaScript
            const isGroupOwner = @json(Auth::id() == $group->author_id);
            const isGroupModerator = @json(Auth::check() && in_array(Auth::id(), json_decode($group->moderators ?? '[]', true)));
            
            // Các nút hành động
            let actionButtons = '';
            
            if (role !== 'Nhóm trưởng') {
                // Nút nâng cấp thành viên lên phó nhóm - chỉ hiển thị cho Nhóm trưởng
                if (role === 'Thành viên' && isGroupOwner) {
                    actionButtons += `
                        <button onclick="promoteToModerator(${userId})" class="text-blue-600 hover:text-blue-900 mr-2">
                            <i class="fas fa-level-up-alt"></i> Phong phó nhóm
                        </button>
                    `;
                }
                
                // Nút hạ cấp phó nhóm xuống thành viên thường - chỉ hiển thị cho Nhóm trưởng
                if (role === 'Nhóm phó' && isGroupOwner) {
                    actionButtons += `
                        <button onclick="demoteToMember(${userId})" class="text-yellow-600 hover:text-yellow-900 mr-2">
                            <i class="fas fa-level-down-alt"></i> Hạ cấp
                        </button>
                    `;
                }
                
                // Nút xóa thành viên - chỉ hiển thị cho Nhóm trưởng
                if (isGroupOwner) {
                    actionButtons += `
                        <button onclick="removeMember(${userId})" class="text-red-600 hover:text-red-900">
                            <i class="fas fa-user-times"></i> Xóa
                        </button>
                    `;
                }
            }
            
            return `
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <img class="h-10 w-10 rounded-full object-cover" src="${photoUrl}" alt="${fullName}">
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">${fullName}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-${colorClass}-100 text-${colorClass}-800">
                            ${role}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${joinedAt}</td>
                    ${canManageMembers ? `
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            ${actionButtons}
                        </td>
                    ` : ''}
                </tr>
            `;
        }
        
        // Hàm render danh sách yêu cầu tham gia
        function renderJoinRequests(requests, container) {
            if (!requests || requests.length === 0) {
                container.innerHTML = `
                    <div class="text-center text-gray-500 py-8">
                        <i class="fas fa-inbox text-4xl mb-2"></i>
                        <p>Không có yêu cầu tham gia nào.</p>
                    </div>
                `;
                return;
            }
            
            let html = '';
            
            requests.forEach(request => {
                html += `
                    <div class="member-request-item bg-white rounded-lg shadow-sm p-4 hover:bg-gray-50 transition">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <img src="${request.user.photo || defaultAvatarUrl}" alt="${request.user.full_name}" 
                                    class="w-12 h-12 rounded-full object-cover mr-3">
                                <div>
                                    <h4 class="font-medium text-gray-800">${request.user.full_name}</h4>
                                    <p class="text-sm text-gray-500">${request.created_at_human}</p>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <form action="${groupApproveMemberUrl}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="user_id" value="${request.user.id}">
                                    <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
                                        <i class="fas fa-check mr-1"></i> Chấp nhận
                                    </button>
                                </form>
                                <form action="${groupRejectMemberUrl}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="user_id" value="${request.user.id}">
                                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                        <i class="fas fa-times mr-1"></i> Từ chối
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            container.innerHTML = html;
        }
        
        // Các hàm quản lý thành viên
        function promoteToModerator(userId) {
            if (!userId || userId === 0) {
                alert('Lỗi: Không thể xác định ID thành viên. Vui lòng thử lại sau.');
                console.error('Invalid userId:', userId);
                return;
            }
            
            if (confirm('Bạn có chắc muốn thăng cấp thành viên này thành nhóm phó?')) {
                const groupId = {{ $group->id }};
                const url = `{{ route('group.promote', ['id' => ':id', 'user_id' => ':user_id']) }}`.replace(':id', groupId).replace(':user_id', userId);
                
                console.log('Sending promote request to:', url);
                
                // Gửi yêu cầu API để thăng cấp thành viên
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    if (!response.ok) {
                        return response.text().then(text => {
                            try {
                                // Thử phân tích response text là JSON
                                const errorData = JSON.parse(text);
                                throw new Error(errorData.message || 'Yêu cầu không thành công, không nâng cấp được thành viên.');
                            } catch (e) {
                                // Nếu không phải JSON, trả về lỗi với text
                                throw new Error('Lỗi ' + response.status + ': ' + (text || 'Không thể nâng cấp thành viên'));
                            }
                        });
                    }
                    return response.text().then(text => text ? JSON.parse(text) : {});
                })
                .then(data => {
                    alert('Đã thăng cấp thành viên thành nhóm phó thành công!');
                    console.log('Promotion successful, refreshing page to update member status');
                    
                    // Làm mới trang để cập nhật danh sách moderators từ server
                    window.location.reload();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert(error.message || 'Có lỗi xảy ra khi thăng cấp thành viên.');
                });
            }
        }
        
        function demoteToMember(userId) {
            if (!userId || userId === 0) {
                alert('Lỗi: Không thể xác định ID thành viên. Vui lòng thử lại sau.');
                console.error('Invalid userId:', userId);
                return;
            }
            
            if (confirm('Bạn có chắc muốn hạ cấp nhóm phó này xuống thành viên thường?')) {
                const groupId = {{ $group->id }};
                const url = `{{ route('group.demote', ['id' => ':id', 'user_id' => ':user_id']) }}`.replace(':id', groupId).replace(':user_id', userId);
                
                console.log('Sending demote request to:', url);
                
                // Gửi yêu cầu API để hạ cấp thành viên
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    if (!response.ok) {
                        return response.text().then(text => {
                            try {
                                // Thử phân tích response text là JSON
                                const errorData = JSON.parse(text);
                                throw new Error(errorData.message || 'Yêu cầu không thành công, không hạ cấp được nhóm phó.');
                            } catch (e) {
                                // Nếu không phải JSON, trả về lỗi với text
                                throw new Error('Lỗi ' + response.status + ': ' + (text || 'Không thể hạ cấp nhóm phó'));
                            }
                        });
                    }
                    return response.text().then(text => text ? JSON.parse(text) : {});
                })
                .then(data => {
                    alert('Đã hạ cấp nhóm phó xuống thành viên thường thành công!');
                    console.log('Demotion successful, refreshing page to update member status');
                    
                    // Làm mới trang để cập nhật danh sách moderators từ server
                    window.location.reload();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert(error.message || 'Có lỗi xảy ra khi hạ cấp nhóm phó.');
                });
            }
        }
        
        function removeMember(userId) {
            if (!userId || userId === 0) {
                alert('Lỗi: Không thể xác định ID thành viên. Vui lòng thử lại sau.');
                console.error('Invalid userId:', userId);
                return;
            }
            
            if (confirm('Bạn có chắc muốn xóa thành viên này khỏi nhóm?')) {
                const groupId = {{ $group->id }};
                const url = `{{ route('group.remove', ['id' => ':id', 'user_id' => ':user_id']) }}`.replace(':id', groupId).replace(':user_id', userId);
                
                console.log('Sending remove request to:', url);
                
                // Gửi yêu cầu API để xóa thành viên
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    if (!response.ok) {
                        return response.text().then(text => {
                            try {
                                // Thử phân tích response text là JSON
                                const errorData = JSON.parse(text);
                                throw new Error(errorData.message || 'Yêu cầu không thành công, không xóa được thành viên.');
                            } catch (e) {
                                // Nếu không phải JSON, trả về lỗi với text
                                throw new Error('Lỗi ' + response.status + ': ' + (text || 'Không thể xóa thành viên'));
                            }
                        });
                    }
                    return response.text().then(text => text ? JSON.parse(text) : {});
                })
                .then(data => {
                    alert('Đã xóa thành viên khỏi nhóm thành công!');
                    console.log('Member removal successful, refreshing page to update member list');
                    
                    // Làm mới trang để cập nhật danh sách thành viên từ server
                    window.location.reload();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert(error.message || 'Có lỗi xảy ra khi xóa thành viên.');
                });
            }
        }

        // Modal đăng bài viết
        function openCreatePostModal() {
            const modal = document.getElementById('createPostModal');
            const content = document.getElementById('createPostContent');
            const groupId = "{{ $group->id }}"; // Lấy group_id từ trang chi tiết nhóm

            // Hiển thị modal với spinner loading
            modal.classList.remove('hidden');
            content.innerHTML =
                '<div class="flex justify-center"><div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div></div>';

            // Tải form từ server
            fetch('{{ route('front.tblogs.get-form') }}', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(async response => {
                    const contentType = response.headers.get('content-type');

                    if (!response.ok) {
                        if (response.status === 401) {
                            window.location.href = '{{ route('front.login') }}';
                            throw new Error('Bạn cần đăng nhập để thực hiện chức năng này.');
                        }
                        // Nếu không phải JSON, trả về thông báo lỗi chung
                        if (!contentType || !contentType.includes('application/json')) {
                            throw new Error('Máy chủ trả về nội dung không hợp lệ. Có thể là lỗi 500 hoặc HTML.');
                        }
                        const errorData = await response.json();
                        throw new Error(errorData.error || 'Không thể tải form.');
                    }

                    if (!contentType || !contentType.includes('application/json')) {
                        throw new Error('Nội dung phản hồi không phải JSON.');
                    }

                    return response.json();
                })

                .then(data => {
                    // Render form với group_id
                    renderCreateForm(data, content, groupId);
                })
                .catch(error => {
                    console.error('Error:', error);
                    content.innerHTML = `<div class="text-red-500 text-center p-4">${error.message}</div>`;
                });
        }

        function closeCreatePostModal() {
            const modal = document.getElementById('createPostModal');
            modal.classList.add('hidden');
        }

        function renderCreateForm(data, container, groupId) {
            // HTML form template
            const formHtml = `
                <form id="createPostForm" action="${data.store_url}" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="${data.csrf_token}">
                    <input type="hidden" name="group_id" value="${groupId}">
                    
                    <!-- Upload ảnh đầu bài -->
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Upload hình ảnh</label>
                        <div class="dropzone border-2 border-dashed border-blue-400 rounded-lg p-4 bg-gray-50" id="imageDropzone"></div>
                        <div id="uploadStatus" class="mt-2 p-2 hidden"></div>
                    </div>
                    
                    <!-- Ẩn input để lưu tên file ảnh -->
                    <input type="hidden" name="photo" id="uploadedImages">
                    
                    <!-- Tiêu đề bài viết -->
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Tiêu đề</label>
                        <input type="text" name="title" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Tiêu đề bài viết..." required>
                    </div>

                    <!-- Thẻ bài viết -->
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Thẻ bài viết</label>
                        <select id="tags" name="tags[]" multiple class="w-full">
                            ${data.tags.map(tag => `<option value="${tag.id}">${tag.title}</option>`).join('')}
                        </select>
                        <span class="text-sm text-gray-500">Tối đa 5 tag</span>
                        
                        <div class="flex flex-wrap gap-2 mt-2">
                            ${data.toptags.map(tag => 
                                `<button type="button" class="tag-button bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs hover:bg-blue-200" 
                                                data-tag-id="${tag.id}" data-tag-name="${tag.title}">
                                                #${tag.title}
                                            </button>`
                            ).join('')}
                        </div>
                    </div>

                    <!-- Nội dung bài viết -->
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Nội dung</label>
                        <textarea name="content" id="editor" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Nội dung bài viết"></textarea>
                    </div>

                    <!-- Tài liệu đính kèm -->
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Tài liệu</label>
                        <input type="file" name="document[]" id="document" class="w-full px-3 py-2 border border-gray-300 rounded-md" multiple>
                        <input type="text" name="urls[]" class="w-full px-3 py-2 border border-gray-300 rounded-md mt-2" placeholder="URL file (nếu có)">
                    </div>

                    <!-- Trạng thái bài viết -->
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Trạng thái bài viết</label>
                        <div class="flex space-x-4">
                            <div class="flex items-center">
                                <input type="radio" id="status_public" name="status" value="1" class="mr-2" checked>
                                <label for="status_public" class="text-sm">
                                    <i class="fas fa-globe-americas mr-1"></i> Công khai
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" id="status_private" name="status" value="0" class="mr-2">
                                <label for="status_private" class="text-sm">
                                    <i class="fas fa-lock mr-1"></i> Chỉ mình tôi
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Nút hành động -->
                    <div class="flex justify-between mt-6">
                        <button type="button" onclick="closeCreatePostModal()" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-100">Hủy</button>
                        <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Đăng bài</button>
                    </div>
                </form>
            `;

            // Render form
            container.innerHTML = formHtml;

            // Khởi tạo Dropzone cho upload ảnh
            Dropzone.autoDiscover = false;

            const uploadedImages = [];
            const uploadStatus = document.getElementById('uploadStatus');

            const imageDropzone = new Dropzone("#imageDropzone", {
                url: data.upload_avatar_url,
                paramName: "photo",
                maxFilesize: 2,
                acceptedFiles: 'image/*',
                addRemoveLinks: true,
                dictDefaultMessage: "Kéo thả ảnh vào đây hoặc nhấp để chọn",
                dictRemoveFile: "Xóa ảnh",
                thumbnailWidth: 150,
                thumbnailHeight: 150,
                maxFiles: 5,
                headers: {
                    'X-CSRF-TOKEN': data.csrf_token
                },
                init: function() {
                    this.on("addedfile", function(file) {
                        uploadStatus.classList.add('hidden');
                    });

                    this.on("error", function(file, errorMessage) {
                        uploadStatus.classList.remove('hidden');
                        uploadStatus.classList.add('bg-red-100', 'text-red-800', 'rounded-md');
                        uploadStatus.textContent = "Lỗi tải lên: " + errorMessage;
                    });

                    this.on("success", function(file, response) {
                        uploadStatus.classList.remove('hidden');
                        uploadStatus.classList.add('bg-green-100', 'text-green-800', 'rounded-md');
                        uploadStatus.textContent = "Tải lên thành công!";
                        setTimeout(() => {
                            uploadStatus.classList.add('hidden');
                        }, 3000);
                    });

                    this.on("maxfilesexceeded", function(file) {
                        this.removeFile(file);
                        alert("Bạn chỉ có thể tải lên tối đa 5 ảnh!");
                    });
                },
                success: function(file, response) {
                    uploadedImages.push(response.link);
                    document.getElementById('uploadedImages').value = JSON.stringify(uploadedImages);
                },
                removedfile: function(file) {
                    const response = JSON.parse(file.xhr.response);
                    const index = uploadedImages.indexOf(response.link);
                    if (index !== -1) {
                        uploadedImages.splice(index, 1);
                        document.getElementById('uploadedImages').value = JSON.stringify(uploadedImages);
                    }
                    file.previewElement.remove();
                }
            });

            // Khởi tạo Tom Select cho tags
            new TomSelect('#tags', {
                maxItems: 5,
                plugins: ['remove_button'],
                placeholder: 'Chọn hoặc tạo thẻ mới...',
                create: false
            });

            // Khởi tạo CKEditor
            ClassicEditor.create(document.querySelector('#editor'), {
                ckfinder: {
                    uploadUrl: data.ckeditor_upload_url
                },
                mediaEmbed: {
                    previewsInData: true
                }
            }).catch(error => {
                console.error("Lỗi CKEditor:", error);
            });

            // Xử lý sự kiện khi nhấn vào tag button
            document.querySelectorAll('.tag-button').forEach(button => {
                button.addEventListener('click', function() {
                    const tagId = this.dataset.tagId;
                    const tagName = this.dataset.tagName;

                    // Lấy đối tượng Tom Select và thêm item
                    const select = document.querySelector('#tags');
                    const tomSelect = select.tomselect;

                    if (tomSelect) {
                        if (!tomSelect.items.includes(tagId)) {
                            tomSelect.addItem(tagId);
                        } else {
                            tomSelect.removeItem(tagId);
                        }
                    }
                });
            });

            // Xử lý gửi form
            document.getElementById('createPostForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);

                fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (response.redirected) {
                            window.location.href = response.url;
                            return;
                        }

                        if (!response.ok) {
                            return response.json().then(data => {
                                throw new Error(data.error || 'Đã xảy ra lỗi khi đăng bài viết.');
                            });
                        }

                        return response.json();
                    })
                    .then(data => {
                        if (data && data.success) {
                            window.location.reload();
                        } else if (data) {
                            alert(data.message || 'Đã xảy ra lỗi khi đăng bài viết.');
                        } else {
                            window.location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert(error.message || 'Đã xảy ra lỗi khi đăng bài viết.');
                    });
            });
        }

        // Khởi tạo khi trang được tải
        document.addEventListener('DOMContentLoaded', function() {
            // Thiết lập tab mặc định
            switchTab('posts');
            
            // Fix lỗi khi DOM đã được render trước khi script được tải
            // Chỉ thực hiện nếu chưa có các liên kết đúng
            const viewAllMembersLinks = document.querySelectorAll('a[href="#"].text-blue-500.text-sm.hover\\:underline');
            viewAllMembersLinks.forEach(link => {
                if (!link.hasAttribute('onclick')) {
                    link.href = "javascript:void(0);";
                    link.setAttribute('onclick', 'switchTab("members")');
                }
            });
        });
        
        // Chuẩn bị dữ liệu cho trang
        const initFormComponents = function() {
            // Các chức năng khởi tạo khác
        };
        
        // Đóng popup
        function closePopup() {
            document.getElementById('contentPopup').classList.add('hidden');
        }
        
        // Đóng các modal cũ
        function closeMemberListModal() {
            document.getElementById('memberListModal').classList.add('hidden');
        }
        
        function closeJoinRequestsModal() {
            document.getElementById('joinRequestsModal').classList.add('hidden');
        }

        // Thêm các function mới mà không làm ảnh hưởng đến code hiện có
        function openCreatePollModal() {
            document.getElementById('createPollModal').classList.remove('hidden');
        }

        function closeCreatePollModal() {
            document.getElementById('createPollModal').classList.add('hidden');
        }

        function addPollOption() {
            const optionsContainer = document.getElementById('poll-options');
            const optionCount = optionsContainer.children.length;
            
            if (optionCount < 5) {
                const newOption = document.createElement('div');
                newOption.classList.add('mb-2', 'flex', 'items-center');
                newOption.innerHTML = `
                    <input type="text" name="options[]" required placeholder="Lựa chọn ${optionCount + 1}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <button type="button" class="ml-2 text-red-500 hover:text-red-700" onclick="removePollOption(this)">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                optionsContainer.appendChild(newOption);
            }
            
            // Disable add button if max reached
            if (optionCount + 1 >= 5) {
                document.querySelector('button[onclick="addPollOption()"]').disabled = true;
            }
        }

        function removePollOption(button) {
            const optionsContainer = document.getElementById('poll-options');
            button.parentElement.remove();
            
            // Re-enable add button if below max
            if (optionsContainer.children.length < 5) {
                document.querySelector('button[onclick="addPollOption()"]').disabled = false;
            }
            
            // Re-number placeholders
            const inputs = optionsContainer.querySelectorAll('input');
            inputs.forEach((input, index) => {
                input.placeholder = `Lựa chọn ${index + 1}`;
            });
        }

        // Submit form với AJAX để tránh chuyển trang
        document.getElementById('createPollForm').addEventListener('submit', function(event) {
            event.preventDefault();
            
            const formData = new FormData(this);
            
            // Hiển thị loading
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
                
                if (data.success) {
                    // Thông báo thành công và đóng modal
                    alert('Khảo sát đã được tạo thành công!');
                    closeCreatePollModal();
                    
                    // Reset form cho lần sau
                    this.reset();
                    
                    // Xóa các options mở rộng
                    const optionsContainer = document.getElementById('poll-options');
                    while (optionsContainer.children.length > 2) {
                        optionsContainer.removeChild(optionsContainer.lastChild);
                    }
                    
                    // Log debug info
                    console.log('Khảo sát đã được tạo: ', data.poll);
                    
                    // Reload trang để hiển thị khảo sát mới trong danh sách bài viết
                    if (data.poll && data.poll.blog_created) {
                        window.location.reload();
                    }
                } else {
                    // Hiển thị lỗi
                    alert('Có lỗi xảy ra: ' + (data.message || 'Không thể tạo khảo sát'));
                    
                    if (data.errors) {
                        let errorMessages = '';
                        for (const key in data.errors) {
                            errorMessages += `${data.errors[key].join('\n')}\n`;
                        }
                        if (errorMessages) {
                            alert(errorMessages);
                        }
                    }
                }
            })
            .catch(error => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
                console.error('Error:', error);
                alert('Đã xảy ra lỗi khi gửi yêu cầu');
            });
        });

        // Xử lý bình chọn khảo sát
        document.addEventListener('DOMContentLoaded', function() {
            const pollForms = document.querySelectorAll('.poll-vote-form');
            
            pollForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const pollId = this.closest('.poll-container').dataset.pollId;
                    const selectedOption = this.querySelector('input[name="option_index"]:checked');
                    
                    if (!selectedOption) {
                        alert('Vui lòng chọn một phương án trả lời');
                        return;
                    }
                    
                    const optionIndex = selectedOption.value;
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalBtnText = submitBtn.innerHTML;
                    
                    // Hiển thị loading
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
                    
                    // Gửi request bình chọn
                    fetch(`/poll-vote/${pollId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            option_index: optionIndex
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalBtnText;
                        
                        if (data.success) {
                            // Ẩn form bình chọn
                            this.classList.add('hidden');
                            
                            // Cập nhật kết quả khảo sát
                            const pollContainer = this.closest('.poll-container');
                            const resultsContainer = pollContainer.querySelector('.poll-results');
                            
                            // Hiển thị kết quả
                            resultsContainer.classList.remove('hidden');
                            
                            // Cập nhật số liệu phiếu bầu
                            if (data.results) {
                                const resultBars = resultsContainer.querySelectorAll('.bg-blue-600');
                                const resultTexts = resultsContainer.querySelectorAll('.text-gray-500');
                                
                                data.results.options.forEach((option, i) => {
                                    if (resultBars[i] && resultTexts[i]) {
                                        const votes = data.results.votes[i];
                                        const percentage = data.results.percentages[i];
                                        
                                        resultBars[i].style.width = `${percentage}%`;
                                        resultTexts[i].textContent = `${percentage}% (${votes} phiếu)`;
                                    }
                                });
                                
                                // Cập nhật tổng số phiếu
                                const totalVotesElem = resultsContainer.querySelector('.text-gray-500.mt-2');
                                if (totalVotesElem) {
                                    totalVotesElem.textContent = `Tổng số phiếu: ${data.results.total_votes}`;
                                }
                            }
                        } else {
                            alert(data.message || 'Có lỗi xảy ra khi bình chọn');
                        }
                    })
                    .catch(error => {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalBtnText;
                        console.error('Error:', error);
                        alert('Đã xảy ra lỗi khi gửi yêu cầu');
                    });
                });
            });
        });

        // Xử lý form thay đổi bình chọn khảo sát
        $(document).on('submit', '.poll-vote-form', function(e) {
            e.preventDefault();
            
            var form = $(this);
            var pollId = form.data('poll-id');
            var selectedOption = form.find('input[name="option_index"]:checked');
            
            if (!selectedOption.length) {
                alert('Vui lòng chọn một lựa chọn');
                return;
            }
            
            var optionIndex = selectedOption.val();
            var optionId = selectedOption.data('option-id') || optionIndex;
            
            // Hiển thị loading trên nút submit
            var submitBtn = form.find('button[type="submit"]');
            var originalText = submitBtn.html();
            submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Đang xử lý...');
            
            $.ajax({
                url: `/polls/${pollId}/ajax-vote`,
                type: 'POST',
                data: {
                    option_index: optionIndex,
                    option_id: optionId,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log("Server response:", response); // Debug: Log server response
                    
                    // Khôi phục nút submit
                    submitBtn.prop('disabled', false).html(originalText);
                    
                    if (response.success) {
                        // Hiển thị kết quả
                        var resultsContainer = $(`#poll-results-${pollId}`);
                        resultsContainer.removeClass('hidden');
                        form.addClass('hidden');
                        
                        var html = '';
                        
                        if (response.results && response.results.options) {
                            // Sử dụng kết quả trả về từ server để hiển thị
                            $.each(response.results.options, function(i, option) {
                                var votes = response.results.counts[i];
                                var percentage = response.results.percentages[i];
                                var isUserVote = (response.option_index == i || response.voted_option_id == optionId);
                                
                                html += `
                                    <div class="mb-3">
                                        <div class="flex justify-between mb-1">
                                            <span class="text-sm font-medium">${option}</span>
                                            <span class="text-sm text-gray-500">
                                                ${percentage}% (${votes} phiếu)
                                            </span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: ${percentage}%"></div>
                                        </div>
                                        ${isUserVote ? '<div class="text-end"><small class="text-muted"><i class="fas fa-check-circle text-success"></i> Lựa chọn của bạn</small></div>' : ''}
                                    </div>
                                `;
                            });
                            
                            html += `<div class="text-sm text-gray-500 mt-2">Tổng số phiếu: ${response.results.total_votes}</div>`;
                            
                            resultsContainer.html(html);
                            
                            // Cập nhật nút bấm xem kết quả
                            $(`button[onclick="togglePollResults('${pollId}')"]`).html('<i class="fas fa-chart-bar mr-1"></i> Ẩn kết quả');
                        } else {
                            // Nếu không có kết quả chi tiết, tải lại trang
                            location.reload();
                        }
                    } else {
                        alert(response.message || 'Có lỗi xảy ra khi bình chọn');
                    }
                },
                error: function(xhr) {
                    console.error("Ajax error:", xhr); // Debug: Log any AJAX errors
                    
                    submitBtn.prop('disabled', false).html(originalText);
                    var errorMsg = 'Đã xảy ra lỗi khi gửi yêu cầu';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    alert(errorMsg);
                }
            });
        });

        // Hàm để chuyển đổi hiển thị kết quả khảo sát
        function togglePollResults(pollId) {
            var resultsContainer = $(`#poll-results-${pollId}`);
            resultsContainer.toggleClass('hidden');
        }

        // Toggle dropdown cho Poll
        function togglePollDropdown(pollId) {
            const dropdown = document.getElementById(`poll-dropdown-${pollId}`);
            if (dropdown) {
                dropdown.classList.toggle('active');
                
                // Đóng các dropdown khác
                document.querySelectorAll('.dropdown-menu.active').forEach(el => {
                    if (el.id !== `poll-dropdown-${pollId}`) {
                        el.classList.remove('active');
                    }
                });
                
                // Click bên ngoài để đóng dropdown
                document.addEventListener('click', function(event) {
                    if (!event.target.closest('.dropdown')) {
                        document.querySelectorAll('.dropdown-menu.active').forEach(el => {
                            el.classList.remove('active');
                        });
                    }
                }, { once: true });
            }
        }
        
        // Xử lý bình chọn cho khảo sát
        document.addEventListener('DOMContentLoaded', function() {
            const pollForms = document.querySelectorAll('.poll-vote-form');
            
            pollForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const pollId = this.dataset.pollId;
                    const selectedOption = this.querySelector('input[name="option_id"]:checked');
                    
                    if (!selectedOption) {
                        alert('Vui lòng chọn một phương án trả lời');
                        return;
                    }
                    
                    const optionId = selectedOption.value;
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalBtnText = submitBtn.innerHTML;
                    
                    // Hiển thị loading
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
                    
                    // Gửi request bình chọn
                    fetch(`/polls/${pollId}/vote`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            option_id: optionId,
                            _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(data => {
                                throw new Error(data.message || 'Có lỗi xảy ra khi bình chọn');
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalBtnText;
                        
                        // Ẩn form bình chọn
                        this.classList.add('hidden');
                        
                        // Hiển thị kết quả
                        const resultsContainer = document.getElementById(`poll-results-${pollId}`);
                        if (resultsContainer) {
                            resultsContainer.classList.remove('hidden');
                        }
                        
                        // Cập nhật kết quả hiển thị
                        if (data.success) {
                            // Tải lại trang để cập nhật kết quả
                            window.location.reload();
                        }
                    })
                    .catch(error => {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalBtnText;
                        console.error('Error:', error);
                        alert(error.message);
                    });
                });
            });
        });
        
        // Hiển thị/ẩn kết quả khảo sát
        function togglePollResults(pollId) {
            const resultsContainer = document.getElementById(`poll-results-${pollId}`);
            if (resultsContainer) {
                resultsContainer.classList.toggle('hidden');
            }
        }
        
        // Hiển thị danh sách người đã bình chọn
        function showVoters(pollId) {
            // Hiển thị modal
            const modal = document.getElementById('votersModal');
            if (modal) {
                modal.classList.remove('hidden');
            }
            
            // Tải danh sách người bình chọn
            fetch(`/polls/${pollId}/voters`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Không thể tải danh sách người bình chọn');
                }
                return response.json();
            })
            .then(data => {
                const container = document.getElementById('votersContainer');
                
                // Nếu không có người bình chọn
                if (data.voters.length === 0) {
                    container.innerHTML = `
                        <div class="text-center py-4">
                            <p class="text-gray-600">Chưa có ai bình chọn cho khảo sát này</p>
                        </div>
                    `;
                    return;
                }
                
                // Hiển thị danh sách người bình chọn
                let html = '<div class="space-y-3">';
                
                data.voters.forEach(voter => {
                    html += `
                        <div class="flex items-center p-2 hover:bg-gray-50 rounded-lg">
                            <img src="${voter.photo || '/images/default-avatar.jpg'}" alt="${voter.name}" 
                                class="w-10 h-10 rounded-full object-cover mr-3">
                            <div>
                                <h4 class="font-medium text-gray-800">${voter.name}</h4>
                                <p class="text-xs text-gray-500">Đã bình chọn: ${voter.option_text}</p>
                                <p class="text-xs text-gray-500">${voter.voted_at}</p>
                            </div>
                        </div>
                    `;
                });
                
                html += '</div>';
                container.innerHTML = html;
            })
            .catch(error => {
                console.error('Error:', error);
                const container = document.getElementById('votersContainer');
                if (container) {
                    container.innerHTML = `
                        <div class="text-center py-4">
                            <p class="text-red-500">${error.message}</p>
                        </div>
                    `;
                }
            });
        }

        function closeVotersModal() {
            const modal = document.getElementById('votersModal');
            if (modal) {
                modal.classList.add('hidden');
            }
        }

        // Hàm hiển thị/ẩn form thay đổi bình chọn
        function toggleChangeVoteForm(pollId) {
            const resultsContainer = document.getElementById(`poll-results-${pollId}`);
            const changeForm = document.querySelector(`.poll-change-form[data-poll-id="${pollId}"]`);
            
            if (changeForm.classList.contains('hidden')) {
                // Hiển thị form thay đổi bình chọn
                changeForm.classList.remove('hidden');
                // Ẩn kết quả nếu đang hiển thị
                if (resultsContainer && !resultsContainer.classList.contains('hidden')) {
                    resultsContainer.classList.add('hidden');
                }
            } else {
                // Ẩn form thay đổi bình chọn
                changeForm.classList.add('hidden');
                // Hiển thị lại kết quả
                if (resultsContainer) {
                    resultsContainer.classList.remove('hidden');
                }
            }
        }

        // Xử lý form thay đổi bình chọn
        $(document).on('submit', '.poll-change-form', function(e) {
            e.preventDefault();
            
            var form = $(this);
            var pollId = form.data('poll-id');
            var selectedOption = form.find('input[name="option_index"]:checked');
            
            if (!selectedOption.length) {
                alert('Vui lòng chọn một lựa chọn');
                return;
            }
            
            var optionId = selectedOption.data('option-id');
            var optionIndex = selectedOption.val();
            
            // Hiển thị loading trên nút submit
            var submitBtn = form.find('button[type="submit"]');
            var originalText = submitBtn.html();
            submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Đang xử lý...');
            
            $.ajax({
                url: `/polls/${pollId}/change-vote`,
                type: 'POST',
                data: {
                    option_id: optionId,
                    option_index: optionIndex,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log("Change vote response:", response); // Debug: Log server response
                    
                    // Khôi phục nút submit
                    submitBtn.prop('disabled', false).html(originalText);
                    
                    if (response.success) {
                        // Nếu lựa chọn không thay đổi
                        if (response.no_change) {
                            alert(response.message);
                            return;
                        }
                        
                        // Ẩn form thay đổi bình chọn
                        form.addClass('hidden');
                        
                        // Hiển thị kết quả
                        var resultsContainer = $(`#poll-results-${pollId}`);
                        resultsContainer.removeClass('hidden');
                        
                        if (response.results && response.results.options) {
                            var html = '';
                            
                            // Xây dựng HTML cho kết quả khảo sát từ dữ liệu server
                            $.each(response.results.options, function(i, option) {
                                var votes = response.results.counts[i];
                                var percentage = response.results.percentages[i];
                                var isUserVote = (i == optionIndex || response.voted_option_id == optionId);
                                
                                html += `
                                    <div class="mb-3">
                                        <div class="flex justify-between mb-1">
                                            <span class="text-sm font-medium">${option}</span>
                                            <span class="text-sm text-gray-500">
                                                ${percentage}% (${votes} phiếu)
                                            </span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: ${percentage}%"></div>
                                        </div>
                                        ${isUserVote ? '<div class="text-end"><small class="text-muted"><i class="fas fa-check-circle text-success"></i> Lựa chọn của bạn</small></div>' : ''}
                                    </div>
                                `;
                            });
                            
                            html += `<div class="text-sm text-gray-500 mt-2">Tổng số phiếu: ${response.results.total_votes}</div>`;
                            
                            resultsContainer.html(html);
                        } else {
                            // Nếu không có dữ liệu chi tiết, tải lại trang
                            location.reload();
                            return;
                        }
                        
                        // Hiển thị thông báo thành công
                        alert('Bình chọn của bạn đã được cập nhật thành công!');
                    } else {
                        alert(response.message || 'Có lỗi xảy ra khi thay đổi bình chọn');
                    }
                },
                error: function(xhr) {
                    console.error("Change vote AJAX error:", xhr); // Debug: Log error
                    
                    submitBtn.prop('disabled', false).html(originalText);
                    var errorMsg = 'Đã xảy ra lỗi khi gửi yêu cầu';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    alert(errorMsg);
                }
            });
        });

        // Toggle bookmark và cập nhật UI
        function toggleBookmark(postId, itemCode) {
            fetch('{{ route('front.tblog.bookmark') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    item_id: postId,
                    item_code: itemCode
                })
            })
            .then(response => response.json())
            .then(data => {
                // Cập nhật UI
                const bookmarkBtn = document.getElementById(`bookmark-btn-${postId}`);
                const bookmarkIcon = bookmarkBtn.querySelector('i');
                
                if (data.status === 'added') {
                    bookmarkBtn.classList.add('text-red-500');
                    bookmarkIcon.classList.remove('far');
                    bookmarkIcon.classList.add('fas');
                } else {
                    bookmarkBtn.classList.remove('text-red-500');
                    bookmarkIcon.classList.remove('fas');
                    bookmarkIcon.classList.add('far');
                }
            })
            .catch(error => {
                console.error('Lỗi:', error);
                alert('Đã xảy ra lỗi khi yêu thích bài viết. Vui lòng thử lại sau.');
            });
        }
    </script>
    
@endsection
