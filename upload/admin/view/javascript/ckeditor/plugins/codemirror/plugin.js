/*
*  The "codemirror" plugin. It's indented to enhance the
*  "sourcearea" editing mode, which displays the xhtml source code with
*  syntax highlight and line numbers.
* Licensed under the MIT license
* jQuery Embed Plugin Embeds: http://code.google.com/p/jquery-oembed/ (MIT License)
* Plugin for: http://ckeditor.com/license (GPL/LGPL/MPL: http://ckeditor.com/license)
*/

(function () {
    CKEDITOR.plugins.add('codemirror', {
        lang: 'af,ar,bg,bn,bs,ca,cs,cy,da,de,el,en-au,en-ca,en-gb,en,eo,es,et,eu,fa,fi,fo,fr-ca,fr,gl,gu,he,hi,hr,hu,is,it,ja,ka,km,ko,ku,lt,lv,mk,mn,ms,nb,nl,no,pl,pt-br,pt,ro,ru,sk,sl,sr-latn,sr,sv,th,tr,ug,uk,vi,zh-cn,zh',
		init: function (editor) {

            var rootPath = this.path;

            // Get Config
            var config = editor.config;

            var codeMirrorTheme = config.codemirror_theme != null ? config.codemirror_theme : 'default';

            CKEDITOR.document.appendStyleSheet(rootPath + 'css/codemirror.css');

            if (codeMirrorTheme.length && codeMirrorTheme != 'default') {
                // Load codemirror theme
                CKEDITOR.document.appendStyleSheet(rootPath + 'theme/' + codeMirrorTheme + '.css');
            }

            CKEDITOR.scriptLoader.load(rootPath + 'js/codemirror.js', function (success) {
                CKEDITOR.scriptLoader.load([rootPath + 'js/xml.js', rootPath + 'js/javascript.js', rootPath + 'js/css.js', rootPath + 'js/htmlmixed.js']);
            });

            // Source mode isn't available in inline mode yet.
            if (editor.elementMode == CKEDITOR.ELEMENT_MODE_INLINE) return;

            var sourcearea = CKEDITOR.plugins.sourcearea;
			
			editor.addMode('source', function (callback) {
                var contentsSpace = editor.ui.space('contents'),
                    textarea = contentsSpace.getDocument().createElement('textarea');
				
				textarea.setStyles(
                CKEDITOR.tools.extend({
                    // IE7 has overflow the <textarea> from wrapping table cell.
                    width: CKEDITOR.env.ie7Compat ? '99%' : '100%',
                    height: '100%',
                    resize: 'none',
                    outline: 'none',
                    'text-align': 'left'
                },
                CKEDITOR.tools.cssVendorPrefix('tab-size', editor.config.sourceAreaTabSize || 4)));

                var ariaLabel = [editor.lang.editor, editor.name].join(',');

                textarea.setAttributes({
                    dir: 'ltr',
                    tabIndex: CKEDITOR.env.webkit ? -1 : editor.tabIndex,
                    'role': 'textbox',
                    'aria-label': ariaLabel
                });

                textarea.addClass('cke_source cke_reset cke_enable_context_menu');

                editor.ui.space('contents').append(textarea);

                window["editable_" +  editor.id] = editor.editable(new sourceEditable(editor, textarea));
				
                // Fill the textarea with the current editor data.
                window["editable_" +  editor.id].setData(editor.getData(1));
				
				window["editable_" +  editor.id].editorID = editor.id;
				
				editor.fire('ariaWidget', this);

                var delay;

                var sourceAreaElement = window["editable_" +  editor.id],
                    holderElement = sourceAreaElement.getParent();

                var holderHeight = holderElement.$.clientHeight + 'px';
                var holderWidth = holderElement.$.clientWidth + 'px';
				
                codemirror = editor.id;
 
				window["codemirror_" +  editor.id] = CodeMirror.fromTextArea(sourceAreaElement.$, {
                    mode: 'text/html',
                    matchBrackets: true,
                    workDelay: 300,
                    workTime: 35,
                    lineNumbers: true,
                    lineWrapping: true,
					theme: codeMirrorTheme,
                });
				
				window["codemirror_" +  editor.id].on("change", function(cm, change) {
					clearTimeout(delay);
                        delay = setTimeout(function () {
                            window["codemirror_" +  editor.id].save();
                        }, 300);
				});

                window["codemirror_" +  editor.id].setSize(holderWidth, holderHeight);

                callback();
            });

            editor.addCommand('source', sourcearea.commands.source);

            if (editor.ui.addButton) {
                editor.ui.addButton('Source', {
                    label: editor.lang.codemirror.toolbar,
                    command: 'source',
                    toolbar: 'mode,10'
                });
            }

            editor.on('mode', function () {
                editor.getCommand('source').setState(editor.mode == 'source' ? CKEDITOR.TRISTATE_ON : CKEDITOR.TRISTATE_OFF);
            });

            editor.on('resize', function () {
                if (window["editable_" +  editor.id]) {
					var holderElement = window["editable_" +  editor.id].getParent();

                	var holderHeight = holderElement.$.clientHeight + 'px';
                	var holderWidth = holderElement.$.clientWidth + 'px';
					
					window["codemirror_" +  editor.id].setSize(holderWidth, holderHeight);
				}
            });
        }
    });

    var sourceEditable = CKEDITOR.tools.createClass({
        base: CKEDITOR.editable,
        proto: {
            setData: function (data) {
                this.setValue(data);
                this.editor.fire('dataReady');
            },
			
			getData: function () {
                return this.getValue();
            },
            // Insertions are not supported in source editable.
            insertHtml: function () {},
            insertElement: function () {},
            insertText: function () {},

            // Read-only support for textarea.
            setReadOnly: function (isReadOnly) {
                this[(isReadOnly ? 'set' : 'remove') + 'Attribute']('readOnly', 'readonly');
            },
			editorID : null,
			

            detach: function () {
				
				window["codemirror_" +  this.editorID].toTextArea();
				
                sourceEditable.baseProto.detach.call(this);
                this.clearCustomData();
                this.remove();

            }
        }
    });
})();

CKEDITOR.plugins.sourcearea = {
    commands: {
        source: {
            modes: {
                wysiwyg: 1,
                source: 1
            },
            editorFocus: false,
            readOnly: 1,
            exec: function (editor) {
                if (editor.mode == 'wysiwyg') editor.fire('saveSnapshot');
                editor.getCommand('source').setState(CKEDITOR.TRISTATE_DISABLED);
				
                editor.setMode(editor.mode == 'source' ? 'wysiwyg' : 'source');
            },

            canUndo: false
        }
    }
};