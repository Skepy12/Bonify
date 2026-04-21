<?php

namespace Database\Seeders;

use App\Models\BusinessType;
use Illuminate\Database\Seeder;

class BusinessTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'code' => 'A10',
                'name' => 'Restaurant',
                'description' => 'A business that prepares and serves food and drinks to customers.',
            ],
            [
                'code' => 'A20',
                'name' => 'Retail Store',
                'description' => 'A business that sells goods directly to consumers.',
            ],
            [
                'code' => 'A30',
                'name' => 'Professional Services',
                'description' => 'Businesses providing expert advice and services, such as legal, accounting, or consulting.',
            ],
            [
                'code' => 'A40',
                'name' => 'Healthcare Provider',
                'description' => "Businesses providing medical care, such as doctors' offices, clinics, or hospitals.",
            ],
            [
                'code' => 'A50',
                'name' => 'Technology Company',
                'description' => 'Businesses involved in the development, manufacturing, or sales of technology products and services.',
            ],
            [
                'code' => 'A60',
                'name' => 'Construction',
                'description' => 'Businesses involved in building, renovating, or demolishing structures.',
            ],
            [
                'code' => 'A70',
                'name' => 'Financial Services',
                'description' => 'Businesses providing financial services like banking, insurance, or investment.',
            ],
            [
                'code' => 'A80',
                'name' => 'Education',
                'description' => 'Businesses providing educational services, such as schools, tutoring centers, or online courses.',
            ],
            [
                'code' => 'A90',
                'name' => 'Transportation',
                'description' => 'Businesses providing transportation services, like taxis, buses, or shipping.',
            ],
            [
                'code' => 'B10',
                'name' => 'Manufacturing',
                'description' => 'Businesses involved in the production of goods.',
            ],
        ];

        foreach ($types as $type) {
            BusinessType::firstOrCreate(
                ['code' => $type['code']],
                [
                    'name' => $type['name'],
                    'description' => $type['description'] ?? null,
                ]
            );
        }
    }
}
