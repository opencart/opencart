---
description: >-
  Track the effectiveness of marketing campaigns, tracking codes, and
  promotional efforts
---

# Marketing

## Introduction

**Marketing Reports** are essential for measuring the return on investment (ROI) of your promotional activities. By using tracking codes on external links (emails, social media, ads), you can see exactly which sources are bringing in visitors and, more importantly, which ones are resulting in orders. This data allows you to focus your budget and efforts on the most profitable channels.

## Accessing Marketing Reports

{% stepper %}
{% step %}
#### Navigate to Reports

Go to **Reports → Reports** in your admin panel.
{% endstep %}

{% step %}
#### Select Report Type

From the **Report Type** dropdown menu, choose **Marketing**.
{% endstep %}

{% step %}
#### Filter and Analyze

Filter by date ranges and order status to see which campaigns have converted into completed sales.
{% endstep %}
{% endstepper %}

## Marketing Report Details

The report tracks the following metrics for each of your marketing campaigns (defined in **Marketing → Marketing**):

* **Campaign Name**: The name you've given to your marketing campaign.
* **Code**: The unique tracking code appended to your campaign URLs.
* **Clicks**: The total number of times the tracked link has been clicked.
* **Orders**: The total number of orders placed by customers who arrived via that campaign.
* **Total**: The total revenue generated from those orders.

## Common Tasks

### Comparing Campaign Performance

To see which ad platform is performing better:

1. Create separate tracking codes for "Facebook\_Ads" and "Google\_Ads".
2. After a period of time, run the **Marketing Report**.
3. Compare the **Total** revenue and **Orders** count against the amount you spent on each platform to calculate your ROI.

### Auditing Email Marketing

To measure the success of a newsletter:

1. Use a unique tracking code for each newsletter you send out.
2. Filter the report to the week following the newsletter release.
3. See how many clicks and orders resulted from that specific email blast.

## Best Practices

<details>

<summary><strong>Tracking Accuracy</strong></summary>

* **Consistent Naming**: Use clear and consistent names for your campaigns to make them easy to identify in long reports.
* **Test Links**: Always click on your tracked links before sending them out to ensure they are being recorded in the system.
* **Status Filtering**: Just like with Sales reports, ensure you filter by "Complete" status to see actual realized revenue from your campaigns.

</details>

{% hint style="info" %}
**Pro Tip**: You can use tracking codes on your own blog posts or affiliate sites to see which specific content is most effective at driving sales.
{% endhint %}

## Troubleshooting

<details>

<summary><strong>Marketing campaign data not appearing in reports</strong></summary>

**Tracking Issues**

* **Campaign Status**: Verify the marketing campaign is active and has tracking codes generated.
* **Link Configuration**: Ensure tracking codes are correctly appended to your campaign URLs.
* **Cookie Settings**: Check that your store's cookie settings allow tracking of referral sources.
* **Time Delay**: Note that campaign data may take some time to appear in reports (usually within 24 hours).

</details>

<details>

<summary><strong>Clicks recorded but no orders shown</strong></summary>

**Conversion Issues**

* **Order Status Filter**: Ensure you're including completed orders in the report filter.
* **Campaign Duration**: Some campaigns may drive traffic that converts later; extend the date range.
* **User Experience**: Investigate if there are issues with the landing page or checkout process for campaign visitors.
* **Tracking Accuracy**: Verify that the tracking code persists through the entire checkout process.

</details>

<details>

<summary><strong>Discrepancies between marketing report and analytics tools</strong></summary>

**Data Consistency Issues**

* **Attribution Models**: Different systems may use different attribution models (first click, last click).
* **Time Zone Differences**: Ensure date ranges align considering time zone settings.
* **Bot Traffic**: Some analytics tools filter out bot traffic, while OpenCart may count all clicks.
* **Data Sampling**: External tools may use data sampling for large datasets, while OpenCart shows complete data.

</details>

<details>

<summary><strong>Cannot generate or export marketing report</strong></summary>

**Technical Issues**

* **Permission Settings**: Verify your user group has permission to access marketing reports.
* **Browser Compatibility**: Try a different browser for report generation and export.
* **PHP Memory Limit**: Increase PHP memory limit for large marketing datasets.
* **Extension Conflicts**: Disable recently installed extensions to check for conflicts with report functionality.

</details>

> "Marketing is the bridge between your products and your customers' needs. Data from marketing reports isn't just numbers—it's the feedback loop that tells you which bridges are strongest and where to build new ones."
