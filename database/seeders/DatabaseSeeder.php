<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        // ⚠️ IMPORTANT: Change this password immediately after first deployment!
        // Run: php artisan tinker → User::first()->update(['password' => Hash::make('YOUR_STRONG_PASSWORD')])
        $defaultPassword = env('ADMIN_DEFAULT_PASSWORD', 'R0w0s4r1-3R-Adm!n-2024');
        User::updateOrCreate(
            ['email' => 'admin@rowosari3r.com'],
            [
                'name' => 'Admin Rowosari',
                'password' => Hash::make($defaultPassword),
            ]
        );

        // Default site settings
        $settings = [
            ['key' => 'hero_headline', 'value' => 'Rowosari Bersih, Rowosari Berdaya', 'type' => 'text'],
            ['key' => 'hero_subheadline', 'value' => 'Program pengelolaan sampah 3R — Reduce, Reuse, Recycle — oleh KKN Undip di Kelurahan Rowosari, Tembalang.', 'type' => 'text'],
            ['key' => 'hero_video_url', 'value' => '', 'type' => 'url'],
            ['key' => 'profil_video_url', 'value' => 'https://www.youtube.com/embed/dQw4w9WgXcQ', 'type' => 'url'],
            ['key' => 'tentang_text', 'value' => 'Program KKN Kelurahan Rowosari berfokus pada pengelolaan sampah berbasis 3R (Reduce, Reuse, Recycle). Kami mengajak warga untuk mengolah sampah menjadi produk bernilai ekonomis, menciptakan lingkungan yang lebih bersih, dan membangun ekonomi kreatif berbasis daur ulang.', 'type' => 'textarea'],
            ['key' => 'stat_sampah', 'value' => '500', 'type' => 'text'],
            ['key' => 'stat_produk', 'value' => '25', 'type' => 'text'],
            ['key' => 'stat_warga', 'value' => '150', 'type' => 'text'],
            ['key' => 'contact_address', 'value' => 'Kelurahan Rowosari, Kecamatan Tembalang, Kota Semarang, Jawa Tengah', 'type' => 'text'],
            ['key' => 'contact_email', 'value' => 'kkn.rowosari3r@gmail.com', 'type' => 'text'],
            ['key' => 'contact_instagram', 'value' => '@kkn.rowosari3r', 'type' => 'text'],
            ['key' => 'contact_phone', 'value' => '+62 812-xxxx-xxxx', 'type' => 'text'],
        ];

        foreach ($settings as $setting) {
            SiteSetting::updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value'], 'type' => $setting['type']]
            );
        }

        // Sample products — 3 main products with 3D models + waste composition
        $products = [
            [
                'name' => 'Kompos Organik Premium',
                'description' => 'Kompos berkualitas tinggi dari sampah organik rumah tangga warga Kelurahan Rowosari. Kaya nutrisi untuk tanaman hias dan sayuran. Dikemas dalam kemasan 5kg yang ramah lingkungan.',
                'category' => 'organik',
                'price' => 20000,
                'model_type' => 'kompos',
                'waste_composition' => [
                    ['name' => 'Sisa Makanan', 'percentage' => 45, 'color' => '#8B4513'],
                    ['name' => 'Daun & Ranting', 'percentage' => 30, 'color' => '#228B22'],
                    ['name' => 'Serbuk Gergaji', 'percentage' => 15, 'color' => '#DEB887'],
                    ['name' => 'Sekam Padi', 'percentage' => 10, 'color' => '#F5DEB3'],
                ],
            ],
            [
                'name' => 'Eco Terrazzo',
                'description' => 'Ubin terrazzo ramah lingkungan yang dibuat dari pecahan kaca, keramik, dan plastik daur ulang. Cocok sebagai alas pot, coaster, atau dekorasi meja. Setiap keping unik dengan pattern berbeda.',
                'category' => 'kriya',
                'price' => 35000,
                'model_type' => 'terrazzo',
                'waste_composition' => [
                    ['name' => 'Pecahan Kaca', 'percentage' => 35, 'color' => '#87CEEB'],
                    ['name' => 'Pecahan Keramik', 'percentage' => 25, 'color' => '#CD853F'],
                    ['name' => 'Semen Daur Ulang', 'percentage' => 25, 'color' => '#808080'],
                    ['name' => 'Plastik Cacah', 'percentage' => 15, 'color' => '#FF6347'],
                ],
            ],
            [
                'name' => 'Keychain Eco 3R',
                'description' => 'Gantungan kunci unik dari resin daur ulang dengan embedded material sampah berwarna-warni. Dilengkapi simbol recycle 3R sebagai pengingat untuk menjaga lingkungan. Setiap keychain adalah satu-satunya di dunia!',
                'category' => 'kriya',
                'price' => 15000,
                'model_type' => 'keychain',
                'waste_composition' => [
                    ['name' => 'Resin Daur Ulang', 'percentage' => 50, 'color' => '#DDA0DD'],
                    ['name' => 'Tutup Botol', 'percentage' => 25, 'color' => '#4169E1'],
                    ['name' => 'Potongan Kain', 'percentage' => 15, 'color' => '#FF69B4'],
                    ['name' => 'Ring Logam Bekas', 'percentage' => 10, 'color' => '#C0C0C0'],
                ],
            ],
            [
                'name' => 'Tas Belanja Daur Ulang',
                'description' => 'Tas belanja cantik dari bahan plastik daur ulang. Kuat, tahan air, dan ramah lingkungan. Cocok untuk belanja harian di pasar atau supermarket.',
                'category' => 'kriya',
                'price' => 35000,
            ],
            [
                'name' => 'Pot Tanaman Eco-Brick',
                'description' => 'Pot tanaman unik yang dibuat dari botol plastik bekas yang dipadatkan (eco-brick). Tahan lama dan membantu mengurangi limbah plastik.',
                'category' => 'kriya',
                'price' => 25000,
            ],
            [
                'name' => 'Pupuk Cair Organik',
                'description' => 'Pupuk cair dari fermentasi sampah organik. Aman untuk semua jenis tanaman. Kemasan 1 liter dengan tutup spray.',
                'category' => 'organik',
                'price' => 15000,
            ],
        ];

        foreach ($products as $productData) {
            Product::updateOrCreate(
                ['name' => $productData['name']],
                $productData
            );
        }
    }
}
