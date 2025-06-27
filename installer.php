<?php
/**
 * Greek E-Shop Demo Quick Setup
 */
require_once('wp-load.php');

if (!current_user_can('manage_options')) {
    wp_die('Please login as admin first!');
}

echo '<div style="max-width: 800px; margin: 50px auto; font-family: Arial;">';
echo '<h1>🚀 Greek E-Shop Demo Setup</h1>';

// Check if WooCommerce is active
if (!class_exists('WooCommerce')) {
    echo '<p style="color: red;">❌ WooCommerce is not active! Please activate it first.</p>';
    echo '<a href="' . admin_url('plugins.php') . '">Go to Plugins</a>';
    exit;
}

// Create Categories
echo '<h2>Creating Categories...</h2>';
$categories = [
    'Ηλεκτρονικά' => 'electronics',
    'Μόδα' => 'fashion',
    'Ομορφιά' => 'beauty'
];

$category_ids = [];
foreach ($categories as $name => $slug) {
    $term = wp_insert_term($name, 'product_cat', ['slug' => $slug]);
    if (!is_wp_error($term)) {
        $category_ids[$slug] = $term['term_id'];
        echo "✅ Created: $name<br>";
    }
}

// Create Products
echo '<h2>Creating Products...</h2>';
$products = [
    ['name' => 'iPhone 15 Pro', 'price' => '1299', 'cat' => 'electronics'],
    ['name' => 'Samsung Galaxy S24', 'price' => '999', 'cat' => 'electronics'],
    ['name' => 'iPad Air', 'price' => '699', 'cat' => 'electronics'],
    ['name' => 'MacBook Air M3', 'price' => '1199', 'cat' => 'electronics'],
    ['name' => 'AirPods Pro', 'price' => '279', 'cat' => 'electronics'],
    ['name' => 'Ανδρικό T-Shirt Premium', 'price' => '29.90', 'cat' => 'fashion'],
    ['name' => 'Γυναικείο Φόρεμα', 'price' => '49.90', 'cat' => 'fashion'],
    ['name' => 'Αθλητικά Παπούτσια', 'price' => '89.90', 'cat' => 'fashion'],
    ['name' => 'Τζιν Skinny Fit', 'price' => '59.90', 'cat' => 'fashion'],
    ['name' => 'Δερμάτινη Τσάντα', 'price' => '119.90', 'cat' => 'fashion'],
    ['name' => 'Αντιγηραντική Κρέμα', 'price' => '45.50', 'cat' => 'beauty'],
    ['name' => 'Σετ Μακιγιάζ', 'price' => '79.90', 'cat' => 'beauty'],
    ['name' => 'Άρωμα Floral 50ml', 'price' => '65.00', 'cat' => 'beauty'],
    ['name' => 'Σαμπουάν Θρέψης', 'price' => '12.90', 'cat' => 'beauty'],
    ['name' => 'Serum Βιταμίνης C', 'price' => '35.00', 'cat' => 'beauty']
];

foreach ($products as $data) {
    $product = new WC_Product_Simple();
    $product->set_name($data['name']);
    $product->set_regular_price($data['price']);
    $product->set_status('publish');
    
    if (isset($category_ids[$data['cat']])) {
        $product->set_category_ids([$category_ids[$data['cat']]]);
    }
    
    $product->save();
    echo "✅ Created: {$data['name']}<br>";
}

// Greek Settings
echo '<h2>Applying Greek Settings...</h2>';
update_option('woocommerce_default_country', 'GR');
update_option('woocommerce_currency', 'EUR');
update_option('woocommerce_price_decimal_sep', ',');
update_option('woocommerce_price_thousand_sep', '.');
update_option('woocommerce_currency_pos', 'right_space');

echo '<div style="background: #d4edda; padding: 20px; margin-top: 20px; border-radius: 5px;">';
echo '<h3>✅ Setup Complete!</h3>';
echo '<p><a href="' . admin_url('edit.php?post_type=product') . '" style="margin-right: 10px;">View Products</a>';
echo '<a href="' . home_url('/shop/') . '">View Shop</a></p>';
echo '</div>';
echo '</div>';
?>
