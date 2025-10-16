<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Enums\TagType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            TagType::Color->value => ['Red', 'Blue', 'Black', 'Green'],
            TagType::Size->value => ['S', 'M', 'L', 'XL'],
            TagType::Material->value => ['Cotton', 'Polyester', 'Wool'],
            TagType::Brand->value => ['Nike', 'Adidas', 'Puma'],
            TagType::Style->value => ['Casual', 'Formal', 'Sport'],
        ];

        foreach ($tags as $type => $values) {
            foreach ($values as $name) {
                $tag = Tag::firstOrCreate([
                    'name' => $name,
                    'type' => $type,
                ], [
                    'slug' => Str::slug($name),
                    'status' => 1,
                ]);
            }
        }
    }
}
