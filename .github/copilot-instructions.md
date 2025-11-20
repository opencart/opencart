# OpenCart Development Guide for AI Agents

## Project Overview

OpenCart is a PHP-based e-commerce platform using a custom MVC framework. Version 4.x requires **PHP 8.0+** and uses modern PHP features (typed properties, namespaces, etc.).

## Architecture Fundamentals

### MVC-A Pattern (Model-View-Controller-Action)

OpenCart uses a unique **Action-based routing** system instead of traditional MVC:

- **Routes** use dot notation: `product/product` → controller method, `product/product.info` → specific method
- **Action objects** (`Opencart\System\Engine\Action`) wrap route strings and execute controller methods
- **Framework flow**: `index.php` → `startup.php` → `framework.php` → Action dispatch loop

```php
// Route resolution example
'product/product' → Opencart\Catalog\Controller\Product\Product::index()
'product/product.info' → Opencart\Catalog\Controller\Product\Product::info()
```

### Directory Structure

```
upload/                          # Deployable root (web root)
├── catalog/                     # Frontend application
│   ├── controller/             # Catalog controllers
│   ├── model/                  # Catalog models
│   ├── view/                   # Twig templates
│   └── language/               # Translation files
├── admin/                       # Backend admin panel (same structure)
├── extension/                   # Extensions (modules, payment, shipping, etc.)
│   └── opencart/               # Core extensions
│       ├── admin/
│       ├── catalog/
│       └── install.json        # Extension manifest
├── system/                      # Framework core (never modify directly)
│   ├── engine/                 # Core classes (Action, Controller, Loader, etc.)
│   ├── library/                # Services (DB, Cache, Session, Template, etc.)
│   ├── helper/                 # Global functions (oc_* prefix)
│   └── config/                 # Application configs (default.php, catalog.php, admin.php)
└── image/                       # Public image storage
```

**Key principle**: `upload/` is the web root. All code references are relative to this.

### Registry Pattern

The **Registry** (`Opencart\System\Engine\Registry`) is the central service container accessed via magic methods:

```php
class Product extends \Opencart\System\Engine\Controller {
    public function index() {
        // All services accessed via $this->
        $this->load->model('catalog/product');           // Load model
        $this->model_catalog_product->getProduct($id);   // Call model method
        $this->db->query("SELECT ...");                  // Direct DB access
        $this->cache->get('key');                        // Cache service
        $this->config->get('config_name');               // Configuration
        $this->request->get['product_id'];               // Request data
        $this->response->setOutput($html);               // Output response
        $this->session->data['cart'];                    // Session storage
        $this->url->link('product/product', 'id=1');     // URL generation
    }
}
```

**Common registry services**: `load`, `db`, `cache`, `config`, `request`, `response`, `session`, `url`, `document`, `language`, `event`, `log`, `template`

### The Loader System

`$this->load` is the dynamic loading mechanism:

```php
// Model loading (creates $this->model_catalog_product proxy)
$this->load->model('catalog/product');

// Controller loading (returns output or Action)
$output = $this->load->controller('common/header');

// View rendering
$html = $this->load->view('product/product', $data);

// Language loading
$this->load->language('product/product');  // Loads language vars into $this->language

// Helper loading (includes global functions)
$this->load->helper('utf8');
```

**Model proxy pattern**: Models are wrapped in `Opencart\System\Engine\Proxy` objects. Each method call triggers `model/{route}.{method}/before` and `/after` events.

### Event System

Events allow hooking into framework execution at specific points:

```php
// Events are registered in system/config/catalog.php or admin.php
$_['action_event'] = [
    'controller/*/before' => [
        0 => 'event/language.before',  // Priority 0 (executes first)
    ],
    'view/*/after' => [
        500 => 'event/theme.after'
    ]
];

// Event triggers fire at these lifecycle points:
// - controller/{route}/before and /after
// - model/{route}.{method}/before and /after
// - view/{route}/before and /after
```

**Extension integration**: Extensions register events dynamically to modify core behavior without editing core files.

## Development Workflows

### Local Development with Docker

**Primary commands** (uses Docker Compose with custom Makefile):

```bash
make init          # Copy docker/.env.docker.example (first time only)
make build         # Build Docker images (PHP 8.4 by default)
make up            # Start services (apache, php, mysql)
make down          # Stop all services
make php           # Enter PHP container as www-data user
make mysql         # Enter MySQL container
make logs          # Stream all service logs

# Optional services via profiles
make up profiles="adminer redis memcached"
```

**Changing PHP version**: Edit `docker/.env.docker` → `PHP_VERSION=8.2`, then `make build`

**Installation after first `make up`**:
1. Visit `http://localhost`
2. Follow installer (creates `upload/config.php` and `upload/admin/config.php`)
3. Delete `upload/install/` directory

### Code Quality & Testing

```bash
# Lint PHP syntax (all PHP versions)
find upload -name "*.php" ! -path 'upload/system/storage/vendor/*' -exec php -l {} +

# Install dependencies
composer install

# Run PHPStan static analysis (level 6)
php tools/phpstan.phar analyze --no-progress

# Check code style (PHP-CS-Fixer)
php tools/php-cs-fixer.phar fix --dry-run --diff
```

**PHPStan notes**: Uses custom extension (`Tools\PHPStan\RegistryPropertyReflectionExtension`) to understand Registry magic properties.

### Configuration Files

**Never commit** `upload/config.php` or `upload/admin/config.php` (auto-generated by installer). Use `-dist.php` templates.

**Key config locations**:
- `system/config/default.php` - Global defaults (cache, session, DB settings)
- `system/config/catalog.php` - Catalog-specific (action_pre_action, action_event)
- `system/config/admin.php` - Admin-specific settings

**Production checklist**: In `system/config/default.php`, set:
```php
$_['error_display'] = false;  // Hide errors in production
```

## Coding Conventions

### Namespace Structure

```php
// Catalog namespace
namespace Opencart\Catalog\Controller\Product;
namespace Opencart\Catalog\Model\Catalog;

// Admin namespace
namespace Opencart\Admin\Controller\Catalog;
namespace Opencart\Admin\Model\Catalog;

// Extensions namespace
namespace Opencart\Admin\Controller\Extension\Opencart\Payment;

// System namespace
namespace Opencart\System\Engine;
namespace Opencart\System\Library;
```

### Controller Patterns

```php
namespace Opencart\Catalog\Controller\Product;

class Product extends \Opencart\System\Engine\Controller {
    /**
     * Default method (route: product/product)
     */
    public function index(): ?Action {
        $this->load->language('product/product');
        $this->load->model('catalog/product');
        
        $product_id = $this->request->get['product_id'] ?? 0;
        $product_info = $this->model_catalog_product->getProduct($product_id);
        
        if (!$product_info) {
            // Return Action to execute error controller
            return new \Opencart\System\Engine\Action('error/not_found');
        }
        
        $data['product'] = $product_info;
        
        // Render view
        $this->response->setOutput($this->load->view('product/product', $data));
        
        return null;
    }
}
```

**Controller return types**:
- `void` or `null` - Normal execution (output already set)
- `Action` - Redirect to another controller
- `Exception` - Framework catches and routes to error controller

### Model Patterns

```php
namespace Opencart\Catalog\Model\Catalog;

class Product extends \Opencart\System\Engine\Model {
    /**
     * Type-hint return arrays with @return annotation
     * 
     * @return array<string, mixed>
     */
    public function getProduct(int $product_id): array {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product` WHERE `product_id` = '" . (int)$product_id . "'");
        
        return $query->row;  // Single row as array
    }
    
    /**
     * @return array<int, array<string, mixed>>
     */
    public function getProducts(array $data = []): array {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "product` LIMIT 10");
        
        return $query->rows;  // Multiple rows
    }
}
```

**Database conventions**:
- Use `DB_PREFIX` constant for table names
- Always cast integers: `(int)$value`
- Prefer prepared statements or manual escaping for user input

### Helper Functions

Use `oc_*` prefixed global functions from `system/helper/`:

```php
oc_token(32);                    // Generate secure token
oc_strlen($string);              // Multibyte-safe strlen
oc_substr($string, 0, 100);      // Multibyte-safe substr
oc_validate_email($email);       // Email validation
oc_validate_url($url);           // URL validation
```

**When to use**: Load helpers with `$this->load->helper('general')` if autoloading fails.

## Extension Development

### Extension Structure

Extensions follow the same MVC structure but live in `upload/extension/{code}/`:

```
extension/opencart/              # Extension code (e.g., 'opencart', 'myextension')
├── install.json                 # Manifest (name, version, author, link)
├── admin/                       # Admin-side extension code
│   ├── controller/
│   │   └── payment/cod.php     # Extension controllers
│   ├── model/
│   ├── view/
│   └── language/
└── catalog/                     # Customer-side extension code
    └── controller/
        └── payment/cod.php      # Catalog controllers
```

**Extension namespaces**: `Opencart\Admin\Controller\Extension\{Code}\Payment\Cod`

### Creating Extensions

1. Create directory: `upload/extension/myextension/`
2. Add `install.json` manifest
3. Implement MVC structure matching core conventions
4. Register via OpenCart admin interface
5. Extensions can hook into core via event system without modifying core files

### Module-specific guidance

- Follow the [Developing Modules guide](https://docs.opencart.com/en-gb/developer/module/) when structuring admin/catalog directories; each module keeps `controller/`, `model/`, `language/`, `view/` under `admin/` and `catalog/`.
- Admin controllers live in `extension/{code}/admin/controller/module/` and automatically appear on **Extensions → Modules** when their filenames match the module route (e.g., `account.php` for route `module/account`).
- Implement `install()`/`uninstall()` methods on the admin controller to seed/clean extra DB tables or settings; the installer/uninstaller links trigger those methods.
- Views are `.twig` templates under `extension/{code}/admin/view/template/module/`; language keys loaded in the controller become `$data['text_*']` for the template.
- Front-end controllers go under `extension/{code}/catalog/controller/module/` and receive `$settings` when invoked from layouts; their view templates live inside `extension/{code}/catalog/view/template/module/` so reuse core helpers/models (e.g., `catalog/model/catalog/product`) instead of duplicating SQL.
- Use `$this->config->get('module_my_module_status')` and the `$settings` array passed to `index()` to honor stored options; persist admin form submissions with `model_setting_setting->editSetting()` instead of manual SQL.
- Give each controller an `index()` accessible via `/admin/index.php?route=extension/module/my_module&user_token=...`; respond with `$this->response->setOutput($this->load->view(...))` and keep `$this->language->get('text_*')` for UI text.

## Common Pitfalls

1. **Don't modify core files** (`upload/system/`, core controllers/models) - use events or extensions
2. **Magic method resolution**: Methods starting with `__` are blocked for security
3. **Route sanitization**: Routes allow only `a-zA-Z0-9_|/\.` - pipes (`|`) convert to dots (`.`)
4. **Private methods**: Prefix with `_` to prevent direct URL access (e.g., `_validateForm`)
5. **Session persistence**: Use `$this->session->data['key']` - plain `$_SESSION` won't persist
6. **URL generation**: Always use `$this->url->link()` for SEO-friendly URLs

## Database Conventions

- **Table prefix**: Always use `DB_PREFIX` constant
- **Primary keys**: Typically `{table}_id` (e.g., `product_id`, `category_id`)
- **Naming**: Snake_case for tables/columns
- **Boolean fields**: Store as `TINYINT(1)` with values `0`/`1`
- **Timestamps**: Use `DATETIME` fields (e.g., `date_added`, `date_modified`)

## API & CLI Development

**CLI execution**: Check `php_sapi_name() != 'cli'` - framework adjusts headers automatically.

**API routes**: Define in `system/config/catalog.php` or admin.php under `action_pre_action`.

## Version-Specific Notes

- **Current**: 4.1.0.4 (see `upload/index.php`)
- **PHP requirement**: 8.0+ (enforced in `startup.php`)
- **Versioning**: MAJOR.MINOR.FEATURE.PATCH (see README.md)
- **Backward compatibility**: FEATURE versions safe for 3rd party modules; MINOR may break compatibility

## Additional Resources

- PHPDoc API: `docs/api/index.html` (generated via ApiGen)
- Developer docs: `docs/developer/` directory
- GitHub Wiki: https://github.com/opencart/opencart/wiki
- Forum: https://forum.opencart.com
- Coding standards: https://github.com/opencart/opencart/wiki/Coding-standards
