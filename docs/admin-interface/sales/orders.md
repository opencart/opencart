---
description: Complete guide to managing customer orders in OpenCart 4
---

# Orders

## Introduction

The Orders section is the central hub for managing all customer purchases in OpenCart 4. This comprehensive system allows you to view, process, modify, and track orders from initial placement to final fulfillment.

{% hint style="info" %}
**Order Processing Workflow** Orders automatically appear in this section when customers complete checkout. You can then process payments, update status, manage shipping, and handle customer communications.
{% endhint %}

## Accessing Orders

To access the Orders section:

1. **Navigate to Sales** in the main admin menu
2. **Click on Orders** to open the order management interface
3. **View the order list** displaying all customer orders

## Order List Overview

The main Orders page displays a comprehensive list of all customer orders with the following information:

| Column            | Description                                                |
| ----------------- | ---------------------------------------------------------- |
| **Order ID**      | Unique identifier assigned to each order                   |
| **Customer**      | Customer name and contact information                      |
| **Store**         | Store location where order was placed                      |
| **Status**        | Current order status (Pending, Processing, Complete, etc.) |
| **Date Added**    | When the order was initially placed                        |
| **Date Modified** | Last time the order was updated                            |
| **Total**         | Order total amount                                         |
| **Action**        | Available actions (Edit, Delete, Invoice)                  |

![Orders List Interface](../../.gitbook/assets/orders-list-interface.png)

## Filtering Orders

OpenCart 4 provides powerful filtering capabilities to help you find specific orders quickly:

{% stepper %}
{% step %}
#### Step 1: Access Filter Options

Click the **Filter** button above the order list to expand the filtering options.
{% endstep %}

{% step %}
#### Step 2: Apply Search Criteria

Use any combination of the following filters:

* **Order ID**: Search by specific order number
* **Customer**: Search by customer name or email
* **Store**: Filter by specific store location
* **Order Status**: Filter by order status
* **Total Amount**: Search by order total
* **Date Ranges**: Filter by date added or modified
{% endstep %}

{% step %}
#### Step 3: Apply and View Results

Click **Apply Filter** to display matching orders. Use **Clear Filter** to reset all criteria.
{% endstep %}
{% endstepper %}

![Orders Filter Interface](../../.gitbook/assets/orders-filter-interface.png)

## Viewing Order Details

To view complete order information:

1. **Click the Edit button** next to any order in the list
2. **Review the comprehensive order details** including:
   * Customer information and contact details
   * Payment and shipping addresses
   * Products ordered with quantities and prices
   * Order totals and applied discounts
   * Order history and status updates

![Order Details Interface](../../.gitbook/assets/order-details-interface.png)

## Order Status Management

### Available Order Statuses

OpenCart 4 includes several built-in order statuses:

| Status         | Description                              |
| -------------- | ---------------------------------------- |
| **Pending**    | Order received but not yet processed     |
| **Processing** | Order is being prepared for shipment     |
| **Shipped**    | Order has been shipped to customer       |
| **Complete**   | Order fulfilled and completed            |
| **Canceled**   | Order has been canceled                  |
| **Denied**     | Order was denied (fraud, payment issues) |
| **Failed**     | Payment processing failed                |
| **Refunded**   | Order has been refunded                  |

### Updating Order Status

{% stepper %}
{% step %}
#### Step 1: Edit Order

Click **Edit** next to the order you want to update.
{% endstep %}

{% step %}
#### Step 2: Navigate to Order History

Scroll to the **Order History** section at the bottom of the order details page.
{% endstep %}

{% step %}
#### Step 3: Update Status

* Select the new **Order Status** from the dropdown
* Add a **Comment** for internal tracking
* Check **Notify Customer** to send email notification
* Click **Add History** to save the status change
{% endstep %}
{% endstepper %}

{% hint style="success" %}
**Best Practice** Always add comments when updating order status to maintain clear communication and audit trails for your team.
{% endhint %}

## Manual Order Creation

You can create orders manually for phone orders, in-person sales, or special customer requests:

{% stepper %}
{% step %}
#### Step 1: Start New Order

Click the **Add** button at the top of the Orders page.
{% endstep %}

{% step %}
#### Step 2: Customer Details

* **Select Customer**: Choose existing customer or enter new customer details
* **Customer Information**: Enter name, email, telephone
* **Customer Group**: Assign appropriate customer group
{% endstep %}

{% step %}
#### Step 3: Payment Details

* **Payment Address**: Select existing address or enter new payment address
* **Payment Method**: Choose payment method (Cash, Bank Transfer, etc.)
{% endstep %}

{% step %}
#### Step 4: Shipping Details

* **Shipping Address**: Select or enter shipping address
* **Shipping Method**: Choose shipping method
{% endstep %}

{% step %}
#### Step 5: Add Products

* **Choose Product**: Search and select products to add to order
* **Set Quantity**: Enter quantity for each product
* **Product Options**: Configure any product options if available
{% endstep %}

{% step %}
#### Step 6: Complete Order

* **Review Totals**: Verify order totals and applied discounts
* **Add Comments**: Include any special instructions
* **Save Order**: Click **Save** to create the order
{% endstep %}
{% endstepper %}

![Manual Order Creation Form](../../.gitbook/assets/manual-order-creation-form.png)

## Invoice Generation

Generate professional invoices for orders:

{% stepper %}
{% step %}
#### Step 1: Select Order

From the Orders list, select the checkbox next to the order(s) you want to invoice.
{% endstep %}

{% step %}
#### Step 2: Generate Invoice

Click the **Print Invoice** button at the top of the page.
{% endstep %}

{% step %}
#### Step 3: Print or Save

* **Print**: Use browser print function to print the invoice
* **Save as PDF**: Use browser's save as PDF option
* **Email**: Copy invoice details to email to customer
{% endstep %}
{% endstepper %}

![Invoice Generation Interface](../../.gitbook/assets/invoice-generation-interface.png)

## Advanced Features

### Reward Points Management

Manage customer reward points directly from orders:

* **Add Reward Points**: Award points for completed orders
* **Remove Points**: Deduct points for returns or adjustments
* **Track Point Balance**: Monitor customer reward point totals

### Affiliate Commission

Handle affiliate commissions through order management:

* **Commission Tracking**: Track affiliate commissions per order
* **Commission Payments**: Manage commission payouts
* **Affiliate Performance**: Monitor affiliate sales performance

### Subscription Orders

Orders containing subscription products include:

* **Subscription Details**: View subscription plan information
* **Billing Cycles**: Monitor recurring billing schedules
* **Subscription Status**: Track subscription active/canceled status

## Order Editing

You can modify existing orders to:

* **Update Customer Information**: Change contact details or addresses
* **Modify Products**: Add, remove, or change product quantities
* **Adjust Totals**: Apply discounts, coupons, or manual price adjustments
* **Update Shipping**: Change shipping method or address

{% hint style="warning" %}
**Important Note** When editing orders, be cautious about changing prices or totals as this may affect accounting and reporting accuracy.
{% endhint %}

## Bulk Actions

Perform actions on multiple orders simultaneously:

* **Delete Orders**: Remove multiple orders at once
* **Update Status**: Change status for multiple orders
* **Print Invoices**: Generate invoices for multiple orders

## Troubleshooting

<details>

<summary>Common Order Issues</summary>

#### Order Not Appearing

* Check if customer completed checkout
* Verify payment was processed successfully
* Ensure order status is not set to hidden

#### Missing Customer Information

* Verify customer account exists
* Check if guest checkout was used
* Ensure required fields were completed

#### Payment Processing Issues

* Confirm payment gateway configuration
* Check for declined payments
* Verify currency settings

#### Shipping Calculation Problems

* Validate shipping method settings
* Check product weight and dimensions
* Verify shipping zone configurations

</details>

## Best Practices

{% hint style="success" %}
**Order Management Tips**

* Update order status promptly to keep customers informed
* Use clear, descriptive comments in order history
* Regularly review and process pending orders
* Maintain accurate inventory through proper order processing
* Use filtering to manage high-volume order periods efficiently
{% endhint %}

## Next Steps

After mastering order management, explore:

* [**Returns Processing**](/broken/pages/zolHwG44CZrAZRfYgUfn) - Handling product returns and refunds
* [**Subscription Management**](/broken/pages/G5UHirojsEns7SW2aNYG) - Managing recurring orders
* [**Order Status Configuration**](https://github.com/wilsonatb/docs-oc-new/blob/main/admin-interface/system/localization/admin-interface/sales/order-statuses.md) - Customizing order status workflow
