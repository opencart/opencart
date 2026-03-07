# Tasks

### Introduction

Tasks have been introduced in OpenCart v4.2, they are background processes for tasks that does not require immediate feedback, aiming to speed up the whole experience.

Tasks are run in a background process using PHP shell\_exec() function, which may overcome some server limitations while using normal PHP process.

### How to Create a task

* **Create a task class**: Write your code into the index() method.
* **Register the task**: Save your task using the task registry.
* **Ensure Accessibility**: Ensure the task is correctly working.

#### Example

**Step 1: Create the Task Class**

Create a PHP class for your extension. For example, place the following code in `extension/test_module/catalog/controller/task.php`:

```php
<?php
namespace Opencart\Catalog\Controller\Extension\TestModule;

class Task extends \Opencart\System\Engine\Controller {
  public function index(): void {
    // Process your customizations here
    $this->log->write('My task has successfully started!');
  }
  
}
```

**Step 2: Register the Task**

Tasks are typically registered in the `install()` method of your extension.

Add the following code to your module’s controller:

```php
<?php
namespace Opencart\Admin\Controller\Extension\TestModule\Module;

class TestModule extends \Opencart\System\Engine\Controller {
    public function install(): void {
    // Load the task model
    $this->load->model('setting/task');

    // Register the task
    $task_data = [
    	'code'   => 'my_task',
		'action' => 'task/catalog/test_module.task',
		'args'   => []
	];

	$this->model_setting_task->addTask($task_data);
  }

  public function uninstall(): void {
    // Remove the task on uninstall
    $this->load->model('setting/task');
    $this->model_setting_task->deleteTaskByCode('my_task');
  }
}
```

**Step 3: Test the Task**

* Install your module via the OpenCart admin panel (`Extensions > Installer`).
* Install your module in Extensions menu (`Extensions > Extensions`).
* Open `Extension > Tasks`, you should see your new entry
* Click on the button `▶️ Run Tasks`
* Check the log file (`System > Maintenance > Error logs`) to verify that your custom log message appears.

