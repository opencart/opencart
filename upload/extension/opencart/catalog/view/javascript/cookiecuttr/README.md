CookieCuttr
===========

A tailorable jQuery plugin to deal with the EU Cookie Law.

**We're not accepting any more pull requests, someone else is working on a new version at the moment, when its ready, we'll link it here.**

Dependencies
------------

* [jQuery](https://github.com/jquery/jquery)
* [jQuery.cookie](https://github.com/carhartl/jquery-cookie)

Useage
------

Add `jquery.cookiecuttr.js`, after jQuery and jQuery.cookie, and `cookiecuttr.css` to the head of your HTML document. Then call CookieCuttr on document ready.

```javascript
$(document).ready(function () {
  $.cookieCuttr();
});
```

For any JavaScript you want to disable, you need to wrap the following if statement around it.

```javascript
if (jQuery.cookie('cc_cookie_accept') == "cc_cookie_accept") {
  // insert the code you do not want to run UNTIL cookies are accepted here
}
```

Example with Google Analytics
-----------------------------

```javascript
if (jQuery.cookie('cc_cookie_accept') == "cc_cookie_accept") {
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-XXXXXXXX-X']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document. getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
}
```

Alternatively, you can do the following until cookies are declined.

```javascript
if (jQuery.cookie('cc_cookie_decline') == "cc_cookie_decline") {
  // do nothing
} else {
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-XXXXXXXX-X']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document. getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
}
```

You can also do the following.

```javascript
if( $.cookieAccepted() ) {
  // insert the code you do not want to run UNTIL cookies are accepted here
}
```

Read More About EU Cookie Law
-----------------------------

* [Is the EU's cookie law confusing you too?](http://www.123-reg.co.uk/blog/security-issues/is-the-eus-cookie-law-confusing-you-too/)
* [New EU cookie law (e-Privacy Directive)](http://www.ico.gov.uk/for_organisations/privacy_and_electronic_communications/the_guide/cookies.aspx)
* [Cookies – Doing nothing isn't the right answer](http://chriswharton.me/2012/05/cookies-doing-nothing-isnt-the-right-answer/)

Using WordPress?
----------------

A Wordpress version is available [here](http://cookiecuttr.com/wordpress-plugin/).

Options
-------

For options, see the [WIKI](/weare2ndfloor/cookieCuttr/wiki/Options).

Contributing to CookieCuttr
---------------------------

* Check out the latest master to make sure the feature hasn't been implemented or the bug hasn't been fixed yet
* Check out the issue tracker to make sure someone already hasn't requested it and/or contributed it
* Fork the project
* Start a feature/bugfix branch
* Commit and push until you are happy with your contribution
* Initiate a [pull request](https://help.github.com/articles/using-pull-requests)

Credits
-------

* [weare2ndfloor](https://github.com/weare2ndfloor) for the plugin
* [unsymbol](https://github.com/unsymbol) for organising the implementation txt into a readme markdown file

Copyright
---------

Copyright (c) 2012 Chris Wharton. See LICENSE.txt for further details.
