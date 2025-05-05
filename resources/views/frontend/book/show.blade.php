@extends('frontend.layouts.master')
@section('css')
    <style>
        /* Biến CSS để dễ dàng quản lý theme */
        :root {
            --color-primary: #3b82f6;
            --color-primary-hover: #2563eb;
            --color-secondary: #facc15;
            --color-gray-50: #f9fafb;
            --color-gray-100: #f3f4f6;
            --color-gray-200: #e5e7eb;
            --color-gray-300: #d1d5db;
            --color-gray-500: #6b7280;
            --color-gray-600: #4b5563;
            --color-gray-700: #374151;
            --color-gray-800: #1f2937;
            --color-red-500: #ef4444;
            --color-red-600: #dc2626;
            --color-green-500: #10b981;
            --color-yellow-100: #fef3c7;
            --color-yellow-800: #92400e;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --radius-sm: 0.25rem;
            --radius-md: 0.375rem;
            --radius-lg: 0.5rem;
            --radius-full: 9999px;
        }

        /* Layout và container */
        .book-header {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: center;
        }

        .book-cover {
            flex: 0 0 140px;
            height: 200px;
            object-fit: cover;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            transition: transform 0.3s;
        }

        .book-cover:hover {
            transform: scale(1.02);
        }

        .book-info {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .book-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-top: 1rem;
        }

        .book-actions a {
            font-size: 0.875rem;
            padding: 0.375rem 1rem;
            border-radius: var(--radius-md);
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .book-tag {
            padding: 0.25rem 0.75rem;
            border: 1px solid var(--color-gray-200);
            border-radius: var(--radius-full);
            font-size: 0.75rem;
            color: var(--color-gray-700);
            background-color: var(--color-gray-50);
            transition: background-color 0.2s;
        }

        .book-tag:hover {
            background-color: var(--color-gray-100);
        }

        /* PDF Viewer */
        .pdf-container {
            max-width: 800px;
            margin: 0 auto;
            border-radius: var(--radius-lg);
            overflow: hidden;
        }

        .pdf-container canvas {
            width: 100%;
            height: auto;
            margin-bottom: 0.5rem;
        }

        /* Stats boxes */
        .book-stat {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 0.25rem 0.5rem;
        }

        .book-stat-value {
            font-weight: 600;
            font-size: 1rem;
            color: var(--color-gray-800);
        }

        .book-stat-label {
            font-size: 0.75rem;
            color: var(--color-gray-500);
        }

        /* Rating system */
        .rating-container {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }

        .rating-stars {
            display: flex;
            margin-right: 0.5rem;
        }

        .rating-star {
            color: var(--color-gray-300);
            font-size: 1.5rem;
            cursor: pointer;
            transition: color 0.2s;
        }

        .rating-star.filled {
            color: var(--color-secondary);
        }

        .rating-star.half {
            position: relative;
        }

        .rating-star.half:after {
            content: '★';
            color: var(--color-secondary);
            position: absolute;
            left: 0;
            top: 0;
            width: 50%;
            overflow: hidden;
        }

        .rating-count {
            color: var(--color-gray-500);
            font-size: 0.875rem;
        }

        .rating-form {
            background-color: var(--color-gray-50);
            border-radius: var(--radius-lg);
            padding: 1rem;
            margin-top: 1rem;
            box-shadow: var(--shadow-sm);
            transition: box-shadow 0.2s;
        }

        .rating-form:hover {
            box-shadow: var(--shadow-md);
        }

        .rating-form input,
        .rating-form textarea {
            border: 1px solid var(--color-gray-200);
            border-radius: var(--radius-md);
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .rating-form input:focus,
        .rating-form textarea:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
            outline: none;
        }

        .rating-list {
            margin-top: 1.5rem;
        }

        .rating-item {
            border-bottom: 1px solid var(--color-gray-200);
            padding: 1rem 0;
            transition: background-color 0.2s;
        }

        .rating-item:hover {
            background-color: rgba(249, 250, 251, 0.5);
        }

        .rating-item:last-child {
            border-bottom: none;
        }

        .rating-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }

        .rating-user {
            font-weight: 500;
            color: var(--color-gray-800);
        }

        .rating-date {
            color: var(--color-gray-500);
            font-size: 0.875rem;
        }

        .rating-value {
            display: flex;
            margin-bottom: 0.5rem;
        }

        .rating-text {
            color: var(--color-gray-600);
            font-size: 0.9375rem;
            line-height: 1.5;
        }

        /* Rating statistics - phiên bản tối ưu hơn */
        .rating-stats {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .rating-average {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--color-gray-800);
            min-width: 80px;
            text-align: center;
            position: relative;
        }

        .rating-average::after {
            content: '/5';
            font-size: 1rem;
            color: var(--color-gray-500);
            position: absolute;
            bottom: 0.5rem;
            right: -10px;
        }

        .rating-distribution {
            flex-grow: 1;
            max-width: 400px;
        }

        .rating-bar {
            display: grid;
            grid-template-columns: 1.5rem 1fr auto auto;
            align-items: center;
            margin-bottom: 0.25rem;
            gap: 0.5rem;
        }

        .rating-bar-label {
            text-align: center;
            font-size: 0.75rem;
            color: var(--color-gray-500);
            font-weight: 500;
        }

        .rating-bar-track {
            background-color: var(--color-gray-200);
            height: 0.375rem;
            border-radius: var(--radius-full);
            overflow: hidden;
            position: relative;
        }

        .rating-bar-fill {
            background-color: var(--color-secondary);
            height: 100%;
            transition: width 0.5s ease-out;
            border-radius: var(--radius-full);
            position: relative;
        }

        .rating-bar-count {
            min-width: 1.5rem;
            text-align: right;
            font-size: 0.75rem;
            color: var(--color-gray-700);
            font-weight: 500;
        }

        .rating-bar-percent {
            min-width: 2.5rem;
            text-align: left;
            font-size: 0.75rem;
            color: var(--color-gray-500);
            transition: opacity 0.2s;
            opacity: 0.8;
        }

        /* Hiệu ứng khi hover vào rating bar */
        .rating-bar:hover .rating-bar-track {
            height: 0.5rem;
        }

        .rating-bar:hover .rating-bar-count {
            font-weight: 600;
            color: var(--color-gray-800);
        }

        .rating-bar:hover .rating-bar-percent {
            opacity: 1;
            font-weight: 500;
        }

        /* Thêm màu sắc khác nhau cho từng thanh */
        .rating-bar:nth-child(1) .rating-bar-fill {
            background-color: #f59e0b;
        }

        .rating-bar:nth-child(2) .rating-bar-fill {
            background-color: #fbbf24;
        }

        .rating-bar:nth-child(3) .rating-bar-fill {
            background-color: #fcd34d;
        }

        .rating-bar:nth-child(4) .rating-bar-fill {
            background-color: #fde68a;
        }

        .rating-bar:nth-child(5) .rating-bar-fill {
            background-color: #fef3c7;
        }

        /* Buttons and interactive elements */
        .star {
            cursor: pointer;
            font-size: 1.25rem;
            color: var(--color-gray-300);
            transition: color 0.2s ease;
        }

        .star.selected,
        .star.hover {
            color: var(--color-secondary);
        }

        button {
            cursor: pointer;
            transition: all 0.2s;
        }

        button:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        /* Toast notification */
        .toast-notification {
            opacity: 0;
            transform: translateY(-10px);
            transition: opacity 0.3s, transform 0.3s;
            box-shadow: var(--shadow-lg);
            z-index: 9999;
        }

        .toast-notification.show {
            opacity: 1;
            transform: translateY(0);
        }

        /* Pagination */
        .pagination-btn {
            transition: all 0.2s;
        }

        .pagination-btn:hover {
            transform: translateY(-1px);
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .book-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .book-cover {
                height: auto;
                width: 100%;
                max-width: 300px;
                margin: 0 auto;
            }

            .book-info {
                width: 100%;
                text-align: center;
            }

            .book-actions {
                flex-direction: column;
                width: 100%;
            }

            .book-actions a {
                width: 100%;
                justify-content: center;
            }

            .rating-stats {
                flex-direction: row;
                flex-wrap: wrap;
                justify-content: center;
            }

            .rating-distribution {
                width: 100%;
                max-width: 400px;
                margin: 1rem auto 0;
            }

            .rating-item {
                padding: 1rem 0.5rem;
            }
        }

        @media (max-width: 480px) {
            .rating-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .rating-date {
                margin-top: 0.25rem;
            }
        }

        /* Tab menu cho đánh giá và bình luận */
        .book-tabs {
            display: flex;
            border-bottom: 1px solid var(--color-gray-200);
            margin-bottom: 1.5rem;
        }

        .book-tab {
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            color: var(--color-gray-600);
            cursor: pointer;
            position: relative;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s;
        }

        .book-tab.active {
            color: var(--color-primary);
            font-weight: 600;
        }

        .book-tab .tab-icon {
            font-size: 1.25rem;
        }

        .book-tab .tab-count {
            color: var(--color-gray-500);
            font-size: 0.9rem;
        }

        .book-tab::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 3px;
            background-color: var(--color-primary);
            transition: width 0.3s;
        }

        .book-tab.active::after {
            width: 100%;
        }

        /* Tab content */
        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        /* Đánh giá form mới */
        .rating-form-header {
            font-size: 1.25rem;
            font-weight: 500;
            color: var(--color-gray-800);
            margin-bottom: 1.5rem;
        }

        .rating-form-label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--color-gray-700);
            font-weight: 500;
        }

        .rating-stars-input {
            display: flex;
            margin-bottom: 1.5rem;
        }

        .rating-stars-input .rating-star {
            font-size: 2rem;
            margin-right: 0.5rem;
        }

        .rating-form textarea {
            width: 100%;
            border: 1px solid var(--color-gray-300);
            border-radius: 0.5rem;
            padding: 1rem;
            font-size: 1rem;
            transition: border-color 0.2s, box-shadow 0.2s;
            min-height: 120px;
            resize: vertical;
            margin-bottom: 1rem;
        }

        .rating-form textarea:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
            outline: none;
        }

        .rating-submit {
            background-color: var(--color-primary);
            color: white;
            font-weight: 500;
            padding: 0.75rem 2rem;
            border-radius: 0.5rem;
            border: none;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .rating-submit:hover {
            background-color: var(--color-primary-hover);
        }

        /* Danh sách đánh giá mới */
        .rating-list {
            margin-top: 2rem;
        }

        .rating-item {
            border-bottom: 1px solid var(--color-gray-200);
            padding: 1.5rem 0;
        }

        .rating-item:last-child {
            border-bottom: none;
        }

        .rating-user-info {
            display: flex;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        .rating-user-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            overflow: hidden;
            margin-right: 1rem;
            flex-shrink: 0;
        }

        .rating-user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .rating-user-details {
            flex-grow: 1;
        }

        .rating-user-name {
            font-weight: 600;
            color: var(--color-gray-800);
            display: block;
            margin-bottom: 0.25rem;
        }

        .rating-date {
            color: var(--color-gray-500);
            font-size: 0.875rem;
        }

        .rating-stars-display {
            display: flex;
            margin-bottom: 0.5rem;
        }

        .rating-stars-display .rating-star {
            font-size: 1.25rem;
            color: var(--color-secondary);
            margin-right: 0.25rem;
        }

        .rating-content {
            color: var(--color-gray-700);
            line-height: 1.6;
            margin-bottom: 0.75rem;
        }

        .rating-actions {
            display: flex;
            align-items: center;
            margin-top: 0.5rem;
        }

        .rating-like {
            display: flex;
            align-items: center;
            color: var(--color-gray-600);
            font-size: 0.875rem;
            cursor: pointer;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            transition: background-color 0.2s;
        }

        .rating-like:hover {
            background-color: var(--color-gray-100);
        }

        .rating-like-icon {
            margin-right: 0.25rem;
        }

        .rating-like-count {
            margin-left: 0.25rem;
        }

        /* Phần bình luận mới */
        .comments-section {
            margin-top: 2rem;
        }

        .comment-form {
            margin-bottom: 2rem;
        }

        .comment-form textarea {
            width: 100%;
            border: 1px solid var(--color-gray-300);
            border-radius: 0.5rem;
            padding: 1rem;
            font-size: 1rem;
            transition: border-color 0.2s, box-shadow 0.2s;
            min-height: 100px;
            resize: vertical;
            margin-bottom: 1rem;
        }

        .comment-form textarea:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
            outline: none;
        }

        /* Book detail page - new design */
        .book-detail-container {
            display: flex;
            flex-direction: column;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .book-main-info {
            display: flex;
            gap: 2rem;
        }

        .book-cover-container {
            flex: 0 0 auto;
            max-width: 300px;
        }

        .book-cover-img {
            width: 100%;
            border-radius: 0.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            object-fit: cover;
            aspect-ratio: 3/4;
            transition: transform 0.3s ease;
        }

        .book-cover-img:hover {
            transform: scale(1.02);
        }

        .book-info-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }

        .book-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--color-gray-800);
            margin-bottom: 0.75rem;
            line-height: 1.3;
        }

        .book-rating-container {
            display: flex;
            align-items: center;
            margin-bottom: 1.25rem;
        }

        .book-rating-stars {
            display: flex;
            align-items: center;
            margin-right: 0.75rem;
        }

        .book-rating-text {
            font-weight: 500;
            color: var(--color-gray-700);
            font-size: 1rem;
        }

        .book-meta {
            margin-bottom: 1.5rem;
        }

        .book-meta-item {
            margin-bottom: 0.5rem;
            display: flex;
            align-items: flex-start;
        }

        .book-meta-label {
            font-weight: 600;
            color: var(--color-gray-700);
            margin-right: 0.5rem;
        }

        .book-meta-value {
            color: var(--color-gray-700);
        }

        .book-tag-list {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .book-description {
            margin-bottom: 1.5rem;
            line-height: 1.6;
            color: var(--color-gray-700);
        }

        .book-content-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--color-gray-800);
            margin-bottom: 1rem;
            margin-top: 1.5rem;
        }

        .book-content-list {
            list-style-type: disc;
            padding-left: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .book-content-item {
            margin-bottom: 0.75rem;
            color: var(--color-gray-700);
            line-height: 1.5;
        }

        .book-actions-container {
            display: flex;
            margin-top: 1rem;
            width: 100%;
            flex-wrap: nowrap;
            justify-content: space-between;
            gap: 0.5rem;
        }

        .book-action-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-weight: 500;
            font-size: 0.875rem;
            text-decoration: none;
            transition: all 0.2s;
            white-space: nowrap;
            flex: 1;
                text-align: center;
        }

        .btn-read {
            background-color: var(--color-primary);
            color: white;
        }

        .btn-read:hover {
            background-color: var(--color-primary-hover);
        }

        .btn-bookmark {
            background-color: var(--color-gray-100);
            color: var(--color-gray-700);
        }

        .btn-bookmark:hover {
            background-color: var(--color-gray-200);
        }

        .btn-bookmark.active {
            background-color: var(--color-primary);
            color: white;
        }

        .btn-icon {
            margin-right: 0.375rem;
            font-size: 1rem;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .book-main-info {
                flex-direction: column;
            }

            .book-cover-container {
                max-width: 100%;
                width: 100%;
                margin: 0 auto;
            }

            .book-cover-img {
                max-width: 250px;
                margin: 0 auto;
                display: block;
            }

            .book-title {
                font-size: 1.5rem;
                text-align: center;
            }

            .book-rating-container {
                justify-content: center;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container mx-auto px-4 py-6">
        <div class="book-detail-container">
            <!-- Book main info section -->
            <div class="book-main-info">
                <!-- Book cover -->
                <div class="book-cover-container">
                <?php $photos = explode(',', $book->photo); ?>
                    <img src="{{ $photos[0] }}" alt="{{ $book->title }}" class="book-cover-img">

                    <!-- Book actions buttons -->
                    <div class="book-actions-container mt-3">
                        <a href="{{ route('front.book.read', $book->id) }}" class="book-action-btn btn-read">
                            <span class="btn-icon">📖</span>Đọc
                        </a>
                        @if(isset($book->has_audio) && $book->has_audio)
                        <a href="{{ route('front.book.show', ['id' => $book->slug, 'format' => 'audio']) }}" class="book-action-btn btn-bookmark" id="share-btn"
                            data-book-url="{{ url()->current() }}">
                            <span class="btn-icon">🎧</span>Nghe
                        </a>
                        @endif
                        <a id="bookmark-btn"
                            class="book-action-btn btn-bookmark {{ \App\Modules\Tuongtac\Models\TRecommend::hasBookmarked($book->id, 'book') ? 'active' : '' }}"
                            data-id="{{ $book->id }}" data-code="book">
                            <span class="btn-icon">🤍</span>Thích
                        </a>
                        <a href="{{ route('front.tblogs.create') }}" class="book-action-btn btn-bookmark" id="share-btn"
                            data-book-url="{{ url()->current() }}">
                            <span class="btn-icon">🔗</span>Chia sẻ
                        </a>
                    </div>
                </div>

                <!-- Book info -->
                <div class="book-info-container">
                    <h1 class="book-title">{{ $book->title }}</h1>

                    <!-- Rating stars -->
                    <div class="book-rating-container">
                        <div class="book-rating-stars">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= floor($book->average_rating))
                                    <span class="rating-star filled">★</span>
                                @elseif ($i - 0.5 <= $book->average_rating)
                                    <span class="rating-star half">☆</span>
                                @else
                                    <span class="rating-star">☆</span>
                                @endif
                            @endfor
                        </div>
                        <span class="book-rating-text">
                            {{ number_format($book->average_rating, 1) }} ({{ $book->rating_count }} đánh giá)
                        </span>
                    </div>

                    <!-- Book metadata -->
                    <div class="book-meta">
                        <div class="book-meta-item">
                            <span class="book-meta-label">Thể loại:</span>
                            <span class="book-meta-value">{{ $book->bookType->title }}</span>
                        </div>

                        <div class="book-meta-item">
                            <span class="book-meta-label">Tags:</span>
                            <div class="book-tag-list">
                        @foreach ($tagNames as $tag)
                            <span class="book-tag">{{ $tag }}</span>
                        @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Book description -->
                    <h3 class="book-content-title">Giới thiệu sách</h3>
                    <div class="book-description">
                        <p>{!! nl2br(e($book->summary)) !!}</p>
                    </div>

                    <!-- Book main content bullet points -->
                    <h3 class="book-content-title">Nội dung chính</h3>
                    <ul class="book-content-list">
                        @php
                            // Tách nội dung chính từ $book->content thành danh sách bullet points
                            $contentLines = explode("\n", $book->content);
                            $bulletPoints = [];

                            foreach ($contentLines as $line) {
                                $line = trim($line);
                                if (!empty($line)) {
                                    $bulletPoints[] = $line;
                                }
                            }

                            // Giới hạn số điểm hiển thị
                            $bulletPoints = array_slice($bulletPoints, 0, 5);
                        @endphp

                        @foreach ($bulletPoints as $point)
                            <li class="book-content-item">{{ $point }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- ... existing content ... -->
        </div>

        <!-- Phần đánh giá và bình luận -->
        <div class="bg-white rounded-lg shadow-md p-6 mt-8" id="reviews-section">
            <!-- Tab Menu -->
            <div class="book-tabs">
                <div class="book-tab active" data-tab="ratings">
                    <span class="tab-icon">⭐</span> Đánh giá
                    <span class="tab-count" id="rating-count">
                        ({{ $book->rating_count ?? 0 }})
                    </span>
                </div>
                <div class="book-tab" data-tab="comments">
                    <span class="tab-icon">💬</span> Bình luận
                    <span class="tab-count" id="comment-count">
                        ({{ $book->comment_count ?? 0 }})
                    </span>
                </div>
                <!-- Thêm tab tài liệu -->
                @php
                    $hasResources = false;
                    $resourceCount = 0;
                    if (!empty($book->resources)) {
                        if (is_string($book->resources)) {
                            $resourcesData = json_decode($book->resources, true);
                            $resourceCount = !empty($resourcesData['resource_ids']) ? count($resourcesData['resource_ids']) : 0;
                            $hasResources = $resourceCount > 0;
                        } else if (is_array($book->resources) && isset($book->resources['resource_ids'])) {
                            $resourceCount = count($book->resources['resource_ids']);
                            $hasResources = $resourceCount > 0;
                        }
                    }
                @endphp
                @if($hasResources)
                <div class="book-tab" data-tab="resources">
                    <span class="tab-icon">📁</span> Tài liệu
                    <span class="tab-count" id="resources-count">
                        ({{ $resourceCount }})
                    </span>
                </div>
                @endif
            </div>

            <!-- Tab Content: Đánh giá -->
            <div class="tab-content active" id="ratings-content">
                <!-- Thống kê đánh giá -->
                <div class="rating-stats">
                    <div class="rating-average" id="rating-stats-average">{{ number_format($book->average_rating, 1) }}
                    </div>
                    <div class="rating-distribution" id="rating-distribution">
                        @php
                            // Lấy phân bố đánh giá từ controller
                            $ratingService = app(\App\Services\RatingService::class);
                            $stats = $ratingService->getRatingStats($book->id);
                        @endphp

                        @for ($i = 5; $i >= 1; $i--)
                            <div class="rating-bar">
                                <div class="rating-bar-label">{{ $i }}</div>
                                <div class="rating-bar-track">
                                    <div class="rating-bar-fill"
                                        style="width: {{ $book->rating_count > 0 ? ($stats['distribution'][$i] / $book->rating_count) * 100 : 0 }}%">
                                    </div>
                                </div>
                                <div class="rating-bar-count">{{ $stats['distribution'][$i] }}</div>
                                <div class="rating-bar-percent">
                                    {{ $book->rating_count > 0 ? number_format(($stats['distribution'][$i] / $book->rating_count) * 100, 0) : 0 }}%
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>

                <!-- Form đánh giá -->
                @auth
                    <div class="rating-form" id="rating-form">
                        <h4 class="rating-form-header">Viết đánh giá của bạn</h4>
                        <label for="rating-stars-input" class="rating-form-label">Đánh giá của bạn:</label>
                        <div class="rating-stars-input" id="rating-stars-input">
                            @for ($i = 1; $i <= 5; $i++)
                                <span class="rating-star cursor-pointer text-2xl" data-value="{{ $i }}">☆</span>
                            @endfor
                        </div>
                        <input type="hidden" id="rating-value" value="">
                        <label for="rating-comment" class="rating-form-label">Nội dung đánh giá:</label>
                        <textarea id="rating-comment" placeholder="Chia sẻ suy nghĩ của bạn về cuốn sách này..."></textarea>
                        <div id="rating-error" class="text-red-500 text-sm mb-2 hidden"></div>
                        <button id="submit-rating" class="rating-submit">Gửi đánh giá</button>
                        <button id="delete-rating"
                            class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 hidden ml-2">Xóa đánh
                            giá</button>
                    </div>
                @else
                    <div class="bg-gray-50 p-4 rounded-md text-center">
                        <p class="text-gray-600">Vui lòng <a href="{{ route('front.login') }}"
                                class="text-blue-600 hover:underline">đăng nhập</a> để đánh giá sách này.</p>
                    </div>
                @endauth

                <!-- Danh sách đánh giá -->
                <div class="rating-list" id="rating-list">
                    <!-- Đánh giá sẽ được tải bằng AJAX -->
                    <div id="user-rating-loading" class="text-center py-2 text-gray-500 hidden">
                        <p>Đang tải đánh giá của bạn...</p>
                    </div>
                    <div id="ratings-loading" class="text-center py-4 text-gray-500">
                        <p>Đang tải đánh giá...</p>
                </div>
                    <div id="ratings-container"></div>
                    <div id="ratings-pagination" class="mt-4"></div>
            </div>
        </div>

            <!-- Tab Content: Bình luận -->
            <div class="tab-content" id="comments-content">
                <div class="flex justify-between border-b border-gray-100 pb-3 mb-3">
                    
                    <button onclick="toggleCommentBox({{ $book->id }}, 'book')"
                        class="flex items-center justify-center w-1/3 py-1 text-gray-500 hover:bg-gray-100 rounded">
                        <i class="far fa-comment mr-2"></i> Bình luận
                    </button>
                    
        </div>

                <div class="flex items-center">
                    <img src="{{ auth()->user()->photo ?? 'https://randomuser.me/api/portraits/women/44.jpg' }}"
                        alt="User" class="w-8 h-8 rounded-full object-cover mr-2">
                    <div class="relative flex-1">
                        <input type="text" id="comment-input-{{ $book->id }}" placeholder="Viết bình luận..."
                            class="comment-input w-full bg-gray-100 rounded-full px-4 py-2 text-sm focus:outline-none">
                        <div class="absolute right-3 top-1/2 transform -translate-y-1/2 flex space-x-1">
                            <button class="text-gray-400 hover:text-gray-600 emoji-trigger"
                                onclick="addEmoji({{ $book->id }}, event, 'book')" data-item-id="{{ $book->id }}">
                                <i class="far fa-smile"></i>
                            </button>
                            <button class="text-gray-400 hover:text-gray-600"
                                onclick="submitComment({{ $book->id }}, 'book')">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
            </div>
        </div>

                <!-- Comment Box - This div will be shown/hidden with toggleCommentBox() -->
                <div id="comment-box-{{ $book->id }}" class="comment-box bg-white rounded-lg shadow-sm p-4 mt-3"
                    style="display: none;">
                    <div id="comments-container-{{ $book->id }}" class="space-y-3">
                        <!-- Comments will be loaded here dynamically -->
                        <div class="text-center text-gray-500 text-sm py-2">
                            <i class="fas fa-spinner fa-spin mr-2"></i> Đang tải bình luận...
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Content: Tài liệu -->
            @if($hasResources)
            <div class="tab-content" id="resources-content">
                <div class="resources-list">
                    <h3 class="text-lg font-medium mb-4">Tài liệu đính kèm</h3>
                    <div id="book-resources-container" class="grid gap-3">
                        <div class="text-center py-4">
                            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-purple-500"></div>
                            <p class="mt-2 text-gray-500">Đang tải danh sách tài liệu...</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://mozilla.github.io/pdf.js/build/pdf.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
                // Bookmark functionality
                const bookmarkBtn = document.getElementById("bookmark-btn");

                const handleBookmarkClick = function(event) {
                event.preventDefault();

                let itemId = this.getAttribute("data-id");
                let itemCode = this.getAttribute("data-code");

                    // Đổi trạng thái UI
                    bookmarkBtn.classList.toggle("active");

                fetch("{{ route('front.book.bookmark') }}", {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            item_id: itemId,
                            item_code: itemCode
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                            // Ensure UI matches server state
                            if (data.isBookmarked) {
                                bookmarkBtn.classList.add("active");
                        } else {
                                bookmarkBtn.classList.remove("active");
                        }
                    })
                    .catch(error => {
                        console.error("Lỗi:", error);
                        });
                };

                if (bookmarkBtn) {
                    bookmarkBtn.addEventListener("click", handleBookmarkClick);
                }

                // Tab switching functionality
                const tabs = document.querySelectorAll('.book-tab');
                const tabContents = document.querySelectorAll('.tab-content');

                tabs.forEach(tab => {
                    tab.addEventListener('click', () => {
                        const tabId = tab.getAttribute('data-tab');

                        // Remove active class from all tabs and contents
                        tabs.forEach(t => t.classList.remove('active'));
                        tabContents.forEach(c => c.classList.remove('active'));

                        // Add active class to clicked tab and corresponding content
                        tab.classList.add('active');
                        document.getElementById(`${tabId}-content`).classList.add('active');
            });
        });

                // Rating functionality
                const bookId = {{ $book->id }};
                let currentPage = 1;
                let hasMoreRatings = true;
                let userRatingId = null;

                // Đảm bảo URL không có tham số thừa
                const baseUrl = "{{ url('/') }}";

                // Định nghĩa biến xác định trạng thái đăng nhập
                const isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};

                // Load user's existing rating if logged in
                @auth
                loadUserRating();
            @endauth

            // Load initial ratings
            loadRatings();

            // Set up rating stars interaction
            const ratingStarsInput = document.getElementById('rating-stars-input');
            if (ratingStarsInput) {
                const stars = ratingStarsInput.querySelectorAll('.rating-star');

                stars.forEach((star, index) => {
                    // Hover effect
                    star.addEventListener('mouseover', () => {
                        for (let i = 0; i <= index; i++) {
                            stars[i].textContent = '★';
                            stars[i].classList.add('filled');
                        }
                        for (let i = index + 1; i < stars.length; i++) {
                            stars[i].textContent = '☆';
                            stars[i].classList.remove('filled');
                        }
                    });

                    // Click to select
                    star.addEventListener('click', () => {
                        // Đặt lại tất cả các sao
                        stars.forEach(s => {
                            s.classList.remove('selected');
                        });
                        // Đánh dấu sao được chọn
                        for (let i = 0; i <= index; i++) {
                            stars[i].classList.add('selected');
                        }
                        // Lưu giá trị rating
                        document.getElementById('rating-value').value = index + 1;
                    });
                });

                // Reset on mouseout if no selection
                ratingStarsInput.addEventListener('mouseout', () => {
                    stars.forEach((star, index) => {
                        if (!star.classList.contains('selected')) {
                            star.textContent = '☆';
                            star.classList.remove('filled');
                        } else {
                            star.textContent = '★';
                            star.classList.add('filled');
                        }
                    });
                });
            }

            // Submit rating
            const submitRatingBtn = document.getElementById('submit-rating');
            if (submitRatingBtn) {
                submitRatingBtn.addEventListener('click', submitRating);
            }

            // Load more ratings button
            const loadMoreBtn = document.getElementById('load-more-ratings');
            if (loadMoreBtn) {
                loadMoreBtn.querySelector('button').addEventListener('click', () => {
                    currentPage++;
                    loadRatings(currentPage);
                });
            }

            // Functions
            function loadUserRating() {
                @guest
                return; // Không làm gì nếu người dùng chưa đăng nhập
            @endguest

            fetch(`${baseUrl}/ratings/user/${bookId}`)
            .then(response => {
                if (!response.ok) {
                    if (response.status === 404) {
                        return {
                            rating: null
                        };
                    }
                    throw new Error('Không thể tải đánh giá của bạn');
                }
                return response.json();
            })
            .then(data => {
                if (data.rating) {
                    const userRating = data.rating;
                    userRatingId = userRating.id;
                    const ratingValue = userRating.rating;
                    document.getElementById('rating-value').value = ratingValue;
                    document.getElementById('rating-comment').value = userRating.comment || '';

                    // Cập nhật sao đánh giá
                    const stars = document.querySelectorAll('.user-rating-star');
                stars.forEach((star, index) => {
                        if (index < ratingValue) {
                            star.classList.add('filled');
                        } else {
                            star.classList.remove('filled');
                        }
                    });

                    // Cập nhật text nút submit
                    const submitRatingBtn = document.getElementById('submit-rating');
                    if (submitRatingBtn) {
                        submitRatingBtn.textContent = 'Cập nhật đánh giá';
                    }

                    // Hiển thị nút xóa
                    const deleteRatingBtn = document.getElementById('delete-rating');
                    if (deleteRatingBtn) {
                        deleteRatingBtn.classList.remove('hidden');
                    }
                }
            })
            .catch(error => {
                console.error('Lỗi khi tải đánh giá của người dùng:', error);
                // Hiển thị thông báo lỗi
                const errorElement = document.getElementById('rating-error');
                if (errorElement) {
                    errorElement.textContent = error.message;
                    errorElement.classList.remove('hidden');
                }
            });
        }

        function loadRatings(page = 1) {
            const baseUrl = window.location.origin;
            const bookId = {{ $book->id }};
            const loadingIndicator = document.getElementById('ratings-loading');
            const ratingsContainer = document.getElementById('ratings-container');

            if (loadingIndicator) {
                loadingIndicator.classList.remove('hidden');
            }

            fetch(`${baseUrl}/ratings/book/${bookId}?page=${page}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Không thể tải đánh giá');
                    }
                    return response.json();
                })
                .then(data => {
                    if (loadingIndicator) {
                        loadingIndicator.classList.add('hidden');
                    }

                    // Cập nhật thống kê đánh giá
                    updateRatingStats(data.stats);

                    // Cập nhật danh sách đánh giá
                    if (ratingsContainer) {
                        ratingsContainer.innerHTML = '';

                        if (data.ratings.data && data.ratings.data.length > 0) {
                            data.ratings.data.forEach(rating => {
                                const ratingElement = createRatingElement(rating);
                                ratingsContainer.appendChild(ratingElement);
                            });

                            // Cập nhật phân trang
                            const paginationContainer = document.getElementById('ratings-pagination');
                            if (paginationContainer) {
                                paginationContainer.innerHTML = createPagination(data.ratings);
                            }
                        } else {
                            ratingsContainer.innerHTML =
                                '<p class="text-center text-gray-500 my-4">Chưa có đánh giá nào.</p>';
                        }
                    }
                })
                .catch(error => {
                    console.error('Lỗi khi tải đánh giá:', error);
                    if (loadingIndicator) {
                        loadingIndicator.classList.add('hidden');
                    }
                    if (ratingsContainer) {
                        ratingsContainer.innerHTML =
                            '<p class="text-center text-red-500 my-4">Có lỗi xảy ra khi tải đánh giá. Vui lòng thử lại sau.</p>';
                    }
                });
        }

        function submitRating() {
            const baseUrl = window.location.origin;
            const bookId = {{ $book->id }};
            const ratingValue = document.getElementById('rating-value').value;
            if (!ratingValue) {
                alert('Vui lòng chọn số sao cho đánh giá của bạn');
                return;
            }

            const comment = document.getElementById('rating-comment').value;

            // Disable submit button to prevent multiple submissions
            submitRatingBtn.disabled = true;
            submitRatingBtn.textContent = 'Đang gửi...';

            // Check if this is an update or a new rating
            const method = userRatingId ? 'PUT' : 'POST';
            const endpoint = userRatingId ?
                `${baseUrl}/ratings/book/${bookId}` :
                `${baseUrl}/ratings/book/${bookId}`;

            fetch(endpoint, {
                    method: method,
                                headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                        rating: ratingValue,
                        comment: comment
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                            .then(data => {
                    // Cập nhật UI
                    if (data.rating) {
                        // Lưu user rating ID để cập nhật sau này
                        userRatingId = data.rating.id;

                        // Hiển thị thông báo thành công
                        showToast(method === 'PUT' ? 'Đánh giá đã được cập nhật thành công!' :
                            'Gửi đánh giá thành công!');

                        // Cập nhật nút submit
                        submitRatingBtn.textContent = 'Cập nhật đánh giá';

                        // Hiển thị nút xóa đánh giá
                        const deleteRatingBtn = document.getElementById('delete-rating');
                        if (deleteRatingBtn) {
                            deleteRatingBtn.classList.remove('hidden');
                        }

                        // Tải lại đánh giá để cập nhật danh sách và stats
                        loadRatings();

                        // Tự động cuộn xuống phần đánh giá nếu là đánh giá mới
                        if (method === 'POST') {
                            const ratingsContainer = document.getElementById('ratings-container');
                            if (ratingsContainer) {
                                ratingsContainer.scrollIntoView({
                                    behavior: 'smooth'
                                });
                            }
                        }
                    } else if (data.errors) {
                        alert('Lỗi: ' + Object.values(data.errors).join('\n'));
                    } else if (data.error) {
                        alert('Lỗi: ' + data.error);
                    }
                })
                .catch(error => {
                    console.error('Error submitting rating:', error);
                    alert('Có lỗi xảy ra khi gửi đánh giá. Vui lòng thử lại sau.');
                })
                .finally(() => {
                    // Re-enable submit button
                    submitRatingBtn.disabled = false;
                    submitRatingBtn.textContent = userRatingId ? 'Cập nhật đánh giá' : 'Gửi đánh giá';
                });
        }

        function createRatingElement(rating) {
            const userId = {{ auth()->id() ?? 'null' }};
            const isCurrentUser = userId && rating.user_id === userId;

            const ratingElement = document.createElement('div');
            ratingElement.className = 'rating-item';
            ratingElement.setAttribute('data-rating-id', rating.id);

            // Default avatar if user has no avatar
            const avatarUrl = rating.user && rating.user.photo ?
                rating.user.photo :
                '{{ asset('images/default-avatar.png') }}';

            ratingElement.innerHTML = `
                    <div class="rating-user-info">
                        <div class="rating-user-avatar">
                            <img src="${avatarUrl}" alt="User avatar">
                        </div>
                        <div class="rating-user-details">
                            <span class="rating-user-name">${rating.user ? rating.user.full_name : 'Người dùng ẩn danh'}</span>
                            <div class="rating-stars-display">
                                ${createStars(rating.rating)}
                            </div>
                            <span class="rating-date">${formatDate(rating.created_at)}</span>
                        </div>
                    </div>
                    ${rating.comment ? `<div class="rating-content">${escapeHtml(rating.comment)}</div>` : ''}
                    <div class="rating-actions">
                        <div class="rating-like">
                            <span class="rating-like-icon">👍</span>
                            <span class="rating-like-count">0</span>
                        </div>
                        ${isCurrentUser ? `
                                <div class="ml-auto">
                                    <button class="delete-rating-btn text-red-600 hover:text-red-800 text-sm" data-id="${rating.id}">Xóa</button>
                                </div>
                            ` : ''}
                    </div>
                `;

            // Add event listeners for buttons
            if (isCurrentUser) {
                setTimeout(() => {
                    const deleteBtn = ratingElement.querySelector('.delete-rating-btn');
                    if (deleteBtn) {
                        deleteBtn.addEventListener('click', () => {
                            if (confirm('Bạn có chắc chắn muốn xóa đánh giá này không?')) {
                                deleteRating(rating.id);
                            }
                        });
                    }
                }, 0);
            }

            // Add like functionality
            setTimeout(() => {
                const likeBtn = ratingElement.querySelector('.rating-like');
                if (likeBtn) {
                    likeBtn.addEventListener('click', () => {
                        const countEl = likeBtn.querySelector('.rating-like-count');
                        let count = parseInt(countEl.textContent);
                        if (!likeBtn.classList.contains('liked')) {
                            countEl.textContent = count + 1;
                            likeBtn.classList.add('liked');
                            likeBtn.style.color = 'var(--color-primary)';
                                        } else {
                            countEl.textContent = count - 1;
                            likeBtn.classList.remove('liked');
                            likeBtn.style.color = 'var(--color-gray-600)';
                        }
                    });
                }
            }, 0);

            return ratingElement;
        }

        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function createStars(ratingValue) {
            let starsHtml = '';
            for (let i = 1; i <= 5; i++) {
                if (i <= Math.floor(ratingValue)) {
                    starsHtml += '<span class="rating-star filled">★</span>';
                } else if (i - 0.5 <= ratingValue) {
                    starsHtml += '<span class="rating-star half">☆</span>';
                                } else {
                    starsHtml += '<span class="rating-star">☆</span>';
                }
            }
            return starsHtml;
        }

        function formatDate(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            if (isNaN(date.getTime())) return '';

            return date.toLocaleDateString('vi-VN', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function updateRatingStats(stats) {
            // Update average rating display
            const avgDisplay = document.getElementById('rating-stats-average');
            const ratingAverageSm = document.getElementById('rating-average');
            const ratingCount = document.getElementById('rating-count');

            if (avgDisplay) avgDisplay.textContent = parseFloat(stats.average).toFixed(1);
            if (ratingAverageSm) ratingAverageSm.textContent = parseFloat(stats.average).toFixed(1);
            if (ratingCount) ratingCount.textContent = stats.count;

            // Update distribution bars
            for (let i = 5; i >= 1; i--) {
                const percentage = stats.count > 0 ? (stats.distribution[i] / stats.count) * 100 : 0;
                const fillElement = document.querySelector(`.rating-bar:nth-child(${6-i}) .rating-bar-fill`);
                const countElement = document.querySelector(`.rating-bar:nth-child(${6-i}) .rating-bar-count`);

                if (fillElement) fillElement.style.width = `${percentage}%`;
                if (countElement) countElement.textContent = stats.distribution[i];
            }

            // Update stars in main display
            const starsElement = document.getElementById('book-rating-display');
            if (starsElement) {
                starsElement.innerHTML = createStars(stats.average);
            }
        }

        function deleteRating(ratingId) {
            const baseUrl = window.location.origin;

            fetch(`${baseUrl}/ratings/${ratingId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(data => {
                            throw new Error(data.error || 'Có lỗi xảy ra khi xóa đánh giá');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    // Xóa đánh giá khỏi UI
                    const ratingElement = document.querySelector(`.rating-item[data-rating-id="${ratingId}"]`);
                    if (ratingElement) {
                        ratingElement.remove();
                    }

                    // Reset form nếu đây là đánh giá của người dùng hiện tại
                    if (userRatingId === ratingId) {
                        userRatingId = null;

                        // Reset stars
                        const stars = document.querySelectorAll('#rating-stars-input .rating-star');
                        stars.forEach(star => {
                            star.textContent = '☆';
                            star.classList.remove('selected', 'filled');
                        });

                        // Reset comment
                        const commentElement = document.getElementById('rating-comment');
                        if (commentElement) {
                            commentElement.value = '';
                        }

                        // Reset rating value
                        document.getElementById('rating-value').value = '';

                        // Update submit button
                        if (submitRatingBtn) {
                            submitRatingBtn.textContent = 'Gửi đánh giá';
                        }

                        // Ẩn nút xóa
                        const deleteRatingBtn = document.getElementById('delete-rating');
                        if (deleteRatingBtn) {
                            deleteRatingBtn.classList.add('hidden');
                        }
                    }

                    // Tải lại đánh giá và thống kê
                    loadRatings();

                    // Hiển thị thông báo thành công
                    showToast('Đánh giá đã được xóa thành công');
                })
                .catch(error => {
                    console.error('Lỗi khi xóa đánh giá:', error);
                    alert('Có lỗi xảy ra khi xóa đánh giá: ' + error.message);
                });
        }

        function createPagination(paginator) {
            if (!paginator || !paginator.last_page || paginator.last_page <= 1) {
                return '';
            }

            let html = '<div class="flex justify-center space-x-2 mt-4">';

            // Nút Prev
            if (paginator.current_page > 1) {
                html +=
                    `<button class="pagination-btn bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1 rounded" data-page="${paginator.current_page - 1}">Trước</button>`;
            }

            // Các trang
            const totalPages = paginator.last_page;
            const currentPage = paginator.current_page;

            // Hiển thị tối đa 5 trang
            let startPage = Math.max(1, currentPage - 2);
            let endPage = Math.min(totalPages, startPage + 4);

            if (endPage - startPage < 4) {
                startPage = Math.max(1, endPage - 4);
            }

            for (let i = startPage; i <= endPage; i++) {
                if (i === currentPage) {
                    html +=
                        `<button class="pagination-btn bg-blue-600 text-white px-3 py-1 rounded" data-page="${i}">${i}</button>`;
                } else {
                    html +=
                        `<button class="pagination-btn bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1 rounded" data-page="${i}">${i}</button>`;
                }
            }

            // Nút Next
            if (currentPage < totalPages) {
                html +=
                    `<button class="pagination-btn bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1 rounded" data-page="${currentPage + 1}">Tiếp</button>`;
            }

            html += '</div>';

            // Thêm event listeners cho các nút phân trang
            setTimeout(() => {
                document.querySelectorAll('.pagination-btn').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const page = parseInt(btn.getAttribute('data-page'));
                        loadRatings(page);
                    });
                });
            }, 0);

            return html;
        }

        // Tải tài liệu sách
        @if($hasResources)
        loadBookResources();
        @endif
        });
    </script>
    <script src="{{ asset('js/book-mention.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Khởi tạo mention cho tất cả các textarea comment
            document.querySelectorAll('.comment-textarea').forEach(textarea => {
                new BookMention(textarea);
            });
        });
    </script>
    <script>
        function showToast(message, type = 'success') {
            // Tạo phần tử toast
            const toast = document.createElement('div');
            toast.className = `toast-notification fixed top-4 right-4 p-4 rounded-md shadow-lg z-50 ${
                type === 'success' ? 'bg-green-500' : 'bg-red-500'
            } text-white`;

            // Thêm nội dung
            toast.innerHTML = `
                <div class="flex items-center">
                    <span class="mr-2">${type === 'success' ? '✅' : '❌'}</span>
                    <span>${message}</span>
                </div>
            `;

            // Thêm vào body
            document.body.appendChild(toast);

            // Hiệu ứng hiển thị
            setTimeout(() => {
                toast.classList.add('show');
            }, 10);

            // Tự động biến mất sau 3 giây
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 3000);
        }
    </script>
    <script src="{{ asset('modules/tuongtac/social-interactions.js') }}"></script>
    <script>
        // Mới thêm: Các hàm tải và hiển thị tài liệu
        function loadBookResources() {
            const bookId = {{ $book->id }};
            const resourcesContainer = document.getElementById('book-resources-container');
            
            if (!resourcesContainer) return;
            
            fetch(`/api/books/${bookId}/resources`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Không thể tải tài liệu');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success && data.resources && data.resources.length > 0) {
                        displayResources(data.resources, resourcesContainer);
                    } else {
                        resourcesContainer.innerHTML = '<p class="text-gray-500 text-center py-4">Không có tài liệu đính kèm.</p>';
                    }
                })
                .catch(error => {
                    console.error('Lỗi khi tải tài liệu:', error);
                    resourcesContainer.innerHTML = '<p class="text-red-500 text-center py-4">Có lỗi xảy ra khi tải tài liệu.</p>';
                });
        }

        function displayResources(resources, container) {
            container.innerHTML = '';
            
            resources.forEach(resource => {
                const resourceElement = document.createElement('div');
                resourceElement.className = 'resource-item bg-gray-50 p-3 rounded-lg border border-gray-200 flex justify-between items-center mb-3';
                
                // Xác định loại tài liệu
                let fileIcon = resource.icon_class || 'fas fa-file';
                
                // Tạo URL tải xuống
                let downloadUrl = '';
                if (resource.path) {
                    downloadUrl = resource.path;
                } else if (resource.file_path) {
                    downloadUrl = resource.file_path;
                } else if (resource.url) {
                    downloadUrl = resource.url;
                } else {
                    downloadUrl = `/resource/download?id=${resource.id}`;
                }
                
                resourceElement.innerHTML = `
                    <div class="flex items-center">
                        <i class="${fileIcon} text-2xl text-gray-600 mr-3"></i>
                        <div>
                            <div class="font-medium">${resource.title || resource.file_name || 'Tài liệu'}</div>
                            <div class="text-sm text-gray-500">${resource.file_name || ''}</div>
                        </div>
                    </div>
                    <a href="${downloadUrl}" 
                       class="download-link bg-purple-500 hover:bg-purple-600 text-white px-3 py-1 rounded flex items-center"
                       target="${resource.link_code === 'youtube' ? '_blank' : '_self'}"
                       ${resource.link_code !== 'youtube' ? 'download' : ''}>
                        <i class="fas fa-download mr-1"></i> Tải xuống
                    </a>
                `;
                
                container.appendChild(resourceElement);
            });
        }

        // Gọi hàm tải tài liệu khi trang được tải
        document.addEventListener('DOMContentLoaded', function() {
            @if($hasResources)
            loadBookResources();
            @endif
            
            // Thêm sự kiện cho tab tài liệu
            const tabsButtons = document.querySelectorAll('.book-tab');
            if (tabsButtons) {
                tabsButtons.forEach(tab => {
                    tab.addEventListener('click', function() {
                        const tabId = this.getAttribute('data-tab');
                        if (tabId === 'resources') {
                            loadBookResources();
                        }
                    });
                });
            }
        });
    </script>
@endsection
