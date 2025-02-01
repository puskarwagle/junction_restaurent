<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SiteSettings;

class SiteSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define the site settings data
        $settings = [
            [
                'key' => 'site_name',
                'value' => 'My Awesome Site',
            ],
            [
                'key' => 'site_description',
                'value' => 'This is a description of my awesome site.',
            ],
            [
                'key' => 'site_email',
                'value' => 'admin@example.com',
            ],
            [
                'key' => 'site_phone',
                'value' => '+1234567890',
            ],
            [
                'key' => 'site_address',
                'value' => '123 Main St, City, Country',
            ],
            [
                'key' => 'site_facebook',
                'value' => 'https://facebook.com/mysite',
            ],
            [
                'key' => 'site_twitter',
                'value' => 'https://twitter.com/mysite',
            ],
            [
                'key' => 'site_instagram',
                'value' => 'https://instagram.com/mysite',
            ],
            [
                'key' => 'site_linkedin',
                'value' => 'https://linkedin.com/company/mysite',
            ],
            [
                'key' => 'site_youtube',
                'value' => 'https://youtube.com/channel/mysite',
            ],
            [
                'key' => 'site_logo',
                'value' => 'assets/images/logo.png',
            ],
            [
                'key' => 'site_favicon',
                'value' => 'assets/images/favicon.ico',
            ],
            [
                'key' => 'admin_username',
                'value' => 'admin',
            ],
            [
                'key' => 'admin_password',
                'value' => 'password123',
            ],
            [
                'key' => 'site_timezone',
                'value' => 'UTC',
            ],
            [
                'key' => 'site_language',
                'value' => 'en',
            ],
            [
                'key' => 'site_currency',
                'value' => 'USD',
            ],
            [
                'key' => 'site_currency_symbol',
                'value' => '$',
            ],
            [
                'key' => 'site_footer_text',
                'value' => '© 2025 My Awesome Site. All rights reserved.',
            ],
            [
                'key' => 'site_google_analytics',
                'value' => 'UA-XXXXX-Y',
            ],
            [
                'key' => 'site_seo_title',
                'value' => 'My Awesome Site - Best in the Business',
            ],
            [
                'key' => 'site_seo_description',
                'value' => 'Visit My Awesome Site for top-notch services and products.',
            ],
            [
                'key' => 'site_seo_keywords',
                'value' => 'awesome, site, business, products, services',
            ],
            [
                'key' => 'site_ssl_enabled',
                'value' => 'true',
            ],
            [
                'key' => 'site_maintenance_mode',
                'value' => 'false',
            ],
            [
                'key' => 'site_contact_email',
                'value' => 'contact@example.com',
            ],
            [
                'key' => 'site_terms_of_service',
                'value' => 'https://mysite.com/terms',
            ],
            [
                'key' => 'site_privacy_policy',
                'value' => 'https://mysite.com/privacy',
            ],
            [
                'key' => 'site_about_us',
                'value' => 'We are the best in the business with years of experience.',
            ],
            [
                'key' => 'site_support_email',
                'value' => 'support@example.com',
            ],
            [
                'key' => 'site_support_phone',
                'value' => '+0987654321',
            ],
            [
                'key' => 'site_hours_of_operation',
                'value' => 'Mon-Fri 9 AM - 6 PM',
            ],
            [
                'key' => 'site_terms_and_conditions',
                'value' => 'https://mysite.com/terms-and-conditions',
            ],
            [
                'key' => 'site_shipping_policy',
                'value' => 'https://mysite.com/shipping-policy',
            ],
            [
                'key' => 'site_refund_policy',
                'value' => 'https://mysite.com/refund-policy',
            ],
            [
                'key' => 'site_payment_methods',
                'value' => 'Credit Card, PayPal, Bank Transfer',
            ],
            [
                'key' => 'site_payment_gateway',
                'value' => 'Stripe',
            ],
            [
                'key' => 'site_default_language',
                'value' => 'English',
            ],
            [
                'key' => 'site_default_country',
                'value' => 'US',
            ],
            [
                'key' => 'site_contact_form_email',
                'value' => 'contactform@example.com',
            ],
            [
                'key' => 'site_smtp_host',
                'value' => 'smtp.example.com',
            ],
            [
                'key' => 'site_smtp_username',
                'value' => 'smtpuser',
            ],
            [
                'key' => 'site_smtp_password',
                'value' => 'smtppassword',
            ],
            [
                'key' => 'site_smtp_port',
                'value' => '587',
            ],
            [
                'key' => 'site_smtp_encryption',
                'value' => 'tls',
            ],
            [
                'key' => 'site_backup_frequency',
                'value' => 'daily',
            ],
        ];        

        // Insert the data into the site_settings table
        foreach ($settings as $setting) {
            SiteSettings::create($setting);
        }

        $this->command->info('Site settings seeded successfully!');
    }
}