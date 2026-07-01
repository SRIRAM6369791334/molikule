<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogAuthor;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Authors
        $authors = [
            [
                'name' => 'Alex Beniwal',
                'designation' => 'Senior Editor',
                'bio' => 'Automotive enthusiast and senior editor with over 10 years of experience in car reviews and industry analysis.',
                'image_url' => 'blogs/author-1.png'
            ],
            [
                'name' => 'Alex Janson',
                'designation' => 'UI/UX Designer',
                'bio' => 'Specializing in automotive interface design and user experience within modern vehicle systems.',
                'image_url' => 'blogs/author-1.png'
            ],
            [
                'name' => 'Rox Amel',
                'designation' => 'Technical Writer',
                'bio' => 'Expert in vehicle maintenance and mechanical systems documentation.',
                'image_url' => 'blogs/author-1.png'
            ]
        ];

        foreach ($authors as $auth) {
            BlogAuthor::create($auth);
        }

        // 2. Seed Categories
        $categories = [
            'Analytics', 'Service', 'Car Parts', 'Car Audio Systems', 
            'Suspension', 'Car Repair Parts', 'Batteries Power', 
            'Wheels And Tyres', 'Lighting', 'Car Fuel'
        ];

        foreach ($categories as $cat) {
            BlogCategory::create([
                'name' => $cat,
                'slug' => Str::slug($cat),
                'active' => true
            ]);
        }

        // 3. Seed Tags
        $tags = ['Garage', 'Electronics', 'Quality', 'Promotion', 'Lighting', 'Tires', 'Gadgets', 'Envato'];
        foreach ($tags as $tagName) {
            BlogTag::create([
                'name' => $tagName,
                'slug' => Str::slug($tagName)
            ]);
        }

        $analyticsId = BlogCategory::where('name', 'Analytics')->first()->id;
        $serviceId = BlogCategory::where('name', 'Service')->first()->id;
        $carPartsId = BlogCategory::where('name', 'Car Parts')->first()->id;
        
        $beniwalId = BlogAuthor::where('name', 'Alex Beniwal')->first()->id;
        $amelId = BlogAuthor::where('name', 'Rox Amel')->first()->id;

        // 4. Seed Blogs
        $blogs = [
            [
                'category_id' => $analyticsId,
                'author_id' => $beniwalId,
                'title' => '2024 Dodge Durango SRT 392 AlcHEMI Marks the End',
                'slug' => Str::slug('2024 Dodge Durango SRT 392 AlcHEMI Marks the End'),
                'excerpt' => 'The internal structure of a tyre can affect every aspect of its performance.',
                'content' => '<p>The engine is the heart of the vehicle, responsible for converting fuel into mechanical energy. It consists of various components, including cylinders, pistons, crankshaft, camshaft, and more. Generates power by burning fuel and converts it into mechanical energy to drive the vehicle.</p><p>The transmission, often referred to as the gearbox, transfers power from the engine to the wheels. It can be automatic or manual. The suspension system includes shocks, struts, springs, and control arms. It absorbs shocks, maintains tire contact with the road, and ensures a smooth ride. Controls the speed and torque of the vehicle by transmitting power from the engine to the wheels.</p><h3>Reports Highest Third Quarter Sales in Company History</h3><p>it\'s crucial to consider factors such as the shop\'s reputation, the availability of quality parts, pricing, and customer service. Reading reviews and checking the shop\'s policies can provide insights into the overall customer experience.</p><h6>Features :</h6><ul><li>The engine\'s power output is measured in horsepower</li><li>Modern engines often feature technologies such as direct injection </li><li>Some suspension systems offer adjustable features</li><li>Certain rims may have features that make them easier to clean and maintain.</li></ul>',
                'image_url' => 'blogs/ac_vent_foam.png',
                'is_published' => true,
                'published_at' => '2023-03-20 10:00:00',
                'meta_title' => '2024 Dodge Durango SRT 392 AlcHEMI Review',
                'meta_description' => 'Detailed analysis of the 2024 Dodge Durango SRT 392 AlcHEMI marks the end of an era.',
            ],
            [
                'category_id' => $serviceId,
                'author_id' => $amelId,
                'title' => 'Warning lights indicating activation of various systems',
                'slug' => Str::slug('Warning lights indicating activation of various systems'),
                'excerpt' => 'Warning lights in a car\'s dashboard indicate the activation of various systems or alert the driver to',
                'content' => '<p>Warning lights in a car\'s dashboard indicate the activation of various systems or alert the driver to potential issues. Understanding these lights is crucial for maintaining vehicle safety and performance.</p>',
                'image_url' => 'blogs/ceramic_spray.jpg',
                'is_published' => true,
                'published_at' => '2023-03-19 11:00:00',
            ],
            [
                'category_id' => $carPartsId,
                'author_id' => $beniwalId,
                'title' => 'Gooloo Battery Jumpers For Sale at Buy Auto Parts',
                'slug' => Str::slug('Gooloo Battery Jumpers For Sale at Buy Auto Parts'),
                'excerpt' => 'If you have a sticking brake, one wheel that locks up consistently before the others and uneven brake pad',
                'content' => '<p>Gooloo Battery Jumpers are now available at competitive prices. These devices are essential for emergency situations and long road trips.</p>',
                'image_url' => 'blogs/chain_lube.jpg',
                'is_published' => true,
                'published_at' => '2023-03-18 12:00:00',
            ],
        ];

        foreach ($blogs as $blogData) {
            $blog = Blog::create($blogData);
            // Randomly attach 2 tags
            $randomTags = BlogTag::inRandomOrder()->limit(2)->pluck('id');
            $blog->tags()->attach($randomTags);
        }
    }
}
