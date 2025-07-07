jQuery( document ).ready( function( $ )
{
	$( 'form.checkout' ).on( 'change', 'input.tipping-input', function()
	{
		if ( $( 'input.tipping-input:checked' ).val() === 'custom' )
		{
			$( '#custom_tip_input_wrapper' ).show();
		}
		else
		{
			$( '#custom_tip_input_wrapper' ).hide();
		}

		$( 'body' ).trigger( 'update_checkout' );
	} );
} );