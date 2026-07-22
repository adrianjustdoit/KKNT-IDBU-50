<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;

$product = Product::where('slug', 'eco-terrazzo')->first();
if ($product) {
    $product->description = 'Coaster dan ubin terrazzo ramah lingkungan yang terbuat dari matriks resin berkualitas dan cacahan plastik daur ulang. Tahan panas, kokoh, dan berestetika tinggi.';
    $product->waste_composition = [
        ['name' => 'Resin Eco-Matrix', 'percentage' => 60, 'color' => '#d4cdc5'],
        ['name' => 'Plastik Cacah', 'percentage' => 40, 'color' => '#3b5e43'],
    ];
    $product->save();
    echo "Successfully updated Eco Terrazzo waste composition!\n";
} else {
    echo "Product not found.\n";
}
