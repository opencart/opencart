---
description: Configuring store localization, languages, and currencies in OpenCart 4
---

# Local

## Introduction

The **Local** tab is responsible for adapting your store to a specific region and language. These settings affect how customers see your storefront, which currency is used for transactions, and what units of measurement are applied to your products.

## Accessing Local Settings

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
#### Select Local Tab

In the store configuration interface, click the **Local** tab.
{% endstep %}
{% endstepper %}

## Configuration Fields

Below are the key fields found in the Local tab:

### Geography & Language

* **Country**: Select the primary country where your store is located.
* **Region / State**: Select the specific region or state.
* **Language**: Select the default language for the storefront (Catalog).
* **Administration Language**: Select the language used in the admin dashboard.

### Currency & Auto-Updates

* **Currency**: Select the default currency used for product prices.
* **Auto Update Currency**: Set this to **Yes** to allow OpenCart to automatically fetch and update currency exchange rates daily.

### Units of Measurement

* **Length Class**: The default unit for product dimensions (e.g., Centimeter, Inch).
* **Weight Class**: The default unit for product weight (e.g., Kilogram, Pound).

{% hint style="info" %}
**Note**: To add more languages or currencies beyond the defaults, you must first configure them under **System → Localization**. Once added there, they will appear as options in this Local tab.
{% endhint %}

## Common Tasks

### Adding a New Language to the Storefront

If you want to offer your store in multiple languages:

1. Go to **System → Localization → Languages** and add the new language first.
2. Navigate back to **System → Settings → Local**.
3. Select the new language from the **Language** dropdown.
4. Click **Save**.

### Enabling Automatic Currency Updates

To ensure your prices stay accurate for international customers:

1. Navigate to the **Currency & Auto-Updates** section.
2. Set **Auto Update Currency** to **Yes**.
3. Ensure your default currency (set in the **Currency** dropdown) is the base currency you want others to calculate from.

## Best Practices

<details>

<summary><strong>Currency Management</strong></summary>

**Dynamic Exchange Rates**

* **Default Currency**: Always set your default currency to the one you use for bookkeeping and pricing your products in the backend.
* **Auto-Update**: Enabling auto-update is recommended if you accept multiple currencies to ensure your prices remain competitive and accurate against the market.

</details>

<details>

<summary><strong>Localization for User Experience</strong></summary>

**Tailored Shopping**

* **Region Accuracy**: Ensure your country and region are accurate, as these are often used as the starting point for shipping and tax calculations.
* **Unit Consistency**: Choose units that are standard for your primary target market to avoid confusing your customers (e.g., use Kilograms for Europe and Pounds for the USA).

</details>

{% hint style="success" %}
**Language Tip**: Providing a storefront in the local language of your customers significantly increases conversion rates and trust.
{% endhint %}

## Troubleshooting

<details>

<summary><strong>Currency Rates are not updating</strong></summary>

**Localization and Server Issues**

* **Check Localization Settings**: Ensure the currencies you want to update are correctly set up in **System → Localization → Currencies**.
* **Server Connectivity**: Your server must be able to connect to external currency API services. Check with your host if outgoing connections are restricted.
* **Manual Update**: You can manually update rates by clicking the "Refresh" button in the Currencies list.

</details>

<details>

<summary><strong>Measurements are appearing incorrectly on products</strong></summary>

**Default Class and Product Data**

* **Default Class**: Verify that the correct **Length Class** and **Weight Class** are selected in the Local tab.
* **Product Data**: Individual products must have their own weight and dimensions defined in their "Data" tab. Ensure they are using the same units set as default here.

</details>

> "Localization is about more than just translation; it's about making your customer feel at home. Correct regional settings are the foundation of global commerce."
