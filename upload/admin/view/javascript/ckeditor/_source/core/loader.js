/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @fileOverview Defines the {@link CKEDITOR.loader} objects, which is used to
 *		load core scripts and their dependencies from _source.
 */

if ( typeof CKEDITOR == 'undefined' )
	CKEDITOR = {};

if ( !CKEDITOR.loader )
{
	/**
	 * Load core scripts and their dependencies from _source.
	 * @namespace
	 * @example
	 */
	CKEDITOR.loader = (function()
	{
		// Table of script names and their dependencies.
		var scripts =
		{
			'core/_bootstrap'		: [ 'core/config', 'core/ckeditor', 'core/plugins', 'core/scriptloader', 'core/tools', /* The following are entries that we want to force loading at the end to avoid dependence recursion */ 'core/dom/elementpath', 'core/dom/text', 'core/dom/range' ],
			'core/ajax'				: [ 'core/xml' ],
			'core/ckeditor'			: [ 'core/ckeditor_basic', 'core/dom', 'core/dtd', 'core/dom/document', 'core/dom/element', 'core/editor', 'core/event', 'core/htmlparser', 'core/htmlparser/element', 'core/htmlparser/fragment', 'core/htmlparser/filter', 'core/htmlparser/basicwriter', 'core/tools' ],
			'core/ckeditor_base'	: [],
			'core/ckeditor_basic'	: [ 'core/editor_basic', 'core/env', 'core/event' ],
			'core/command'			: [],
			'core/config'			: [ 'core/ckeditor_base' ],
			'core/dom'				: [],
			'core/dom/document'		: [ 'core/dom', 'core/dom/domobject', 'core/dom/window' ],
			'core/dom/documentfragment'	: [ 'core/dom/element' ],
			'core/dom/element'		: [ 'core/dom', 'core/dom/document', 'core/dom/domobject', 'core/dom/node', 'core/dom/nodelist', 'core/tools' ],
			'core/dom/elementpath'	: [ 'core/dom/element' ],
			'core/dom/event'		: [],
			'core/dom/node'			: [ 'core/dom/domobject', 'core/tools' ],
			'core/dom/nodelist'		: [ 'core/dom/node' ],
			'core/dom/domobject'	: [ 'core/dom/event' ],
			'core/dom/range'		: [ 'core/dom/document', 'core/dom/documentfragment', 'core/dom/element', 'core/dom/walker' ],
			'core/dom/text'			: [ 'core/dom/node', 'core/dom/domobject' ],
			'core/dom/walker'		: [ 'core/dom/node' ],
			'core/dom/window'		: [ 'core/dom/domobject' ],
			'core/dtd'				: [ 'core/tools' ],
			'core/editor'			: [ 'core/command', 'core/config', 'core/editor_basic', 'core/focusmanager', 'core/lang', 'core/plugins', 'core/skins', 'core/themes', 'core/tools', 'core/ui' ],
			'core/editor_basic'		: [ 'core/event' ],
			'core/env'				: [],
			'core/event'			: [],
			'core/focusmanager'		: [],
			'core/htmlparser'		: [],
			'core/htmlparser/comment'	: [ 'core/htmlparser' ],
			'core/htmlparser/element'	: [ 'core/htmlparser', 'core/htmlparser/fragment' ],
			'core/htmlparser/fragment'	: [ 'core/htmlparser', 'core/htmlparser/comment', 'core/htmlparser/text', 'core/htmlparser/cdata' ],
			'core/htmlparser/text'		: [ 'core/htmlparser' ],
			'core/htmlparser/cdata'		: [ 'core/htmlparser' ],
			'core/htmlparser/filter'	: [ 'core/htmlparser' ],
			'core/htmlparser/basicwriter': [ 'core/htmlparser' ],
			'core/imagecacher'		: [ 'core/dom/element' ],
			'core/lang'				: [],
			'core/plugins'			: [ 'core/resourcemanager' ],
			'core/resourcemanager'	: [ 'core/scriptloader', 'core/tools' ],
			'core/scriptloader'		: [ 'core/dom/element', 'core/env' ],
			'core/skins'			: [ 'core/imagecacher', 'core/scriptloader' ],
			'core/themes'			: [ 'core/resourcemanager' ],
			'core/tools'			: [ 'core/env' ],
			'core/ui'				: [],
			'core/xml'				: [ 'core/env' ]
		};

		var basePath = (function()
		{
			// This is a copy of CKEDITOR.basePath, but requires the script having
			// "_source/core/loader.js".
			if ( CKEDITOR && CKEDITOR.basePath )
				return CKEDITOR.basePath;

			// Find out the editor directory path, based on its <script> tag.
			var path = '';
			var scripts = document.getElementsByTagName( 'script' );

			for ( var i = 0 ; i < scripts.length ; i++ )
			{
				var match = scripts[i].src.match( /(^|.*[\\\/])core\/loader.js(?:\?.*)?$/i );

				if ( match )
				{
					path = match[1];
					break;
				}
			}

			// In IE (only) the script.src string is the raw valued entered in the
			// HTML. Other browsers return the full resolved URL instead.
			if ( path.indexOf('://') == -1 )
			{
				// Absolute path.
				if ( path.indexOf( '/' ) === 0 )
					path = location.href.match( /^.*?:\/\/[^\/]*/ )[0] + path;
				// Relative path.
				else
					path = location.href.match( /^[^\?]*\// )[0] + path;
			}

			return path;
		})();

		var timestamp = '99GE';

		var getUrl = function( resource )
		{
			if ( CKEDITOR && CKEDITOR.getUrl )
				return CKEDITOR.getUrl( resource );

			return basePath + resource +
				( resource.indexOf( '?' ) >= 0 ? '&' : '?' ) +
				't=' + timestamp;
		};

		var pendingLoad = [];

		/** @lends CKEDITOR.loader */
		return {
			/**
			 * The list of loaded scripts in their loading order.
			 * @type Array
			 * @example
			 * // Alert the loaded script names.
			 * alert( <b>CKEDITOR.loader.loadedScripts</b> );
			 */
			loadedScripts : [],

			loadPending : function()
			{
				var scriptName = pendingLoad.shift();

				if ( !scriptName )
					return;

				var scriptSrc = getUrl( '_source/' + scriptName + '.js' );

				var script = document.createElement( 'script' );
				script.type = 'text/javascript';
				script.src = scriptSrc;

				function onScriptLoaded()
				{
					// Append this script to the list of loaded scripts.
					CKEDITOR.loader.loadedScripts.push( scriptName );

					// Load the next.
					CKEDITOR.loader.loadPending();
				}

				// We must guarantee the execution order of the scripts, so we
				// need to load them one by one. (#4145)
				// The followin if/else block has been taken from the scriptloader core code.
				if ( CKEDITOR.env.ie )
				{
					/** @ignore */
					script.onreadystatechange = function()
					{
						if ( script.readyState == 'loaded' || script.readyState == 'complete' )
						{
							script.onreadystatechange = null;
							onScriptLoaded();
						}
					};
				}
				else
				{
					/** @ignore */
					script.onload = function()
					{
						// Some browsers, such as Safari, may call the onLoad function
						// immediately. Which will break the loading sequence. (#3661)
						setTimeout( function() { onScriptLoaded( scriptName ); }, 0 );
					};
				}

				document.body.appendChild( script );
			},

			/**
			 * Loads a specific script, including its dependencies. This is not a
			 * synchronous loading, which means that the code the be loaded will
			 * not necessarily be available after this call.
			 * @example
			 * CKEDITOR.loader.load( 'core/dom/element' );
			 */
			load : function( scriptName, defer )
			{
				// Check if the script has already been loaded.
				if ( scriptName in this.loadedScripts )
					return;

				// Get the script dependencies list.
				var dependencies = scripts[ scriptName ];
				if ( !dependencies )
					throw 'The script name"' + scriptName + '" is not defined.';

				// Mark the script as loaded, even before really loading it, to
				// avoid cross references recursion.
				this.loadedScripts[ scriptName ] = true;

				// Load all dependencies first.
				for ( var i = 0 ; i < dependencies.length ; i++ )
					this.load( dependencies[ i ], true );

				var scriptSrc = getUrl( '_source/' + scriptName + '.js' );

				// Append the <script> element to the DOM.
				if ( document.body )
				{
					pendingLoad.push( scriptName );

					if ( !defer )
						this.loadPending();
				}
				else
				{
					// Append this script to the list of loaded scripts.
					this.loadedScripts.push( scriptName );

					document.write( '<script src="' + scriptSrc + '" type="text/javascript"><\/script>' );
				}
			}
		};
	})();
}

// Check if any script has been defined for autoload.
if ( CKEDITOR._autoLoad )
{
	CKEDITOR.loader.load( CKEDITOR._autoLoad );
	delete CKEDITOR._autoLoad;
}
