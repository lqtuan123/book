<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Tuongtac\Models\TBlog;
use App\Modules\Tuongtac\Models\TTag;
use App\Modules\Group\Models\Group;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class TBlogSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('vi_VN');

        // Kiểm tra và tạo TTag nếu cần
        $tags = [
            'PHP', 'Laravel', 'Python', 'Java', 'JavaScript', 
            'Web Development', 'Mobile Development', 'Database', 'AI', 
            'DevOps', 'Cloud Computing', 'Security', 'UX/UI', 'Frontend',
            'Backend', 'Fullstack', 'API', 'Design Pattern', 'Testing',
            'Docker', 'Kubernetes', 'AWS', 'Azure', 'GCP'
        ];

        foreach ($tags as $tagName) {
            TTag::firstOrCreate(
                ['title' => $tagName],
                [
                    'slug' => Str::slug($tagName),
                    'hit' => $faker->numberBetween(0, 100),
                ]
            );
        }

        // Lấy danh sách tag id
        $tagIds = TTag::whereIn('title', $tags)->pluck('id')->toArray();

        // Lấy danh sách nhóm
        $groups = Group::pluck('id')->toArray();

        // Danh sách ảnh từ mạng
        $onlineImages = collect(range(1, 10))->map(fn($i) => "https://picsum.photos/800/600?random={$i}")->toArray();

        // Tiêu đề bài viết
        $blogTitles = [
            'Hướng dẫn sử dụng Laravel 10 cho người mới bắt đầu',
            'Cách tối ưu hiệu suất ứng dụng PHP',
            'ReactJS vs Angular: Nên chọn framework nào cho dự án frontend?',
            'Docker cho môi trường phát triển web: từ A đến Z',
            'Thiết kế RESTful API hiệu quả với Laravel',
            'Kinh nghiệm phỏng vấn xin việc vị trí Frontend Developer',
            'Xây dựng ứng dụng microservices với NodeJS',
            'Machine Learning cơ bản cho lập trình viên',
            'Clean Code: Nguyên tắc và thực hành',
            'DevOps là gì và tại sao nó quan trọng?'
        ];

        // Tạo 10 bài viết
        for ($i = 0; $i < 10; $i++) {
            $title = $blogTitles[$i];
            $slug = Str::slug($title);

            // Đảm bảo slug duy nhất
            $originalSlug = $slug;
            $counter = 1;
            while (TBlog::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter++;
            }

            // Dùng ảnh mạng
            $photoArray = [$faker->randomElement($onlineImages)];

            // Chọn tag và group ngẫu nhiên
            $selectedTags = $faker->randomElements($tagIds, $faker->numberBetween(3, 5));
            $groupId = $faker->boolean(70) ? $faker->randomElement($groups) : 0;

            // Nội dung
            $content = '<h2>Giới thiệu</h2>';
            $content .= '<p>' . $faker->paragraph(3) . '</p>';
            $content .= '<h2>Nội dung chính</h2>';
            $content .= '<p>' . $faker->paragraph(5) . '</p>';
            $content .= '<h3>Phần 1</h3>';
            $content .= '<p>' . $faker->paragraph(4) . '</p>';
            $content .= '<h3>Phần 2</h3>';
            $content .= '<p>' . $faker->paragraph(4) . '</p>';
            $content .= '<h2>Kết luận</h2>';
            $content .= '<p>' . $faker->paragraph(2) . '</p>';

            $blog = TBlog::create([
                'title' => $title,
                'slug' => $slug,
                'content' => $content,
                'photo' => json_encode($photoArray),
                'user_id' => $faker->numberBetween(1, 5),
                'hit' => $faker->numberBetween(0, 500),
                'status' => $faker->randomElement([0, 1]),
                'resources' => json_encode([]),
                'group_id' => $groupId,
            ]);

            // Gán tag
            foreach ($selectedTags as $tagId) {
                DB::table('t_tag_items')->insert([
                    'tag_id' => $tagId,
                    'item_id' => $blog->id,
                    'item_code' => 'tblog',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
