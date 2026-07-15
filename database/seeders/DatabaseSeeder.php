<?php

namespace Database\Seeders;

use App\Models\GalleryPhoto;
use App\Models\Member;
use App\Models\News;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Copy seed data images to storage
        $this->copySeedImages();

        // ─── Admin User ───────────────────────────────────────
        $defaultPassword = env('ADMIN_DEFAULT_PASSWORD', 'R0w0s4r1-3R-Adm!n-2024');
        $user = User::updateOrCreate(
            ['email' => 'admin@rowosari3r.com'],
            [
                'name' => 'Admin Rowosari',
                'password' => Hash::make($defaultPassword),
            ]
        );

        // ─── Site Settings ────────────────────────────────────
        $settings = [
            ['key' => 'hero_headline', 'value' => 'Rowosari Bersih, Rowosari Berdaya', 'type' => 'text'],
            ['key' => 'hero_subheadline', 'value' => 'Edukasi dan Inovasi Pengelolaan Sampah Menjadi Produk Bernilai Ekonomis Berbasis 3R dan Partisipasi Masyarakat', 'type' => 'text'],
            ['key' => 'hero_video_url', 'value' => null, 'type' => 'url'],
            ['key' => 'profil_video_url', 'value' => 'https://drive.google.com/file/d/1s88wPzc3jxTojY2Qmph_U_FnEvgbspOq/view?usp=sharing', 'type' => 'url'],
            ['key' => 'tentang_text', 'value' => 'Program KKN Kelurahan Rowosari berfokus pada pengelolaan sampah berbasis 3R (Reduce, Reuse, Recycle). Kami mengajak warga untuk mengolah sampah menjadi produk bernilai ekonomis, menciptakan lingkungan yang lebih bersih, dan membangun ekonomi kreatif berbasis daur ulang.', 'type' => 'textarea'],
            ['key' => 'stat_sampah', 'value' => '500', 'type' => 'text'],
            ['key' => 'stat_produk', 'value' => '4', 'type' => 'text'],
            ['key' => 'stat_warga', 'value' => '150', 'type' => 'text'],
            ['key' => 'contact_address', 'value' => 'Kelurahan Rowosari, Kecamatan Tembalang, Kota Semarang, Jawa Tengah', 'type' => 'text'],
            ['key' => 'contact_email', 'value' => 'kkn.rowosari3r@gmail.com', 'type' => 'text'],
            ['key' => 'contact_instagram', 'value' => '@kknt.rowosari', 'type' => 'text'],
            ['key' => 'contact_phone', 'value' => '+62 812-1234-5678', 'type' => 'text'],
            ['key' => 'tentang_image', 'value' => 'settings/damsCvatixIh2ZYhQErIfdFLjPM2Nu0iLzgoAY76.jpg', 'type' => 'image'],
        ];

        foreach ($settings as $setting) {
            SiteSetting::updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value'], 'type' => $setting['type']]
            );
        }

        // ─── Products ─────────────────────────────────────────
        $products = [
            [
                'name' => 'Kompos Organik Premium',
                'slug' => 'kompos-organik-premium',
                'description' => 'Kompos berkualitas tinggi dari sampah organik rumah tangga warga Kelurahan Rowosari. Kaya nutrisi untuk tanaman hias dan sayuran. Dikemas dalam kemasan 5kg yang ramah lingkungan.',
                'category' => 'organik',
                'price' => 150000,
                'shopee_link' => 'https://shopee.co.id/Premium-Vermi-Kompos-5Kg-Tanpa-Campuran-Kascing-Pupuk-Organik-Vermicompost-Sustain-Agro-i.1447930444.42524866635',
                'model_type' => 'kompos',
                'waste_composition' => [
                    ['name' => 'Sisa Makanan', 'percentage' => 45, 'color' => '#8B4513'],
                    ['name' => 'Daun & Ranting', 'percentage' => 30, 'color' => '#228B22'],
                    ['name' => 'Serbuk Gergaji', 'percentage' => 15, 'color' => '#DEB887'],
                    ['name' => 'Sekam Padi', 'percentage' => 10, 'color' => '#F5DEB3'],
                ],
                'image' => 'products/AVzE7c6bOc2zwurnXpe4uIcSwVxsAKnnW98oDVQM.jpg',
            ],
            [
                'name' => 'Eco Terrazzo',
                'slug' => 'eco-terrazzo',
                'description' => 'Ubin terrazzo ramah lingkungan yang dibuat dari pecahan kaca, keramik, dan plastik daur ulang. Cocok sebagai alas pot, coaster, atau dekorasi meja. Setiap keping unik dengan pattern berbeda.',
                'category' => 'kriya',
                'price' => 55000,
                'model_type' => 'terrazzo',
                'waste_composition' => [
                    ['name' => 'Pecahan Kaca', 'percentage' => 35, 'color' => '#87CEEB'],
                    ['name' => 'Pecahan Keramik', 'percentage' => 25, 'color' => '#CD853F'],
                    ['name' => 'Semen Daur Ulang', 'percentage' => 25, 'color' => '#808080'],
                    ['name' => 'Plastik Cacah', 'percentage' => 15, 'color' => '#FF6347'],
                ],
                'image' => 'products/FRJClhO6efm30vOWNcM8zG8oeppCEdhzFnynIeNF.jpg',
            ],
            [
                'name' => 'Keychain Eco 3R',
                'slug' => 'keychain-eco-3r',
                'description' => 'Gantungan kunci unik dari resin daur ulang dengan embedded material sampah berwarna-warni. Dilengkapi simbol recycle 3R sebagai pengingat untuk menjaga lingkungan. Setiap keychain adalah satu-satunya di dunia!',
                'category' => 'kriya',
                'price' => 20000,
                'model_type' => 'keychain',
                'waste_composition' => [
                    ['name' => 'Resin Daur Ulang', 'percentage' => 50, 'color' => '#DDA0DD'],
                    ['name' => 'Tutup Botol', 'percentage' => 25, 'color' => '#4169E1'],
                    ['name' => 'Potongan Kain', 'percentage' => 15, 'color' => '#FF69B4'],
                    ['name' => 'Ring Logam Bekas', 'percentage' => 10, 'color' => '#C0C0C0'],
                ],
                'image' => 'products/1GxR1zjxMXi9dzsqBNgl08ib40cMLjD6YIQqBtXo.jpg',
            ],
        ];

        foreach ($products as $productData) {
            $imagePath = $productData['image'];
            unset($productData['image']);

            $product = Product::updateOrCreate(
                ['slug' => $productData['slug']],
                $productData
            );

            // Create product image if not exists
            if ($imagePath) {
                ProductImage::updateOrCreate(
                    ['product_id' => $product->id, 'image_path' => $imagePath],
                    ['is_primary' => true, 'sort_order' => 0]
                );
            }
        }

        // ─── Members (Struktur Organisasi) ────────────────────
        $members = [
            ['name' => 'Prof. Dr. Widowati, S.Si., M.Si.', 'role' => 'DPL', 'division' => null, 'image_path' => 'members/0R9N5WGa7KGeR5gfvxatx3oXNwUSsYQOsGDzM7b3.jpg'],
            ['name' => 'Muhammad Athhar Aria Suwardi', 'role' => 'Koordes', 'division' => null, 'image_path' => 'members/5QNXWoM9YZoIfBT8Pw2p4WbRwAnPb4TylJUTFh1r.jpg'],
            ['name' => 'Afifah Kamiliya', 'role' => 'Wakoordes', 'division' => null, 'image_path' => 'members/zb5npZSGkP9pB5A1YS1OhCZ8qlZKlEFRJPwfNmPt.webp'],
            ['name' => 'Fahma Salsabilla Rohmah', 'role' => 'Sekretaris', 'division' => null, 'image_path' => 'members/kUZwRp6JjDo0b46aGDLkZvlGmM8qjMEXwRaxurVh.png'],
            ['name' => 'Yeni Diana Rifqhi', 'role' => 'Sekretaris', 'division' => null, 'image_path' => 'members/mlXFenB7K4KKFFjTfMyjPYB4epfNSD0flk4wD0VM.png'],
            ['name' => 'Viola Anggita Sunarya', 'role' => 'Bendahara', 'division' => null, 'image_path' => 'members/zIzscFkjYJxdctoaxWg6dAjxAE4AXCLPrloGMS67.jpg'],
            ['name' => 'Josua Maurits H. Lumban Tobing', 'role' => 'Bendahara', 'division' => null, 'image_path' => 'members/Xe1R5UQpsZ1hLg3bA0iukd0yPEkYskgTihdciFrQ.jpg'],
        ];

        foreach ($members as $memberData) {
            Member::updateOrCreate(
                ['name' => $memberData['name']],
                $memberData
            );
        }

        // ─── Gallery Photos ───────────────────────────────────
        $galleryPhotos = [
            ['title' => null, 'image_path' => 'gallery/RE29rJEUOO5csVXeQ8KcQNws2jtuz62XIwEBBji7.jpg', 'sort_order' => 0],
            ['title' => null, 'image_path' => 'gallery/B8OJMxoSfqB4tMksDQs6tdzwLSjKHlBCuN6nyo29.jpg', 'sort_order' => 1],
            ['title' => null, 'image_path' => 'gallery/WdlYdfV6hBcvfoirOHCgluatlMDWOVLLvPpi4xqj.jpg', 'sort_order' => 2],
            ['title' => 'SOWAN RW', 'image_path' => 'gallery/nGh1uvBWufIGi1wgCsgnDslq4tExsqVNEWquvo7x.jpg', 'sort_order' => 3],
            ['title' => 'SOWAN RW', 'image_path' => 'gallery/g0Va06Wnxt8sy8rVYhGwOKMkuDq3o2O1W8K0uVxp.jpg', 'sort_order' => 4],
            ['title' => 'SOWAN RW', 'image_path' => 'gallery/aZqobejnTclJxY68VtaNLr8HwmeS4rvlbx0DniBg.jpg', 'sort_order' => 5],
            ['title' => 'SOWAN RW', 'image_path' => 'gallery/knTxz3PKWmoHbuoBj8liij12ighx6IdCOS67cyLN.jpg', 'sort_order' => 6],
            ['title' => 'SOWAN RW', 'image_path' => 'gallery/vfaTmKc6l3uqCqVOIMn4S7VgB1JJwRcfXjXmCVZk.jpg', 'sort_order' => 7],
            ['title' => 'SOWAN RW', 'image_path' => 'gallery/kvNhAAEs8UrDQq5P4FogzznBRX3GN81X06PVHRkH.jpg', 'sort_order' => 8],
            ['title' => 'SOWAN RW', 'image_path' => 'gallery/K2r6EiASktqlUrjKtYxa9RuN4TGurA9wRVjdZebb.jpg', 'sort_order' => 9],
            ['title' => 'SOWAN RW', 'image_path' => 'gallery/CMw3pZ9GeXclYEnUzc7j5TkMlMeYMvKBeeXQDFpL.jpg', 'sort_order' => 10],
            ['title' => 'SOWAN RW', 'image_path' => 'gallery/XudoSWTPLU18Vs2QQHxhoz6zbgowvmCWZzcYM6pB.jpg', 'sort_order' => 11],
            ['title' => 'SOWAN RW', 'image_path' => 'gallery/AyWgkMSUQSbQodFY4Gp3kyVpIzyuXbbW4ri7xIZO.jpg', 'sort_order' => 12],
            ['title' => null, 'image_path' => 'gallery/ecNaZrCE6GQM3GwLPsX0rl1S9hrPI5bSMqBvT8bm.jpg', 'sort_order' => 13],
            ['title' => null, 'image_path' => 'gallery/l0boQ1FHUmhP9hhUFJcLMrcGKldhDXW7YL0tRXlS.jpg', 'sort_order' => 14],
            ['title' => null, 'image_path' => 'gallery/kO0Wlrta49vIDkAjjiT0iYchbRXmxvIf0zjS1MPa.jpg', 'sort_order' => 15],
            ['title' => null, 'image_path' => 'gallery/4xp8mPEcyZqCH24fJ4AjFlwZQbCdRIP1veZCUsyF.jpg', 'sort_order' => 16],
            ['title' => null, 'image_path' => 'gallery/1yyqOJTDOvHQPXMdgGTPudvUKQRtUz8HSJlitkNB.jpg', 'sort_order' => 17],
            ['title' => null, 'image_path' => 'gallery/3fstsQLimpzJ7R7FVim02Vo0jxJ8Cyfx7ADDIs2o.jpg', 'sort_order' => 18],
            ['title' => null, 'image_path' => 'gallery/6T3qDI9AoD8NGSFRG6ox7kv9Brq0vvyNtgn29CRv.jpg', 'sort_order' => 19],
            ['title' => null, 'image_path' => 'gallery/TkhluuqMcBvziMltZyvzOhbSN8WEQSBGAI96bqZP.jpg', 'sort_order' => 20],
            ['title' => null, 'image_path' => 'gallery/yEF5JHdDyK7cgEk29MSPoqQWOLNTI9FJdGhVDo6o.jpg', 'sort_order' => 21],
            ['title' => null, 'image_path' => 'gallery/83SdBrU76Y8HCmXlS6EU1po56daPaYJ20mSPrGDG.jpg', 'sort_order' => 22],
            ['title' => null, 'image_path' => 'gallery/Ynyr4N08MHuL12DNt9DfpgSHDoSRR3VS61pK3bWi.jpg', 'sort_order' => 23],
            ['title' => null, 'image_path' => 'gallery/RsixVs83dmcQv26UNPTMeiEH6sFr50FpCcf8Rfrd.jpg', 'sort_order' => 24],
            ['title' => null, 'image_path' => 'gallery/DaXV79DmIZjQATArb4tDjeEZ8RqdOh7ZQIy8qdge.jpg', 'sort_order' => 25],
            ['title' => null, 'image_path' => 'gallery/qjWFRgjHPPCKW6UtaFkvPcZ6fIJmIIaVFzIwKHB4.jpg', 'sort_order' => 26],
            ['title' => null, 'image_path' => 'gallery/vXLz9ld2xEYyyWEUgGMLUwxbrDVhGdz1u6YnPFp0.jpg', 'sort_order' => 27],
            ['title' => null, 'image_path' => 'gallery/FeeOGVJK5UGNNDGOCCceiKwcM6I4PHNXXehpXlMH.jpg', 'sort_order' => 28],
            ['title' => null, 'image_path' => 'gallery/46wwSSRdnMSJ37kk4G2hm2mBx8JdI5WVGoAPRf4c.jpg', 'sort_order' => 29],
            ['title' => null, 'image_path' => 'gallery/eZ2btHq46Zvl6uUVdQ7OELlbtHa4zQ0TWE12nCSD.jpg', 'sort_order' => 30],
        ];

        foreach ($galleryPhotos as $photoData) {
            GalleryPhoto::updateOrCreate(
                ['image_path' => $photoData['image_path']],
                $photoData
            );
        }

        // ─── News ─────────────────────────────────────────────
        // Note: News content contains image URLs that reference the APP_URL.
        // The startup script will fix these URLs after deployment.
        $appUrl = rtrim(config('app.url'), '/');
        $newsContent = '<p>Tim Kuliah Kerja Nyata (KKN) Universitas Diponegoro resmi memulai rangkaian program pengabdian masyarakat di Kelurahan Rowosari. Kegiatan ini diawali dengan agenda penerjunan mahasiswa pada Selasa, 7 Juli 2026, yang bertempat di Balai Kelurahan Rowosari. Kehadiran tim mahasiswa disambut dengan hangat dan diterima secara langsung oleh Bapak Lurah beserta jajaran aparat desa, dengan turut didampingi oleh Dosen Pendamping Lapangan (DPL).</p>'
            . '<p><br></p>'
            . '<p>Sebagai langkah awal untuk membaur dan memahami kondisi lingkungan sekitar, tim KKN segera melakukan kegiatan silaturahmi atau <em>sowan</em> kepada para tokoh masyarakat. Pada tanggal 8 Juli 2026, kunjungan dilakukan ke kediaman Ketua RT 02 RW 01, dan dilanjutkan ke kediaman Ketua RT 01 RW 01 pada keesokan harinya, 9 Juli 2026. Kunjungan ini bertujuan untuk memperkenalkan diri, memohon izin pelaksanaan kegiatan, serta berdiskusi langsung mengenai potensi, kebutuhan, dan program yang dapat disinergikan dengan warga setempat.</p>'
            . '<p><img src="' . $appUrl . '/storage/news-content/Lnlc1ELDLa8CR7V7TFSMzIEfTOfd381NGa6WsIgw.jpg" alt="Sowan RT"></p>'
            . '<p><br></p>'
            . '<p>Setelah menjalin komunikasi yang baik dengan warga, tim KKN mulai merealisasikan program kerja fisik pada tanggal 11 Juli 2026 melalui proses pembuatan kolam pupuk yang berlokasi di wilayah RW 02. Kegiatan ini diharapkan dapat menjadi salah satu solusi pengelolaan lingkungan yang bermanfaat bagi produktivitas warga sekitar.</p>'
            . '<p><img src="' . $appUrl . '/storage/news-content/Y2YLOTLEHeu3dEYDvE6lyqCyomUoRDvMfVeFogc9.jpg" alt="Kolam Pupuk"></p>'
            . '<p><br></p>'
            . '<p>Rangkaian kegiatan di minggu pertama ini kemudian ditutup dengan penuh semangat melalui acara Senam Pagi bersama warga RT 02 RW 01 pada hari Minggu, 12 Juli 2026. Antusiasme warga yang hadir menjadikan suasana sangat meriah. Kegiatan ini tidak hanya bermanfaat untuk menjaga kebugaran jasmani, tetapi juga menjadi sarana yang sangat efektif untuk membangun keakraban, kekompakan, dan rasa kekeluargaan antara mahasiswa dan warga Kelurahan Rowosari. Harapannya, sinergi yang positif ini dapat terus terjaga untuk mendukung kelancaran program-program kerja selanjutnya.</p>'
            . '<p><img src="' . $appUrl . '/storage/news-content/8irQS80or6kAmgGpNUUn0uGQNESc2kKz8xdROY1v.jpg" alt="Senam Pagi"></p>';

        News::updateOrCreate(
            ['slug' => 'langkah-awal-mengabdi-rangkaian-kegiatan-minggu-pertama-kkn-di-kelurahan-rowosari'],
            [
                'title' => 'Langkah Awal Mengabdi: Rangkaian Kegiatan Minggu Pertama KKN di Kelurahan Rowosari',
                'slug' => 'langkah-awal-mengabdi-rangkaian-kegiatan-minggu-pertama-kkn-di-kelurahan-rowosari',
                'excerpt' => 'Tim KKN Universitas Diponegoro telah resmi memulai kegiatan pengabdian di Kelurahan Rowosari. Rangkaian kegiatan minggu pertama berjalan lancar, meliputi upacara penerjunan di Balai Kelurahan, silaturahmi dengan tokoh masyarakat setempat, gotong royong pembuatan kolam pupuk, hingga ditutup dengan senam sehat bersama warga untuk mempererat keakraban.',
                'content' => $newsContent,
                'event_date' => '2026-07-15',
                'location' => 'Balai Kelurahan Rowosari',
                'image_path' => 'news/OucgKbEb1TApr0tFRtun8jYt4bAfk3AlNVE16kN0.jpg',
                'author_id' => $user->id,
                'is_published' => true,
                'published_at' => '2026-07-15 21:53:17',
            ]
        );
    }

    /**
     * Copy seed images from database/seeders/data/ to storage/app/public/
     */
    private function copySeedImages(): void
    {
        $seedDataPath = database_path('seeders/data');

        if (!File::isDirectory($seedDataPath)) {
            return;
        }

        $directories = ['products', 'members', 'gallery', 'news', 'news-content', 'settings'];

        foreach ($directories as $dir) {
            $sourcePath = $seedDataPath . '/' . $dir;

            if (!File::isDirectory($sourcePath)) {
                continue;
            }

            // Ensure target directory exists
            $targetPath = storage_path('app/public/' . $dir);
            if (!File::isDirectory($targetPath)) {
                File::makeDirectory($targetPath, 0755, true);
            }

            // Copy all files
            $files = File::files($sourcePath);
            foreach ($files as $file) {
                $targetFile = $targetPath . '/' . $file->getFilename();
                if (!File::exists($targetFile)) {
                    File::copy($file->getRealPath(), $targetFile);
                }
            }
        }
    }
}
