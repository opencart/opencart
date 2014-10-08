# OpenCart 2.0 change log

## v2.0.0.1 (Pending)
#### Bugs fixed
* Changed namespace issues in DB driver files.
* Added missing text_edit string in the settings area.
* Fixed scaling issue for admin progress status bars.
* Fixed image_width and image_height modules #1916
* Sale total dashboard fix (https://github.com/opencart/opencart/commit/6b44f708f8619081e91c3eedf59ad266f279c985)
* modification refresh does not clear files #1919
* Best seller module can now be enabled #1926 (https://github.com/opencart/opencart/commit/7ca7ab4a7bb3b35edbfc40eef12582c9602b1f90)
* Summernote fix for marketing #1935 (https://github.com/opencart/opencart/commit/41ba7adfd1a9572dc0143c3425a1cad1a677650d)
* Pagination closing tag fix #1933 (https://github.com/opencart/opencart/commit/fe4bb21f280087852ad77061d9f5d3b76e6d3bca)
* FTP settings variable correction #1930 (https://github.com/opencart/opencart/commit/17e450971396ecfa2e93eb513b79c7f0fdb6c38b)

#### Changes
* Changed DB connection error response during install when using mysqli to use native message from mysqli
* Removed unused text in stats menu in the admin
* No module validation added (https://github.com/opencart/opencart/commit/d8fb60b2958223a69b042530220962d725cdf6a7)
* Typo fix #1929
* Module name strip_tags #1925
* FTP status check added (https://github.com/opencart/opencart/commit/0b7fcfc7d652dc0e67b3ef3109f1736c05842df1)
* Added check for compression size #1772
* Update moment.js #1896

#### Added
* Check for URL alias #1915 (https://github.com/opencart/opencart/commit/69d6252f4157faf207e3db0504de20b34eab58ef)
