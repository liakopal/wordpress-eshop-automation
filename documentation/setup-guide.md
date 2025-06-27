# 🚀 Οδηγός Εγκατάστασης WordPress E-Shop Demo

## Προαπαιτούμενα (Δωρεάν Εργαλεία)

### 1. IDE - Visual Studio Code
- Download: https://code.visualstudio.com/
- Εγκατάσταση Extensions:
  ```
  - PHP Intelephense (bmewburn.vscode-intelephense-client)
  - WordPress Snippets (wordpresstoolbox.wordpress-toolbox)
  - Tailwind CSS IntelliSense (bradlc.vscode-tailwindcss)
  - MySQL (cweijan.vscode-mysql-client2)
  ```

### 2. Local Server - Local by Flywheel
- Download: https://localwp.com/
- Δωρεάν για local development
- Περιλαμβάνει: PHP, MySQL, WordPress

### 3. Εναλλακτικά: XAMPP
- Download: https://www.apachefriends.org/
- Περιλαμβάνει: Apache, MySQL, PHP

## 📋 Βήματα Εγκατάστασης

### Βήμα 1: Setup Local Environment

#### Με Local by Flywheel (Προτεινόμενο):
1. Ανοίξτε Local
2. Click "Create a new site"
3. Site name: `greek-eshop-demo`
4. Choose "Preferred" environment
5. Username: `demo`, Password: `demo123`, Email: `demo@example.com`
6. Click "Add Site"

#### Με XAMPP:
1. Start Apache και MySQL
2. Download WordPress: https://el.wordpress.org/
3. Extract στο `htdocs/greek-eshop-demo`
4. Create database: `greek_eshop_demo`
5. Run WordPress installer

### Βήμα 2: Εγκατάσταση WordPress
1. Επισκεφθείτε: `http://greek-eshop-demo.local`
2. Επιλέξτε Ελληνικά
3. Database settings:
   - Database: `greek_eshop_demo`
   - Username: `root` 
   - Password: `root` (για Local) ή κενό (για XAMPP)
   - Host: `localhost`
4. Site settings:
   - Title: Greek E-Shop Demo
   - Username/Password: Αυτά που ορίσατε

### Βήμα 3: Εγκατάσταση Theme & Plugins

#### Από WordPress Admin:
1. **Theme**: Appearance > Themes > Add New
   - Search: "GeneratePress"
   - Install & Activate

2. **WooCommerce**: Plugins > Add New
   - Search: "WooCommerce"
   - Install & Activate
   - Run setup wizard:
     - Country: Greece
     - Currency: Euro (€)
     - Product types: Physical products

3. **Ελληνικές Ρυθμίσεις**:
   - Settings > General:
     - Timezone: Athens
     - Date Format: d/m/Y
     - Time Format: H:i
   
### Βήμα 4: Εκτέλεση Demo Installer

1. Copy το `installer.php` στο WordPress root directory
2. Επισκεφθείτε: `http://greek-eshop-demo.local/installer.php`
3. Ο installer θα:
   - Ρυθμίσει τις ελληνικές παραμέτρους
   - Δημιουργήσει demo κατηγορίες
   - Προσθέσει 15 demo προϊόντα
   - Ρυθμίσει ΦΠΑ και shipping

### Βήμα 5: TailwindCSS Integration

#### Για Demo (CDN Method):
Προσθέστε στο `functions.php` του theme:

```php
function add_tailwind_cdn() {
    echo '<script src="https://cdn.tailwindcss.com"></script>';
}
add_action('wp_head', 'add_tailwind_cdn');
```

#### Για Production (Build Process):
```bash
# Στο theme directory
npm init -y
npm install -D tailwindcss
npx tailwindcss init
```

### Βήμα 6: Import Demo Products

1. Από τον installer ή manually:
2. Products > Import
3. Χρησιμοποιήστε το `products.json`

## 🔧 Ρυθμίσεις WooCommerce για Ελλάδα

### Γενικές Ρυθμίσεις:
- WooCommerce > Settings > General
  - Store Address: Ελλάδα
  - General Options: Enable taxes
  - Currency: Euro (€)
  - Thousand separator: .
  - Decimal separator: ,

### Φόροι (ΦΠΑ):
- WooCommerce > Settings > Tax
  - Standard Rate: 24%
  - Reduced Rate: 13%
  - Zero Rate: 0%

### Μεταφορικά:
- WooCommerce > Settings > Shipping
  - Zone 1: Αττική (Flat rate: €3)
  - Zone 2: Υπόλοιπη Ελλάδα (Flat rate: €5)
  - Zone 3: Νησιά (Flat rate: €7)

### Πληρωμές:
- Enable: Cash on Delivery (Αντικαταβολή)
- Enable: Bank Transfer (Τραπεζική κατάθεση)

## 📁 File Structure

```
greek-eshop-demo/
├── wp-content/
│   ├── themes/
│   │   └── generatepress/
│   │       └── functions.php (add customizations)
│   ├── plugins/
│   │   └── woocommerce/
│   └── uploads/
├── installer.php
└── demo-content/
    └── products.json
```

## 🎨 Customization με TailwindCSS

### Παράδειγμα Custom Product Card:
```html
<div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
    <div class="h-48 bg-gray-200">
        <!-- Product Image -->
    </div>
    <div class="p-4">
        <h3 class="font-bold text-lg mb-2">Όνομα Προϊόντος</h3>
        <p class="text-gray-600 text-sm mb-3">Περιγραφή</p>
        <div class="flex items-center justify-between">
            <span class="text-xl font-bold text-blue-600">€29.90</span>
            <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                Προσθήκη
            </button>
        </div>
    </div>
</div>
```

## 🐛 Troubleshooting

### Πρόβλημα: Ελληνικοί χαρακτήρες δεν εμφανίζονται σωστά
**Λύση**: 
- Database collation: `utf8mb4_unicode_ci`
- wp-config.php: `define('DB_CHARSET', 'utf8mb4');`

### Πρόβλημα: Installer timeout
**Λύση**:
- Αύξηση `max_execution_time` σε php.ini
- Ή run installer σε batches

### Πρόβλημα: Memory limit
**Λύση**:
- wp-config.php: `define('WP_MEMORY_LIMIT', '256M');`

## ✅ Demo Checklist

- [ ] WordPress εγκατεστημένο
- [ ] GeneratePress theme activated
- [ ] WooCommerce configured
- [ ] Greek language pack installed
- [ ] Demo products imported
- [ ] Tax rates configured
- [ ] Shipping zones setup
- [ ] Payment methods enabled
- [ ] TailwindCSS working
- [ ] Mobile responsive test

## 📹 Demo Script

1. Δείξτε το manual setup (2.5 ώρες)
2. Δείξτε το automated setup (15-20 λεπτά)
3. Navigate στο site
4. Δείξτε categories και products
5. Test checkout process
6. Δείξτε admin panel
7. Highlight Greek features

## 🎯 Key Points για Παρουσίαση

1. **Ταχύτητα**: 85% μείωση χρόνου setup
2. **Ελληνική Προσαρμογή**: ΦΠΑ, shipping zones, currency
3. **Mobile Responsive**: Λειτουργεί τέλεια σε κινητά
4. **Δωρεάν Tools**: Μόνο open source
5. **Scalability**: Εύκολη επέκταση

## 📧 Support

Για απορίες σχετικά με το demo:
- Check: `/documentation/README.md`
- Installer issues: Check PHP error logs
- WooCommerce: Official docs

---

**Happy Demo Building! 🚀**
