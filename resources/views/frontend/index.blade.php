<?php
 
$setting = \app\Models\SettingDetail::find(1);
$user = auth()->user();
 
// Get top categories with most books
$topCategories = \App\Modules\Book\Models\BookType::withCount(['books' => function($query) {
        $query->where('status', 'active')->where('block', 'no');
    }])
    ->where('status', 'active')
    ->orderBy('books_count', 'desc')
    ->limit(6)
    ->get();


?>


@extends('frontend.layouts.master1')

@section('css')
    {{-- <link rel="stylesheet" href="{{ asset('frontend/assets_f/style.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/custom8.css') }}" type="text/css" /> --}}
    <style>
        .leaderboard-user-card {
            transition: all 0.3s ease;
        }
        .leaderboard-user-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0,0,0,0.1);
        }
        .user-rank {
            width: 2rem;
            height: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-weight: bold;
            color: white;
        }
        .rank-1 {
            background: linear-gradient(45deg, #FFD700, #FFC107);
            box-shadow: 0 3px 8px rgba(255, 215, 0, 0.4);
        }
        .rank-2 {
            background: linear-gradient(45deg, #C0C0C0, #E0E0E0);
            box-shadow: 0 3px 6px rgba(192, 192, 192, 0.4);
        }
        .rank-3 {
            background: linear-gradient(45deg, #CD7F32, #D2691E);
            box-shadow: 0 3px 6px rgba(205, 127, 50, 0.4);
        }
        .rank-other {
            background: linear-gradient(45deg, #64748b, #94a3b8);
            box-shadow: 0 2px 4px rgba(100, 116, 139, 0.3);
        }
    </style>
@endsection

@section('content')
    @include('frontend.layouts.bannertop')
    <div class="py-8">
        <!-- Book Categories Section - Full Width -->
        <section class="mb-12">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Thể loại sách</h2>
                <a href="{{ route('front.book.index') }}" class="text-blue-500 hover:text-blue-700 font-medium">Xem tất cả <i class="fas fa-chevron-right ml-1"></i></a>
            </div>
            
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach($topCategories as $category)
                <a href="{{ route('front.book.byType', $category->slug) }}" class="category-card bg-white rounded-lg p-4 shadow-sm hover:shadow-md transition cursor-pointer text-center">
                    <div class="bg-{{ ['blue', 'purple', 'pink', 'green', 'yellow', 'red'][rand(0,5)] }}-100 w-16 h-16 mx-auto rounded-full flex items-center justify-center mb-3">
                        <i class="fas fa-{{ ['book', 'brain', 'code', 'heart', 'square-root-alt', 'chart-line'][rand(0,5)] }} text-{{ ['blue', 'purple', 'pink', 'green', 'yellow', 'red'][rand(0,5)] }}-500 text-2xl"></i>
                    </div>
                    <h3 class="font-medium text-gray-800">{{ $category->title }}</h3>
                    <p class="text-sm text-gray-500 mt-1">{{ $category->books_count }} sách</p>
                </a>
                @endforeach
                
                @if(count($topCategories) < 6)
                    @for($i = count($topCategories); $i < 6; $i++)
                    <div class="category-card bg-white rounded-lg p-4 shadow-sm hover:shadow-md transition cursor-pointer text-center">
                        <div class="bg-gray-100 w-16 h-16 mx-auto rounded-full flex items-center justify-center mb-3">
                            <i class="fas fa-book text-gray-500 text-2xl"></i>
                        </div>
                        <h3 class="font-medium text-gray-800">Thể loại khác</h3>
                        <p class="text-sm text-gray-500 mt-1">Khám phá</p>
                    </div>
                    @endfor
                @endif
            </div>
        </section>

        <!-- Book and Aside Sections -->
        <div class="flex flex-col lg:flex-row gap-8">
            @include('frontend.layouts.book')
            @include('frontend.layouts.aside')
            
            
                
                
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{-- <script src="{{ asset('frontend/assets/js/timer.js') }}"></script> --}}
@endsection
