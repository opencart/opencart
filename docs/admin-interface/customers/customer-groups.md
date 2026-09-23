---
description: Guide to creating and managing customer groups in OpenCart 4
---

# Customer Groups

{% hint style="info" %}
**Organizing Your Customers** Customer Groups allow you to categorize customers for targeted marketing, special pricing, and permission management in OpenCart 4.
{% endhint %}

## Introduction

Customer Groups in OpenCart 4 enable you to organize customers into logical categories. This powerful feature allows you to apply different settings, pricing, and permissions to different groups of customers, making it ideal for businesses that serve multiple customer segments.

## Default Customer Groups

OpenCart 4 comes with three default customer groups:

| Group         | Description                  | Typical Use                        |
| ------------- | ---------------------------- | ---------------------------------- |
| **Default**   | Standard customer group      | Regular retail customers           |
| **Retail**    | Retail customers             | General public shoppers            |
| **Wholesale** | Wholesale/business customers | B2B customers with special pricing |

{% hint style="success" %}
**Tip:** You can modify the default groups or create entirely new groups to match your business needs. The Default group cannot be deleted but can be modified.
{% endhint %}

## Accessing Customer Groups

To access the Customer Groups interface:

1. Log in to your OpenCart admin panel
2. Navigate to **Customers → Customer Groups**
3. You'll see the customer group list with existing groups

![Customer Groups List](../../.gitbook/assets/group-list.png)

## Creating a New Customer Group

{% stepper %}
{% step %}
**Step 1: Click Add New**

Click the **Add New** button (+) in the top-right corner of the customer group list.

_Figura 2: Add New button in customer groups list_
{% endstep %}

{% step %}
**Step 2: Configure Group Settings**

Fill in the group configuration form:

**General Settings**

{% hint style="info" %}
**Group Name & Description** 📝

* **Group Name:** Required, 3-32 characters per language, multilingual support
* **Description:** Optional internal notes for admin reference only
{% endhint %}

**Approval Settings**

{% hint style="warning" %}
**Approval Required** ⚠️

* **Yes:** Admin must manually approve each new registration (high-security stores, B2B portals)
* **No:** Automatic approval upon registration (standard retail stores, public websites)
{% endhint %}

**Display Settings**

{% hint style="success" %}
**Sort Order** 🔢

* **Purpose:** Controls display order in dropdown menus and lists
* **Lower numbers** appear first (e.g., 0 before 1)
* **Default:** 0 for default groups
{% endhint %}

![Customer Group Form](../../.gitbook/assets/group-form.png)
{% endstep %}

{% step %}
**Step 3: Save the Group**

Click **Save** to create the new customer group. You'll see a success message confirming the group has been created.
{% endstep %}
{% endstepper %}

## Editing an Existing Customer Group

To edit an existing customer group:

1. From the customer group list, click the **Edit** button (pencil icon) next to the group
2. Make your changes in the group form
3. Click **Save** to update the group settings

{% hint style="warning" %}
**Note:** You cannot delete the Default customer group, but you can edit its settings. Other groups can be deleted if they have no customers assigned to them.
{% endhint %}

## Group Configuration Details

<details>

<summary><strong>Group Name</strong></summary>

* **Required**: Yes
* **Length**: 3-32 characters per language
* **Multilingual**: Supports multiple languages for international stores

</details>

<details>

<summary><strong>Description</strong></summary>

* **Required**: No
* **Purpose**: Internal notes about the group's purpose
* **Visibility**: Not shown to customers, for admin reference only

</details>

<details>

<summary><strong>Approval Required</strong></summary>

This setting controls whether new customer registrations in this group require manual approval:

| Setting | Behavior                                          | Use Case                                                 |
| ------- | ------------------------------------------------- | -------------------------------------------------------- |
| **Yes** | Admin must manually approve each new registration | High-security stores, B2B portals, exclusive memberships |
| **No**  | Automatic approval upon registration              | Standard retail stores, public websites                  |

![Approval Required Setting](../../.gitbook/assets/approval-setting.png)

_Figura 4: Approval Required setting in customer group configuration_

</details>

<details>

<summary><strong>Sort Order</strong></summary>

* **Purpose**: Controls display order in dropdown menus
* **Lower numbers**: Appear first in lists
* **Default**: 0 for default groups

</details>

## Use Cases for Customer Groups

<details>

<summary><strong>1. Retail vs Wholesale Pricing 🛍️</strong></summary>

Create separate groups for retail and wholesale customers with different pricing rules:

* **Retail Group**: Standard pricing, no approval required
* **Wholesale Group**: Special pricing, approval required for new accounts

</details>

<details>

<summary><strong>2. Geographic Segmentation 🌍</strong></summary>

Create groups for customers in different regions or countries:

* **Domestic Customers**: Standard shipping rates
* **International Customers**: Higher shipping rates, different tax rules

</details>

<details>

<summary><strong>3. Customer Tier System 🥇</strong></summary>

Implement loyalty tiers based on purchase history:

* **Bronze**: New customers, basic benefits
* **Silver**: Regular customers, enhanced benefits
* **Gold**: VIP customers, premium benefits

</details>

<details>

<summary><strong>4. Business Customer Management 🏢</strong></summary>

Special groups for business customers:

* **Corporate Accounts**: Company-specific pricing, approval required
* **Government Accounts**: Special terms, documentation requirements

</details>

## Assigning Customers to Groups

<details>

<summary><strong>During Registration 📝</strong></summary>

Customers select their group during registration (if multiple groups are available and don't require approval).

</details>

<details>

<summary><strong>Manual Assignment 👤</strong></summary>

Admins can assign customers to groups:

1. Go to **Customers → Customers**
2. Edit a customer
3. Change the **Customer Group** in the General tab
4. Save the changes

</details>

## Integration with Other Features

<details>

<summary><strong>Custom Fields 📝</strong></summary>

Customer groups determine which custom fields are shown during registration and in customer profiles:

1. Create custom fields in **Customers → Custom Fields**
2. Assign fields to specific customer groups
3. Fields only appear for customers in those groups

</details>

<details>

<summary><strong>Pricing Rules 💰</strong></summary>

Use customer groups with special pricing extensions to offer group-specific pricing.

</details>

<details>

<summary><strong>Marketing Campaigns 📧</strong></summary>

Target email campaigns and promotions to specific customer groups.

</details>

<details>

<summary><strong>Permission Management 🔒</strong></summary>

Control access to certain store features based on customer group membership.

</details>

## Best Practices

{% hint style="success" %}
**Group Strategy** 🎯

1. **Start Simple**: Begin with basic groups (Retail, Wholesale) and expand as needed
2. **Clear Naming**: Use descriptive names that indicate the group's purpose
3. **Minimal Groups**: Create only as many groups as necessary to avoid complexity
{% endhint %}

{% hint style="warning" %}
**Approval Workflow** ⚠️

1. **Selective Approval**: Use approval requirements only for high-value or sensitive groups
2. **Clear Communication**: Inform customers about approval requirements during registration
3. **Timely Processing**: Regularly check and process approval requests
{% endhint %}

{% hint style="info" %}
**Group Maintenance** 🛠️

1. **Regular Review**: Periodically review group assignments and settings
2. **Clean Up**: Remove unused groups to simplify management
3. **Documentation**: Keep notes on group purposes and rules
{% endhint %}

## Troubleshooting

### Common Issues

<details>

<summary><strong>Group not appearing in registration 🔍</strong></summary>

**Solution:** Check group settings: Approval Required should be "No" for self-selection

</details>

<details>

<summary><strong>Cannot delete group 🗑️</strong></summary>

**Solution:** Ensure no customers are assigned to the group. Reassign customers first

</details>

<details>

<summary><strong>Custom fields not showing 📝</strong></summary>

**Solution:** Verify custom fields are assigned to the correct customer groups

</details>

<details>

<summary><strong>Approval emails not sending 📧</strong></summary>

**Solution:** Check email configuration and notification settings

</details>

{% hint style="info" %}
**Performance Considerations** ⚡

* Large numbers of customer groups can slow down registration and admin interfaces
* Consider using extensions for advanced group management if you need many groups
* Regularly clean up inactive groups and customer assignments
{% endhint %}

{% hint style="success" %}
**Documentation Summary** 📋

You've now learned how to:

* Create and manage customer groups in OpenCart 4
* Configure group settings and approval requirements
* Use customer groups for segmentation and targeting
* Integrate groups with other store features
* Apply best practices for group management

**Next Steps:**

* [Customer Management](/broken/pages/W3iuma9SRc05P2lExajW) - Learn how to manage individual customer accounts
* [Customer Approval](/broken/pages/Um8iYGrsf89Q8Rk9hmiF) - Set up and manage registration approval workflows
* [Custom Fields](/broken/pages/Ahlg4yE4ksx2AIcMmMVp) - Create custom form fields for different customer groups
* [GDPR Management](/broken/pages/qOJkXN41JqkLR52tEMIz) - Manage data privacy settings by customer group
{% endhint %}
