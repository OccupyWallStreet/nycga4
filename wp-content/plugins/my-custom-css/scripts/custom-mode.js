( function( global, $ ) {
	var editor,
		syncCSS = function() {
			$( '#my_custom_css_textarea' ).val( editor.getSession().getValue() );
		},
		loadAce = function() {
			editor = ace.edit( 'my_custom_css' );
			global.safecss_editor = editor;
			editor.getSession().setUseWrapMode( true );
			editor.setShowPrintMargin( false );
			editor.getSession().setValue( $( '#my_custom_css_textarea' ).val() );
			editor.getSession().setMode( 'ace/mode/css' );
			editor.setOptions({maxLines: Infinity});
			editor.setOptions({minLines: 10});
			jQuery.fn.spin&&$( '#my_custom_css_container' ).spin( false );
			$( '#my-custom-css' ).submit( syncCSS );
		};
	if ( $.browser.msie&&parseInt( $.browser.version, 10 ) <= 7 ) {
		$( '#my_custom_css_container' ).hide();
		$( '#my_custom_css_textarea' ).show();
		return false;
	} else {
		$( global ).load( loadAce );
	}
	global.aceSyncCSS = syncCSS;
} )( this, jQuery );