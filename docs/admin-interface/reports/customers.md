---
description: >-
  Comprehensive analytics on customer behavior, engagement, search patterns, and
  financial activity
---

# Customers

## Introduction

**Customer Reports** offer deep insights into your audience's behavior and engagement with your store. By tracking everything from purchase history and reward point usage to search terms and real-time online activity, these reports help you understand who your customers are and what they are looking for. This data is invaluable for personalized marketing, improving user experience, and building customer loyalty.

## Accessing Customer Reports

{% stepper %}
{% step %}
#### Navigate to Reports

Go to **Reports → Reports** in your admin panel.
{% endstep %}

{% step %}
#### Select Report Type

From the **Report Type** dropdown menu, choose a customer-focused report (e.g., Customer Online, Customer Activity, Customer Orders, etc.).
{% endstep %}

{% step %}
#### Filter and Analyze

Use the available filters such as date ranges, customer names, or IP addresses to refine the data for analysis.
{% endstep %}
{% endstepper %}

## Customer Report Types

### 1. Customer Registration Report

Track new customer sign-ups over time.

* **Key Columns**: Date Start, Date End, No. Customers.
* **Usage**: Monitoring growth of your customer base, identifying peak registration periods, and measuring the effectiveness of marketing campaigns.

### 2. Customers Online

Monitor real-time activity on your storefront.

* **Key Columns**: IP, Customer Name, Last Page Visited, Referrer, and Last Click Time.
* **Usage**: Tracking current traffic and identifying which pages are currently attracting interest.

### 3. Customer Activity Report

Track specific actions taken by customers on your site.

* **Key Columns**: Customer, Comment (Action Description), IP, and Date Added.
* **Usage**: Auditing customer actions such as logins, account registrations, and order placements for security and engagement analysis.

### 4. Customer Search Report

See what terms customers are typing into your store's search bar.

* **Key Columns**: Keyword, Found Products, Category, Customer, IP, and Date Added.
* **Usage**: Identifying "gaps" in your catalog (products searched for but not found) and optimizing SEO/Product names.

### 5. Customer Orders Report

Analyze the purchasing patterns of individual customers.

* **Key Columns**: Customer Name, Email, Customer Group, Status, No. Orders, No. Products, and Total.
* **Usage**: Identifying your "VIP" customers who purchase most frequently or have the highest lifetime value.

### 6. Customer Reward Points Report

Monitor loyalty program participation and point balances.

* **Key Columns**: Customer Name, Email, Customer Group, Status, Reward Points, No. Orders, Total.
* **Usage**: Managing loyalty programs, identifying high-engagement customers, and optimizing reward point campaigns.

### 7. Customer Transaction Report

Track store credit (balance) additions and usage.

* **Key Columns**: Customer Name, Email, Customer Group, Status, Total.
* **Usage**: Monitoring store credit liabilities, tracking customer balances, and managing financial relationships.

## Common Tasks

### Identifying High-Value Customers

To find your top-spending customers:

1. Select **Customer Orders Report**.
2. Sort the report by the **Total** column in descending order.
3. Use this list to send exclusive offers or early access to new products to your best customers.

### Analyzing Search Trends for Stock Planning

To see what products you should consider adding:

1. Select **Customer Search Report**.
2. Look for frequently searched keywords that don't match your current inventory.
3. Use these insights to guide your next wholesale purchase or product development.

### Monitoring Customer Growth

To track new customer acquisition:

1. Select **Customer Registration Report**.
2. Group the data by month to see registration trends over time.
3. Compare periods to measure the impact of marketing campaigns or seasonal factors.

### Auditing Customer Activity for Security

To review suspicious account activity:

1. Select **Customer Activity Report**.
2. Filter by IP addresses to detect multiple accounts from the same location.
3. Review login patterns and account changes for potential security issues.

## Best Practices

<details>

<summary><strong>Customer Engagement Strategy</strong></summary>

* **Privacy Compliance**: Always handle customer IP and activity data in accordance with local privacy laws (GDPR, CCPA).
* **Segment by Group**: Use the Customer Group filter to see if wholesale customers behave differently than retail customers.
* **Monitor Search Failure**: Pay close attention to search terms; if customers search for "free shipping" frequently, consider making your shipping policy more visible.

</details>

<details>

<summary><strong>Data Management &#x26; Retention</strong></summary>

* **Regular Cleanup**: Customer activity and search logs can grow very large over time. Periodically archive or purge old logs to maintain database performance.
* **Backup Before Deletion**: Always backup your database before clearing historical report data.
* **Data Accuracy**: Ensure customer statuses are up-to-date for accurate segmentation in reports.

</details>

{% hint style="info" %}
**Pro Tip**: Combine Customer Orders Report with Customer Reward Points Report to identify customers who make frequent purchases but don't participate in your loyalty program. Target them with special reward point offers.
{% endhint %}

{% hint style="warning" %}
**Data Retention**: Customer activity and search logs can grow very large over time. Periodically check your database size and consider clearing old logs if your server performance is affected.
{% endhint %}

## Troubleshooting

<details>

<summary><strong>Customer reports show incomplete or missing data</strong></summary>

**Data Coverage Issues**

* **Date Range Check**: Verify the date range filter includes the period you're analyzing.
* **Customer Status Filter**: Ensure you're not filtering out customers with specific statuses (e.g., only showing "Enabled" customers).
* **Report Configuration**: Check that the report extension is enabled in **Extensions → Extensions → Reports**.
* **Database Consistency**: Run database maintenance tools to ensure customer data tables are properly indexed and optimized.

</details>

<details>

<summary><strong>Customer activity or search logs not appearing</strong></summary>

**Logging Issues**

* **System Settings**: Verify that customer activity logging is enabled in **System → Settings → Server tab**.
* **Permission Settings**: Ensure your user group has permission to view customer activity data.
* **Cache Issues**: Clear OpenCart cache to refresh logged data.
* **Extension Conflicts**: Disable recently installed extensions to check for conflicts with logging functionality.

</details>

<details>

<summary><strong>Reports loading slowly or timing out</strong></summary>

**Performance Issues**

* **Date Range Reduction**: Narrow the date range for faster processing, especially for activity and search reports.
* **Database Optimization**: Optimize database tables, especially customer-related tables (customer, customer\_activity, customer\_search).
* **Server Resources**: Check server memory and CPU usage during report generation.
* **Scheduled Reports**: Generate complex reports during off-peak hours.

</details>

<details>

<summary><strong>Customer data discrepancies between reports</strong></summary>

**Data Consistency Issues**

* **Status Mismatch**: Verify customer statuses are consistent across all systems.
* **Time Zone Settings**: Check that your server time zone matches your store's time zone setting.
* **Data Synchronization**: Ensure that customer data updates are properly synchronized across all relevant tables.
* **Extension Interference**: Some extensions may modify customer data flow; check for conflicts.

</details>

> "Customers are the lifeblood of any business. Understanding their behavior isn't just about tracking numbers—it's about listening to their story through data, anticipating their needs, and building relationships that last beyond a single transaction."
