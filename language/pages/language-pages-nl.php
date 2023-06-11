<?php
######################## Home ########################
if(($page == 'home') OR ($page == '')){
    #Screen
    $txt['pagetitle'] = 'Home';
}

######################## Page not found ######################
elseif($page == 'notfound'){
    #Not found page
    $txt['pagetitle'] = 'Pagina niet gevonden';
    $txt['notfoundtext'] = '<p>De pagina is niet gevonden. De pagina waar jij naar zoekt is misschien verwijderd, 
                        heeft zijn naam veranderd, of is tijdelijk offline.<br /><br />
						<strong>Probeer a.u.b. het volgende:</strong><br /><br />
						1. Als je het adres hebt ingetypt in de adresbalk, kijk eens of er een fout in zit.<br />
						2. Zoek de link op de linker en rechter menu\'s.<br />
						3. Klik op de Back button van je browser.<br />
						4. Klik op loguit bovenaan het scherm en probeer het nog eens.</p>';
}

######################## Captcha page ######################
elseif($page == 'captcha'){
    #Not found page
    $txt['pagetitle'] = 'Beveiligingscode';
    $txt['title_text_1'] = 'Om te voorkomen dat mensen valsspelen moet je hier soms een willekeurige code invullen.<br />
								Als je het 3x fout hebt, niets invoert of refreshed word je uitgelogd.<br />
								Tevens word dan opgeslagen dat je het 3x fout hebt, als dat tevaak voorkomt word je verbannen.<br /><br />
								Je hebt nog';
    $txt['title_text_2'] = 'pogingen over.';
    $txt['guard_code'] = 'Beveiligingscode:';
    $txt['button'] = 'Go';

    #Alerts
    $txt['alert_no_guardcode'] = 'Geen beveiligingscode ingevuld.';
    $txt['alert_guardcode_numbers_only'] = 'Beveiligingscode bevat alleen cijfers.';
    $txt['alert_guardcode_wrong'] = 'Beveiligingscode is incorrect.';
}

######################## REGISTER ########################
if($page == 'register'){
    #Alerts
    $txt['alert_already_this_ip'] = 'Je hebt met dit ip adres deze week al een account aangemaakt.<br>
										 Volgende week kun je weer een account aanmaken.';
    $txt['alert_no_firstname'] = 'Geen voornaam ingevuld';
    $txt['alert_firstname_too_long'] = 'Voornaam te lang, maximaal 12 tekens.';
    $txt['alert_no_lastname'] = 'Geen achternaam ingevuld';
    $txt['alert_lastname_too_long'] = 'Achternaam te lang, maximaal 12 tekens.';
    $txt['alert_no_country'] = 'Geen land gekozen.';
    $txt['alert_no_full_gebdate'] = 'Alle velden moeten ingevuld zijn bij je geboortedatum.';
    $txt['alert_character_invalid'] = 'Persoon niet beschikbaar.';
    $txt['alert_no_username'] = 'Geen Gebruikersnaam ingevuld.';
    $txt['alert_username_too_short'] = 'Gebruikersnaam te kort, minimaal 3 tekens.';
    $txt['alert_username_too_long'] = 'Gebruikersnaam te lang, maximaal 10 tekens.';
    $txt['alert_username_exists'] = 'Gebruikersnaam bestaat al.';
    $txt['alert_username_incorrect_signs'] = 'Gebruikersnaam bevat onjuiste tekens.';
    $txt['alert_no_password'] = 'Geen wachtwoord ingevuld.';
    $txt['alert_passwords_dont_match'] = 'Wachtwoorden komen niet overeen.';
    $txt['alert_no_email'] = 'Geen e-mail ingevoerd.';
    $txt['alert_email_incorrect_signs'] = 'Geen geldig e-mail adres.';
    $txt['alert_email_exists'] = 'E-mail bestaat al.';
    $txt['alert_no_beginworld'] = 'Geen begin wereld gekozen.';
    $txt['alert_world_invalid'] = 'Begin wereld onbekend.';
    $txt['alert_1account_condition'] = 'Ga akkoord met de voorwaarde, ik heb maar 1 account.';
    $txt['alert_no_offend_condition'] = 'Ga akkoord met de voorwaarde, ik ga niet schelden enz.';
    $txt['alert_guardcore_invalid'] = 'Beveiligingscode verkeerd ingevuld.';
    $txt['success_register'] = 'Je hebt je succesvol aangemeld, er is een mailtje verzonden met een activatiecode.<br/>
Als je geen mail hebt ontvangen controleer dan je junk/spam folder of stuur een mail naar '.GLOBALDEF_ADMINEMAIL.'<br/><br/>';
    $txt['success_register2'] = 'Je hebt je succesvol aangemeld, je kan direct inloggen.';

    #Screen
    $txt['pagetitle'] = 'Register';
    $txt['title_text'] = 'Meld je nu gratis aan voor '.GLOBALDEF_SITEDOMAIN.' en krijg <br/><img src="images/icons/gold.png" /> <strong>50 gold gratis!</strong> <img src="images/icons/gold.png" />';
    $txt['register_personal_data'] = 'Persoonlijke gegevens';
    $txt['register_game_data'] = 'Spel gegevens';
    $txt['register_security'] = 'Beveiliging';
    $txt['firstname'] = 'Voornaam:';
    $txt['lastname'] = 'Achternaam:';
    $txt['country'] = 'Land:';
    $txt['gebdate'] = 'Geboortedatum:';
    $txt['day'] = 'Dag';
    $txt['month'] = 'Maand';
    $txt['year'] = 'Jaar';
    $txt['character'] = 'Persoon';
    $txt['username'] = 'Gebruikersnaam';
    $txt['password'] = 'Wachtwoord';
    $txt['password_again'] = 'Wachtwoord nogmaals';
    $txt['email'] = 'Email';
    $txt['beginworld'] = 'Wereld';
    $txt['1account_rule'] = 'Ik heb een refer';
    $txt['referer'] = 'Referer';
    $txt['not_oblige'] = '*Niet verplicht.';
    $txt['guardcode'] = 'Beveiligingscode';
    $txt['captcha'] = 'Beveiligingscode plaatje';
    $txt['button'] = 'Aanmelden';
}

######################## INFORMATION ########################
elseif($page == 'information'){
    $txt['pagetitle'] = 'Informatie';
    $txt['link_subpage_game_info'] = 'Spel informatie';
    $txt['link_subpage_pokemon_info'] = 'Pok&eacute;mon informatie';
    $txt['link_subpage_attack_info'] = 'Aanval informatie';

    if($_GET['category'] == 'game-info'){
        #Screen
        $txt['pagetitle'] .= ' - Spel informatie';
        $txt['informationpage'] = '<h2>Inhoud</h2>
				<div id="information">
				<ol>
				<li><a href="#thegame">Het spel</a></li>
				<li><a href="#rules">Regels</a></li>
				<li><a href="#begin">Het begin</a></li>
				<li><a href="#tips">Tips voor het spel</a></li>
				<li><a href="#program">Programma</a></li>
				<li><a href="#silver&gold">Silver en Gold</a></li>
				<li><a href="#pokemon">Pok&eacute;mon</a></li>
				<li><a href="#ranks">Ranks</a></li>
				<li><a href="#attacks">TM\'s en HM\'s</a></li>
				<li><a href="#admins">Administratoren</a></li>
				<li><a href="#register">Registreren</a></li>
				<li><a href="#contact">Contact</a></li>
				<li><a href="#activate">Activeer account</a></li>
				<li><a href="#forgotusername">Inlognaam vergeten</a></li>
				<li><a href="#forgotpassword">Wachtwoord vergeten</a></li>
				<li><a href="#accountoptions">Account opties</a></li>
				<li><a href="#profile">Profiel</a></li>
				<li><a href="#rankinglist">Rankinglijst</a></li>
				<li><a href="#pokemoninfo">Pok&eacute;mon informatie</a></li>
				<li><a href="#advertise">Adverteren voor silver</a></li>
				<li><a href="#modifyorder">Pok&eacute;mon volgorde wijzigen</a></li>
				<li><a href="#sell">Verkoop een Pok&eacute;mon</a></li>
				<li><a href="#release">Een Pok&eacute;mon vrijlaten</a></li>
				<li><a href="#items">Items</a></li>
				<li><a href="#badgecase">Badge doos</a></li>
				<li><a href="#myhouse">Mijn huis</a></li>
				<li><a href="#pokedex">Pokedex</a></li>
				<li><a href="#buddyandblock">Buddylijst en Blocklijst</a></li>
				<li><a href="#areamessenger">Area messenger</a></li>
				<li><a href="#attack">Aanvallen</a></li>
				<li><a href="#gyms">Gyms</a></li>
				<li><a href="#duel">Duelleren</a></li>
				<li><a href="#tournament">Toernooi</a></li>
				<li><a href="#work">Werken</a></li>
				<li><a href="#race">Race</a></li>
				<li><a href="#traders">Traders</a></li>
				<li><a href="#stealandspy">Stelen en Bespioneren</a></li>
				<li><a href="#chooselevel">Kies een level</a></li>
				<li><a href="#premiumbank">Area markt</a></li>
				<li><a href="#pokemoncenter">Pok&eacute;mon center</a></li>
				<li><a href="#market">Markt</a></li>
				<li><a href="#bank">Bank</a></li>
				<li><a href="#boat">Bootverhuur</a></li>
				<li><a href="#houseseller">Huizenverkoper</a></li>
				<li><a href="#transferlist">Transferlijst</a></li>
				<li><a href="#daycare">Daycare</a></li>
				<li><a href="#namespecialist">Naam specialist</a></li>
				<li><a href="#shinyspecialist">Shiny specialist</a></li>
				<li><a href="#jail">Gevangenis</a></li>
				<li><a href="#gamble">Gokken</a></li>
				</ol>
				<hr />
				<div id="thegame">
					<h2>Het spel</h2>
					'.GLOBALDEF_SITENAME.' is een online multiplayer spel.<br />
					Het spel heeft 4 werelden en 496 Pok&eacute;mon!<br />
					Iedereen mag er gratis aan meedoen, tevens kun je er voor betalen voor extra functies.<br />
					Ons doel is om 1 van de grootste multiplayer sites te worden.<br />
					We zijn nog hard bezig met allerlei updates, soms met updates voor de snelheid van de site en soms komt er weer een geheel nieuwe pagina met functies.<br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="rules">
					<h2>Regels</h2>
					De regels van '.GLOBALDEF_SITENAME.' zijn best simpel:
					<ul>
						<li>Niet schelden (op je profiel of via een bericht).</li>
						<li>Niet vragen om wachtwoorden.</li>
						<li>Je wachtwoord zelf dus ook <strong>nooit</strong> weggeven.</li>
						<li>Niet spammen (spammen is een aantal keer achter elkaar iets onnodigs zeggen).</li>
						<li>Niet adverteren voor andere websites.</li>
						<li>Geen dubbelaccount.</li>
					</ul>
					Als je toch 1 van deze dingen doet zullen we je zonder pardon verbannen van de site.<br />
					Hoelang de verbanning is zal liggen aan wat je verkeerd hebt gedaan.<br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="begin">
					<h2>Het begin</h2>
					In het begin zal Professor Oak op je afkomen.<br />
					Hij zal samen met jou de regels nog eens doornemen en daarna geeft hij je een Pok&eacute;mon ei.<br />
					Je hebt de keuze tussen een aantal Pok&eacute;mon van de wereld waar je op dat moment bent.<br />
					Nadat je de Pok&eacute;mon hebt gekregen kun je meteen beginnen met het spel.<br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="tips">
					<h2>Tips voor het spel</h2>
					<ul>
						<li>Speel '.GLOBALDEF_SITENAME.' in een Mozilla Firefox browser.</li>
						<li>Als je op een openbare computer bent (dus op school ofzo). Onthou je wachtwoord dan <strong>niet</strong>.</li>
						<li>Tevens als je op een openbare computer speelt: Altijd uitloggen als je even weg loopt.</li>
						<li>Als je op vakantie gaat, zet dan al je geld op de bank en doe je Pok&eacute;mon naar de daycare.</li>
						<li>Koop altijd eerst balls voordat je gaat vechten. Wie weet wat je ineens tegenkomt.</li>
						<li>Als je een Pok&eacute;mon zoekt, kijk bij Pok&eacute;mon informatie waar die te vinden is.</li>
						<li>Word premium, de extra functies zijn erg goed.</li>
						<li>Probeer een shiny ditto te vangen, kan erg handig zijn voor de daycare.</li>
					</ul><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="program">
					<h2>Programma</h2>
					'.GLOBALDEF_SITENAME.' heeft een programma ontworpen dat je tijdens het spelen een *Poing* hoort als iemand iets zegt in Area messenger, tevens is het spel in het programma heel snel en je kunt '.GLOBALDEF_SITENAME.' voortaan opstarten vanaf je bureaublad! Je kunt het <strong>gratis downloaden</strong> op de home pagina.<br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="silver&gold">
					<h2>Silver en Gold</h2>
					Je kunt betalen in '.GLOBALDEF_SITENAME.' met Silver en Gold, hier zie je wat silver en wat gold is:
					<ul>
						<li><img src="images/icons/silver.png" title="Silver"> = Silver.</li>
						<li><img src="images/icons/gold.png" title="Gold"> = Gold.</li>
					</ul>
					Silver kun je krijgen in het spel d.m.v. werken, trainers verslaan, etc.<br />
					Gold kun je kopen in de premium markt, hiermee kun je een aantal dingen wat je met silver niet kunt, zoals:
					<ul>
						<li>Master balls kopen</li>
						<li>Rare candy\'s kopen</li>
						<li>Gebruikersnaam veranderen</li>
						<li>Gold doneren aan andere spelers</li>
						<li>Van een Pok&eacute;mon een shiny Pok&eacute;mon maken</li>
					</ul><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="pokemon">
					<h2>Pok&eacute;mon</h2>
					'.GLOBALDEF_SITENAME.' heeft 496 verschillende Pok&eacute;mon en al die Pok&eacute;mon zijn ook nog eens in een shiny vorm.<br />
					Een shiny Pok&eacute;mon is dezelfde Pok&eacute;mon als een normale maar dan zeldzamer en een andere kleur.<br />
					Je kunt een shiny Pok&eacute;mon herkennen aan de <img src="images/icons/lidbetaald.png" /> achter de naam en de kleur van de Pok&eacute;mon.<br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<HR />
				<div id="ranks">
					<h2>Ranks</h2>
					Er zijn 20 ranks op '.GLOBALDEF_SITENAME.'. Dit zijn ze:
					<ol>
						<li>Newbie</li>
						<li>Junior</li>
						<li>Senior</li>
						<li>Casual</li>
						<li>Trainer</li>
						<li>Great Trainer</li>
						<li>Traveller</li>
						<li>Macho</li>
						<li>Gym Leader</li>
						<li>Shiny Trainer</li>
						<li>Elite Trainer</li>
						<li>Commander</li>
						<li>Master</li>
						<li>Shiny Master</li>
						<li>Mystery Trainer</li>
						<li>Professional</li>
						<li>Ranger</li>
						<li>Elite Ranger</li>
						<li>Hero</li>
						<li>King</li>
						<li>Champion</li>
						<li>Legendary</li>
						<li>Untouchable</li>
						<li>Immortal</li>
						<li>Area-Master</li>
						<li>Area-Champion</li>
						<li>Area-God</li>
						<li>Ultimate</li>
						<li>Pok&eacute;mon Leader</li>
						<li>Champion+</li>
						<li>Ultimate+</li>
						<li>Master of PW</li>
						<li>King of PW</li>
						<li>Godfather of PW</li>
						<li>The Emperor of PW</li>
						<li>GameFreak of PW</li>
						<li>Guardian of PW</li>
						<li>Champion of PW</li>
						<li>Ultimate Champion of PW</li>
						<li>Pok&eacute;mon Master</li>
					</ol><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<HR />
				<div id="attacks">
					<h2>TM\'s en HM\'s</h2>
					Er zijn op '.GLOBALDEF_SITENAME.' 92 TM\'s en 8 HM\'s.<br /><br />
					<strong>TM</strong><br />
					Een TM kun je kopen bij de markt. Je kunt een TM alleen aan de Pok&eacute;mon geven met dezelfde type, dus als het een ijs aanval is kan alleen een ijs Pok&eacute;mon het aanleren. Sommige aanvallen hebben 2 types.<br /><br />
					<strong>HM</strong><br />
					Een HM kun je verdienen door een gymleader te verslaan.<br />
					De HM kun je zovaak gebruiken als je wilt.<br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="admins">
					<h2>Administratoren</h2>
					Er zijn altijd een paar administratoren op een spel.<br />
					Dit zijn de functies van een administrator:
					<ul>
						<li>Mensen helpen door hun vragen te beantwoorden.</li>
						<li>Updates maken voor de site.</li>
					</ul>
					Een administrator kan alle berichten lezen die iedereen naar elkaar stuurt. Dit is ervoor om leden te beschermen.<br /><br />
					Als je een fout ziet in het spel zouden we dat graag willen horen.<br />
					Als iemand je uitscheld of je in welke mate dan ook beledigd horen we dat graag, we doen ons best om er dan iets aan te doen.<br /><br />
					Vraag niet of jij zelf een administrator kunt worden. Als we een nieuwe nodig hebben, dan kiezen we die zelf. Gebasseerd op hoe je doet op het spel.<br />
					Oftewel als je vaak iemand helpt, je bent vaak online, enzovoort. Dan heb je meer kans.	<br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="register">
					<h2>Registreren</h2>
					Vul bij het registreren alle velden zo goed mogelijk in.<br />
					Je e-mail adres moet geldig zijn want daar word je activatiemail heen gestuurd.<br />
					Je vinkt onderaan de pagina aan dat het jouw enigste account is. Probeer hier niet over te liegen.<br />
					Kijk nadat je jouw account hebt aangemaakt even naar welk e-mail adres de mail is gestuurd. Je kunt natuurlijk altijd een typfoutje maken.<br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="contact">
					<h2>Contact</h2>
					Als er iets misgaat kun je altijd contact met ons opnemen. Hiermee bedoelen we:
					<ul>
						<li>Activatiemail niet gekregen.</li>
						<li>Het activeren lukt niet.</li>
						<li>Ik kan niet meer op mijn account.</li>
					</ul>
					Tevens mag je ons ook mailen voor andere zaken zoals:
					<ul>
						<li>Linkpartner worden.</li>
						<li>Vragen over de site.</li>
					</ul>
					Contact is alleen te zien als je niet bent ingelogd. Dit komt omdat als je wel bent ingelogd je een berichtje kunt sturen naar een administrator in het spel.<br /><br />
					We proberen altijd zo snel mogelijk een bericht of e-mail terug te sturen.<br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="activate">
					<h2>Activeer account</h2>
					Voordat je kunt spelen moet je eerst je account activeren.<br />
					De activatie code word verstuurd naar je e-mail adres die je hebt opgegeven bij het aanmelden.<br /><br />
					Als het je niet lukt om het account te activeren horen wij dat graag via contact.<br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="forgotusername">
					<h2>Inlognaam vergeten</h2>
					Vul je e-mail in en je inlognaam word verstuurd naar je e-mail adres.<br /><br />
					Als je geen e-mail ontvangt horen wij dat graag via contact.<br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="forgotpassword">
					<h2>Wachtwoord vergeten</h2>
					Vul je inlognaam en e-mail in en er word een nieuw wachtwoord verstuurd naar je e-mail adres.<br />
					Log met dat nieuwe wachtwoord in en je kunt je wachtwoord in het spel weer veranderen bij Account opties - Wachtwoord.<br /><br />
					Als je geen e-mail ontvangt horen wij dat ook graag via contact.<br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="accountoptions">
					<h2>Account opties</h2>
					Account opties heeft 4 verschillende pagina\'s:<br /><br />
					<strong><img src="images/icons/user.png" /> Persoonlijk</strong><br />
					Hier kun je jouw gegevens wijzigen.<br />
					Gelieve je profiel zo eerlijk mogelijk in te vullen, dus voornaam, achternaam, land en geslacht.<br />
					Je kunt hier ook aanvinken of jouw team en badges op je profiel komen te staan.<br /><br />
					<span class="smalltext">Badges op je profiel is pas beschikbaar als je een Badge doos hebt.</span><br /><br />
					<strong><img src="images/icons/key.png" /> Wachtwoord</strong><br />
					Hier kun je jouw wachtwoord wijzigen.<br />
					Probeer deze goed te onthouden.<br /><br />
					<strong><img src="images/icons/images.png" /> Profiel</strong><br />
					Alles wat je hieronder invult komt onderaan je profiel te staan.<br />
					Als je op <strong>Here</strong> klikt komt er een pagina tevoorschijn waar alle codes instaan zoals:
					<ul>
						<li>Youtube filmpjes op je profiel zetten.</li>
						<li>Een Pok&eacute;mon op je profiel zetten.</li>
						<li>Tekst kleur veranderen.</li>
						<li>Enz. enz.</li>
					</ul>
					<strong><img src="images/icons/new.gif" /> Opnieuw beginnen</strong><br />
					Je kunt 1 keer per dag opnieuw beginnen.<br />
					Dit is ervoor zodat leden niet elke 15 minuten opnieuw beginnen omdat ze hun Pok&eacute;mon niet leuk vinden.<br />
					Stel dat je jouw Pok&eacute;mon niet leuk vind kun je altijd met iemand ruilen. Als dit echt niet lukt kun je altijd nog overwegen om over 24 uur opnieuw te beginnen.<br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="profile">
					<h2>Profiel</h2>
					Elke speler heeft zijn/haar eigen profiel.<br />
					Die is ook toegankelijk als je niet bent ingelogd via rankinglijst.<br />
					Zet op je profiel geen negatieve dingen over andere spelers. Scheldwoorden zijn ook niet toegestaan.<br />
					Je kunt je profiel erg mooi maken, zie daarvoor Account opties - Profiel hierboven.<br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="rankinglist">
					<h2>Rankinglijst</h2>
					Dit is de lijst van alle spelers gesorteerd op rang.<br />
					Als je niet bent ingelogd kun je ook op deze pagina. Dus weet waar je het voor doet!<br />
					De top 50 krijgt overigens een medaille op hun profiel.<br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="pokemoninfo">
					<h2>Pok&eacute;mon informatie</h2>
					Selecteer een Pok&eacute;mon en je vind alle informatie die je nodig bent.<br />
					Je ziet zelfs waar je de Pok&eacute;mon kunt vinden (welk gebied + wereld).<br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="advertise">
					<h2>Adverteren voor silver</h2>
					Je kunt via msn ofzo jouw '.GLOBALDEF_SITENAME.' link doorsturen naar andere mensen.<br />
					Voor elk aangemeld lid krijg jij dan wat silver!<br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="modifyorder">
					<h2>Pok&eacute;mon volgorde wijzigen</h2>
					Bij dit systeem moet je de Pok&eacute;mon slepen naar boven of beneden.<br />
					Het word automatisch opgeslagen.<br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="sell">
					<h2>Verkoop een Pok&eacute;mon</h2>
					Je kunt een Pok&eacute;mon verkopen door hem op de transferlijst te zetten.<br />
					Iemand kan de Pok&eacute;mon dan kopen voor de prijs waarvoor jij de Pok&eacute;mon op de transferlijst hebt gezet.<br /><br />
					Een Pok&eacute;mon heeft een bepaalde waarde, de vraagprijs mag maximaal 1,5x zijn waarde en minimaal de helft van zijn waarde.<br />
					Je kunt je beginPok&eacute;mon niet verkopen, die is teveel aan je gehecht.<br /><br />
					<span class="smalltext">Een premiumlid mag 3 Pok&eacute;mon op de transferlijst zetten. Een normaal lid 1.<br />
					Een Pok&eacute;mon verkopen kan pas vanaf rank 4 Casual.</span><br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="release">
					<h2>Een Pok&eacute;mon vrijlaten</h2>
					Je kunt een Pok&eacute;mon vrijlaten op '.GLOBALDEF_SITENAME.'.<br />
					Je krijgt dan de ball terug waarmee die is gevangen, tenminste als jouw itemdoos niet vol is.<br />
					Je kunt je beginPok&eacute;mon niet verkopen, die is teveel aan je gehecht.<br />
					<strong>Let op:</strong> dit kan niet worden teruggedraaid.<br /><br />
					<span class="smalltext">Een Pok&eacute;mon vrijlaten kan pas vanaf rank 5 Trainer.</span><br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="items">
					<h2>Items</h2>
					Hier word bijgehouden welke items je allemaal hebt.<br />
					Je kunt ze hier ook gebruiken of verkopen.<br />
					Je begint met een tas, daar kunnen 20 items in. Als je meer items wilt moet je in de markt een Yellow box, Blue box of Red box kopen.<br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="badgecase">
					<h2>Badge doos</h2>
					Voor elke wereld heb je een doos voor je badges.<br />
					De badges komen hier automatisch in te liggen als je ze hebt.<br />
					Je krijgt een badge doos van de gymleader die je als eerst verslaat.<br /><br />
					Deze pagina is pas toegankelijk als je de badge doos hebt gekregen van de gymleader.<br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="myhouse">
					<h2>Mijn huis</h2>
					In de normale Pok&eacute;mon spellen worden de Pok&eacute;mon verplaatst in het Pok&eacute;mon center met een computer.<br />
					Bij '.GLOBALDEF_SITENAME.' is dit anders, je hebt een huis!<br />
					Je begint met een doos, hier kunnen 2 Pok&eacute;mon in verblijven.<br />
					Bij de huizenverkoper kun je een huis kopen waarin meer Pok&eacute;mon kunnen verblijven.<br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="pokedex">
					<h2>Pokedex</h2>
					<strong>Pokedex</strong><br />
					In je pokedex word opgeslagen welke Pok&eacute;mon je hebt gezien en welke je hebt gevangen.<br />
					Je moet hiervoor natuurlijk wel eerst een pokedex kopen in de markt.<br /><br />
					<strong>Pokedex chip</strong><br />
					Je kunt in de markt ook een pokedex chip kopen. Dit is een update voor de Pokedex.<br />
					Hier mee kun je de level van een wilde Pok&eacute;mon zien.<br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="sendmessage">
					<h2>Bericht zenden</h2>
					Je kunt op '.GLOBALDEF_SITENAME.' een bericht zenden naar een ander lid.<br />
					Denk hierbij wel na wat je stuurt. Bij regels staat wat wel en niet mag!<br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="buddyandblock">
					<h2>Buddylijst en blocklijst</h2>
					<strong>Buddylijst</strong><br />
					De buddylijst is ervoor om makkelijk iemand geld te geven of een bericht te sturen. Je hoeft dus niet meer naar een speler te zoeken.<br /><br />
					<strong>Blocklijst</strong><br />
					Hier komen alle leden in die jij hebt geblokkeerd.<br />
					Een lid die is geblokkeerd kan jou geen berichten meer sturen, jij overigens ook niet naar diegene.<br />
					Pas dit dus ook toe als iemand je uitscheld of je beledigd.<br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="areamessenger">
					<h2>Area messenger</h2>
					Hier kun je met andere leden praten.<br />
					<strong>Tip:</strong> vertel hier welke Pok&eacute;mon je verkoopt.<br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="attack">
					<h2>Aanvallen</h2>
					Eerst kies je een gebied waar je wilt gaan vechten.<br />
					Dan komt er een Pok&eacute;mon tevoorschijn die tegen je wil vechten.<br /><br />
					<strong>Shiny & Legendarische Pok&eacute;mon</strong><br />
					Je kunt zelfs Shiny Pok&eacute;mon tegenkomen of Legendarische. Het maakt niet uit welke rank je bent, de kans op een Shiny of Legendarische is altijd even groot.<br />
					Geluk dwing je natuurlijk af door zoveel mogelijk te gaan aanvallen.<br /><br />
					<strong>Trainer</strong><br />
					Soms daagt een trainer of Team Rocket je ineens uit tot een gevecht. Je hebt een kans dat als je de trainer of Team Rocket verslaat dat je van hun een item of wat silver krijgt.<br /><br />
					<strong>Run</strong><br />
					Voordat je run kunt gebruiken moet je eerst een fiets kopen in de markt.<br />
					Een fiets werkt alleen bij een wilde Pok&eacute;mon, dus niet bij een trainer gevecht.<br /><br />
					<strong>Verlies</strong><br />
					Als je verliest gaat er 25% van je silver af, wat je cash bij je hebt.<br />
					Ga daarom vaak langs de Pok&eacute;mon center! Gok niet op nog een Pok&eacute;mon want er kan altijd een trainer tevoorschijn komen.<br /><br />
					<strong>Foutcode</strong><br />
					Als er ineens een foutcode tevoorschijn komt zou het handig zijn om dat te vertellen aan een administrator. Vertel dan dus ook welke foutcode je zag.<br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="gyms">
					<h2>Gyms</h2>
					In elke wereld zijn 8 gyms. Je kunt een gym pas uitdagen als jou rank hoog genoeg is.
					<ul>
						<li><img src="images/icons/pokeball.gif"> = Pok&eacute;mon is levend.</li>
						<li><img src="images/icons/pokeball_black.gif"> = Pok&eacute;mon is Knock out.</li>
					</ul>
					Je hebt pas gewonnen als alle Pok&eacute;mon van de gymleader Knock out zijn.<br /><br />
					Na het gevecht komt de gymleader bij je en die geeft je wat je verdient. Meestal is dit een badge en wat silver.<br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="duel">
					<h2>Duelleren</h2>
					Je kunt in '.GLOBALDEF_SITENAME.' ook duelleren tegen andere leden.<br />
					Als je wint krijg je de ingelegde prijs.<br /><br />
					Er word opgeslagen wie er wint, de laatste 10 wedstrijden staan bij statistieken.<br /><br />
					<span class="smalltext">Duel is alleen toegankelijk als premiumlid, je kunt ook alleen maar duelleren tegen een premiumlid.</span><br /><br />
					<span class="smalltext">Duel is pas beschikbaar vanaf rank 5 Trainer.</span><br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="tournament">
					<h2>Toernooi</h2>
					Je kunt ook deelnemen aan een toernooi.<br />
					Elke dag om 6 uur word er een ronde gehouden, de speler die wint gaat een ronde verder!<br /><br />
					<span class="smalltext">Toernooi is alleen toegankelijk als premiumlid en beschikbaar vanaf rank 5 Trainer.</span><br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="work">
					<h2>Werken</h2>
					Bij '.GLOBALDEF_SITENAME.' kun je ook werken voor wat silver.<br />
					Hoe hoger je rank hoe makkelijk het werk word. Dit betekent dus ook dat als je net begint dat je geen casino kunt beroven. De kans dat dit zal lukken is erg klein.<br /><br />
					<span class="smalltext">Een premiumlid heeft extra werkmogelijkheden.</span><br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="race">
					<h2>Race</h2>
					Je kunt racen tegen andere spelers.<br />
					Het systeem kiest automatisch je snelste Pok&eacute;mon, daar race je mee. Tegen de snelste van je tegenstander.<br />
					De Pok&eacute;mon kan tegen een boom aanrennen, etc.<br />
					Als een race uitnodiging al 3 dagen oud is word het automatisch verwijderd.<br /><br />
					<span class="smalltext">Racen is beschikbaar vanaf rank 4 Casual.</span><br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="traders">
					<h2>Traders</h2>
					Er zijn ook trainers die hun Pok&eacute;mon willen ruilen.<br />
					Ze vertellen welke Pok&eacute;mon hun willen en welke zij zelf hebben.<br />
					Elke nacht om 01:00 worden de Pok&eacute;mon vernieuwd.<br />
					<strong>Tip:</strong> ruil zoveel mogelijk want dan krijgt de Pok&eacute;mon na een gevecht meer EXP points.<br /><br />
					<span class="smalltext">Traders zijn pas beschikbaar vanaf rank 5 Trainer.</span><br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="stealandspy">
					<h2>Stelen en bespioneren</h2>
					<strong>Stelen</strong><br />
					Je kunt wat silver van iemand stelen in '.GLOBALDEF_SITENAME.'. Je Pok&eacute;mon moeten daarbij wel sterker zijn als die van je tegenstander. Dit varieert natuurlijk wel.<br /><br />
					<strong>Bespioneren</strong><br />
					Je kunt ook een speler bespioneren, hierbij huur je Team Rocket.<br />
					Zij zullen alle gegevens die ze hebben verzameld aan jou doorgeven.<br />
					<strong>Tip:</strong> ga eerst bespioneren voordat je iemand gaat bestelen.<br /><br />
					<span class="smalltext">Een premiumlid kan 5x per dag stelen. Een normaal lid 3x.<br />
					Stelen en bespioneren is beschikbaar vanaf rank 3 Senior.</span><br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="chooselevel">
					<h2>Kies een level</h2>
					Kies een level is een functie waarbij je kunt aanvinken welk level Pok&eacute;mon je wilt tegenkomen in het wild.<br /><br />
					<span class="smalltext">Deze functie is pas beschikbaar vanaf rank 18 Untouchable.</span><br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="premiumbank">
					<h2>Area markt</h2>
					In de Premium markt kun je Premiumdagen kopen. De voordelen van premium zijn staat bij elke pack.<br /><br />
					Tevens verkopen we aanbieding packs. Zoals:
					<ul>
						<li>5000 Silver pack.</li>
						<li>25 Gold pack.</li>
					</ul>
					We hebben veel betaalmogelijkheden. Dat varieert wel van welke pack je wilt kopen.<br /><br />
					Gelieve eerst aan je ouders/verzorgers te vragen of je mag betalen.<br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="pokemoncenter">
					<h2>Pok&eacute;mon center</h2>
					Je kunt je Pok&eacute;mon hier weer vol leven geven.<br />
					Het is gratis, het kost alleen wat tijd.<br /><br />
					<span class="smalltext">Een premiumlid moet 10 sec te wachten voor de genezing van de Pok&eacute;mon, bij een normaal lid is dit 1 minuut.</span><br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="market">
					<h2>Markt</h2>
					Bij de markt kun je alle dingen kopen wat je nodig bent:
					<ul>
						<li>Balls</li>
						<li>Potions</li>
						<li>Items</li>
						<li>Special items</li>
						<li>Stones</li>
						<li>Pok&eacute;mon eieren</li>
						<li>TM\'s</li>
					</ul>
					Pok&eacute;mon eieren worden 1 keer per uur weer bijgevuld.<br />
					Tevens worden de Pok&eacute;mon eieren elke maandag en donderdag om 01:00 geheel vernieuwd.<br />
					<span class="smalltext">TM\'s zijn pas beschikbaar vanaf rank 5 Trainer.<br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="bank">
					<h2>Bank</h2>
					Functies van de bank:
					<ul>
						<li>Silver op de bank zetten.</li>
						<li>Silver van de bank halen.</li>
						<li>Geld overmaken naar een andere speler.</li>
					</ul>
					<strong>Tip:</strong> Maak als je veel silver cash hebt het over naar de bank. Dan kan niemand het stelen. Verdeel het wel over de dag, niet dat je beurten al op zijn in de ochtend.<br /><br />
					<span class="smalltext">Een premiumlid kan 5x per dag geld storten naar de bank, een normaal lid kan dit 3x.</span><br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="boat">
					<h2>Bootverhuur</h2>
					Je kunt een boot huren om naar een andere wereld te varen.<br />
					Dit kost geen tijd, je bent er dus in minder dan 1 seconde!<br />
					Je betaald tickets voor al je Pok&eacute;mon + jezelf.<br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="houseseller">
					<h2>Huizenverkoper</h2>
					Koop hier een huis op '.GLOBALDEF_SITENAME.'. Hoe groter het huis, hoe meer Pok&eacute;mon erin kunnen verblijven.<br />
					Je kunt je huis niet verkopen en je kunt alleen maar beter kopen dan dat je al hebt.<br /><br />
					Als je een Villa hebt (het grootste huis) verdwijnt huizenverkoper uit het menu.<br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="transferlist">
					<h2>Transferlijst</h2>
					Hier kun je een Pok&eacute;mon kopen.<br />
					Hoe hoger jouw rank is, hoe hoger de level is wat je van de transferlijst kunt kopen.<br />
					Als je een Pok&eacute;mon hebt gekocht zit die in je huis.<br /><br />
					<span class="smalltext">De transferlijst is beschikbaar vanaf rank 4 Casual.</span><br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="daycare">
					<h2>Daycare</h2>
					Hier kun je Pok&eacute;mon heenbrengen en de daycare probeert ze dan te trainen.<br />
					Soms krijgt een Pok&eacute;mon er zelfs 2 levels per dag bij!<br /><br />
					<strong>Ei:</strong><br />
					Soms krijgen je Pok&eacute;mon een kindje (een ei). Je kunt deze halen bij de daycare als er 1 is.<br />
					Er kan alleen een ei komen als 2 van dezelfde Pok&eacute;mon in de daycare zitten. Oftewel:<br />
					Als je er een Pikachu en Pikachu heenbrengt, krijg je een ei met Pichu.<br />
					Een pikachu en Raichu zal niet werken.<br /><br />
					Als beide Pok&eacute;mon shiny zijn, zal de Pok&eacute;mon die uit het eitje komt ook shiny zijn.<br /><br />
					Het werkt overigens alleen bij Pok&eacute;mon die <strong>niet</strong> zeldzaam zijn.<br /><br />
					<strong>Tip:</strong> een ditto kan goed van pas komen.<br /><br />
					<span class="smalltext">Een premiumlid mag 2 Pok&eacute;mon bij de daycare laten, een normaal lid mag 1 Pok&eacute;mon.<br />
					De daycare is beschikbaar vanaf rank 4 Casual.</span><br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="namespecialist">
					<h2>Naam specialist</h2>
					De namenspecialist kan de naam van een Pok&eacute;mon veranderen.<br />
					Het kost <img src="images/icons/silver.gif" title="Silver" style="margin-bottom: -3px;" /> 40. En je naam is veranderd naar de naam wat jij wilt.<br />
					<strong>Let op:</strong> de naam mag geen scheldwoord zijn.<br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="shinyspecialist">
					<h2>Shiny specialist</h2>
					De shiny specialist kan met voldoende Gold een Pok&eacute;mon shiny maken. Elke Pok&eacute;mon heeft een bepaald aantal gold nodig om shiny te worden.<br />Door de impact van het glimmende Gold \'evolueert\' de Pok&eacute;mon naar een shiny Pok&eacute;mon.<br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="jail">
					<h2>Gevangenis</h2>
					<strong>In gevangenis</strong><br />
					Soms kom je in de gevangenis omdat je iets probeert te stelen op het werk, of je doet iets anders fouts.<br />
					Je kunt jezelf dan uitkopen of je moet hopen dat iemand anders je uit bust (bevrijd).<br /><br />
					<strong>Op bezoek</strong><br />
					Je kunt ook op bezoek gaan in de gevangenis en iemand uitkopen of uitbusten. Je hebt toch wel wat over andere leden?<br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="gamble">
					<h2>Gokken</h2>
					<strong>Gooi een munt</strong><br />
					Een simpel spelletje waarbij je moet gokken of de munt op kop of munt valt.<br />
					Als je het goed hebt win je de inzet dubbel terug, bij fout ben je het silver kwijt.<br /><br />
					<strong>Wie is het quiz</strong><br />
					Bij de \'wie is het quiz\' moet je raden hoe de Pok&eacute;mon heet.<br /><br />
					<span class="smalltext">Elke speler kan dit 1 keer per uur doen.</span><br /><br />
					<strong>Geluksrad</strong><br />
					Geluksrad is een extra functie om gratis wat te krijgen. Draai aan het rad en zie wat je prijs is.<br /><br />
					<span class="smalltext">Een premiumlid mag per dag 3x aan het rad draaien, een normaal lid kan dat 1x.</span><br /><br />
					<strong>Loterij</strong><br />
					Loterij is een erg leuke variant om te gokken.<br />
					De leden kunnen per loterij maximaal 10 kaartjes kopen.<br />
					De loterij word verloot op de tijd wat er staat. De winnaar krijgt al het ingelegde geld.<br /><br />
					<span class="smalltext">De premiumloterij is alleen toegankelijk voor Premium leden. Hierbij win je het ingelegde geld + een Rare candy.</span><br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				</div>';
    }

    ######################## POKEMON INFO ########################
    elseif($_GET['category'] == 'pokemon-info'){
        #Screen
        $txt['pagetitle'] .= ' - Pok&eacute;mon informatie';
        $txt['choosepokemon'] = 'Kies Pok&eacute;mon:';
        $txt['choose_a_pokemon'] = 'Kies een Pok&eacute;mon.';
        $txt['not_rare'] = 'niet zeldzaam';
        $txt['a_bit_rare'] = 'een beetje zeldzaam';
        $txt['very_rare'] = 'erg zeldzaam';
        $txt['not_a_favorite_place'] = 'Heeft geen bepaalde lievelingsplek.';
        $txt['is_his_favorite_place'] = 'is zijn lievelingsplek.';
        $txt['is'] = 'is';
        $txt['lives_in'] = 'Leeft in';
        $txt['how_much_1'] = 'Er zijn';
        $txt['how_much_2'] = 'in het spel.';
        $txt['attack&evolution'] = 'Attack & Evolutie';
        $txt['no_attack_or_evolve'] = 'Evolueert niet en leert geen aanval.';
        $txt['level'] = 'Level';
        $txt['evolution'] = 'Evolutie';
    }
    elseif($_GET['category'] == 'attack-info'){
        #Screen
        $txt['pagetitle'] .= ' - Aanval informatie';
        $txt['#'] = '#';
        $txt['name'] = 'Naam';
        $txt['type'] = 'Type';
        $txt['att'] = 'Att';
        $txt['acc'] = 'Acc';
        $txt['effect'] = 'Effect';
        $txt['ready'] = 'Klaar';
    }
}

######################## STATISTICS ########################
elseif($page == 'statistics'){
    #Screen
    $txt['pagetitle'] = 'Statistics';
    $txt['top6_pokemon_title'] = 'Top team van '.GLOBALDEF_SITENAME.'<br /><span class="smalltext">Gebasseerd op alle stats.</span>';
    $txt['game_data'] = 'Spel gegevens';
    $txt['users_total'] = 'Leden aantal:';
    $txt['silver_in_game'] = 'Silver in spel:';
    $txt['pokemon_total'] = 'Aantal Pok&eacute;mon:';
    $txt['matches_played'] = 'Gespeelde gevechten:';
    $txt['top5_silver_users'] = 'Top 5 meeste silver';
    $txt['#'] = '#';
    $txt['who'] = 'Wie';
    $txt['silver'] = 'Silver';
    $txt['top5_pokemon_total'] = 'Top 5 aantal Pok&eacute;mon';
    $txt['number'] = 'Aantal';
    $txt['top5_matches_played'] = 'Top 5 gevechten<br /><span class="smalltext">Gevechten gewonnen - gevechten verloren.</span>';
    $txt['matches'] = 'Gevechten';
    $txt['top10_new_users'] = 'Top 10 nieuwste leden';
    $txt['when'] = 'Wanneer';
}
######################## RANKINGLIST ########################
elseif($page == 'rankinglist'){
    #Screen
    $txt['pagetitle'] = 'Rankinglist';
    $txt['#'] = '#';
    $txt['username'] = 'Gebruikersnaam';
    $txt['country'] = 'Land';
    $txt['rank'] = 'Rank';
    $txt['status'] = 'Status';
    $txt['online'] = 'Online';
    $txt['offline'] = 'Offline';
}

######################## CONTACT ########################
elseif($page == 'contact'){
    #Alerts
    $txt['alert_email_to_unknown'] = $_POST['sendto'].' is ongeldig.';
    $txt['alert_no_name'] = 'U heeft geen naam ingevuld.';
    $txt['alert_no_email'] = 'Geen email adres ingevuld.';
    $txt['alert_email_incorrect_signs'] = 'E-mail adres is niet geldig.';
    $txt['alert_no_subject'] = 'U heeft geen onderwerp ingevuld.';
    $txt['alert_no_message'] = 'U heeft geen bericht getypt.';
    $txt['success_contact'] = 'De e-mail is succesvol verzonden.';

    #Screen
    $txt['pagetitle'] = 'Contact';
    $txt['title_text'] = 'Indien u vragen heeft kunt u middels het onderstaande formulier contact met ons opnemen.';
    $txt['email_to'] = 'E-mail naar';
    $txt['your_name'] = 'Uw naam';
    $txt['your_email'] = 'Uw e-mail';
    $txt['subject'] = 'Onderwerp';
    $txt['message'] = 'Bericht';
    $txt['button'] = 'Stuur mail';
}

######################## ACTIVATE ########################
elseif($page == 'activate'){
    #Alerts
    $txt['alert_no_username'] = 'Geen Gebruikersnaam ingevuld.';
    $txt['alert_username_too_short'] = 'Gebruikersnaam te kort.';
    $txt['alert_username_too_long'] = 'Gebruikersnaam te lang.';
    $txt['alert_username_dont_exist'] = $_POST['inlognaam'].' bestaat niet.';
    $txt['alert_no_activatecode'] = 'Geen activatiecode ingevuld.';
    $txt['alert_activatecode_too_short'] = 'Activatiecode is te kort.';
    $txt['alert_activatecode_too_long'] = 'Activatiecode is te lang.';
    $txt['alert_guardcore_invalid'] = 'Beveiligingscode incorrect.';
    $txt['alert_already_activated'] = $_POST['inlognaam'].' is al geactiveerd!';
    $txt['alert_activatecode_wrong'] = 'Verkeerde code ingevuld.';
    $txt['alert_username_wrong'] = 'Verkeerde Gebruikersnaam ingevuld.';
    $txt['success_activate'] = $_POST['inlognaam'].' is succesvol geactiveerd!';

    #Screen
    $txt['pagetitle'] = 'Activate account';
    $txt['title_text'] = 'Hier kunt u uw account activeren.';
    $txt['username'] = 'Gebruikersnaam';
    $txt['activatecode'] = 'Activatiecode';
    $txt['captcha'] = 'Beveiligingscode plaatje.';
    $txt['guardcode'] = 'Beveiligingscode';
    $txt['button'] = 'Activeer nu';
}

######################## FORGOT USERNAME ########################
elseif($page == 'forgot-username'){
    #Alerts
    $txt['alert_no_email'] = 'Geen e-mail adres ingevuld.';
    $txt['alert_email_dont_exist'] = 'E-mail bestaat niet.';
    $txt['alert_guardcore_invalid'] = 'Beveiligingscode incorrect.';
    $txt['success_forgot_username'] = 'De e-mail is succesvol verstuurd!';

    #Screen
    $txt['pagetitle'] = 'Forgot username';
    $txt['email'] = 'E-mail adres';
    $txt['captcha'] = 'Beveiligingscode plaatje.';
    $txt['guardcode'] = 'Beveiligingscode';
    $txt['title_text'] = 'Uw inlognaam vergeten?<br />Hier kunt u uw inlognaam opvragen, hij word dan opgestuurd naar uw e-mail adres.';
    $txt['button'] = 'Verstuur mail';
}

######################## FORGOT PASSWORD ########################
elseif($page == 'forgot-password'){
    #Alerts
    $txt['alert_no_username'] = 'Geen Gebruikersnaam ingevuld.';
    $txt['alert_username_too_short'] = 'Gebruikersnaam te kort.';
    $txt['alert_username_too_long'] = 'Gebruikersnaam te lang.';
    $txt['alert_no_email'] = 'Geen e-mail adres ingevuld.';
    $txt['alert_guardcore_invalid'] = 'Beveiligingscode incorrect.';
    $txt['alert_username_dont_exist'] = 'Gebruikersnaam bestaat niet.';
    $txt['alert_email_dont_exist'] = 'E-mail adres bestaat niet.';
    $txt['alert_wrong_combination'] = 'Verkeerde combinatie';
    $txt['success_forgot_password'] = 'De e-mail is succesvol verstuurd!';

    #Screen
    $txt['pagetitle'] = 'Forgot password';
    $txt['title_text'] = 'Uw wachtwoord vergeten?<br /> Hier kunt u uw wachtwoord vernieuwen, u krijgt een nieuwe toegestuurd naar uw e-mail adres.';
    $txt['username'] = 'Gebruikersnaam';
    $txt['email'] = 'E-mail adres';
    $txt['captcha'] = 'Beveiligingscode plaatje.';
    $txt['guardcode'] = 'Beveiligingscode';
    $txt['button'] = 'Stuur mail';
}

######################## ACCOUNT OPTIONS ########################
elseif($page == 'account-options'){
    #Screen
    $txt['pagetitle'] = 'Account opties';
    #Titles
    $txt['link_subpage_personal'] = 'Persoonlijk';
    $txt['link_subpage_password'] = 'Wachtwoord';
    $txt['link_subpage_profile'] = 'Profiel';
    $txt['link_subpage_restart'] = 'Opnieuw beginnen';

    if($_GET['category'] == 'personal'){
        #Alerts general
        $txt['alert_not_enough_gold'] = 'Je hebt niet genoeg gold.';
        $txt['alert_no_username'] = 'Geen username ingevuld.';
        $txt['alert_username_too_short'] = 'Gebruikersnaam te kort.';
        $txt['alert_username_too_long'] = 'Gebruikersnaam te lang.';
        $txt['alert_username_already_taken'] = 'Gebruikersnaam bestaat al.';
        $txt['alert_firstname_too_long'] = 'Voornaam te lang.';
        $txt['alert_lastname_too_long'] = 'Achternaam te lang.';
        $txt['alert_character_invalid'] = 'Persoon onbekend.';
        $txt['alert_seeteam_invalid'] = 'Team zichtbaar onbekend.';
        $txt['alert_seebuddies_invalid'] = 'Buddies zichtbaar onbekend.';
        $txt['alert_seesnow_invalid'] = 'Buddies zichtbaar onbekend.';
        $txt['alert_seebadges_invalid'] = 'Badges zichtbaar onbekend.';
        $txt['alert_advertisement_invalid'] = 'Reclame onbekend.';
        $txt['alert_duel_invalid'] = 'Duel onbekend.';
        $txt['success_modified'] = 'Succesvol gewijzigd!';

        #Screen general
        $txt['pagetitle'] .= ' - Persoonlijk';
        $txt['buy_premium_here'] = 'Bestel hier Premium!';
        $txt['days_left'] = 'dagen resterend.';
        $txt['username'] = 'Gebruikersnaam:';
        $txt['cost_15_gold'] = 'Dit kost 15 gold.';
        $txt['firstname'] = 'Voornaam:';
        $txt['lastname'] = 'Achternaam:';
        $txt['youtube'] = 'Youtube link:';
        $txt['country'] = 'Land:';
        $txt['character'] = 'Persoon:';
        $txt['premium_days'] = 'Premiumdagen:';
        $txt['advertisement'] = 'Reclame:';
        $txt['advertisement_info'] = '(Voor elke 24 uur reclame actief verdien je 5 gold.)';
        $txt['alert_not_premium'] = 'Je bent geen premiumlid.';
        $txt['on'] = 'Aan';
        $txt['off'] = 'Uit';
        $txt['team_on_profile'] = 'Team op profiel:';
        $txt['buddies_on_profile'] = 'Buddies op profiel:';
        $txt['snow_on'] = 'Sneeuw:';
        $txt['music_on'] = 'Muziek op '.GLOBALDEF_SITENAME.':';
        $txt['yes'] = 'Ja';
        $txt['no'] = 'Nee';
        $txt['badges_on_profile'] = 'Badges op profiel:';
        $txt['alert_dont_have_badgebox'] = 'Je hebt geen badge doos.';
        $txt['duel_invitation'] = 'Duel uitnodiging:';
        $txt['alert_not_yet_available'] = 'Nog niet beschikbaar.';
        $txt['available_rank_senior'] = 'Kan vanaf rank Senior.';
        $txt['battleScreen'] = 'Battle scherm grafisch:';
        $txt['button_personal'] = 'Wijzig profiel';
    }
    elseif($_GET['category'] == 'password'){
        #Alerts password
        $txt['alert_all_fields_required'] = 'Niet alle velden ingevuld.';
        $txt['alert_old_new_password_thesame'] = 'Je nieuwe wachtwoord is het zelfde als je huidige.';
        $txt['alert_old_password_wrong'] = 'Je huidige wachwoord is niet goed.';
        $txt['alert_password_too_short'] = 'Wachtwoord is te kort';
        $txt['alert_new_controle_password_wrong'] = 'Je nieuwe wachtwoord en de controle wachtwoord zijn niet gelijk.';
        $txt['success_password'] = 'Je wachtwoord is succesvol veranderd.';

        #Screen password
        $txt['pagetitle'] .= ' - Verander wachtwoord';
        $txt['new_password'] = 'Nieuw wachtwoord:';
        $txt['new_password_again'] = 'Nieuw wachtwoord nogmaals:';
        $txt['password_now'] = 'Huidig wachtwoord:';
        $txt['button_password'] = 'Wijzig wachtwoord';
    }
    elseif($_GET['category'] == 'profile'){
        #Alerts profile
        $txt['success_profile'] = 'Je profiel is succesvol veranderd.';

        #Screen profile
        $txt['pagetitle'] .= ' - Pimp je profiel';
        $txt['link_text_effects'] = '<u><a href="codes.php?category=profile" class="colorbox" title="Text effects for profile"><b>Hier</b></a></u> kun je zien hoe je tekst effecten moet toepassen of plaatjes invoegen.';
        $txt['button_profile'] = 'Wijzig profiel';
    }
    elseif($_GET['category'] == 'restart'){
        #Alerts restart
        $txt['alert_no_password'] = 'Geen wachtwoord ingevuld.';
        $txt['alert_password_wrong'] = 'Onjuist wachtwoord ingevuld.';
        $txt['alert_no_beginworld'] = 'Geen beginwereld gekozen.';
        $txt['alert_world_invalid'] = 'Beginwereld onbekend.';
        $txt['success_restart'] = 'Succesvol overnieuw begonnen!';
        $txt['alert_when_restart'] = 'Je kunt over
										  <strong><span id=uur3></span></strong> uren
										  <strong><span id=minuten3> </span>&nbsp;minuten</strong> en 
										  <strong><span id=seconden3></span>&nbsp;seconden</strong> opnieuw beginnen.';

        #Screen restart
        $txt['pagetitle'] .= ' - Overnieuw beginnen';
        $txt['restart_title_text'] = '<center>Vul hieronder je wachtwoord in en kies je wereld waarin je wilt beginnen.<br /><br />
										
										Al je Pok&eacute;mon, items, silver en rankingpoints worden verwijderd.<br />
										<strong>Dit kan niet ongedaan worden gemaakt.</strong></center>';
        $txt['password_security'] = 'Wachtwoord beveiliging:';
        $txt['button_restart'] = 'Begin opnieuw';
    }
}

######################## PROMOTION ########################
elseif($page == 'promotion'){
    $txt['pagetitle'] = 'Promotie voor goud';
    $txt['promotion_text'] = '<p>Je kunt ervoor zorgen dat '.GLOBALDEF_SITENAME.' meer leden krijgt en je word er zelf nog beter van ook!<br />
		Voor elk lid die zich aanmeld en die bij referer jouw inlognaam opgeeft krijg jij <img src="images/icons/gold.png" title="Gold" style="margin-bottom:-3px;" />150.<br /><br />
		Tip: Je kan promoten via skype, facebook, mail enzovoort!<br /><br />
		Jouw link waar iemand zich gemakkelijk mee kan aanmelden is:<br /><br /><strong>'.GLOBALDEF_SITEPROTOCOL.'://www.'.GLOBALDEF_SITEDOMAIN.'/index.php?page=register&referer='.$_SESSION['naam'].'</strong></p>';
}

######################## MODIFY ORDER ########################
elseif($page == 'modify-order'){
    #Screen
    $txt['pagetitle'] = 'Wijzig Pok&eacute;mon volgorde';
    $txt['modify_order_text'] = 'Hier kunt u uw Pok&eacute;mon volgorde wijzigen.<br />
									 Sleep de Pok&eacute;mon naar de desbetreffende positie.';
}

######################## EXTENDED ########################
elseif($page == 'extended'){
    #Screen
    $txt['pagetitle'] = 'Uitgebreide Pok&eacute;mon informatie';
    $txt['catched_with'] = 'Gevangen met een';
    $txt['pokemon'] = 'Pok&eacute;mon:';
    $txt['attack_points'] = 'Attack:';
    $txt['clamour_name'] = 'Roepnaam:';
    $txt['defence_points'] = 'Defence:';
    $txt['type'] = 'Type:';
    $txt['level'] = 'Level:';
    $txt['speed_points'] = 'Speed:';
    $txt['spc_attack_points'] = 'Spc. Attack:';
    $txt['mood'] = 'Karakter:';
    $txt['spc_defence_points'] = 'Spc. Defence:';
    $txt['attacks'] = 'Aanvallen:';
    $txt['egg_will_hatch_in'] = 'Ei komt uit in:';
    $txt['begin_pokemon'] = 'Begin Pok&eacute;mon';
}

######################## SELL ########################
elseif($page == 'sell'){
    #Screen
    $txt['pagetitle'] = 'Verkoop';
    $txt['colorbox_text'] = 'Open this window again and this message will still be here.';
    $txt['title_text_1'] = 'Je kunt maximaal';
    $txt['title_text_2'] = 'Pok&eacute;mon op de transferlijst zetten die in je huis zijn.<br />
									 Momenteel heb je';
    $txt['title_text_3'] = 'Pok&eacute;mon op de transferlijst staan.';
    $txt['no_pokemon_in_house'] = 'Er zijn geen Pok&eacute;mon in je huis.';
    $txt['#'] = '#';
    $txt['pokemon'] = 'Pok&eacute;mon';
    $txt['clamour_name'] = 'Naam';
    $txt['level'] = 'Level';
    $txt['sell'] = 'Verkoop';
    $txt['go_to_transferlist'] = 'Ga naar transferlijst';
}

######################## RELEASE ########################
elseif($page == 'release'){
    #Alerts
    $txt['alert_itemplace'] = 'Let op: U heeft geen itemplek over, dus u krijgt uw ball niet terug als u nu een Pok&eacute;mon vrijlaat.';
    $txt['alert_not_your_pokemon'] = 'Dit is niet je Pok&eacute;mon.';
    $txt['alert_beginpokemon'] = 'Dit is je begin Pok&eacute;mon, die kun je niet vrijlaten.';
    $txt['alert_no_pokemon_selected'] = 'U heeft geen Pok&eacute;mon geselecteerd.';
    $txt['success_release'] = 'U heeft uw Pok&eacute;mon succesvol vrijgelaten.';

    #Screen
    $txt['pagetitle'] = 'Laat Pok&eacute;mon vrij';
    $txt['title_text'] = 'Hier je jouw Pok&eacute;mon vrijlaten.<br />
									  De Pok&eacute;ball waarmee de Pok&eacute;mon is gevangen krijg je weer terug.<br />
									  <div class="blue"><strong>Let op!</strong> Dit kan niet ongedaan worden gemaakt.</div>';
    $txt['pokemon_team'] = 'Pok&eacute;mon team';
    $txt['#'] = '#';
    $txt['pokemon'] = 'Pok&eacute;mon';
    $txt['clamour_name'] = 'Roepnaam';
    $txt['level'] = 'Level';
    $txt['release'] = 'Vrijlaten';
    $txt['alert_no_pokemon_in_hand'] = 'Er zijn geen Pok&eacute;mon bij je.';
    $txt['button'] = 'Vrijlaten';
    $txt['pokemon_at_home'] = 'Pok&eacute;mon in je huis';
    $txt['alert_no_pokemon_at_home'] = 'Er zijn geen Pok&eacute;mon in je huis.';
}

######################## ITEMS ########################
elseif($page == 'items'){
    #Alerts
    $txt['alert_no_amount'] = 'Geen aantal gekozen.';
    $txt['alert_too_much_items_selected'] = 'Zoveel heb je niet van dat item.';
    $txt['success_items'] = 'Je hebt '.$_POST['amount'].'x '.$_POST['name'].' verkocht voor';

    #Screen
    $txt['pagetitle'] = 'Items';
    $txt['title_text_1'] = 'Je hebt nog ';
    $txt['title_text_2'] = 'item plaatsen over.';
    $txt['name'] = 'Naam';
    $txt['number'] = 'Aantal';
    $txt['sellprice'] = 'Verkoopprijs';
    $txt['sell'] = 'Verkoop';
    $txt['use'] = 'Gebruik';
    $txt['balls'] = 'Balls';
    $txt['potions'] = 'Potions';
    $txt['items'] = 'Items';
    $txt['badge_case_title'] = 'Een doos voor je badge verzameling.';
    $txt['box_title'] = 'You can save your items in the';
    $txt['spc_items'] = 'Special items';
    $txt['stones'] = 'Stones';
    $txt['tm'] = 'TM';
    $txt['hm'] = 'HM';
    $txt['button_use'] = 'gebruiken';
    $txt['button_sell'] = 'verkopen';
}

######################## ITEMS ########################
elseif($page == 'store'){
    #Alerts
    $txt['alert_no_amount'] = 'Geen aantal gekozen.';
    $txt['alert_too_much_items_selected'] = 'Zoveel heb je niet van dat item.';
    $txt['success_items'] = 'Je hebt '.$_POST['amount'].'x '.$_POST['name'].' verkocht voor';

    #Screen
    $txt['pagetitle'] = 'Store';
    $txt['name'] = 'Naam';
    $txt['number'] = 'Aantal';
    $txt['currency'] = 'Gold/Silver';
    $txt['sellprice'] = 'Verkoopprijs';
    $txt['sell'] = 'Verkoop';
    $txt['use'] = 'Gebruik';
    $txt['balls'] = 'Balls';
    $txt['potions'] = 'Potions';
    $txt['items'] = 'Items';
    $txt['badge_case_title'] = 'Een doos voor je badge verzameling.';
    $txt['box_title'] = 'You can save your items in the';
    $txt['spc_items'] = 'Special items';
    $txt['stones'] = 'Stones';
    $txt['tm'] = 'TM';
    $txt['hm'] = 'HM';
    $txt['button_buy'] = 'Kopen';
    $txt['button_cancel'] = 'Intrekken';
}

######################## BADGES ########################
elseif($page == 'badges'){
    #Screen
    $txt['pagetitle'] = 'Badge doos';
    $txt['badges'] = 'Badges';
    $txt['alert_dont_have_badgebox'] = 'Je hebt geen badge doos.';
}

######################## HOUSE ########################
elseif($page == 'house'){
    #Alerts
    $txt['alert_not_your_pokemon'] = 'Dit is niet jouw Pok&eacute;mon.';
    $txt['alert_house_full'] = 'Je huis is vol.';
    $txt['success_bring'] = 'Je hebt je Pok&eacute;mon naar je huis gebracht.';
    $txt['alert_hand_full'] = 'Je hebt al 6 Pok&eacute;mon in je hand.';
    $txt['alert_pokemon_on_transferlist'] = 'Deze Pok&eacute;mon staat op de transferlijst.';
    $txt['success_get'] = 'Je hebt je Pok&eacute;mon succesvol opgehaald.';

    #Screen
    $txt['pagetitle'] = 'Je huis';
    $txt['title_text_1'] = 'Momenteel heb je een';
    $txt['title_text_2'] = 'hier kunnen';
    $txt['title_text_3'] = 'Pok&eacute;mon in verblijven.<br><br>
									  * Pok&eacute;mon wegbrengen, daar kun je de Pok&eacute;mon naar je huis laten gaan.<br>
									  * Pok&eacute;mon ophalen, daar kun je de Pok&eacute;mon terughalen naar je hand.<br><br>
									  Je kunt pas een Pok&eacute;mon ophalen als je minimaal 1 plaats vrij hebt in je hand.';
    $txt['pokemon_bring_away'] = 'Pok&eacute;mon wegbrengen';
    $txt['pokemon_pick_up'] = 'Pok&eacute;mon ophalen';
    $txt['box'] = 'kartonnen doos';
    $txt['little_house'] = 'klein huis';
    $txt['normal_house'] = 'normaal huis';
    $txt['big_house'] = 'villa';
    $txt['hotel'] = 'hotel';
    $txt['places_over'] = 'plaatsen vrij';
    $txt['#'] = '#';
    $txt['clamour_name'] = 'Roepnaam';
    $txt['level'] = 'Level';
    $txt['bring_away'] = 'Thuis brengen';
    $txt['take'] = 'Ophalen';
    $txt['button_take'] = 'Ophalen';
    $txt['button_bring'] = 'Breng thuis';
    $txt['empty'] = 'Leeg';
}

######################## POKEDEX ########################
elseif($page == 'pokedex'){
    #Screen
    $txt['pagetitle'] = 'Pokedex';
    $txt['seen'] = 'Gezien';
    $txt['had'] = 'Gehad';
    $txt['have'] = 'Bezit';
    $txt['#'] = '#';
    $txt['pokemon'] = 'Pok&eacute;mon';
    $txt['name'] = 'Naam';
    $txt['type'] = 'Type';
    $txt['status'] = 'Status';
}

######################## INBOX ########################
elseif($page == 'inbox'){
    #Alerts
    $txt['alert_nothing_selected'] = 'Je hebt niets geselecteerd.';
    $txt['success_deleted'] = 'Je hebt je berichten succesvol verwijderd.';

    #Screen
    $txt['pagetitle'] = 'Inbox';
    $txt['new_check'] = 'New';
    $txt['subject'] = 'Onderwerp';
    $txt['username'] = 'Van speler';
    $txt['status'] = 'Status';
    $txt['online'] = 'Online';
    $txt['offline'] = 'Offline';
    $txt['date-time'] = 'Datum/ Tijd';
    $txt['no_messages'] = 'Geen berichten';
    $txt['button'] = 'Delete';
}

######################## SEND MESSAGE ########################
elseif($page == 'send-message'){
    #Alerts
    $txt['alert_no_receiver'] = 'Geen ontvanger ingevuld.';
    $txt['alert_inbox_full'] = 'Inbox van '.$_POST['ontvanger'].' is vol.';
    $txt['alert_receiver_blocked'] = 'Je hebt '.$_POST['ontvanger'].' geblokkeerd.';
    $txt['alert_has_blocked_you'] = $_POST['ontvanger'].' heeft jou geblokkeerd.';
    $txt['alert_communication_ban'] = 'Je kan geen bericht sturen naar '.$_POST['ontvanger'].' vanwege een communicatieban.';
    $txt['alert_message_to_yourself'] = 'Je kunt geen bericht naar jezelf sturen.';
    $txt['alert_username_dont_exist'] = $_POST['ontvanger'].' bestaat niet.';
    $txt['alert_no_subject'] = 'Geen onderwerp ingevuld.';
    $txt['alert_subject_wrong_signs'] = 'Het onderwerp mag geen tekens bevatten.';
    $txt['alert_text_wrong_signs'] = 'Het bericht mag geen < bevatten.';
    $txt['alert_no_message'] = 'Geen bericht getypt.';
    $txt['success_send_message'] = 'Bericht is succesvol verstuurd naar '.$_POST['ontvanger'].'.';

    #Screen
    $txt['pagetitle'] = 'Verstuur een bericht';
    $txt['link_text_effects'] = '<u><a href="codes.php?category=message" class="colorbox" title="Text effects for profile"><b>Hier</b></a></u> kun je zien hoe je tekst effecten moet toepassen of plaatjes invoegen.';
    $txt['name_receiver'] = 'Naam ontvanger:';
    $txt['subject'] = 'Onderwerp:';
    $txt['more_emoticons'] = 'Voor meer emoticons <a href=\'index.php?page=area-market\'><strong>Klik hier</strong></a>';
    $txt['button'] = 'Stuur bericht!';
}

######################## SEND MESSAGE ########################
elseif($page == 'read-message'){
    #Alerts
    $txt['alert_link_incorrect'] = 'Link ongeldig.';
    $txt['alert_not_your_message'] = 'Dit bericht is niet voor jou.';
    $txt['alert_inbox_full'] = 'Inbox van deze speler is vol.';
    $txt['alert_receiver_blocked'] = 'Je kunt niets terugsturen, je hebt deze speler geblokkeerd.';
    $txt['alert_has_blocked_you'] = 'Je kunt niets terugsturen, deze speler heeft jou geblokkeerd.';
    $txt['alert_text_wrong_signs'] = 'Het bericht mag geen < bevatten.';
    $txt['alert_no_message'] = 'Geen bericht getypt.';
    $txt['success_send_message'] = 'Bericht is succesvol verstuurd naar';

    #Screen
    $txt['pagetitle'] = 'Lees een bericht';
    $txt['from_player'] = 'Van Speler:';
    $txt['subject'] = 'Onderwerp:';
    $txt['respond'] = 'Stuur bericht terug';
    $txt['inbox'] = 'Inbox';
    $txt['block'] = 'Block';
    $txt['pagetitle'] = 'Verstuur een bericht';
    $txt['link_text_effects'] = '<u><a href="codes.php?category=message" class="colorbox" title="Text effects for profile"><b>Hier</b></a></u> kun je zien hoe je tekst effecten moet toepassen of plaatjes invoegen.';
    $txt['more_emoticons'] = 'Voor meer emoticons <a href=\'index.php?page=area-market\'><strong>Klik hier</strong></a>';
    $txt['button'] = 'Stuur bericht!';
}

######################## EVENTS ########################
elseif($page == 'events'){
    #Alerts
    $txt['alert_nothing_selected'] = 'Je hebt niets geselecteerd.';
    $txt['alert_more_events_deleted'] = 'Gebeurtenissen succesvol verwijderd.';
    $txt['alert_one_event_deleted'] = 'Gebeurtenis succesvol verwijderd.';

    #Screen
    $txt['pagetitle'] = 'Gebeurtenissen';
    $txt['date-time'] = 'Datum / tijd';
    $txt['no_events'] = 'Geen gebeurtenissen';
    $txt['button'] = 'Delete';
    $txt['event'] = 'Gebeurtenis';
}

######################## BUDDYLIST ########################
elseif($page == 'buddylist'){
    #Alerts
    $txt['success_deleted'] = $_POST['deletenaam'].' is niet meer je buddy.';
    $txt['alert_buddy_not_yourself'] = 'Je kunt jezelf niet als buddy toevoegen.';
    $txt['alert_username_dont_exist'] = 'Gebruikersnaam bestaat niet.';
    $txt['alert_already_buddy'] = $_POST['buddynaam'].' is al een buddy van je of je verzoek wacht nog op akkoord.';
    $txt['alert_is_blocked'] = $_POST['buddynaam'].' staat in je blocklist.';
    $txt['success_add'] = $_POST['buddynaam'].' heeft een buddy verzoek ontvangen.';
    $txt['alert_receiver_blocked'] = $_POST['buddynaam'].' heeft je geblokkeerd, je kan hem/haar niet toevoegen als buddy.';
    $txt['alert_receiver_blocked'] = $_POST['buddynaam'].' heeft je geblokkeerd, je kan hem/haar niet toevoegen als buddy.';
    $txt['alert_communication_ban'] = 'Je kan geen verzoek sturen naar '.$_POST['buddynaam'].' vanwege een communicatieban.';

    #Screen
    $txt['pagetitle'] = 'Buddylijst';
    $txt['title_text'] = '<img src="images/icons/groep.png" width="16" height="16" /> <strong>Stuur hier je buddy verzoeken naar andere spelers.</strong>';
    $txt['username'] = 'Speelnaam:';
    $txt['#'] = '#';
    $txt['country'] = 'Land';
    $txt['status'] = 'Status';
    $txt['actions'] = 'Acties';
    $txt['offline'] = 'Offline';
    $txt['online'] = 'Online';
    $txt['send_message'] = 'Stuur bericht';
    $txt['donate_silver'] = 'Doneer silver';
    $txt['delete_buddy'] = 'Verwijder buddy';
    $txt['no_buddys'] = 'Je hebt geen buddy\'s.';
    $txt['button'] = 'Voeg buddy toe';
}

######################## POKEMON INFO ########################
elseif($page == 'blocklist'){
    #Alerts
    $txt['success_deleted'] = $_POST['deletenaam'].' staat niet meer in je blocklist.';
    $txt['alert_block_yourself'] = 'Je kunt jezelf niet blokkeren.';
    $txt['alert_unknown_username'] = 'Gebruikersnaam onbekend.';
    $txt['alert_already_in_blocklist'] = $_POST['blocknaam'].' is al geblokkeerd.';
    $txt['alert_is_your_buddy'] = $_POST['blocknaam'].' is je buddy al.';
    $txt['alert_admin_block'] = 'Je kunt geen admin blokkeren.';
    $txt['success_blocked'] = $_POST['blocknaam'].' is succesvol geblokkeerd.';

    #Screen
    $txt['pagetitle'] = 'Blocklijst';
    $txt['title_text'] = '<img src="images/icons/blokkeer.png" border="0" /> <strong>Speler Blokkeren.</strong><br />Als je een speler hebt geblokkeerd kun je die geen berichten meer sturen, en diegene jou ook niet.';
    $txt['username'] = 'Gebruikersnaam:';
    $txt['button'] = 'Blokkeer speler';
    $txt['*'] = '*';
    $txt['#'] = '#';
    $txt['country'] = 'Land';
    $txt['status'] = 'Status';
    $txt['actions'] = 'Acties';
    $txt['offline'] = 'Offline';
    $txt['online'] = 'Online';
    $txt['block_delete'] = 'Verwijder';
    $txt['nobody_blocked'] = 'Je hebt niemand geblokkeerd.';
}

######################## SEARCH USER ########################
elseif($page == 'search-user'){
    #Screen
    $txt['pagetitle'] = 'Zoek een speler';
    $txt['title_text'] = '<img src="images/icons/groep_magnify.png" border="0" /> <strong>Zoek hier een speler.</strong>';
    $txt['username'] = 'Gebruikersnaam';
    $txt['#'] = '#';
    $txt['country'] = 'Land';
    $txt['rank'] = 'Rank';
    $txt['status'] = 'Status';
    $txt['offline'] = 'Offline';
    $txt['online'] = 'Online';
    $txt['button'] = 'Zoek';
}

######################## PROFILE ########################
elseif($page == 'profile'){
    #Screen
    $txt['pagetitle'] = 'Profiel van '.$_GET['player'];
    $txt['offline'] = 'Offline';
    $txt['online'] = 'Online';
    $txt['username'] = 'Gebruikersnaam:';
    $txt['name'] = 'Naam:';
    $txt['country'] = 'Land:';
    $txt['date_started'] = 'Begon op:';
    $txt['world'] = 'Wereld:';
    $txt['silver'] = 'Silver:';
    $txt['gold'] = 'Gold:';
    $txt['bank'] = 'Bank:';
    $txt['rank'] = 'Rank:';
    $txt['rank_number'] = 'Plaats:';
    $txt['badges_number'] = 'Badges:';
    $txt['pokemon'] = 'Pok&eacute;mon:';
    $txt['win'] = 'Gevechten winst:';
    $txt['lost'] = 'Gevechten verloren:';
    $txt['status'] = 'Status:';
    $txt['action'] = 'Actie:';
    $txt['add_buddy'] = 'Voeg toe aan je buddylijst';
    $txt['send_message'] = 'Stuur bericht';
    $txt['block'] = 'Blokkeer';
    $txt['spy'] = 'Bespioneer';
    $txt['steal'] = 'Steel';
    $txt['race'] = 'Race';
    $txt['duel'] = 'Duelleer';
    $txt['bank_transfer'] = 'Stuur silver of gold';
    $txt['email'] = 'E-mail:';
    $txt['ip_registered'] = 'IP aangemeld:';
    $txt['ip_login'] = 'IP ingelogd:';
    $txt['admin_options'] = 'Admin opties:';
    $txt['edit_profile'] = 'Verander profiel';
    $txt['make_admin'] = 'Maak admin';
    $txt['give_egg'] = 'Geef ei';
    $txt['give_pokemon'] = 'Geef Pok&eacute;mon';
    $txt['give_pack'] = 'Geef pack';
    $txt['team'] = 'Team:';
    $txt['buddies'] = 'Buddies:';
    $txt['badges'] = 'Badges';
    $txt['no_badges_from'] = 'Nog geen badges van';
    $txt['no_profile_insert'] = 'Profiel niet opgemaakt.';
    $txt['no_buddies'] = 'Buddies niet zichtbaar of ingesteld.';
}

######################## WORK ########################
elseif($page == 'work'){
    #Alerts
    $txt['alert_nothing_selected'] = 'Je hebt niets geselecteerd.';
    $txt['alert_captcha_wrong'] = 'De beveiligingscode is verkeerd.';
    $txt['and'] = 'en';
    $txt['seconds'] = 'seconden';
    $txt['minutes'] = 'minuten';
    $txt['minute'] = 'minuut';
    $txt['success_work_1'] = 'Je gaat nu';
    $txt['success_work_2'] = 'aan het werk.';

    #Screen
    $txt['pagetitle'] = 'Werken';
    $txt['#'] = '#';
    $txt['work_name'] = 'Werken';
    $txt['duration'] = 'Tijdsduur';
    $txt['turnover'] = 'Opbrengst';
    $txt['chance'] = 'Kans';
    $txt['button'] = 'Werken';

    $txt['work_1'] = 'Verkoop ranja op het plein';
    $txt['work_2'] = 'In de PokeMarkt helpen';
    $txt['work_3'] = 'De PokeMagazine Bezorgen';
    $txt['work_4'] = 'Pok&eacute;mon Center schoonmaken';
    $txt['work_5'] = 'Daag Team Rocket uit voor een potje golf';
    $txt['work_6'] = 'Zoek waardevolle spullen in de stad';
    $txt['work_7'] = 'Hou een Pok&eacute;mon demonstratie op het plein';
    $txt['work_8'] = 'Medisch experiment voor je Pok&eacute;mon';
    $txt['work_9'] = 'Laat je Pok&eacute;mon freestylen in het park';
    $txt['work_10'] = 'Help agent Jenny';
    $txt['work_11'] = 'Laat je Pok&eacute;mon stelen';
    $txt['work_12'] = 'Beroof een casino met je Pok&eacute;mon';
}

######################## TRADERS ########################
elseif($page == 'traders'){
    #Alerts
    $txt['alert_dont_have_1'] = 'je hebt geen';
    $txt['alert_dont_have_2'] = 'bij je.';

    $txt['alert_i_have_1'] = 'ik heb';
    $txt['alert_i_have_2'] = 'net geruild, sorry.';
    $txt['success_traders_change'] = 'bedankt voor de ruil, zorg goed voor';
    $txt['success_traders_refresh'] = 'Succesvol de Pok&eacute;mon ge-refreshed!';

    #Screen
    $txt['pagetitle'] = 'Pok&eacute;mon verkopers';
    $txt['title_text'] = 'Hier kun je een Pok&eacute;mon ruilen met Kayl, Wayne en Remy.<br />
										De level van de Pok&eacute;mon word gebasseerd op het level van de Pok&eacute;mon die je inruilt.<br /><br />
										Je moet de Pok&eacute;mon die je wilt ruilen wel bij je hebben.<br />
										Als je 2 dezelfde Pok&eacute;mon hebt, word de eerste die je bij je hebt geruild.';
    $txt['kayl_no_pokemon'] = 'Sorry mate, ik heb alle Pok&eacute;mon die ik wil.';
    $txt['kayl_text_1'] = 'Heej!<br />
								Heb jij toevallig een <strong>';
    $txt['kayl_text_2'] = '</strong>?<br />
								Ik zou die graag willen ruilen voor mijn <strong>';
    $txt['kayl_text_3'] = '</strong>.<br /><br />
								Ik zou het echt mooi vinden als je met me wilt ruilen!';
    $txt['button_change'] = 'Ruil met';

    $txt['wayne_no_pokemon'] = 'Ik hoef nu geen zaken met je te doen.';
    $txt['wayne_text_1'] = 'Hoi, mijn naam is Wayne.<br />
								Ik zoek een <strong>';
    $txt['wayne_text_2'] = '</strong>, zou je die willen ruilen voor mijn <strong>';
    $txt['wayne_text_3'] = '</strong>?<br /><br />
								Als je met mij zaken wilt doen zal ik je rijkelijk belonen door je <img src="images/icons/silver.png" title="Silver" style="margin-bottom:-3px;"> 100 erbij te geven.<br />';

    $txt['remy_no_pokemon'] = 'Sorry, ik ben op het moment niet op zoek naar een Pok&eacute;mon.';
    $txt['remy_text_1'] = 'Hallo ik ben Remy.<br />
								Ik ben al een heletijd opzoek naar een <strong>';
    $txt['remy_text_2'] = '</strong>, heb jij die misschien?<br />
								Ik zou hem graag willen ruilen voor mijn <strong>';
    $txt['remy_text_3'] = '</strong>.';

    $txt['refresh_pokemon'] = 'Refresh de Pok&eacute;mon';
    $txt['button_traders_refresh'] = 'Refresh Pok&eacute;mon';
}

######################## RACE INVITE ########################
elseif($page == 'race-invite'){
    #Alerts
    $txt['alert_no_races_today'] = 'Je kunt vandaag niet meer uitnodigingen sturen om te racen.';
    $txt['alert_no_player'] = 'Je hebt geen speler ingevuld.';
    $txt['alert_not_yourself'] = 'Je kunt jezelf niet uitdagen.';
    $txt['alert_unknown_amount'] = 'Ongeldig bedrag.';
    $txt['alert_no_amount'] = 'Geen bedrag hoeveelheid ingevuld.';
    $txt['alert_unknown_what'] = 'Kies of je voor silver of gold wilt racen.';
    $txt['alert_not_enough_silver_or_gold'] = 'Je hebt niet genoeg silver of gold.';
    $txt['alert_user_unknown'] = 'Gebruikersnaam bestaat niet.';
    $txt['alert_opponent_not_in'] = 'zit niet in';
    $txt['alert_opponent_not_casual'] = 'is nog geen rank Casual.';
    $txt['alert_no_admin'] = 'Je kunt geen admin uitnodigen voor een race.';
    $txt['success'] = 'Je hebt '.$_POST['naam'].' succesvol uitgedaagd voor een race!';

    #Screen
    $txt['pagetitle'] = 'Race';
    $txt['title_text'] = '<img src="images/icons/vlag.png" width="16" height="16" alt="Race" /> <strong>Daag een speler uit voor een race van 5 kilometer!</strong> <img src="images/icons/vlag.png" width="16" height="16" alt="Race" />';
    $txt['races_left_today'] = 'Race uitnodigingen wat je vandaag nog kunt versturen:';
    $txt['premium_10_times'] = 'Premiumleden kunnen 10x per dag iemand uitnodigen, <a href="?page=area-market">word premium hier!</a>.';
    $txt['player'] = 'Gebruikersnaam:';
    $txt['silver_or_gold'] = 'Silver of gold:';
    $txt['amount'] = 'Hoeveel:';
    $txt['button'] = 'Nodig uit!';
    $txt['races_opened'] = 'Openstaande uitnodigingen';
    $txt['races_deleted_3_days'] = 'Als een race uitnodiging ouder is dan 3 dagen word de uitnodiging automatisch verwijderd.';
    $txt['#'] = '#';
    $txt['opponent'] = 'Tegenstander';
    $txt['price'] = 'Inzet';
    $txt['when'] = 'Uitgenodigd op';
    $txt['no_races_opened'] = 'Je hebt geen uitnodigingen open staan.';
}

######################## RACE ########################
elseif($page == 'race'){
    #Alerts
    $txt['alert_to_low_rank'] = 'Je moet rank Casual zijn.';
    $txt['alert_no_pokemon_in_hand'] = 'Je hebt geen Pok&eacute;mon bij je.';
    $txt['alert_link_invalid'] = 'Link is ongeldig.';
    $txt['alert_race_invalid'] = 'Race is niet meer beschikbaar.';
    $txt['alert_not_enough_money'] = 'Je hebt niet genoeg silver of gold.';
    $txt['success_denied'] = 'Je hebt de race succesvol geweigerd.';
    $txt['success_accepted'] = 'Race succesvol geaccepteerd, je krijgt zo een gebeurtenis.';

}

######################## STEAL ########################
elseif($page == 'steal'){
    #Alerts
    $txt['alert_no_more_steal'] = 'Je kunt vandaag niet meer stelen.';
    $txt['alert_no_username'] = 'Geen Gebruikersnaam ingevuld.';
    $txt['alert_steal_from_yourself'] = 'Je kunt niet van jezelf stelen.';
    $txt['alert_username_dont_exist'] = 'Gebruikersnaam bestaat niet.';
    $txt['alert_username_incorrect_signs'] = 'Gebruikersnaam bevat ongeldige tekens.';
    $txt['alert_admin_steal'] = 'Je kunt niet van een admin stelen.';
    $txt['alert_is_not_in'] = $_POST['player'].' is niet in';
    $txt['alert_too_low_rank'] = $_POST['player'].' heeft een te lage rank.';
    $txt['alert_too_low_or_high_rank'] = $_POST['player'].' heeft een te lage of te hoge rank.';
    $txt['alert_steal_failed_1'] = 'Stelen is mislukt.';
    $txt['alert_steal_failed_2'] = 'was sterker.';

    $txt['alert_steal_jail'] = 'Je bent opgepakt door agent Jenny.<br>';
    $txt['success_stole_1'] = 'Je hebt';
    $txt['success_stole_2'] = 'gestolen van '.$_POST['player'];

    $txt['alert_steal_jail_text_1'] = 'Je zit nu';
    $txt['alert_steal_jail_text_2'] = 'min en';
    $txt['alert_steal_jail_text_3'] = 'sec in de gevangenis.';

    //Sreen
    $txt['pagetitle'] = 'Stelen';
    $txt['title_text'] = 'Je kunt hier je Pok&eacute;mon laten stelen van een tegenstander.<br />Als het lukt nemen ze zoveel mogelijk geld mee! 	 										 								  Als het mislukt heb je kans dat je in de gevangenis komt.<br />
									  Er mag maximaal 1 rank tussen jou en je tegenstander zitten. Rank Junior en lager mag je niet beroven.<br /><br />';

    $txt['steal_premium_text'] = 'Premiumaccount leden mogen 3 keer per dag iemand beroven. <a href="index.php?page=area-market"><strong>Word hier premium!</strong></a><br><br>';
    $txt['steal_how_much_1'] = ' Je kunt vandaag nog <strong>';
    $txt['steal_how_much_2'] = '</strong> keer je Pok&eacute;mon laten stelen.';
    $txt['username'] = 'Gebruikersnaam:';
    $txt['button'] = 'Steel!';
}

######################## SPY ########################
elseif($page == 'spy'){
    #Alerts
    $txt['alert_no_username'] = 'Geen Gebruikersnaam ingevuld.';
    $txt['alert_spy_yourself'] = 'Je kunt jezelf niet bespioneren.';
    $txt['alert_username_dont_exist'] = 'Gebruikersnaam bestaat niet.';
    $txt['alert_not_enough_silver'] = 'Je hebt niet genoeg Silver.';
    $txt['alert_admin_spy'] = 'Je kunt geen admin bespioneren.';
    $txt['alert_spy_failed'] = 'Het bespioneren is mislukt.';
    $txt['alert_spy_failed_jail_1'] = 'Team Rocket is opgepakt!<br> Je zit nu';
    $txt['alert_spy_failed_jail_2'] = 'min en';
    $txt['alert_spy_failed_jail_3'] = 'sec in de gevangenis.';
    $txt['success_spy'] = 'Spionage was succesvol.';

    #Screen
    $txt['pagetitle'] = 'Bespioneren';
    $txt['username'] = 'Gebruikersnaam:';
    $txt['button'] = 'Bespioneer';
    $txt['world'] = 'Wereld';
    $txt['silver_in_hand'] = 'Silver';
    $txt['team'] = 'Team';
    $txt['title_text'] = 'Je kunt hier Team Rocket inhuren om een speler te bespioneren.<br />
								  Als het hun lukt geven ze info over de wereld waar diegene zit, het geld wat diegene bij zich heeft en alle info over het team dat hij/zij bij zich heeft.<br />
								  Maar als het niet lukt moet Team Rocket naar de gevangenis, en dan zullen ze jou erbij linken.<br /><br />
								  Team Rocket vraagt voor elke spionage sessie <img src="images/icons/silver.png" title="Silver" style="margin-bottom:-3px;" /> 100.';
}

######################## LVL CHOOSE ########################
elseif($page == 'lvl-choose'){
    #Alerts
    $txt['success_lvl_choose'] = 'Je kunt nu Pok&eacute;mon van lvl '.$_POST['lvl'].' tegen komen.';

    #Screen
    $txt['pagetitle'] = 'Level kiezen';
    $txt['title_text'] = 'Hier kun je kiezen welke level Pok&eacute;mon je kunt tegenkomen.<br />
  								Je kunt alleen op deze pagina als je rank 18 of hoger bent.';
    $txt['#'] = '#';
    $txt['level'] = 'Level';
    $txt['5-20'] = '5-20';
    $txt['20-40'] = '20-40';
    $txt['40-60'] = '40-60';
    $txt['60-80'] = '60-80';
    $txt['80-100'] = '80-100';
    $txt['button'] = 'Opslaan!';
}

######################## Area markt ########################
elseif($page == 'area-market'){
    #Screen
    $txt['pagetitle'] = 'Area markt';
    $txt['colorbox_text'] = 'Open this window again and this message will still be here.';
    $txt['premiumdays'] = 'Premiumdagen';
    $txt['premiumpacks'] = 'Starter packs';
    $txt['premiumtext'] = 'Steun '.GLOBALDEF_SITENAME.' met de aankop van een pack. <br/>Premium packs zorgen dat je meer opties krijgt in het spel, Enkele voorbeelden hiervan zijn:<br /><br />
- <span style="color:green;font-weight: bold;">Een premium markt waar je ook gold kan kopen!</span><br />
- Meer inbox en gebeurtenis ruimte.<br />
- Meer Race mogelijkheden.<br />
- 3 pogingen voor het geluksrad.<br />
- Geen wachttijd bij het Pok&eacute;moncenter.<br />
- Premium boosted XP van 5%.<br />
- En nog veel meer!';
    $txt['valuepacks'] = 'Andere packs';
    $txt['valuetext'] = 'Deze packs zijn zeer handig in het spel, voor Gold kun je namelijk Master Balls kopen etc.';
    $txt['premiumrow'] = 'Premium packs';
    $txt['premiumrowtext'] = 'Met deze packs krijg je voor een aantal dagen premium. Hiermee krijg je voordelen zoals een XP boost, <br />of toegang tot de premium markt.';
    $txt['buy'] = 'Koop';
}

######################## POKEMON CENTER ########################
elseif($page == 'pokemoncenter'){
    #Alerts
    $txt['minute'] = 'minuut';
    $txt['minutes'] = 'minuten';
    $txt['seconds'] = 'seconden';
    $txt['success_pokecenter_premium'] = 'Je Pok&eacute;mon zijn verzorgd en weer op sterkte.';
    $txt['success_pokecenter'] = 'Je pokemon worden verzorgd in de tijd:';

    #Screen
    $txt['pagetitle'] = 'Pok&eacute;moncenter';
    $txt['title_text_premium'] = 'Herstel hier je Pok&eacute;mon direct.';
    $txt['title_text_normal'] = 'Een keer naar het Pok&eacute;moncenter duurt 10 seconden.<br/>Voor premiumleden is dit direct.<br><a href="index.php?page=area-market"><strong>Word hier Premium!</strong></a>';
    $txt['all'] = 'All';
    $txt['who'] = 'Pok&eacute;mon';
    $txt['health'] = 'Health';
    $txt['nvt'] = 'Niet van toepassing';
    $txt['button'] = 'Verzorgen';
}

######################## MARKET ########################
elseif($page == 'market'){
    #Alerts
    $txt['alert_itemplace'] = 'Let op: U heeft geen itemplek over, dus u kunt niets kopen.';
    $txt['alert_not_enough_money'] = 'Niet genoeg silver of gold.';
    $txt['alert_itembox_full_1'] = 'Item box vol! Je kunt nog';
    $txt['alert_itembox_full_2'] = 'kopen.';
    $txt['success_market'] = 'Je hebt een ';
    $txt['alert_nothing_selected'] = 'Je hebt niks gekozen.';
    $txt['alert_pokedex_chip'] = 'Je kunt nog geen Pokedex Chip kopen, koop eerst een Pokedex.';
    $txt['alert_not_enough_place'] = 'Uw itembox is vol.';
    $txt['alert_hand_full'] = 'Je hebt al 6 Pok&eacute;mon bij je.';
    $txt['alert_not_in_stock'] = 'Product op het moment niet beschikbaar.';

    #Screen
    $txt['pagetitle'] = 'Markt';
    $txt['balls'] = 'Balls';
    $txt['potions'] = 'Potions';
    $txt['items'] = 'Items';
    $txt['spc_items'] = 'Special items';
    $txt['stones'] = 'Stones';
    $txt['attacks'] = 'Attacks';
    $txt['pokemon'] = 'Pok&eacute;mon';

    if($_GET['shopitem'] == 'balls'){
        $txt['pagetitle'] .= ' - Balls';
        $txt['button_balls'] = 'Koop balls';
    }
    elseif($_GET['shopitem'] == 'potions'){
        $txt['pagetitle'] .= ' - Potions';
        $txt['button_potions'] = 'Koop potions';
    }
    elseif($_GET['shopitem'] == 'items'){
        $txt['pagetitle'] .= ' - Items';
        $txt['button_items'] = 'Koop items';
    }
    elseif($_GET['shopitem'] == 'specialitems'){
        $txt['pagetitle'] .= ' - Special items';
        $txt['button_spc_items'] = 'Koop special items';
    }
    elseif($_GET['shopitem'] == 'stones'){
        $txt['pagetitle'] .= ' - Stones';
        $txt['button_stones'] = 'Koop stones';
    }
    elseif($_GET['shopitem'] == 'attacks'){
        $txt['pagetitle'] .= ' - Attacks';
        $txt['button_attacks'] = 'Koop attacks';
        $txt['market_attack_types'] = 'Pok&eacute;mon kunnen deze aanval leren.';
    }
    elseif($_GET['shopitem'] == 'pokemon'){
        $txt['pagetitle'] .= ' - Pok&eacute;mon';
        $txt['button_pokemon'] = 'Koop Pok&eacute;mon';
        $txt['not_rare'] = 'Niet zeldzaam';
        $txt['middle_rare'] = 'Beetje zeldzaam';
        $txt['rare'] = 'Zeldzaam';
        $txt['out_of_stock_1'] = 'Alle Pok&eacute;mon zijn op het moment verkocht in de';
        $txt['out_of_stock_2'] = 'markt.';
        $txt['success_bought_pokemon'] = '1 Pokemon egg.';
    }
}
######################## MARKET ########################
elseif($page == 'premiummarket'){
    #Alerts
    $txt['alert_itemplace'] = 'Let op: U heeft geen itemplek over, dus u kunt niets kopen.';
    $txt['alert_not_enough_money'] = 'Niet genoeg silver of gold.';
    $txt['alert_itembox_full_1'] = 'Item box vol! Je kunt nog';
    $txt['alert_itembox_full_2'] = 'kopen.';
    $txt['success_market'] = 'Je hebt';
    $txt['alert_nothing_selected'] = 'Je hebt niks gekozen.';
    $txt['alert_pokedex_chip'] = 'Je kunt nog geen Pokedex Chip kopen, koop eerst een Pokedex.';
    $txt['alert_not_enough_place'] = 'Uw itembox is vol.';
    $txt['alert_hand_full'] = 'Je hebt al 6 pokemon bij je.';
    $txt['alert_not_in_stock'] = 'Product op het moment niet beschikbaar.';
    $txt['alert_not_incorrect_amount'] = 'Je kan niet meer dan een legend kans vergroter per keer kopen.';
}
######################## BANK ########################
elseif($page == 'bank'){
    #Alerts
    $txt['alert_success_silver_withdraw'] = "Er is <img src=\"images/icons/silver.png\" /> " . $_POST['amount'] . " silver afgeschreven van je bankrekening!";
    $txt['alert_failed_silver_withdraw'] = "Zoveel silver staat er niet op je bankrekening.";
    $txt['alert_success_silver_deposit'] = "Er is <img src=\"images/icons/silver.png\" /> " . $_POST['amount'] . " bijgeschreven bij je bankrekening!";
    $txt['alert_failed_silver_deposit'] = "Je kan niet meer storten vandaag.";
    $txt['alert_failed_max_silver_deposit'] = "Je mag maar <img src=\"images/icons/silver.png\" /> ".$bankmax." per keer storten";
    $txt['alert_failed_funds_silver_deposit'] = "Zoveel silver heb je niet";
    $txt['alert_bank_transfer_name_incorrect'] = "De naam die je hebt ingevuld klopt niet!";
    $txt['alert_bank_transfer_success'] = "Er is <img src=\"images/icons/silver.png\" /> ".$_POST['silver']." aan {$ontvanger['username']} overgemaakt.";
    $txt['alert_bank_transfer_funds'] = "Je hebt niet genoeg silver contant staan.";
    $txt['alert_bank_transfer_no_amount'] = "Er is geen silver opgegeven.";
    $txt['alert_bank_transfer_clan_incorrect'] = "De clan die je hebt ingevuld klopt niet!";
    $txt['alert_bank_transfer_clan_success'] = "Er is <img src=\"images/icons/silver.png\" /> ".$_POST['silver']." aan de clan {$ontvanger['clan_naam']} overgemaakt";
    $txt['alert_bank_transfer_clan_funds'] = "Je hebt niet genoeg silver contant staan.";
    $txt['alert_bank_transfer_clan_gold_success'] = "Er is <img src=\"images/icons/gold.png\" /> " . $_POST['gold'] . " aan de clan {$ontvanger['clan_naam']} overgemaakt";
    $txt['alert_bank_transfer_clan_gold_funds'] = "Je hebt niet genoeg gold.";
    $txt['alert_bank_transfer_clan_no_ammount'] = "Er is geen silver of gold opgegeven.";

    #Screen
    $txt['pagetitle'] = "Pinnen &amp; Storten";
    $txt['deposit_to_clan'] = "Overschrijven naar Clan";
    $txt['bank_deposit_amount_max'] = "Je mag nog ".$gebruiker['storten']."x silver storten.";
    $txt['bank_deposit_cash'] = "Contant:";
    $txt['bank_deposit_bank'] = "Op de bank:";
    $txt['bank_deposit_pin'] = "pin";
    $txt['bank_deposit_deposit'] = "stort";
    $txt['bank_transfer'] = "Overschrijven";
    $txt['bank_transfer_to'] = "Aan:";
    $txt['bank_transfer_silver'] = "Silver:";
    $txt['bank_transfer_gold'] = "Gold:";
    $txt['bank_transfer_button'] = "Overmaken";
}

######################## HOUSE SELLER ########################
elseif($page == 'house-seller'){
    #Alerts
    $txt['alert_nothing_selected'] = 'Je moet wel een huis selecteren.';
    $txt['alert_you_own_this_house'] = 'Je hebt dit huis al!';
    $txt['alert_not_enough_silver'] = 'Je hebt niet genoeg silver voor dit huis.';
    $txt['alert_already_have_villa'] = 'Je hebt al een Villa je kunt niet beter krijgen.';
    $txt['alert_you_have_better_now'] = 'Je moet wel beter kopen dan je nu hebt.';
    $txt['success_house_1'] = 'Je hebt succesvol een';
    $txt['success_house_2'] = 'gekocht.';

    #Screen
    $txt['pagetitle'] = 'Huizenverkoper';
    $txt['house1'] = 'Kartonnen doos';
    $txt['house2'] = 'Klein huis';
    $txt['house3'] = 'Normaal huis';
    $txt['house4'] = 'Villa';
    $txt['house5'] = 'hotel';
    $txt['title_text'] = 'Hier bij de huizenverkoper kun je een huis kopen voor je Pok&eacute;mon.<br />
							  Je hebt nu een';
    $txt['house'] = 'Huis';
    $txt['price'] = 'Prijs';
    $txt['description'] = 'Omschrijving';
    $txt['button'] = 'Kopen';
}

######################## TRAVEL ########################
elseif($page == 'travel'){
    #Alerts
    $txt['alert_no_world'] = 'Je hebt geen wereld gekozen.';
    $txt['alert_already_in_world'] = 'Je zit al in '.$_POST['wereld'].'.';
    $txt['alert_world_invalid'] = $_POST['wereld'].' is geen geldige wereld.';
    $txt['alert_not_enough_money'] = 'Je hebt niet genoeg silver om naar '.$_POST['wereld'].' te varen.';
    $txt['success_travel'] = 'Je bent aangekomen in '.$_POST['wereld'].', dit kost je';
    $txt['alert_not_everything_selected'] = 'Niet alles aangevinkt.';
    $txt['alert_not_your_pokemon'] = 'Deze Pok&eacute;mon is niet van jou.';
    $txt['alert_no_surf'] = 'Deze Pok&eacute;mon kent geen Surf attack.';
    $txt['alert_not_strong_enough'] = 'Deze Pok&eacute;mon is niet sterk genoeg.';
    $txt['success_surf'] = 'Je Pok&eacute;mon heeft je succesvol naar '.$_POST['wereld'].' gevaren.';

    #Screen
    $txt['pagetitle'] = 'Reis naar een andere wereld';
    $txt['title_text'] = 'Je kunt hier een boot huren en varen naar een andere wereld.';
    $txt['#'] = '#';
    $txt['world'] = 'Wereld';
    $txt['price'] = 'Prijs per Pok&eacute;mon';
    $txt['price_total'] = 'Prijs totaal';
    $txt['button_travel'] = 'Reizen';
    $txt['title_text_surf'] = 'Als een Pok&eacute;mon de aanval \'Surf\' heeft en de Pok&eacute;mon is level 80+.<br />
								Dan kun je hier gratis surfen naar een andere wereld!';
    $txt['pokemon'] = 'Pok&eacute;mon';
    $txt['button_surf'] = 'Surfen';
    $txt['button_fly'] = 'Vliegen';
}

######################## TRANSFERLIST ########################
elseif($page == 'transferlist'){
    #Screen
    $txt['pagetitle'] = 'Transferlijst';
    $txt['colorbox_text'] = 'Open this window again and this message will still be here.';
    $txt['title_text_1'] = 'Je hebt nu:';
    $txt['title_text_2'] = 'Tip: kijk ook naar de aanvallen van de Pok&eacute;mon.';
    $txt['#'] = '#';
    $txt['pokemon'] = 'Pok&eacute;mon';
    $txt['clamour_name'] = 'Roepnaam';
    $txt['level'] = 'Level';
    $txt['price'] = 'Prijs';
    $txt['owner'] = 'Eigenaar';
    $txt['buy'] = 'Koop';
}

######################## DAYCARE ########################
elseif($page == 'daycare'){
    #Alerts
    $txt['alert_not_your_pokemon'] = 'Dit is niet jou Pok&eacute;mon.';
    $txt['alert_hand_full'] = 'Je hebt al 6 Pok&eacute;mon bij je.';
    $txt['alert_no_eggs'] = 'Er zijn geen eieren voor jou.';
    $txt['success_egg'] = 'Je hebt succesvol het eitje aangenomen.';
    $txt['alert_already_in_daycare'] = 'Pok&eacute;mon is al bij de daycare.';
    $txt['alert_already_lvl_100'] = 'Pok&eacute;mon is al level 100.';
    $txt['alert_daycare_full'] = 'Je kunt niet meer Pok&eacute;mon bij de daycare hebben.';
    $txt['success_bring'] = 'U heeft uw Pok&eacute;mon succesvol weggebracht.';
    $txt['alert_not_enough_silver'] = 'Je hebt niet genoeg silver.';
    $txt['success_take'] = 'U heeft uw Pok&eacute;mon succesvol opgehaald.';
    $txt['alert_no_pokemon'] = 'Je moet wel een Pok&eacute;mon bij je hebben als je &egrave;&egrave;n naar de daycare wilt brengen.';

    #Screen
    $txt['pagetitle'] = 'Dagverblijf';
    $txt['egg_text'] = 'Heej!<br /><br />
							  We hebben een ei gevonden in onze daycare, wil je het ei hebben?<br /><br />
							  <input type="submit" name="accept" value="Ja graag!" class="text_long"><input type="submit" name="dontaccept" value="Nee dankje." class="text_long" style="margin-left:10px;">';
    $txt['normal_user'] = 'Je kan maximaal 1 Pok&eacute;mon naar de daycare brengen, premiumleden kunnen 2 pokemon.';
    $txt['premium_user'] = 'Je kan maximaal 2 Pok&eacute;mon naar de daycare brengen.';
    $txt['title_text'] = 'Een Pok&eacute;mon in de daycare zetten kost <img src="images/icons/silver.png" title="Silver" /> 250 per pokemon.<br />
			Per nieuw level komt daar nog eens <img src="images/icons/silver.png" title="Silver" /> 500 bovenop.<br />
			<small>Evolueren en aanvallen leren doet de pokemon pas als je hem ophaalt.</small>';
    $txt['give_pokemon_text'] = 'Geef een Pok&eacute;mon aan de daycare beheerder:';
    $txt['button_bring'] = 'Wegbrengen';
    $txt['take_pokemon_text'] = 'Pok&eacute;mon die in de daycare zitten';
    $txt['#'] = '#';
    $txt['name'] = 'Naam';
    $txt['level'] = 'Level';
    $txt['levelup'] = 'Level up';
    $txt['cost'] = 'Kosten';
    $txt['buy'] = 'Koop';
    $txt['button_take'] = 'Haal op';
}

######################## NAME SPECIALIST ########################
elseif($page == 'name-specialist'){
    #Alerts
    $txt['alert_no_pokemon_in_hand'] = 'Je hebt geen Pok&eacute;mon bij je.';
    $txt['alert_nothing_selected'] = 'Je hebt geen Pok&eacute;mon geselecteerd.';
    $txt['alert_not_enough_silver'] = 'Je hebt niet genoeg silver.';
    $txt['alert_name_too_long'] = 'Naam mag niet langer als 12 tekens.';
    $txt['alert_not_your_pokemon'] = 'Deze Pok&eacute;mon is niet van jou.';
    $txt['success_namespecialist'] = 'De nieuwe naam is:';

    #Screen
    $txt['pagetitle'] = 'Namenspecialist';
    $txt['title_text'] = 'Hier kun je de naam van je Pok&eacute;mon veranderen!<br />
							Per Pok&eacute;mon kost dit';
    $txt['#'] = '#';
    $txt['name_now'] = 'Naam nu';
    $txt['button'] = 'Verander naam';
}

######################## NAME SPECIALIST ########################
elseif($page == 'shiny-specialist'){
    #Alerts
    $txt['alert_no_pokemon_selected'] = 'Geen Pok&eacute;mon geselecteerd.';
    $txt['alert_pokemon_is_egg'] = 'Deze Pok&eacute;mon is een ei.';
    $txt['alert_not_your_pokemon'] = 'Dit is niet jouw Pok&eacute;mon.';
    $txt['alert_already_shiny'] = 'Pok&eacute;mon is al shiny.';
    $txt['alert_pokemon_not_in_hand'] = 'Pok&eacute;mon is niet bij je.';
    $txt['alert_not_enough_gold'] = 'Je hebt niet genoeg gold.';
    $txt['success'] = 'Je Pok&eacute;mon is nu shiny!';

    #Screen
    $txt['pagetitle'] = 'Shiny specialist';
    $txt['title_text'] = 'Wetenschappers hebben iets nieuws ontdekt:<br />
Door een Pok&eacute;mon veel gold te geven word een Pok&eacute;mon door de impact van het glimmende gold Shiny!<br />
Bij elke Pok&eacute;mon is het aantal gold verschillend, zeldzame Pok&eacute;mon hebben bijvoorbeeld meer gold nodig.<br />
Je kunt hier een Pok&eacute;mon shiny maken door hem het benodigde gold te geven.';
    $txt['#'] = '#';
    $txt['gold_need'] = 'Gold';
    $txt['button'] = 'Maak shiny';
}

######################## JAIL ########################
elseif($page == 'jail'){
    #Alerts
    $txt['alert_already_broke_out'] = $_POST['naam'].' is al uitgebroken.';
    $txt['alert_already_free'] = $_POST['naam'].' is al vrij.';
    $txt['success_bust'] = 'Je hebt '.$_POST['naam'].' succesvol uitgebroken.';
    $txt['alert_bust_failed_1'] = 'Het is je mislukt om '.$_POST['naam'].' uit te breken. Je zit nu zelf';
    $txt['alert_bust_failed_2'] = 'in de gevangenis.';
    $txt['alert_not_enough_silver'] = 'Je hebt niet genoeg silver om '.$_POST['naam'].' uit te kopen.';
    $txt['success_bought'] = 'Je hebt '.$_POST['naam'].' uitgekocht voor';

    #Screen
    $txt['pagetitle'] = 'Gevangenis';
    $txt['title_text'] = 'Bust je medespelers uit de gevangenis, als het mislukt zit je zelf in de gevangenis. <br />
							  Als het wel lukt krijg je rankpoints en is diegene vrij!<br />
							  Je kunt ze ook uitkopen, dit kost je geld maar je weet zeker dat diegene vrij komt.';
    $txt['#'] = '#';
    $txt['username'] = 'Gebruikersnaam';
    $txt['country'] = 'Land';
    $txt['time'] = 'Tijd';
    $txt['cost'] = 'Kosten';
    $txt['buy_out'] = 'Koop uit';
    $txt['bust'] = 'Bust';
    $txt['button_buy'] = 'Koop';
    $txt['button_bust'] = 'Bust';
    $txt['nobody_injail_1'] = 'Momenteel zitten er geen spelers in de';
    $txt['nobody_injail_2'] = 'gevangenis.';
}

######################## FLIP A COIN ########################
elseif($page == 'flip-a-coin'){
    #Alerts
    $txt['alert_no_amount'] = 'Geen bedrag ingevoerd.';
    $txt['alert_too_less_silver'] = 'Je hebt te weinig silver.';
    $txt['alert_amount_unknown'] = 'Ongeldig bedrag ingevuld.';
    $txt['success_win'] = 'Kop. Je wint';
    $txt['success_lose'] = 'Munt. Je verliest';

    #Screen
    $txt['pagetitle'] = 'Gooi een munt';
    $txt['title_text'] = 'Als het kop is, win je het bedrag dubbel terug.<br>
      						  Bij munt verlies je het ingezette geld.';
    $txt['button'] = 'Gooi';
}

######################## WHO IS IT QUIZ ########################
elseif($page == 'who-is-it-quiz'){
    #Alerts
    $txt['alert_wait'] = 'Je kunt over
							  <strong><span id=uur3></span></strong> uur
							  <strong><span id=minuten3> </span>&nbsp;minuten</strong> en 
							  <strong><span id=seconden3></span>&nbsp;seconden</strong> de quiz nog eens doen.';
    $txt['alert_choose_a_pokemon'] = 'Geen Pok&eacute;mon gekozen.';
    $txt['alert_no_answer'] = 'Er is geen antwoord bekend.';
    $txt['success_win'] = 'Het antwoord is goed! Je hebt <img src="images/icons/silver.png" title="Silver"> 200 gewonnen! Je mag de quiz over een uur weer doen.';
    $txt['success_lose_1'] = 'Helaas, het antwoord was';
    $txt['success_lose_2'] = 'Probeer het over 1 uur weer.';

    #Screen
    $txt['pagetitle'] = 'Wie is het Quiz';
    $txt['who_is_it'] = 'Wie is het?';
    $txt['title_text'] = '<strong>Wie is het Quiz.</strong><br />
							  Je kunt hier 1 keer per uur raden wat de naam van de Pok&eacute;mon is.<br />
							  Als het antwoord goed is, verdien je <img src="images/icons/silver.png" title="Silver"> 200!';
    $txt['choose_a_pokemon'] = 'Kies een Pok&eacute;mon';
    $txt['button'] = 'Kiezen';
}

######################## WHEEL OF FORTUNE ########################
elseif($page == 'wheel-of-fortune'){
    #Alerts
    $txt['alert_itemplace'] = 'Let op: U heeft geen itemplek over, dus u kunt geen Item winnen.';
    $txt['alert_no_more_wof'] = 'Je kan vandaag geen geluksrad meer doen.';
    $txt['win_100_silver'] = 'Je wint <img src="images/icons/silver.png" title="Silver"> 100 silver!';
    $txt['win_250_silver'] = 'Je wint <img src="images/icons/silver.png" title="Silver"> 250 silver!';
    $txt['win_5_gold'] = 'Je wint <img src="images/icons/gold.png" title="Gold"> 5 gold!';
    $txt['win_ball'] = 'Je wint een';
    $txt['alert_itembox_full'] = 'Je item box is vol!';
    $txt['lose_jailzone'] = 'OH! Jail zone, je zit in jail!';
    $txt['win_spc_item'] = 'WOW! Je wint een Special item:';
    $txt['win_stone'] = 'Je wint een';
    $txt['win_tm'] = 'Je wint';
    $txt['lose_fortune_silver'] = 'Helaas! je hebt <img src="images/icons/silver.png" title="Silver"> 100 silver verloren.';

    #Screen
    $txt['pagetitle'] = 'Geluksrad';
    $txt['title_text_1'] = 'Je hebt nog';
    $txt['title_text_2'] = 'pogingen over vandaag.';
    $txt['premiumtext'] = '<br>Premiumleden kunnen 3x per dag aan het geluksrad draaien. <a href="index.php?page=area-market"><strong>Word hier premium!</strong></a>';
    $txt['button'] = 'Draai het rad!';
}

######################## WHEEL OF FORTUNE ########################
elseif($page == 'lottery'){
    #Alerts
    $txt['alert_premium_only'] = 'Alleen voor premium leden.';
    $txt['alert_no_amount'] = 'Je moet wel een aantal invullen.';
    $txt['alert_unknown_amount'] = 'Ongeldig bedrag.';
    $txt['alert_max_10_tickets'] = 'Je mag maar 10 kaartjes kopen!';
    $txt['alert_not_enough_money'] = 'Je hebt niet genoeg geld voor '.$_POST['aantal'].' kaarten.';
    $txt['alert_no_tickets_left'] = 'Je kunt geen kaartjes meer kopen!';
    $txt['alert_buys_left_1'] = 'Je kunt nog maar';
    $txt['alert_buys_left_2'] = 'kaartjes kopen!';
    $txt['success_lottery'] = 'Succesvol '.$_POST['aantal'].' kaartjes gekocht.';

    #Screen
    $txt['pagetitle'] = 'Loterij';
    $txt['title_text'] = 'Hier kun je kaartjes kopen voor 5 verschillende loterijen.<br />
								De loterijen worden willekeurig verloot.<br />
								Hoe meer kaartjes je koopt, des te meer kans je maakt op de prijs!';
    $txt['lottery'] = 'Loterij';
    $txt['time'] = 'Tijd van verloting:';
    $txt['ticket_price'] = 'Kaart prijs:';
    $txt['price_money'] = 'Prijzengeld:';
    $txt['tickets_sold'] = 'Kaarten verkocht:';
    $txt['last_winner'] = 'Laatste winnaar:';
    $txt['button'] = 'Kopen';
    $txt['only_premium'] = '* Alleen beschikbaar voor Premiumleden.';
    $txt['buy_tickets'] = 'Koop kaartjes:';
}

######################## Forum Categories ########################
elseif($page == 'forum-categories'){
    #Alerts
    $txt['alert_no_name'] = 'Geen naam ingevuld.';
    $txt['alert_name_too_short'] = 'Naam te kort, minimaal 3 tekens.';
    $txt['alert_name_too_long'] = 'Naam te lang, maximaal 20 tekens.';
    $txt['alert_no_icon'] = 'Geen icoon URL ingevuld.';
    $txt['alert_icon_doenst_exist'] = 'Icoon bestaat niet.';
    $txt['alert_name_already_taken'] = 'Categorie naam bestaat al.';
    $txt['success_add_category'] = 'Succesvol een categorie gestart.';
    $txt['success_edit_category'] = 'Succesvol de categorie bewerkt.';

    #Screen
    $txt['pagetitle'] = 'Forum';
    $txt['game-forum'] = ''.GLOBALDEF_SITENAME.' forum';
    $txt['#'] = '#';
    $txt['name'] = 'Naam';
    $txt['threads'] = 'Topics';
    $txt['messages'] = 'Berichten';
    $txt['last_post'] = 'Laatste post';
    $txt['nothing_posted'] = '';
    $txt['edit_category'] = 'Bewerk categorie';
    $txt['add_category'] = 'Voeg categorie toe';
    $txt['name_of_category'] = 'Naam van categorie:';
    $txt['icon_url'] = 'Icoon URL:';
    $txt['button'] = 'Voeg categorie toe';
}

######################## Forum threads ########################
elseif($page == 'forum-threads'){
    #Alerts
    $txt['alert_no_name'] = 'Geen naam ingevuld.';
    $txt['alert_name_too_short'] = 'Naam te kort, minimaal 3 tekens.';
    $txt['alert_name_too_long'] = 'Naam te lang, maximaal 20 tekens.';
    $txt['alert_name_already_taken'] = 'Topic naam bestaat al.';
    $txt['success_add_thread'] = 'Succesvol een topic gestart.';
    $txt['success_edit_thread'] = 'Succesvol de topic bewerkt.';
    $txt['success_changed_status'] = 'Succesvol status van de topic veranderd.';

    #Screen
    $txt['pagetitle'] = 'Forum';
    $txt['game-forum'] = ''.GLOBALDEF_SITENAME.' forum';
    $txt['#'] = '#';
    $txt['title'] = 'Titel';
    $txt['maker'] = 'Auteur';
    $txt['messages'] = 'Berichten';
    $txt['last_post'] = 'Laatste post';
    $txt['no_threads'] = 'Er zijn geen topics in deze categorie.';
    $txt['no_last_post'] = 'Nog geen berichten.';
    $txt['open_thread'] = 'Open topic';
    $txt['close_thread'] = 'Close topic';
    $txt['edit_thread'] = 'Bewerk topic';
    $txt['thread_is_open'] = 'Topic is open';
    $txt['thread_is_closed'] = 'Topic is closed';
    $txt['add_thread'] = 'Begin een topic:';
    $txt['english_topics'] = 'Altijd een engelse benaming.';
    $txt['name_of_thread'] = 'Naam van topic:';
    $txt['button'] = 'Voeg topic toe';
}

######################## Forum messages ########################
elseif($page == 'forum-messages'){
    #Alerts
    $txt['alert_no_text'] = 'Geen tekst ingevuld.';
    $txt['alert_already_send'] = 'Dit bericht heb je al eens verstuurd in deze topic.';
    $txt['success_post_message'] = 'Succesvol een bericht erin gezet.';
    $txt['alert_not_admin'] = 'U heeft niet de benodigde rechten.';
    $txt['alert_message_doesnt_exist'] = 'Dit bericht ID bestaat niet.';
    $txt['success_edit_message'] = 'Succesvol bericht bewerkt.';
    $txt['success_message_delete'] = 'Bericht is succesvol verwijderd.';

    #Screen
    $txt['pagetitle'] = 'Forum';
    $txt['game-forum'] = ''.GLOBALDEF_SITENAME.' forum';
    $txt['you_must_be_online'] = 'Let op: je kunt alleen een bericht verzenden wanneer je online bent.';
    $txt['topic_closed'] = 'Let op: dit topic is <strong>gesloten</strong>.';
    $txt['please_talk_english'] = 'Praat a.u.b. engels op dit forum zodat iedereen het kan begrijpen.';
    $txt['no_messages'] = 'Er zijn nog geen berichten.';
    $txt['quote_this_message'] = 'Quote dit bericht';
    $txt['edit_this_message'] = 'Bewerk dit bericht';
    $txt['delete_this_message'] = 'Verwijder dit bericht';
    $txt['first_login'] = 'Je moet je inloggen voordat je kunt reageren.';
    $txt['topic_closed_no_reply'] = 'Dit <strong>topic is gesloten</strong> en je kan dus niet reageren.';
    $txt['colorbox_text'] = 'Open this window again and this message will still be here.';
    $txt['add_message'] = 'Reageren:';
    $txt['link_text_effects'] = '<u><a href="codes.php?category=forum" class="colorbox" title="Tekst effecten voor forum"><b>Hier</b></a></u> kun je zien hoe je tekst effecten moet toepassen of plaatjes invoegen.';
    $txt['button'] = 'Verstuur reactie';
}

######################## Beginning ########################
elseif($page == 'beginning'){
    #Screen
    $txt['pagetitle'] = 'Het begin';
    $txt['title_text'] = 'Welkom op '.GLOBALDEF_SITEDOMAIN.'.<br />
							  Mijn naam is professor Oak. <br />
							  Dit zijn de regels van '.GLOBALDEF_SITEDOMAIN.'.<br /><br />
							  * '.GLOBALDEF_SITENAME.' niet in een slecht daglicht zetten.<br />
							  * Respecteer beheerders en andere spelers.<br />
							  * Niet schelden tegen andere spelers.<br />
							  * Niet bedelen bij de admins of moderators.<br />
							  * Niet adverteren voor andere spellen.<br /><br />
							  Als je je niet aan deze regels houdt word je verbannen van de site.';
    $txt['button']	= 'Ga verder';
}

######################## Choose Pokemon ########################
elseif($page == 'choose-pokemon'){
    #Alerts
    $txt['alert_no_pokemon'] = 'Je hebt geen Pok&eacute;mon gekozen.';
    $txt['alert_pokemon_unknown'] = 'Pok&eacute;mon dat je gekozen hebt is niet beschikbaar.';
    $txt['success'] = 'Je hebt een Pok&eacute;mon van Professor Oak gekregen, je wordt nu doorgestuurd...';

    #Screen
    $txt['pagetitle'] = 'Kies een Pok&eacute;mon';
    $txt['title_text'] = 'Okee, genoeg gepraat over al die regels.<br /><br />
							  Ik wil jou een Pok&eacute;mon geven, omdat ik denk dat je er klaar voor bent.<br />
							  Hieronder is een lijst met Pok&eacute;mon die ik voor je beschikbaar heb.';
    $txt['#'] = '#';
    $txt['starter_pokemon'] = 'Starter Pok&eacute;mon';
    $txt['normal_pokemon'] = 'Normale Pok&eacute;mon';
    $txt['baby_pokemon'] = 'Baby Pok&eacute;mon';
    $txt['starter_name'] = 'Starter naam';
    $txt['type'] = 'Type';
    $txt['normal_name'] = 'Normaal naam';
    $txt['baby_name'] = 'Baby naam';
    $txt['no_pokemon_this_world'] = 'Er zijn geen Baby Pok&eacute;mon in deze wereld.';
    $txt['button']	= 'Kies';
}

######################## Error page ########################
elseif($page == 'error'){
    #Screen
    $txt['pagetitle'] = 'Error';
    $txt['title_text'] = 'Sorry, deze pagina is niet toegankelijk voor jou. Log in met een account om deze pagina te zien.';
}

######################## Attack Map ########################
elseif($page == 'attack/attack_map'){
    #Alerts
    $txt['alert_no_fishing_rod'] = 'Je hebt geen Fishing rod.';
    $txt['alert_no_cave_suit'] = 'Je hebt geen Cave suit.';
    $txt['alert_error'] = 'Er is een fout opgetreden. Meld dit a.u.b. aan <a href=\"?page=send-message&player=Skank\">Skank</a>.<br /> Vermeld de wereld, het gebied en de error:';
    $txt['alert_no_pokemon'] = 'Je hebt geen levende Pok&eacute;mon opzak.';

    #Screen
    $txt['pagetitle'] = 'Attackmap';
    $txt['title_text'] = 'Kies je plek waar je tegen een Pok&eacute;mon wilt vechten!';
}

######################## Attack Gyms ########################
elseif($page == 'attack/gyms'){
    #Alerts
    $txt['alert_itemplace'] = 'Let op: U heeft geen itemplek over, dus u wint de HM niet als u nu een gevecht aangaat.';
    $txt['alert_rank_too_less'] = 'Je rank is niet hoog genoeg voor deze gym.';
    $txt['alert_wrong_world'] = 'Je zit in de verkeerde wereld.';
    $txt['alert_gym_finished'] = 'Je hebt deze gym al behaald.';
    $txt['alert_no_pokemon'] = 'Je hebt geen Pok&eacute;mon opzak.';
    $txt['begindood'] = "Al je Pok&eacute;mon die je opzak hebt zijn knock out.";

    #Screen
    $txt['pagetitle'] = 'Gyms';
    $txt['finished'] = 'Gehaald';
    $txt['rank_too_less'] = 'Rank te laag';
    $txt['leader'] = 'Leader:';
    $txt['from_rank'] = 'Vanaf rank';
}

######################## Attack Duel invite ########################
elseif($page == 'attack/duel/invite'){
    #Alerts
    $txt['alert_not_yourself'] = 'Je kunt jezelf niet uitdagen.';
    $txt['alert_youre_not_premium'] = 'Jij bent geen premium speler, dus je kunt niemand uitdagen.';
    $txt['alert_unknown_amount'] = 'Ongeldig bedrag.';
    $txt['alert_not_enough_silver'] = 'Je hebt niet genoeg silver.';
    $txt['alert_all_pokemon_ko'] = 'Al je Pok&eacute;mon zijn knock out.';
    $txt['alert_opponent_not_premium'] = 'is geen premiumlid.';
    $txt['alert_opponent_not_in'] = 'zit niet in';
    $txt['alert_opponent_not_traveller'] = 'is nog geen rank Traveller.';
    $txt['alert_opponent_duelevent_off'] = 'heeft dueluitnodigingen uitgeschakeld.';
    $txt['alert_opponent_already_fighting'] = 'is bezig met een gevecht.';
    $txt['waiting_for_accept'] = 'is uitgedaagd, wachten totdat hij/zij accepteert.';
    $txt['alert_opponent_no_silver'] = 'Je tegenstander heeft geen geld.';
    $txt['alert_opponent_no_health'] = 'Je tegenstander geen levende Pok&eacute;mon.';
    $txt['alert_user_unknown'] = 'Gebruikersnaam bestaat niet.';

    #Screen
    $txt['pagetitle'] = 'Duel';
    $txt['title_text'] = '<p><img src="images/icons/duel.png" /> <strong>Daag een speler uit voor een duel.</strong> <img src="images/icons/duel.png" /><br />
    Let op, je tegenstander moet online zijn.</p>';
    $txt['player'] = 'Gebruikersnaam:';
    $txt['money'] = 'Inzet:';
    $txt['button_duel'] = 'Daag uit';
}

######################## Attack Duel invited ########################
elseif($page == 'attack/duel/invited'){
    #Alerts
    $txt['alert_not_enough_silver'] = 'Je hebt niet genoeg silver.';
    $txt['alert_all_pokemon_ko'] = 'Al je Pok&eacute;mon zijn knock out.';
    $txt['success_accepted'] = 'Je hebt het duel geaccepteerd.';
    $txt['success_cancelled'] = 'Je hebt het duel geannuleerd.';
    $txt['alert_too_late'] = 'had je uitgedaagd voor een duel. Je was helaas te laat.';

    #Screen
    $txt['pagetitle'] = 'Je word uitgedaagd';
    $txt['dueltext_1'] = 'Dit duel heeft een inzet van:';
    $txt['dueltext_2'] = 'daagt je uit voor een duel.';
    $txt['accept'] = 'Accepteer';
    $txt['cancel'] = 'Annuleer';
}

######################## Attack Wild ########################
elseif($page == 'attack/wild/wild-attack'){
    #Screen
    $txt['you_won'] = 'Jij hebt gewonnen.';
    $txt['you_lost'] = 'Jij hebt verloren.';
    $txt['you_lost_1'] = 'Je verliest <img src=\'images/icons/silver.png\' title=\'Silver\'>';
    $txt['you_lost_2'] = '<br/><br/><a href=\'?page=pokemoncenter\'><img src=\'/images/pokemoncenter.gif\' title=\'Pokemoncenter\'><br/>Naar het Pok&eacute;moncenter.</a><br/><br/>';
    $txt['you_first_attack'] = 'Jij mag de eerste aanval doen.';
    $txt['opponent_first_attack'] = 'mag de eerste aanval doen.';
    $txt['opponents_turn'] = 'mag aanvallen.';
    $txt['your_turn'] = 'Jij mag aanvallen.';
    $txt['have_to_change_1'] = 'Je';
    $txt['have_to_change_2'] = 'is knock out, je moet wisselen.';
    $txt['next_time_wait'] = 'Wacht voortaan totdat het gevecht is afgelopen.';
    $txt['fight_finished'] = 'Gevecht is al afgelopen.';
    $txt['success_catched_1'] = 'Je hebt';
    $txt['success_catched_2'] = 'gevangen!';
    $txt['no_item_selected'] = 'Je moet wel een item kiezen!';
    $txt['potion_no_pokemon_selected'] = 'Je moet wel een Pok&eacute;mon kiezen!';
    $txt['busy_with_attack'] = 'Bezig met aanval.';
    $txt['have_already'] = 'Je hebt al een';
    $txt['a_wild'] = 'een wilde';
    $txt['potion_text'] = 'Which Pok&eacute;mon do you want to give the';
    $txt['*'] = '*';
    $txt['pokemon'] = 'Pok&eacute;mon';
    $txt['level'] = 'Level';
    $txt['health'] = 'Health';
    $txt['potion_egg_text'] = 'Niet van toepassing';
    $txt['button_potion'] = 'Give';
    $txt['attack'] = 'Aanval';
    $txt['change'] = 'Wissel';
    $txt['items'] = 'Items';
    $txt['button_item'] = 'Gebruik';
    $txt['must_attack'] = 'moet aanvallen';
    $txt['is_ko'] = 'is knock out';
    $txt['flinched'] = 'is flinched';
    $txt['sleeps'] = 'slaapt.';
    $txt['awake'] = 'is wakker geworden.';
    $txt['frozen'] = 'is bevroren.';
    $txt['no_frozen'] = 'is ontdooit.';
    $txt['not_paralyzed'] = 'is niet langer paralyzed.';
    $txt['paralyzed'] = 'is paralyzed.';
    $txt['fight_over'] = 'Het gevecht is afgelopen.';
    $txt['choose_another_pokemon'] = 'Kies een andere Pok&eacute;mon.';
    $txt['use_attack_1'] = 'deed';
    $txt['use_attack_2'] = ', hij raakt. Je Pok&eacute;mon is Knock Out.<br />';
    $txt['use_attack_2_hit'] = ', hij raakt.';
    $txt['did'] = 'doet';
    $txt['hit!'] = ', raak!';
    $txt['your_attack_turn'] = '<br />Het is nu jouw beurt.';
    $txt['opponent_choose_attack'] = 'kiest een aanval.';

    $txt['pagetitle'] = 'Wilde Pok&eacute;mon gevecht';

    //Start Fight
    $txt['begindood'] = "Al je Pok&eacute;mon die je opzak hebt zijn knock out.";
    $txt['opponent_error'] = "Error: Geen tegenstander Bekend.";

    //Attack General
    $txt['success_catched_1'] = "Je hebt ";
    $txt['success_catched_2'] = "gevangen. Het gevecht is afgelopen.";
    $txt['new_pokemon_dead']   = " kan niet vechten. Hij is knock out!";
    $txt['not_your_turn'] = " moet aanvallen.";

    //Change Pokemon
    $txt['change_block'] = "Je bent geraakt door Block je kunt niet ruilen!";
    $txt['change_egg']  = "Je kunt geen ei inbrengen!";
    $txt['success_change_1']  = "Je wisselt van Pok&eacute;mon.";
    $txt['success_change_2'] = "mag nu aanvallen.";
    $txt['success_change_you_attack'] = "Je wisselt van Pok&eacute;mon. Je mag nu aanvallen";

    //Use Pokeball
    $txt['ball_choose'] = "Kies een item dat je bezit, of doe een aanval.";
    $txt['hand_house_full'] = "Je hebt geen ruimte meer voor een nieuwe Pok&eacute;mon.";
    $txt['ball_have'] = "Je moet wel een pokeball gebruiken.";
    $txt['ball_amount'] = "Je hebt geen ";
    $txt['ball_throw_1'] = "Je gooit een ";
    $txt['ball_throw_2'] = ". Je hebt ";
    $txt['ball_success'] = " gevangen.";
    $txt['ball_failure'] = " niet gevangen.";
    $txt['ball_success_2'] = " zit nu in je huis.";

    //Use potion
    $txt['potion_choose'] = "Kies een item dat je bezit, of doe een aanval.";
    $txt['potion_have'] = "Je moet wel een potion gebruiken.";
    $txt['potion_life_full'] = " heeft al vol leven.";
    $txt['potion_amount'] = "Je hebt geen ";
    $txt['potion_life_zero_1'] = "Je kunt ";
    $txt['potion_life_zero_2'] = " niet healen";
    $txt['potion_give_1'] = "Je geeft ";
    $txt['potion_give_2'] = " een ";
    $txt['potion_give'] = "Je gebruikt een ";
    $txt['potion_give_end_1'] = " is genezen";
    $txt['potion_give_end_2'] = " is weer half levend";
    $txt['potion_give_end_3'] = " is weer levend";

    //Run
    $txt['success_run'] = "Je bent ontsnapt van ";
    $txt['failure_run'] = "Je kon niet ontkomen van ";

    //Function
    $txt['recieve'] = "krijgt";
    $txt['recieve_boost'] = "krijgt een boosted";
    $txt['recieve_premium_boost'] = "krijgt een premiumboosted";
    $txt['recieve_boost_and_premium'] = "krijgt een boosted en premium boosted";

    $txt['exp_points'] = "exp punten.";
}
######################## Trainer Attack ########################
elseif($page == 'attack/trainer/trainer-attack'){
    #Screen
    $txt['you_won'] = 'Jij hebt gewonnen.';
    $txt['you_lost'] = 'Jij hebt verloren.';
    $txt['you_lost_1'] = 'Je verliest <img src=\'images/icons/silver.png\' title=\'Silver\'>';
    $txt['you_lost_2'] = '<br/><br/><a href=\'?page=pokemoncenter\'><img src=\'/images/pokemoncenter.gif\' title=\'Pokemoncenter\'><br/>Naar het Pok&eacute;moncenter.</a><br/><br/>';
    $txt['you_first_attack'] = 'Jij mag de eerste aanval doen.';
    $txt['opponent_first_attack'] = 'mag de eerste aanval doen.';
    $txt['opponents_turn'] = 'mag aanvallen.';
    $txt['your_turn'] = 'Jij mag aanvallen.';
    $txt['have_to_change_1'] = 'Je';
    $txt['have_to_change_2'] = 'is knock out, je moet wisselen.';
    $txt['next_time_wait'] = 'Wacht voortaan totdat het gevecht is afgelopen.';
    $txt['fight_finished'] = 'Gevecht is al afgelopen.';
    $txt['success_catched_1'] = 'Je hebt';
    $txt['success_catched_2'] = 'gevangen!';
    $txt['no_item_selected'] = 'Je moet wel een item kiezen!';
    $txt['potion_no_pokemon_selected'] = 'Je moet wel een Pok&eacute;mon kiezen!';
    $txt['busy_with_attack'] = 'Bezig met aanval.';
    $txt['have_already'] = 'Je hebt al een';
    $txt['a_wild'] = 'een wilde';
    $txt['potion_text'] = 'Which Pok&eacute;mon do you want to give the';
    $txt['*'] = '*';
    $txt['pokemon'] = 'Pok&eacute;mon';
    $txt['level'] = 'Level';
    $txt['health'] = 'Health';
    $txt['potion_egg_text'] = 'Niet van toepassing';
    $txt['button_potion'] = 'Give';
    $txt['attack'] = 'Aanval';
    $txt['change'] = 'Wissel';
    $txt['items'] = 'Items';
    $txt['button_item'] = 'Gebruik';
    $txt['must_attack'] = 'moet aanvallen';
    $txt['is_ko'] = 'is knock out';
    $txt['flinched'] = 'is flinched';
    $txt['sleeps'] = 'slaapt.';
    $txt['awake'] = 'is wakker geworden.';
    $txt['frozen'] = 'is bevroren.';
    $txt['no_frozen'] = 'is ontdooit.';
    $txt['not_paralyzed'] = 'is niet langer paralyzed.';
    $txt['paralyzed'] = 'is paralyzed.';
    $txt['fight_over'] = 'Het gevecht is afgelopen.';
    $txt['choose_another_pokemon'] = 'Kies een andere Pok&eacute;mon.';
    $txt['use_attack_1'] = 'deed';
    $txt['use_attack_2'] = ', hij raakt. Je Pok&eacute;mon is Knock Out.<br />';
    $txt['use_attack_2_hit'] = ', hij raakt.';
    $txt['did'] = 'doet';
    $txt['hit!'] = ', raak!';
    $txt['your_attack_turn'] = '<br />Het is nu jouw beurt.';
    $txt['opponent_choose_attack'] = 'kiest een aanval.';
    $txt['start_0'] = "Jij daagt ";
    $txt['start_1'] = " uit.";
    $txt['appears'] = " verschijnt.";
    $txt['defeated_1'] = "Jij verslaat";
    $txt['defeated_2'] = "je krijgt ";
    $txt['defeated_masterball'] = '';
    $txt['get_badge_1'] = '';
    $txt['get_badge_2'] = '';
    $txt['no_badgecase'] = '';
    $txt['has_defeated_you_1'] = 'heeft je verslagen.';
    $txt['has_defeated_you_2'] = ' Hij steelt ';
    $txt['bringed'] = 'brengt';

    $txt['pagetitle'] = 'Trainer gevecht';

    //Start Fight
    $txt['begindood'] = "Al je Pok&eacute;mon die je opzak hebt zijn knock out.";
    $txt['opponent_error'] = "Error: Geen tegenstander Bekend.";

    //Attack General
    $txt['new_pokemon_dead']   = " kan niet vechten. Hij is knock out!";
    $txt['not_your_turn'] = " moet aanvallen.";

    //Change Pokemon
    $txt['change_block'] = "Je bent geraakt door Block je kunt niet ruilen!";
    $txt['change_egg']  = "Je kunt geen ei inbrengen!";
    $txt['success_change_1']  = "Je wisselt van Pok&eacute;mon.";
    $txt['success_change_2'] = "mag nu aanvallen.";
    $txt['success_change_you_attack'] = "Je wisselt van Pok&eacute;mon. Je mag nu aanvallen";

    //Use potion
    $txt['potion_choose'] = "Kies een item dat je bezit, of doe een aanval.";
    $txt['potion_have'] = "Je moet wel een potion gebruiken.";
    $txt['potion_life_full'] = " heeft al vol leven.";
    $txt['potion_amount'] = "Je hebt geen ";
    $txt['potion_life_zero_1'] = "Je kunt ";
    $txt['potion_life_zero_2'] = " niet healen";
    $txt['potion_give_1'] = "Je geeft ";
    $txt['potion_give_2'] = " een ";
    $txt['potion_give'] = "Je gebruikt een ";
    $txt['potion_give_end_1'] = " is genezen";
    $txt['potion_give_end_2'] = " is weer half levend";
    $txt['potion_give_end_3'] = " is weer levend";

    //Function
    $txt['recieve'] = "krijgt";
    $txt['recieve_boost'] = "krijgt een boosted";
    $txt['exp_points'] = "exp punten.";
}
######################## Attack Duel ########################
elseif($page == 'attack/duel/duel-attack'){
    #Screen
    $txt['a_boosted'] = 'krijgt een boosted';
    $txt['exp_points'] = "exp punten.";
    $txt['recieve'] = 'krijgt';
    $txt['too_late_lost'] = 'Jij was te laat! Je hebt verloren';
    $txt['you_won_dus'] = 'Jij hebt dus gewonnen';

    $txt['you_won'] = 'Jij hebt gewonnen.';
    $txt['you_lost'] = 'Jij hebt verloren.';
    $txt['you_lost_1'] = 'Je verliest <img src=\'images/icons/silver.png\' title=\'Silver\'>';
    $txt['you_lost_2'] = '<br/><br/><a href=\'?page=pokemoncenter\'><img src=\'/images/pokemoncenter.gif\' title=\'Pokemoncenter\'><br/>Naar het Pok&eacute;moncenter.</a><br/><br/>';
    $txt['you_first_attack'] = 'Jij mag de eerste aanval doen.';
    $txt['opponent_first_attack'] = 'mag de eerste aanval doen.';
    $txt['has_invite_you'] = 'daagt jou uit.';
    $txt['you_invite_1'] = 'Jij daagt';
    $txt['you_invite_2'] = 'uit.';
    $txt['change_now'] = 'Jij moet nu wisselen.';
    $txt['opponent_change'] = 'moet nu wisselen.';
    $txt['opponents_turn'] = 'mag aanvallen.';
    $txt['your_turn'] = 'Jij mag nu aanvallen.';
    $txt['have_to_change'] = 'is knock out, je moet wisselen.';
    $txt['opponent_have_to_change_1'] = 'is knock out,';
    $txt['opponent_have_to_change_2'] = 'gaat wisselen.';
    $txt['fight_over_win'] = 'Het gevecht is afgelopen.<br>Jij hebt gewonnen.';
    $txt['fight_over_lost'] = 'Het gevecht is afgelopen.<br>Jij hebt verloren.';
    $txt['you_win'] = 'Jij wint';
    $txt['you_lose'] = 'Jij verliest';
    $txt['opponent_not_ready'] = 'Uw tegenstander is nog niet klaar.';
    $txt['too_late_lost'] = 'Uw was te laat, U heeft verloren.';
    $txt['opponent_too_late'] = 'Uw tegenstander was te laat, U heeft gewonnen.';
    $txt['fight_over'] = 'Het gevecht is afgelopen.';
    $txt['opponent_must_change'] = 'Uw tegenstander moet wisselen.';
    $txt['opponent_must_attack'] = 'Uw tegenstander moet aanvallen.';
    $txt['pokemon_is_ko'] = 'Pok&eacute;mon is knock out';
    $txt['opponent_have_changed_you_attack'] = 'heeft gewisseld, jij mag nu aanvallen.';
    $txt['you_have_changed_opponent_attack'] = 'heeft gewisseld en mag aanvallen.';
    $txt['you_have_to_change'] = ', raak! <br />Jij moet nu wisselen.';
    $txt['opponent_have_to_change'] = 'Tegenstander moet wisselen.';
    $txt['youre_defeated'] = ', Jij bent verslagen.';
    $txt['busy_with_attack'] = 'Bezig met aanval.';
    $txt['*'] = '*';
    $txt['pokemon'] = 'Pok&eacute;mon';
    $txt['level'] = 'Level';
    $txt['health'] = 'Health';
    $txt['potion_egg_text'] = 'Niet van toepassing';
    $txt['button_potion'] = 'Give';
    $txt['you_doet'] = 'Jij doet';
    $txt['attack'] = 'Aanval';
    $txt['change'] = 'Wissel';
    $txt['time'] = 'Tijd';
    $txt['seconds_left'] = 'sec over.';
    $txt['bringed'] = 'brengt';
    $txt['must_attack'] = 'moet aanvallen';
    $txt['is_ko'] = 'is knock out';
    $txt['flinched'] = 'is flinched';
    $txt['sleeps'] = 'slaapt.';
    $txt['awake'] = 'is wakker geworden.';
    $txt['frozen'] = 'is bevroren.';
    $txt['no_frozen'] = 'is ontdooit.';
    $txt['not_paralyzed'] = 'is niet langer paralyzed.';
    $txt['paralyzed'] = 'is paralyzed.';
    $txt['choose_another_pokemon'] = 'Kies een andere Pok&eacute;mon.';
    $txt['use_attack_1'] = 'deed';
    $txt['use_attack_2'] = ', hij raakt. Je Pok&eacute;mon is Knock Out.<br />';
    $txt['use_attack_2_hit'] = ', hij raakt.';
    $txt['did'] = 'deed';
    $txt['hit!'] = ', raak!';
    $txt['your_attack_turn'] = '<br />Het is nu jouw beurt.';
    $txt['opponent_choose_attack'] = 'kiest een aanval.';
    $txt['opponent_choose_pokemon'] = 'Kiest een Pok&eacute;mon.';

    $txt['pagetitle'] = 'Trainer gevecht';
}
######################## Catched ########################
elseif($page == 'catched'){
    #Screen
    $txt['shiny'] = 'Shiny';
    $txt['normal'] = 'Normal';
    $txt['amount_caught'] = "is al ".$query2." keer gevangen.";
}
elseif($page == 'clan-invite'){
    #Alerts
    $txt['alert_no_clan_leader'] = "Je bent geen clan leider.";
    $txt['alert_no_name'] = "Vul een naam in.";
    $txt['alert_clan_full'] = "Je clan is vol, Je moet je clan upgraden om voor meer leden.";
    $txt['alert_already_in_clan'] = "De speler heeft al een clan.";
    $txt['alert_player_does_not_exist'] = "De speler bestaat niet.";
    $txt['invite_text'] = '<img src="images/icons/blue.png" width="16" height="16" class="imglower"> ' . $gebruiker['username'] . ' heeft jou uitgenodigd voor clan <strong>' . $clan['clan_naam'] . '</strong>.<a href="?page=clan-invite2&id=' . $claninputid . '&code=' . $code . '&accept=1">Accepteren</a>, <a href="?page=clan-invite2&id=' . $claninputid . '&code=' . $code . '&accept=0">Weigeren</a>.';
    $txt['invite_sent'] = 'Uitnodiging verzonden naar ' . $_POST['naam'] . '';

    #Screen
    $txt['invite_a_player_for_clan'] = "Nodig een speler uit voor ".$clan['clan_naam'].".";
    $txt['max_invite_text'] = "<p>Een level ".$clan['clan_level']." clan kan ".$clanmembers." leden hebben.</p><p>Je kan nog ".$claninvites." leden uitnodigen.</p>";
    $txt['invite_button'] = "Invite";
    $txt['outstanding_invites'] = "Geen uitstaande uitnodigingen.";
    $txt['no_clan'] = "<center>Je hebt geen clan, maak <a href='?page=clan-make'>hier</a> een clan aan.</center>";
}