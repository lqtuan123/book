{{-- group --}}
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nhóm học tập | ReadSocial</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        .group-banner {
            height: 300px;
            background-size: cover;
            background-position: center;
        }
        .group-avatar {
            margin-top: -75px;
            border: 5px solid white;
        }
        .mobile-sidebar-toggle {
            display: none;
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
            .group-avatar {
                margin-top: -50px;
                width: 100px;
                height: 100px;
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
</head>
<body class="bg-gray-50 font-sans">
    <!-- Header Section -->
    <header class="bg-white shadow-sm sticky top-0 z-40">
        <div class="container mx-auto px-4 py-3 flex items-center justify-between">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="#" class="text-2xl font-bold text-blue-600 flex items-center">
                    <i class="fas fa-book-open mr-2"></i>
                    <span>ReadSocial</span>
                </a>
            </div>

            <!-- Search Bar (Center) -->
            <div class="hidden md:flex mx-4 flex-1 max-w-xl">
                <div class="relative w-full flex items-center rounded-full border border-gray-300 bg-gray-50 px-4 py-2">
                    <i class="fas fa-search text-gray-400 mr-2"></i>
                    <input type="text" placeholder="Tìm kiếm bài viết..." class="bg-transparent w-full focus:outline-none">
                    <button class="ml-2 bg-blue-500 text-white px-4 py-1 rounded-full text-sm hover:bg-blue-600 transition">Tìm</button>
                </div>
            </div>

            <!-- Navigation Menu -->
            <nav class="hidden md:flex space-x-6">
                <a href="#" class="text-gray-700 hover:text-blue-600 font-medium">Trang chủ</a>
                <a href="#" class="text-gray-700 hover:text-blue-600 font-medium">Blog</a>
                <a href="#" class="text-blue-600 font-medium">Nhóm học tập</a>
                <a href="#" class="text-gray-700 hover:text-blue-600 font-medium">Thư viện</a>
            </nav>

            <!-- User Area -->
            <div class="flex items-center">
                <!-- Mobile Menu Button -->
                <button id="mobile-menu-button" class="md:hidden text-gray-600 mr-4">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                
                <!-- Search Icon for Mobile -->
                <button id="mobile-search-button" class="md:hidden text-gray-600 mr-4">
                    <i class="fas fa-search text-xl"></i>
                </button>
                
                <!-- User Avatar with Dropdown -->
                <div class="dropdown relative">
                    <button id="user-menu-button" class="flex items-center space-x-2 focus:outline-none">
                        <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="User" class="w-10 h-10 rounded-full object-cover border-2 border-blue-500">
                    </button>
                    
                    <div id="user-dropdown" class="dropdown-menu absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Trang cá nhân</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Bài viết của tôi</a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Cài đặt</a>
                        <div class="border-t border-gray-200"></div>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Đăng xuất</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="mobile-menu flex-col">
            <div class="relative w-full flex items-center rounded-full border border-gray-300 bg-gray-50 px-4 py-2 mb-4">
                <i class="fas fa-search text-gray-400 mr-2"></i>
                <input type="text" placeholder="Tìm kiếm..." class="bg-transparent w-full focus:outline-none">
            </div>
            <a href="#" class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded">Trang chủ</a>
            <a href="#" class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded">Blog</a>
            <a href="#" class="px-4 py-2 text-blue-600 font-medium rounded">Nhóm học tập</a>
            <a href="#" class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded">Thư viện</a>
            <div class="border-t border-gray-200 my-2"></div>
            <a href="#" class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded">Trang cá nhân</a>
            <a href="#" class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded">Bài viết của tôi</a>
            <a href="#" class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded">Cài đặt</a>
            <a href="#" class="px-4 py-2 text-gray-700 hover:bg-gray-100 rounded">Đăng xuất</a>
        </div>

        <!-- Mobile Search Bar -->
        <div id="mobile-search-bar" class="mobile-menu flex-col hidden">
            <div class="relative w-full flex items-center rounded-full border border-gray-300 bg-gray-50 px-4 py-2 mb-4">
                <i class="fas fa-search text-gray-400 mr-2"></i>
                <input type="text" placeholder="Tìm kiếm bài viết..." class="bg-transparent w-full focus:outline-none">
                <button class="ml-2 bg-blue-500 text-white px-4 py-1 rounded-full text-sm hover:bg-blue-600 transition">Tìm</button>
            </div>
        </div>
    </header>

    <!-- Group Banner Section -->
    <section class="group-banner bg-gradient-to-r from-blue-500 to-blue-600 relative">
        <div class="absolute inset-0 bg-black bg-opacity-30"></div>
        <div class="container mx-auto px-4 relative z-10 h-full flex items-end pb-8">
            <div class="flex flex-col md:flex-row items-start md:items-end w-full">
                <div class="flex items-end">
                    <img src="https://randomuser.me/api/portraits/lego/1.jpg" alt="Group Avatar" class="group-avatar w-32 h-32 rounded-full object-cover bg-white mr-4">
                    <div class="mb-4">
                        <div class="flex items-center">
                            <h1 class="text-3xl font-bold text-white">Nhóm Lập Trình Viên</h1>
                            <span class="ml-3 bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Public</span>
                        </div>
                        <p class="text-white mt-2">Nơi chia sẻ kiến thức, kinh nghiệm và hỗ trợ nhau trong lĩnh vực lập trình</p>
                        <div class="flex items-center text-white text-sm mt-2">
                            <span class="flex items-center mr-4">
                                <i class="fas fa-users mr-1"></i> 1,245 thành viên
                            </span>
                            <span class="flex items-center mr-4">
                                <i class="fas fa-newspaper mr-1"></i> 568 bài viết
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-calendar-alt mr-1"></i> Thành lập 15/03/2020
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Group Actions (Desktop) -->
                <div class="group-actions-desktop ml-auto space-x-3 mb-4">
                    <button class="bg-white text-blue-600 px-4 py-2 rounded-md flex items-center font-medium hover:bg-gray-100">
                        <i class="fas fa-plus mr-2"></i> Đăng bài mới
                    </button>
                    <button class="bg-red-500 text-white px-4 py-2 rounded-md flex items-center font-medium hover:bg-red-600">
                        <i class="fas fa-sign-out-alt mr-2"></i> Rời nhóm
                    </button>
                    <button class="bg-gray-200 text-gray-800 px-4 py-2 rounded-md flex items-center font-medium hover:bg-gray-300">
                        <i class="fas fa-cog mr-2"></i> Chỉnh sửa nhóm
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Group Actions (Mobile) -->
    <div class="group-actions-mobile bg-white shadow-sm py-2 px-4">
        <div class="flex justify-between">
            <button class="text-blue-600 flex flex-col items-center text-xs">
                <i class="fas fa-plus text-lg mb-1"></i>
                <span>Đăng bài</span>
            </button>
            <button class="text-red-500 flex flex-col items-center text-xs">
                <i class="fas fa-sign-out-alt text-lg mb-1"></i>
                <span>Rời nhóm</span>
            </button>
            <button class="text-gray-600 flex flex-col items-center text-xs">
                <i class="fas fa-cog text-lg mb-1"></i>
                <span>Chỉnh sửa</span>
            </button>
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
                <button id="sidebar-toggle" class="lg:hidden w-full bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-md mb-4 flex items-center justify-between">
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
                        Nhóm dành cho các lập trình viên chia sẻ kiến thức, kinh nghiệm làm việc, hỏi đáp và học hỏi lẫn nhau. 
                        Chúng tôi tổ chức các buổi meetup hàng tháng và thường xuyên chia sẻ tài liệu học tập hữu ích.
                    </p>
                    <div class="mt-3">
                        <div class="flex items-center text-gray-600 text-sm mb-1">
                            <i class="fas fa-user-shield mr-2"></i>
                            <span>Quản trị viên: <a href="#" class="text-blue-500 hover:underline">Nguyễn Văn A</a></span>
                        </div>
                        <div class="flex items-center text-gray-600 text-sm">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            <span>Ngày tạo: 15/03/2020</span>
                        </div>
                    </div>
                </div>
                
                <!-- Group Members -->
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-3">
                        <h3 class="font-bold text-gray-800 flex items-center">
                            <i class="fas fa-users mr-2 text-blue-500"></i>
                            Thành viên (1,245)
                        </h3>
                        <a href="#" class="text-blue-500 text-sm hover:underline">Xem tất cả</a>
                    </div>
                    
                    <div class="grid grid-cols-3 gap-3">
                        <a href="#" class="flex flex-col items-center">
                            <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Member" class="w-12 h-12 rounded-full object-cover mb-1">
                            <span class="text-xs text-gray-700 text-center truncate w-full">Nguyễn Văn A</span>
                        </a>
                        <a href="#" class="flex flex-col items-center">
                            <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Member" class="w-12 h-12 rounded-full object-cover mb-1">
                            <span class="text-xs text-gray-700 text-center truncate w-full">Trần Thị B</span>
                        </a>
                        <a href="#" class="flex flex-col items-center">
                            <img src="https://randomuser.me/api/portraits/men/75.jpg" alt="Member" class="w-12 h-12 rounded-full object-cover mb-1">
                            <span class="text-xs text-gray-700 text-center truncate w-full">Lê Văn C</span>
                        </a>
                        <a href="#" class="flex flex-col items-center">
                            <img src="https://randomuser.me/api/portraits/women/22.jpg" alt="Member" class="w-12 h-12 rounded-full object-cover mb-1">
                            <span class="text-xs text-gray-700 text-center truncate w-full">Phạm Thị D</span>
                        </a>
                        <a href="#" class="flex flex-col items-center">
                            <img src="https://randomuser.me/api/portraits/men/55.jpg" alt="Member" class="w-12 h-12 rounded-full object-cover mb-1">
                            <span class="text-xs text-gray-700 text-center truncate w-full">Hoàng Văn E</span>
                        </a>
                        <a href="#" class="flex flex-col items-center">
                            <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="Member" class="w-12 h-12 rounded-full object-cover mb-1">
                            <span class="text-xs text-gray-700 text-center truncate w-full">Võ Thị F</span>
                        </a>
                    </div>
                </div>
                
                <!-- Member Requests (for private groups) -->
                <div id="member-requests" class="mb-6">
                    <div class="flex justify-between items-center mb-3">
                        <h3 class="font-bold text-gray-800 flex items-center">
                            <i class="fas fa-user-clock mr-2 text-blue-500"></i>
                            Yêu cầu tham gia (5)
                        </h3>
                        <a href="#" class="text-blue-500 text-sm hover:underline">Xem tất cả</a>
                    </div>
                    
                    <div class="space-y-2">
                        <div class="member-request-item flex items-center justify-between p-2 rounded hover:bg-gray-50">
                            <div class="flex items-center">
                                <img src="https://randomuser.me/api/portraits/men/11.jpg" alt="Request" class="w-8 h-8 rounded-full object-cover mr-2">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-800">Nguyễn Văn X</h4>
                                    <p class="text-xs text-gray-500">2 giờ trước</p>
                                </div>
                            </div>
                            <div class="flex space-x-1">
                                <button class="text-xs bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button class="text-xs bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="member-request-item flex items-center justify-between p-2 rounded hover:bg-gray-50">
                            <div class="flex items-center">
                                <img src="https://randomuser.me/api/portraits/women/33.jpg" alt="Request" class="w-8 h-8 rounded-full object-cover mr-2">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-800">Trần Thị Y</h4>
                                    <p class="text-xs text-gray-500">5 giờ trước</p>
                                </div>
                            </div>
                            <div class="flex space-x-1">
                                <button class="text-xs bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button class="text-xs bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
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
            <!-- Search and Filter -->
            <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div class="relative w-full mb-4 md:mb-0">
                        <div class="relative flex items-center rounded-full border border-gray-300 bg-gray-50 px-4 py-2">
                            <i class="fas fa-search text-gray-400 mr-2"></i>
                            <input type="text" placeholder="Tìm kiếm bài viết trong nhóm..." class="bg-transparent w-full focus:outline-none">
                            <button class="ml-2 bg-blue-500 text-white px-4 py-1 rounded-full text-sm hover:bg-blue-600 transition">Tìm</button>
                        </div>
                    </div>
                    
                    <div class="flex items-center">
                        <span class="text-gray-600 mr-2 text-sm hidden md:block">Sắp xếp:</span>
                        <select class="bg-gray-50 border border-gray-300 text-gray-700 text-sm rounded-md px-3 py-1 focus:outline-none">
                            <option>Mới nhất</option>
                            <option>Cũ nhất</option>
                            <option>Nhiều tương tác nhất</option>
                            <option>Nhiều bình luận nhất</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Create Post -->
            <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
                <div class="flex items-start">
                    <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="User" class="w-10 h-10 rounded-full object-cover mr-3">
                    <div class="flex-1">
                        <input type="text" placeholder="Bạn muốn chia sẻ điều gì với nhóm?" class="w-full bg-gray-100 rounded-full px-4 py-2 text-sm focus:outline-none mb-3">
                        <div class="flex justify-between">
                            <div class="flex space-x-3">
                                <button class="flex items-center text-gray-500 hover:bg-gray-100 px-3 py-1 rounded">
                                    <i class="fas fa-image text-green-500 mr-1"></i>
                                    <span class="text-sm">Ảnh/Video</span>
                                </button>
                                <button class="flex items-center text-gray-500 hover:bg-gray-100 px-3 py-1 rounded">
                                    <i class="fas fa-link text-blue-500 mr-1"></i>
                                    <span class="text-sm">Link</span>
                                </button>
                                <button class="flex items-center text-gray-500 hover:bg-gray-100 px-3 py-1 rounded">
                                    <i class="fas fa-poll text-yellow-500 mr-1"></i>
                                    <span class="text-sm">Khảo sát</span>
                                </button>
                            </div>
                            <button class="bg-blue-500 text-white px-4 py-1 rounded text-sm hover:bg-blue-600">
                                Đăng
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Post Feed -->
            <section class="mb-8">
                <!-- Loading Spinner -->
                <div id="loading-spinner" class="loading-spinner flex justify-center my-8">
                    <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
                </div>
                
                <!-- Post Feed -->
                <div id="post-feed" class="space-y-6">
                    <!-- Post 1 -->
                    <div class="post-card bg-white rounded-lg overflow-hidden shadow-sm transition cursor-pointer p-4">
                        <div class="flex items-start mb-4">
                            <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="User" class="w-10 h-10 rounded-full object-cover mr-3">
                            <div>
                                <h3 class="font-medium text-gray-800">Nguyễn Văn A</h3>
                                <p class="text-xs text-gray-500">2 giờ trước · <i class="fas fa-users text-xs"></i> Nhóm Lập trình viên</p>
                            </div>
                            <div class="ml-auto relative">
                                <button class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-ellipsis-h"></i>
                                </button>
                                <div class="dropdown-menu absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Lưu bài viết</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Báo cáo</a>
                                    <div class="border-t border-gray-200"></div>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Chỉnh sửa</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Xóa</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <p class="text-gray-800 mb-3">Hôm nay mình muốn chia sẻ về cuốn sách "Clean Code" của Robert Martin. Đây là cuốn sách cực kỳ hữu ích cho các lập trình viên muốn nâng cao kỹ năng viết code sạch và dễ bảo trì.</p>
                            <div class="flex flex-wrap gap-2 mb-3">
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">#Sách</span>
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">#Lập_trình</span>
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">#Clean_Code</span>
                            </div>
                            <img src="https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1074&q=80" 
                                 alt="Post image" 
                                 class="w-full h-auto rounded-lg">
                        </div>
                        
                        <div class="flex items-center justify-between text-gray-500 border-t border-b border-gray-100 py-2 mb-3">
                            <div class="flex items-center">
                                <div class="flex items-center">
                                    <i class="fas fa-thumbs-up text-blue-500 mr-1"></i>
                                    <span class="text-xs">42</span>
                                </div>
                                <div class="flex items-center ml-4">
                                    <i class="fas fa-comment text-gray-400 mr-1"></i>
                                    <span class="text-xs">8</span>
                                </div>
                            </div>
                            <div class="text-xs">12 lượt chia sẻ</div>
                        </div>
                        
                        <div class="flex justify-between border-b border-gray-100 pb-3 mb-3">
                            <button class="flex items-center justify-center w-1/3 py-1 text-gray-500 hover:bg-gray-100 rounded">
                                <i class="far fa-thumbs-up mr-2"></i> Thích
                            </button>
                            <button class="flex items-center justify-center w-1/3 py-1 text-gray-500 hover:bg-gray-100 rounded">
                                <i class="far fa-comment mr-2"></i> Bình luận
                            </button>
                            <button class="flex items-center justify-center w-1/3 py-1 text-gray-500 hover:bg-gray-100 rounded">
                                <i class="fas fa-share mr-2"></i> Chia sẻ
                            </button>
                        </div>
                        
                        <div class="flex items-center">
                            <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="User" class="w-8 h-8 rounded-full object-cover mr-2">
                            <div class="relative flex-1">
                                <input type="text" placeholder="Viết bình luận..." class="comment-input w-full bg-gray-100 rounded-full px-4 py-2 text-sm focus:outline-none">
                                <div class="absolute right-3 top-1/2 transform -translate-y-1/2 flex space-x-1">
                                    <button class="text-gray-400 hover:text-gray-600">
                                        <i class="far fa-smile"></i>
                                    </button>
                                    <button class="text-gray-400 hover:text-gray-600">
                                        <i class="fas fa-camera"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Post 2 - Video Post -->
                    <div class="post-card bg-white rounded-lg overflow-hidden shadow-sm transition cursor-pointer p-4">
                        <div class="flex items-start mb-4">
                            <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="User" class="w-10 h-10 rounded-full object-cover mr-3">
                            <div>
                                <h3 class="font-medium text-gray-800">Trần Thị B</h3>
                                <p class="text-xs text-gray-500">5 giờ trước · <i class="fas fa-users text-xs"></i> Nhóm Lập trình viên</p>
                            </div>
                            <div class="ml-auto relative">
                                <button class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-ellipsis-h"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <p class="text-gray-800 mb-3">Mình vừa làm xong video hướng dẫn về React Hooks cho người mới bắt đầu. Hy vọng sẽ giúp ích cho các bạn đang học React!</p>
                            <div class="flex flex-wrap gap-2 mb-3">
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">#Lập_trình</span>
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">#ReactJS</span>
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">#Frontend</span>
                            </div>
                            <div class="relative w-full h-0 pb-[56.25%] bg-gray-200 rounded-lg overflow-hidden">
                                <img src="https://images.unsplash.com/photo-1633356122544-f134324a6cee?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80" 
                                     alt="Video thumbnail" 
                                     class="absolute w-full h-full object-cover">
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <button class="bg-white bg-opacity-80 rounded-full p-3">
                                        <i class="fas fa-play text-blue-500 text-xl"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between text-gray-500 border-t border-b border-gray-100 py-2 mb-3">
                            <div class="flex items-center">
                                <div class="flex items-center">
                                    <i class="fas fa-thumbs-up text-blue-500 mr-1"></i>
                                    <span class="text-xs">28</span>
                                </div>
                                <div class="flex items-center ml-4">
                                    <i class="fas fa-comment text-gray-400 mr-1"></i>
                                    <span class="text-xs">5</span>
                                </div>
                            </div>
                            <div class="text-xs">3 lượt chia sẻ</div>
                        </div>
                        
                        <div class="flex justify-between border-b border-gray-100 pb-3 mb-3">
                            <button class="flex items-center justify-center w-1/3 py-1 text-gray-500 hover:bg-gray-100 rounded">
                                <i class="far fa-thumbs-up mr-2"></i> Thích
                            </button>
                            <button class="flex items-center justify-center w-1/3 py-1 text-gray-500 hover:bg-gray-100 rounded">
                                <i class="far fa-comment mr-2"></i> Bình luận
                            </button>
                            <button class="flex items-center justify-center w-1/3 py-1 text-gray-500 hover:bg-gray-100 rounded">
                                <i class="fas fa-share mr-2"></i> Chia sẻ
                            </button>
                        </div>
                        
                        <div class="flex items-center">
                            <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="User" class="w-8 h-8 rounded-full object-cover mr-2">
                            <div class="relative flex-1">
                                <input type="text" placeholder="Viết bình luận..." class="comment-input w-full bg-gray-100 rounded-full px-4 py-2 text-sm focus:outline-none">
                                <div class="absolute right-3 top-1/2 transform -translate-y-1/2 flex space-x-1">
                                    <button class="text-gray-400 hover:text-gray-600">
                                        <i class="far fa-smile"></i>
                                    </button>
                                    <button class="text-gray-400 hover:text-gray-600">
                                        <i class="fas fa-camera"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Post 3 - Poll Post -->
                    <div class="post-card bg-white rounded-lg overflow-hidden shadow-sm transition cursor-pointer p-4">
                        <div class="flex items-start mb-4">
                            <img src="https://randomuser.me/api/portraits/men/75.jpg" alt="User" class="w-10 h-10 rounded-full object-cover mr-3">
                            <div>
                                <h3 class="font-medium text-gray-800">Lê Văn C</h3>
                                <p class="text-xs text-gray-500">Hôm qua · <i class="fas fa-users text-xs"></i> Nhóm Lập trình viên</p>
                            </div>
                            <div class="ml-auto relative">
                                <button class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-ellipsis-h"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <p class="text-gray-800 mb-3">Mọi người cho mình hỏi nên học ngôn ngữ lập trình nào trong năm nay để có cơ hội việc làm tốt nhất?</p>
                            <div class="flex flex-wrap gap-2 mb-3">
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">#Khảo_sát</span>
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">#Lập_trình</span>
                            </div>
                            
                            <div class="bg-gray-50 rounded-lg p-3">
                                <div class="mb-2">
                                    <div class="flex justify-between mb-1">
                                        <span class="text-sm font-medium">JavaScript</span>
                                        <span class="text-sm text-gray-500">45% (45 votes)</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-500 h-2 rounded-full" style="width: 45%"></div>
                                    </div>
                                </div>
                                
                                <div class="mb-2">
                                    <div class="flex justify-between mb-1">
                                        <span class="text-sm font-medium">Python</span>
                                        <span class="text-sm text-gray-500">30% (30 votes)</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-green-500 h-2 rounded-full" style="width: 30%"></div>
                                    </div>
                                </div>
                                
                                <div class="mb-2">
                                    <div class="flex justify-between mb-1">
                                        <span class="text-sm font-medium">Java</span>
                                        <span class="text-sm text-gray-500">15% (15 votes)</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-yellow-500 h-2 rounded-full" style="width: 15%"></div>
                                    </div>
                                </div>
                                
                                <div class="mb-2">
                                    <div class="flex justify-between mb-1">
                                        <span class="text-sm font-medium">Go</span>
                                        <span class="text-sm text-gray-500">10% (10 votes)</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-red-500 h-2 rounded-full" style="width: 10%"></div>
                                    </div>
                                </div>
                                
                                <div class="text-xs text-gray-500 mt-2">Tổng cộng 100 votes · Kết thúc trong 2 ngày</div>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between text-gray-500 border-t border-b border-gray-100 py-2 mb-3">
                            <div class="flex items-center">
                                <div class="flex items-center">
                                    <i class="fas fa-thumbs-up text-blue-500 mr-1"></i>
                                    <span class="text-xs">35</span>
                                </div>
                                <div class="flex items-center ml-4">
                                    <i class="fas fa-comment text-gray-400 mr-1"></i>
                                    <span class="text-xs">7</span>
                                </div>
                            </div>
                            <div class="text-xs">5 lượt chia sẻ</div>
                        </div>
                        
                        <div class="flex justify-between border-b border-gray-100 pb-3 mb-3">
                            <button class="flex items-center justify-center w-1/3 py-1 text-gray-500 hover:bg-gray-100 rounded">
                                <i class="far fa-thumbs-up mr-2"></i> Thích
                            </button>
                            <button class="flex items-center justify-center w-1/3 py-1 text-gray-500 hover:bg-gray-100 rounded">
                                <i class="far fa-comment mr-2"></i> Bình luận
                            </button>
                            <button class="flex items-center justify-center w-1/3 py-1 text-gray-500 hover:bg-gray-100 rounded">
                                <i class="fas fa-share mr-2"></i> Chia sẻ
                            </button>
                        </div>
                        
                        <div class="flex items-center">
                            <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="User" class="w-8 h-8 rounded-full object-cover mr-2">
                            <div class="relative flex-1">
                                <input type="text" placeholder="Viết bình luận..." class="comment-input w-full bg-gray-100 rounded-full px-4 py-2 text-sm focus:outline-none">
                                <div class="absolute right-3 top-1/2 transform -translate-y-1/2 flex space-x-1">
                                    <button class="text-gray-400 hover:text-gray-600">
                                        <i class="far fa-smile"></i>
                                    </button>
                                    <button class="text-gray-400 hover:text-gray-600">
                                        <i class="fas fa-camera"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Private Group Notice -->
                    <div id="private-group-notice" class="hidden bg-white rounded-lg shadow-sm p-6 text-center">
                        <div class="text-blue-500 text-5xl mb-4">
                            <i class="fas fa-lock"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Đây là nhóm riêng tư</h3>
                        <p class="text-gray-600 mb-6">Bạn cần tham gia nhóm để xem nội dung này. Hãy gửi yêu cầu tham gia và chờ quản trị viên phê duyệt.</p>
                        <button class="bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600">
                            Gửi yêu cầu tham gia
                        </button>
                    </div>
                </div>
                
                <!-- Load More Button -->
                <div class="mt-8 text-center">
                    <button id="load-more" class="bg-white border border-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-50 transition">
                        <i class="fas fa-sync-alt mr-2"></i> Tải thêm bài viết
                    </button>
                </div>
            </section>
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
                        <!-- Top Post 1 -->
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-16 h-16 bg-gray-200 rounded overflow-hidden">
                                <img src="https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1074&q=80" 
                                     alt="Post image" 
                                     class="w-full h-full object-cover">
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-gray-800 truncate">Review sách Clean Code</h3>
                                <div class="flex items-center text-xs text-gray-500 mt-1">
                                    <i class="fas fa-thumbs-up text-blue-500 mr-1"></i>
                                    <span>42 likes</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Top Post 2 -->
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-16 h-16 bg-gray-200 rounded overflow-hidden">
                                <img src="https://images.unsplash.com/photo-1633356122544-f134324a6cee?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80" 
                                     alt="Post image" 
                                     class="w-full h-full object-cover">
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-gray-800 truncate">Hướng dẫn React Hooks</h3>
                                <div class="flex items-center text-xs text-gray-500 mt-1">
                                    <i class="fas fa-thumbs-up text-blue-500 mr-1"></i>
                                    <span>28 likes</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <a href="#" class="block text-center text-blue-500 text-sm mt-4 hover:text-blue-700">Xem tất cả</a>
                </div>
                
                <!-- Top Members -->
                <div class="mb-6">
                    <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-user-friends mr-2 text-purple-500"></i>
                        Thành viên tích cực
                    </h3>
                    
                    <div class="space-y-3">
                        <!-- Member 1 -->
                        <div class="flex items-center">
                            <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="User" class="w-10 h-10 rounded-full object-cover">
                            <div class="ml-3">
                                <h4 class="text-sm font-medium text-gray-800">Nguyễn Văn A</h4>
                                <p class="text-xs text-gray-500">120 bài viết</p>
                            </div>
                            <button class="ml-auto text-xs bg-blue-500 text-white px-3 py-1 rounded-full hover:bg-blue-600">Theo dõi</button>
                        </div>
                        
                        <!-- Member 2 -->
                        <div class="flex items-center">
                            <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="User" class="w-10 h-10 rounded-full object-cover">
                            <div class="ml-3">
                                <h4 class="text-sm font-medium text-gray-800">Trần Thị B</h4>
                                <p class="text-xs text-gray-500">85 bài viết</p>
                            </div>
                            <button class="ml-auto text-xs bg-blue-500 text-white px-3 py-1 rounded-full hover:bg-blue-600">Theo dõi</button>
                        </div>
                    </div>
                    
                    <a href="#" class="block text-center text-blue-500 text-sm mt-4 hover:text-blue-700">Xem tất cả</a>
                </div>
                
                <!-- Similar Groups -->
                <div class="mb-6">
                    <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-users mr-2 text-green-500"></i>
                        Nhóm tương tự
                    </h3>
                    
                    <div class="space-y-3">
                        <!-- Group 1 -->
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-code text-blue-500"></i>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-sm font-medium text-gray-800">Frontend Developers</h4>
                                <p class="text-xs text-gray-500">850 thành viên</p>
                            </div>
                            <button class="ml-auto text-xs bg-blue-500 text-white px-3 py-1 rounded-full hover:bg-blue-600">Tham gia</button>
                        </div>
                        
                        <!-- Group 2 -->
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-server text-purple-500"></i>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-sm font-medium text-gray-800">Backend Developers</h4>
                                <p class="text-xs text-gray-500">720 thành viên</p>
                            </div>
                            <button class="ml-auto text-xs bg-blue-500 text-white px-3 py-1 rounded-full hover:bg-blue-600">Tham gia</button>
                        </div>
                    </div>
                    
                    <a href="#" class="block text-center text-blue-500 text-sm mt-4 hover:text-blue-700">Xem thêm</a>
                </div>
                
                <!-- Group Tags -->
                <div>
                    <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-tags mr-2 text-blue-500"></i>
                        Chủ đề nhóm
                    </h3>
                    
                    <div class="flex flex-wrap gap-2">
                        <a href="#" class="tag bg-gray-100 hover:bg-blue-100 text-gray-800 px-3 py-1 rounded-full text-xs transition">Lập trình</a>
                        <a href="#" class="tag bg-gray-100 hover:bg-blue-100 text-gray-800 px-3 py-1 rounded-full text-xs transition">JavaScript</a>
                        <a href="#" class="tag bg-gray-100 hover:bg-blue-100 text-gray-800 px-3 py-1 rounded-full text-xs transition">React</a>
                        <a href="#" class="tag bg-gray-100 hover:bg-blue-100 text-gray-800 px-3 py-1 rounded-full text-xs transition">Node.js</a>
                        <a href="#" class="tag bg-gray-100 hover:bg-blue-100 text-gray-800 px-3 py-1 rounded-full text-xs transition">Clean Code</a>
                        <a href="#" class="tag bg-gray-100 hover:bg-blue-100 text-gray-800 px-3 py-1 rounded-full text-xs transition">Algorithm</a>
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
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-16 h-16 bg-gray-200 rounded overflow-hidden">
                                    <img src="https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1074&q=80" 
                                         alt="Post image" 
                                         class="w-full h-full object-cover">
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-gray-800 truncate">Review sách Clean Code</h3>
                                    <div class="flex items-center text-xs text-gray-500 mt-1">
                                        <i class="fas fa-thumbs-up text-blue-500 mr-1"></i>
                                        <span>42 likes</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Top Members Card -->
                    <div class="w-64 flex-shrink-0">
                        <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-user-friends mr-2 text-purple-500"></i>
                            Thành viên tích cực
                        </h3>
                        
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="User" class="w-10 h-10 rounded-full object-cover">
                                <div class="ml-3">
                                    <h4 class="text-sm font-medium text-gray-800">Nguyễn Văn A</h4>
                                    <p class="text-xs text-gray-500">120 bài viết</p>
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
                            <a href="#" class="tag bg-gray-100 hover:bg-blue-100 text-gray-800 px-3 py-1 rounded-full text-xs transition">Lập trình</a>
                            <a href="#" class="tag bg-gray-100 hover:bg-blue-100 text-gray-800 px-3 py-1 rounded-full text-xs transition">JavaScript</a>
                            <a href="#" class="tag bg-gray-100 hover:bg-blue-100 text-gray-800 px-3 py-1 rounded-full text-xs transition">React</a>
                        </div>
                    </div>
                </div>
            </div>
        </aside>
    </main>

    <!-- Quick View Modal -->
    <div id="quick-view-modal" class="quick-view-modal fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center p-4">
        <div class="bg-white rounded-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Xem nhanh bài viết</h3>
                    <button id="close-quick-view" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="flex flex-col">
                    <div class="flex items-start mb-4">
                        <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="User" class="w-10 h-10 rounded-full object-cover mr-3">
                        <div>
                            <h3 class="font-medium text-gray-800">Nguyễn Văn A</h3>
                            <p class="text-xs text-gray-500">2 giờ trước · <i class="fas fa-users text-xs"></i> Nhóm Lập trình viên</p>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <p class="text-gray-800 mb-3">Hôm nay mình muốn chia sẻ về cuốn sách "Clean Code" của Robert Martin. Đây là cuốn sách cực kỳ hữu ích cho các lập trình viên muốn nâng cao kỹ năng viết code sạch và dễ bảo trì.</p>
                        <div class="flex flex-wrap gap-2 mb-3">
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">#Sách</span>
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">#Lập_trình</span>
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">#Clean_Code</span>
                        </div>
                        <img src="https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1074&q=80" 
                             alt="Post image" 
                             class="w-full h-auto rounded-lg">
                    </div>
                    
                    <div class="flex items-center justify-between text-gray-500 border-t border-b border-gray-100 py-2 mb-3">
                        <div class="flex items-center">
                            <div class="flex items-center">
                                <i class="fas fa-thumbs-up text-blue-500 mr-1"></i>
                                <span class="text-xs">42</span>
                            </div>
                            <div class="flex items-center ml-4">
                                <i class="fas fa-comment text-gray-400 mr-1"></i>
                                <span class="text-xs">8</span>
                            </div>
                        </div>
                        <div class="text-xs">12 lượt chia sẻ</div>
                    </div>
                    
                    <div class="flex justify-between border-b border-gray-100 pb-3 mb-3">
                        <button class="flex items-center justify-center w-1/3 py-1 text-gray-500 hover:bg-gray-100 rounded">
                            <i class="far fa-thumbs-up mr-2"></i> Thích
                        </button>
                        <button class="flex items-center justify-center w-1/3 py-1 text-gray-500 hover:bg-gray-100 rounded">
                            <i class="far fa-comment mr-2"></i> Bình luận
                        </button>
                        <button class="flex items-center justify-center w-1/3 py-1 text-gray-500 hover:bg-gray-100 rounded">
                            <i class="fas fa-share mr-2"></i> Chia sẻ
                        </button>
                    </div>
                    
                    <div class="space-y-3 mb-4">
                        <!-- Comment 1 -->
                        <div class="flex items-start">
                            <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="User" class="w-8 h-8 rounded-full object-cover mr-2">
                            <div class="bg-gray-100 rounded-lg p-2 flex-1">
                                <div class="flex items-center">
                                    <span class="font-medium text-sm text-gray-800">Nguyễn Thị B</span>
                                    <span class="text-xs text-gray-500 ml-2">5 phút trước</span>
                                </div>
                                <p class="text-sm text-gray-700 mt-1">Bài review rất hay, mình cũng đang đọc cuốn này!</p>
                                <div class="flex items-center mt-1">
                                    <button class="text-xs text-gray-500 hover:text-gray-700">Thích</button>
                                    <button class="text-xs text-gray-500 hover:text-gray-700 ml-3">Phản hồi</button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Comment 2 -->
                        <div class="flex items-start">
                            <img src="https://randomuser.me/api/portraits/men/75.jpg" alt="User" class="w-8 h-8 rounded-full object-cover mr-2">
                            <div class="bg-gray-100 rounded-lg p-2 flex-1">
                                <div class="flex items-center">
                                    <span class="font-medium text-sm text-gray-800">Lê Văn C</span>
                                    <span class="text-xs text-gray-500 ml-2">2 phút trước</span>
                                </div>
                                <p class="text-sm text-gray-700 mt-1">Bạn có thể chia sẻ thêm về chương bạn thấy ấn tượng nhất không?</p>
                                <div class="flex items-center mt-1">
                                    <button class="text-xs text-gray-500 hover:text-gray-700">Thích</button>
                                    <button class="text-xs text-gray-500 hover:text-gray-700 ml-3">Phản hồi</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center">
                        <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="User" class="w-8 h-8 rounded-full object-cover mr-2">
                        <div class="relative flex-1">
                            <input type="text" placeholder="Viết bình luận..." class="comment-input w-full bg-gray-100 rounded-full px-4 py-2 text-sm focus:outline-none">
                            <div class="absolute right-3 top-1/2 transform -translate-y-1/2 flex space-x-1">
                                <button class="text-gray-400 hover:text-gray-600">
                                    <i class="far fa-smile"></i>
                                </button>
                                <button class="text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-camera"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scroll to Top Button -->
    <button id="scroll-to-top" class="fixed bottom-6 right-6 bg-blue-500 text-white w-12 h-12 rounded-full shadow-lg flex items-center justify-center opacity-0 invisible transition">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Footer Section -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Column 1: About -->
                <div>
                    <h3 class="text-xl font-bold mb-4">Về ReadSocial</h3>
                    <p class="text-gray-300 mb-4">ReadSocial là nền tảng kết hợp đọc sách và mạng xã hội học tập, giúp bạn tiếp cận tri thức và kết nối với cộng đồng người học.</p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-300 hover:text-white"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-gray-300 hover:text-white"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-gray-300 hover:text-white"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-gray-300 hover:text-white"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                
                <!-- Column 2: Policies -->
                <div>
                    <h3 class="text-xl font-bold mb-4">Chính sách</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-300 hover:text-white">Điều khoản sử dụng</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Chính sách bảo mật</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Chính sách cookie</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Quyền riêng tư</a></li>
                    </ul>
                </div>
                
                <!-- Column 3: Support -->
                <div>
                    <h3 class="text-xl font-bold mb-4">Hỗ trợ</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-300 hover:text-white">Trung tâm trợ giúp</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Liên hệ hỗ trợ</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Báo lỗi</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white">Câu hỏi thường gặp</a></li>
                    </ul>
                </div>
                
                <!-- Column 4: Contact -->
                <div>
                    <h3 class="text-xl font-bold mb-4">Liên hệ</h3>
                    <ul class="space-y-2">
                        <li class="flex items-center">
                            <i class="fas fa-map-marker-alt mr-2 text-gray-300"></i>
                            <span class="text-gray-300">123 Đường ABC, Quận 1, TP.HCM</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone-alt mr-2 text-gray-300"></i>
                            <span class="text-gray-300">+84 123 456 789</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-envelope mr-2 text-gray-300"></i>
                            <span class="text-gray-300">support@readsocial.vn</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>© 2023 ReadSocial. Tất cả quyền được bảo lưu.</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            const searchBar = document.getElementById('mobile-search-bar');
            menu.classList.toggle('active');
            searchBar.classList.add('hidden');
        });

        // Mobile search toggle
        document.getElementById('mobile-search-button').addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            const searchBar = document.getElementById('mobile-search-bar');
            menu.classList.add('hidden');
            searchBar.classList.toggle('hidden');
        });

        // User dropdown menu toggle
        document.getElementById('user-menu-button').addEventListener('click', function(e) {
            e.stopPropagation();
            const dropdown = document.getElementById('user-dropdown');
            dropdown.classList.toggle('active');
        });

        // Mobile group menu toggle
        document.getElementById('mobile-group-menu').addEventListener('click', function(e) {
            e.stopPropagation();
            const dropdown = document.getElementById('mobile-group-dropdown');
            dropdown.classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener
</html>