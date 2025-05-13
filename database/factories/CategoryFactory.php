<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    /** @var array Track used slugs during factory execution */
    public static array $usedSlugs = [];

    public function definition(): array
    {
        // Generate a base name (2-3 words)
        $baseName = $this->faker->words($this->faker->numberBetween(2, 3), true);

        return [
            'name' => ucfirst($baseName),
            'slug' => $this->generateUniqueSlug($baseName),
            'description' => $this->faker->paragraph(),
            'image' => 'categories/placeholder.jpg',
            'featured' => $this->faker->boolean(20),
            'parent_id' => null,
        ];
    }

    protected function generateUniqueSlug(string $baseName): string
    {
        $baseSlug = Str::slug($baseName);
        $slug = $baseSlug;
        $counter = 1;

        while (in_array($slug, self::$usedSlugs)) {
            $slug = $baseSlug . '-' . $counter++;
        }

        self::$usedSlugs[] = $slug;
        return $slug;
    }

    public function withParent()
    {
        return $this->state(function (array $attributes) {
            return [
                'parent_id' => \App\Models\Category::inRandomOrder()->first()->id,
            ];
        });
    }
}
