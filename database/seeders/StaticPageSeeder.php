<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class StaticPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            'about-us' => [
                'en' => 'About Us',
                'ar' => 'عنا',
            ],
            'privacy-policy' => [
                'en' => 'Privacy Policy',
                'ar' => 'سياسة الخصوصية',
            ],
            'return-and-refund' => [
                'en' => 'Return and Refund',
                'ar' => 'سياسة الاسترجاع والاستبدال',
            ],
            'terms-and-conditions' => [
                'en' => 'Terms and Conditions',
                'ar' => 'الشروط والأحكام',
            ],
            'delivery-and-shipping' => [
                'en' => 'Delivery and Shipping',
                'ar' => 'سياسة التوصيل والشحن',
            ],
        ];

        foreach ($pages as $slug => $title) {
            \App\Models\StaticPage::updateOrCreate(
                ['slug' => $slug],
                ['title' => $title]
            );
        }
    }
}
