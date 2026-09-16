<?php

namespace Database\Seeders;

use App\Models\ToolOverride;
use Illuminate\Database\Seeder;

class ToolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $allTools = config('tools.list', []);

        foreach ($allTools as $slug => $tool) {
            ToolOverride::updateOrCreate(
                ['slug' => $slug],
                [
                    'is_active' => true,
                    'custom_title' => $tool['title'],
                    'custom_badge' => $tool['badge'] ?? 'Tiện ích',
                    'custom_desc' => $tool['short_desc'] ?? '',
                ]
            );
        }
    }
}
