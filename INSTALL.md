# Install

These instructions are for a new OpenCart 4 installation. For an in-place upgrade of an existing store, use [UPGRADE.md](UPGRADE.md) instead.

## Requirements

- PHP 8.1 or newer
- A database and credentials for a supported driver (`mysqli` is the default installation path)
- PHP extensions: `curl`, `gd`, `openssl`, `zip`, `zlib`, and either `mbstring` or `iconv`
- `file_uploads` enabled
- `session.auto_start` disabled
- If `open_basedir` is enabled, it must allow access to the OpenCart directory tree

## What to deploy

Deploy the contents of the `upload/` directory to your web root. In this repository, `upload/` is the application root; the repository root itself is not meant to be served by the web server.

If you are deploying from a Git checkout, keep `upload/system/storage/vendor/` in place or run `composer install` from the repository root before uploading files.

## Manual browser install

1. Copy the contents of `upload/` to your document root.
2. Create the two writable config placeholders:
   - rename `config-dist.php` to `config.php`
   - rename `admin/config-dist.php` to `admin/config.php`
3. In this repository, both `config-dist.php` files are intentionally empty placeholders, so renaming them is enough.
4. Make the following files writable during installation:
   - `config.php`
   - `admin/config.php`
5. Make sure your web server user can write to the runtime directories that OpenCart uses:
   - `system/storage/cache/`
   - `system/storage/download/`
   - `system/storage/logs/`
   - `system/storage/session/`
   - `system/storage/upload/`
   - `image/`
6. Create an empty database and a database user with permissions to that database.
7. Open your store URL in the browser. If `config.php` already exists but is still blank, OpenCart will take you to `/install/`.
8. Follow the on-screen installer steps and provide the database, store, and administrator details.
9. After installation completes, remove or block access to `install/`.
10. Remove write access from `config.php` and `admin/config.php` if your hosting model allows it.

## CLI install

The CLI installer is useful for local and scripted deployments. The source comments document it as currently tested on Linux.

From the repository root:

```bash
php upload/install/cli_install.php install \
  --username admin \
  --password strong-password \
  --email admin@example.com \
  --http_server https://www.example.com/ \
  --language en-gb \
  --db_driver mysqli \
  --db_hostname localhost \
  --db_username opencart \
  --db_password secret \
  --db_database opencart \
  --db_port 3306 \
  --db_prefix oc_
```

Notes:

- If you are already inside the deployed `upload/` directory, use `php install/cli_install.php ...` instead.
- `--http_server` must be a valid `http://` or `https://` URL ending with `/`.
- `config.php` and `admin/config.php` must already exist and be writable before the CLI installer runs.

## Local Docker install

From the repository root:

```bash
docker compose up -d
```

This stack builds the PHP/Apache container from `tools/Dockerfile`, provisions MySQL, and runs the CLI installer automatically on first boot.

Useful defaults:

- Storefront and admin: `http://localhost/`
- Admin username: `admin`
- Admin password: `admin`
- Database name: `opencart`
- MySQL root password: `opencart`
- Adminer: `http://localhost:8080/`

The compose bootstrap creates `upload/install.lock` after a successful automated install.

## Going live

Before serving production traffic:

1. Change any default administrator credentials.
2. Confirm that `install/` is removed or inaccessible.
3. Set `$_['error_display'] = false;` in `upload/system/config/default.php`.
4. Verify mail settings, HTTPS, scheduled tasks, and file permissions.
5. Keep regular backups of the database and `image/` plus any custom storage path.

## Troubleshooting

- If the installer says `config.php` is missing, rename the `config-dist.php` placeholders or create empty `config.php` files manually.
- If the installer fails environment checks, confirm the required PHP version and extensions are loaded in the same PHP runtime that serves the site.
- If `open_basedir` is enabled, it must allow access to the OpenCart install path and any external `DIR_STORAGE` path you use.
- If runtime dependencies are missing, run `composer install` from the repository root and redeploy `upload/system/storage/vendor/`.
