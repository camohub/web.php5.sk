$( function()
{
	$.nette.init();


	// Menu - open/close section handler /////////////////////////////////////////////////

	var no_current = true,
		side_menu = $('#sideMenu');

	side_menu.find( 'li' ).each( function()
	{
		if ( $( this ).attr( 'id' ) == category_id )  // category_id comes from menu.latte
		{
			$( this ).addClass( 'current_li' ).children( 'a' ).addClass( 'current' );
			$( this ).parents( 'li' ).addClass( 'current_li' );

			no_current = false;
		}
	} );

	//if( no_current ) side_menu.find( 'ul' ).css( 'display', 'block' );

	// End of menu handler ///////////////////////////////////////////////////////////////


	$( document ).on( 'click', '.x', function()
	{
		$( this ).closest( 'div' ).css( 'display', 'none' );
	} );


	prettyPrint();

} );
