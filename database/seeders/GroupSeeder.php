<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Group\Models\Group;
use App\Modules\Group\Models\GroupType;
use App\Modules\Tuongtac\Models\TPage;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class GroupSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('vi_VN');

        // Kiểm tra và tạo GroupType nếu chưa có
        $typeCode = 'general';
        $groupType = GroupType::firstOrCreate(
            ['type_code' => $typeCode],
            ['title' => 'Nhóm học tập', 'status' => 'active']
        );

        // Danh sách ảnh từ mạng
        $onlineImages = collect(range(1, 10))->map(fn($i) => "https://picsum.photos/800/600?random={$i}")->toArray();

        // Tạo nhóm CNTTK21-25 (riêng tư)
        for ($i = 21; $i <= 25; $i++) {
            $title = "CNTTK{$i}";
            $slug = Str::slug($title);
            $originalSlug = $slug;
            $counter = 1;

            while (Group::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter++;
            }

            $photo = $faker->randomElement($onlineImages);
            $coverPhoto = $faker->randomElement($onlineImages);

            $group = Group::create([
                'title' => $title,
                'slug' => $slug,
                'description' => $faker->paragraph(3),
                'type_code' => $typeCode,
                'author_id' => 1,
                
                'is_private' => true,
                'pending_members' => json_encode([]),
                'members' => json_encode([1]),
                'moderators' => json_encode([]),
                'status' => 'active',
                'photo' => $photo,
                'cover_photo' => $coverPhoto,
            ]);

            $this->createPageForGroup($group);
        }

        // Tạo 5 nhóm công khai
        $groupNames = [
            'Lập trình viên Python',
            'Cộng đồng Laravel Việt Nam',
            'Chia sẻ kiến thức Web Development',
            'Data Science Việt Nam',
            'Mobile App Development'
        ];

        foreach ($groupNames as $name) {
            $title = $name;
            $slug = Str::slug($title);
            $originalSlug = $slug;
            $counter = 1;

            while (Group::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter++;
            }

            $photo = $faker->randomElement($onlineImages);
            $coverPhoto = $faker->randomElement($onlineImages);

            $memberCount = $faker->numberBetween(5, 15);
            $members = [1];

            while (count($members) < $memberCount) {
                $members[] = $faker->numberBetween(2, 50);
                $members = array_unique($members);
            }

            $availableModerators = array_values(array_diff($members, [1]));
            shuffle($availableModerators);
            $moderatorCount = min($faker->numberBetween(1, 3), count($availableModerators));
            $moderators = array_slice($availableModerators, 0, $moderatorCount);

            $group = Group::create([
                'title' => $title,
                'slug' => $slug,
                'description' => $faker->paragraph(3),
                'type_code' => $typeCode,
                'author_id' => 1,
                
                'is_private' => false,
                'pending_members' => json_encode([]),
                'members' => json_encode($members),
                'moderators' => json_encode($moderators),
                'status' => 'active',
                'photo' => $photo,
                'cover_photo' => $coverPhoto,
            ]);

            $this->createPageForGroup($group);
        }
    }

    private function createPageForGroup($group)
    {
        $slug = $group->slug;

        if (!TPage::where('slug', $slug)->exists()) {
            TPage::create([
                'item_id' => $group->id,
                'item_code' => 'group',
                'title' => $group->title,
                'slug' => $slug,
                'description' => $group->description,
                'banner' => $group->cover_photo ?? $group->photo,
                'avatar' => $group->photo,
                'status' => 'active'
            ]);
        }
    }
}
