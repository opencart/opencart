# Events

### Introduction

OpenCart v4.x uses an **event system** to allow developers to extend and modify core functionality without altering the core codebase. The event system enables you to hook into specific points in the application lifecycle, execute custom code, and modify data or behavior. This guide explains how to work with events in OpenCart, including registering, triggering, and handling events, with practical examples.

### What is the Event System?

The event system in OpenCart is based on a publisher-subscriber model. Events are triggered at specific points in the application, and developers can register listeners (handlers) to execute custom code when these events occur. This makes OpenCart highly extensible, allowing modifications like adding custom logic, altering data, or integrating third-party services.

#### Key Components

* **Event**: A named trigger point in the code (e.g., `catalog/controller/product/category/before`).
* **Listener**: A function or method that executes when an event is triggered.
* **Event Registry**: The system that maps events to their listeners.
* **Trigger**: The action of firing an event to execute registered listeners.

### Event Naming Convention

Events are typically identified by a string key in the format `namespace/action/stage`.

* **Namespace**: Indicates the context (e.g., `catalog`, `admin`, `extension`).
* **Action**: Specifies the action path (e.g., `controller/checkout/cart.add`).
* **Stage**: Indicates when the event occurs (`before` or `after`).

Example: `catalog/controller/product/product/before` is triggered before rendering the product view page in the catalog (front-end). Note that when referring to index method you have to let the action path without specifying the method (correct: product/product, wrong: product/product.index).

#### Common Events

Here are some commonly used events you might want to hook into:

* `catalog/view/product/product/before`: Before rendering a product page.
* `catalog/view/common/content_top/after`: After rendering content\_top.
* `catalog/controller/checkout/cart.add/after`: After adding a product to the cart.
* `catalog/model/checkout/order.editOrder/before`: Before editing an order.
* `catalog/controller/checkout/confirm/after`: After confirming a checkout.

### How to Register an Event Listener

Event listeners are registered in OpenCart through the **event registry**. You typically define listeners in your module or extension's code, either in a custom module or by modifying an existing extension.

#### Steps to Register an Event Listener

1. **Create a Listener Method**: Write the function or method that will handle the event.
2. **Register the Event**: Map the event to the listener using the event registry.
3. **Ensure Accessibility**: Ensure the listener is loaded when the event is triggered.

#### Example: Registering an Event Listener

Suppose you want to execute custom code before a product is added to the cart in the front-end (`catalog/controller/checkout/cart.add/before`).

**Step 1: Create the Listener**

Create a PHP class for your extension. For example, place the following code in `extension/test_module/catalog/controller/events.php`:

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

**Step 2: Register the Event**

Events are typically registered in the `install()` method of your extension. For programmatic registration, use the `startup/event` model.

Add the following code to your module’s controller:

```php
<?php
namespace Opencart\Admin\Controller\Extension\TestModule\Module;

class TestModule extends \Opencart\System\Engine\Controller {
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
}
```

**Step 3: Test the Event**

* Install your module via the OpenCart admin panel (`Extensions > Installer`).
* Install and enable your module in `Extensions > Extensions` (if config code "module\_\[module\_name]\_status" is not set then the corresponding event won't be triggered).
* Add a product to the cart in the front-end.
* Check the log file (`System > Maintenance > Error logs`) to verify that your custom log message appears.

### Triggering Custom Events

You can also trigger your own custom events. This is useful for custom modules or extensions that need to notify other parts of the system.

Suppose you want to trigger a custom event called `extension/test_module/custom_action/after`.

In your controller or model, use the `trigger()` method from the event registry. For example, in `/extension/test_module/catalog/controller/custom_action.php`:

```php
// Data to pass to event
$data = ['message' => 'Custom action executed'];

// Trigger a custom event
$this->event->trigger('extension/test_module/custom_action/after', $data);
```

### Best Practices

1. **Use Descriptive Event Names**: Follow the `namespace/action/stage` convention for clarity.
2. **Avoid Core Modifications**: Use the event system or OCMOD to keep your code upgrade-safe.
3. **Handle Data Carefully**: Always validate and sanitize data passed to event listeners.
4. **Optimize Performance**: Avoid heavy processing in event listeners to prevent slowing down the application.
5. **Test Thoroughly**: Test your event listeners in different scenarios to ensure they work as expected.
6. **Clean Up on Uninstall**: Always remove events in the `uninstall()` method to avoid orphaned entries.

### Debugging Events

* **Check Logs**: Enable error logging in OpenCart (`System > Settings > Server > Error Logging`) to debug issues.
* **Verify Event Registration**: Ensure your event is registered in `Extensions > Events`.
* **Test Incrementally**: Test one listener at a time to isolate issues.
