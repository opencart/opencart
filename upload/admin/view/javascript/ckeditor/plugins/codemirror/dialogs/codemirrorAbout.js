/**
 * @license Copyright (c) 2003-2021, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.dialog.add('codemirrorAboutDialog',
    function(editor) {
        var lang = editor.lang.codemirror,
            imagePath = CKEDITOR.getUrl(CKEDITOR.plugins.get('codemirror').path + 'dialogs/logo.png');

        return {
            title: lang.dlgTitle,
            minWidth: 390,
            minHeight: 210,
            contents: [
                {
                    id: 'tab1',
                    label: '',
                    title: '',
                    expand: true,
                    padding: 0,
                    elements: [
                        {
                            type: 'html',
                            html: '<style type="text/css">' +
                                '.cke_about_container' +
                                '{' +
                                'color:#000 !important;' +
                                'padding:10px 10px 0;' +
                                'margin-top:5px' +
                                '}' +
                                '.cke_about_container p' +
                                '{' +
                                'margin: 0 0 10px;' +
                                '}' +
                                '.cke_about_container .cke_about_logo' +
                                '{' +
                                'height:105px;' +
                                'background-color:#fff;' +
                                'background-image:url(' +
                                imagePath +
                                ');' +
                                'background-position:center; ' +
                                'background-repeat:no-repeat;' +
                                'margin-bottom:10px;' +
                                '}' +
                                '.cke_about_container a' +
                                '{' +
                                'cursor:pointer !important;' +
                                'color:#00B2CE !important;' +
                                'text-decoration:underline !important;' +
                                '}' +
                                '.cke_about_container > p,' +
                                '.cke_rtl .cke_about_container > p' +
                                '{' +
                                'text-align:center;' +
                                '}' +
                                '</style>' +
                                '<div class="cke_about_container">' +
                                '<div class="cke_about_logo"></div>' +
                                '<p>' +
                                (typeof (CodeMirror) == "undefined" ? "" : 'CodeMirror ' + CodeMirror.version) +
                                ' (CKEditor Plugin Version ' +
                                editor.plugins.codemirror.version +
                                ')<br>' +
                                '<a target="_blank" rel="noopener noreferrer" href="https://codemirror.net">https://codemirror.net</a> - ' +
                                '<a target="_blank" rel="noopener noreferrer" href="https://github.com/w8tcha/CKEditor-CodeMirror-Plugin">https://github.com/w8tcha/CKEditor-CodeMirror-Plugin</a>' +
                                '</p>' +
                                '<h5>' +
                                lang.moreInfoShortcuts +
                                '</h5>' +
                                '<p><ul>' +
                                '<li>' +
                                lang.moreInfoShortcuts1 +
                                '</li>' +
                                '<li>' +
                                lang.moreInfoShortcuts2 +
                                '</li>' +
                                '<li>' +
                                lang.moreInfoShortcuts3 +
                                '</li>' +
                                '<li>' +
                                lang.moreInfoShortcuts4 +
                                '</li>' +
                                '<li>' +
                                lang.moreInfoShortcuts5 +
                                '</li>' +
                                '<li>' +
                                lang.moreInfoShortcuts6 +
                                '</li>' +
                                '<li>' +
                                lang.moreInfoShortcuts7 +
                                '</li>' +
                                '<li>' +
                                lang.moreInfoShortcuts8 +
                                '</li>' +
                                '<li>' +
                                lang.moreInfoShortcuts9 +
                                '</li>' +
                                '</ul></p>' +
                                '<p>' +
                                lang.copyright +
                                '</p>' +
                                '</div>'
                        }
                    ]
                }
            ],
            buttons: [CKEDITOR.dialog.cancelButton]
        };
    });
