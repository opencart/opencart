---
description: >-
  Managing regional settings, languages, currencies, taxes, and geographical
  data for multi-store and international operations
---

# Localization

## Introduction

The **Localization** section is the global configuration hub for all regional and geographical settings in your OpenCart store. This is where you define languages, currencies, countries, tax rules, measurement units, and order statuses—essential elements for operating in multiple regions or selling internationally.

Unlike store-specific settings, localization configurations apply across your entire OpenCart installation (including all stores in multi-store setups). These settings ensure consistent customer experiences regardless of language, currency, or location.

## Localization Modules

OpenCart's localization system is organized into several interconnected modules:

### Core Regional Settings

* **Languages**: Manage storefront and admin languages, including RTL support
* **Currencies**: Configure currency codes, symbols, and exchange rates
* **Countries & Zones**: Define geographical regions for shipping, taxes, and customer addresses

### Business Logic Configuration

* **Tax Classes & Rates**: Set up complex tax rules for different regions and product types
* **Order Statuses**: Customize order workflow with status labels and colors
* **Stock Statuses**: Define inventory availability messages (In Stock, Out of Stock, etc.)
* **Returns Management**: Configure return reasons, actions, and statuses

### Measurement Systems

* **Length Classes**: Define measurement units (centimeters, inches, etc.) for products
* **Weight Classes**: Configure weight units (kilograms, pounds, ounces, etc.) for shipping

### Geographical Zones

* **Geo Zones**: Create custom geographical groupings for shipping and tax rules that span multiple countries or regions

{% hint style="info" %}
**Multi-Store Consistency**: Localization settings are shared across all stores in a multi-store installation. This ensures customers see consistent currencies, languages, and tax rules regardless of which store they visit.
{% endhint %}

## Common Localization Tasks

### Setting Up a Multi-Language Store

1. Add languages in **System → Localization → Languages**
2. Install corresponding language packs (if available)
3. Translate product descriptions, categories, and information pages
4. Set default languages for storefront and admin in store settings

### Configuring International Taxes

1. Define countries and zones in **System → Localization → Countries & Zones**
2. Create tax classes for different product categories
3. Set tax rates based on geographical zones
4. Assign tax classes to products

### Managing Multiple Currencies

1. Add currencies with correct codes and symbols
2. Set up automatic exchange rate updates
3. Configure currency display formats
4. Enable currency switcher in storefront themes

## Best Practices

<details>

<summary><strong>Internationalization Strategy</strong></summary>

**Global Readiness**

* **Language Planning**: Add all languages before launching to avoid content gaps
* **Currency Setup**: Configure currencies with accurate decimal places and symbols
* **Tax Compliance**: Research local tax requirements before setting up rates
* **Measurement Consistency**: Use consistent units across all products

</details>

<details>

<summary><strong>Data Integrity</strong></summary>

**Accurate Configuration**

* **Country Codes**: Use ISO standard country and zone codes
* **Currency Precision**: Set correct decimal places for each currency
* **Tax Rate Accuracy**: Double-check tax percentages and applicability
* **Status Definitions**: Create clear, unambiguous status labels

</details>

{% hint style="warning" %}
**Critical Configuration Warning** ⚠️ Never delete default languages, currencies, or countries that are in use. Deleting a language used by customers or a currency used in orders can cause display issues. Instead, disable items you no longer need.
{% endhint %}

> "Localization transforms your store from a generic shop into a culturally aware marketplace. Each setting—from language to tax rate—bridges the gap between your products and your customers' expectations."
