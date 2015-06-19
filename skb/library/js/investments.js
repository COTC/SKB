jQuery(document).ready( function (){

	jQuery('#add_to_watch_list').on('click', function(){
		jQuery.post(
		    Investment.ajaxurl, {
		        action : 'add_to_watch_list',
		        investment_id : Investment.id,
		        watchlist_nonce : Investment.watchlist_nonce,
		    }, function(response) {
		        jQuery("#ajax_watchlist_response").html(response);
		        jQuery('#alert-watchlist').removeClass('hide');
		        jQuery('#add_to_watch_list').addClass('disabled');
		    }
		);
	});

	jQuery('#remove_from_watch_list').on('click', function(){
		jQuery.post(
		    Investment.ajaxurl, {
		        action : 'remove_from_watch_list',
		        investment_id : Investment.id,
		        watchlist_nonce : Investment.watchlist_nonce,
		    }, function(response) {
		        jQuery("#ajax_watchlist_response").html(response);
		        jQuery('#alert-watchlist').removeClass('hide');
		        jQuery('#remove_from_watch_list').addClass('disabled');
		    }
		);
	});

	jQuery('#read_the_offering').on('click', function(){
		jQuery.post(
		    Investment.ajaxurl, {
		        action : 'mark_offering_read',
		        investment_id : Investment.id,
		        readlist_nonce : Investment.readlist_nonce,
		    }, function(response) {
				jQuery('#invest_in_this_deal').removeClass('disabled');
		    }
		);

		window.open(Investment.offering_pdf, "Investment Offering");

	});

});