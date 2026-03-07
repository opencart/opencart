---
description: Configuring server-side technical options, security, and SEO in OpenCart 4
---

# Server

## Introduction

The **Server** tab contains technical settings that affect the security, performance, and SEO of your OpenCart installation. These options interact directly with your web server and should be configured with care.

## Accessing Server Settings

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
#### Select Server Tab

In the store configuration interface, click the **Server** tab.
{% endstep %}
{% endstepper %}

## Configuration Fields

Below are the key fields found in the Server tab:

### Maintenance & SEO

* **Maintenance Mode**: Set to **Yes** to prevent customers from browsing your store while you are making updates. They will see a "Maintenance" message instead. Logged-in administrators can still see the storefront.
* **Use SEO URLs**: Set to **Yes** to enable friendly URLs (e.g., `/iphone` instead of `/index.php?route=product/product&product_id=42`).

### Performance

* **Compression Level**: GZIP compression level (0-9). Higher levels reduce the amount of data sent to the browser but use more CPU resources. A setting of `4` or `5` is usually balanced.

### Error Handling

* **Display Errors**: Set to **No** on live production sites to prevent technical details from being visible to customers.
* **Log Errors**: Set to **Yes** to record system errors in a file for troubleshooting.
* **Error Log Filename**: The name of the file where errors are saved (default is `error.log`).

{% hint style="danger" %}
**Security Risk**: Never set **Display Errors** to "Yes" on a live store. This can expose sensitive server information to malicious users.
{% endhint %}

## Common Tasks

### Enabling Maintenance Mode

When you need to perform updates or changes without customers seeing errors:

1. Navigate to the **Maintenance & SEO** section.
2. Set **Maintenance Mode** to **Yes**.
3. Click **Save**. Your store will now display a maintenance message to everyone except logged-in administrators.

### Activating SEO Friendly URLs

To make your URLs look professional and improve search rankings:

1. Set **Use SEO URLs** to **Yes**.
2. **Crucial Step**: You must rename the file `htaccess.txt` in your OpenCart root directory to `.htaccess`.
3. Ensure each product, category, and information page has a unique **SEO URL** (Keyword) assigned in its respective "SEO" tab.

## Best Practices

<details>

<summary><strong>SEO &#x26; Performance</strong></summary>

**Optimization Tips**

* **SEO URLs**: After enabling this, ensure your `.htaccess` file is correctly configured in your root directory.
* **Compression**: GZIP compression can significantly improve your Google PageSpeed score by reducing the size of HTML, CSS, and JS files.

</details>

<details>

<summary><strong>Maintenance &#x26; Security</strong></summary>

**Safe Updates**

* **Maintenance Mode**: Always enable this when performing major theme updates or installing new extensions to avoid customer errors during the process.

</details>

{% hint style="info" %}
**Error Logs**: If your store is acting strangely or showing a white page, the first place to check is the **Error Log** (found in Maintenance → Error Logs).
{% endhint %}

## Troubleshooting

<details>

<summary><strong>SEO URLs are not working (404 Error)</strong></summary>

**.htaccess and Server Config**

* **Check .htaccess**: Ensure you have renamed `htaccess.txt` to `.htaccess`.
* **Apache Rewrite**: Verify that your web server has the `mod_rewrite` module enabled.
* **RewriteBase**: If your store is in a subfolder (e.g., `yourstore.com/shop/`), you may need to edit `.htaccess` and set `RewriteBase /shop/`.
* **Keyword Uniqueness**: Ensure the SEO keyword you are using is not already taken by another product or category.

</details>

<details>

<summary><strong>The Store is showing a "White Page" (Blank)</strong></summary>

**Error Diagnostics**

* **Enable Error Logging**: Ensure **Log Errors** is set to **Yes**.
* **Check Error Logs**: Go to **System → Maintenance → Error Logs** to see the specific PHP error causing the crash.
* **Display Errors**: Temporarily set **Display Errors** to **Yes** (only on a development site) to see the error directly on the screen.

</details>

> "The Server settings are the protective walls and high-performance engine of your store. Balancing security with speed ensures a safe and fast experience for your users."
