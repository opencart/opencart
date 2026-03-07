---
description: Advanced product identification and validation in OpenCart 4
---

# Product Identifiers

## Introduction

Product identifiers in OpenCart 4 provide advanced product tracking and validation capabilities. This system supports multiple identifier types with custom validation rules, ensuring data integrity and compatibility with global standards.

{% stepper %}
{% step %}
#### Step 1: Identifier Planning

Plan your identifier strategy and requirements

{% hint style="info" %}
**Planning Tip:** Consider your business needs, international requirements, and integration with existing systems when planning identifiers.
{% endhint %}
{% endstep %}

{% step %}
#### Step 2: System Configuration

Configure identifier types and validation rules

{% hint style="info" %}
**Configuration Strategy:** Set up validation rules and uniqueness requirements before assigning identifiers to products.
{% endhint %}
{% endstep %}

{% step %}
#### Step 3: Product Assignment

Assign identifiers to products with proper validation

{% hint style="info" %}
**Assignment Strategy:** Use consistent naming conventions and ensure all required identifiers are properly assigned.
{% endhint %}
{% endstep %}

{% step %}
#### Step 4: Quality Assurance

Validate and maintain identifier data integrity

{% hint style="info" %}
**Quality Strategy:** Regularly audit identifier data and maintain consistency across your product catalog.
{% endhint %}
{% endstep %}
{% endstepper %}

## Identifier Types

OpenCart 4 supports multiple standard identifier types:

| Identifier Type | Purpose                                            | Format                                | Region                 | Required    | Example           |
| --------------- | -------------------------------------------------- | ------------------------------------- | ---------------------- | ----------- | ----------------- |
| **SKU**         | Internal product tracking and inventory management | Alphanumeric, customizable            | Global                 | Recommended | PROD-001-2024     |
| **UPC**         | North American retail product identification       | 12-digit numeric                      | North America          | Optional    | 123456789012      |
| **EAN**         | European and international product identification  | 13-digit numeric                      | Europe & International | Optional    | 1234567890128     |
| **ISBN**        | Book and publication identification                | ISBN-10: 10 chars, ISBN-13: 13 digits | Global                 | For books   | 978-0-123456-78-9 |
| **MPN**         | Manufacturer-specific product identification       | Alphanumeric, manufacturer-defined    | Global                 | Optional    | ABC-123-XYZ       |

**Identifier Characteristics:**

* **SKU**: Customizable, internal use, highly flexible
* **UPC**: Standardized, retail-focused, North American market
* **EAN**: International standard, European market focus
* **ISBN**: Book industry specific, global standard
* **MPN**: Manufacturer-specific, technical products

### SKU (Stock Keeping Unit)

**Purpose:** Internal product tracking and inventory management

**Best Practices:**

| Practice                     | Description                             | Example       |
| ---------------------------- | --------------------------------------- | ------------- |
| **Consistent Format**        | Use same structure across all products  | PROD-001-2024 |
| **Category Codes**           | Include product category in identifier  | TSHIRT-RED-M  |
| **Avoid Special Characters** | Use only letters, numbers, hyphens      | PROD001-2024  |
| **Uniqueness**               | Ensure no duplicate SKUs                | PROD-001-2024 |
| **Sequential Numbering**     | Use sequential numbers for organization | 001, 002, 003 |
| **Year Coding**              | Include year for product lifecycle      | PROD-001-2024 |

**SKU Naming Strategies:**

* **Category-Product-Size**: CLO-TSH-RED-M (Clothing-TShirt-Red-Medium)
* **Manufacturer-Product**: MANUF-PROD-001 (Manufacturer-Product-001)
* **Sequential with Prefix**: PROD-001-2024 (Product-001-Year)
* **Location-Based**: WAREHOUSE-A-PROD-001 (Warehouse-Product-001)

### UPC (Universal Product Code)

**Purpose:** North American retail product identification

**Technical Details:**

* 12-digit numeric code
* Includes manufacturer code and product number
* Check digit validation
* GS1 US standard
* Example: 123456789012

### EAN (European Article Number)

**Purpose:** European and international product identification

**Technical Details:**

* 13-digit numeric code
* Includes country code, manufacturer code, product number
* Check digit validation
* GS1 international standard
* Example: 1234567890128

### ISBN (International Standard Book Number)

**Purpose:** Book and publication identification

**Technical Details:**

* ISBN-10: 10 characters (0-9, X for check digit)
* ISBN-13: 13 digits starting with 978 or 979
* Includes country/group, publisher, title, check digit
* Example: 978-0-123456-78-9

### MPN (Manufacturer Part Number)

**Purpose:** Manufacturer-specific product identification

**Best Practices:**

* Use manufacturer's exact part number
* Include revision codes if applicable
* Maintain consistency with manufacturer documentation
* Example: ABC-123-XYZ

## Implementation Guide

### Product Identifier Assignment

When adding products, you can assign multiple identifiers:

**Example Product Identifiers:**

* **SKU**: TSHIRT-RED-M
* **UPC**: 123456789012
* **MPN**: MANUF-123-X

## Best Practices

{% hint style="info" %}
**Identifier Strategy**

* Develop consistent naming conventions
* Consider future expansion needs
* Document identifier formats
* Train staff on proper usage
{% endhint %}

{% hint style="warning" %}
**Data Integrity**

* Validate identifiers at entry point
* Enforce uniqueness where required
* Regular data quality checks
* Backup identifier databases
{% endhint %}

{% hint style="success" %}
**Integration Planning**

* Coordinate with inventory systems
* Align with supplier identifier systems
* Plan for international expansion
* Consider barcode printing requirements
{% endhint %}

{% hint style="danger" %}
**Security Considerations**

* Avoid exposing internal identifiers publicly
* Use different identifiers for internal vs external
* Protect sensitive manufacturer codes
* Implement access controls
{% endhint %}

## Troubleshooting

### Common Identifier Issues

Duplicate Identifiers

**Problem:** Unique identifier conflicts

**Solutions:**

* Check existing product database
* Verify identifier uniqueness settings
* Implement identifier generation system
* Review bulk import processes

<details>

<summary>International Compatibility</summary>

**Problem:** Identifiers not recognized internationally

**Solutions:**

* Use standard formats (UPC, EAN, ISBN)
* Register with appropriate agencies
* Validate against international standards
* Consider regional requirements

</details>

## Next Steps

* [Learn about product variants](/broken/pages/cFve5DSbS2azs3ngfQrF)
* [Explore product management](/broken/pages/EsE5SjFTCoY94AE9VHIB)
* [Understand subscription products](/broken/pages/QoZ72xxe7XgreP2PZqAo)
* [Master product form tabs](/broken/pages/ppVKh3ctAf55cprlOM6c)
