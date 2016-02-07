$( function()
{
	$.nette.init();


	var no_current = true,
		side_menu = $('#sideMenu' ),
		location_path = window.location.pathname;

	side_menu.find( 'a' ).each( function()
	{
		if ( $( this ).attr( 'href' ) == location_path )
		{
			$( this ).addClass( 'current' ).parents( 'li' ).addClass( 'current_li' );

			no_current = false;
		}
	} );

	if( no_current ) side_menu.find( 'ul' ).css( 'display', 'block' );


	$( document ).on( 'click', '.x', function()
	{
		$( this ).closest( 'div' ).css( 'display', 'none' );
	} );


	prettyPrint();

} );
