/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @fileOverview Defines the {@link CKEDITOR.config} object, which holds the
 * default configuration settings.
 */

CKEDITOR.ENTER_P	= 1;
CKEDITOR.ENTER_BR	= 2;
CKEDITOR.ENTER_DIV	= 3;

/**
 * Holds the default configuration settings. Changes to this object are
 * reflected in all editor instances, if not specificaly specified for those
 * instances.
 * @namespace
 * @example
 * // All editor created after the following setting will not load custom
 * // configuration files.
 * CKEDITOR.config.customConfig = '';
 */
CKEDITOR.config =
{
	/**
	 * The URL path for the custom configuration file to be loaded. If not
	 * overloaded with inline configurations, it defaults to the "config.js"
	 * file present in the root of the CKEditor installation directory.<br /><br />
	 *
	 * CKEditor will recursively load custom configuration files defined inside
	 * other custom configuration files.
	 * @type String
	 * @default '&lt;CKEditor folder&gt;/config.js'
	 * @example
	 * // Load a specific configuration file.
	 * CKEDITOR.replace( 'myfiled', { customConfig : '/myconfig.js' } );
	 * @example
	 * // Do not load any custom configuration file.
	 * CKEDITOR.replace( 'myfiled', { customConfig : '' } );
	 */
	customConfig : CKEDITOR.getUrl( 'config.js' ),

	/**
	 * Whether the replaced element (usually a textarea) is to be updated
	 * automatically when posting the form containing the editor.
	 * @type Boolean
	 * @default true
	 * @example
	 * config.autoUpdateElement = true;
	 */
	autoUpdateElement : true,

	/**
	 * The base href URL used to resolve relative and absolute URLs in the
	 * editor content.
	 * @type String
	 * @default '' (empty string)
	 * @example
	 * config.baseHref = 'http://www.example.com/path/';
	 */
	baseHref : '',

	/**
	 * The CSS file(s) to be used to apply style to the contents. It should
	 * reflect the CSS used in the final pages where the contents are to be
	 * used.
	 * @type String|Array
	 * @default '&lt;CKEditor folder&gt;/contents.css'
	 * @example
	 * config.contentsCss = '/css/mysitestyles.css';
	 * config.contentsCss = ['/css/mysitestyles.css', '/css/anotherfile.css'];
	 */
	contentsCss : CKEDITOR.basePath + 'contents.css',

	/**
	 * The writting direction of the language used to write the editor
	 * contents. Allowed values are 'ltr' for Left-To-Right language (like
	 * English), or 'rtl' for Right-To-Left languages (like Arabic).
	 * @default 'ltr'
	 * @type String
	 * @example
	 * config.contentsLangDirection = 'rtl';
	 */
	contentsLangDirection : 'ltr',

	/**
	 * The user interface language localization to use. If empty, the editor
	 * automatically localize the editor to the user language, if supported,
	 * otherwise the {@link CKEDITOR.config.defaultLanguage} language is used.
	 * @default true
	 * @type Boolean
	 * @example
	 * // Load the German interface.
	 * config.language = 'de';
	 */
	language : '',

	/**
	 * The language to be used if {@link CKEDITOR.config.language} is left empty and it's not
	 * possible to localize the editor to the user language.
	 * @default 'en'
	 * @type String
	 * @example
	 * config.defaultLanguage = 'it';
	 */
	defaultLanguage : 'en',

	/**
	 * Sets the behavior for the ENTER key. It also dictates other behaviour
	 * rules in the editor, like whether the &lt;br&gt; element is to be used
	 * as a paragraph separator when indenting text.
	 * The allowed values are the following constants, and their relative
	 * behavior:
	 * <ul>
	 *     <li>{@link CKEDITOR.ENTER_P} (1): new &lt;p&gt; paragraphs are created;</li>
	 *     <li>{@link CKEDITOR.ENTER_BR} (2): lines are broken with &lt;br&gt; elements;</li>
	 *     <li>{@link CKEDITOR.ENTER_DIV} (3): new &lt;div&gt; blocks are created.</li>
	 * </ul>
	 * <strong>Note</strong>: It's recommended to use the
	 * {@link CKEDITOR.ENTER_P} value because of its semantic value and
	 * correctness. The editor is optimized for this value.
	 * @type Number
	 * @default {@link CKEDITOR.ENTER_P}
	 * @example
	 * // Not recommended.
	 * config.enterMode = CKEDITOR.ENTER_BR;
	 */
	enterMode : CKEDITOR.ENTER_P,

	/**
	 * Just like the {@link CKEDITOR.config.enterMode} setting, it defines the behavior for the SHIFT+ENTER key.
	 * The allowed values are the following constants, and their relative
	 * behavior:
	 * <ul>
	 *     <li>{@link CKEDITOR.ENTER_P} (1): new &lt;p&gt; paragraphs are created;</li>
	 *     <li>{@link CKEDITOR.ENTER_BR} (2): lines are broken with &lt;br&gt; elements;</li>
	 *     <li>{@link CKEDITOR.ENTER_DIV} (3): new &lt;div&gt; blocks are created.</li>
	 * </ul>
	 * @type Number
	 * @default {@link CKEDITOR.ENTER_BR}
	 * @example
	 * config.shiftEnterMode = CKEDITOR.ENTER_P;
	 */
	shiftEnterMode : CKEDITOR.ENTER_BR,

	/**
	 * A comma separated list of plugins that are not related to editor
	 * instances. Reserved to plugins that extend the core code only.<br /><br />
	 *
	 * There are no ways to override this setting, except by editing the source
	 * code of CKEditor (_source/core/config.js).
	 * @type String
	 * @example
	 */
	corePlugins : '',

	/**
	 * Sets the doctype to be used when loading the editor content as HTML.
	 * @type String
	 * @default '&lt;!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"&gt;'
	 * @example
	 * // Set the doctype to the HTML 4 (quirks) mode.
	 * config.docType = '&lt;!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"&gt;';
	 */
	docType : '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">',

	/**
	 * Indicates whether the contents to be edited are being inputted as a full
	 * HTML page. A full page includes the &lt;html&gt;, &lt;head&gt; and
	 * &lt;body&gt; tags. The final output will also reflect this setting,
	 * including the &lt;body&gt; contents only if this setting is disabled.
	 * @type Boolean
	 * @default false
	 * @example
	 * config.fullPage = true;
	 */
	fullPage : false,

	/**
	 * The height of editing area( content ), in relative or absolute, e.g. 30px, 5em.
	 * Note: Percentage unit is not supported yet. e.g. 30%.
	 * @type Number|String
	 * @default '200'
	 * @example
	 * config.height = 500;
	 * config.height = '25em';
	 * config.height = '300px';
	 */
	height : 200,

	/**
	 * Comma separated list of plugins to load and initialize for an editor
	 * instance. This should be rarely changed, using instead the
	 * {@link CKEDITOR.config.extraPlugins} and
	 * {@link CKEDITOR.config.removePlugins} for customizations.
	 * @type String
	 * @example
	 */
	plugins : 'about,basicstyles,blockquote,button,clipboard,colorbutton,colordialog,contextmenu,elementspath,enterkey,entities,filebrowser,find,flash,font,format,forms,horizontalrule,htmldataprocessor,image,indent,justify,keystrokes,link,list,maximize,newpage,pagebreak,pastefromword,pastetext,popup,preview,print,removeformat,resize,save,scayt,smiley,showblocks,sourcearea,stylescombo,table,tabletools,specialchar,tab,templates,toolbar,undo,wysiwygarea,wsc',

	/**
	 * List of additional plugins to be loaded. This is a tool setting which
	 * makes it easier to add new plugins, whithout having to touch and
	 * possibly breaking the {@link CKEDITOR.config.plugins} setting.
	 * @type String
	 * @example
	 * config.extraPlugins = 'myplugin,anotherplugin';
	 */
	extraPlugins : '',

	/**
	 * List of plugins that must not be loaded. This is a tool setting which
	 * makes it easier to avoid loading plugins definied in the
	 * {@link CKEDITOR.config.plugins} setting, whithout having to touch it and
	 * potentially breaking it.
	 * @type String
	 * @example
	 * config.removePlugins = 'elementspath,save,font';
	 */
	removePlugins : '',

	/**
	 * List of regular expressions to be executed over the input HTML,
	 * indicating code that must stay untouched.
	 * @type Array
	 * @default [] (empty array)
	 * @example
	 * config.protectedSource.push( /<\?[\s\S]*?\?>/g );   // PHP Code
	 * config.protectedSource.push( /<%[\s\S]*?%>/g );   // ASP Code
	 * config.protectedSource.push( /(<asp:[^\>]+>[\s|\S]*?<\/asp:[^\>]+>)|(<asp:[^\>]+\/>)/gi );   // ASP.Net Code
	 */
	protectedSource : [],

	/**
	 * The editor tabindex value.
	 * @type Number
	 * @default 0 (zero)
	 * @example
	 * config.tabIndex = 1;
	 */
	tabIndex : 0,

	/**
	 * The theme to be used to build the UI.
	 * @type String
	 * @default 'default'
	 * @see CKEDITOR.config.skin
	 * @example
	 * config.theme = 'default';
	 */
	theme : 'default',

	/**
	 * The skin to load. It may be the name of the skin folder inside the
	 * editor installation path, or the name and the path separated by a comma.
	 * @type String
	 * @default 'default'
	 * @example
	 * config.skin = 'v2';
	 * @example
	 * config.skin = 'myskin,/customstuff/myskin/';
	 */
	skin : 'kama',

	/**
	 * The editor width in CSS size format or pixel integer.
	 * @type String|Number
	 * @default '' (empty)
	 * @example
	 * config.width = 850;
	 * @example
	 * config.width = '75%';
	 */
	width : '',

	/**
	 * The base Z-index for floating dialogs and popups.
	 * @type Number
	 * @default 10000
	 * @example
	 * config.baseFloatZIndex = 2000
	 */
	baseFloatZIndex : 10000

};

// PACKAGER_RENAME( CKEDITOR.config )
