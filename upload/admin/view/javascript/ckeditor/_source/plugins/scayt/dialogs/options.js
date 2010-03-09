/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.dialog.add( 'scaytcheck', function( editor )
{
	var firstLoad = true,
		captions,
		doc = CKEDITOR.document,
		tags = [],
		i,
		contents = [],
		userDicActive = false;
	var dic_buttons = [
		// [0] contains buttons for creating
		"dic_create,dic_restore",
		// [1] contains buton for manipulation
		"dic_rename,dic_delete"
	];
	var tags_contents =  [
				{
					id : 'options',
					label : editor.lang.scayt.optionsTab,
					elements : [
						{
							type : 'html',
							id : 'options',
							html : 	'<div class="inner_options">' +
									'	<div class="messagebox"></div>' +
									'	<div style="display:none;">' +
									'		<input type="checkbox" value="0" id="allCaps" />' +
									'		<label for="allCaps" id="label_allCaps"></label>' +
									'	</div>' +
									'	<div style="display:none;">' +
									'		<input type="checkbox" value="0" id="ignoreDomainNames" />' +
									'		<label for="ignoreDomainNames" id="label_ignoreDomainNames"></label>' +
									'	</div>' +
									'	<div style="display:none;">' +
									'	<input type="checkbox" value="0" id="mixedCase" />' +
									'		<label for="mixedCase" id="label_mixedCase"></label>' +
									'	</div>' +
									'	<div style="display:none;">' +
									'		<input type="checkbox" value="0" id="mixedWithDigits" />' +
									'		<label for="mixedWithDigits" id="label_mixedWithDigits"></label>' +
									'	</div>' +
									'</div>'
						}
					]
				},
				{
					id : 'langs',
					label : editor.lang.scayt.languagesTab,
					elements : [
						{
							type : 'html',
							id : 'langs',
							html : 	'<div class="inner_langs">' +
									'	<div class="messagebox"></div>	' +
									'   <div style="float:left;width:47%;margin-left:5px;" id="scayt_lcol" ></div>' +
									'   <div style="float:left;width:47%;margin-left:15px;" id="scayt_rcol"></div>' +
									'</div>'
						}
					]
				},
				{
					id : 'dictionaries',
					label : editor.lang.scayt.dictionariesTab,
					elements : [
						{
							type : 'html',
							style: '',
							id : 'dic',
							html : 	'<div class="inner_dictionary" style="text-align:left; white-space:normal;">' +
									'	<div style="margin:5px auto; width:80%;white-space:normal; overflow:hidden;" id="dic_message"> </div>' +
									'	<div style="margin:5px auto; width:80%;white-space:normal;"> ' +
									'       <span class="cke_dialog_ui_labeled_label" >Dictionary name</span><br>'+
									'		<span class="cke_dialog_ui_labeled_content" >'+
									'			<div class="cke_dialog_ui_input_text">'+
									'				<input id="dic_name" type="text" class="cke_dialog_ui_input_text"/>'+
									'		</div></span></div>'+
									'		<div style="margin:5px auto; width:80%;white-space:normal;">'+
									'			<a style="display:none;" class="cke_dialog_ui_button" href="javascript:void(0)" id="dic_create">'+
									'				</a>' +
									'			<a  style="display:none;" class="cke_dialog_ui_button" href="javascript:void(0)" id="dic_delete">'+
									'				</a>' +
									'			<a  style="display:none;" class="cke_dialog_ui_button" href="javascript:void(0)" id="dic_rename">'+
									'				</a>' +
									'			<a  style="display:none;" class="cke_dialog_ui_button" href="javascript:void(0)" id="dic_restore">'+
									'				</a>' +
									'		</div>' +
									'	<div style="margin:5px auto; width:95%;white-space:normal;" id="dic_info"></div>' +
									'</div>'
						}
					]
				},
				{
					id : 'about',
					label : editor.lang.scayt.aboutTab,
					elements : [
						{
							type : 'html',
							id : 'about',
							style : 'margin: 10px 40px;',
							html : '<div id="scayt_about"></div>'
						}
					]
				}
			];
	var dialogDefiniton = {
		title : editor.lang.scayt.title,
		minWidth : 340,
		minHeight : 200,
		onShow : function()
		{
			var dialog = this;
			dialog.data = editor.fire( 'scaytDialog', {} );
			dialog.options = dialog.data.scayt_control.option();
			dialog.sLang = dialog.data.scayt_control.sLang;

			if ( !dialog.data || !dialog.data.scayt || !dialog.data.scayt_control )
			{
				alert( 'Error loading application service' );
				dialog.hide();
				return;
			}

			var stop = 0;
			if ( firstLoad )
			{
				dialog.data.scayt.getCaption( 'en', function( caps )
					{
						if ( stop++ > 0 )	// Once only
							return;
						captions = caps;
						init_with_captions.apply( dialog );
						reload.apply( dialog );
						firstLoad = false;
					});
			}
			else
				reload.apply( dialog );

			dialog.selectPage( dialog.data.tab );
		},
		onOk : function()
		{
			var scayt_control =  this.data.scayt_control,
				o = scayt_control.option(),
				c = 0;

			// Set up options if any was set.
			for ( var i in this.options )
			{
				if (o[i] != this.options[ i ] && c === 0 )
				{
					scayt_control.option( this.options );
					c++;
				}
			}

			// Setup languge if it was changed.
			var csLang = this.chosed_lang;
			if ( csLang && this.data.sLang != csLang )
			{
				scayt_control.setLang( csLang );
				c++;
			}
			if ( c > 0 )
				scayt_control.refresh();
		},
		contents : contents
        };

	var scayt_control = CKEDITOR.plugins.scayt.getScayt( editor );
	if ( scayt_control )
	{
		tags = scayt_control.uiTags;
	}

	for ( i in tags ) {
		if ( tags[ i ] == 1 )
			contents[ contents.length ] = tags_contents[ i ];
	}
	if ( tags[2] == 1 )
		userDicActive = true;

	function onDicButtonClick()
	{
		var dic_name = doc.getById('dic_name').getValue();
		if ( !dic_name )
		{
			dic_error_message(" Dictionary name should not be empty. ");
			return false;
		}
		//apply handler
		window.dic[ this.getId() ].apply( null, [ this, dic_name, dic_buttons ] );

		return true;
	}
	var init_with_captions = function()
	{
		var dialog = this,
			lang_list = dialog.data.scayt.getLangList(),
			buttons = [ 'dic_create','dic_delete','dic_rename','dic_restore' ],
			labels = [ 'mixedCase','mixedWithDigits','allCaps','ignoreDomainNames' ],
			i;

		// Add buttons titles
		if (userDicActive)
		{
			for ( i in buttons )
			{
				var button = buttons[ i ];
				doc.getById( button ).setHtml( '<span class="cke_dialog_ui_button">' + captions[ 'button_' + button]  +'</span>' );
			}
			doc.getById( 'dic_info' ).setHtml( captions[ 'dic_info' ] );
		}


		// Fill options and dictionary labels.
		for ( i in labels )
		{
			var label = 'label_' + labels[ i ],
				labelElement = doc.getById( label );

			if (  'undefined' != typeof labelElement
			   && 'undefined' != typeof captions[ label ]
			   && 'undefined' != typeof dialog.options[labels[ i ]] )
			{
				labelElement.setHtml( captions[ label ] );
				var labelParent = labelElement.getParent();
				labelParent.$.style.display = "block";
			}
		}

		var about = '<p>' + captions[ 'about_throwt_image' ] + '</p>'+
					'<p>' + captions[ 'version' ]  + dialog.data.scayt.version.toString() + '</p>' +
					'<p>' + captions[ 'about_throwt_copy' ] + '</p>';

		doc.getById( 'scayt_about' ).setHtml( about );

		// Create languages tab.
		var createOption = function( option, list )
		{
			var label = doc.createElement( 'label' );
			label.setAttribute( 'for', 'cke_option' + option );
			label.setHtml( list[ option ] );

			if ( dialog.sLang == option )	// Current.
				dialog.chosed_lang = option;

			var div = doc.createElement( 'div' );
			var radio = CKEDITOR.dom.element.createFromHtml( '<input id="cke_option' +
					option + '" type="radio" ' +
					( dialog.sLang == option ? 'checked="checked"' : '' ) +
					' value="' + option + '" name="scayt_lang" />' );

			radio.on( 'click', function()
				{
					this.$.checked = true;
					dialog.chosed_lang = option;
				});

			div.append( radio );
			div.append( label );

			return {
				lang : list[ option ],
				code : option,
				radio : div
			};
		};

		var langList = [];
		for ( i in lang_list.rtl )
			langList[ langList.length ] = createOption( i, lang_list.ltr );

		for ( i in lang_list.ltr )
			langList[ langList.length  ] = createOption( i, lang_list.ltr );

		langList.sort( 	function( lang1, lang2 )
			{
				return ( lang2.lang > lang1.lang ) ? -1 : 1 ;
			});

		var fieldL = doc.getById( 'scayt_lcol' ),
			fieldR = doc.getById( 'scayt_rcol' );
		for ( i=0; i < langList.length; i++ )
		{
			var field = ( i < langList.length / 2 ) ? fieldL : fieldR;
			field.append( langList[ i ].radio );
		}

		// user dictionary handlers
		var dic = {};
		dic.dic_create = function( el, dic_name , dic_buttons )
			{
				// comma separated button's ids include repeats if exists
				var all_buttons = dic_buttons[0] + ',' + dic_buttons[1];

				var err_massage = captions["err_dic_create"];
				var suc_massage = captions["succ_dic_create"];
				//console.info("--plugin ");

				window.scayt.createUserDictionary(dic_name,
					function(arg)
						{
							//console.info( "dic_create callback called with args" , arg );
							hide_dic_buttons ( all_buttons );
							display_dic_buttons ( dic_buttons[1] );
							suc_massage = suc_massage.replace("%s" , arg.dname );
							dic_success_message (suc_massage);
						},
					function(arg)
						{
							//console.info( "dic_create errorback called with args" , arg )
							err_massage = err_massage.replace("%s" ,arg.dname );
							dic_error_message ( err_massage + "( "+ (arg.message || "") +")");
						});

			};

		dic.dic_rename = function( el, dic_name )
			{
				//
				// try to rename dictionary
				// @TODO: rename dict
				//console.info ( captions["err_dic_rename"] )
				var err_massage = captions["err_dic_rename"] || "";
				var suc_massage = captions["succ_dic_rename"] || "";
				window.scayt.renameUserDictionary(dic_name,
					function(arg)
						{
							//console.info( "dic_rename callback called with args" , arg );
							suc_massage = suc_massage.replace("%s" , arg.dname );
							set_dic_name( dic_name );
							dic_success_message ( suc_massage );
						},
					function(arg)
						{
							//console.info( "dic_rename errorback called with args" , arg )
							err_massage = err_massage.replace("%s" , arg.dname  );
							set_dic_name( dic_name );
							dic_error_message( err_massage + "( " + ( arg.message || "" ) + " )" );
						});
			};

		dic.dic_delete = function ( el, dic_name , dic_buttons )
			{
				var all_buttons = dic_buttons[0] + ',' + dic_buttons[1];
				var err_massage = captions["err_dic_delete"];
				var suc_massage = captions["succ_dic_delete"];

				// try to delete dictionary
				// @TODO: delete dict
				window.scayt.deleteUserDictionary(
					function(arg)
						{
							//console.info( "dic_delete callback " , dic_name ,arg );
							suc_massage = suc_massage.replace("%s" , arg.dname );
							hide_dic_buttons ( all_buttons );
							display_dic_buttons ( dic_buttons[0] );
							set_dic_name( "" ); // empty input field
							dic_success_message( suc_massage );
						},
					function(arg)
						{
							//console.info( " dic_delete errorback called with args" , arg )
							err_massage = err_massage.replace("%s" , arg.dname );
							dic_error_message(err_massage);
						});
			};

		dic.dic_restore = dialog.dic_restore || function ( el, dic_name , dic_buttons )
			{
				// try to restore existing dictionary
				var all_buttons = dic_buttons[0] + ',' + dic_buttons[1];
				var err_massage = captions["err_dic_restore"];
				var suc_massage = captions["succ_dic_restore"];

				window.scayt.restoreUserDictionary(dic_name,
					function(arg)
						{
							//console.info( "dic_restore callback called with args" , arg );
							suc_massage = suc_massage.replace("%s" , arg.dname );
							hide_dic_buttons ( all_buttons );
							display_dic_buttons(dic_buttons[1]);
							dic_success_message( suc_massage );
						},
					function(arg)
						{
							//console.info( " dic_restore errorback called with args" , arg )
							err_massage = err_massage.replace("%s" , arg.dname );
							dic_error_message( err_massage );
						});
			};

		// ** bind event listeners
		var arr_buttons = ( dic_buttons[0] + ',' + dic_buttons[1] ).split( ',' ),
			l;

		for ( i = 0, l = arr_buttons.length ; i < l ; i += 1 )
		{
		 	var dic_button = doc.getById(arr_buttons[i]);
			if ( dic_button )
				dic_button.on( 'click', onDicButtonClick, this );
		}
	};

	var reload = function()
	{
		var dialog = this;

		// Animate options.
		for ( var i in dialog.options )
		{
			var checkbox = doc.getById( i );
			if ( checkbox )
			{
				checkbox.removeAttribute( 'checked' );
				if ( dialog.options[ i ] == 1 )
					checkbox.setAttribute( 'checked', 'checked' );

				// Bind events. Do it only once.
				if ( firstLoad )
				{
					checkbox.on( 'click', function()
						{
							dialog.options[ this.getId() ] = this.$.checked ? 1 : 0 ;
						} );
				}
			}
		}

		// * user dictionary
		if ( userDicActive ){
			window.scayt.getNameUserDictionary(
			function( o )
			{
				var dic_name = o.dname;
				if ( dic_name )
				{
					doc.getById( 'dic_name' ).setValue(dic_name);
					display_dic_buttons( dic_buttons[1] );
				}
				else
					display_dic_buttons( dic_buttons[0] );

			},
			function ()
			{
				doc.getById( 'dic_name' ).setValue("");
			});
			dic_success_message("");
		}

	};

	function dic_error_message ( m )
		{
			doc.getById('dic_message').setHtml('<span style="color:red;">' + m + '</span>' );
		}
    function dic_success_message ( m )
		{
			doc.getById('dic_message').setHtml('<span style="color:blue;">' + m + '</span>') ;
		}
	function display_dic_buttons ( sIds )
		{

			sIds = String( sIds );
			var aIds = sIds.split(',');
			for ( var i=0, l = aIds.length; i < l ; i+=1)
			{
				doc.getById( aIds[i] ).$.style.display = "inline";
			}

		}
	function hide_dic_buttons ( sIds )
		{
			sIds = String( sIds );
			var aIds = sIds.split(',');
			for ( var i = 0, l = aIds.length; i < l ; i += 1 )
			{
				doc.getById( aIds[i] ).$.style.display = "none";
			}
		}
	function set_dic_name ( dic_name )
		{
			doc.getById('dic_name').$.value= dic_name;
		}

	return dialogDefiniton;
});
