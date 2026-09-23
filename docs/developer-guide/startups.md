# Startups

### Introduction

The **startup system** allow developers process code at very early stage when opening a page, it is useful for example to initialize some library for further use, handle seo related data, etc. This guide explains how to work with startups in OpenCart with practical examples.

#### Key Components

* **Startup class**: The class to execute at startup.
* **Startup registry**: The system that stores the startups.

### How to Register a Startup

1. **Create a startup class**: Write your code into the index() method.
2. **Register the startup**: Save your startup using the startup registry.
3. **Ensure accessibility**: Ensure the startup is loaded when accessing a page.

#### Example: Registering a Startup

**Step 1: Create the Startup Class**

Create a PHP class for your extension. For example, place the following code in `extension/test_module/catalog/controller/startup.php`:

```php
<?php
namespace Opencart\Catalog\Controller\Extension\TestModule;

class Startup extends \Opencart\System\Engine\Controller {
  public function index(): void {
    // Process your customizations here
    $this->log->write('My startup has been successfully initialized!');
  }
  
}
```

**Step 2: Register the Startup**

Startups are typically registered in the `install()` method of your extension. For programmatic registration, use the `setting/startup` model.

Add the following code to your module’s controller:

```php
<?php
namespace Opencart\Admin\Controller\Extension\TestModule\Module;

class TestModule extends \Opencart\System\Engine\Controller {
    public function install(): void {
    // Load the startup model
    $this->load->model('setting/startup');
    
    // Register the startup
    $this->model_setting_startup->addStartup([
      'code'			=> 'my_startup',
      'action'		=> 'catalog/extension/test_module/startup',
      'description'	=> 'My custom startup',
      'sort_order'	=> 1,
      'status'		=> true
    ]);
  }

  public function uninstall(): void {
    // Remove the startup on uninstall
    $this->load->model('setting/startup');
    $this->model_setting_startup->deleteStartupByCode('my_startup');
  }
}
```

**Step 3: Test the Startup**

* Install your module via the OpenCart admin panel (`Extensions > Installer`).
* Install your module in Extensions menu (`Extensions > Extensions`).
* Check if the startup has been correctly registered into `Extensions > Startups`.
* Open a page on front-end
* Check the log file (`System > Maintenance > Error logs`) to verify that your custom log message appears.
