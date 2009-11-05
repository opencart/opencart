/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.plugins.add( 'smiley',
{
	requires : [ 'dialog' ],

	init : function( editor )
	{
		editor.addCommand( 'smiley', new CKEDITOR.dialogCommand( 'smiley' ) );
		editor.ui.addButton( 'Smiley',
			{
				label : editor.lang.smiley.toolbar,
				command : 'smiley'
			});
		CKEDITOR.dialog.add( 'smiley', this.path + 'dialogs/smiley.js' );
	}
} );

/**
 * The base path used to build the URL for the smiley images. It must end with
 * a slash.
 * @type String
 * @default {@link CKEDITOR.basePath} + 'plugins/smiley/images/'
 * @example
 * config.smiley_path = 'http://www.example.com/images/smileys/';
 * @example
 * config.smiley_path = '/images/smileys/';
 */
CKEDITOR.config.smiley_path = CKEDITOR.basePath +
	'_source/' +		// @Packager.RemoveLine
	'plugins/smiley/images/';

/**
 * The file names for the smileys to be displayed. These files must be
 * contained inside the URL path defined with the
 * {@link CKEDITOR.config.smiley_path} setting.
 * @type Array
 * @default (see example)
 * @example
 * // This is actually the default value.
 * config.smiley_images = [
 *     'regular_smile.gif','sad_smile.gif','wink_smile.gif','teeth_smile.gif','confused_smile.gif','tounge_smile.gif',
 *     'embaressed_smile.gif','omg_smile.gif','whatchutalkingabout_smile.gif','angry_smile.gif','angel_smile.gif','shades_smile.gif',
 *     'devil_smile.gif','cry_smile.gif','lightbulb.gif','thumbs_down.gif','thumbs_up.gif','heart.gif',
 *     'broken_heart.gif','kiss.gif','envelope.gif'];
 */
CKEDITOR.config.smiley_images = [
	'regular_smile.gif','sad_smile.gif','wink_smile.gif','teeth_smile.gif','confused_smile.gif','tounge_smile.gif',
	'embaressed_smile.gif','omg_smile.gif','whatchutalkingabout_smile.gif','angry_smile.gif','angel_smile.gif','shades_smile.gif',
	'devil_smile.gif','cry_smile.gif','lightbulb.gif','thumbs_down.gif','thumbs_up.gif','heart.gif',
	'broken_heart.gif','kiss.gif','envelope.gif'];

/**
 * The description to be used for each of the smileys defined in the
 * {@link CKEDITOR.config.smiley_images} setting. Each entry in this array list
 * must match its relative pair in the {@link CKEDITOR.config.smiley_images}
 * setting.
 * @type Array
 * @default (see example)
 * @example
 * // This is actually the default value.
 * config.smiley_descriptions = [
 *     ':)', ':(', ';)', ':D', ':/', ':P',
 *     '', '', '', '', '', '',
 *     '', ';(', '', '', '', '',
 *     '', ':kiss', '' ];
 */
CKEDITOR.config.smiley_descriptions = [
	':)', ':(', ';)', ':D', ':/', ':P',
	'', '', '', '', '', '',
	'', ';(', '', '', '', '',
	'', ':kiss', '' ];
