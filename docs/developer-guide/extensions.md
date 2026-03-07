# Extensions

### Create your own extensions <a href="#create-your-own-extensions" id="create-your-own-extensions"></a>

OpenCart supports extensions to add or modify functionality without altering core files. Extensions can include modules (e.g., for adding features like sliders or analytics), payment gateways, shipping methods, themes, reports, and more. In OpenCart 4.x, extensions leverage the MVC-L (Model-View-Controller-Language) pattern, the Events system for hooks, and OCMOD for modifications. This guide provides a step-by-step approach to creating, packaging, and installing extensions, focusing on modules as a common example.

### Prerequisites <a href="#prerequisites" id="prerequisites"></a>

* A working installation of OpenCart 4.x.
* Basic knowledge of PHP, HTML, CSS, JavaScript, and the MVC pattern.
* Familiarity with Twig templating (used for views).
* Access to the admin panel and file system (via FTP or server access).
* Tools like a code editor (e.g., VS Code, Notepad++) and ZIP archiver.

Ensure your extension follows OpenCart's naming conventions: filenames should be 1-128 characters, end in `.ocmod.zip` for installable packages, and not exceed 32 MB.

### Types of Extensions <a href="#types-of-extensions" id="types-of-extensions"></a>

Possible extensions types are:

* **Modules**: Add features like banners, carousels, or custom pages.
* **Feeds:** Product feeds (e.g., Google Merchant)
* **Payments/Shipping**: Integrate gateways (e.g., PayPal) or methods (e.g., UPS).
* **Themes**: Customize the storefront appearance.
* **Reports/Analytics**: Generate insights or integrate tools like Google Analytics.
* **Others**: Anti-fraud, captcha, order totals, etc.

All extensions are managed via the admin panel under **Extensions > Extensions**.

### Directory Structure <a href="#directory-structure" id="directory-structure"></a>

Extensions in OpenCart 4.x are packed in a zip file named for example `test_module.ocmod.zip`, once that zip package uploaded into extension installer a folder will be created into the `extension/` directory based on the name of your file, in this case it will be `extension/test_module/`.

The zip package must be structured as follows:

* **install.json**: Metadata file (required for installation).
* **admin/**: Backend files.
  * `controller/module/test_module.php`: Admin controller.
  * `language/en-gb/module/test_module.php`: Language strings.
  * `view/template/module/test_module.twig`: Admin view.
  * `model/module/test_module.php`: Optional model for custom DB operations.
* **catalog/**: Frontend files.
  * `controller/module/test_module.php`: Frontend controller.
  * `language/en-gb/module/test_module.php`: Language strings.
  * `view/theme/default/template/module/test_module.twig`: Frontend view (use `default` or theme name).
  * `model/module/test_module.php`: Optional model.
* **ocmod/**: Optional, for OCMOD modifications (e.g., `test_module.ocmod.xml`).

Default extensions are in `extension/opencart/`. Make sure to use unique name on your package .ocmod.zip to avoid conflicts (e.g., prefix with your brand).

### Step-by-Step: Creating a Simple Module Extension <a href="#step-by-step-creating-a-simple-module-extension" id="step-by-step-creating-a-simple-module-extension"></a>

Let's create a sample module called "Test Module" that will use events to trigger an action on adding a product to cart.

#### Create the folder <a href="#create-the-folder" id="create-the-folder"></a>

Create a folder named `Test module/`, inside this folder you will create the files as described below.

#### 1. Create the install.json File <a href="#id-1-create-the-installjson-file" id="id-1-create-the-installjson-file"></a>

This JSON file provides extension metadata.

```json
{
  "name": "Test Module",
  "version": "1.0",
  "author": "Your Name",
  "link": "https://yourwebsite.com",
}
```

#### 2. Create the Admin Controller <a href="#id-2-create-the-admin-controller" id="id-2-create-the-admin-controller"></a>

File: `admin/controller/module/test_module.php`

This handles the admin interface, form saving, and event registration during installation.

```php
<?php
namespace Opencart\Admin\Controller\Extension\TestModule\Module;

class TestModule extends \Opencart\System\Engine\Controller {

  public function index(): void {
        $this->load->language('extension/test_module/module/test_module');

        $this->document->setTitle($this->language->get('heading_title'));

        $data['breadcrumbs'] = [];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module')
        ];

        $data['breadcrumbs'][] = [
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/test_module/module/test_module', 'user_token=' . $this->session->data['user_token'])
        ];

        $data['save'] = $this->url->link('extension/test_module/module/test_module.save', 'user_token=' . $this->session->data['user_token']);
        $data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module');

        $data['module_test_module_status'] = $this->config->get('module_test_module_status');

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/test_module/module/test_module', $data));
    }

    public function save(): void {
        $this->load->language('extension/test_module/module/test_module');

        $json = [];

        if (!$this->user->hasPermission('modify', 'extension/test_module/module/test_module')) {
            $json['error'] = $this->language->get('error_permission');
        }

        if (!$json) {
            // Setting
            $this->load->model('setting/setting');

            $this->model_setting_setting->editSetting('module_test_module', $this->request->post);

            $json['success'] = $this->language->get('text_success');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

  public function install(): void {
    // Load the event model
    $this->load->model('setting/event');

    // Register the event
    $this->model_setting_event->addEvent([
      'description' => 'Test module - Event before cart add',
      'code' => 'test_module_cart_add_before', // Event code (unique identifier)
      'trigger' => 'catalog/controller/checkout/cart.add/before', // Event trigger
      'action' => 'extension/test_module/events.onCartAddBefore', // Listener method
      'status' => 1,
      'sort_order' => 1
    ]);
  }

  public function uninstall(): void {
    // Remove the event on uninstall
    $this->load->model('setting/event');
    $this->model_setting_event->deleteEventByCode('test_module_cart_add_before');
}
```

#### 3. Create the Admin Language File <a href="#id-3-create-the-admin-language-file" id="id-3-create-the-admin-language-file"></a>

File: `admin/language/en-gb/module/test_module.php`

```php
<?php
$_['heading_title']    = 'Test module';
$_['text_extension']   = 'Extensions';
$_['text_success']     = 'Success: You have modified account module!';
$_['text_edit']        = 'Edit Module';
$_['entry_status']     = 'Status';
$_['error_permission'] = 'Warning: You do not have permission to modify test module!';
```

#### 4. Create the Admin View Template <a href="#id-4-create-the-admin-view-template" id="id-4-create-the-admin-view-template"></a>

File: `admin/view/template/module/test_module.twig`

```twig
{{ header }}{{ column_left }}
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="float-end">
        <button type="submit" form="form-module" data-bs-toggle="tooltip" title="{{ button_save }}" class="btn btn-primary"><i class="fa-solid fa-save"></i></button>
        <a href="{{ back }}" data-bs-toggle="tooltip" title="{{ button_back }}" class="btn btn-light"><i class="fa-solid fa-reply"></i></a></div>
      <h1>{{ heading_title }}</h1>
      <ol class="breadcrumb">
        {% for breadcrumb in breadcrumbs %}
          <li class="breadcrumb-item"><a href="{{ breadcrumb.href }}">{{ breadcrumb.text }}</a></li>
        {% endfor %}
      </ol>
    </div>
  </div>
  <div class="container-fluid">
    <div class="card">
      <div class="card-header"><i class="fa-solid fa-pencil"></i> {{ text_edit }}</div>
      <div class="card-body">
        <form id="form-module" action="{{ save }}" method="post" data-oc-toggle="ajax">
          <div class="row mb-3">
            <label class="col-sm-2 col-form-label">{{ entry_status }}</label>
            <div class="col-sm-10">
              <div class="form-check form-switch form-switch-lg">
                <input type="hidden" name="module_test_module_status" value="0"/>
                <input type="checkbox" name="module_test_module_status" value="1" id="input-status" class="form-check-input"{% if module_test_module_status %} checked{% endif %}/>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
{{ footer }}
```

#### 5. Create the Frontend Event Controller <a href="#id-5-create-the-frontend-event-controller" id="id-5-create-the-frontend-event-controller"></a>

File: `catalog/controller/events.php`

This listens to events and will log an entry when it has been triggered.

When the current event does have `$output`, it's possible alter it to change the final rendering.

```php
<?php
namespace Opencart\Catalog\Controller\Extension\TestModule;

class Events extends \Opencart\System\Engine\Controller {

  public function onCartAddBefore(&$route, &$data, &$output = null) {
    // Process your customizations here
    $this->log->write('onCartAddBefore() has been successfully triggered!');
  }
}
```

#### 6. Optional: Add Models if Needed <a href="#id-6-optional-add-models-if-needed" id="id-6-optional-add-models-if-needed"></a>

For custom database operations, create `admin/model/module/test_module.php` or `catalog/model/module/test_module.php`. Extend `\Opencart\System\Engine\Model` and define methods like `addData()` with SQL queries.

#### 7. Using OCMOD for Core Modifications (If Events Are Insufficient) <a href="#id-7-using-ocmod-for-core-modifications-if-events-are-insufficient" id="id-7-using-ocmod-for-core-modifications-if-events-are-insufficient"></a>

For modifications not covered by events, use OCMOD XML files in `ocmod/test_module.ocmod.xml`. Example to add a menu item in admin:

```xml
<?xml version="1.0" encoding="utf-8"?>
<modification>
    <name>Example OCMOD</name>
    <code>example_ocmod</code>
    <version>1.0</version>
    <author>Your Name</author>
    <file path="admin/controller/common/column_left.php">
        <operation>
            <search><![CDATA[if ($marketplace) {]]></search>
            <add position="before"><![CDATA[
                $data['menus'][] = [
                    'id'       => 'test-module',
                    'icon'     => 'fas fa-cog',
                    'name'     => 'Test Module',
                    'href'     => $this->url->link('extension/test_module/module/test_module', 'user_token=' . $this->session->data['user_token']),
                    'children' => []
                ];
            ]]></add>
        </operation>
    </file>
</modification>
```

### Packaging and Installation <a href="#packaging-and-installation" id="packaging-and-installation"></a>

1. Zip the files in your extension folder (e.g., `test_module.ocmod.zip`), including `install.json` and all subfolders/files. Note that you must not zip the folder `Test module/` but the inside files directly (so when you open your zip file you will see `install.json`, `admin/`, `catalog/`).
2. Go to **Extensions > Installer** > Upload the ZIP.
3. Click on install button near the new entry that appeared in `Extensions > Installer`.
4. Install via **Extensions > Extensions > Modules** (click Install).
5. If you have an ocmod, refresh modifications in **Extensions > Modifications** > Refresh (blue button).
6. Clear cache in **Dashboard > Gear Icon > Clear Cache**.
7. Configure and enable the module in **Extensions > Extensions > Modules**.

If errors occur, check logs in `system/storage/logs/` or ensure no file conflicts.

### Best Practices <a href="#best-practices" id="best-practices"></a>

* **Namespace Usage**: Always use namespaces (e.g., `Opencart\Admin\Controller\Extension\MyExtension\Module`).
* **Internationalization**: Use language files for all text.
* **Security**: Validate inputs, check permissions (`$this->user->hasPermission()`).
* **Compatibility**: Test on multiple themes and PHP versions (8.1+ recommended).
* **Cleanup**: Implement `uninstall()` to remove DB entries/events.
* **Documentation**: Include instructions in `install.json` or a README.
* **Avoid Core Changes**: Prefer events over OCMOD when possible.
* **Testing**: Use a development store; clear caches frequently.
* **Marketplace Ready**: If distributing, ensure compliance with OpenCart's extension standards.

### More Examples <a href="#more-examples" id="more-examples"></a>

Current example is basic and will help to understand the logic for extension creation, then to go further and see how are made all extension types (modules, payment gateways, shipping methods, etc) the best is to check the opencart example packages that are included by default.

On default install go to `Extension > Installer`, you will see the following items:

* **OpenCart Language Example**
* **OpenCart OCMOD Example**
* **OpenCart Payment Example**
* **OpenCart Theme Example**

These are specifically made to show how to create an extension of each of these types, there is also :

* **OpenCart Default Extensions**

This one contains various default opencart modules (bestsellers, latest, carrousel, etc), payment gateways (bank transfer, checkout on delivery), and others that will give you practical example of extensions, you can find the zip packages into `storage/marketplace/`folder so you can extract them to see the structure and adapt for you own usage.
