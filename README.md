# OpenCart

## Overview

OpenCart is a free open source ecommerce platform for online merchants. OpenCart provides a professional and reliable foundation from which to build a successful online store.

## Reporting a bug and security issues

Read the instructions below before you create a bug report.

 1. Search the [OpenCart forum](http://forum.opencart.com/viewforum.php?f=191), ask the community if they have seen the bug or know how to fix it.
 2. Check all open and closed issues on the [GitHub bug tracker](https://github.com/opencart/opencart/issues).
 3. If your bug is related to the OpenCart core code then please create a bug report on GitHub.
 4. READ the [changelog for the master branch](https://github.com/opencart/opencart/blob/master/changelog.md)
 5. Use [Google](http://www.google.com) to search for your issue.
 6. Make sure that your bug/issue is not related to your hosting environment.
 7. We now offer limited free support through our [support site](http://support.opencart.com/)

If you are not sure about your issue, it is always best to ask the community on our [bug forum thread](http://forum.opencart.com/viewforum.php?f=191)

**Important!**
- If your bug report is not related to the core code (such as a 3rd party module or your server configuration) then the issue will be closed without a reason. You must contact the extension developer, use the forum or find a commercial partner to resolve a 3rd party code issue.
- If you would like to report a security bug please do one of the following; PM an OpenCart moderator (Daniel or James) on the forum, send an email to the support desk at &nbsp;&nbsp;&nbsp;![Support Email image](http://img4me.com/ZHj.png "Type our email into your mail client")or use the contact form on the [OpenCart website](https://www.opencart.com/index.php?route=support/contact). 
- Please do not report concept/ideas/unproven security flaws - all security reports are taken seriously but you must include the EXACT details steps to reproduce it otherwise it is a huge drain on resources. Please DO NOT post security flaws in a public location like forums, twitter, Reddit
- If you have found a bug / security issue it is very important to check if it is present in the latest version of OpenCart before reporting it, please check commit the history of the file(s) where the bug is to see if it is resolved.

## Making a suggestion

Please do not create a bug report if you think something needs improving / adding (such as features or change to code standards etc).

We welcome public suggestions on our [User Voice site](http://opencart.uservoice.com).

## How to contribute

Fork the repository, edit and [submit a pull request](https://github.com/opencart/opencart/wiki/Creating-a-pull-request).

Please be very clear on your commit messages and pull request, empty pull request messages may be rejected without reason.

Your code standards should match the [OpenCart coding standards](https://github.com/opencart/opencart/wiki/Coding-standards). We use an automated code scanner to check for most basic mistakes - if the test fails your pull request will be rejected.

## Versioning

The version is broken down into 4 points e.g 1.2.3.4 We use MAJOR.MINOR.FEATURE.PATCH to describe the version numbers.

A MAJOR is very rare, it would only be considered if the source was effectively re-written or a clean break was desired for other reasons. This increment would likely break most 3rd party modules.

A MINOR is when there are significant changes that affect core structures. This increment would likely break some 3rd party modules.

A FEATURE version is when new extensions or features are added (such as a payment gateway, shipping module etc). Updating a feature version is at a low risk of breaking 3rd party modules.

A PATCH version is when a fix is added, it should be considered safe to update patch versions e.g 1.2.3.4 to 1.2.3.5

## Releases

OpenCart will announce to developers 1 week prior to public release of FEATURE versions, this is to allow for testing of their own modules for compatibility. For bigger releases (ones that contain many core changes, features and fixes) an extended period will be considered following an announced release candidate (RC). Patch versions (which are considered safe to update with) may have a significantly reduced developer release period.

The master branch will always contain an "_rc" postfix of the next intended version. The next "_rc" version may change at any time.

Developer release source code will not change once tagged.

If a bug is found in an announced developer release that is significant (such as a major feature is broken) then the release will be pulled. A patch version will be issued to replace it, depending on the severity of the patch an extended testing period may be announced. If the developer release version was never made public then the preceding patch version tag will be removed.

To receive developer notifications about release information, sign up to the newsletter on the [OpenCart website](http://www.opencart.com) - located in the footer. Then choose the developer news option.

## How to install

Please read the installation instructions included in the repository or download file.

## License

[GNU General Public License version 3 (GPLv3)](https://github.com/opencart/opencart/blob/master/license.txt)

## Links

- [OpenCart homepage](http://www.opencart.com/)
- [OpenCart forums](http://forum.opencart.com/)
- [OpenCart blog](http://www.opencart.com/index.php?route=feature/blog)
- [How to documents](http://docs.opencart.com/)
- [Newsletter](http://newsletter.opencart.com/h/r/B660EBBE4980C85C)
- [User Voice suggestions](http://opencart.uservoice.com)
