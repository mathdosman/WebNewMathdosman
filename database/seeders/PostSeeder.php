<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $judul = [
            'judul pertama',
            'judul kedua',
            'judul ketiga',
            'judul keempat'
        ];

        foreach($judul as $j){
            $slug = Str::slug($j);
            $slugOri = $slug;
            $count = 1;
            while(Post::where('slug',$slug)->exists()){
                $slug = $slugOri."-".$count;
                $count++;
            }
            Post::create([
                'title'=> $j,
                'slug'=>$slug,
                'description'=>'Deskripsi untuk '. $j,
                'content' => 'Konten untuk '.$j,
                'status'=>'publish',
                'user_id'=>'1'
            ]);
        }
    }
}
