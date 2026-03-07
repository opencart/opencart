---
description: Configuring store contact details and location information in OpenCart 4
---

# Store

## Introduction

The **Store** tab contains your business's contact details and physical location information. This is where you define the store owner, address, email, phone number, opening hours, and any additional notes that appear on your storefront's contact page.

## Accessing Store Settings

{% stepper %}
{% step %}
#### Navigate to Settings

Log in to your admin dashboard and go to **System → Settings**.
{% endstep %}

{% step %}
#### Edit Store

Find your store in the list (usually "Your Store" by default) and click the **Edit** (blue pencil) button on the right.
{% endstep %}

{% step %}
#### Select Store Tab

In the store configuration interface, click the **Store** tab.
{% endstep %}
{% endstepper %}

## Configuration Fields

Below are the key fields found in the Store tab:

### Store Identity

* **Store Owner**: **(Required)** The name of the person or entity that owns the store.
* **Address**: **(Required)** The physical location of your business. This will be displayed on the "Contact Us" page.
* **Geocode**: Optional GPS coordinates for your location (used by some themes for maps).
* **E-Mail**: **(Required)** The primary contact email for the store. This is where customer inquiries from the contact form will be sent.
* **Telephone**: The public business phone number.
* **Image**: An image representing your physical store (displayed on the contact page in some themes).

### Business Information

* **Opening Times**: Your business hours (e.g., "Mon-Fri 9am to 6pm").
* **Comment**: Any additional notes you want to display on the contact page (e.g., "We do not accept checks").
* **Store Location**: Select which of your store locations (if you have multiple) should be displayed on the contact page.

{% hint style="info" %}
**Multi-Store Contact Information**: In a multi-store environment, each store can have its own unique contact details, allowing you to have different owners, addresses, emails, and phone numbers for different brands.
{% endhint %}

## Common Tasks

### Updating Store Contact Information

To update the details shown on your "Contact Us" page:

1. Enter your new business address in the **Address** field.
2. Update the **E-Mail** and **Telephone** fields with your current support contact details.
3. Click **Save**. These changes will reflect immediately on the storefront.

### Adding Business Hours

To let customers know when you are open:

1. Locate the **Opening Times** field.
2. Enter your hours in a clear format (e.g., "Mon-Sat: 09:00 - 18:00").
3. These will be displayed on the contact page of most themes.

## Best Practices

<details>

<summary><strong>Contact Information Accuracy</strong></summary>

**Trust and Transparency**

* **Address**: Even if you are an online-only store, providing a physical address or P.O. Box increases customer trust.
* **E-Mail**: Use a professional domain email (e.g., `sales@yourstore.com`) rather than a generic one (e.g., `yourstore@gmail.com`).
* **Opening Times**: Clearly stating when you are available for support helps manage customer expectations.

</details>

<details>

<summary><strong>Geocode and Location</strong></summary>

**Mapping and Discoverability**

* **Geocode**: Adding precise coordinates improves accuracy when integrating with map services.
* **Store Location**: If you have multiple physical locations, select which ones should appear on the contact page.
* **Image**: Use a high-quality photo of your storefront or location to build trust with customers.

</details>

{% hint style="warning" %}
**Email Accuracy** ⚠️ Ensure the email address entered here is correct and monitored. It is the primary bridge between your customers and your sales team.
{% endhint %}

## Troubleshooting

<details>

<summary><strong>Contact information is not updating on the storefront</strong></summary>

**Cache and Theme Issues**

* **Cache Issue**: Your browser or a server-side cache (like Cloudflare or an OpenCart cache extension) might be showing an old version. Clear your browser cache or the OpenCart theme cache.
* **Theme Specifics**: Some custom themes might use their own settings instead of the default OpenCart Store settings. Check your theme's documentation if changes don't reflect.

</details>

<details>

<summary><strong>Emails from the Contact Us form are not arriving</strong></summary>

**Connectivity and Protocol**

* **Check Store E-Mail**: Ensure the email address in the **E-Mail** field is correct.
* **Mail Protocol**: If the email is correct but you are still not receiving messages, your [Mail Settings](/broken/pages/O3kpr2RpAGvxgIoY8LIZ) might be incorrectly configured. We recommend using **SMTP** for better reliability.

</details>

> "Your store's contact information is the bridge between you and your customers. Accuracy here builds trust and ensures they can reach you when needed."
