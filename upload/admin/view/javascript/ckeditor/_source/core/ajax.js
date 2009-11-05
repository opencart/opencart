/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @fileOverview Defines the {@link CKEDITOR.ajax} object, which holds ajax methods for
 *		data loading.
 */

/**
 * Ajax methods for data loading.
 * @namespace
 * @example
 */
CKEDITOR.ajax = (function()
{
	var createXMLHttpRequest = function()
	{
		// In IE, using the native XMLHttpRequest for local files may throw
		// "Access is Denied" errors.
		if ( !CKEDITOR.env.ie || location.protocol != 'file:' )
			try { return new XMLHttpRequest(); } catch(e) {}

		try { return new ActiveXObject( 'Msxml2.XMLHTTP' ); } catch (e) {}
		try { return new ActiveXObject( 'Microsoft.XMLHTTP' ); } catch (e) {}

		return null;
	};

	var checkStatus = function( xhr )
	{
		// HTTP Status Codes:
		//	 2xx : Success
		//	 304 : Not Modified
		//	   0 : Returned when running locally (file://)
		//	1223 : IE may change 204 to 1223 (see http://dev.jquery.com/ticket/1450)

		return ( xhr.readyState == 4 &&
				(	( xhr.status >= 200 && xhr.status < 300 ) ||
					xhr.status == 304 ||
					xhr.status === 0 ||
					xhr.status == 1223 ) );
	};

	var getResponseText = function( xhr )
	{
		if ( checkStatus( xhr ) )
			return xhr.responseText;
		return null;
	};

	var getResponseXml = function( xhr )
	{
		if ( checkStatus( xhr ) )
		{
			var xml = xhr.responseXML;
			return new CKEDITOR.xml( xml && xml.firstChild ? xml : xhr.responseText );
		}
		return null;
	};

	var load = function( url, callback, getResponseFn )
	{
		var async = !!callback;

		var xhr = createXMLHttpRequest();

		if ( !xhr )
			return null;

		xhr.open( 'GET', url, async );

		if ( async )
		{
			// TODO: perform leak checks on this closure.
			/** @ignore */
			xhr.onreadystatechange = function()
			{
				if ( xhr.readyState == 4 )
				{
					callback( getResponseFn( xhr ) );
					xhr = null;
				}
			};
		}

		xhr.send(null);

		return async ? '' : getResponseFn( xhr );
	};

	return 	/** @lends CKEDITOR.ajax */ {

		/**
		 * Loads data from an URL as plain text.
		 * @param {String} url The URL from which load data.
		 * @param {Function} [callback] A callback function to be called on
		 *		data load. If not provided, the data will be loaded
		 *		asynchronously, passing the data value the function on load.
		 * @returns {String} The loaded data. For asynchronous requests, an
		 *		empty string. For invalid requests, null.
		 * @example
		 * // Load data synchronously.
		 * var data = CKEDITOR.ajax.load( 'somedata.txt' );
		 * alert( data );
		 * @example
		 * // Load data asynchronously.
		 * var data = CKEDITOR.ajax.load( 'somedata.txt', function( data )
		 *     {
		 *         alert( data );
		 *     } );
		 */
		load : function( url, callback )
		{
			return load( url, callback, getResponseText );
		},

		/**
		 * Loads data from an URL as XML.
		 * @param {String} url The URL from which load data.
		 * @param {Function} [callback] A callback function to be called on
		 *		data load. If not provided, the data will be loaded
		 *		asynchronously, passing the data value the function on load.
		 * @returns {CKEDITOR.xml} An XML object holding the loaded data. For asynchronous requests, an
		 *		empty string. For invalid requests, null.
		 * @example
		 * // Load XML synchronously.
		 * var xml = CKEDITOR.ajax.loadXml( 'somedata.xml' );
		 * alert( xml.getInnerXml( '//' ) );
		 * @example
		 * // Load XML asynchronously.
		 * var data = CKEDITOR.ajax.loadXml( 'somedata.xml', function( xml )
		 *     {
		 *         alert( xml.getInnerXml( '//' ) );
		 *     } );
		 */
		loadXml : function( url, callback )
		{
			return load( url, callback, getResponseXml );
		}
	};
})();
