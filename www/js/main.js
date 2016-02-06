$( function()
{
	$.nette.init();

	$( document ).on( 'click', '.x', function()
	{
		$( this ).closest( 'div' ).css( 'display', 'none' );
	} );


	$( '#sideMenu' ).find( 'a' ).each( function()
	{
		if ( $( this ).attr( 'href' ) == window.location.pathname )
		{
			$( this ).addClass( 'current' )
				.parents( 'li' ).addClass( 'current_li' );
		}
	} );


	LiveForm.setOptions( {
		messageTag: 'div',
		controlErrorClass: 'b6',
		messageErrorClass: 'error',
		messageErrorPrefix: '<i class="glyphicon glyphicon-exclamation-sign"></i>&nbsp;',  // Default adjusts &nbsp; before
	} );

	prettyPrint();

} );
