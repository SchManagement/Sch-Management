<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $icons = [
            'laravel' => '/images/technologies/laravel.svg',
            'php' => '/images/technologies/php.svg',
            'vue-js' => '/images/technologies/vue-js.svg',
            'mysql' => '/images/technologies/mysql.svg',
            'tailwind-css' => '/images/technologies/tailwind-css.svg',
            'vite' => '/images/technologies/vite.svg',
        ];

        foreach ($icons as $slug => $icon) {
            $existing = DB::table('technologies')->where('slug', $slug)->first();

            if ($existing) {
                if (! $existing->icon) {
                    DB::table('technologies')->where('slug', $slug)->update([
                        'icon' => $icon,
                        'updated_at' => now(),
                    ]);
                }

                continue;
            }

            if ($slug === 'mysql') {
                DB::table('technologies')->insert([
                    'name' => 'MySQL',
                    'slug' => $slug,
                    'icon' => $icon,
                    'website_url' => 'https://www.mysql.com/',
                    'sort_order' => 35,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $section = DB::table('homepage_sections')->where('section_key', 'technology')->first();
        $content = $section ? (json_decode($section->content ?? '{}', true) ?: []) : [];
        $content['eyebrow'] = 'TEKNOLOGI KAMI';
        $content['description'] = 'Kami menggunakan teknologi yang relevan, stabil, dan banyak digunakan untuk mengembangkan website dan sistem digital yang aman, fleksibel, serta mudah dikembangkan.';
        $content['items'] = ['laravel', 'php', 'vue-js', 'mysql', 'tailwind-css', 'vite'];

        if ($section) {
            DB::table('homepage_sections')->where('section_key', 'technology')->update([
                'heading' => 'Teknologi Modern untuk Solusi Anda',
                'content' => json_encode($content, JSON_UNESCAPED_UNICODE),
                'sort_order' => 35,
                'updated_at' => now(),
            ]);

            return;
        }

        DB::table('homepage_sections')->insert([
            'section_key' => 'technology',
            'heading' => 'Teknologi Modern untuk Solusi Anda',
            'content' => json_encode($content, JSON_UNESCAPED_UNICODE),
            'is_enabled' => true,
            'sort_order' => 35,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        // CMS content and catalog entries may have been edited after deployment.
    }
};
