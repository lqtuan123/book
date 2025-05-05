<?php
use \App\Modules\Tuongtac\Models\TPage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

// Lấy user ID nếu đã đăng nhập
$userId = Auth::check() ? Auth::id() : null;

// Get new posts
$newposts = \App\Modules\Tuongtac\Models\TBlog::with('group')
    ->where('status', 1)
    ->where(function($query) use ($userId) {
        // Bài viết không thuộc nhóm nào
        $query->where(function($q) {
            $q->whereNull('group_id')
              ->orWhere('group_id', 0);
        });
        
        // HOẶC bài viết thuộc nhóm công khai
        $query->orWhereHas('group', function($q) {
            $q->where('is_private', 0)->where('status', 'active');
        });
        
        // HOẶC bài viết thuộc nhóm riêng tư nhưng người dùng là thành viên (nếu đã đăng nhập)
        if ($userId) {
            $query->orWhereHas('group', function($q) use ($userId) {
                $q->where('is_private', 1)
                  ->where('status', 'active')
                  ->where(function($subQ) use ($userId) {
                      // Người dùng là thành viên
                      $subQ->whereRaw("JSON_CONTAINS(members, '\"$userId\"')");
                      // Hoặc là người tạo nhóm
                      $subQ->orWhere('author_id', $userId);
                      // Hoặc là phó nhóm
                      $subQ->orWhereRaw("JSON_CONTAINS(moderators, '\"$userId\"')");
                  });
            });
        }
    })
    ->inRandomOrder()
    ->limit(10)
    ->get();

// Get popular posts
$popularPosts = \App\Modules\Tuongtac\Models\TBlog::with('group')
    ->where('status', 1)
    ->where(function($query) use ($userId) {
        // Bài viết không thuộc nhóm nào
        $query->where(function($q) {
            $q->whereNull('group_id')
              ->orWhere('group_id', 0);
        });
        
        // HOẶC bài viết thuộc nhóm công khai
        $query->orWhereHas('group', function($q) {
            $q->where('is_private', 0)->where('status', 'active');
        });
        
        // HOẶC bài viết thuộc nhóm riêng tư nhưng người dùng là thành viên (nếu đã đăng nhập)
        if ($userId) {
            $query->orWhereHas('group', function($q) use ($userId) {
                $q->where('is_private', 1)
                  ->where('status', 'active')
                  ->where(function($subQ) use ($userId) {
                      // Người dùng là thành viên
                      $subQ->whereRaw("JSON_CONTAINS(members, '\"$userId\"')");
                      // Hoặc là người tạo nhóm
                      $subQ->orWhere('author_id', $userId);
                      // Hoặc là phó nhóm
                      $subQ->orWhereRaw("JSON_CONTAINS(moderators, '\"$userId\"')");
                  });
            });
        }
    })
    ->orderBy('hit', 'desc')
    ->limit(10)
    ->get();

// Get new users
$newusers = \App\Models\User::orderBy('id','desc')->limit(5)->get();

// Get active groups with most members
use App\Modules\Group\Models\Group;
$groups = Group::where('status', 'active')
    ->orderByRaw('JSON_LENGTH(members) DESC') // Sắp xếp theo số lượng thành viên (giảm dần)
    ->limit(10) // Giới hạn 10 nhóm
    ->get();
?>

<aside class="sidebar right-sidebar lg:w-1/5 lg:pl-4">
    <div class="bg-white rounded-lg shadow-sm p-4">
        <!-- Top Menu Links -->
        <div class="mb-6">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-list mr-2 text-blue-500"></i>
                Điều hướng
            </h3>
            <ul class="space-y-2">
                <li>
                    <a href="{{route('front.tblogs.index')}}" class="flex items-center text-gray-700 hover:text-blue-600 {{ request()->routeIs('front.tblogs.index') ? 'text-blue-600 font-medium' : '' }}">
                        <i class="fas fa-th-list mr-2 text-sm"></i>
                        Tất cả
                    </a>
                </li>
                <li>
                    <a href="{{route('front.tblogs.myblog')}}" class="flex items-center text-gray-700 hover:text-blue-600 {{ request()->routeIs('front.tblogs.myblog') ? 'text-blue-600 font-medium' : '' }}">
                        <i class="fas fa-pencil-alt mr-2 text-sm"></i>
                        Bài viết của tôi
                    </a>
                </li>
                <li>
                    <a href="{{route('front.tblogs.favblog')}}" class="flex items-center text-gray-700 hover:text-blue-600 {{ request()->routeIs('front.tblogs.favblog') ? 'text-blue-600 font-medium' : '' }}">
                        <i class="fas fa-heart mr-2 text-sm"></i>
                        Bài viết quan tâm
                    </a>
                </li>
                <li>
                    <a href="{{route('front.userpages.hornor')}}" class="flex items-center text-gray-700 hover:text-blue-600 {{ request()->routeIs('front.userpages.hornor') ? 'text-blue-600 font-medium' : '' }}">
                        <i class="fas fa-award mr-2 text-sm"></i>
                        Người dùng vinh danh
                    </a>
                </li>
                <li>
                    <a href="{{ route('front.tblogs.trendblog') }}" class="flex items-center text-gray-700 hover:text-blue-600 {{ request()->routeIs('front.tblogs.trendblog') ? 'text-blue-600 font-medium' : '' }}">
                        <i class="fas fa-chart-line mr-2 text-sm"></i>
                        Bài viết xu hướng
                    </a>
                </li>
            </ul>
        </div>
        
        <!-- Groups -->
        <div class="mb-6">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-users mr-2 text-green-500"></i>
                <a href="{{ route('group.index') }}" class="hover:text-blue-600">Nhóm thành viên</a>
            </h3>
            
            <div class="space-y-3">
                @foreach($groups as $group)
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full overflow-hidden">
                        @if($group->photo)
                            <img src="{{ $group->photo }}" alt="{{ $group->title }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-users text-blue-500"></i>
                            </div>
                        @endif
                    </div>
                    <div class="ml-3">
                        <a href="{{ route('group.show', $group->id) }}" class="text-sm font-medium text-gray-800 hover:text-blue-600">
                            {{ Str::limit($group->title, 20) }}
                        </a>
                        <div class="text-xs text-gray-500 mt-1">
                            <i class="fas fa-users text-gray-400 mr-1"></i> {{ $group->getMemberCount() }} thành viên
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <a href="{{ route('group.index') }}" class="block text-center text-blue-500 text-sm mt-4 hover:text-blue-700">Xem tất cả</a>
        </div>
        
        <!-- Recent Posts -->
        <div class="mb-6">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-newspaper mr-2 text-red-500"></i>
                Có thể bạn quan tâm
            </h3>
            
            <div class="space-y-4">
                @foreach($newposts as $post)
                <?php
                    $images = json_decode($post->photo, true);
                    if (!$images) {
                        $thumbnail_url = "https://itcctv.vn/images/profile-8.jpg";
                    } else {
                        $thumbnail_url = $images[0];
                    }
                ?>
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-16 h-16 bg-gray-200 rounded overflow-hidden">
                        <img src="{{ $thumbnail_url }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
                    </div>
                    <div class="ml-3">
                        <a href="{{ route('front.tblogs.show', $post->slug) }}" class="text-sm font-medium text-gray-800 hover:text-blue-600">
                            {{ Str::limit($post->title, 45) }}
                        </a>
                        <div class="flex items-center mt-1">
                            <span class="text-xs text-gray-500">
                                <i class="fas fa-eye text-gray-400 mr-1"></i> {{ $post->hit ?? 0 }}
                            </span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <a href="{{ route('front.tblogs.index') }}" class="block text-center text-blue-500 text-sm mt-4 hover:text-blue-700">Xem thêm</a>
        </div>
        
        <!-- Ad Banner -->
        <div class="mb-6">
            <ins class="adsbygoogle"
                style="display:block"
                data-ad-client="ca-pub-5437344106154965"
                data-ad-slot="1550593306"
                data-ad-format="auto"
                data-full-width-responsive="true"></ins>
            <script>
                (adsbygoogle = window.adsbygoogle || []).push({});
            </script>
        </div>
        
        <!-- Popular Posts -->
        <div class="mb-6">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-fire mr-2 text-orange-500"></i>
                Bài viết phổ biến
            </h3>
            
            <div class="space-y-4">
                @foreach($popularPosts as $post)
                <?php
                    $images = json_decode($post->photo, true);
                    if (!$images) {
                        $thumbnail_url = "https://itcctv.vn/images/profile-8.jpg";
                    } else {
                        $thumbnail_url = $images[0];
                    }
                ?>
                <div class="flex items-start">
                    <div class="flex-shrink-0 w-16 h-16 bg-gray-200 rounded overflow-hidden">
                        <img src="{{ $thumbnail_url }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
                    </div>
                    <div class="ml-3">
                        <a href="{{ route('front.tblogs.show', $post->slug) }}" class="text-sm font-medium text-gray-800 hover:text-blue-600">
                            {{ Str::limit($post->title, 45) }}
                        </a>
                        <div class="flex items-center mt-1">
                            <span class="text-xs text-gray-500">
                                <i class="fas fa-fire text-orange-400 mr-1"></i> {{ $post->hit ?? 0 }} lượt xem
                            </span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <a href="{{ route('front.tblogs.trendblog') }}" class="block text-center text-blue-500 text-sm mt-4 hover:text-blue-700">Xem thêm</a>
        </div>
        
        <!-- Ad Banner -->
        <div class="mb-6">
            <ins class="adsbygoogle"
                style="display:block"
                data-ad-client="ca-pub-5437344106154965"
                data-ad-slot="7114573880"
                data-ad-format="auto"
                data-full-width-responsive="true"></ins>
            <script>
                (adsbygoogle = window.adsbygoogle || []).push({});
            </script>
        </div>
        
        <!-- New Users -->
        <div class="mb-6">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-user-friends mr-2 text-purple-500"></i>
                Người dùng mới
            </h3>
            
            <div class="space-y-3">
                @foreach($newusers as $newuser)
                <div class="flex items-center">
                    <img src="{{ $newuser->photo }}" alt="{{ $newuser->full_name }}" class="w-10 h-10 rounded-full object-cover">
                    <div class="ml-3">
                        <a href="{{ TPage::getPageUrl($newuser->id, 'user') }}" class="text-sm font-medium text-gray-800 hover:text-blue-600">
                            {{ $newuser->full_name }}
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            
            <a href="{{ route('front.userpages.hornor') }}" class="block text-center text-blue-500 text-sm mt-4 hover:text-blue-700">Xem tất cả</a>
        </div>
        
        <!-- Ad Banner -->
        <div>
            <ins class="adsbygoogle"
                style="display:block"
                data-ad-format="autorelaxed"
                data-ad-client="ca-pub-5437344106154965"
                data-ad-slot="2431624238"></ins>
            <script>
                (adsbygoogle = window.adsbygoogle || []).push({});
            </script>
        </div>
    </div>
</aside>

<!-- Right Sidebar for Mobile (Slider) -->
<aside class="right-sidebar-mobile lg:hidden mt-6">
    <div class="bg-white rounded-lg shadow-sm p-4 overflow-x-auto">
        <div class="flex space-x-4 w-max">
            <!-- Recent Posts Card -->
            <div class="w-64 flex-shrink-0">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-newspaper mr-2 text-red-500"></i>
                    Bài viết mới
                </h3>
                
                <div class="space-y-4">
                    @foreach($newposts->take(3) as $post)
                    <?php
                        $images = json_decode($post->photo, true);
                        if (!$images) {
                            $thumbnail_url = "https://itcctv.vn/images/profile-8.jpg";
                        } else {
                            $thumbnail_url = $images[0];
                        }
                    ?>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-14 h-14 bg-gray-200 rounded overflow-hidden">
                            <img src="{{ $thumbnail_url }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
                        </div>
                        <div class="ml-3">
                            <a href="{{ route('front.tblogs.show', $post->slug) }}" class="text-xs font-medium text-gray-800 hover:text-blue-600">
                                {{ Str::limit($post->title, 30) }}
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Popular Posts Card -->
            <div class="w-64 flex-shrink-0">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-fire mr-2 text-orange-500"></i>
                    Bài nổi bật
                </h3>
                
                <div class="space-y-4">
                    @foreach($popularPosts->take(3) as $post)
                    <?php
                        $images = json_decode($post->photo, true);
                        if (!$images) {
                            $thumbnail_url = "https://itcctv.vn/images/profile-8.jpg";
                        } else {
                            $thumbnail_url = $images[0];
                        }
                    ?>
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-14 h-14 bg-gray-200 rounded overflow-hidden">
                            <img src="{{ $thumbnail_url }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
                        </div>
                        <div class="ml-3">
                            <a href="{{ route('front.tblogs.show', $post->slug) }}" class="text-xs font-medium text-gray-800 hover:text-blue-600">
                                {{ Str::limit($post->title, 30) }}
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Groups Card -->
            <div class="w-64 flex-shrink-0">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-users mr-2 text-green-500"></i>
                    Nhóm
                </h3>
                
                <div class="space-y-3">
                    @foreach($groups->take(3) as $group)
                    <div class="flex items-center">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full overflow-hidden">
                            @if($group->photo)
                                <img src="{{ $group->photo }}" alt="{{ $group->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-blue-100 flex items-center justify-center">
                                    <i class="fas fa-users text-blue-500"></i>
                                </div>
                            @endif
                        </div>
                        <div class="ml-3">
                            <a href="{{ route('group.show', $group->id) }}" class="text-xs font-medium text-gray-800 hover:text-blue-600">
                                {{ Str::limit($group->title, 15) }}
                            </a>
                            <div class="text-xs text-gray-500 mt-1">
                                <i class="fas fa-users text-gray-400 mr-1"></i> {{ $group->getMemberCount() }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</aside>
</aside>

<script>
    // Make sure we have a single instance of each function
    document.addEventListener('DOMContentLoaded', function() {
        // Ensure bookmark buttons work
        document.querySelectorAll('.btn-bookmark').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const postId = this.getAttribute('data-post-id');
                if (postId) {
                    bookmarkPost(postId);
                }
            });
        });
        
        // Ensure reaction buttons work
        document.querySelectorAll('.btn-reaction').forEach(button => {
            button.addEventListener('click', function() {
                const reactionId = this.getAttribute('data-reaction-id');
                const postId = this.getAttribute('data-id');
                const itemCode = this.getAttribute('item_code');
                reactToPost(reactionId, postId, itemCode);
            });
        });
    });
</script>