---
description: Guide to upgrade from OpenCart 3.x to 4.x
---

# Upgrade from 3.x to 4.x

## Important Notes

Before starting the upgrade process, please note:

* **Backup everything**: Database and files
* **Test in staging environment** first
* **Check extension compatibility** with OpenCart 4
* **Review theme compatibility**

## Pre-Upgrade Checklist

1. **Backup your store**:
   * Database backup
   * File system backup
   * Extension configurations
2. **Check compatibility**:
   * Verify PHP version compatibility (PHP 8.0+ required)
   * Check if extensions support OpenCart 4
   * Review theme compatibility
3. **Prepare for downtime**:
   * Schedule upgrade during low-traffic periods
   * Inform customers about maintenance

## Upgrade Methods

### Method 1: Manual Upgrade

1. **Download OpenCart 4**
   * Get the latest version from [OpenCart website](https://www.opencart.com)
2. **Upload new files**
   * Upload all files except:
     * `/config.php`
     * `/admin/config.php`
     * `/images/` directory
     * `/system/storage/` directory
3. **Run upgrade script**
   * Navigate to `yourdomain.com/install`
   * Follow the upgrade wizard
4. **Update database**
   * The upgrade script will automatically update your database schema

### Method 2: Extension-Based Upgrade

Some hosting providers offer extension-based upgrades through their control panels.

## Post-Upgrade Tasks

1. **Clear cache**:
   * Delete `/system/storage/cache/` contents
   * Clear browser cache
2. **Test functionality**:
   * Test frontend and backend
   * Verify all extensions work
   * Check payment and shipping methods
3. **Update extensions**:
   * Install OpenCart 4 compatible versions
   * Configure settings as needed

## Common Issues

* **Extension compatibility**: Some extensions may not work with OpenCart 4
* **Theme issues**: Custom themes may need updates
* **Database errors**: Check error logs for any database-related issues

## Support

If you encounter issues during upgrade, visit the [OpenCart forums](https://forum.opencart.com/) for community support.
