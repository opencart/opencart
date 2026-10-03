# Upgrading OpenCart

## ⚠️ Upgrading from OpenCart 3.x to 4.x is NOT supported

**Do not attempt to upgrade directly from OpenCart 3.x to 4.x.**

OpenCart 4.x is a major rewrite with fundamental breaking changes:

- All PHP code is now **namespaced** — 3.x extensions and themes will not load
- The **extension directory structure** changed entirely (3.x OCMODs are incompatible)
- The **database schema** has significant structural differences
- There is **no migration path** for 3.x extensions, themes, or modifications

**If you are coming from OpenCart 3.x, you must do a fresh installation of OpenCart 4.x** and manually migrate your data (products, customers, orders) using database tools or a third-party migration service.

See the [OpenCart documentation](https://docs.opencart.com/) for guidance on fresh installations.

---

# Upgrading from versions 4.1.0.0 - 4.1.0.3

* For existing installs only
* Don't use with previous versions

## For previous versions

If you have an older version and want to upgrade __to 4.1.0.4__, You may try to [contact our dedicated support](https://dedicated.opencart.com/)


## Step by step

1. BACKUP YOUR EXISTING STORE FILES AND DATABASE!!
    * Backup your __database__ via your store
    `Admin -> System -> tools -> Backup`
    * Backup your __files__ using FTP file copy or use cPanel filemanager to create a zip of all the existing opencart files and folders

2. Download the __latest version 4.1.0.x__ of OpenCart and __upload all__ new files on top of your current install __except__ your `config.php` and `admin/config.php`.
    * Before an upgrade to the latest version, you should double check the existing extensions (in your store) are compatible with latest version. Please contact the extension developers if in doubt before you upgrade.

3. Browse to `https://<yourstore.com>/install` Replacing `<yourstore.com>` with your actual site (and subdirectory if applicable).

4. You should see the OpenCart Upgrade script.
    * If you see the OpenCart Install page, then that means you overwrote your `config.php` files. Restore them from your backup first. Then try again.

5. Click "Upgrade". After a few seconds you should see the upgrade success page.
    * If you see any errors, report them immediately in the forum before continuing.

6. Clear any cookies in your browser

7. Goto the admin side of your store and press `Ctrl+F5` or `Ctrl+Shift+R` for several times to refresh your browser cache. That will prevent oddly shifted elements due to stylesheet changes. Login to your admin as the main administrator.

8. Goto `Admin -> Users -> User Groups` and Edit the Top Administrator group. Check All boxes.
    * This will ensure you have permissions for all the new files

9. Goto `Admin -> Extensions -> Extensions -> Theme` enabled and save the default theme again.

10. Goto `Admin -> System Settings`
    * Update any blank fields and click save.
    * Even if you do not see any new fields, click save anyway to update the database with any new field names.

11. Other Adjustments that may need to be made, depending on which version you are upgrading from and to. These are broken down by which version they were added in. So if you are not currently at that version, you may need to make changes

12. Load the store front and again press CTRL+F5 3x times to refresh your browser cache. That will prevent oddly shifted elements due to stylesheet. (If you skip step 9, you will get the error message.)

## Troubleshooting:

1. If you have any upgrade script errors, post them in the forum
2. If you have 3rd party addon errors, contact the mod author for an update.
3. If you find bugs, check the "official" bug thread for this version of Opencart


Many bugs may have already been reported and fixes will be offered in the first post of this thread.
You should always visit this thread immediately after a fresh upgrade to see if there are any immediate bug fixes
If nobody has reported your bug, then please report it.

See also our online documentation at https://docs.opencart.com/
