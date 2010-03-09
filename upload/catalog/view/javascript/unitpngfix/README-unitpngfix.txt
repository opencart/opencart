Unit PNG Fix Readme
07/21/09

Some quick notes to properly use the Unit PNG Fix:

- unitpngfix.js is written for IE6 only. You should use the conditional statement in your html:

	<!--[if lt IE 7]>
        		<script type="text/javascript" src="unitpngfix.js"></script>
	<![endif]--> 

to make sure that it only loads in IE6. Unit PNG Fix will not work in Firefox, Opera, Safari, IE7, etc.

- unitpngfix.js adds the proprietary Microsoft filter attribute to all elements using pngs in order to correctly display alpha transparency, and the filter property has some drawbacks. There is no way to use background-position with the filter property (well, there is a workaround, but it will hack your markup, so we won’t be doing that). Also, the filter property will cause the children of an element to become unclickable/unselectable...

- unitpngfix.js will change the CSS position attribute of some elements in order to allow them to be clickable/selectable. It will change elements with a background png to position:static (default) and all child nodes within that first element, that do not have a position specified, will get position:relative. It should not affect any absolutely positioned elements anymore.

- unitpngfix.js is written to work with xhtml/css layouts that are written properly. It may not work play with layouts that are not properly built. 

- By default, unitpngfix.js will apply to any pngs on the page. If you wish, you can opt in to only those elements you want fixed by adding the class “unitPng” to those elements. This will force unitpngfix.js to only apply the filter attribute to those elements you have designated with the “unitPng” class.