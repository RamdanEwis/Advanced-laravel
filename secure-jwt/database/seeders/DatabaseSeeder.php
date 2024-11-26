<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 🧑‍💻 Create a new user
        $user = User::where('email','devramdanewis@gamil.com')->firstorfail();
        if (!$user){
            $user = User::create([
                'name' => 'Test User',
                'email' => 'devramdanewis@gamil.com',
                'password' => bcrypt('12345678'),
                ]);  // 🔒 Make sure to hash the password
        }

        // 📝 Create 10,000 posts for the new user
        Post::factory(10000)->create([
            'user_id' => $user->id,
        ]);

        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
