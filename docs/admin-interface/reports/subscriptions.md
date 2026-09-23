---
description: >-
  Analyze recurring revenue, active subscriptions, and renewal trends for
  subscription-based products
---

# Subscriptions

## Introduction

**Subscription Reports** are designed for stores that offer products with recurring payments. By tracking active, canceled, and expiring subscriptions, you can project future revenue and monitor the health of your subscription programs. This data is critical for understanding churn rates and identifying which subscription plans are most attractive to your customers.

## Accessing Subscription Reports

{% stepper %}
{% step %}
#### Navigate to Reports

Go to **Reports → Reports** in your admin panel.
{% endstep %}

{% step %}
#### Select Report Type

From the **Report Type** dropdown menu, choose **Subscriptions**.
{% endstep %}

{% step %}
#### Filter and Analyze

Filter by subscription status and date ranges to understand your recurring revenue performance.
{% endstep %}
{% endstepper %}

## Subscription Report Details

The report provides insights into your recurring payment products:

* **Key Columns**: Date Start, Date End, No. Subscriptions, No. Products, Tax, Total.
* **Usage**: Tracking the growth of your subscription base and the resulting order volume.

## Common Tasks

### Tracking Subscription Growth

To see how your subscription model is scaling:

1. Run a **Subscription Report** for the current quarter.
2. Group the data by month.
3. Observe the trend in the **No. Subscriptions** column to see if your base is growing.

### Projecting Recurring Revenue

To estimate income from existing subscriptions:

1. Filter the report by "Active" subscription status.
2. Observe the **Total** revenue generated in previous periods.
3. Use these figures as a baseline for your next month's revenue projections.

## Best Practices

<details>

<summary><strong>Subscription Management</strong></summary>

* **Monitor Expirations**: Regularly check the number of subscriptions nearing expiration to plan re-engagement campaigns.
* **Status Integrity**: Ensure subscription statuses are accurately updated (Active, Canceled, Suspended) for precise reporting.
* **Review Plan Popularity**: If one subscription plan has much higher numbers than others, consider why and apply those successful elements to other plans.

</details>

{% hint style="info" %}
**Subscription Tip**: Use this report to identify the most popular billing cycles (e.g., monthly vs. yearly) to tailor your offerings.
{% endhint %}

## Troubleshooting

<details>

<summary><strong>Subscription report shows zero or incorrect data</strong></summary>

**Data Accuracy Issues**

* **Status Filter**: Verify you're not filtering by subscription status that excludes active subscriptions.
* **Date Range**: Ensure the date range includes periods when subscriptions were active.
* **Extension Status**: Check that the subscription extension is enabled in **Extensions → Extensions → Reports**.
* **Subscription Configuration**: Verify subscriptions are properly set up with recurring payment profiles.

</details>

<details>

<summary><strong>Subscription counts don't match actual active subscriptions</strong></summary>

**Data Synchronization Issues**

* **Payment Gateway Sync**: Some payment gateways may not sync subscription status in real-time.
* **Status Updates**: Ensure subscription statuses are updated correctly when payments succeed/fail.
* **Time Zone Differences**: Check that date calculations account for your store's time zone.
* **Cache Issues**: Clear OpenCart cache to refresh subscription data.

</details>

<details>

<summary><strong>Tax column shows incorrect values</strong></summary>

**Tax Calculation Issues**

* **Tax Configuration**: Verify tax settings for subscription products are configured correctly.
* **Tax Class Assignment**: Ensure subscription products have the appropriate tax class assigned.
* **Regional Tax Rules**: Check that tax rules apply correctly to the customer's location.
* **Currency Conversion**: If using multiple currencies, ensure tax amounts are converted correctly.

</details>

<details>

<summary><strong>Report generation is slow</strong></summary>

**Performance Issues**

* **Date Range Reduction**: Narrow the date range for faster processing.
* **Database Optimization**: Optimize database tables, especially subscription-related tables.
* **Server Resources**: Check server memory and CPU usage during report generation.
* **Scheduled Reports**: Generate subscription reports during off-peak hours.

</details>

> "Recurring revenue transforms customers into communities and transactions into relationships. Subscriptions aren't just about predictable income—they're about building lasting value and continuous engagement with your audience."
