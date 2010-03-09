/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @fileOverview Defines the {@link CKEDITOR.editor} class, which represents an
 *		editor instance.
 */

(function()
{
	// The counter for automatic instance names.
	var nameCounter = 0;

	var getNewName = function()
	{
		var name = 'editor' + ( ++nameCounter );
		return ( CKEDITOR.instances && CKEDITOR.instances[ name ] ) ? getNewName() : name;
	};

	// ##### START: Config Privates

	// These function loads custom configuration files and cache the
	// CKEDITOR.editorConfig functions defined on them, so there is no need to
	// download them more than once for several instances.
	var loadConfigLoaded = {};
	var loadConfig = function( editor )
	{
		var customConfig = editor.config.customConfig;

		// Check if there is a custom config to load.
		if ( !customConfig )
			return false;

		var loadedConfig = loadConfigLoaded[ customConfig ] || ( loadConfigLoaded[ customConfig ] = {} );

		// If the custom config has already been downloaded, reuse it.
		if ( loadedConfig.fn )
		{
			// Call the cached CKEDITOR.editorConfig defined in the custom
			// config file for the editor instance depending on it.
			loadedConfig.fn.call( editor, editor.config );

			// If there is no other customConfig in the chain, fire the
			// "configLoaded" event.
			if ( editor.config.customConfig == customConfig || !loadConfig( editor ) )
				editor.fireOnce( 'customConfigLoaded' );
		}
		else
		{
			// Load the custom configuration file.
			CKEDITOR.scriptLoader.load( customConfig, function()
				{
					// If the CKEDITOR.editorConfig function has been properly
					// defined in the custom configuration file, cache it.
					if ( CKEDITOR.editorConfig )
						loadedConfig.fn = CKEDITOR.editorConfig;
					else
						loadedConfig.fn = function(){};

					// Call the load config again. This time the custom
					// config is already cached and so it will get loaded.
					loadConfig( editor );
				});
		}

		return true;
	};

	var initConfig = function( editor, instanceConfig )
	{
		// Setup the lister for the "customConfigLoaded" event.
		editor.on( 'customConfigLoaded', function()
			{
				if ( instanceConfig )
				{
					// Register the events that may have been set at the instance
					// configuration object.
					if ( instanceConfig.on )
					{
						for ( var eventName in instanceConfig.on )
						{
							editor.on( eventName, instanceConfig.on[ eventName ] );
						}
					}

					// Overwrite the settings from the in-page config.
					CKEDITOR.tools.extend( editor.config, instanceConfig, true );

					delete editor.config.on;
				}

				onConfigLoaded( editor );
			});

		// The instance config may override the customConfig setting to avoid
		// loading the default ~/config.js file.
		if ( instanceConfig && instanceConfig.customConfig != undefined )
			editor.config.customConfig = instanceConfig.customConfig;

		// Load configs from the custom configuration files.
		if ( !loadConfig( editor ) )
			editor.fireOnce( 'customConfigLoaded' );
	};

	// ##### END: Config Privates

	var onConfigLoaded = function( editor )
	{
		// Set config related properties.

		var skin = editor.config.skin.split( ',' ),
			skinName = skin[ 0 ],
			skinPath = CKEDITOR.getUrl( skin[ 1 ] || (
				'_source/' +	// @Packager.RemoveLine
				'skins/' + skinName + '/' ) );

		editor.skinName = skinName;
		editor.skinPath = skinPath;
		editor.skinClass = 'cke_skin_' + skinName;

		// Fire the "configLoaded" event.
		editor.fireOnce( 'configLoaded' );

		// Load language file.
		loadLang( editor );
	};

	var loadLang = function( editor )
	{
		CKEDITOR.lang.load( editor.config.language, editor.config.defaultLanguage, function( languageCode, lang )
			{
				editor.langCode = languageCode;

				// As we'll be adding plugin specific entries that could come
				// from different language code files, we need a copy of lang,
				// not a direct reference to it.
				editor.lang = CKEDITOR.tools.prototypedCopy( lang );

				// We're not able to support RTL in Firefox 2 at this time.
				if ( CKEDITOR.env.gecko && CKEDITOR.env.version < 10900 && editor.lang.dir == 'rtl' )
					editor.lang.dir = 'ltr';

				loadPlugins( editor );
			});
	};

	var loadPlugins = function( editor )
	{
		var config			= editor.config,
			plugins			= config.plugins,
			extraPlugins	= config.extraPlugins,
			removePlugins	= config.removePlugins;

		if ( extraPlugins )
		{
			// Remove them first to avoid duplications.
			var removeRegex = new RegExp( '(?:^|,)(?:' + extraPlugins.replace( /\s*,\s*/g, '|' ) + ')(?=,|$)' , 'g' );
			plugins = plugins.replace( removeRegex, '' );

			plugins += ',' + extraPlugins;
		}

		if ( removePlugins )
		{
			removeRegex = new RegExp( '(?:^|,)(?:' + removePlugins.replace( /\s*,\s*/g, '|' ) + ')(?=,|$)' , 'g' );
			plugins = plugins.replace( removeRegex, '' );
		}

		// Load all plugins defined in the "plugins" setting.
		CKEDITOR.plugins.load( plugins.split( ',' ), function( plugins )
			{
				// The list of plugins.
				var pluginsArray = [];

				// The language code to get loaded for each plugin. Null
				// entries will be appended for plugins with no language files.
				var languageCodes = [];

				// The list of URLs to language files.
				var languageFiles = [];

				// Cache the loaded plugin names.
				editor.plugins = plugins;

				// Loop through all plugins, to build the list of language
				// files to get loaded.
				for ( var pluginName in plugins )
				{
					var plugin = plugins[ pluginName ],
						pluginLangs = plugin.lang,
						pluginPath = CKEDITOR.plugins.getPath( pluginName ),
						lang = null;

					// Set the plugin path in the plugin.
					plugin.path = pluginPath;

					// If the plugin has "lang".
					if ( pluginLangs )
					{
						// Resolve the plugin language. If the current language
						// is not available, get the first one (default one).
						lang = ( CKEDITOR.tools.indexOf( pluginLangs, editor.langCode ) >= 0 ? editor.langCode : pluginLangs[ 0 ] );

						if ( !plugin.lang[ lang ] )
						{
							// Put the language file URL into the list of files to
							// get downloaded.
							languageFiles.push( CKEDITOR.getUrl( pluginPath + 'lang/' + lang + '.js' ) );
						}
						else
						{
							CKEDITOR.tools.extend( editor.lang, plugin.lang[ lang ] );
							lang = null;
						}
					}

					// Save the language code, so we know later which
					// language has been resolved to this plugin.
					languageCodes.push( lang );

					pluginsArray.push( plugin );
				}

				// Load all plugin specific language files in a row.
				CKEDITOR.scriptLoader.load( languageFiles, function()
					{
						// Initialize all plugins that have the "beforeInit" and "init" methods defined.
						var methods = [ 'beforeInit', 'init', 'afterInit' ];
						for ( var m = 0 ; m < methods.length ; m++ )
						{
							for ( var i = 0 ; i < pluginsArray.length ; i++ )
							{
								var plugin = pluginsArray[ i ];

								// Uses the first loop to update the language entries also.
								if ( m === 0 && languageCodes[ i ] && plugin.lang )
									CKEDITOR.tools.extend( editor.lang, plugin.lang[ languageCodes[ i ] ] );

								// Call the plugin method (beforeInit and init).
								if ( plugin[ methods[ m ] ] )
									plugin[ methods[ m ] ]( editor );
							}
						}

						// Load the editor skin.
						editor.fire( 'pluginsLoaded' );
						loadSkin( editor );
					});
			});
	};

	var loadSkin = function( editor )
	{
		CKEDITOR.skins.load( editor, 'editor', function()
			{
				loadTheme( editor );
			});
	};

	var loadTheme = function( editor )
	{
		var theme = editor.config.theme;
		CKEDITOR.themes.load( theme, function()
			{
				var editorTheme = editor.theme = CKEDITOR.themes.get( theme );
				editorTheme.path = CKEDITOR.themes.getPath( theme );
				editorTheme.build( editor );

				if ( editor.config.autoUpdateElement )
					attachToForm( editor );
			});
	};

	var attachToForm = function( editor )
	{
		var element = editor.element;

		// If are replacing a textarea, we must
		if ( editor.elementMode == CKEDITOR.ELEMENT_MODE_REPLACE && element.is( 'textarea' ) )
		{
			var form = element.$.form && new CKEDITOR.dom.element( element.$.form );
			if ( form )
			{
				function onSubmit()
				{
					editor.updateElement();
				}
				form.on( 'submit',onSubmit );

				// Setup the submit function because it doesn't fire the
				// "submit" event.
				if ( !form.$.submit.nodeName )
				{
					form.$.submit = CKEDITOR.tools.override( form.$.submit, function( originalSubmit )
						{
							return function()
								{
									editor.updateElement();

									// For IE, the DOM submit function is not a
									// function, so we need thid check.
									if ( originalSubmit.apply )
										originalSubmit.apply( this, arguments );
									else
										originalSubmit();
								};
						});
				}

				// Remove 'submit' events registered on form element before destroying.(#3988)
				editor.on( 'destroy', function()
				{
					form.removeListener( 'submit', onSubmit );
				} );
			}
		}
	};

	function updateCommandsMode()
	{
		var command,
			commands = this._.commands,
			mode = this.mode;

		for ( var name in commands )
		{
			command = commands[ name ];
			command[ command.modes[ mode ] ? 'enable' : 'disable' ]();
		}
	}

	/**
	 * Initializes the editor instance. This function is called by the editor
	 * contructor (editor_basic.js).
	 * @private
	 */
	CKEDITOR.editor.prototype._init = function()
		{
			// Get the properties that have been saved in the editor_base
			// implementation.
			var element			= CKEDITOR.dom.element.get( this._.element ),
				instanceConfig	= this._.instanceConfig;
			delete this._.element;
			delete this._.instanceConfig;

			this._.commands = {};
			this._.styles = [];

			/**
			 * The DOM element that has been replaced by this editor instance. This
			 * element holds the editor data on load and post.
			 * @name CKEDITOR.editor.prototype.element
			 * @type CKEDITOR.dom.element
			 * @example
			 * var editor = CKEDITOR.instances.editor1;
			 * alert( <b>editor.element</b>.getName() );  "textarea"
			 */
			this.element = element;

			/**
			 * The editor instance name. It hay be the replaced element id, name or
			 * a default name using a progressive counter (editor1, editor2, ...).
			 * @name CKEDITOR.editor.prototype.name
			 * @type String
			 * @example
			 * var editor = CKEDITOR.instances.editor1;
			 * alert( <b>editor.name</b> );  "editor1"
			 */
			this.name = ( element && ( this.elementMode == CKEDITOR.ELEMENT_MODE_REPLACE )
							&& ( element.getId() || element.getNameAtt() ) )
						|| getNewName();

			if ( this.name in CKEDITOR.instances )
				throw '[CKEDITOR.editor] The instance "' + this.name + '" already exists.';

			/**
			 * The configurations for this editor instance. It inherits all
			 * settings defined in (@link CKEDITOR.config}, combined with settings
			 * loaded from custom configuration files and those defined inline in
			 * the page when creating the editor.
			 * @name CKEDITOR.editor.prototype.config
			 * @type Object
			 * @example
			 * var editor = CKEDITOR.instances.editor1;
			 * alert( <b>editor.config.theme</b> );  "default" e.g.
			 */
			this.config = CKEDITOR.tools.prototypedCopy( CKEDITOR.config );

			/**
			 * Namespace containing UI features related to this editor instance.
			 * @name CKEDITOR.editor.prototype.ui
			 * @type CKEDITOR.ui
			 * @example
			 */
			this.ui = new CKEDITOR.ui( this );

			/**
			 * Controls the focus state of this editor instance. This property
			 * is rarely used for normal API operations. It is mainly
			 * destinated to developer adding UI elements to the editor interface.
			 * @name CKEDITOR.editor.prototype.focusManager
			 * @type CKEDITOR.focusManager
			 * @example
			 */
			this.focusManager = new CKEDITOR.focusManager( this );

			CKEDITOR.fire( 'instanceCreated', null, this );

			this.on( 'mode', updateCommandsMode, null, null, 1 );

			initConfig( this, instanceConfig );
		};
})();

CKEDITOR.tools.extend( CKEDITOR.editor.prototype,
	/** @lends CKEDITOR.editor.prototype */
	{
		/**
		 * Adds a command definition to the editor instance. Commands added with
		 * this function can be later executed with {@link #execCommand}.
		 * @param {String} commandName The indentifier name of the command.
		 * @param {CKEDITOR.commandDefinition} commandDefinition The command definition.
		 * @example
		 * editorInstance.addCommand( 'sample',
		 * {
		 *     exec : function( editor )
		 *     {
		 *         alert( 'Executing a command for the editor name "' + editor.name + '"!' );
		 *     }
		 * });
		 */
		addCommand : function( commandName, commandDefinition )
		{
			return this._.commands[ commandName ] = new CKEDITOR.command( this, commandDefinition );
		},

		/**
		 * Add a trunk of css text to the editor which will be applied to the wysiwyg editing document.
		 * Note: This function should be called before editor is loaded to take effect.
		 * @param css {String} CSS text.
		 * @example
		 * editorInstance.addCss( 'body { background-color: grey; }' );
		 */
		addCss : function( css )
		{
			this._.styles.push( css );
		},

		/**
		 * Destroys the editor instance, releasing all resources used by it.
		 * If the editor replaced an element, the element will be recovered.
		 * @param {Boolean} [noUpdate] If the instance is replacing a DOM
		 *		element, this parameter indicates whether or not to update the
		 *		element with the instance contents.
		 * @example
		 * alert( CKEDITOR.instances.editor1 );  e.g "object"
		 * <b>CKEDITOR.instances.editor1.destroy()</b>;
		 * alert( CKEDITOR.instances.editor1 );  "undefined"
		 */
		destroy : function( noUpdate )
		{
			if ( !noUpdate )
				this.updateElement();

			this.theme.destroy( this );
			this.fire( 'destroy' );
			CKEDITOR.remove( this );
		},

		/**
		 * Executes a command.
		 * @param {String} commandName The indentifier name of the command.
		 * @param {Object} [data] Data to be passed to the command
		 * @returns {Boolean} "true" if the command has been successfuly
		 *		executed, otherwise "false".
		 * @example
		 * editorInstance.execCommand( 'Bold' );
		 */
		execCommand : function( commandName, data )
		{
			var command = this.getCommand( commandName );

			var eventData =
			{
				name: commandName,
				commandData: data,
				command: command
			};

			if ( command && command.state != CKEDITOR.TRISTATE_DISABLED )
			{
				if ( this.fire( 'beforeCommandExec', eventData ) !== true )
				{
					eventData.returnValue = command.exec( eventData.commandData );

					// Fire the 'afterCommandExec' immediately if command is synchronous.
					if ( !command.async && this.fire( 'afterCommandExec', eventData ) !== true )
						return eventData.returnValue;
				}
			}

			// throw 'Unknown command name "' + commandName + '"';
			return false;
		},

		/**
		 * Gets one of the registered commands. Note that, after registering a
		 * command definition with addCommand, it is transformed internally
		 * into an instance of {@link CKEDITOR.command}, which will be then
		 * returned by this function.
		 * @param {String} commandName The name of the command to be returned.
		 * This is the same used to register the command with addCommand.
		 * @returns {CKEDITOR.command} The command object identified by the
		 * provided name.
		 */
		getCommand : function( commandName )
		{
			return this._.commands[ commandName ];
		},

		/**
		 * Gets the editor data. The data will be in raw format. It is the same
		 * data that is posted by the editor.
		 * @type String
		 * @returns (String) The editor data.
		 * @example
		 * if ( CKEDITOR.instances.editor1.<b>getData()</b> == '' )
		 *     alert( 'There is no data available' );
		 */
		getData : function()
		{
			this.fire( 'beforeGetData' );

			var eventData = this._.data;

			if ( typeof eventData != 'string' )
			{
				var element = this.element;
				if ( element && this.elementMode == CKEDITOR.ELEMENT_MODE_REPLACE )
					eventData = element.is( 'textarea' ) ? element.getValue() : element.getHtml();
				else
					eventData = '';
			}

			eventData = { dataValue : eventData };

			// Fire "getData" so data manipulation may happen.
			this.fire( 'getData', eventData );

			return eventData.dataValue;
		},

		getSnapshot : function()
		{
			var data = this.fire( 'getSnapshot' );

			if ( typeof data != 'string' )
			{
				var element = this.element;
				if ( element && this.elementMode == CKEDITOR.ELEMENT_MODE_REPLACE )
					data = element.is( 'textarea' ) ? element.getValue() : element.getHtml();
			}

			return data;
		},

		loadSnapshot : function( snapshot )
		{
			this.fire( 'loadSnapshot', snapshot );
		},

		/**
		 * Sets the editor data. The data must be provided in raw format.
		 * @param {String} data HTML code to replace the curent content in the editor.
		 * @param {Function} callback Function to be called after the setData is completed.
		 * @example
		 * CKEDITOR.instances.editor1.<b>setData( '&lt;p&gt;This is the editor data.&lt;/p&gt;' )</b>;
		 * CKEDITOR.instances.editor1.setData( '&lt;p&gt;Some other editor data.&lt;/p&gt;', function()
		 * {
		 * 		CKEDITOR.instances.editor1.checkDirty(); 	// true
		 * } );
		 */
		setData : function( data , callback )
		{
			if( callback )
			{
				this.on( 'dataReady', function( evt )
				{
					evt.removeListener();
					callback.call( evt.editor );
				} );
			}
			// Fire "setData" so data manipulation may happen.
			var eventData = { dataValue : data };
			this.fire( 'setData', eventData );

			this._.data = eventData.dataValue;

			this.fire( 'afterSetData', eventData );
		},

		/**
		 * Inserts HTML into the currently selected position in the editor.
		 * @param {String} data HTML code to be inserted into the editor.
		 * @example
		 * CKEDITOR.instances.editor1.<b>insertHtml( '&lt;p&gt;This is a new paragraph.&lt;/p&gt;' )</b>;
		 */
		insertHtml : function( data )
		{
			this.fire( 'insertHtml', data );
		},

		/**
		 * Inserts an element into the currently selected position in the
		 * editor.
		 * @param {CKEDITOR.dom.element} element The element to be inserted
		 *		into the editor.
		 * @example
		 * var element = CKEDITOR.dom.element.createFromHtml( '&lt;img src="hello.png" border="0" title="Hello" /&gt;' );
		 * CKEDITOR.instances.editor1.<b>insertElement( element )</b>;
		 */
		insertElement : function( element )
		{
			this.fire( 'insertElement', element );
		},

		checkDirty : function()
		{
			return ( this.mayBeDirty && this._.previousValue !== this.getSnapshot() );
		},

		resetDirty : function()
		{
			if ( this.mayBeDirty )
				this._.previousValue = this.getSnapshot();
		},

		/**
		 * Updates the &lt;textarea&gt; element that has been replaced by the editor with
		 * the current data available in the editor.
		 * @example
		 * CKEDITOR.instances.editor1.updateElement();
		 * alert( document.getElementById( 'editor1' ).value );  // The current editor data.
		 */
		updateElement : function()
		{
			var element = this.element;
			if ( element && this.elementMode == CKEDITOR.ELEMENT_MODE_REPLACE )
			{
				if ( element.is( 'textarea' ) )
					element.setValue( this.getData() );
				else
					element.setHtml( this.getData() );
			}
		}
	});

CKEDITOR.on( 'loaded', function()
	{
		// Run the full initialization for pending editors.
		var pending = CKEDITOR.editor._pending;
		if ( pending )
		{
			delete CKEDITOR.editor._pending;

			for ( var i = 0 ; i < pending.length ; i++ )
				pending[ i ]._init();
		}
	});
