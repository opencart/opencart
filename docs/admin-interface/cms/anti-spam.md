---
description: >-
  Protecting your blog from spam comments by maintaining a list of banned
  keywords and phrases
---

# Anti-Spam

## Introduction

The **Anti‑Spam** system protects your blog from unwanted comment spam by maintaining a list of banned keywords and phrases. When a comment contains any of these keywords, it can be automatically flagged, held for moderation, or rejected. This simple yet effective filtering reduces moderation workload and keeps your comment sections clean and relevant.

## Accessing Anti-Spam Management

{% stepper %}
{% step %}
#### Navigate to Anti-Spam

Log in to your admin dashboard and go to **CMS → Anti‑Spam**.
{% endstep %}

{% step %}
#### Anti-Spam List

You will see a list of all banned keywords with options to edit or delete them.
{% endstep %}

{% step %}
#### Manage Keywords

Use the **Add New** button to create a new banned keyword or click **Edit** on any existing keyword to modify it.
{% endstep %}
{% endstepper %}

## Anti-Spam Interface Overview

### Anti-Spam Configuration

<details>

<summary><strong>Keyword Management</strong></summary>

**Banned Keyword Entry**

* **Keyword**: **(Required)** The word or phrase to block (1‑64 characters, unique)
* **Validation Rules**: Keywords can contain letters, numbers, hyphens, and underscores
* **Case Sensitivity**: Matching is typically case‑insensitive (e.g., "SPAM" matches "spam")
* **Partial Matching**: Keywords match anywhere in comment text (e.g., "casino" matches "onlinecasino")

</details>

## Common Tasks

### Adding New Spam Keywords

To block new spam patterns:

1. Navigate to **CMS → Anti‑Spam** and click **Add New**.
2. Enter the keyword or phrase you want to block (e.g., "viagra", "online casino", "make money fast").
3. Click **Save**. The keyword is added to the banned list.
4. New comments containing this keyword will be flagged based on your spam handling settings.

### Building an Effective Spam Filter List

To create comprehensive spam protection:

1. **Start Common**: Add obviously spammy keywords like "viagra", "casino", "loan", "mortgage".
2. **Monitor Spam Comments**: When you mark a comment as spam, check for unique keywords to add.
3. **Use Variations**: Include common misspellings and character substitutions (e.g., "v1agra", "c@sin0").
4. **Avoid Overblocking**: Be careful not to block legitimate words that might appear in genuine comments.
5. **Regular Updates**: Review and update your list monthly as spam tactics evolve.

### Integrating with Comment Moderation

To connect anti‑spam with comment management:

1. When marking a comment as spam in **CMS → Comments**, note any unique keywords.
2. Add those keywords to the anti‑spam list to prevent similar future comments.
3. Consider setting up automatic spam handling (if supported by extensions or custom code).
4. Use the anti‑spam list as a first line of defense, with manual moderation as backup.

## Best Practices

<details>

<summary><strong>Effective Spam Filtering Strategy</strong></summary>

**Balancing Protection & Accessibility**

* **Layered Defense**: Use anti‑spam keywords **plus** CAPTCHA, comment approval, and other spam prevention methods.
* **Regular Maintenance**: Review your keyword list quarterly to remove obsolete entries and add new threats.
* **Test Legitimate Content**: Before adding a keyword, test if it might appear in legitimate comments (e.g., "credit" might appear in genuine discussions about credit cards).
* **Monitor False Positives**: Check your moderation queue for legitimate comments caught by over‑broad keywords.
* **Industry‑Specific Spam**: Tailor your list to spam common in your industry (e.g., fashion blogs get different spam than tech blogs).

</details>

<details>

<summary><strong>Keyword Selection Techniques</strong></summary>

**Smart Keyword Choices**

* **Specific Over General**: "buy viagra online" is better than "buy" which could block legitimate comments.
* **Include Variations**: Spammers use substitutions (@ for a, 1 for i, etc.)—include common variants.
* **Phrase Matching**: Multi‑word phrases reduce false positives (e.g., "make money fast" vs. "money").
* **Regular Expressions**: If supported by extensions, use regex patterns for more powerful matching (e.g., `.*casino.*`).
* **International Spam**: Consider spam keywords in other languages if you have international visitors.

</details>

<details>

<summary><strong>Integration with Other Systems</strong></summary>

**Comprehensive Spam Defense**

* **Comment Approval Settings**: Combine with **System → Settings → Options** comment approval requirements.
* **CAPTCHA Extensions**: Install CAPTCHA or reCAPTCHA extensions for additional protection.
* **Third‑Party Services**: Consider external spam filtering services (Akismet, etc.) via extensions.
* **Log Analysis**: Regularly review spam attempts to identify new patterns and keywords.
* **User Registration**: Require registration for commenting to reduce anonymous spam.

</details>

{% hint style="warning" %}
**Keyword Uniqueness Requirement** ⚠️ Each anti‑spam keyword must be **globally unique**. Duplicate keywords will be rejected. Check existing keywords before adding new ones to avoid conflicts.
{% endhint %}

## Troubleshooting

<details>

<summary><strong>Spam comments still appearing despite keywords</strong></summary>

**Filter Effectiveness Issues**

* **Keyword Match Type**: Anti‑spam uses **partial matching**—ensure your keywords appear within spam comments.
* **Case Sensitivity**: Matching is case‑insensitive, but check for unusual character substitutions.
* **New Patterns**: Spammers constantly evolve—add new keywords based on recent spam.
* **System Integration**: Verify anti‑spam is integrated with comment processing (should be automatic).
* **Cache**: Clear OpenCart cache to ensure new keywords take effect immediately.

</details>

<details>

<summary><strong>Legitimate comments being blocked as spam</strong></summary>

**False Positive Issues**

* **Over‑Broad Keywords**: Review your keyword list for words that appear in legitimate comments (e.g., "free", "win", "best").
* **Partial Match Problems**: "ass" might block "passenger" or "class"—use whole words or phrases.
* **Customer Notification**: Consider notifying customers why their comment was blocked (if possible).
* **Moderation Queue**: Ensure blocked comments go to moderation rather than being silently discarded.
* **Keyword Adjustment**: Modify or remove keywords causing false positives.

</details>

<details>

<summary><strong>Cannot add a keyword (validation error)</strong></summary>

**Input Validation Issues**

* **Length Limits**: Keywords must be 1‑64 characters.
* **Character Restrictions**: Only letters (a‑z), numbers (0‑9), hyphens (-), and underscores (\_) allowed.
* **Uniqueness**: Keyword already exists in the list (check for duplicates, including case variations).
* **Special Characters**: Remove spaces, punctuation, or special characters (except hyphen and underscore).
* **Database Constraints**: Very rare—check error logs for specific database errors.

</details>

<details>

<summary><strong>Anti-spam list not filtering new comments</strong></summary>

**System Integration Issues**

* **Comment Processing**: Ensure comments are processed through the anti‑spam system (default in OpenCart).
* **Extension Conflicts**: Other extensions may override or disable the built‑in anti‑spam filtering.
* **Version Compatibility**: Verify anti‑spam features work in your OpenCart version.
* **Testing**: Test by adding a common word like "testspam" and posting a comment containing it.
* **Logs**: Check system logs for errors in spam filtering processing.

</details>

> "Spam filters are the silent guardians of your comment sections—they work in the background, learning from each attack, adapting to new threats, and preserving the signal amidst the noise. A well‑maintained filter protects not just your blog, but your community's experience."
