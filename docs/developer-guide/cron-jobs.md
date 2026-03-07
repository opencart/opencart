# Cron Jobs

### Introduction

This guide provides detailed instructions for developers on setting up and managing cron jobs in OpenCart. Cron jobs are essential for automating recurring tasks such as refreshing currency rates, cache clearing, sitemap generation, sending newsletters, etc.

OpenCart integrates a cron handler that can centralize all reccuring tasks to allow to easily manage all from the admin instead of having to manage them from the host panel, which can be convenient for the end-user.

### Set up the OpenCart Cron in your Host Panel

First of all it's necessary to set up your host to call the OpenCart cron handler, this is necessary only once, then all will be handled directly from OpenCart, follow this procedure to set up the cron, here based on cPanel but the same can be done in all host panels.

1. Open `Extension > Cron` menu
2. Go to your cPanel Cron jobs section (or equivalent in other host panels)
3. Add new cron job
4. copy-paste the command that is displayed into `Extension > Cron` (e.g.: `wget "https://mywebsite.tld/index.php?route=cron/cron" --read-timeout=5400`)
5. Set the cron task to run every hour

That's all, now all the cron tasks set into `Extension > Cron` will run automatically.

### How to Create an OpenCart Cron Job

1. **Create a cron class**: Write your code into the index() method.
2. **Register the cron job**: Save your cron job using the cron registry.
3. **Ensure Accessibility**: Ensure the cron is correctly working.

#### Example

**Step 1: Create the Cron Class**

Create a PHP class for your extension. For example, place the following code in `extension/test_module/catalog/controller/cron.php`:

```php
<?php
namespace Opencart\Catalog\Controller\Extension\TestModule;

class Cron extends \Opencart\System\Engine\Controller {
  public function index(): void {
    // Process your customizations here
    $this->log->write('My cron job has successfully started!');
  }
}
```

**Step 2: Register the Startup**

Startups are typically registered in the `install()` method of your extension. For programmatic registration, use the `Startup` model.

Add the following code to your module’s controller:

```php
<?php
namespace Opencart\Admin\Controller\Extension\TestModule\Module;

class TestModule extends \Opencart\System\Engine\Controller {
    public function install(): void {
    // Load the cron model
    $this->load->model('setting/cron');
    
    // Register the cron job
    $this->model_setting_cron->addCron([
      'code' => 'my_cron', // Cron code (unique identifier)
      'description' => 'My custom cron',
      'cycle' => 'day', // cycle (hour, day, week)
      'action' => 'extension/test_module/cron', // action
      'status' => true
    ]);
  }

  public function uninstall(): void {
    // Remove the cron job on uninstall
    $this->load->model('setting/cron');
    $this->model_setting_cron->deleteCronByCode('my_cron');
}
```

**Step 3: Test the Cron Job**

* Install your module via the OpenCart admin panel (`Extensions > Installer`).
* Install your module in Extensions menu (`Extensions > Extensions`).
* Open `Extension > Cron Jobs`, you should see your new entry
* Click on the button `▶️ Run Cron Job`
* Check the log file (`System > Maintenance > Error logs`) to verify that your custom log message appears.
