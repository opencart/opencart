* [MVC Structure](#MVC-Structure)
* [File Structure](#File-Structure)
* [Loading](#Loading)
* [Components](#Components)
  * [Controller](#Controller)
  * [Language](#Language)
  * [Model](#Model)
  * [View](#View)
  * [Library](#Library)
  * [Config](#Config)
  * [Helper](#Helper)

<a name="MVC-Structure" />

## MVC Structure

OpenCart structure is split into 2 separate applications. One is the catalog (store front) and admin (administration). Each application structure is based on the Model View Controller [(MVC) paradigm](https://en.wikipedia.org/wiki/Model-view-controller) and includes a shared library.

MVC is basically the splitting of the data, logic and UI into separate parts to make building applications more structured and easier to manage.

### Model

The functions for data storing, editing and retrieval. This can be done via a database, file, remote storing etc..

### View

In web based MVC the view is normally where the templates are stored.

### Controller

The bit in the middle where logic is used to choose which models to load and push the data into the view / template.

![alt text](http://docs.opencart.com/image/mvcl.png "OpenCart MVC")

<a name="File-Structure" />

## File Structure

The front and the admin are spit into two separate folders and the shared library folder. 

* admin - Administration Area
* catalog - Store Front
* system - Shared System Files

![alt text](http://docs.opencart.com/image/file_struct.png "File Structure")

### Application

In the two application directories you will find the controller, model, view and a language folder. This so each of component that makes up the OpenCart application can be easily managed.

* controller/
* language/
* model/
* view/

### System

* config/
* engine/
* helper/
* library/
* storage/

### Storage

It's very important that you move the storage directory outside of the web directory as the files stored here can contain information that can compromise the security of a users web site.

* cache/
* download/
* logs/
* modification/
* session/
* upload/
* vendor/

<a name="Loading" />

## Loading

Its important to understand about how parts of the OpenCart system are loaded before describing the file structure and coding structure.

### Page Loading

Controllers can be directly called by users visiting the web site and also be accessed via the code using the [loader class]().

The route URL variable in the address bar will need to match up with the path of the controller being called.

###### Example

URL

```
https://www.yourstore.com/index.php?route=account/login
```

Path to file

```
/catalog/controller/account/login.php
```

Path to controller method

###### Example

the last part of the route will either be the file name or the 

So 

### Code Loading

The [loader class](https://github.com/opencart/opencart/wiki/Engine#loader) is used to load the different components of OpenCart. Only the library classes can be auto loaded upon [Object Initialization](http://php.net/manual/en/language.types.object.php).

###### Example Controller load

`$this->load->controller('directory/filename');`

###### Example Model load

`$this->load->model('directory/filename');`

Models can be access directory in the code using:

`$this->load->model('directory/filename');`

`$this->load->view('directory/filename');`

`$this->load->helper('directory/filename');`

`$this->load->language('directory/filename');`

`$this->load->config('filename');`

Please refer to the [loader class](https://github.com/opencart/opencart/wiki/Engine#loader) for the class API.

<a name="Components" />

## Components

Components Description 

<a name="Controller" />

### Controllers

Controller Description 

#### Code Structure

So for example a blog extension structure maybe

```
<?php
class ControllerCmsBlog extends ***Controller*** {
   // If no method is called index is called by default
   public function index() {
  
   }
}
```
 
### Languages

OpenCart tries to detect the language your browser is using even if the database is down it will use the directory name to see

The language directory should be named after [Web browser language identification codes](http://www.metamodpro.com/browser-language-codes) in lowercase. 

Language|Code|Language|Code|Language|Code
---------|--|--------|------------|------------|------------
Afrikaans|af|Croatian|hr|Greek|el
Albanian|sq			

#### Code Structure

Language files are basically just arrays and are broken into a few different parts.

###### Example

```
<?php
// Heading
$_['heading_title']      = 'My Heading';

// Text
$_['text_account']       = 'General';

// Entry``
$_['entry_firstname']    = 'First Name';
$_['entry_lastname']     = 'Last Name';
$_['entry_email']        = 'E-Mail';
$_['entry_telephone']    = 'Telephone';

// Error
$_['error_exists']       = 'Warning: E-Mail address is already registered!';
$_['error_firstname']    = 'First Name must be between 1 and 32 characters!';
$_['error_lastname']     = 'Last Name must be between 1 and 32 characters!';
$_['error_email']        = 'E-Mail Address does not appear to be valid!';
$_['error_telephone']    = 'Telephone must be between 3 and 32 characters!';
$_['error_custom_field'] = '%s required!';
```

<a name="Model" />

### Models

Description

#### Code Structure

```
<?php
class ModelCmsBlog extends Model {
  public function getBlogs($data = array()) {
  
  }
}
```


<a name="View" />

### Views

Description

#### File Structure

Admin

* view/
* view/image/
* view/stylesheets/
* view/sass/
* view/template/

Catalog

* catalog/controller/
* catalog/language/
* catalog/model/
* catalog/view/theme/template

#### Code Structure

<a name="Config" />

### Config

(Description)

#### Code Structure

<a name="Helper" />

### Helper

(Description)

#### Code Structure

<a name="Library" />

### Library

A complete list of available library is here.

#### Code Structure