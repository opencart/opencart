---
description: >-
  Detailed analytics on store sales, including orders, taxes, shipping, returns,
  and coupon usage
---

# Sales

## Introduction

**Sales Reports** provide essential data for understanding your store's financial performance. By analyzing sales trends, tax collections, shipping costs, and the impact of returns and coupons, you can gain a clear picture of your revenue streams and operational efficiency. These reports are crucial for financial planning, tax compliance, and identifying areas for logistical improvement.

## Accessing Sales Reports

{% stepper %}
{% step %}
#### Navigate to Reports

Go to **Reports → Reports** in your admin panel.
{% endstep %}

{% step %}
#### Select Report Type

From the **Report Type** dropdown menu, choose one of the sales-related reports (Orders, Tax, Shipping, Returns, or Coupons).
{% endstep %}

{% step %}
#### Filter and Analyze

Apply date ranges, group by time periods (day, week, month, year), and filter by order status to focus on the data you need.
{% endstep %}
{% endstepper %}

## Sales Report Types

### 1. Sales Order Report

This report provides a high-level overview of orders placed within a specific period.

* **Group By**: Day, Week, Month, or Year.
* **Key Columns**: Date Start, Date End, No. Orders, No. Products, Tax, and Total.
* **Usage**: Tracking revenue growth over time and identifying peak sales periods.

### 2. Tax Report

The Tax Report breaks down the taxes collected on orders.

* **Key Columns**: Tax Title, No. Orders, and Total Tax.
* **Usage**: Essential for accounting and filing tax returns by showing how much tax was collected under different tax classes.

### 3. Shipping Report

This report analyzes shipping costs and methods.

* **Key Columns**: Shipping Title, No. Orders, and Total Shipping.
* **Usage**: Evaluating which shipping methods are most popular and understanding total shipping expenditure/revenue.

### 4. Returns Report

Track product returns and their impact on your store.

* **Key Columns**: Date Start, Date End, No. Returns.
* **Usage**: Identifying trends in product returns to investigate potential quality issues or customer dissatisfaction.

### 5. Coupon Report

Measure the effectiveness of your promotional coupons.

* **Key Columns**: Coupon Name, Code, No. Orders, and Total Discount.
* **Usage**: Understanding which promotions are driving sales and the total cost of discounts given to customers.

## Common Tasks

### Generating a Monthly Sales Summary

To see how your store performed last month:

1. Select **Sales Report** from the report type list.
2. Set the **Date Start** to the first day of the month and **Date End** to the last day.
3. Set **Group By** to "Month".
4. Select "Complete" (or similar) in **Order Status** to exclude pending or failed orders.
5. Click **Filter**.

### Auditing Tax Collections

To prepare for tax filing:

1. Select **Tax Report**.
2. Set the date range for your filing period (e.g., quarterly or annually).
3. Filter to see the breakdown by tax type.
4. Export the data for your accountant.

## Best Practices

<details>

<summary><strong>Financial Accuracy</strong></summary>

* **Filter by Status**: Always filter by "Complete" or "Processed" order statuses for final revenue figures. Including "Pending" orders can inflate your perceived performance.
* **Regular Audits**: Cross-reference your sales reports with your payment gateway statements monthly to ensure data consistency.
* **Monitor Return Rates**: A sudden spike in the Returns Report for a specific period should trigger an immediate review of recent product batches or shipping handling.

</details>

{% hint style="info" %}
**Pro Tip**: Use the "Group By" feature in the Sales Order Report to identify which day of the week your customers are most active. This can help you schedule marketing emails or flash sales for maximum impact.
{% endhint %}

## Troubleshooting

<details>

<summary><strong>Sales reports show incorrect totals or missing orders</strong></summary>

**Data Accuracy Issues**

* **Order Status Filter**: Verify you're including the correct order statuses (Complete, Processing, etc.) in your report filter.
* **Date Range**: Ensure the date range covers the period you're analyzing.
* **Currency Conversion**: If using multiple currencies, check that conversions are calculated correctly.
* **Tax Settings**: Verify tax inclusion/exclusion settings match your reporting requirements.

</details>

<details>

<summary><strong>Tax or shipping reports don't match financial records</strong></summary>

**Financial Reconciliation Issues**

* **Tax Configuration**: Check that tax classes and rates are correctly configured.
* **Shipping Method Settings**: Verify shipping costs are properly recorded for each method.
* **Refund Handling**: Ensure refunds are properly accounted for in financial reports.
* **Time Zone Alignment**: Confirm that report dates align with your accounting periods.

</details>

<details>

<summary><strong>Returns report shows unexpected spikes or drops</strong></summary>

**Return Analysis Issues**

* **Return Status Filter**: Verify you're viewing the correct return statuses (Pending, Complete, etc.).
* **Product Quality**: Investigate specific products with high return rates for quality issues.
* **Seasonal Patterns**: Consider seasonal factors that may affect return rates.
* **Policy Changes**: Recent changes to return policies may affect return reporting.

</details>

<details>

<summary><strong>Coupon report doesn't reflect all discount usage</strong></summary>

**Discount Tracking Issues**

* **Coupon Status**: Ensure coupons are active and within their validity period.
* **Usage Limits**: Check if coupons have usage limits that have been reached.
* **Cart Conditions**: Verify that coupon conditions (minimum purchase, product categories) are being met.
* **Multiple Discounts**: If multiple discounts apply, the report may only track the primary coupon.

</details>

> "Sales data is the financial heartbeat of your store. Each report is a vital sign—orders show circulation, taxes reveal regulatory health, shipping indicates logistical fitness, and returns signal customer satisfaction. Monitor them regularly to keep your business thriving."
