jsTree 0.9.9
http://jstree.com/

Copyright (c) 2009 Ivan Bozhanov (vakata.com)

Dual licensed under the MIT and GPL licenses:
  http://www.opensource.org/licenses/mit-license.php
  http://www.gnu.org/licenses/gpl.html

Date: 2009-09-24



In the root directory you will find jquery.tree.js and jquery.tree.min.js (minified version of jquery.tree.js). 
To be able to use the jsTree jquery tree plugin all you need to do is include jquery (http://jquery.com) and one of the above files in your page.
For more - check the documentation.html and examples.html files.

In the plugins/ dir you will find all of the official plugins, along with a documentation.html file - it contains the docs for all the plugins.

In the lib/ dir you will find a copy of jquery.js, along with all other dependencies of the plugins. 
If you use a plugin - it will alert you about a missing dependency.

All themes are located in the themes/ dir. Make sure to keep it relative to the jquery.tree.js file, so that your chosen theme will be included.
If that is not an option for you - search the documentation.html file for "theme_path".

For users upgrading from a previous version to 0.9.9:
1) jQuery.tree_reference became jQuery.tree.reference (the function is also upgraded)
2) jQuery.tree_focused became jQuery.tree.focused
3) jQuery.tree_create became jQuery.tree.create
4) The data config section is changed (all the options for the datastore are in the "data.opts" object) - check the docs for more.
5) If you use XML - you will have to include the appropriate plugin and Sarissa - again - check the options - they are a bit changed.
6) getXML and getJSON no longer exist - use the "get" function - check the documentation on how to use it.
7) If you used the checkbox callback - switch to the checkbox plugin
8) If you used the cookie option - switch to the cookie plugin - check the plugin docs for info.
9) onJSONdata became ondata callback - you get what the server returned before jsTree displays it
10) async_data becomes beforedata callback
11) Drag, clickable, deletable rules are out of the picture. Enter type definitions - all types inherit from the "default" type. 
You can now set valid children, max_depth, max_children, clickable, deletable, etc - any of those can also be functions, so you can dinamically check.
It is all in the docs - check the "types" section. For global tree rules - check the "rules" config section.

