<?php
use Illuminate\Support\Str;
use App\Models\Tag;

// Get top tags
$toptags = Tag::where('status', 'active')
    ->orderBy('hit', 'desc')
    ->limit(5)
    ->get();

$ids = $toptags->pluck('id')->toArray();

// Query the menu tags
$menutags = Tag::where('status', 'active')
    ->whereNotIn('id', $ids)
    ->orderBy('hit', 'desc')
    ->limit(10)
    ->get();

// Query the remaining tags
$tags = Tag::where('status', 'active')
    ->whereNotIn('id', $ids)
    ->whereNotIn('id', $menutags->pluck('id')->toArray())
    ->orderBy('hit', 'desc')
    ->limit(50)
    ->get();
?>

<aside id="left-sidebar" class="sidebar lg:w-1/5 lg:pr-4 mb-6 lg:mb-0">
    <div class="bg-white rounded-lg shadow-sm p-4">
        <!-- Sidebar Toggle Button (Mobile) -->
        <button id="sidebar-toggle" class="lg:hidden w-full bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-md mb-4 flex items-center justify-between">
            <span>Tag phổ biến</span>
            <i class="fas fa-bars"></i>
        </button>
        
        <!-- Popular Tags -->
        <div>
            <h3 class="font-bold text-gray-800 mb-3 flex items-center">
                <i class="fas fa-tags mr-2 text-blue-500"></i>
                Tag phổ biến
            </h3>
            <div class="flex flex-wrap gap-2 mb-4">
                @foreach($toptags as $tag)
                <a href="{{ route('front.tblogs.tag', $tag->slug) }}" class="tag bg-gray-100 hover:bg-blue-100 text-gray-800 px-3 py-1 rounded-full text-xs transition {{ request()->is('front/blogs/tag/'.$tag->slug) ? 'bg-blue-100 text-blue-600' : '' }}">
                    {{ $tag->title }}
                </a>
                @endforeach
                
                @foreach($menutags as $tag)
                <a href="{{ route('front.tblogs.tag', $tag->slug) }}" class="tag bg-gray-100 hover:bg-blue-100 text-gray-800 px-3 py-1 rounded-full text-xs transition {{ request()->is('front/blogs/tag/'.$tag->slug) ? 'bg-blue-100 text-blue-600' : '' }}">
                    {{ $tag->title }}
                </a>
                @endforeach
            </div>
            
            @if($tags->count() > 0)
            <div class="text-sm text-gray-500 mb-2">Thẻ khác:</div>
            <div class="flex flex-wrap gap-2">
                @foreach($tags as $tag)
                <a href="{{ route('front.tblogs.tag', $tag->slug) }}" class="text-blue-500 hover:text-blue-700 text-xs">
                    #{{ $tag->title }}
                </a>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</aside>
