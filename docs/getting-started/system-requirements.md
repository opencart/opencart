# System Requirements

OpenCart has certain technical requirements for the software to operate properly. The software must be uploaded to a web server, which will make the store publicly available on the web. If you do not already have a domain or web hosting account, those can easily be purchased for an affordable price at various places online.

When selecting a hosting service, an Apache server is recommended. You will also need a database server that supports MySQLi, PDO, or PostgreSQL. (MySQLi is recommended if possible.) Finally, you will need to have the following PHP libraries installed in your PHP configuration:

* PHP 8.0 or later
* Curl
* GD Library
* Iconv
* Mbstring
* OpenSSL Encrypt
* ZipArchive
* Zlib

Additionally, you will want to turn on the following PHP settings:

* file\_uploads
* magic\_quotes\_gpc
* register\_globals
* session\_auto\_start

During the OpenCart installation process it will check to make sure you have those libraries and settings enabled. Typically they are already enabled by default with most hosting providers. However, if you receive an error warning during installation that one of them is not active, you should contact your hosting provider and ask them to add it to your PHP configuration.
