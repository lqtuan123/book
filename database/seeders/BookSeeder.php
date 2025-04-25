<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Book\Models\Book;
use App\Modules\Book\Models\BookType;
use App\Modules\Resource\Models\Resource;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Faker\Factory as Faker;

class BookSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('vi_VN');

        // Tạo BookType nếu chưa có
        if (BookType::count() == 0) {
            $types = [
                'Sách giáo khoa',
                'Sách lập trình',
                'Sách công nghệ thông tin',
                'Sách tham khảo'
            ];

            foreach ($types as $type) {
                BookType::create([
                    'title' => $type,
                    'slug' => Str::slug($type),
                    'status' => 'active'
                ]);
            }
        }

        $bookTypeIds = BookType::pluck('id')->toArray();

        $tags = [
            'PHP', 'Laravel', 'Python', 'Java', 'C++', 'JavaScript', 
            'Web Development', 'Mobile Development', 'Database', 'AI', 
            'Machine Learning', 'DevOps', 'Cloud Computing', 'Security'
        ];

        foreach ($tags as $tagName) {
            Tag::firstOrCreate(
                ['title' => $tagName],
                [
                    'slug' => Str::slug($tagName),
                    'status' => 'active'
                ]
            );
        }

        $tagIds = Tag::whereIn('title', $tags)->pluck('id')->toArray();

        $bookInfo = [
             [
                'title' => 'Getting Started with Laravel 4',
                'image' => 'laravel4.jpg',
                'summary' => 'Hướng dẫn cơ bản về Laravel 4 cho người mới bắt đầu',
                'tags' => ['PHP', 'Laravel', 'Web Development']
            ],
            [
                'title' => 'Laravel Starter',
                'image' => 'laravel_start.jpg',
                'summary' => 'Khởi đầu với Laravel framework cho các nhà phát triển Web',
                'tags' => ['PHP', 'Laravel', 'Web Development']
            ],
            [
                'title' => 'Laravel Application Development',
                'image' => 'laravel_app.jpg',
                'summary' => 'Phát triển ứng dụng web chuyên nghiệp với Laravel',
                'tags' => ['PHP', 'Laravel', 'Web Development', 'Database']
            ],
            [
                'title' => 'Beginning ASP.NET 2.0',
                'image' => 'asp2.jpg',
                'summary' => 'Hướng dẫn ASP.NET 2.0 với C# cho người mới bắt đầu',
                'tags' => ['Web Development', 'Database']
            ],
            [
                'title' => 'C# 2008 Databases',
                'image' => 'c#2008.jpg',
                'summary' => 'Làm việc với cơ sở dữ liệu trong C# 2008',
                'tags' => ['Database']
            ],
            [
                'title' => 'Introduction to C# Programming',
                'image' => 'C#.jpg',
                'summary' => 'Nhập môn lập trình C#',
                'tags' => ['Web Development']
            ],
            [
                'title' => 'Beginning Unix',
                'image' => 'begin_unix.jpg',
                'summary' => 'Cơ bản về hệ điều hành Unix',
                'tags' => ['DevOps', 'Security']
            ],
            [
                'title' => 'Beginning Python',
                'image' => 'begin_python.jpg',
                'summary' => 'Nhập môn lập trình Python',
                'tags' => ['Python']
            ],
            [
                'title' => 'Python Scripting For Computational Science',
                'image' => 'py1.jpg',
                'summary' => 'Lập trình Python cho khoa học tính toán',
                'tags' => ['Python', 'AI', 'Machine Learning']
            ],
            [
                'title' => 'Making use of Python',
                'image' => 'py2.jpg',
                'summary' => 'Ứng dụng Python trong thực tế',
                'tags' => ['Python', 'Web Development']
            ],
            [
                'title' => 'Guide To PHP Security',
                'image' => 'php1.png',
                'summary' => 'Hướng dẫn về bảo mật trong PHP',
                'tags' => ['PHP', 'Security', 'Web Development']
            ],
            [
                'title' => 'Mạng máy tính cơ bản',
                'image' => 'mmt.jpg',
                'summary' => 'Giới thiệu về mạng máy tính',
                'tags' => ['Security', 'Cloud Computing']
            ],
            [
                'title' => 'Mạng máy tính nâng cao',
                'image' => 'mmt1.jpg',
                'summary' => 'Kiến thức nâng cao về mạng máy tính',
                'tags' => ['Security', 'Cloud Computing']
            ],
            [
                'title' => 'Lập trình hướng đối tượng với Java',
                'image' => 'java.jpg',
                'summary' => 'Hướng dẫn lập trình hướng đối tượng với Java',
                'tags' => ['Java', 'Mobile Development']
            ],
            [
                'title' => 'C++ Programming Language',
                'image' => 'c++.jpg',
                'summary' => 'Ngôn ngữ lập trình C++',
                'tags' => ['C++']
            ],
            [
                'title' => 'C Programming Fundamentals',
                'image' => 'C.jpg',
                'summary' => 'Những kiến thức cơ bản về ngôn ngữ lập trình C',
                'tags' => ['C++']
            ],
            // ... Thêm các book khác nếu cần
        ];

        $documentPath = public_path('book seeder/file');
        $documentFiles = File::files($documentPath);

        foreach ($bookInfo as $info) {
            $title = $info['title'];
            $slug = Str::slug($title);
            $originalSlug = $slug;
            $counter = 1;

            while (Book::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter++;
            }

            $randomDocuments = collect($documentFiles)->random(rand(1, 3));
            $resourceIds = [];

            foreach ($randomDocuments as $file) {
                $fileName = $file->getFilename();
                $extension = $file->getExtension();
                $mimeType = File::mimeType($file->getPathname());
                $fileSize = $file->getSize();

                $resourceTitle = pathinfo($fileName, PATHINFO_FILENAME);
                $resourceSlug = Str::slug($resourceTitle);
                $originalResourceSlug = $resourceSlug;
                $slugCounter = 1;

                while (Resource::where('slug', $resourceSlug)->exists()) {
                    $resourceSlug = $originalResourceSlug . '-' . $slugCounter++;
                }

                $resource = Resource::create([
                    'title' => $resourceTitle,
                    'slug' => $resourceSlug,
                    'file_name' => $fileName,
                    'file_type' => $mimeType,
                    'file_size' => $fileSize,
                    'url' => 'book seeder/file/' . $fileName,
                    'type_code' => $extension,
                    'link_code' => null,
                    'code' => Str::random(10)
                ]);

                $resourceIds[] = $resource->id;
            }

            $content = $faker->paragraphs(rand(10, 20), true);

            $book = Book::create([
                'title' => $title,
                'slug' => $slug,
                'photo' => '/book seeder/photo/' . $info['image'],
                'summary' => $info['summary'],
                'content' => $content,
                'status' => 'active',
                'user_id' => 1,
                'book_type_id' => $faker->randomElement($bookTypeIds),
                'block' => 'no',
                'views' => $faker->numberBetween(0, 1000),
                'resources' => json_encode([
                    'book_id' => null, // Will be updated after creation
                    'resource_ids' => $resourceIds,
                ])
            ]);
            
            // Update book_id in resources
            $resourcesData = json_decode($book->resources, true);
            $resourcesData['book_id'] = $book->id;
            $book->resources = json_encode($resourcesData);
            $book->save();

            $selectedTagTitles = $info['tags'] ?? $faker->randomElements($tags, rand(1, 5));
            $selectedTagIds = Tag::whereIn('title', $selectedTagTitles)->pluck('id')->toArray();

            foreach ($selectedTagIds as $tagId) {
                DB::table('tag_books')->insert([
                    'book_id' => $book->id,
                    'tag_id' => $tagId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
