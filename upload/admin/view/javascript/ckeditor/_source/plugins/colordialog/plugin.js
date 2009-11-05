( function()
{
	CKEDITOR.plugins.colordialog =
	{
		init : function( editor )
		{
			editor.addCommand( 'colordialog', new CKEDITOR.dialogCommand( 'colordialog' ) );
			CKEDITOR.dialog.add( 'colordialog', this.path + 'dialogs/colordialog.js' );
		}
	};

	CKEDITOR.plugins.add( 'colordialog', CKEDITOR.plugins.colordialog );
} )();
