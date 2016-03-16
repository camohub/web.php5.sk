$( function()
{

	$.nette.init();


	// Live-form-validation section /////////////////////////////////////////////////

	// Necessary to switch off onchange/onblur validation and switch on submit validation.
	LiveForm.setupHandlers = function( el )
	{
		if ( this.hasClass( el, this.options.disableLiveValidationClass ) )
			return;

		// Check if element was already initialized
		if ( el.getAttribute( "data-lfv-initialized" ) )
			return;

		// Remember we initialized this element so we won't do it again
		el.setAttribute( 'data-lfv-initialized', 'true' );

		var handler = function( event )
		{
			event = event || window.event;
			Nette.validateControl( event.target ? event.target : event.srcElement );
		};

		var self = this;

		Nette.addEvent( el, "submit", handler );
		//Nette.addEvent(el, "change", handler);
		//Nette.addEvent(el, "blur", handler);
		Nette.addEvent( el, "keydown", function( event )
		{
			if ( ! self.isSpecialKey( event.which ) && (self.options.wait === false || self.options.wait >= 200) )
			{
				// Hide validation span tag.
				self.removeClass( self.getGroupElement( this ), self.options.controlErrorClass );
				self.removeClass( self.getGroupElement( this ), self.options.controlValidClass );

				var messageEl = self.getMessageElement( this );
				messageEl.innerHTML = '';
				messageEl.className = '';

				// Cancel timeout to run validation handler
				if ( self.timeout )
				{
					clearTimeout( self.timeout );
				}
			}
		} );
		Nette.addEvent( el, "keyup", function( event )
		{
			if ( self.options.wait !== false )
			{
				event = event || window.event;
				if ( event.keyCode !== 9 )
				{
					if ( self.timeout ) clearTimeout( self.timeout );
					self.timeout = setTimeout( function()
					{
						handler( event );
					}, self.options.wait );
				}
			}
		} );
	};

	// End of live-form-validation section ///////////////////////////////////////////////

	// Menu - open/close section handler /////////////////////////////////////////////////

	var no_current = true,
		side_menu = $( '#sideMenu' );

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


	// Calling undefined function like in layoutSlim.latte which does not load prettyPrint causes an error.
	if ( typeof prettyPrint !== 'undefined' )
	{
		prettyPrint();
	}

} );




