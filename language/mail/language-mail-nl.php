<?php
	//NEDERLANDS MAIL
	
	//Register
	$txt['mail_register_title'] = GLOBALDEF_SITENAME.' Activatie';
	$txt['mail_register'] = 'Welkom op '.GLOBALDEF_SITENAME.' '.$voornaam.' '.$achternaam.'!<br>
								<br> 
								Je activatiecode is: <b>'.$activatiecode.'</b><br>
								Je kunt je account hier activeren: <a href="/?page=activate&player='.$inlognaam.'&code='.$activatiecode.'" target="_blank">
								/?page=activate&player='.$inlognaam.'&code='.$activatiecode.'</a><br>
								<br>
								Je inlognaam is: <b>'.$inlognaam.'</b><br>
								Je wachtwoord is: <b>'.$wachtwoord.'</b><br>
								*Bewaar deze e-mail goed, deze informatie kan nog eens van pas komen.<br>
								<br>
								Wij wensen je veel speelplezier, en probeer niet vals te spelen!<br>
								<br>
								Met vriendelijke groet,<br>
								'.GLOBALDEF_SITENAME.'';

	//Contact
	$txt['mail_contact'] = $bericht;

	//Forgot username
	$txt['mail_forgot_username_title'] = GLOBALDEF_SITENAME.' Inlognaam opvragen';
	$txt['mail_forgot_username'] = 'Beste '.$gegeven['voornaam'].' '.$gegeven['achternaam'].'!<br><br>
									Je hebt je inlognaam opgevraagd, dit is: <strong>'.$gegeven['username'].'</strong><br><br>
									Veel speelplezier!<br><br>
									Met vriendelijke groet,<br>
									'.GLOBALDEF_SITENAME.'';

	//Forgot password
	$txt['mail_forgot_password_title'] = GLOBALDEF_SITENAME.' Nieuw Wachtwoord';
	$txt['mail_forgot_password'] = 'Beste '.$gegeven['voornaam'].' '.$gegeven['achternaam'].'!<br><br>
									Je hebt een nieuw wachtwoord opgevraagd, dit is: <b>'.$nieuwww.'</b><br><br>
									*Je kunt je wachtwoord in het spel weer veranderen;<br>
									1. Inloggen met je nieuwe wachtwoord<br>
									2. Account opties<br>
									3. Wachtwoord<br><br>
									Veel speelplezier!<br><br>
									Met vriendelijke groet,<br>
									'.GLOBALDEF_SITENAME.'';

	//Premium Shop
	$txt['mail_premiumshop_title'] = GLOBALDEF_SITENAME.' Premium Shop';
	$txt['mail_premiumshop_password'] = 'Beste '.$voornaam.' '.$achternaam.',<br><br> 
									Je hebt een <b>'.$packnaam.'</b> gekocht ter waarde van &euro;'.$packkosten.'<br>
									Bewaar deze mail goed, dit geldt als een betalingsbewijs.<br><br>
									Veel plezier hiermee!<br><br>
									Met vriendelijke groet,<br>
									'.GLOBALDEF_SITENAME.' Team';