/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

(function()
{
	var loaded = {};

	var loadImage = function( image, callback )
	{
		var doCallback = function()
			{
				loaded[ image ] = 1;
				callback();
			};

		var img = new CKEDITOR.dom.element( 'img' );
		img.on( 'load', doCallback );
		img.on( 'error', doCallback );
		img.setAttribute( 'src', image );
	};

	/**
	 * Load images into the browser cache.
	 * @namespace
	 * @example
	 */
 	CKEDITOR.imageCacher =
	{
		/**
		 * Loads one or more images.
		 * @param {Array} images The URLs for the images to be loaded.
		 * @param {Function} callback The function to be called once all images
		 *		are loaded.
		 */
		load : function( images, callback )
		{
			var pendingCount = images.length;

			var checkPending = function()
			{
				if ( --pendingCount === 0 )
					callback();
			};

			for ( var i = 0 ; i < images.length ; i++ )
			{
				var image = images[ i ];

				if ( loaded[ image ] )
					checkPending();
				else
					loadImage( image, checkPending );
			}
		}
	};
})();
