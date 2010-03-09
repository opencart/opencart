/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @fileOverview Defines the {@link CKEDITOR.scriptLoader} object, used to load scripts
 *		asynchronously.
 */

/**
 * Load scripts asynchronously.
 * @namespace
 * @example
 */
CKEDITOR.scriptLoader = (function()
{
	var uniqueScripts = {};
	var waitingList = {};

	return /** @lends CKEDITOR.scriptLoader */ {
		/**
		 * Loads one or more external script checking if not already loaded
		 * previously by this function.
		 * @param {String|Array} scriptUrl One or more URLs pointing to the
		 *		scripts to be loaded.
		 * @param {Function} [callback] A function to be called when the script
		 *		is loaded and executed. If a string is passed to "scriptUrl", a
		 *		boolean parameter is passed to the callback, indicating the
		 *		success of the load. If an array is passed instead, two array
		 *		parameters are passed to the callback; the first contains the
		 *		URLs that have been properly loaded, and the second the failed
		 *		ones.
		 * @param {Object} [scope] The scope ("this" reference) to be used for
		 *		the callback call. Default to {@link CKEDITOR}.
		 * @param {Boolean} [noCheck] Indicates that the script must be loaded
		 *		anyway, not checking if it has already loaded.
		 * @example
		 * CKEDITOR.scriptLoader.load( '/myscript.js' );
		 * @example
		 * CKEDITOR.scriptLoader.load( '/myscript.js', function( success )
		 *     {
		 *         // Alerts "true" if the script has been properly loaded.
		 *         // HTTP error 404 should return "false".
		 *         alert( success );
		 *     });
		 * @example
		 * CKEDITOR.scriptLoader.load( [ '/myscript1.js', '/myscript2.js' ], function( completed, failed )
		 *     {
		 *         alert( 'Number of scripts loaded: ' + completed.length );
		 *         alert( 'Number of failures: ' + failed.length );
		 *     });
		 */
		load : function( scriptUrl, callback, scope, noCheck )
		{
			var isString = ( typeof scriptUrl == 'string' );

			if ( isString )
				scriptUrl = [ scriptUrl ];

			if ( !scope )
				scope = CKEDITOR;

			var scriptCount = scriptUrl.length,
				completed = [],
				failed = [];

			var doCallback = function( success )
			{
				if ( callback )
				{
					if ( isString )
						callback.call( scope, success );
					else
						callback.call( scope, completed, failed );
				}
			};

			if ( scriptCount === 0 )
			{
				doCallback( true );
				return;
			}

			var checkLoaded = function( url, success )
			{
				( success ? completed : failed ).push( url );

				if ( --scriptCount <= 0 )
					doCallback( success );
			};

			var onLoad = function( url, success )
			{
				// Mark this script as loaded.
				uniqueScripts[ url ] = 1;

				// Get the list of callback checks waiting for this file.
				var waitingInfo = waitingList[ url ];
				delete waitingList[ url ];

				// Check all callbacks waiting for this file.
				for ( var i = 0 ; i < waitingInfo.length ; i++ )
					waitingInfo[ i ]( url, success );
			};

			var loadScript = function( url )
			{
				if ( noCheck !== true && uniqueScripts[ url ] )
				{
					checkLoaded( url, true );
					return;
				}

				var waitingInfo = waitingList[ url ] || ( waitingList[ url ] = [] );
				waitingInfo.push( checkLoaded );

				// Load it only for the first request.
				if ( waitingInfo.length > 1 )
					return;

				// Create the <script> element.
				var script = new CKEDITOR.dom.element( 'script' );
				script.setAttributes( {
					type : 'text/javascript',
					src : url } );

				if ( callback )
				{
					if ( CKEDITOR.env.ie )
					{
						// FIXME: For IE, we are not able to return false on error (like 404).

						/** @ignore */
						script.$.onreadystatechange = function ()
						{
							if ( script.$.readyState == 'loaded' || script.$.readyState == 'complete' )
							{
								script.$.onreadystatechange = null;
								onLoad( url, true );
							}
						};
					}
					else
					{
						/** @ignore */
						script.$.onload = function()
						{
							// Some browsers, such as Safari, may call the onLoad function
							// immediately. Which will break the loading sequence. (#3661)
							setTimeout( function() { onLoad( url, true ); }, 0 );
						};

						// FIXME: Opera and Safari will not fire onerror.

						/** @ignore */
						script.$.onerror = function()
						{
							onLoad( url, false );
						};
					}
				}

				// Append it to <head>.
				script.appendTo( CKEDITOR.document.getHead() );

				CKEDITOR.fire( 'download', url );		// @Packager.RemoveLine
			};

			for ( var i = 0 ; i < scriptCount ; i++ )
			{
				loadScript( scriptUrl[ i ] );
			}
		},

		/**
		 * Executes a JavaScript code into the current document.
		 * @param {String} code The code to be executed.
		 * @example
		 * CKEDITOR.scriptLoader.loadCode( 'var x = 10;' );
		 * alert( x );  // "10"
		 */
		loadCode : function( code )
		{
			// Create the <script> element.
			var script = new CKEDITOR.dom.element( 'script' );
			script.setAttribute( 'type', 'text/javascript' );
			script.appendText( code );

			// Append it to <head>.
			script.appendTo( CKEDITOR.document.getHead() );
		}
	};
})();
