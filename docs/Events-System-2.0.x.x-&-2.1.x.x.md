In 2.0+ we have added Events, these are method notifications/hooks that are called when an action takes place.

> Why use Events? We have vQmod/OCmod that can do this for us...

Code level changes can easily conflict, if 2 or more modules want to change some code you will run into conflicts very easily. Using Events you reduce this risk as each portion of code is isolated that does not modify any core code.

#### Registering your Events
When your module is installed you will want to register all events that your script needs.

*Load the model:*
```
$this->load->model('extension/event');
```

*Register your Event:*
```
$this->model_extension_event->addEvent('my_module', 'post.admin.product.add', 'folder/file/method');
```

#### Removing your Event
When your module is uninstalled you will need to ensure that you remove all of the Events that you registered.

*Remove your Event:*
```
$this->model_extension_event->deleteEvent('my_module');
```

#### Using Events
Once your Event is registered you need to ensure that the controller method exists. In the example we have added an event that is triggered when a product is added. The file called would be *admin/controller/module/demo.php* and the method inside the demo controller class would be `eventSendAdminAlert()` and if you wanted to add an event to the front end (catalog) use *catalog/controller/module/demo.php*

#### Products, Attributes, Categories, Options, Downloads
| Event name                          | Params                   |
| ------------------------------------|:------------------------:|
| pre.admin.product.add               | (array)data              |
| post.admin.product.add              | (int)product_id          |
| pre.admin.product.edit              | (array)data              |
| post.admin.product.edit             | (int)product_id          |
| pre.admin.product.delete            | (int)product_id          |
| post.admin.product.delete           | (int)product_id          |
| pre.admin.attribute.add             | (array)data              |
| post.admin.attribute.add            | (int)attribute_id        |
| pre.admin.attribute.edit            | (array)data              |
| post.admin.attribute.edit           | (int)attribute_id        |
| pre.admin.attribute.delete          | (int)attribute_id        |
| post.admin.attribute.delete         | (int)attribute_id        |
| pre.admin.attribute_group.add       | (array)data              |
| post.admin.attribute_group.add      | (int)attribute_group_id  |
| pre.admin.attribute_group.edit      | (array)data              |
| post.admin.attribute_group.edit     | (int)attribute_group_id  |
| pre.admin.attribute_group.delete    | (int)attribute_group_id  |
| post.admin.attribute_group.delete   | (int)attribute_group_id  |
| pre.admin.category.add              | (array)data              |
| post.admin.category.add             | (int)category_id         |
| pre.admin.category.edit             | (array)data              |
| post.admin.category.edit            | (int)category_id         |
| pre.admin.category.delete           | (int)category_id         |
| post.admin.category.delete          | (int)category_id         |
| pre.admin.option.add                | (array)data              |
| post.admin.option.add               | (int)option_id           |
| pre.admin.option.edit               | (array)data              |
| post.admin.option.edit              | (int)option_id           |
| pre.admin.option.delete             | (int)option_id           |
| post.admin.option.delete            | (int)option_id           |
| pre.admin.download.add              | (array)data              |
| post.admin.download.add             | (int)download_id         |
| pre.admin.download.edit             | (array)data              |
| post.admin.download.edit            | (int)download_id         |
| pre.admin.download.delete           | (int)download_id         |
| post.admin.download.delete          | (int)download_id         |

#### Information, Banners and Layouts
| Event name                          | Params                   |
| ------------------------------------|:------------------------:|
| pre.admin.information.add               | (array)data              |
| post.admin.information.add              | (int)information_id      |
| pre.admin.information.edit              | (array)data              |
| post.admin.information.edit             | (int)information_id      |
| pre.admin.information.delete            | (int)information_id      |
| post.admin.information.delete           | (int)information_id      |
| pre.admin.banner.add                    | (array)data              |
| post.admin.banner.add                   | (int)banner_id           |
| pre.admin.banner.edit                   | (array)data              |
| post.admin.banner.edit                  | (int)banner_id           |
| pre.admin.banner.delete                 | (int)banner_id           |
| post.admin.banner.delete                | (int)banner_id           |
| pre.admin.layout.add                    | (array)data              |
| post.admin.layout.add                   | (int)layout_id           |
| pre.admin.layout.edit                   | (array)data              |
| post.admin.layout.edit                  | (int)layout_id           |
| pre.admin.layout.delete                 | (int)layout_id           |
| post.admin.layout.delete                | (int)layout_id           |

#### Filters
| Event name                          | Params                   |
| ------------------------------------|:------------------------:|
| pre.admin.filter.add                    |                          |
| post.admin.filter.add                   | (int)filter_id           |
| pre.admin.filter.edit                   |                          |
| post.admin.filter.edit                  | (int)filter_id           |
| pre.admin.filter.delete                 | (int)filter_id           |
| post.admin.filter.delete                | (int)filter_id           |

#### Manufacturers
| Event name                          | Params                   |
| ------------------------------------|:------------------------:|
| pre.admin.manufacturer.add              |                          |
| post.admin.manufacturer.add             | (int)manufacturer_id     |
| pre.admin.manufacturer.edit             |                          |
| post.admin.manufacturer.edit            | (int)manufacturer_id     |
| pre.admin.manufacturer.delete           | (int)manufacturer_id     |
| post.admin.manufacturer.delete          | (int)manufacturer_id     |

#### Recurring profiles
| Event name                          | Params                   |
| ------------------------------------|:------------------------:|
| pre.admin.recurring.add                 | (array)data              |
| post.admin.recurring.add                | (int)recurring_id        |
| pre.admin.recurring.edit                | (array)data              |
| post.admin.recurring.edit               | (int)recurring_id        |
| pre.admin.recurring.delete              | (int)recurring_id        |
| post.admin.recurring.delete             | (int)recurring_id        |

#### Reviews
| Event name                          | Params                   |
| ------------------------------------|:------------------------:|
| pre.admin.review.add                    | (array)data              |
| post.admin.review.add                   | (int)review_id           |
| pre.admin.review.edit                   | (array)data              |
| post.admin.review.edit                  | (int)review_id           |
| pre.admin.review.delete                 | (int)review_id           |
| post.admin.review.delete                | (int)review_id           |

#### Marketing
| Event name                          | Params                   |
| ------------------------------------|:------------------------:|
| pre.admin.marketing.add                 |                          |
| post.admin.marketing.add                | (int)marketing_id        |
| pre.admin.marketing.edit                |                          |
| post.admin.marketing.edit               | (int)marketing_id        |
| pre.admin.marketing.delete              | (int)marketing_id        |
| post.admin.marketing.delete             | (int)marketing_id        |

#### Coupons
| Event name                          | Params                   |
| ------------------------------------|:------------------------:|
| pre.admin.coupon.add                    | (array)data              |
| post.admin.coupon.add                   | (int)coupon_id           |
| pre.admin.coupon.edit                   | (array)data              |
| post.admin.coupon.edit                  | (int)coupon_id           |
| pre.admin.coupon.delete                 | (int)coupon_id           |
| post.admin.coupon.delete                | (int)coupon_id           |

#### Stores and backups
| Event name                          | Params                   |
| ------------------------------------|:------------------------:|
| pre.admin.store.add                     |                          |
| post.admin.store.add                    | (int)store_id            |
| pre.admin.store.edit                    |                          |
| post.admin.store.edit                   | (int)store_id            |
| pre.admin.store.delete                  | (int)store_id            |
| post.admin.store.delete                 | (int)store_id            |
| pre.admin.backup                        | (array)tables            |
| post.admin.backup                       |                          |

#### Affiliates
| Event name                          | Params                   |
| ------------------------------------|:------------------------:|
| pre.admin.affiliate.add                 | (array)data              |
| post.admin.affiliate.add                | (int)affiliate_id        |
| pre.admin.affiliate.edit                | (array)data              |
| post.admin.affiliate.edit               | (int)affiliate_id        |
| pre.admin.affiliate.delete              | (int)affiliate_id        |
| post.admin.affiliate.delete             | (int)affiliate_id        |
| pre.admin.affiliate.approve             | (int)affiliate_id        |
| post.admin.affiliate.approve            | (int)affiliate_id        |
| pre.admin.affiliate.transaction.add     | (int)affiliate_id        |
| post.admin.affiliate.transaction.add    | (int)affiliate_transaction_id |
| pre.admin.affiliate.transaction.delete  | (int)order_id            |
| post.admin.affiliate.transaction.delete | (int)order_id            |

#### Customer Events
| Event name                     | Params                   |
| -------------------------------|:------------------------:|
| pre.customer.logout            |                          |
| post.customer.logout           |                          |
| pre.customer.login             |                          |
| post.customer.login            |                          |
| pre.customer.add               | (array)data              |
| post.customer.add              | (int)customer_id         |
| pre.customer.edit              | (array)data              |
| post.customer.edit             | (int)customer_id         |
| pre.customer.password.edit     |                          |
| post.customer.password.edit    |                          |
| pre.customer.newsletter.edit   |                          |
| pre.customer.newsletter.edit   |                          |
| pre.customer.add.address       | (array)data              |
| post.customer.add.address      | (int)address_id          |
| pre.customer.edit.address      | (array)data              |
| post.customer.edit.address     | (int)address_id          |
| pre.customer.delete.address    | (int)address_id          |
| post.customer.delete.address   | (int)address_id          |
| pre.return.add                 | (array)data              |
| post.return.add                | (int)return_id           |
| pre.review.add                 | (array)data              |
| post.review.add                | (int)review_id           |

#### Customer Affiliate Events
| Event name                     | Params                   |
| -------------------------------|:------------------------:|
| pre.affiliate.add              | (array)data              |
| post.affiliate.add             | (int)affiliate_id        |
| pre.affiliate.edit             | (array)data              |
| post.affiliate.edit            | (int)affiliate_id        |
| pre.affiliate.payment.edit     | (array)data              |
| post.affiliate.payment.edit    | (int)affiliate_id        |
| pre.affiliate.password.edit    | (int)affiliate_id        |
| post.affiliate.password.edit   | (int)affiliate_id        |
| pre.affiliate.transaction.add  | (array)data              |
| post.affiliate.transaction.add | (int)affiliate_transaction_id |

#### Order Events
| Event name                     | Params                   |
| -------------------------------|:------------------------:|
| pre.order.add                  | (array)data              |
| post.order.add                 | (int)order_id            |
| pre.order.edit                 | (array)data              |
| post.order.edit                | (int)order_id            |
| pre.order.delete               | (int)order_id            |
| post.order.delete              | (int)order_id            |
| pre.order.history.add          | (int)order_id            |
| post.order.history.add         | (int)order_id            |