CKEditor-CodeMirror-Plugin
==========================

Syntax Highlighting for the CKEditor (Source View and Source Dialog) with the CodeMirror Plugin

### Available Shortcuts
* 'CTRL + K' to comment the currently selected text
* 'CTRL + SHIFT + K' to uncomment currently selected text
* 'CTRL + ALT + K' to auto format currently selected text
* 'CTRL + Q' Expand/Collapse Code Block
* 'CTRL + F' to perform a search
* 'CTRL + G' to find next
* 'CTRL + SHIFT + G' to find previous
* 'CTRL + SHIFT + F' to find and replace
* 'CTRL + SHIFT + R' to find and replace all

### Demo
http://w8tcha.github.io/CKEditor-CodeMirror-Plugin/

The Full Theme List can be found here: http://codemirror.net/demo/theme.html

![Screenshot](http://www.watchersnet.de/Portals/0/screenshots/dnn/CKEditorSourceView.png)

![Screenshot](http://www.watchersnet.de/Portals/0/screenshots/dnn/SourceDialog.png)

#### License

Licensed under the terms of the MIT License.

#### Installation

 1. Extract the contents of the file into the "plugins" folder of CKEditor.
 2. In the CKEditor configuration file (config.js) add the following code:

````js
config.extraPlugins = 'codemirror';
````

If you are using CKEditor in inline mode you also need to add the sourcedialog to the extra Plugins list 

````js
config.extraPlugins = 'sourcedialog,codemirror';
````

3. To Configure the Plugin the following options are available...

````js
config.codemirror = {
	
	// Whether or not you want Brackets to automatically close themselves
	autoCloseBrackets: true,

     // Whether or not you want tags to automatically close themselves
	autoCloseTags: true,

     // Whether or not to automatically format code should be done when the editor is loaded
	autoFormatOnStart: true, 
	
	// Whether or not to automatically format code which has just been uncommented
	autoFormatOnUncomment: true,
	
	// Whether or not to continue a comment when you press Enter inside a comment block
	continueComments: true,

     // Whether or not you wish to enable code folding (requires 'lineNumbers' to be set to 'true')
	enableCodeFolding: true,
	
	// Whether or not to enable code formatting
	enableCodeFormatting: true,
	
	// Whether or not to enable search tools, CTRL+F (Find), CTRL+SHIFT+F (Replace), CTRL+SHIFT+R (Replace All), CTRL+G (Find Next), CTRL+SHIFT+G (Find Previous)
	enableSearchTools: true,
	
	// Whether or not to highlight all matches of current word/selection
	highlightMatches: true,

     // Whether, when indenting, the first N*tabSize spaces should be replaced by N tabs
	indentWithTabs: false,

     // Whether or not you want to show line numbers
	lineNumbers: true,
	
	// Whether or not you want to use line wrapping
	lineWrapping: true,

     // Define the language specific mode 'htmlmixed' for html  including (css, xml, javascript), 'application/x-httpd-php' for php mode including html, or 'text/javascript' for using java script only 
	mode: 'htmlmixed',
	
	// Whether or not you want to highlight matching braces
	matchBrackets: true,
	
	// Whether or not you want to highlight matching tags
	matchTags: true,

	// Whether or not to show the showAutoCompleteButton   button on the toolbar
	showAutoCompleteButton: true,
     
     // Whether or not to show the comment button on the toolbar
	showCommentButton: true,

	// Whether or not to show the format button on the toolbar
	showFormatButton: true,

     // Whether or not to show the search Code button on the toolbar
	showSearchButton: true,

     // Whether or not to show Trailing Spaces
	showTrailingSpace: true,
	
	// Whether or not to show the uncomment button on the toolbar
	showUncommentButton: true,

     // Whether or not to highlight the currently active line
	styleActiveLine: true,

     // Set this to the theme you wish to use (codemirror themes)
	theme: 'default',
	
	// "Whether or not to use Beautify for auto formatting On start
	useBeautifyOnStart: false
};

````
