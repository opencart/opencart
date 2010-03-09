/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @fileOverview Defines the {@link CKEDITOR.resourceManager} class, which is
 *		the base for resource managers, like plugins and themes.
 */

 /**
 * Base class for resource managers, like plugins and themes. This class is not
 * intended to be used out of the CKEditor core code.
 * @param {String} basePath The path for the resources folder.
 * @param {String} fileName The name used for resource files.
 * @namespace
 * @example
 */
CKEDITOR.resourceManager = function( basePath, fileName )
{
	/**
	 * The base directory containing all resources.
	 * @name CKEDITOR.resourceManager.prototype.basePath
	 * @type String
	 * @example
	 */
	this.basePath = basePath;

	/**
	 * The name used for resource files.
	 * @name CKEDITOR.resourceManager.prototype.fileName
	 * @type String
	 * @example
	 */
	this.fileName = fileName;

	/**
	 * Contains references to all resources that have already been registered
	 * with {@link #add}.
	 * @name CKEDITOR.resourceManager.prototype.registered
	 * @type Object
	 * @example
	 */
	this.registered = {};

	/**
	 * Contains references to all resources that have already been loaded
	 * with {@link #load}.
	 * @name CKEDITOR.resourceManager.prototype.loaded
	 * @type Object
	 * @example
	 */
	this.loaded = {};

	/**
	 * Contains references to all resources that have already been registered
	 * with {@link #addExternal}.
	 * @name CKEDITOR.resourceManager.prototype.externals
	 * @type Object
	 * @example
	 */
	this.externals = {};

	/**
	 * @private
	 */
	this._ =
	{
		// List of callbacks waiting for plugins to be loaded.
		waitingList : {}
	};
};

CKEDITOR.resourceManager.prototype =
{
	/**
	 * Registers a resource.
	 * @param {String} name The resource name.
	 * @param {Object} [definition] The resource definition.
	 * @example
	 * CKEDITOR.plugins.add( 'sample', { ... plugin definition ... } );
	 * @see CKEDITOR.pluginDefinition
	 */
	add : function( name, definition )
	{
		if ( this.registered[ name ] )
			throw '[CKEDITOR.resourceManager.add] The resource name "' + name + '" is already registered.';

		this.registered[ name ] = definition || {};
	},

	/**
	 * Gets the definition of a specific resource.
	 * @param {String} name The resource name.
	 * @type Object
	 * @example
	 * var definition = <b>CKEDITOR.plugins.get( 'sample' )</b>;
	 */
	get : function( name )
	{
		return this.registered[ name ] || null;
	},

	/**
	 * Get the folder path for a specific loaded resource.
	 * @param {String} name The resource name.
	 * @type String
	 * @example
	 * alert( <b>CKEDITOR.plugins.getPath( 'sample' )</b> );  // "&lt;editor path&gt;/plugins/sample/"
	 */
	getPath : function( name )
	{
		var external = this.externals[ name ];
		return CKEDITOR.getUrl( ( external && external.dir ) || this.basePath + name + '/' );
	},

	/**
	 * Get the file path for a specific loaded resource.
	 * @param {String} name The resource name.
	 * @type String
	 * @example
	 * alert( <b>CKEDITOR.plugins.getFilePath( 'sample' )</b> );  // "&lt;editor path&gt;/plugins/sample/plugin.js"
	 */
	getFilePath : function( name )
	{
		var external = this.externals[ name ];
		return CKEDITOR.getUrl(
				this.getPath( name ) +
				( ( external && external.file ) || ( this.fileName + '.js' ) ) );
	},

	/**
	 * Registers one or more resources to be loaded from an external path
	 * instead of the core base path.
	 * @param {String} names The resource names, separated by commas.
	 * @param {String} path The path of the folder containing the resource.
	 * @param {String} [fileName] The resource file name. If not provided, the
	 *		default name is used.
	 * @example
	 * // Loads a plugin from '/myplugin/samples/plugin.js'.
	 * CKEDITOR.plugins.addExternal( 'sample', '/myplugins/sample/' );
	 * @example
	 * // Loads a plugin from '/myplugin/samples/my_plugin.js'.
	 * CKEDITOR.plugins.addExternal( 'sample', '/myplugins/sample/', 'my_plugin.js' );
	 */
	addExternal : function( names, path, fileName )
	{
		names = names.split( ',' );
		for ( var i = 0 ; i < names.length ; i++ )
		{
			var name = names[ i ];

			this.externals[ name ] =
			{
				dir : path,
				file : fileName
			};
		}
	},

	/**
	 * Loads one or more resources.
	 * @param {String|Array} name The name of the resource to load. It may be a
	 *		string with a single resource name, or an array with several names.
	 * @param {Function} callback A function to be called when all resources
	 *		are loaded. The callback will receive an array containing all
	 *		loaded names.
	 * @param {Object} [scope] The scope object to be used for the callback
	 *		call.
	 * @example
	 * <b>CKEDITOR.plugins.load</b>( 'myplugin', function( plugins )
	 *     {
	 *         alert( plugins['myplugin'] );  // "object"
	 *     });
	 */
	load : function( names, callback, scope )
	{
		// Ensure that we have an array of names.
		if ( !CKEDITOR.tools.isArray( names ) )
			names = names ? [ names ] : [];

		var loaded = this.loaded,
			registered = this.registered,
			urls = [],
			urlsNames = {},
			resources = {};

		// Loop through all names.
		for ( var i = 0 ; i < names.length ; i++ )
		{
			var name = names[ i ];

			if ( !name )
				continue;

			// If not available yet.
			if ( !loaded[ name ] && !registered[ name ] )
			{
				var url = this.getFilePath( name );
				urls.push( url );
				if ( !( url in urlsNames ) )
					urlsNames[ url ] = [];
				urlsNames[ url ].push( name );
			}
			else
				resources[ name ] = this.get( name );
		}

		CKEDITOR.scriptLoader.load( urls, function( completed, failed )
			{
				if ( failed.length )
				{
					throw '[CKEDITOR.resourceManager.load] Resource name "' + urlsNames[ failed[ 0 ] ].join( ',' )
						+ '" was not found at "' + failed[ 0 ] + '".';
				}

				for ( var i = 0 ; i < completed.length ; i++ )
				{
					var nameList = urlsNames[ completed[ i ] ];
					for ( var j = 0 ; j < nameList.length ; j++ )
					{
						var name = nameList[ j ];
						resources[ name ] = this.get( name );

						loaded[ name ] = 1;
					}
				}

				callback.call( scope, resources );
			}
			, this);
	}
};
