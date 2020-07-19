**(Still working on the documentation)**

## What are events?
In 2.2+ we have added a new events system, these are hooks that can be called before and after events such as a controller method call, model method call or templates being loaded. They give the ability to manipulate the input method parameters and returned output.

**NOTE**

Since 2.2+ the events system has been updated so that controllers, models, views, language and config can have an event attached to them. In previous versions only model data could be overridden. 

## Registering your Events
When your module is installed you will want to register all events that your script needs.

**Load the model**

```
$this->load->model('setting/event');
```

**Register your Event**

```
$this->model_setting_event->addEvent($code, $trigger, $action);
```

**Example**

```
$this->model_setting_event->addEvent('my_theme', 'catalog/controller/catalog/product/before', 'my_theme/product');
```

#### Code
Code should be a unique code that is unique only to your extensions. you could use your OpenCart username then the name of the extension.

**Example**

username_theme 

The code is also used to delete the event you have added should the user decide to uninstall your extension.

#### Trigger

Trigger is the call being made to the controller, model, view, language or config file being called or loaded.

The trigger is made up of many parts:

application / type / folder / file / method / before or after

##### Application
Needs to be set to catalog or admin.

##### Type

* controller
* model
* view
* language
* config

##### Folder
Folder is optional depending if the path of the file being loaded is within a sub-folder or not.

##### File
File name being called.

##### Method
Method is only used with controllers and models. 

The event trigger when a controller or model method is called. Controllers dont always require method but models do.

##### Before / After

Sets weather the event is triggered before or after the file is loaded or method called.

#### Action

Action is an object that has in its constructor the route to the action controller being called. Within the controller you can manipulate the route, data, arguments and return of data of the controller, model, view being called.

**Example**

folder/file/method

If no method is set then it will call index.

## Removing your Event
When your module is uninstalled you will need to ensure that you remove all of the events that you registered.

**Remove your Event**

```
$this->model_setting_event->deleteEvent('my_theme');
```

## Using Events

To use and event you must:

1. Set a trigger.  
1. Ensure that the action (controller) being called by the event trigger exists.

Only controllers can be called by the action. This means if you attach an action to be triggered when a model event is called it can only call a controller and not another model.

Depending if a class being loaded or called controller, model, view, language or config file is being called action being called arguments have to be supplied to the action.

Examples:

### Controller

###### Parameters

| Tables | Type   | Description |
|:------ |:-------|:------------|
| $route | string | gets the library object by key |
| $data| array | The data being being fed into the controller |

###### Return
If you return the data it will stop any other event actions that are set to be called.  

### Model

#### Parameters (2)

##### $route
The route that is being called.

##### $data
The data being being fed into the controller.

##### $data
The data being being fed into the controller.

#### Return
If you return the data it will stop any other actions that are
### View

Input Parameters

View
the view being loaded

data
the data

### Language

#### Parameters

##### $route
The route that

### Config


In the example we have added an event that is triggered when a product is added. The file called would be *admin/controller/module/demo.php* and the method inside the demo controller class would be `eventSendAdminAlert()` and if you wanted to add an event to the front end (catalog) use *catalog/controller/module/demo.php*