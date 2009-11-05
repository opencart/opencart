/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @fileOverview Defines the {@link CKEDITOR.skins} object, which is used to
 *		manage skins loading.
 */

/**
 * Manages skins loading.
 * @namespace
 * @example
 */
CKEDITOR.skins = (function()
{
	// Holds the list of loaded skins.
	var loaded = {};
	var preloaded = {};
	var paths = {};

	var loadedPart = function( skinName, part, callback )
	{
		// Get the skin definition.
		var skinDefinition = loaded[ skinName ];

		var appendSkinPath = function( fileNames )
		{
			for ( var n = 0 ; n < fileNames.length ; n++ )
			{
				fileNames[ n ] = CKEDITOR.getUrl( paths[ skinName ] + fileNames[ n ] );
			}
		};

		// Check if we need to preload images from it.
		if ( !preloaded[ skinName ] )
		{
			var preload = skinDefinition.preload;
			if ( preload && preload.length > 0 )
			{
				appendSkinPath( preload );
				CKEDITOR.imageCacher.load( preload, function()
					{
						preloaded[ skinName ] = 1;
						loadedPart( skinName, part, callback );
					} );
				return;
			}

			// Mark it as preloaded.
			preloaded[ skinName ] = 1;
		}

		// Get the part definition.
		part = skinDefinition[ part ];
		var partIsLoaded = !part || !!part._isLoaded;

		// Call the callback immediately if already loaded.
		if ( partIsLoaded )
			callback && callback();
		else
		{
			// Put the callback in a queue.
			var pending = part._pending || ( part._pending = [] );
			pending.push( callback );

			// We may have more than one skin part load request. Just the first
			// one must do the loading job.
			if ( pending.length > 1 )
				return;

			// Check whether the "css" and "js" properties have been defined
			// for that part.
			var cssIsLoaded = !part.css || !part.css.length;
			var jsIsLoaded = !part.js || !part.js.length;

			// This is the function that will trigger the callback calls on
			// load.
			var checkIsLoaded = function()
			{
				if ( cssIsLoaded && jsIsLoaded )
				{
					// Mark the part as loaded.
					part._isLoaded = 1;

					// Call all pending callbacks.
					for ( var i = 0 ; i < pending.length ; i++ )
					{
						if ( pending[ i ] )
							pending[ i ]();
					}
				}
			};

			// Load the "css" pieces.
			if ( !cssIsLoaded )
			{
				appendSkinPath( part.css );

				for ( var c = 0 ; c < part.css.length ; c++ )
					CKEDITOR.document.appendStyleSheet( part.css[ c ] );

				cssIsLoaded = 1;
			}

			// Load the "js" pieces.
			if ( !jsIsLoaded )
			{
				appendSkinPath( part.js );
				CKEDITOR.scriptLoader.load( part.js, function()
					{
						jsIsLoaded = 1;
						checkIsLoaded();
					});
			}

			// We may have nothing to load, so check it immediately.
			checkIsLoaded();
		}
	};

	return /** @lends CKEDITOR.skins */ {

		/**
		 * Registers a skin definition.
		 * @param {String} skinName The skin name.
		 * @param {Object} skinDefinition The skin definition.
		 * @example
		 */
		add : function( skinName, skinDefinition )
		{
			loaded[ skinName ] = skinDefinition;

			skinDefinition.skinPath = paths[ skinName ]
				|| ( paths[ skinName ] =
						CKEDITOR.getUrl(
							'_source/' +	// @Packager.RemoveLine
							'skins/' + skinName + '/' ) );
		},

		/**
		 * Loads a skin part. Skins are defined in parts, which are basically
		 * separated CSS files. This function is mainly used by the core code and
		 * should not have much use out of it.
		 * @param {String} skinName The name of the skin to be loaded.
		 * @param {String} skinPart The skin part to be loaded. Common skin parts
		 *		are "editor" and "dialog".
		 * @param {Function} [callback] A function to be called once the skin
		 *		part files are loaded.
		 * @example
		 */
		load : function( editor, skinPart, callback )
		{
			var skinName = editor.skinName,
				skinPath = editor.skinPath;

			if ( loaded[ skinName ] )
			{
				loadedPart( skinName, skinPart, callback );

				// Get the skin definition.
				var skinDefinition = loaded[ skinName ];

				// Trigger init function if any.
				if ( skinDefinition.init )
					skinDefinition.init( editor );
			}
			else
			{
				paths[ skinName ] = skinPath;
				CKEDITOR.scriptLoader.load( skinPath + 'skin.js', function()
						{
							loadedPart( skinName, skinPart, callback );

							// Get the skin definition.
							var skinDefinition = loaded[ skinName ];

							// Trigger init function if any.
							if ( skinDefinition.init )
								skinDefinition.init( editor );
						});
			}
		}
	};
})();
