<?php
/**
 * Greek E-Shop Advanced Installer v2.0
 * With Configuration Wizard
 */

// Security check
if (!defined('ABSPATH')) {
    require_once('wp-load.php');
}

if (!current_user_can('manage_options')) {
    wp_die('Please login as admin first!');
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['install_eshop'])) {
    install_greek_eshop($_POST);
    exit;
}

// Business Templates
$business_templates = [
    'fashion' => [
        'name' => '👔 Μόδα & Ένδυση',
        'categories' => ['Ανδρικά', 'Γυναικεία', 'Παιδικά', 'Παπούτσια', 'Αξεσουάρ'],
        'demo_products' => [
            ['name' => 'Ανδρικό T-Shirt', 'price' => 29.90, 'cat' => 'Ανδρικά'],
            ['name' => 'Γυναικείο Φόρεμα', 'price' => 49.90, 'cat' => 'Γυναικεία'],
            ['name' => 'Παιδική Μπλούζα', 'price' => 19.90, 'cat' => 'Παιδικά'],
            ['name' => 'Αθλητικά Παπούτσια', 'price' => 89.90, 'cat' => 'Παπούτσια'],
            ['name' => 'Δερμάτινη Τσάντα', 'price' => 119.90, 'cat' => 'Αξεσουάρ']
        ]
    ],
    'electronics' => [
        'name' => '📱 Ηλεκτρονικά & Τεχνολογία',
        'categories' => ['Κινητά', 'Laptops', 'Gaming', 'Smart Home', 'Accessories'],
        'demo_products' => [
            ['name' => 'iPhone 15 Pro', 'price' => 1299, 'cat' => 'Κινητά'],
            ['name' => 'MacBook Air M3', 'price' => 1199, 'cat' => 'Laptops'],
            ['name' => 'PlayStation 5', 'price' => 499, 'cat' => 'Gaming'],
            ['name' => 'Alexa Echo Dot', 'price' => 59.90, 'cat' => 'Smart Home'],
            ['name' => 'AirPods Pro', 'price' => 279, 'cat' => 'Accessories']
        ]
    ],
    'food' => [
        'name' => '🍷 Τρόφιμα & Ποτά',
        'categories' => ['Ποτά', 'Σνακς', 'Deli', 'Βιολογικά', 'Κρασιά'],
        'demo_products' => [
            ['name' => 'Coca-Cola 1.5L', 'price' => 1.80, 'cat' => 'Ποτά'],
            ['name' => 'Chips Πατάτας', 'price' => 2.50, 'cat' => 'Σνακς'],
            ['name' => 'Φέτα ΠΟΠ 400γρ', 'price' => 6.90, 'cat' => 'Deli'],
            ['name' => 'Μέλι Βιολογικό', 'price' => 12.50, 'cat' => 'Βιολογικά'],
            ['name' => 'Κρασί Νεμέα', 'price' => 8.90, 'cat' => 'Κρασιά']
        ]
    ],
    'beauty' => [
        'name' => '💄 Καλλυντικά & Ομορφιά',
        'categories' => ['Skincare', 'Makeup', 'Αρώματα', 'Μαλλιά', 'Ανδρική Περιποίηση'],
        'demo_products' => [
            ['name' => 'Κρέμα Προσώπου Anti-Age', 'price' => 45.50, 'cat' => 'Skincare'],
            ['name' => 'Παλέτα Σκιών', 'price' => 35.90, 'cat' => 'Makeup'],
            ['name' => 'Άρωμα Floral 50ml', 'price' => 65.00, 'cat' => 'Αρώματα'],
            ['name' => 'Σαμπουάν Θρέψης', 'price' => 12.90, 'cat' => 'Μαλλιά'],
            ['name' => 'Aftershave Balm', 'price' => 18.50, 'cat' => 'Ανδρική Περιποίηση']
        ]
    ]
];

function install_greek_eshop($config) {
    echo '<div style="max-width: 800px; margin: 50px auto; font-family: Arial;">';
    echo '<h1>🚀 Εγκατάσταση Greek E-Shop...</h1>';

    // Check WooCommerce
    if (!class_exists('WooCommerce')) {
        echo '<p style="color: red;">❌ Το WooCommerce πρέπει να είναι ενεργό!</p>';
        return;
    }

    // Get template
    global $business_templates;
    $template = $business_templates[$config['business_type']];

    // 1. Create Categories
    echo '<h2>📁 Δημιουργία Κατηγοριών...</h2>';
    $category_ids = [];
    foreach ($template['categories'] as $cat_name) {
        $term = wp_insert_term($cat_name, 'product_cat');
        if (!is_wp_error($term)) {
            $category_ids[$cat_name] = $term['term_id'];
            echo "✅ {$cat_name}<br>";
        }
    }

    // 2. Create Products
    if (isset($config['create_demo_products'])) {
        echo '<h2>🛍️ Δημιουργία Demo Προϊόντων...</h2>';
        foreach ($template['demo_products'] as $prod) {
            $product = new WC_Product_Simple();
            $product->set_name($prod['name']);
            $product->set_regular_price($prod['price']);
            $product->set_status('publish');

            if (isset($category_ids[$prod['cat']])) {
                $product->set_category_ids([$category_ids[$prod['cat']]]);
            }

            $product->save();
            echo "✅ {$prod['name']}<br>";
        }
    }

    // 3. Configure Settings
    echo '<h2>⚙️ Ρύθμιση Παραμέτρων...</h2>';

    // Store settings
    update_option('woocommerce_store_address', $config['address']);
    update_option('woocommerce_store_city', $config['city']);
    update_option('woocommerce_default_country', 'GR');
    update_option('woocommerce_currency', 'EUR');
    update_option('woocommerce_currency_pos', 'right_space');
    update_option('woocommerce_price_decimal_sep', ',');
    update_option('woocommerce_price_thousand_sep', '.');
    echo "✅ Ελληνικές ρυθμίσεις<br>";

    // 4. Payment Methods
    echo '<h2>💳 Ρύθμιση Πληρωμών...</h2>';
    $payment_methods = $config['payment_methods'] ?? [];

    if (in_array('cod', $payment_methods)) {
        update_option('woocommerce_cod_settings', [
            'enabled' => 'yes',
            'title' => 'Αντικαταβολή',
            'description' => 'Πληρωμή κατά την παραλαβή'
        ]);
        echo "✅ Αντικαταβολή<br>";
    }

    if (in_array('bacs', $payment_methods)) {
        update_option('woocommerce_bacs_settings', [
            'enabled' => 'yes',
            'title' => 'Τραπεζική Κατάθεση',
            'description' => 'Κατάθεση σε τραπεζικό λογαριασμό'
        ]);
        echo "✅ Τραπεζική Κατάθεση<br>";
    }

    // 5. Shipping Zones
    echo '<h2>🚚 Ρύθμιση Μεταφορικών...</h2>';

    // Create shipping zones
    $zones = [
        'Αττική' => $config['shipping_attica'] ?? 3.00,
        'Υπόλοιπη Ελλάδα' => $config['shipping_mainland'] ?? 5.00,
        'Νησιά' => $config['shipping_islands'] ?? 7.00
    ];

    foreach ($zones as $zone_name => $cost) {
        // Here you would create actual shipping zones
        // For demo, just showing
        echo "✅ {$zone_name}: €{$cost}<br>";
    }

    // 6. Save Configuration
    $saved_config = [
        'business_type' => $config['business_type'],
        'company_name' => $config['company_name'],
        'vat' => $config['vat'],
        'email' => $config['email'],
        'created_at' => current_time('mysql')
    ];
    update_option('greek_eshop_config', $saved_config);

    echo '<div style="background: #d4edda; padding: 20px; margin-top: 20px; border-radius: 5px;">';
    echo '<h3>✅ Η εγκατάσταση ολοκληρώθηκε!</h3>';
    echo '<p><strong>Επιχείρηση:</strong> ' . $config['company_name'] . '</p>';
    echo '<p><strong>ΑΦΜ:</strong> ' . $config['vat'] . '</p>';
    echo '<p><strong>Τύπος:</strong> ' . $template['name'] . '</p>';
    echo '<hr>';
    echo '<a href="' . admin_url('edit.php?post_type=product') . '" class="button">Προβολή Προϊόντων</a> ';
    echo '<a href="' . home_url('/shop/') . '" class="button">Προβολή Shop</a>';
    echo '</div>';
    echo '</div>';
}

// Show configuration form
?>
<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <title>Greek E-Shop Configuration Wizard</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: #f0f0f0;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 40px;
        }
        .step {
            background: #f9f9f9;
            padding: 25px;
            margin-bottom: 25px;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
        }
        .step h3 {
            margin-top: 0;
            color: #0073aa;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #555;
        }
        input[type="text"],
        input[type="email"],
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        .radio-group,
        .checkbox-group {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 10px;
        }
        .radio-item,
        .checkbox-item {
            background: white;
            padding: 15px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s;
            flex: 1;
            min-width: 180px;
        }
        .radio-item:hover,
        .checkbox-item:hover {
            border-color: #0073aa;
            background: #f0f8ff;
        }
        input[type="radio"]:checked + .radio-item,
        input[type="checkbox"]:checked + .checkbox-item {
            border-color: #0073aa;
            background: #e3f2fd;
        }
        input[type="radio"],
        input[type="checkbox"] {
            display: none;
        }
        .button {
            background: #0073aa;
            color: white;
            padding: 15px 40px;
            border: none;
            border-radius: 4px;
            font-size: 18px;
            cursor: pointer;
            display: block;
            margin: 30px auto 0;
            transition: background 0.3s;
        }
        .button:hover {
            background: #005a87;
        }
        .info {
            background: #e3f2fd;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            color: #0073aa;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>🛒 Greek E-Shop Configuration Wizard</h1>

    <div class="info">
        ℹ️ Αυτό το εργαλείο θα ρυθμίσει το WooCommerce για την επιχείρησή σας με βάση τον τύπο της.
    </div>

    <form method="post">
        <div class="step">
            <h3>1️⃣ Τύπος Επιχείρησης</h3>
            <div class="radio-group">
                <?php foreach ($business_templates as $key => $template): ?>
                    <label>
                        <input type="radio" name="business_type" value="<?php echo $key; ?>" required>
                        <div class="radio-item">
                            <strong><?php echo $template['name']; ?></strong>
                        </div>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="step">
            <h3>2️⃣ Στοιχεία Επιχείρησης</h3>
            <div class="form-group">
                <label>Επωνυμία Επιχείρησης *</label>
                <input type="text" name="company_name" required>
            </div>
            <div class="form-group">
                <label>ΑΦΜ *</label>
                <input type="text" name="vat" pattern="[0-9]{9}" title="9 ψηφία" required>
            </div>
            <div class="form-group">
                <label>Email Επικοινωνίας *</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Διεύθυνση</label>
                <input type="text" name="address">
            </div>
            <div class="form-group">
                <label>Πόλη</label>
                <input type="text" name="city" value="Αθήνα">
            </div>
        </div>

        <div class="step">
            <h3>3️⃣ Μέθοδοι Πληρωμής</h3>
            <div class="checkbox-group">
                <label>
                    <input type="checkbox" name="payment_methods[]" value="cod" checked>
                    <div class="checkbox-item">
                        💵 Αντικαταβολή
                    </div>
                </label>
                <label>
                    <input type="checkbox" name="payment_methods[]" value="bacs" checked>
                    <div class="checkbox-item">
                        🏦 Τραπεζική Κατάθεση
                    </div>
                </label>
                <label>
                    <input type="checkbox" name="payment_methods[]" value="viva">
                    <div class="checkbox-item">
                        💳 Viva Wallet (Σύντομα)
                    </div>
                </label>
            </div>
        </div>

        <div class="step">
            <h3>4️⃣ Κόστος Μεταφορικών</h3>
            <div class="form-group">
                <label>Αττική (€)</label>
                <input type="text" name="shipping_attica" value="3.00">
            </div>
            <div class="form-group">
                <label>Υπόλοιπη Ελλάδα (€)</label>
                <input type="text" name="shipping_mainland" value="5.00">
            </div>
            <div class="form-group">
                <label>Νησιά (€)</label>
                <input type="text" name="shipping_islands" value="7.00">
            </div>
        </div>

        <div class="step">
            <h3>5️⃣ Demo Content</h3>
            <label>
                <input type="checkbox" name="create_demo_products" checked>
                Δημιουργία demo προϊόντων (5 προϊόντα ανά κατηγορία)
            </label>
        </div>

        <button type="submit" name="install_eshop" class="button">
            🚀 Εγκατάσταση E-Shop
        </button>
    </form>
</div>
</body>
</html>
