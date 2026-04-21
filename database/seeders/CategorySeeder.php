<?php

namespace Database\Seeders;

use App\Models\BusinessType;
use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Restaurant' => ['Fast Food', 'Casual Dining', 'Fine Dining', 'Cafe', 'Bakery'],
            'Retail Store' => ['Clothing Store', 'Electronics Store', 'Grocery Store', 'Bookstore', 'Furniture Store'],
            'Professional Services' => ['Legal Services', 'Accounting', 'Consulting', 'Marketing Agency', 'Cleaning Services'],
            'Manufacturing' => ['Automobile', 'Food Production', 'Textiles', 'Furniture', 'Chemicals'],
            'Technology Company' => ['Software Development', 'IT Support', 'E-commerce', 'Hardware', 'Tech Consulting'],
            'Healthcare Provider' => ['Clinic', 'Hospital', 'Dental Office', 'Pharmacy', 'Rehab Center'],
            'Education' => ['School', 'University', 'Online Courses', 'Tutoring', 'Vocational Training'],
            'Construction' => ['Builders', 'Renovators', 'Demolition', 'Architecture'],
            'Financial Services' => ['Bank', 'Insurance', 'Investments', 'Accounting', 'FinTech'],
            'Transportation' => ['Taxi', 'Bus', 'Shipping', 'Logistics', 'Courier'],
        ];

        foreach ($categories as $typeName => $categoryList) {
            $type = BusinessType::where('name', $typeName)->first();
            if (! $type) {
                continue;
            }

            foreach ($categoryList as $category) {
                Category::firstOrCreate([
                    'business_type_id' => $type->id,
                    'name' => $category,
                ]);
            }
        }
    }
}
