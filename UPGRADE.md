# Upgrade

These instructions are for upgrading an existing store in place to the OpenCart version contained in this repository. For a brand-new installation, use [INSTALL.md](INSTALL.md) instead.

## Requirements

- PHP 8.1 or newer
- A backup of the database and the complete store filesystem before you start
- A staging copy of the live site to test against

## Before you start

1. Back up the full database and the complete store filesystem.
2. Test the upgrade on a staging copy before touching production.
3. Check every custom theme, extension, OCMOD/VQMod change, and integration for compatibility with the target version.
4. Record your admin directory name if you renamed it from the default `admin`.
5. Keep your current `config.php`, admin config file, `image/`, and any external `DIR_STORAGE` location safe.

## Important upgrade behaviour

- The OpenCart upgrade flow runs from `your-store/install/` only after a valid `config.php` already exists.
- The upgrade UI asks for the admin directory name so renamed admin folders can be preserved.
- Existing `DIR_STORAGE` values are preserved. If your storage directory lives outside the web root, keep that path writable and do not overwrite it.
- The upgrade patches normalize config files, create missing storage folders, migrate legacy filesystem layout, and apply database changes.
- The upgrade UI does not download a release for you. Upload the target version's `upload/` files first, then run `/install/`.

## Recommended upgrade process

1. Put the store into maintenance mode if possible and make sure no background jobs are writing to the database during the upgrade.
2. Upload the new version's `upload/` contents over the existing store files.
3. Do not overwrite your environment-specific configuration:
   - preserve `config.php`
   - preserve the admin config file for your actual admin directory
   - preserve `image/`
   - preserve any custom `DIR_STORAGE` path and its contents
4. If your admin directory has a custom name, keep that folder name exactly as it is.
5. Browse to `https://example.com/install/` on the upgraded fileset.
6. When prompted, enter the admin directory name without a leading or trailing slash.
7. Start the upgrade and let all patch steps complete.
8. If any step reports an error, stop there, fix the issue, and re-run the upgrade from the same codebase rather than continuing blindly.

## What the upgrade patches handle

The current upgrade flow includes filesystem and schema migrations such as:

- rewriting old-style config files into the current OpenCart 4 format
- preserving a custom `DIR_STORAGE` value if one is already configured
- creating missing `storage` subdirectories
- merging legacy `system/upload/` and `system/download/` data into `system/storage/`
- moving legacy `image/data/` content into `image/catalog/`
- applying database schema, settings, event, and data migrations

Even with these patches, file upload still needs to be done by you before running the web upgrader.

## After the upgrade

1. Clear browser cookies if you see token or session issues.
2. Hard-refresh the admin area and storefront with `Ctrl+F5` or `Ctrl+Shift+R`.
3. Log in as the main administrator.
4. Open `Admin -> Users -> User Groups`, edit the top administrator group, and enable all permissions.
5. Open the main store settings and click save even if nothing looks changed.
6. Re-save the active theme and review extension settings.
7. Test the storefront, admin login, checkout, cron jobs, mail delivery, and any payment or shipping integrations.
8. Remove or block access to `install/` again.

## Troubleshooting

- If `/install/` shows the fresh installer instead of the upgrade screen, your live `config.php` was overwritten or emptied. Restore it from backup first.
- If the upgrader cannot find the admin config file, re-run it with the correct custom admin directory name.
- If third-party modules fail after the upgrade, treat those as compatibility issues until proven otherwise and update them separately.
- If you use a custom `DIR_STORAGE` path, confirm that the web server can still read and write it after the file upload.
