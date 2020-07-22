In version 3.0 we have added some additional path restrictions so extensions being installed will not cause issues when uninstalling.

Extension System

Types of extension
 
* analytics
* captcha
* dashboard
* extension
* feed
* fraud
* module
* payment
* report
* shipping
* theme
* total

Allowed directories extension files can be written to.
 
Admin

* admin/controller/extension/
* admin/language/
* admin/model/extension/
* admin/view/image/
* admin/view/javascript/
* admin/view/stylesheet/
* admin/view/template/extension/

Catalog

* catalog/controller/extension/
* catalog/language/
* catalog/model/extension/
* catalog/view/javascript/
* catalog/view/theme/

System

* system/config/
* system/library/


Image

* image/catalog/
 
$this->load->model('extension/event');$this->load->model('extension/event');
