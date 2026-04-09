<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use App\Models\Company;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $company = Company::create([
            'name' => 'Midevs',
            'address' => 'Boulevard de 1 Europe',
            'phone' => '(021)543154321',
            'email' => 'syambusiness@gmail.com',
            'owner_name' => 'Syamil Gymnastiar Nugroho',
            'owner_mobile' => '085973129741',
            'owner_email' => 'syamilgnugroho@gmail.com',
            'contact_name' => 'gymnas_mil',
            'contact_mobile' => '085973129741',
            'contact_email' => 'kocakbangetdeh@gmail.com',
            'is_active' => true,
        ]);

        $category = Category::create([
            'name' => 'Otomotif'
        ]);

        Product::create([
            'name_en' => 'Productive Devs Book',
            'name_fr' => 'asdafaiefmada',
            'description_en' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Maxime sit optio, perspiciatis cum distinctio ut deserunt quos iure ea quae. Totam vel accusantium debitis nesciunt fugiat, neque, officiis eum laboriosam ea perspiciatis harum quae veniam rerum sit, sunt error enim accusamus saepe ipsa. Reprehenderit excepturi eveniet, magnam laboriosam error exercitationem.',
            'description_fr' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Maxime sit optio, perspiciatis cum distinctio ut deserunt quos iure ea quae. Totam vel accusantium debitis nesciunt fugiat, neque, officiis eum laboriosam ea perspiciatis harum quae veniam rerum sit, sunt error enim accusamus saepe ipsa. Reprehenderit excepturi eveniet, magnam laboriosam error exercitationem.',
            'gtin' => fake()->randomNumber(7, true) + fake()->randomNumber(7, true),
            'brand' => 'Zalor',
            'category_id' => $category->id,
            'company_id' => $company->id,
            'country' => 'United States',
            'gross_weight' => 0.5,
            'net_weight' => 0.4,
            'unit_weight' => 'g',
            'is_hidden' => false,
        ]);
    }
}
