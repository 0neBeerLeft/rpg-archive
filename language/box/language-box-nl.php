<?php
	//ENGLISH BOX
	
	//Language things
	$lang['taalshort']    		= 'nl';
	$lang['taalgeneral']  		= 'Nederland';
	$lang['taal']         		= 'Nederland';
	
	############################## SELL BOX #####################################
	if($page == 'sell-box'){
		//Alerts
		$txt['alert_too_low_rank'] = 'Rank is te laag.';
		$txt['alert_not_your_pokemon'] = 'Dit is niet jouw Pokemon.';
		$txt['alert_beginpokemon'] = 'Dit is je Starter Pokemon, je kan hem niet verkopen.';
		$txt['alert_no_amount'] = 'Geen bedrag opgegeven.';
		$txt['alert_price_too_less'] = 'Je gekozen bedrag is te laag het minimum is <img src="images/icons/silver.png" title="Silver" />';
		$txt['alert_price_too_much'] = 'Je gekozen bedrag is te hoog het maximum is <img src="images/icons/silver.png" title="Silver" />';
		$txt['alert_price_too_less_gold'] = 'Je gekozen bedrag is te laag het minimum is <img src="images/icons/gold.png" title="Gold" />';
		$txt['alert_price_too_much_gold'] = 'Je gekozen bedrag is te hoog het maximum is <img src="images/icons/gold.png" title="Gold" />';
		$txt['alert_pokemon_already_for_sale'] = 'Deze Pokemon staat al op de transferlijst.';
		$txt['alert_user_dont_exist'] = 'Gebruiker bestaat niet';
		$txt['alert_success_sell'] = 'Je Pokemon is succesvol op de transferlist geplaatst.';
		$txt['alert_too_much_on_transfer_1'] = 'Je hebt al';
		$txt['alert_too_much_on_transfer_2'] = 'een Pokemon op de transferlist geplaatst.';
		
		//Screen
		$txt['pagetitle'] = 'Verkopen';
		$txt['sell_box_title_text_1'] = 'Weet je zeker dat je jouw';
		$txt['sell_box_title_text_2'] = 'te koop wil zetten?';
		$txt['sell_box_title_text_3'] = 'is momenteel';
		$txt['sell_box_title_text_4'] = 'silver waard.<br />';
		$txt['sell_box_title_text_5'] = 'on sale for?';
		$txt['keep_empty'] = '(mag leegelaten worden).';
		
		$txt['sell_rules'] = '* Je kan de waarde maximaal 1.5x verhogen.<br />
							  * De minimale waarde is al opgegeven in het silver veld.';
		$txt['button_sell_box'] = 'Verkoop';
	}
	
	######################## AREA MESSENGER ########################
	elseif($page == 'area-messenger'){
		//Alerts
		$txt['alert_no_message'] = 'Nothing insert.';
		
		//Screen
		$txt['pagetitle'] = 'Area messenger';
		$txt['area_messenger_title_text'] = 'Welcome '.$_SESSION['naam'].' on Area messenger.<br />
											 <span class="smalltext">Don\'t scold, spam or advertise, else you will be banned from the site.</span>';
		$txt['area_messenger_advertisement'] = 'Advertisement.<br />
											 <span class="smalltext">Please click on the ads.</span>';
		$txt['say'] = 'Say:';
		$txt['button_area_messenger'] = 'Submit';
		$txt['please_login_first'] = '! --- You have to be signed in to respond, press F5 when you\'re logged in --- !';
		$txt['footer_made_by'] = 'Made by darkshifty';
		$txt['footer_copyright'] = 'copy; '.GLOBALDEF_SITENAME.' - '.date('Y');
	}
	
	############################## PREMIUM BOX #####################################
	elseif($page == 'area-box'){
		//Screen
		$txt['pagetitle'] = 'Premium';
		$txt['colorbox_text'] = 'Open this window again and this message will still be here.';
		$txt['prembox_title_text_1'] = 'You want to buy a';
		$txt['prembox_title_text_2'] = 'for account';
		$txt['prembox_title_text_3'] = 'price';
		
		$txt['call_text'] = '<div class="title_premium">Call</div>Here you can pay with the telefone.<br />
			 A computer will talk to you and say numbers to you. Type the numbers from the screen on your phone, when it\'s done, you have your pack.';
		$txt['call_button'] = 'Call now';
		$txt['paypal_text'] = '<div class="title_premium">Paypal</div>Here can you pay with Paypal.<br />
			Paypal is a online pay-method, you need a paypal account.';
		$txt['paypal_button'] = 'Paypal now';
		$txt['ideal_text'] = '<div class="title_premium">Ideal</div>
			Ideal is a pay-method. You can pay with your bank account.<br />
			With the following banks you can pay:<br />
			* ING<br />
			* ABM Amro<br />
			* Rabobank<br />
			* SNS<br />
			* Fortis<br />
			* Friesland Bank';
		$txt['ideal_button'] = 'Pay nu';
		$txt['wallie_text'] = '<div class="title_premium">Wallie</div>
			Here can you pay with a wallie card.<br />
			You can buy a wallie card at some shops, the most likely is Free Record Shop.';
		$txt['wallie_button'] = 'Wallie now';
	}
	
	############################## PREMIUM BOX IDEAL #####################################
	elseif($page == 'area-box-ideal'){
		//Screen
		$txt['title_text'] = 'You want to buy a '.$_SESSION['packnaam'].' pack for &euro;'.$info['kosten'].' with a bank payment. See here how:<br /><br />
								1. Go to your bank website.<br />
								2. Go to \'money transfer\'.<br />
								3. Insert at description:<br />
								<div style="padding-left:25px; float:left;">* Site: (<strong>'.GLOBALDEF_SITENAME.'</strong>).</div><br />
								<div style="padding-left:25px; float:left;">* Username: (<strong>'.$_SESSION['naam'].'</strong>).</div><br />
								<div style="padding-left:25px;">* Packname: (<strong>'.$_SESSION['packnaam'].'</strong>).</div><br />
								4. Transfer <strong>&euro; '.$info['kosten'].'</strong> to <strong>56.09.35.803</strong>.<br />
								5. Ask a administrator (<strong>SV2011</strong>) to check of the payment is done.<br />
								If the payment is successfully, the administrator will give you your premium things.<br /><br />
								* Important! If you transfer money in the weekend, the money will be transfered on Monday.<br />
								* Transfer the whole amount.';
	}
	
	############################## TRANSFERLIST BOX #####################################
	elseif($page == 'transferlist-box'){
		//Alerts
		$txt['alert_sold'] = 'is verkocht of van de transferlijst afgehaald door de eigenaar.';
		$txt['alert_too_low_rank_1'] = 'Je rank is te laag.';
		$txt['alert_too_low_rank_2'] = 'is het maximum wat je kan kopen.';
		$txt['alert_house_full'] = 'Je huis is vol.';
		$txt['alert_too_less_silver'] = 'Je hebt onvoldoende silver.';
		$txt['alert_too_less_gold'] = 'Je hebt onvoldoende gold.';

		//Screen
		$txt['pagetitle'] = 'Transferlist';
		$txt['trbox_title_text_bought_1'] = 'Gefeliciteerd met je nieuwe pokemon!';
		$txt['trbox_title_text_bought_2'] = 'Je hebt';
		$txt['trbox_title_text_bought_3'] = 'succesvol gekocht';
		$txt['trbox_title_text_bought_4'] = 'is nu in jouw huis.';
		
		$txt['trbox_title_text_1'] = 'Koop';
		$txt['trbox_title_text_2'] = 'van';
		$txt['trbox_title_text_3'] = 'is nu';
		$txt['trbox_title_text_4'] = 'waard.';
		$txt['trbox_title_text_5'] = 'is te koop voor';
		$txt['trbox_title_text_6'] = 'Weet je zeker dat je';
		$txt['trbox_title_text_7'] = '?';
		$txt['trbox_title_text_8'] = 'en';
		$txt['trbox_title_text_9'] = 'wil kopen';
		$txt['button_transferlist_box'] = 'Koop';
	}