<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Configuration;
use Illuminate\Support\Carbon;

class ConfigurationSeeder extends Seeder {
    public function run(): void
    {
        $now = Carbon::now();

        $data = [
            ['type' => 'text', 'key' => 'app_name', 'value' => 'School'],
            ['type' => 'dropdown', 'key' => 'page_title_show', 'value' => 'yes'],
            ['type' => 'dropdown', 'key' => 'dark_mode', 'value' => 'yes'],
            ['type' => 'dropdown', 'key' => 'collapse_sidebar', 'value' => 'no'],
            ['type' => 'text', 'key' => 'footer_copyright_title', 'value' => 'School'],
            ['type' => 'text', 'key' => 'footer_copyright_url', 'value' => 'https://example.com/'],
            ['type' => 'file', 'key' => 'app_logo_link', 'value' => '/static_image/full.jpg'],
            ['type' => 'file', 'key' => 'app_favicon_link', 'value' => '/static_image/icon.png'],
            ['type' => 'color', 'key' => 'sidebar_color', 'value' => '#050ab3'],
            ['type' => 'color', 'key' => 'sidebar_hover', 'value' => '#2a3ab7'],
            ['type' => 'color', 'key' => 'btn_success', 'value' => '#0ea20b'],
            ['type' => 'color', 'key' => 'bg_success', 'value' => '#51c205'],
            ['type' => 'color', 'key' => 'btn_primary', 'value' => '#4126a1'],
            ['type' => 'color', 'key' => 'btn_info', 'value' => '#39b2db'],
            ['type' => 'color', 'key' => 'btn_danger', 'value' => '#de1717'],
            ['type' => 'color', 'key' => 'btn_warning', 'value' => '#efc325'],
            ['type' => 'color', 'key' => 'progress_bar', 'value' => '#30b814'],
            ['type' => 'color', 'key' => 'bg_warning', 'value' => '#eed42b'],
            ['type' => 'text', 'key' => 'support_mail', 'value' => 'support@example.com'],
            ['type' => 'text', 'key' => 'support_phone', 'value' => '+8801719018721'],
            ['type' => 'text', 'key' => 'report_mail', 'value' => 'complain@example.com'],
            ['type' => 'text', 'key' => 'report_phone', 'value' => '+8801719018721'],
            ['type' => 'number', 'key' => 'request_time', 'value' => 5],
            ['type' => 'number', 'key' => 'max_file_size', 'value' => 2048],
        ];

        foreach ($data as $item)
        {
            Configuration::updateOrCreate(
                ['key' => $item['key']], // Match by `key` instead of `id`
                [
                    'type' => $item['type'],
                    'value' => $item['value'],
                    'remarks' => null,
                    'status' => 1,
                    'updated_at' => $now,
                    'created_at' => $now, // Safe to reassign for new records
                    'deleted_at' => null,
                ]
            );
        }
    }
}
