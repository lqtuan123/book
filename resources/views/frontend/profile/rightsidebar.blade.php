<!-- Right Sidebar -->
<aside id="right-sidebar" class="sidebar right-sidebar lg:w-1/5 lg:pl-4">
    <div class="bg-white rounded-lg shadow-sm p-4">
        <!-- Recent Activities -->
        <div class="mb-6">
            <h3 class="font-bold text-gray-800 mb-4">
                <i class="fas fa-bell mr-2 text-blue-500 flex-shrink-0"></i>
                <span class="truncate">Hoạt động gần đây</span>
            </h3>

            <div class="space-y-3">
                @if (isset($recentActivities) && count($recentActivities) > 0)
                    @foreach ($recentActivities as $activity)
                        <!-- Activity {{ $loop->iteration }} -->
                        <div class="activity-item flex items-start p-2 rounded hover:bg-gray-50">
                            <div
                                class="{{ $activity->icon_bg ?? 'bg-blue-100' }} {{ $activity->icon_text ?? 'text-blue-500' }} rounded-full p-2 mr-3 flex-shrink-0">
                                <i class="{{ $activity->icon ?? 'fas fa-history' }} text-sm"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm text-gray-800 break-words">{!! $activity->content !!}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ \Carbon\Carbon::parse($activity->created_at)->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-4">
                        <p class="text-gray-500 text-sm">Không có hoạt động gần đây</p>
                    </div>
                @endif
            </div>

            @if (isset($recentActivities) && count($recentActivities) > 0)
                <a href="#" class="block text-center text-blue-500 text-sm mt-4 hover:text-blue-700">Xem
                    tất cả</a>
            @endif
            
           
        </div>

        <!-- Sách được xem nhiều -->
        <div class="mb-6">
            <h3 class="font-bold text-gray-800 mb-4">
                <a href="#books" class="tab-link flex items-center hover:text-blue-500" data-tab="books">
                    <i class="fas fa-eye mr-2 text-blue-500 flex-shrink-0"></i>
                    <span class="truncate">Sách của tôi </span>
                </a>
            </h3>

            <div class="space-y-3">
                @if (isset($books) && count($books) > 0)
                    @php
                        $mostViewedBooks = $books->sortByDesc('views')->take(5);
                    @endphp
                    
                    @if($mostViewedBooks->count() > 0)
                        @foreach ($mostViewedBooks as $book)
                            <!-- Book {{ $loop->iteration }} -->
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-12 h-16 bg-gray-200 rounded overflow-hidden">
                                    <a href="{{ route('front.book.show', $book->slug) }}">
                                        <img src="{{ $book->photo ? (strpos($book->photo, 'http') === 0 ? $book->photo : asset($book->photo)) : asset('images/default-book.jpg') }}"
                                            alt="{{ $book->title }}" class="w-full h-full object-cover">
                                    </a>
                                </div>
                                <div class="ml-3 min-w-0 flex-1">
                                    <h3 class="text-sm font-medium text-gray-800 truncate">
                                        <a href="{{ route('front.book.show', $book->slug) }}" class="hover:text-blue-500">
                                            {{ $book->title }}
                                        </a>
                                    </h3>
                                    <p class="text-xs text-gray-500">
                                        <i class="fas fa-eye mr-1"></i> {{ $book->views ?? 0 }} lượt xem
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <p class="text-gray-500 text-sm">Chưa có sách nào được xem</p>
                        </div>
                    @endif
                @else
                    <div class="text-center py-4">
                        <p class="text-gray-500 text-sm">Chưa có sách nào</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Bài viết được xem nhiều -->
        <div>
            <h3 class="font-bold text-gray-800 mb-4">
                <a href="#posts" class="tab-link flex items-center hover:text-blue-500" data-tab="posts">
                    <i class="fas fa-eye mr-2 text-blue-500 flex-shrink-0"></i>
                    <span class="truncate">Bài viết của tôi </span>
                </a>
            </h3>

            <div class="space-y-3">
                @if (isset($userPosts) && count($userPosts) > 0)
                    @php
                        $mostViewedPosts = collect($userPosts)->sortByDesc('hit')->take(5);
                    @endphp
                    
                    @if(count($mostViewedPosts) > 0)
                        @foreach ($mostViewedPosts as $post)
                            <!-- Post {{ $loop->iteration }} -->
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-12 h-12 bg-gray-200 rounded overflow-hidden">
                                    @php
                                        $images = json_decode($post->photo ?? '[]', true);
                                        $thumbnail_url = null;
                                        if (is_array($images) && count($images) > 0) {
                                            $thumbnail_url = $images[0];
                                        }
                                    @endphp
                                    
                                    <a href="{{ route('front.tblogs.show', $post->slug) }}">
                                        @if($thumbnail_url)
                                            <img src="{{ $thumbnail_url }}" alt="{{ $post->title ?? 'Bài viết' }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-gray-300 flex items-center justify-center">
                                                <i class="fas fa-newspaper text-gray-400"></i>
                                            </div>
                                        @endif
                                    </a>
                                </div>
                                <div class="ml-3 min-w-0 flex-1">
                                    <h3 class="text-sm font-medium text-gray-800 truncate">
                                        <a href="{{ route('front.tblogs.show', $post->slug) }}" class="hover:text-blue-500">
                                            {{ $post->title ?? 'Bài viết không có tiêu đề' }}
                                        </a>
                                    </h3>
                                    <div class="flex items-center text-xs text-gray-500 mt-1">
                                        <i class="fas fa-eye text-blue-500 mr-1"></i>
                                        <span class="truncate">{{ $post->hit ?? 0 }} lượt xem</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <p class="text-gray-500 text-sm">Chưa có bài viết nào được xem</p>
                        </div>
                    @endif
                @else
                    <div class="text-center py-4">
                        <p class="text-gray-500 text-sm">Chưa có bài viết nào</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</aside>

<!-- Right Sidebar for Mobile (Slider) -->
<aside class="right-sidebar-mobile lg:w-1/5 lg:pl-4 mt-6">
    <div class="bg-white rounded-lg shadow-sm p-4 overflow-x-auto">
        <div class="flex space-x-4 w-max">
            <!-- Activities Card -->
            <div class="w-64 flex-shrink-0">
                <h3 class="font-bold text-gray-800 mb-4">
                    <i class="fas fa-bell mr-2 text-blue-500 flex-shrink-0"></i>
                    <span class="truncate">Hoạt động gần đây</span>
                </h3>

                <div class="space-y-3">
                    @if (isset($recentActivities) && count($recentActivities) > 0)
                        <div class="flex items-start">
                            <div
                                class="{{ $recentActivities[0]->icon_bg ?? 'bg-blue-100' }} {{ $recentActivities[0]->icon_text ?? 'text-blue-500' }} rounded-full p-2 mr-3 flex-shrink-0">
                                <i class="{{ $recentActivities[0]->icon ?? 'fas fa-history' }} text-sm"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm text-gray-800 break-words">{!! $recentActivities[0]->content ?? 'Không có hoạt động gần đây' !!}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ isset($recentActivities[0]) ? \Carbon\Carbon::parse($recentActivities[0]->created_at)->diffForHumans() : '' }}
                                </p>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-gray-500 text-sm">Không có hoạt động gần đây</p>
                        </div>
                    @endif
                </div>
                
                <!-- Thống kê lượt xem cho mobile -->
                <div class="mt-3 pt-3 border-t border-gray-200">
                    <h4 class="text-xs font-semibold text-gray-700 mb-2">Tổng lượt xem</h4>
                    <div class="flex justify-between items-center">
                        <div class="text-center flex-1">
                            <span class="block text-blue-600 font-bold text-sm">{{ $totalBookViews ?? 0 }}</span>
                            <span class="text-xs text-gray-500">Sách</span>
                        </div>
                        <div class="text-center flex-1">
                            <span class="block text-green-600 font-bold text-sm">{{ $totalBlogViews ?? 0 }}</span>
                            <span class="text-xs text-gray-500">Bài viết</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sách được xem nhiều - Mobile -->
            <div class="w-64 flex-shrink-0">
                <h3 class="font-bold text-gray-800 mb-4">
                    <a href="#books" class="tab-link flex items-center hover:text-blue-500" data-tab="books">
                        <i class="fas fa-eye mr-2 text-blue-500 flex-shrink-0"></i>
                        <span class="truncate">Sách được xem nhiều</span>
                    </a>
                </h3>

                <div class="flex space-x-2">
                    @if (isset($books) && count($books) > 0)
                        @php
                            $mostViewedBooks = $books->sortByDesc('views')->take(2);
                        @endphp
                        
                        @if($mostViewedBooks->count() > 0)
                            @foreach ($mostViewedBooks as $book)
                                <div class="flex-shrink-0 w-12 h-16 bg-gray-200 rounded overflow-hidden">
                                    <a href="{{ route('front.book.show', $book->slug) }}">
                                        <img src="{{ $book->photo ? (strpos($book->photo, 'http') === 0 ? $book->photo : asset($book->photo)) : asset('images/default-book.jpg') }}"
                                            alt="{{ $book->title }}" class="w-full h-full object-cover">
                                    </a>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center w-full py-4">
                                <p class="text-gray-500 text-sm">Chưa có sách nào được xem</p>
                            </div>
                        @endif
                    @else
                        <div class="text-center w-full py-4">
                            <p class="text-gray-500 text-sm">Chưa có sách nào</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</aside>
