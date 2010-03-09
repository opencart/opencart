/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @fileOverview Contains the second part of the {@link CKEDITOR} object
 *		definition, which defines the basic editor features to be available in
 *		the root ckeditor_basic.js file.
 */

if ( CKEDITOR.status == 'unloaded' )
{
	(function()
	{
		CKEDITOR.event.implementOn( CKEDITOR );

		/**
		 * Forces the full CKEditor core code, in the case only the basic code has been
		 * loaded (ckeditor_basic.js). This method self-destroys (becomes undefined) in
		 * the first call or as soon as the full code is available.
		 * @example
		 * // Check if the full core code has been loaded and load it.
		 * if ( CKEDITOR.loadFullCore )
		 *     <b>CKEDITOR.loadFullCore()</b>;
		 */
		CKEDITOR.loadFullCore = function()
		{
			// If not the basic code is not ready it, just mark it to be loaded.
			if ( CKEDITOR.status != 'basic_ready' )
			{
				CKEDITOR.loadFullCore._load = true;
				return;
			}

			// Destroy this function.
			delete CKEDITOR.loadFullCore;

			// Append the script to the head.
			var script = document.createElement( 'script' );
			script.type = 'text/javascript';
			script.src = CKEDITOR.basePath + 'ckeditor.js';

			document.getElementsByTagName( 'head' )[0].appendChild( script );
		};

		/**
		 * The time to wait (in seconds) to load the full editor code after the
		 * page load, if the "ckeditor_basic" file is used. If set to zero, the
		 * editor is loaded on demand, as soon as an instance is created.
		 *
		 * This value must be set on the page before the page load completion.
		 * @type Number
		 * @default 0 (zero)
		 * @example
		 * // Loads the full source after five seconds.
		 * CKEDITOR.loadFullCoreTimeout = 5;
		 */
		CKEDITOR.loadFullCoreTimeout = 0;

		/**
		 * The class name used to identify &lt;textarea&gt; elements to be replace
		 * by CKEditor instances.
		 * @type String
		 * @default 'ckeditor'
		 * @example
		 * <b>CKEDITOR.replaceClass</b> = 'rich_editor';
		 */
		CKEDITOR.replaceClass = 'ckeditor';

		/**
		 * Enables the replacement of all textareas with class name matching
		 * {@link CKEDITOR.replaceClass}.
		 * @type Boolean
		 * @default true
		 * @example
		 * // Disable the auto-replace feature.
		 * <b>CKEDITOR.replaceByClassEnabled</b> = false;
		 */
		CKEDITOR.replaceByClassEnabled = true;

		var createInstance = function( elementOrIdOrName, config, creationFunction )
		{
			if ( CKEDITOR.env.isCompatible )
			{
				// Load the full core.
				if ( CKEDITOR.loadFullCore )
					CKEDITOR.loadFullCore();

				var editor = creationFunction( elementOrIdOrName, config );
				CKEDITOR.add( editor );
				return editor;
			}

			return null;
		};

		/**
		 * Replaces a &lt;textarea&gt; or a DOM element (DIV) with a CKEditor
		 * instance. For textareas, the initial value in the editor will be the
		 * textarea value. For DOM elements, their innerHTML will be used
		 * instead. We recommend using TEXTAREA and DIV elements only.
		 * @param {Object|String} elementOrIdOrName The DOM element (textarea), its
		 *		ID or name.
		 * @param {Object} [config] The specific configurations to apply to this
		 *		editor instance. Configurations set here will override global CKEditor
		 *		settings.
		 * @returns {CKEDITOR.editor} The editor instance created.
		 * @example
		 * &lt;textarea id="myfield" name="myfield"&gt;&lt:/textarea&gt;
		 * ...
		 * <b>CKEDITOR.replace( 'myfield' )</b>;
		 * @example
		 * var textarea = document.body.appendChild( document.createElement( 'textarea' ) );
		 * <b>CKEDITOR.replace( textarea )</b>;
		 */
		CKEDITOR.replace = function( elementOrIdOrName, config )
		{
			return createInstance( elementOrIdOrName, config, CKEDITOR.editor.replace );
		};

		/**
		 * Creates a new editor instance inside a specific DOM element.
		 * @param {Object|String} elementOrId The DOM element or its ID.
		 * @param {Object} [config] The specific configurations to apply to this
		 *		editor instance. Configurations set here will override global CKEditor
		 *		settings.
		 * @returns {CKEDITOR.editor} The editor instance created.
		 * @example
		 * &lt;div id="editorSpace"&gt;&lt:/div&gt;
		 * ...
		 * <b>CKEDITOR.appendTo( 'editorSpace' )</b>;
		 */
		CKEDITOR.appendTo = function( elementOrId, config )
		{
			return createInstance( elementOrId, config, CKEDITOR.editor.appendTo );
		};

		/**
		 * @ignore
		 * Documented at ckeditor.js.
		 */
		CKEDITOR.add = function( editor )
		{
			// For now, just put the editor in the pending list. It will be
			// processed as soon as the full code gets loaded.
			var pending = this._.pending || ( this._.pending = [] );
			pending.push( editor );
		};

		/**
		 * Replace all &lt;textarea&gt; elements available in the document with
		 * editor instances.
		 * @example
		 * // Replace all &lt;textarea&gt; elements in the page.
		 * CKEDITOR.replaceAll();
		 * @example
		 * // Replace all &lt;textarea class="myClassName"&gt; elements in the page.
		 * CKEDITOR.replaceAll( 'myClassName' );
		 * @example
		 * // Selectively replace &lt;textarea&gt; elements, based on custom assertions.
		 * CKEDITOR.replaceAll( function( textarea, config )
		 *     {
		 *         // Custom code to evaluate the replace, returning false
		 *         // if it must not be done.
		 *         // It also passes the "config" parameter, so the
		 *         // developer can customize the instance.
		 *     } );
		 */
		CKEDITOR.replaceAll = function()
		{
			var textareas = document.getElementsByTagName( 'textarea' );

			for ( var i = 0 ; i < textareas.length ; i++ )
			{
				var config = null;
				var textarea = textareas[i];
				var name = textarea.name;

				// The "name" and/or "id" attribute must exist.
				if ( !textarea.name && !textarea.id )
					continue;

				if ( typeof arguments[0] == 'string' )
				{
					// The textarea class name could be passed as the function
					// parameter.

					var classRegex = new RegExp( '(?:^| )' + arguments[0] + '(?:$| )' );

					if ( !classRegex.test( textarea.className ) )
						continue;
				}
				else if ( typeof arguments[0] == 'function' )
				{
					// An assertion function could be passed as the function parameter.
					// It must explicitly return "false" to ignore a specific <textarea>.
					config = {};
					if ( arguments[0]( textarea, config ) === false )
						continue;
				}

				this.replace( textarea, config );
			}
		};

		(function()
		{
			var onload = function()
			{
				var loadFullCore = CKEDITOR.loadFullCore,
					loadFullCoreTimeout = CKEDITOR.loadFullCoreTimeout;

				// Replace all textareas with the default class name.
				if ( CKEDITOR.replaceByClassEnabled )
					CKEDITOR.replaceAll( CKEDITOR.replaceClass );

				CKEDITOR.status = 'basic_ready';

				if ( loadFullCore && loadFullCore._load )
					loadFullCore();
				else if ( loadFullCoreTimeout )
				{
					setTimeout( function()
						{
							if ( CKEDITOR.loadFullCore )
								CKEDITOR.loadFullCore();
						}
						, loadFullCoreTimeout * 1000 );
				}
			};

			if ( window.addEventListener )
				window.addEventListener( 'load', onload, false );
			else if ( window.attachEvent )
				window.attachEvent( 'onload', onload );
		})();

		CKEDITOR.status = 'basic_loaded';
	})();
}
