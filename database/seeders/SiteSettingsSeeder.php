<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SiteSetting;

class SiteSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General
            ['key' => 'brand_name',      'value' => 'Jem Designs & Co.',          'group' => 'general'],
            ['key' => 'tagline',         'value' => 'Heritage, Reimagined',         'group' => 'general'],
            ['key' => 'logo',            'value' => '',                             'group' => 'general'],

            // Contact
            ['key' => 'whatsapp_number', 'value' => '918368873736',                'group' => 'contact'],
            ['key' => 'email',           'value' => '',                             'group' => 'contact'],

            // Social
            ['key' => 'instagram_url',   'value' => 'https://www.instagram.com/jem.designsandco', 'group' => 'social'],
            ['key' => 'facebook_url',    'value' => '',                             'group' => 'social'],

            // Founder
            ['key' => 'founder_name',    'value' => 'Lalringmawii Ralte',          'group' => 'founder'],
            ['key' => 'founder_title',   'value' => 'Founder & Creative Director', 'group' => 'founder'],
            ['key' => 'founder_photo',   'value' => '',                             'group' => 'founder'],
            ['key' => 'founder_quote',   'value' => "I grew up watching my grandmother weave — her hands moving with a precision that no machine could replicate. I realized that if these patterns didn't find a place in modern fashion, they would slowly fade from memory. Jem is my way of making sure that never happens.", 'group' => 'founder'],

            // About
            ['key' => 'brand_story',     'value' => '[BRAND STORY — to be confirmed with client]', 'group' => 'about'],

            // SEO
            ['key' => 'meta_title',       'value' => 'Jem Designs & Co — Heritage, Reimagined', 'group' => 'seo'],
            ['key' => 'meta_description', 'value' => 'Traditional Kuki-Zo tribal weave motifs reimagined for contemporary wardrobes. Handwoven in Northeast India.', 'group' => 'seo'],
            ['key' => 'og_image',         'value' => '',                             'group' => 'seo'],

            // Checkout
            ['key' => 'payment_mode',     'value' => 'whatsapp',                    'group' => 'checkout'],
            ['key' => 'payment_whatsapp', 'value' => '1',                           'group' => 'checkout'],
            ['key' => 'payment_razorpay', 'value' => '0',                           'group' => 'checkout'],

            // WhatsApp Business
            ['key' => 'wa_business_name', 'value' => 'Jem Designs & Co.',          'group' => 'whatsapp'],
            ['key' => 'wa_phone',         'value' => '918368873736',               'group' => 'whatsapp'],
            ['key' => 'wa_greeting',       'value' => 'Hello! 🙏',                 'group' => 'whatsapp'],
            ['key' => 'wa_footer',         'value' => 'Thank you for choosing Jem Designs & Co. — Heritage, Reimagined. 🌿', 'group' => 'whatsapp'],
        ];

        foreach ($settings as $setting) {
            SiteSetting::firstOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value'], 'group' => $setting['group']]
            );
        }
    }
}
