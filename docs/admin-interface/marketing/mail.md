---
description: >-
  Guide to sending mass emails to customers, affiliates, and newsletter
  subscribers in OpenCart 4
---

# Mail

{% hint style="info" %}
**Reach Your Audience** The Mail feature allows you to send mass emails to different customer segments, affiliates, and newsletter subscribers directly from your OpenCart 4 admin panel.
{% endhint %}

## Introduction

The Mail feature in OpenCart 4 enables you to send targeted email campaigns to various segments of your audience. This powerful tool is ideal for marketing campaigns, announcements, promotions, and customer communication. With flexible recipient options and an integrated WYSIWYG editor, you can create professional emails without leaving your admin dashboard.

## Accessing the Mail Interface

To access the Mail feature:

1. Log in to your OpenCart admin panel
2. Navigate to **Marketing → Mail**
3. You'll see the email composition interface with all available options

![Mail Interface](<../../.gitbook/assets/mail-interface (1).png>)

## Sending a Mass Email

{% stepper %}
{% step %}
**Step 1: Configure Sender Settings**

Select the **From** store that will appear as the sender of the email:

* **Default**: Uses your main store configuration
* **Specific Store**: Select from your multi-store setup (if enabled)

This determines which store's email address and name will be used as the sender.
{% endstep %}

{% step %}
**Step 2: Select Recipients**

Choose who will receive your email from the **To** dropdown:

| Recipient Type                 | Description                                      | Use Case                                            |
| ------------------------------ | ------------------------------------------------ | --------------------------------------------------- |
| **All Newsletter Subscribers** | Customers who have subscribed to your newsletter | Newsletter campaigns, general announcements         |
| **All Customers**              | Every customer in your database                  | Store-wide announcements, policy changes            |
| **Customer Group**             | Customers belonging to a specific group          | Targeted promotions (e.g., wholesale customers)     |
| **Customers**                  | Individual customers selected manually           | VIP communications, specific customer follow-ups    |
| **All Affiliates**             | Every affiliate in your system                   | Affiliate program updates, commission announcements |
| **Affiliates**                 | Individual affiliates selected manually          | Specific affiliate communications                   |
| **Products**                   | Customers who have purchased specific products   | Product-specific promotions, update notifications   |

When selecting **Customer Group**, choose the specific group from the dropdown that appears.

When selecting **Customers**, **Affiliates**, or **Products**, use the autocomplete field to search and add specific recipients.
{% endstep %}

{% step %}
**Step 3: Compose Your Email**

Fill in the email composition form:

**Subject** (Required)

* Enter a clear, descriptive subject line
* Keep it concise but informative
* Avoid spam trigger words

**Message** (Required)

* Use the WYSIWYG editor (CKEditor) to format your email
* Supports HTML formatting, images, links, and styling
* Create engaging content with proper formatting
{% endstep %}

{% step %}
**Step 4: Send the Email**

Click the **Send** button to start the mailing process:

* Emails are sent in batches of 10 recipients at a time
* You'll see progress updates as emails are sent
* A success message confirms completion
* If interrupted, you can resume from where it left off
{% endstep %}
{% endstepper %}

## Recipient Options Details

<details>

<summary><strong>All Newsletter Subscribers</strong></summary>

* **Target**: Customers who have opted in to receive newsletters
* **Best for**: Regular newsletter campaigns, general store updates
* **Considerations**: Ensure compliance with anti-spam regulations

</details>

<details>

<summary><strong>All Customers</strong></summary>

* **Target**: Every customer in your database with an email address
* **Best for**: Important store-wide announcements
* **Considerations**: Use sparingly to avoid overwhelming customers

</details>

<details>

<summary><strong>Customer Group</strong></summary>

* **Target**: Customers belonging to a specific customer group
* **Best for**: Targeted promotions (retail vs wholesale, geographic segments)
* **Configuration**: Select the desired group from the dropdown
* **Integration**: Works with your existing customer group structure

</details>

<details>

<summary><strong>Individual Customers</strong></summary>

* **Target**: Specific customers selected via autocomplete
* **Best for**: Personalized communications, VIP treatment
* **Selection**: Type customer name to search and add
* **Management**: Added customers appear in a list that can be edited

</details>

<details>

<summary><strong>All Affiliates</strong></summary>

* **Target**: Every affiliate in your affiliate program
* **Best for**: Affiliate program updates, commission changes
* **Considerations**: Keep affiliates informed about program changes

</details>

<details>

<summary><strong>Individual Affiliates</strong></summary>

* **Target**: Specific affiliates selected via autocomplete
* **Best for**: Direct communication with top performers
* **Selection**: Type affiliate name to search and add

</details>

<details>

<summary><strong>Product-Based Targeting</strong></summary>

* **Target**: Customers who have purchased specific products
* **Best for**: Product updates, accessory promotions, follow-up offers
* **Selection**: Type product name to search and add
* **Logic**: Only customers who have ordered the selected products will receive the email

</details>

## Email Configuration

<details>

<summary><strong>Sender Configuration</strong></summary>

The **From** setting determines which store's identity is used:

* **Email Address**: Taken from the store's configuration (System → Settings → Store tab)
* **Sender Name**: Uses the store name from the selected store
* **Multi-store Support**: Each store can have different sender information

</details>

<details>

<summary><strong>Email Content</strong></summary>

* **WYSIWYG Editor**: Full-featured CKEditor for HTML email creation
* **HTML Support**: Create richly formatted emails with images and links
* **Plain Text Fallback**: System generates plain text version automatically
* **Character Encoding**: UTF-8 support for international characters

</details>

<details>

<summary><strong>Sending Process</strong></summary>

* **Batch Size**: 10 emails per batch to prevent server overload
* **Progress Tracking**: Real-time updates during sending
* **Resume Capability**: Can resume if process is interrupted
* **Error Handling**: Invalid emails are skipped, valid ones continue

</details>

## System Requirements

<details>

<summary><strong>Email System Configuration</strong></summary>

Before using the Mail feature, ensure your email system is properly configured in **System → Settings → Server tab**:

| Setting           | Description                   | Recommended Value                 |
| ----------------- | ----------------------------- | --------------------------------- |
| **Mail Engine**   | Method for sending emails     | `mail` (PHP mail()) or `smtp`     |
| **SMTP Hostname** | SMTP server address           | Your email provider's SMTP server |
| **SMTP Username** | SMTP authentication username  | Your email address                |
| **SMTP Password** | SMTP authentication password  | Your email password               |
| **SMTP Port**     | SMTP server port              | 587 (TLS) or 465 (SSL)            |
| **SMTP Timeout**  | Connection timeout in seconds | 30                                |

**Note**: For reliable mass email delivery, consider using SMTP with a professional email service.

</details>

<details>

<summary><strong>Server Requirements</strong></summary>

* **PHP mail() Function**: Must be enabled and configured on your server
* **SMTP Support**: Required if using SMTP mail engine
* **Execution Time**: Sufficient PHP execution time for large batches
* **Memory Limit**: Adequate PHP memory for processing emails

</details>

## Use Cases for Mass Emails

<details>

<summary><strong>1. Newsletter Campaigns 📰</strong></summary>

Send regular newsletters to subscribers:

* Monthly product updates
* Seasonal promotions
* Company news and announcements
* Educational content related to your products

</details>

<details>

<summary><strong>2. Product Promotions 🛍️</strong></summary>

Target customers based on purchase history:

* Cross-sell accessories for purchased products
* Notify about product restocks
* Announce new versions or updates
* Special offers on related products

</details>

<details>

<summary><strong>3. Customer Segmentation 🎯</strong></summary>

Send different messages to different customer groups:

* VIP discounts for loyal customers
* Wholesale pricing announcements for business customers
* Geographic-specific promotions
* New customer welcome series

</details>

<details>

<summary><strong>4. Affiliate Communication 🤝</strong></summary>

Keep your affiliate network informed:

* New affiliate program features
* Commission structure updates
* Marketing material announcements
* Performance reports and tips

</details>

<details>

<summary><strong>5. System Notifications 🔔</strong></summary>

Important store announcements:

* Policy changes (shipping, returns, privacy)
* Holiday schedules
* System maintenance notices
* Security updates

</details>

## Best Practices

{% hint style="success" %}
**Email Strategy** 📧

1. **Segment Your Audience**: Use customer groups and purchase history for targeted messaging
2. **Clear Subject Lines**: Be descriptive but concise to improve open rates
3. **Mobile-Friendly Design**: Ensure emails look good on mobile devices
4. **Call to Action**: Include clear next steps for recipients
5. **Test Before Sending**: Send test emails to yourself first
{% endhint %}

{% hint style="warning" %}
**Compliance & Delivery** ⚠️

1. **Permission-Based**: Only email customers who have opted in (especially for newsletters)
2. **Unsubscribe Option**: Include unsubscribe instructions in every email
3. **Anti-Spam Laws**: Comply with regulations like CAN-SPAM, GDPR, CASL
4. **Sender Reputation**: Maintain good email practices to avoid being marked as spam
5. **List Hygiene**: Regularly clean inactive or bouncing email addresses
{% endhint %}

{% hint style="info" %}
**Technical Considerations** ⚡

1. **Batch Size**: The system sends 10 emails at a time - be patient with large lists
2. **SMTP Recommended**: For reliable delivery, use SMTP instead of PHP mail()
3. **Email Templates**: Consider creating reusable templates for common communications
4. **Testing**: Always test with a small group before sending to everyone
5. **Timing**: Schedule sends for optimal open times (varies by audience)
{% endhint %}

## Troubleshooting

### Common Issues

<details>

<summary><strong>Emails not sending 🚫</strong></summary>

**Solution:** Check your email configuration in System → Settings → Server tab:

1. Verify Mail Engine is set correctly
2. Check SMTP credentials if using SMTP
3. Test with a single recipient first
4. Check server error logs for mail function errors

</details>

<details>

<summary><strong>Emails going to spam 📭</strong></summary>

**Solution:** Improve email deliverability:

1. Use a recognizable "From" name and address
2. Avoid spam trigger words in subject and content
3. Include unsubscribe instructions
4. Ensure your server's IP isn't on blacklists
5. Consider using a dedicated email service

</details>

<details>

<summary><strong>Slow sending process 🐢</strong></summary>

**Solution:** The system sends 10 emails per batch for server stability:

1. Be patient with large recipient lists
2. Check server performance and resources
3. Consider sending during off-peak hours
4. Break large campaigns into smaller segments

</details>

<details>

<summary><strong>Missing recipient options 🔍</strong></summary>

**Solution:** Some options require data to be available:

1. **Customer Groups**: Ensure you have created customer groups first
2. **Products**: Products must have been purchased by customers
3. **Newsletter Subscribers**: Customers must have opted in to newsletters
4. **Affiliates**: Affiliate program must be enabled and have affiliates

</details>

<details>

<summary><strong>Formatting issues in emails 🎨</strong></summary>

**Solution:** Check your HTML email formatting:

1. Use the WYSIWYG editor's formatting tools
2. Test in multiple email clients
3. Keep designs simple and responsive
4. Avoid complex CSS that may not render consistently

</details>

{% hint style="info" %}
**Performance Considerations** ⚡

* Large recipient lists will take time to process (10 emails per batch)
* Server resources affect sending speed
* Consider using cron jobs for very large campaigns (requires customization)
* Monitor server load during mass email sends
{% endhint %}

{% hint style="success" %}
**Documentation Summary** 📋

You've now learned how to:

* Access and use the Mail feature in OpenCart 4
* Select different recipient types for targeted campaigns
* Compose professional emails using the WYSIWYG editor
* Configure email settings for optimal delivery
* Apply best practices for effective email marketing
* Troubleshoot common email sending issues

**Next Steps:**

* [Marketing Overview](/broken/pages/91vrPdJtZiyPXdqSPLJC) - Explore other marketing features
* [Affiliate Management](/broken/pages/Te57sd1Oj7BDxJFbBVdo) - Set up and manage your affiliate program
* [Coupon Management](/broken/pages/xbRg0v2R988utvAoov0A) - Create and distribute discount coupons
* [Customer Groups](/broken/pages/LAO0SyfaDGHgMwDovS2i) - Organize customers for better targeting
* [System Settings](/broken/pages/xCvZYwheznxxvkDkGycZ) - Configure email and other system settings
{% endhint %}
