<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PointHistory;
use App\Modules\Book\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

class UserLeaderboardController extends Controller
{
    /**
     * Hiển thị trang vinh danh bạn đọc (leaderboard)
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Lấy top 20 người dùng có điểm cao nhất
        $topUsers = User::select('users.id', 'users.full_name', 'users.photo', 'users.email', DB::raw('SUM(point_histories.point) as total_points'))
            ->leftJoin('point_histories', 'users.id', '=', 'point_histories.user_id')
            ->where('users.status', 'active')
            ->where('point_histories.status', 'active')
            ->groupBy('users.id', 'users.full_name', 'users.photo', 'users.email')
            ->orderBy('total_points', 'desc')
            ->limit(20)
            ->get();
        
        // Lấy top người dùng theo tuần
        $weeklyTopUsers = User::select('users.id', 'users.full_name', 'users.photo', 'users.email', DB::raw('SUM(point_histories.point) as total_points'))
            ->leftJoin('point_histories', 'users.id', '=', 'point_histories.user_id')
            ->where('users.status', 'active')
            ->where('point_histories.status', 'active')
            ->where('point_histories.created_at', '>=', now()->subDays(7))
            ->groupBy('users.id', 'users.full_name', 'users.photo', 'users.email')
            ->orderBy('total_points', 'desc')
            ->limit(10)
            ->get();
        
        // Lấy top người dùng theo tháng
        $monthlyTopUsers = User::select('users.id', 'users.full_name', 'users.photo', 'users.email', DB::raw('SUM(point_histories.point) as total_points'))
            ->leftJoin('point_histories', 'users.id', '=', 'point_histories.user_id')
            ->where('users.status', 'active')
            ->where('point_histories.status', 'active')
            ->where('point_histories.created_at', '>=', now()->subDays(30))
            ->groupBy('users.id', 'users.full_name', 'users.photo', 'users.email')
            ->orderBy('total_points', 'desc')
            ->limit(10)
            ->get();

        // Lấy số sách đã đọc cho mỗi người dùng
        $allUsers = collect();
        $allUsers = $allUsers->merge($topUsers)->merge($weeklyTopUsers)->merge($monthlyTopUsers)->unique('id');
        
        // Tạo một danh sách các ID người dùng để thực hiện truy vấn một lần
        $userIds = $allUsers->pluck('id')->toArray();
        
        // Lấy dữ liệu số sách đã đọc cho tất cả người dùng một lần
        $booksReadCounts = DB::table('point_histories')
            ->select('user_id', DB::raw('COUNT(DISTINCT reference_id) as books_read_count'))
            ->whereIn('user_id', $userIds)
            ->where('status', 'active')
            ->where(function($query) {
                $query->where('reference_type', 'book')
                    ->orWhereExists(function($subquery) {
                        $subquery->select(DB::raw(1))
                            ->from('point_rules')
                            ->whereRaw('point_histories.point_rule_id = point_rules.id')
                            ->where('point_rules.code', 'read_book');
                    });
            })
            ->groupBy('user_id')
            ->get()
            ->keyBy('user_id');
        
        // Gán số sách đã đọc vào từng bộ dữ liệu người dùng
        foreach ($topUsers as $user) {
            $user->books_read_count = $booksReadCounts[$user->id]->books_read_count ?? 0;
        }
        
        foreach ($weeklyTopUsers as $user) {
            $user->books_read_count = $booksReadCounts[$user->id]->books_read_count ?? 0;
        }
        
        foreach ($monthlyTopUsers as $user) {
            $user->books_read_count = $booksReadCounts[$user->id]->books_read_count ?? 0;
        }

        return view('frontend.leaderboard.index', compact('topUsers', 'weeklyTopUsers', 'monthlyTopUsers'));
    }
} 