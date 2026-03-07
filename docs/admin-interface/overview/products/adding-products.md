---
description: Step-by-step guide to adding products in OpenCart 4
---

# Adding Products

## Introduction

Adding products is the foundation of your e-commerce store. This comprehensive guide walks you through the complete process of creating products in OpenCart 4, from basic setup to advanced configurations.

{% hint style="info" %}
**Quick Reference**

* **Basic Product**: Name, price, quantity, status
* **Complete Setup**: All tabs for full product details
* **Variants**: Options for size, color, etc.
* **Subscriptions**: Recurring billing products
{% endhint %}

## Quick Start: Basic Product

{% stepper %}
{% step %}
#### Step 1: Access Product Management

![Product Management](../../../.gitbook/assets/product-management.png)

1. **Log in to your OpenCart admin panel**
2. **Navigate to Catalog → Products**
3. **Click the "Add New" button** (top right)

{% hint style="info" %}
**Quick Access Tip:** You can also use keyboard shortcuts to navigate quickly through the admin panel.
{% endhint %}
{% endstep %}

{% step %}
#### Step 2: Essential Information

Fill in the minimum required fields to create a basic product:

**Required Fields:**

* **Product Name**: Enter a clear, descriptive name that customers will see
* **Meta Title**: Create a search engine-friendly title
* **Model**: Add your internal product code or SKU
* **Price**: Set the selling price
* **Quantity**: Enter initial stock level
* **Stock Status**: Select "In Stock"
* **Status**: Enable to make product visible

{% hint style="info" %}
**Quick Setup Tips:**

* Use descriptive names that include key features
* Create unique model numbers for easy tracking
* Set realistic stock levels based on your inventory
* Enable products immediately if ready for sale
{% endhint %}
{% endstep %}

{% step %}
#### Step 3: Save and Verify

1. **Click "Save"** (bottom right)
2. **Verify product appears in product list**
3. **Check storefront visibility**

{% hint style="success" %}
**Basic Product Complete!** Your product is now live in your store. Customers can find and purchase it immediately.
{% endhint %}
{% endstep %}
{% endstepper %}

## Comprehensive Product Setup

For detailed information about each product form tab, refer to the [Product Form Tabs](/broken/pages/ppVKh3ctAf55cprlOM6c) guide which provides comprehensive explanations of all tabs and their settings.

### Recommended Workflow

Follow this systematic approach when creating products:

{% stepper %}
{% step %}
**Step 1: Basic Information (General Tab)**

* Enter product name, description, and meta information
* Complete all required fields in your primary language
* Add translations for multi-language stores

{% hint style="info" %}
**Pro Tip:** Start with your main language first, then add translations to ensure consistency across all language versions.
{% endhint %}
{% endstep %}

{% step %}
**Step 2: Technical Configuration (Data Tab)**

* Set pricing, inventory levels, and product identifiers
* Configure shipping dimensions and weight
* Assign appropriate tax classes

{% hint style="info" %}
**Pro Tip:** Use consistent SKU naming conventions across your product catalog for better inventory management.
{% endhint %}
{% endstep %}

{% step %}
**Step 3: Organization (Links Tab)**

* Assign product to relevant categories
* Select manufacturer and apply filters
* Set up related products for cross-selling

{% hint style="info" %}
**Pro Tip:** Use multiple category assignments to help customers find products through different navigation paths.
{% endhint %}
{% endstep %}

{% step %}
**Step 4: Specifications (Attribute Tab)**

* Add detailed product attributes and specifications
* Include material, care instructions, and technical details
* Provide comprehensive product information

{% hint style="info" %}
**Pro Tip:** Create attribute groups for different product types to maintain consistency across your catalog. Learn more in the [Product Attributes](/broken/pages/VaRbGTCgrKznpxkew1Yd) guide.
{% endhint %}
{% endstep %}

{% step %}
**Step 5: Variations (Option Tab)**

* Configure product options like sizes, colors, or styles
* Set up required and optional choices
* Define price adjustments for different options

{% hint style="info" %}
**Pro Tip:** Complete option setup before creating variants to ensure all combinations are properly configured. Learn more in the [Product Options](/broken/pages/PSxHqzfAVUmCvJg8B3RC) guide.
{% endhint %}
{% endstep %}

{% step %}
**Step 6: Visual Content (Image Tab)**

* Upload high-quality product images
* Set main image and additional views
* Organize image display order

{% hint style="info" %}
**Pro Tip:** Use consistent image backgrounds and lighting across your product catalog for a professional appearance.
{% endhint %}
{% endstep %}

{% step %}
**Step 7: Marketing & SEO**

* Configure discounts and special pricing
* Set up SEO-friendly URLs
* Add reward points for loyalty programs

{% hint style="info" %}
**Pro Tip:** Create unique meta descriptions and titles for each product to improve search engine visibility.
{% endhint %}
{% endstep %}
{% endstepper %}

## Advanced Configurations

### Creating Product Variants

For detailed information about creating and managing product variants, refer to the [Product Variants](/broken/pages/cFve5DSbS2azs3ngfQrF) guide.

**Basic Variant Creation Steps:**

1. Create master product with options
2. Use the "Variant" button from the product list
3. Configure variant-specific pricing and inventory
4. Save variants

### Subscription Product Setup

For comprehensive subscription product configuration, see the [Subscription Products](/broken/pages/QoZ72xxe7XgreP2PZqAo) guide.

**Basic Subscription Setup:**

1. Configure subscription plans first
2. Assign plans to products in the Subscription tab
3. Set customer group pricing
4. Configure trial periods if needed

## Validation and Error Handling

<details>

<summary><strong>Common Validation Rules</strong></summary>

**Required Fields and Validation:**

* **Product Name**: Required, maximum 255 characters
* **Meta Title**: Required, maximum 255 characters
* **Model**: Required, maximum 64 characters
* **Price**: Required, must be a valid number greater than 0

**Common Error Messages:**

* "Product name is required"
* "Meta title is required"
* "Model is required"
* "Valid price is required"

</details>

<details>

<summary><strong>Troubleshooting Common Issues</strong></summary>

**Product won't save:**

* Check all required fields are filled
* Verify field length limits
* Ensure numeric fields contain numbers
* Check for special character issues

**Product not visible in store:**

* Verify status is set to "Enabled"
* Check date availability
* Confirm store assignment
* Review category assignments

**Images not displaying:**

* Verify image file paths
* Check file permissions
* Confirm image file types
* Review image size limits

</details>

## Best Practices

{% hint style="info" %}
**Product Setup Workflow**

* Start with General tab for basic info
* Configure Data tab for pricing and inventory
* Set up Links for categorization
* Add Attributes for specifications
* Create Options for variations
* Upload Images for visual appeal
* Configure SEO for discoverability
{% endhint %}

{% hint style="warning" %}
**Data Accuracy**

* Double-check pricing before saving
* Verify inventory quantities
* Test option combinations
* Preview before publishing
{% endhint %}

{% hint style="success" %}
**SEO Optimization**

* Use keyword-rich product names
* Write unique meta descriptions
* Include relevant tags
* Create SEO-friendly URLs
* Optimize image alt text
{% endhint %}

{% hint style="danger" %}
**Inventory Management**

* Set realistic stock levels
* Enable stock tracking
* Configure minimum quantities
* Monitor low stock alerts
{% endhint %}

## Product Validation Checklist

Use this checklist to ensure your product is properly configured before publishing:

* [ ] **Basic Information**
  * [ ] Product name is descriptive and complete
  * [ ] Meta title is unique and SEO-friendly
  * [ ] Model/SKU is unique and follows naming convention
  * [ ] Product description is comprehensive
* [ ] **Pricing & Inventory**
  * [ ] Price is accurate and competitive
  * [ ] Tax class is correctly assigned
  * [ ] Stock quantity is realistic
  * [ ] Stock status is set appropriately
  * [ ] Minimum quantity is configured if needed
* [ ] **Categorization & Organization**
  * [ ] Product is assigned to correct categories
  * [ ] Manufacturer is selected if applicable
  * [ ] Related products are configured
  * [ ] Filters are applied for better searchability
* [ ] **Visual & Technical**
  * [ ] Main product image is high quality
  * [ ] Additional images show different angles
  * [ ] Product attributes are complete
  * [ ] Options are configured correctly
  * [ ] SEO URL is clean and descriptive
* [ ] **Final Verification**
  * [ ] Product status is set to "Enabled"
  * [ ] Date available is correct
  * [ ] Store assignment is correct
  * [ ] Product appears correctly in storefront

## Next Steps

* [Learn about product management](/broken/pages/EsE5SjFTCoY94AE9VHIB)
* [Explore product variants](/broken/pages/cFve5DSbS2azs3ngfQrF)
* [Understand subscription products](/broken/pages/QoZ72xxe7XgreP2PZqAo)
* [Master product identifiers](/broken/pages/RZcvJdsGlV3nQ0ISkoPV)
* [Detailed product form tabs guide](/broken/pages/ppVKh3ctAf55cprlOM6c)
