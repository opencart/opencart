/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.plugins.add('smiley',{requires:['dialog'],init:function(a){a.addCommand('smiley',new CKEDITOR.dialogCommand('smiley'));a.ui.addButton('Smiley',{label:a.lang.smiley.toolbar,command:'smiley'});CKEDITOR.dialog.add('smiley',this.path+'dialogs/smiley.js');}});CKEDITOR.config.smiley_path=CKEDITOR.basePath+'plugins/smiley/images/';CKEDITOR.config.smiley_images=['regular_smile.gif','sad_smile.gif','wink_smile.gif','teeth_smile.gif','confused_smile.gif','tounge_smile.gif','embaressed_smile.gif','omg_smile.gif','whatchutalkingabout_smile.gif','angry_smile.gif','angel_smile.gif','shades_smile.gif','devil_smile.gif','cry_smile.gif','lightbulb.gif','thumbs_down.gif','thumbs_up.gif','heart.gif','broken_heart.gif','kiss.gif','envelope.gif'];CKEDITOR.config.smiley_descriptions=[':)',':(',';)',':D',':/',':P','','','','','','','',';(','','','','','',':kiss',''];
