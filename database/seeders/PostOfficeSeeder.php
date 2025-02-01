<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PostOffice;

class PostOfficeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Sample data for the post_office table
        $posts = [
            [
                'title' => 'First Post',
                'excerpt' => 'This is the excerpt for the first post.',
                'body' => 'This is the full content of the first post. It can be as long as needed.',
                'featured' => true,
                'post_image' => 'images/post1.jpg',
            ],
            [
                'title' => 'Second Post',
                'excerpt' => 'This is the excerpt for the second post.',
                'body' => 'This is the full content of the second post. It can be as long as needed.',
                'featured' => false,
                'post_image' => 'images/post2.jpg',
            ],
            [
                'title' => 'Third Post',
                'excerpt' => 'This is the excerpt for the third post.',
                'body' => 'This is the full content of the third post. It can be as long as needed.',
                'featured' => true,
                'post_image' => 'images/post3.jpg',
            ],
        ];

        // Insert the sample data into the post_office table
        foreach ($posts as $post) {
            PostOffice::create($post);
        }
    }
}