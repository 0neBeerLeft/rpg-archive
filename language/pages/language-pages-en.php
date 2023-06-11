<?php
######################## Home ########################
if(($page == 'home') OR ($page == '')){
    #Screen
    $txt['pagetitle'] = 'Home';
}

######################## Page not found ######################
elseif($page == 'notfound'){
    #Not found page
    $txt['pagetitle'] = 'Page not found';
    $txt['notfoundtext'] = '<p>Page not found. The page you where looking for might be deleten, had a name change, or is temporary offline.<br /><br />
						<strong>Please try the following:</strong><br /><br />
						1. Correct the error, if you have typed the adres in your adres bar.<br />
						2. Use one of the links in the main menu.<br />
						3. Use the back button of your browser.<br />
						4. Log out the game and try again.</p>';
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
    $txt['alert_already_this_ip'] = 'You already have an account with this IP.<br>
										 You can create a new account next week.';
    $txt['alert_no_firstname'] = 'Please fill in a firstname';
    $txt['alert_firstname_too_long'] = 'Firstname is too long, max 12 characters.';
    $txt['alert_no_lastname'] = 'Please fill in a lastname';
    $txt['alert_lastname_too_long'] = 'Firstname is too long, max 12 characters.';
    $txt['alert_no_country'] = 'Please choose a country.';
    $txt['alert_no_full_gebdate'] = 'Please fill in all fields';
    $txt['alert_character_invalid'] = 'Personallity not available.';
    $txt['alert_no_username'] = 'Please fill in a username.';
    $txt['alert_username_too_short'] = 'A username needs to be longer than 3 characters.';
    $txt['alert_username_too_long'] = 'A username must be shorter than 10 characters.';
    $txt['alert_username_exists'] = 'Username already taken.';
    $txt['alert_username_incorrect_signs'] = 'Username invalid.';
    $txt['alert_no_password'] = 'Please fill in a password.';
    $txt['alert_passwords_dont_match'] = 'Passwords do not match.';
    $txt['alert_no_email'] = 'Please fill in a e-mail.';
    $txt['alert_email_incorrect_signs'] = 'Invalid e-mail address.';
    $txt['alert_email_exists'] = 'E-mail already exists.';
    $txt['alert_no_beginworld'] = 'Please choose a world to start in.';
    $txt['alert_world_invalid'] = 'Please choose a world to start in.';
    $txt['alert_1account_condition'] = 'I agree to the terms and confirm that i only have one account.';
    $txt['alert_no_offend_condition'] = 'I agree to the terms and conditions';
    $txt['alert_guardcore_invalid'] = 'Please check the captcha';
    $txt['success_register'] = 'Your account creation was successfull.<br>
		An email has been sent to '.$_POST['email'].'.<br>In this email there are activation details of your game.<br />Note: the e-mail might end up in your spam folder.';

    #Screen
    $txt['pagetitle'] = 'Register';
    $txt['title_text'] = 'Sign up for '.GLOBALDEF_SITENAME.' for free and <strong>get one day premium!</strong> ';
    $txt['register_personal_data'] = 'Personal';
    $txt['register_game_data'] = 'Personal';
    $txt['register_security'] = 'Captcha';
    $txt['firstname'] = 'Firstname:';
    $txt['lastname'] = 'Lastname:';
    $txt['country'] = 'Country:';
    $txt['gebdate'] = 'Date of birth:';
    $txt['day'] = 'Day';
    $txt['month'] = 'Month';
    $txt['year'] = 'Year';
    $txt['character'] = 'Personality:';
    $txt['username'] = 'Username:';
    $txt['password'] = 'Password:';
    $txt['password_again'] = 'Repeat password:';
    $txt['email'] = 'E-mail:';
    $txt['beginworld'] = 'World:';
    $txt['1account_rule'] = 'I have a refer';
    $txt['referer'] = 'Referer:';
    $txt['not_oblige'] = '*Not required.';
    $txt['guardcode'] = 'Captcha code:';
    $txt['captcha'] = 'Captcha code';
    $txt['button'] = 'Signup!';
}

######################## INFORMATION ########################
elseif($page == 'information'){
    $txt['pagetitle'] = 'Informatie';
    $txt['link_subpage_game_info'] = 'Spel informatie';
    $txt['link_subpage_pokemon_info'] = 'Pokemon informatie';
    $txt['link_subpage_attack_info'] = 'Aanval informatie';

    if($_GET['category'] == 'game-info'){
        #Screen
        $txt['pagetitle'] .= ' - Game information';
        $txt['informationpage'] = '<h2>Content</h2>
				<div id="information">
				<ol>
				<li><a href="#thegame">The game</a></li>
				<li><a href="#rules">Rules</a></li>
				<li><a href="#begin">The beginning</a></li>
				<li><a href="#tips">Tips for the game</a></li>
				<li><a href="#silver&gold">Silver and gold</a></li>
				<li><a href="#pokecoins">Poke Coins</a></li>
				<li><a href="#pokemon">Pokemon</a></li>
				<li><a href="#ranks">Ranks</a></li>
				<li><a href="#attacks">TM\'s and HM\'s</a></li>
				<li><a href="#admins">Administrators</a></li>
				<li><a href="#moderators">Moderators</a></li>
				<li><a href="#register">Register</a></li>
				<li><a href="#contact">Contact</a></li>
				<li><a href="#activate">Activate account</a></li>
				<li><a href="#forgotusername">Forgot username</a></li>
				<li><a href="#forgotpassword">Forgot password</a></li>
				<li><a href="#accountoptions">Account options</a></li>
				<li><a href="#profile">Profile</a></li>
				<li><a href="#slogan">Slogan</a></li>
				<li><a href="#rankinglist">Rankinglist</a></li>
				<li><a href="#pokemoninfo">Pokemon information</a></li>
				<li><a href="#advertise">Advertising for gold</a></li>
				<li><a href="#modifyorder">Pokemon reorder</a></li>
				<li><a href="#sell">Sell &#8203;&#8203;a pokemon</a></li>
				<li><a href="#release">Release a pokemon</a></li>
				<li><a href="#items">Items</a></li>
				<li><a href="#badgecase">Badge box</a></li>
				<li><a href="#myhouse">My house</a></li>
				<li><a href="#pokedex">Pokedex</a></li>
				<li><a href="#buddyandblock">Buddylist and Blocklist</a></li>
				<li><a href="#sendmessage">Send message</a></li>
                <li><a href="#areamessenger">Pokemon messenger</a></li>
				<li><a href="#attack">Attack</a></li>
				<li><a href="#gyms">Gyms</a></li>
				<li><a href="#daymissions">Day Missions</a></li>
				<li><a href="#duel">Duel</a></li>
				<li><a href="#tournament">Tournament</a></li>
				<li><a href="#work">Work</a></li>
				<li><a href="#race">Race</a></li>
				<li><a href="#traders">Traders</a></li>
				<li><a href="#chooselevel">Choose a level</a></li>
				<li><a href="#premiumbank">Premium market</a></li>
				<li><a href="#pokemoncenter">Pokemon center</a></li>
				<li><a href="#market">Market</a></li>
				<li><a href="#bank">Bank</a></li>
				<li><a href="#boat">Rent a boat</a></li>
				<li><a href="#houseseller">Houses seller</a></li>
				<li><a href="#transferlist">Transferlist</a></li>
				<li><a href="#daycare">Daycare</a></li>
				<li><a href="#namespecialist">Name specialist</a></li>
				<li><a href="#shinyspecialist">Shiny specialist</a></li>
				<li><a href="#jail">Jail</a></li>
				<li><a href="#gamble">Gamble</a></li>
				</ol>
				<hr />
				<div id="thegame">
					<h2>The game</h2>
					'.GLOBALDEF_SITENAME.' is a multiplayer online game.<br />
					The game has 7 worlds and 808 pokemon!<br />
					Anyone can join for free, You can also pay for additional features.<br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="rules">
					<h2>Rules</h2>
					The rules are on '.GLOBALDEF_SITENAME.' are pretty simple:
					<ul>
						<li>Not advertise other websites.</li>
						<li>No spamming. (Spamming is a number of times in succession say something unnecessary).</li>
						<li>Ignoring warnings of a Crewmember may lead to a temporary Mute/Ban. If you keep doing this, is a possible permanent Mute/Ban in place.</li>
						<li>The Crew has the right to see and do everything, they can never be held responsible for.</li>
						<li>Cheating is strictly prohibited. If you make a mistake in the game and have discovered these benefits is not allowed. You will be heavily punished. Tell us so.</li>
						<li>No scolding, swearing and/or write vulgar language.</li>
						<li>Racist, offensive and/or accusatory expressions is not allowed</li>						
						<li>No arguing.</li>
						<li>Place no links on the chat.</li>
						<li>No private matters discuss through chat, forum and/or on your profile. Do this through Personal Message.</li>
						<li>No flooding (Flooding is the excessive repetition of the same text, word, letters, too many images and/or emoticons).</li>
						<li>Do not prompt for passwords. <strong>Never</strong> give your password away.</li>
						<li>No double account, only with permission of the owner.</li>		
                                       </ul>
					If you do one of these things we will mercilessly mute or ban you from the site.<br />
					How long the Mute or Ban will be depends on what you did wrong.<br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="begin">
					<h2>The beginning</h2>
					In the beginning, Professor Oak will come to you.<br />
				        He will be with you the rules once through and then he gives you a pokemon egg.<br />
					You have the choice between a number of pokemon depends on the world where you are at that moment.<br />
					After you get the pokemon you can immediately start the game.<br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="tips">
					<h2>Tips for the game</h2>
					<ul>
						<li>Play '.GLOBALDEF_SITENAME.' in a Mozilla Firefox browser.</li>
						<li>If you\'re on a public computer (At school or something), remember your password, then <strong> not </strong>.</li>
						<li>Also if you\'re on a public computer plays: Aways log out when you just walk away.</li>
						<li>If you go on vacation, put all your money in the bank and do your pokemon at the daycare.</li>
						<li>Buy Always balls before you start fighting. Who knows what you suddenly encounter.</li>
						<li>If you are looking for a pokemon, look at pokemon information where to find it.</li>
						<li>Become a premium, the additional features are very good.</li>
					</ul><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="silver&gold">
					<h2>Silver and gold</h2>
					You can pay in '.$game['titel'].' with silver and gold, here you can see where silver and gold for is:
					<ul>
						<li><img src="images/icons/silver.png" title="Silver"> = Silver.</li>
						<li><img src="images/icons/gold.png" title="Gold"> = Gold.</li>
					</ul>
					Silver can you get in the game by means work, defeat trainers, etc.<br />
					Gold you can buy in the premium market, this allows you to buy some things you can not with silver, like:
					<ul>
						<li>Buy master balls</li>
						<li>Buy rare candy\'s</li>
						<li>Change username</li>
						<li>Donate gold to other members</li>
						<li>Make from a pokemon, a shiny pokemon</li>
					</ul><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="pokemon">
					<h2>Pokemon</h2>
					'.GLOBALDEF_SITENAME.' has 808 different pokemon and all those pokemon are also in a shiny shape.<br />
					A shiny pokemon is the same okemon as a normal but rarer and another color.<br />
					You can recognize a shiny pokemon the <img src="images/icons/lidbetaald.png" /> the name and color of the pokemon.<br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<HR />
				<div id="ranks">
					<h2>Ranks</h2>
					There are 20 ranks on '.GLOBALDEF_SITENAME.':
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
						<li>Professional</li>
						<li>Hero</li>
						<li>King</li>
						<li>Champion</li>
						<li>Legendary</li>
						<li>Untouchable</li>
						<li>God</li>
						<li>Pokemon Master</li>
					</ol><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<HR />
				<div id="attacks">
					<h2>TM\'s and HM\'s</h2>
					There are on '.GLOBALDEF_SITENAME.' 92 TM\'s and 8 HM\'s.<br /><br />
					<strong>TM</strong><br />
					A TM can be bought at the market. You can give a TM only to a pokemon with the same type, so if it is an fire attack only a fire pokemon can learn. Some attacks have 2 types.<br />
					A TM can be used only 1 time.<br /><br />
					<strong>HM</strong><br />
					A HM can be earned by defeating a gymleader.<br />
					The HM can be used as often as you like.<br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="admins">
					<h2>Administrators</h2>
					There are always one or more administrators in a game.<br />
					The name of an administrator is red.<br /> These are the functions of a administrator:
					<ul>
						<li>Help people by answering their questions.</li>
					</ul>
					An administrator can read all messages everyone sends. This is sure to protect members.<br /><br />
					If you see an error in the game we would like to hear.<br />
					If someone insults you out if you are in any degree offended let us know, We do our best to have something to do.<br /><br />
					Do not ask if you can be an administrator. If we need a new, then we choose them.<br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="moderators">
					<h2>Moderators</h2>
					There are always one or more moderators in a game.<br />
					The name of a moderator is blue.<br />
                                        These are the functions of a moderator:
					<ul>
						<li>Help people by answering their questions.</li>
					</ul>
					A moderator is to ensure that the forum and/or chat is running flexible. For instance, if there\'s a fight he/she can intervene.<br /><br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="register">
					<h2>Register</h2>
					Fill in all fields when registering as well as possible.<br />
					Your email address must be valid because that makes your activation email sent there.<br />
					You check the bottom of the page that the only thing your account. Try not to lie about it.<br />
					Look after you have created your account as to which email address the email is sent. You can always make a typing mistake.<br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="contact">
					<h2>Contact</h2>
					If something goes wrong, you can always contact us. By this we mean:
					<ul>
						<li>Activation mail not received.</li>
						<li>The activation can not.</li>
						<li>I can not go on my account.</li>
					</ul>
					You may also email us for other things such as:
					<ul>
						<li>To be linkpartners.</li>
						<li>Questions about the site.</li>
					</ul>
					Contact can only be seen if you\'re not logged in. This is because when you are logged in you can send a message to an administrator in the game.<br /><br />
					We always try as soon as possible to send a message or email back.<br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="activate">
					<h2>Activate account</h2>
					Before you can play you must first activate your account.<br />
					The activation code is sent to your email address you specified when logging.<br /><br />
					If you are unable to activate the account please let us know by contact.<br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="forgotusername">
					<h2>Forgot username</h2>

					Enter your email and your username will be sent to your email address.<br /><br />
					If you do not receive email please let us know by contact.<br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="forgotpassword">
					<h2>Forgot password</h2>
					Enter your username and email and get a new password sent to your e-mail address.<br />
					Login with the new password and you can change your password in the game at Account options - Password.<br /><br />
					If you do not receive email please let us know by contact.<br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="accountoptions">
					<h2>Account options</h2>
					Account options has 5 different page\'s:<br /><br />
					<strong><img src="images/icons/user.png" /> Personal</strong><br />
					Here you can edit your data.<br />
					Please update your profile as honestly as possible to fill, so first name, last name, country, and sex.<br />
					You can also check whether your team and badges on your profile come to be.<br /><br />
					<span class="smalltext">Badges on your profile is only available if you have a badge box.</span><br /><br />
					<strong><img src="images/icons/key.png" /> Password</strong><br />
					Here you can change your password.<br />
					Try this easy to remember.<br /><br />
					<strong><img src="images/icons/images.png" /> Profile</strong><br />
					Everything you enter is below the bottom of your profile to stand.<br />
					If you click on <strong>Here</strong> there will appear a page where all codes as responsible:
					<ul>
						<li>Set youtube videos on your profile.</li>
						<li>Set a pokemon on your profile.</li>
						<li>Change text color.</li>
						<li>Etc. etc.</li>
					</ul>
					<strong><img src="images/icons/new.gif" /> Restart account</strong><br />
					You can restart your account 1 time at a day.<br />
					This is to allow members not every 15 minutes restart their account because they do not like their pokemon.<br />
					Suppose you do not like your pokemon you can always trade with someone. If this really does not work you can always consider about 24 hours to restart your account.<br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="profile">
					<h2>Profile</h2>
					Each member has his/her own profile.<br />
					Which is also accessible if you are not logged in through rankinglist.<br />
					Add to your profile no negative things about other players. Invective are not allowed.<br />
					You can make your profile very nice, see for Account options - Profile above.<br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="rankinglist">
					<h2>Rankinglist</h2>
					This is the list of all the members sorted by rank.<br />
					If you are not logged in you can also see this page. So know what you\'re doing!<br />
					The top 50 will also be given a medal on their profile.<br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="pokemoninfo">
					<h2>Pokemon information</h2>
					Select a pokemon and you find all the information you need.<br />
					You can even see where you can that find pokemon (which area + world).<br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="advertise">
					<h2>Advertising for gold</h2>
					You can send your '.GLOBALDEF_SITENAME.' link through Whatsapp, Forums, Facebook, etc..<br />
					For each registered member you get some gold!<br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="modifyorder">
					<h2>Pokemon reorder</h2>
					With this system you have the pokemon drag up or down.<br />
					It will automatically be saved.<br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="sell">
					<h2>Sell a pokemon</h2>
					You can sell a pokemon.Set him on the transferlist.<br />
					Someone can buy the pokemon you have set him on t he transferlist.<br /><br />
					A pokemon has a certain value, the asking price may be worth up to 1.5x and at least half of its value.<br />
					You can\'t sell your starter pokemon, who is too much attached to you.<br /><br />
					<span class="smalltext">A Premium Member may set 3 pokemon on the transferlist. A normal member 1.<br />
					You can sell a pokemon from rank 4 Casual.</span><br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="release">
					<h2>Release a pokemon</h2>
					You can release a pokemon on '.GLOBALDEF_SITENAME.'.<br />
					You\'ll get the ball back which caught, at least if your item box is not full.<br />
					You can\'t release your starter pokemon, who is too much attached to you.<br />
					<strong>Note:</strong> this can not be reversed.<br /><br />
					<span class="smalltext">You can release a pokemon from rank 5 Trainer.</span><br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="items">
					<h2>Items</h2>
					Here I kept all the items you have.<br />
					They can also be used or sell.<br />
					You start with a bag, it can contain 20 items. If you want more items you have to buy in the market a Yellow box, Blue box or Red box.<br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="badgecase">
					<h2>Badge box</h2>
					For each world, you have a box for your badges.<br />
					The badges come automatically to lie here if you have them.<br />
					You get a badge box from the gymleader you as first defeat.<br /><br />
					This page is only accessible if you\'ve got the badge box from a gymleader.<br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="myhouse">
					<h2>My house</h2>
					In the normal pokemon games are the pokemon move in the pokemon center with a computer.<br />
					In '.GLOBALDEF_SITENAME.' is different, you have a house!<br />
					You start with a box, 2 pokemon here can stay in.<br />
					At the house seller you can buy a house which can accommodate more pokemon.<br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="pokedex">
					<h2>Pokedex</h2>
					<strong>Pokedex</strong><br />
					In your Pokedex is saved what pokemon you\'ve seen and what you have caught.<br />
					You should first buy a Pokedex at the market.<br /><br />
					<strong>Pokedex chip</strong><br />
					You can also buy at the market a Pokedex chip. that\'s a update for your Pokedex.<br />
					With this you can see the level of a wild pokemon.<br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="buddyandblock">
					<h2>Buddylist and Blocklist</h2>
					<strong>Buddylist</strong><br />
					The buddylist is easy to make someone to give money or send a message. You no longer need to search for a player.<br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="sendmessage">
					<h2>Send message</h2>
					You can send a message on '.GLOBALDEF_SITENAME.' to another member.<br />
					Think about what you are sending. When rules state what can and can not!<br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="areamessenger">
					<h2>Pokemon messenger</h2>
					Here you can talk with other members.<br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="attack">
					<h2>Attack</h2>
					First you choose an area where you want to fight.<br />
					Then a pokemon appear who wants to fight you.<br /><br />
					<strong>Shiny & Legendary pokemon</strong><br />
					You can even encounter Shiny pokemon or Legendary. It does not matter what rank you are, the probability of a Shiny or Legendary is always great.<br />
					Happiness mandatory course you off as much as possible to continue attacks.<br /><br />
					<strong>Trainer</strong><br />
					Sometimes a trainer or Team Rocket suddenly appears to fight you. You have a chance when the trainer or Team Rocket is defeated you get an item or some silver.<br /><br />
					<strong>Run</strong><br />
					Before you can run you first need to buy a bike in the market.<br />
					A bicycle works only a with wild pokemon, So not at a trainer battle.<br /><br />
					<strong>Lose</strong><br />
					If you lose 25% of your silver off, what cash you have with you.<br />
					Go therefore often along the pokemon center! Do not gamble on another pokemon because there is always a trainer emerge.<br /><br />
					<strong>Error code</strong><br />
					When suddenly an error code pops would be useful to mention them to an administrator. Tell then also the error code you saw.<br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="gyms">
					<h2>Gyms</h2>
					In each world are 8 gyms. You can only challenge a gym if you rank is high enough.
					<ul>
						<li><img src="images/icons/pokeball.gif"> = Pokemon is alive.</li>
						<li><img src="images/icons/pokeball_black.gif"> = Pokemon is Knock out.</li>
					</ul>
					You only win if all the pokemon from gymleader are Knock out.<br /><br />
					After the fight, the gymleader gives you what you deserve. This is usually a badge and some silver.<br /><br />
					<span class="smalltext">The gyms are only available from rank 8 Macho.</span><br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="duel">
					<h2>Duel</h2>
					You can also in '.GLOBALDEF_SITENAME.' duel against other members.<br />
					If you win you get the pickled price.<br /><br />
					It will be saved who wins.<br /><br />
					<span class="smalltext">Duel is only accessible if you\'re Premium, you can only duel against a Premium Member.</span><br />
					<span class="smalltext">Duel is only available from rank 5 Trainer.</span><br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="tournament">
					<h2>Tournament</h2>
					You can also participate in a tournament.<br />
					The member who wins goes a round further!<br /><br />
					<span class="smalltext">Tournament is only accessible if you\'re Premium and is only available from rank 5 Trainer.</span><br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="work">
					<h2>Work</h2>
					In '.GLOBALDEF_SITENAME.' you can also work for some silver.<br />
					The higher your rank how easy the work is. This also means that when you start you can not rob casino. The chance that this will happen is very small.<br /><br />
					<span class="smalltext">A Premium Member has additional job opportunities.</span><br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="race">
					<h2>Race</h2>
					You can race against other members.<br />
					The system automatically selects your fastest pokemon, race you there. Against the fastest of your opponent.<br />
					The pokemon can running up against a tree, etc.<br />
					If a race invitation already 3 days old it will be automatically deleted.<br /><br />
					<span class="smalltext">Racing is available from rank 4 Casual.</span><br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="traders">
					<h2>Traders</h2>
					There are also trainers who want to trade pokemon.<br />
					They tell you what pokemon they want and what they have for you.<br />
					Every day at 3:00 be the pokemon renewed.<br />
					If you have trade you can not trade for 3 days with the traders.<br /><br />
					<strong>Tip:</strong> exchange as much as possible because this will cause the pokemon after a battle more EXP points.<br /><br />
					<span class="smalltext">Traders are only available from rank 5 Trainer.</span><br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="chooselevel">
					<h2>Choose a level</h2>
					Choose a level is a function where you can check what level pokemon you want to encounter in the wild.<br /><br />
					<span class="smalltext">This function is only available from rank 16 Professional.</span><br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="premiumbank">
					<h2>Premium market</h2>
					In the Premium market, you can buy Premium days. The benefits of premium are able to each pack.<br /><br />
					We also sell discount packs. If you are underage, make sure that you have your parents approval<br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="pokemoncenter">
					<h2>Pokemon center</h2>
					You can heal your pokemon here again full of life.<br />
					It is free, it just takes some time.<br /><br />
					<span class="smalltext">A Premium member must wait 10 sec for the healing of the pokemon, and a normal member 30 seconds.</span><br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="market">
					<h2>Market</h2>
					In the market you can buy all the things you need:
					<ul>
						<li>Balls</li>
						<li>Potions</li>
						<li>Items</li>
						<li>Special items</li>
						<li>Stones</li>
						<li>Pokemon eggs</li>
						<li>TM\'s</li>
					</ul>
                              The pokemon eggs are each hour renewed.<br /><br />
					<span class="smalltext">Pokemon eggs are only available from rank 2 Junior.</span><br />
					<span class="smalltext">TM\'s are only available from rank 5 Trainer.</span><br />
					<span class="smalltext">Transform items are only available from rank 11 Elite Trainer.</span><br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="bank">
					<h2>Bank</h2>
					Functions of the Bank:
					<ul>
						<li>Deposit silver to the bank.</li>
						<li>Deposit silver from the bank.</li>
						<li>Transfer money to another member.</li>
					</ul>
					<strong>Tip:</strong> Deposit as much silver you have cash to the bank. Then no one can steal. Spread it on the day, not that you already out of turns in the morning.<br /><br />
					<span class="smalltext">A Premium Member can deposit money to the bank 10x per day, a normal member 5x.</span><br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="boat">
					<h2>Rent a boat</h2>
					You can rent a boat to sail to another world.<br />
					This takes no time, you\'re there in less than 1 second!<br />
					You paid tickets for all your pokemon + yourself.<br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="houseseller">
					<h2>Houses seller</h2>
					Buy a house on '.GLOBALDEF_SITENAME.'. The bigger the house, the more pokemon in it can stay.<br />
					You can not sell your house and you can only get better buy than you already have.<br /><br />
					If you have a Villa (the biggest house) house seller disappears from the menu.<br /><br />
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
					Here you can bring pokemon and the daycare is trying to train them.<br />
					Sometimes a pokemon even grows 2 levels per day!<br /><br />
					<strong>Egg:</strong><br />
					Sometimes you get a baby pokemon (an egg). You can pick it up at the daycare if there is 1.<br />
					There can only be an egg if the 2 pokemon are the same. For instance:<br />
					If you have a 2 Pikachu\'s and brings to the daycare, you get an egg with Pichu.<br />
					A Pikachu and Raichu will not work.<br /><br />
					If both are shiny pokemon, the pokemon from the egg will also be shiny.<br /><br />
					It works only with pokemon that are <strong>not</strong> rare.<br /><br />
					<strong>Tip:</strong> a Ditto can come in handy.<br /><br />
					<span class="smalltext">A Premium Member can bring 2 Pokemon at the daycare and a normal member 1 pokemon.<br />
					The daycare is available from rank 4 Casual.</span><br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="namespecialist">
					<h2>Name specialist</h2>
					The name specialist can change the name of a pokemon.<br />
					It costs <img src="images/icons/silver.png" title="Silver" style="margin-bottom: -3px;"/> 40 silver. And your name is changed to the name that you want<br />
					<strong>Note: </strong> the name may not be a scold name<br /><br />
					<span class="smalltext">The name specialist is available from rank 9 Gym Leader.</span><br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="shinyspecialist">
					<h2>Shiny specialist</h2>
					The shiny specialist can make a pokemon shiny with gold. Each pokemon has a certain amount of gold needed to be shiny.<br /> 
                    Due to the impact of the shiny gold \'evolving \' the pokemon into a shiny pokemon.<br /><br />
					<span class="smalltext">The shiny specialist is available from rank 10 Shiny Trainer.</span><br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="jail">
					<h2>Jail</h2>
					<strong>In jail</strong><br />
					Sometimes you gets in jail because you\'re trying to steal something at work, or do anything else wrong.<br />
					You can buyout yourself whether you should hope that someone else breaks you out (released).<br /><br />
					<strong>Visiting</strong><br />
					You can also go to visit someone in jail and buy him/her out, or break free.<br /><br />
					<a href="#wrapper">Go to top</a>
				</div>
				<hr />
				<div id="gamble">
					<h2>Gamble</h2>
					<strong>Toss a coin</strong><br />
                                        A simple game where you have to guess whether the coin is heads or tails.<br />
                                        If you do it right you win the stake back twice, in error, you lose the silver.<br /><br />
                                        <strong>Who is the quiz</strong><br />
                                        In the \'Who is the quiz\' you have to guess what the name is from the pokemon. <br /> <br />
                                        <span class="smallText">Each member can do this 1 time per hour.</span><br /><br />
										<strong>Cracking the safe</strong><br />
					                    At \'Cracking the safe\' you have to guess the code. If you are wrong 200 Silver is going in the pot.
					                    If you got it right, you win the pot and then it get reset and put the pot back on 1000 Silver..<br /><br />	
                                        <strong>Wheel of Fortune</strong><br />
                                        Wheel of Fortune is an additional function to free what to get. Spin the wheel and see what your price is.<br /><br />
                                        <span class="smallText">A Premium Member can spin the wheel 3x per day, a normal member 1x.</span><br /><br />
                                        <strong>Lottery</strong><br />
                                         Lottery is a very nice variation to gamble.<br />
                                         Members can buy tickets up to 10 per lottery.<br />
                                         The lottery will be raffled at the time what it says. The winner gets all the inlaid money.<br /><br />
                                         <span class="smallText">The Premium lottery is only available to Premium members. Here you win the inlaid money + a Rare Candy.</span><br /><br />
                                         <strong>Pokemonrace</strong><br />
                                         The pokemonrace is a very nice variation to gamble.<br />
                                         Members can fill in 5 gold per race.<br />
                                         The pokemonrace get paid at 22:00 hours. The winner gets his inlaid gold time 2,3,4 or 5 back.<br /><br />
                                         <span class="smallText">The pokemonrace is only available to Premium members.</span><br /><br />
                                         
					<a href="#wrapper">Go to top</a>
				</div>
				</div>';
    }

    ######################## POKEMON INFO ########################
    elseif($_GET['category'] == 'pokemon-info'){
        #Screen
        $txt['pagetitle'] .= ' - Pokemon informatie';
        $txt['choosepokemon'] = 'Choose a Pok&eacute;mon:';
        $txt['choose_a_pokemon'] = 'Choose a Pok&eacute;mon.';
        $txt['not_rare'] = 'common';
        $txt['a_bit_rare'] = 'a little rare';
        $txt['very_rare'] = 'very rare';
        $txt['not_a_favorite_place'] = 'Does not have a favorite spot.';
        $txt['is_his_favorite_place'] = 'is his favorite spot.';
        $txt['is'] = 'is';
        $txt['lives_in'] = 'Lives in';
        $txt['how_much_1'] = 'There are';
        $txt['how_much_2'] = 'in the game.';
        $txt['attack&evolution'] = 'Attack & Evolution';
        $txt['no_attack_or_evolve'] = 'Does not evolve or learn new attacks.';
        $txt['level'] = 'Level';
        $txt['evolution'] = 'Evolution';
    }
    elseif($_GET['category'] == 'attack-info'){
        #Screen
        $txt['pagetitle'] .= ' - Attack information';
        $txt['#'] = '#';
        $txt['name'] = 'Name';
        $txt['type'] = 'Type';
        $txt['att'] = 'Att';
        $txt['acc'] = 'Acc';
        $txt['effect'] = 'Effect';
        $txt['ready'] = 'Done';
    }
}

######################## STATISTICS ########################
elseif($page == 'statistics'){
    #Screen
    $txt['pagetitle'] = 'Statistics';
    $txt['top6_pokemon_title'] = 'Top team of '.$game['titel'].'<br /><span class="smalltext">Based on all stats.</span>';
    $txt['game_data'] = 'Game statistics';
    $txt['users_total'] = 'Number of members:';
    $txt['silver_in_game'] = 'Silver in the game:';
    $txt['pokemon_total'] = 'Number of Pok&eacute;mon:';
    $txt['matches_played'] = 'Total fights:';
    $txt['top5_silver_users'] = 'Top 5 silver';
    $txt['#'] = '#';
    $txt['who'] = 'Who';
    $txt['silver'] = 'Silver';
    $txt['top5_pokemon_total'] = 'Top 5 Pok&eacute;mon';
    $txt['number'] = 'Amount';
    $txt['top5_matches_played'] = 'Top 5 <br /><span class="smalltext">Battles won - Battles lost.</span>';
    $txt['matches'] = 'Battles';
    $txt['top10_new_users'] = 'Top 10 newest members';
    $txt['when'] = 'When';

    $txt['strongest'] = 'Strongest legend';
    $txt['strongest_not'] = 'Strongest nonlegend';
    $txt['statistics'] = 'Statistics';
}
######################## RANKINGLIST ########################
elseif($page == 'rankinglist'){
    #Screen
    $txt['pagetitle'] = 'Rankinglist';
    $txt['#'] = '#';
    $txt['username'] = 'Username';
    $txt['country'] = 'Country';
    $txt['rank'] = 'Rank';
    $txt['status'] = 'Status';
    $txt['online'] = 'Online';
    $txt['offline'] = 'Offline';
}

######################## CLAN-MAKE ########################
elseif($page == 'clan-make'){
    #Alerts
    $txt['clan_name_required'] = 'A clan name is required';
    $txt['not_longer_than_20'] = 'The name of your clan cannot be larger than 20 characters';
    $txt['clan_name_already_in_use'] = 'This clan already exists.';
    $txt['description_required'] = 'A description is required.';
    $txt['description_max'] = 'The description cannot be larger than 20 characters';
    $txt['clan_type_required'] = 'Select a type.';
    $txt['already_in_a_clan'] = 'You already have a clan.';
    $txt['text_only'] = 'The clan name may only contain alpha numeric characters.';
    $txt['captcha_invalid'] = 'Captcha invalid.';
    $txt['not_enough_gold'] = 'Insufficient gold.';
    $txt['created_successfully'] = 'succesfully created.';

    #Screen
    $txt['create_a_clan_for'] = 'Create your own clan for';
    $txt['gold'] = 'gold';
    $txt['clan_name'] = 'Clan name';
    $txt['description'] = 'Description';
    $txt['type'] = 'Type';
    $txt['select'] = 'Select';
    $txt['security'] = 'Security';
    $txt['button_create'] = 'Create';
}

######################## CLAN-RANK ########################
elseif($page == 'clan-rank'){
    #Screen
    $txt['#'] = '#';
    $txt['clan_name'] = 'Clan name';
    $txt['members'] = 'Members';
    $txt['owner'] = 'Owner';
    $txt['level'] = 'Level';
    $txt['gold'] = 'Gold';
    $txt['silver'] = 'Silver';
}

######################## CLAN-PROFILE ########################
elseif($page == 'clan-profile'){
    #Alerts
    $txt['value_required'] = 'A value is required';
    $txt['not_enough_gold'] = 'You do not have enough gold';
    $txt['not_a_member'] = 'You aren\'t a member of this clan';
    $txt['donation_gold_succesfull'] = $_POST['clan_donate'].' gold deposited';
    $txt['donation_silver_succesfull'] = $_POST['clan_donate'].' silver deposited';
    $txt['cant_kick_self'] = 'You cannot kick yourself.';
    $txt['user_removed'] = 'You\'ve removed '.$_POST['who'].' from your clan.';
    $txt['user_removed_message'] = 'You have ben kicked from '.$profiel['clan_naam'];
    $txt['user_stepped_out'] = 'You left the clan.';
    $txt['user_stepped_out_message'] = $_SESSION['naam'].' has left the clan.';
    $txt['clan_not_enough_gold'] = 'The clan does not have enough gold.';
    $txt['new_clan_level'] = 'Congratulations the clan is now';

    #Screen
    $txt['admin'] = 'Admin';
    $txt['clan_name'] = 'Clan name';
    $txt['clan_owner'] = 'Clan owner';
    $txt['clan_description'] = 'Clan description';
    $txt['clan_position'] = 'Position';
    $txt['clan_level'] = 'Clan level';
    $txt['clan_upgrade'] = 'an upgrade costs';
    $txt['clan_gold'] = 'Gold';
    $txt['clan_silver'] = 'Silver';
    $txt['clan_members'] = 'Members';
    $txt['clan_make_owner'] = 'Make owner';
    $txt['clan_leave'] = 'Leave';
    $txt['clan_upgrade_level'] = 'Upgrade clan';
}

######################## CLAN-SHOUT ########################
elseif($page == 'clan-shout'){
    #Alerts
    $txt['no_clan'] = '<center>You are not in a clan, create your own clan <a href=\'?page=clan-make\'>here</a>.</center>';

    #Screen
    $txt['clan_shout_info'] = '<center><h2>Clanshout</h2><br>Welcome in the clan shoutbox of '.$gebruiker['clan'].', only you and your clanmembers can see this shoutbox.</center>';
    $txt['clan_shout_retrieving_messages'] = 'Retrieving messages...';
    $txt['clan_send_message'] = 'Send message';
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
    $txt['success_contact'] = 'Je bericht is succesvol verzonden.';

    #Screen
    $txt['pagetitle'] = 'Contact';
    $txt['title_text'] = 'Hier kunt u naar ons een e-mail sturen. Wij proberen je e-mail zo snel mogelijk te beantwoorden.<br />
			Gelieve uw echte e-mail adres invullen. Anders kunnen we u niet terug mailen.<br />
			Bekijk dus a.u.b. eerst alles goed voordat je de mail naar ons verstuurd.';
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
    $txt['alert_no_username'] = 'Geen speelnaam ingevuld.';
    $txt['alert_username_too_short'] = 'Speelnaam te kort.';
    $txt['alert_username_too_long'] = 'Speelnaam te lang.';
    $txt['alert_username_dont_exist'] = $_POST['inlognaam'].' bestaat niet.';
    $txt['alert_no_activatecode'] = 'Geen activatiecode ingevuld.';
    $txt['alert_activatecode_too_short'] = 'Activatiecode is te kort.';
    $txt['alert_activatecode_too_long'] = 'Activatiecode is te lang.';
    $txt['alert_guardcore_invalid'] = 'Beveiligingscode incorrect.';
    $txt['alert_already_activated'] = $_POST['inlognaam'].' is al geactiveerd!';
    $txt['alert_activatecode_wrong'] = 'Verkeerde code ingevuld.';
    $txt['alert_username_wrong'] = 'Verkeerde speelnaam ingevuld.';
    $txt['success_activate'] = $_POST['inlognaam'].' is succesvol geactiveerd!';

    #Screen
    $txt['pagetitle'] = 'Activate account';
    $txt['title_text'] = 'Hier kunt u uw account activeren.';
    $txt['username'] = 'Speelnaam';
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
    $txt['alert_no_username'] = 'Geen speelnaam ingevuld.';
    $txt['alert_username_too_short'] = 'Speelnaam te kort.';
    $txt['alert_username_too_long'] = 'Speelnaam te lang.';
    $txt['alert_no_email'] = 'Geen e-mail adres ingevuld.';
    $txt['alert_guardcore_invalid'] = 'Beveiligingscode incorrect.';
    $txt['alert_username_dont_exist'] = 'Speelnaam bestaat niet.';
    $txt['alert_email_dont_exist'] = 'E-mail adres bestaat niet.';
    $txt['alert_wrong_combination'] = 'Verkeerde combinatie';
    $txt['success_forgot_password'] = 'De e-mail is succesvol verstuurd!';

    #Screen
    $txt['pagetitle'] = 'Forgot password';
    $txt['title_text'] = 'Uw wachtwoord vergeten?<br /> Hier kunt u uw wachtwoord vernieuwen, u krijgt een nieuwe toegestuurd naar uw e-mail adres.';
    $txt['username'] = 'Speelnaam';
    $txt['email'] = 'E-mail adres';
    $txt['captcha'] = 'Beveiligingscode plaatje.';
    $txt['guardcode'] = 'Beveiligingscode';
    $txt['button'] = 'Stuur mail';
}

######################## ACCOUNT OPTIONS ########################
elseif($page == 'account-options'){
    #Screen
    $txt['pagetitle'] = 'Account options';
    #Titles
    $txt['link_subpage_personal'] = 'Personal';
    $txt['link_subpage_password'] = 'Password';
    $txt['link_subpage_profile'] = 'Profile';
    $txt['link_subpage_restart'] = 'Start over';

    if($_GET['category'] == 'personal'){
        #Alerts general
        $txt['alert_not_enough_gold'] = 'You do not have enough gold.';
        $txt['alert_no_username'] = 'Username is incorrect.';
        $txt['alert_username_too_short'] = 'Username is too short.';
        $txt['alert_username_too_long'] = 'Username is too long.';
        $txt['alert_username_already_taken'] = 'Username already exists.';
        $txt['alert_firstname_too_long'] = 'Firstname is too long.';
        $txt['alert_lastname_too_long'] = 'Lastname is too long.';
        $txt['alert_character_invalid'] = 'Personality incorrect.';
        $txt['alert_seeteam_invalid'] = 'Team visibility incorrect.';
        $txt['alert_seebuddies_invalid'] = 'Buddy visibility incorrect.';
        $txt['alert_seebadges_invalid'] = 'Badges visibility incorrect.';
        $txt['alert_duel_invalid'] = 'Duel incorrect.';
        $txt['success_modified'] = 'Your profile has been changed!';
        $txt['alert_advertisement_invalid'] = 'Advertising unknown';

        #Screen general
        $txt['pagetitle'] .= ' - Personal';
        $txt['buy_premium_here'] = 'Order premium here!';
        $txt['days_left'] = 'day(s) left.';
        $txt['username'] = 'This will cost 15 gold.';
        $txt['firstname'] = 'Firstname:';
        $txt['lastname'] = 'Lastname:';
        $txt['youtube'] = 'Youtube link:';
        $txt['cost_15_gold'] = 'Dit kost 15 gold.';
        $txt['country'] = 'Country:';
        $txt['character'] = 'Personality:';
        $txt['premium_days'] = 'Premium days:';
        $txt['advertisement'] = 'Advertisement:';
        $txt['advertisement_info'] = '(if you have advertising activated you will earn 5 gold each 24hr.)';
        $txt['alert_not_premium'] = 'You are no premium member.';
        $txt['on'] = 'On';
        $txt['off'] = 'Off';
        $txt['team_on_profile'] = 'Team on profile:';
        $txt['buddies_on_profile'] = 'Buddies on profile:';
        $txt['music_on'] = 'Music on '.GLOBALDEF_SITENAME.':';
        $txt['yes'] = 'Yes';
        $txt['no'] = 'No';
        $txt['badges_on_profile'] = 'Badges on profile:';
        $txt['alert_dont_have_badgebox'] = 'You do not have a badgecase.';
        $txt['duel_invitation'] = 'Duel invites:';
        $txt['alert_not_yet_available'] = 'Not yet available.';
        $txt['available_rank_Senior'] = 'Available from rank Senior.';
        $txt['battleScreen'] = 'Battlescreen with images:';
        $txt['button_personal'] = 'Change profile';
    }
    elseif($_GET['category'] == 'password'){
        #Alerts password
        $txt['alert_all_fields_required'] = 'Fill in all fields.';
        $txt['alert_old_new_password_thesame'] = 'Your new password is the same as your current password.';
        $txt['alert_old_password_wrong'] = 'Your current password is not correct.';
        $txt['alert_password_too_short'] = 'New password is too short';
        $txt['alert_new_controle_password_wrong'] = 'Your new password and the repeated password are not the same.';
        $txt['success_password'] = 'Your password has been changed.';

        #Screen password
        $txt['pagetitle'] .= ' - Verander wachtwoord';
        $txt['new_password'] = 'New password:';
        $txt['new_password_again'] = 'Repeat new password:';
        $txt['password_now'] = 'Current password:';
        $txt['button_password'] = 'Change password';
    }
    elseif($_GET['category'] == 'profile'){
        #Alerts profile
        $txt['success_profile'] = 'Your profile has been changed.';

        #Screen profile
        $txt['pagetitle'] .= ' - Pimp your profile';
        $txt['button_profile'] = 'Change profile';
    }
    elseif($_GET['category'] == 'restart'){
        #Alerts restart
        $txt['alert_no_password'] = 'Please fill in your password.';
        $txt['alert_password_wrong'] = 'Incorrect password.';
        $txt['alert_no_beginworld'] = 'Please choose a world to start in.';
        $txt['alert_world_invalid'] = 'Start world unknown.';
        $txt['success_restart'] = 'You\'ve successfully restarted your account!';
        $txt['alert_when_restart'] = 'You can restart your account in
										  <strong><span id=uur3></span></strong> hours
										  <strong><span id=minuten3> </span>&nbsp;minutes</strong> and 
										  <strong><span id=seconden3></span>&nbsp;seconds</strong>';

        #Screen restart
        $txt['pagetitle'] .= ' - Restart';
        $txt['restart_title_text'] = '<center>Fill in your password and choose a new world to start in.<br /><br />
										
										All your pokemon, items, silver and experience will be removed.<br />
										<strong>This action cannot be reversed.</strong></center>';
        $txt['password_security'] = 'Password check:';
        $txt['button_restart'] = 'Restart';
    }
}

######################## PROMOTION ########################
elseif($page == 'promotion'){
    $txt['promotion_text'] = '<p>You can help '.$game['titel'].' with gaining more members while you profit from it!<br />
		for each new member with your refer you will receive <img src="images/icons/gold.png" title="Gold" style="margin-bottom:-3px;" />150.<br /><br />
		Tip: You can easily gain new members via skype, facebook, mail etc!<br /><br />
		Your link for easy access with you as refer:<br />
		<strong>https://'.$game_domein['domein'].'/index.php?page=register&referer='.$_SESSION['naam'].'</strong></p>';
}

######################## MODIFY ORDER ########################
elseif($page == 'modify-order'){
    #Screen
    $txt['pagetitle'] = 'Modify order';
    $txt['modify_order_text'] = 'You can change the order of your team here.<br />
									 Use the arrows to move your Pok&eacute;mon to the desired position.';
}

######################## EXTENDED ########################
elseif($page == 'extended'){
    #Screen
    $txt['pagetitle'] = 'Extended Pok&eacute;mon information';
    $txt['catched_with'] = 'Caught with a';
    $txt['pokemon'] = 'Pok&eacute;mon:';
    $txt['attack_points'] = 'Attack:';
    $txt['clamour_name'] = 'Nickname:';
    $txt['defence_points'] = 'Defense:';
    $txt['type'] = 'Type:';
    $txt['level'] = 'Level:';
    $txt['speed_points'] = 'Speed:';
    $txt['spc_attack_points'] = 'Spc. Attack:';
    $txt['mood'] = 'Mood:';
    $txt['spc_defence_points'] = 'Spc. Defense:';
    $txt['attacks'] = 'Attacks:';
    $txt['egg_will_hatch_in'] = 'Egg will hatch in:';
    $txt['begin_pokemon'] = 'Starter Pok&eacute;mon';
}

######################## SELL ########################
elseif($page == 'sell'){
    #Screen
    $txt['pagetitle'] = 'Sell';
    $txt['title_text_1'] = 'You can only put';
    $txt['title_text_2'] = 'Pok&eacute;mon on the transferlist.<br />
								You currently have';
    $txt['title_text_3'] = 'Pok&eacute;mon on the transferlist.';
    $txt['no_pokemon_in_house'] = 'You have no Pok&eacute;mon in your house.';
    $txt['#'] = '#';
    $txt['pokemon'] = 'Pok&eacute;mon';
    $txt['clamour_name'] = 'Name';
    $txt['level'] = 'Level';
    $txt['sell'] = 'Sell';
    $txt['go_to_transferlist'] = 'Go to the transferlist';
}

######################## RELEASE ########################
elseif($page == 'release'){
    #Alerts
    $txt['alert_itemplace'] = 'Note: You do not have any room left in your itembox, if you release a Pok&eacute;mon you wont receive the pokeball.';
    $txt['alert_not_your_pokemon'] = 'This is not your Pok&eacute;mon.';
    $txt['alert_beginpokemon'] = 'You cannot release your starter Pok&eacute;mon.';
    $txt['alert_no_pokemon_selected'] = 'Please select a Pok&eacute;mon.';
    $txt['success_release'] = 'You\'ve released your Pok&eacute;mon.';

    #Screen
    $txt['pagetitle'] = 'Release Pok&eacute;mon';
    $txt['title_text'] = 'Here you can release your Pok&eacute;mon.<br />
									  The Pok&eacute;ball which was used to catch the Pok&eacute;mon will be returned.<br />
									  <div class="blue"><strong>Note:</strong> This cannot be reversed.</div>';
    $txt['pokemon_team'] = 'Pok&eacute;mon team';
    $txt['#'] = '#';
    $txt['pokemon'] = 'Pok&eacute;mon';
    $txt['clamour_name'] = 'Nickname';
    $txt['level'] = 'Level';
    $txt['release'] = 'Release';
    $txt['alert_no_pokemon_in_hand'] = 'There are no Pok&eacute;mon in your team.';
    $txt['button'] = 'Release';
    $txt['pokemon_at_home'] = 'Pok&eacute;mon in your home';
    $txt['alert_no_pokemon_at_home'] = 'There are no Pok&eacute;mon in your home.';
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
    $txt['pagetitle'] = 'Badge box';
    $txt['badges'] = 'Badges';
    $txt['no_badges_from'] = 'You do not have badges from';
}

######################## HOUSE ########################
elseif($page == 'house'){
    #Alerts
    $txt['alert_not_your_pokemon'] = 'This is not your Pok&eacute;mon.';
    $txt['alert_house_full'] = 'Your house is full.';
    $txt['success_bring'] = 'You\'ve brought your Pok&eacute;mon home.';
    $txt['alert_hand_full'] = 'You already have 6 Pok&eacute;mon in your team.';
    $txt['alert_pokemon_on_transferlist'] = 'This Pok&eacute;mon is on the transferlist.';
    $txt['success_get'] = 'You successfully retrieved the Pok&eacute;mon.';

    #Screen
    $txt['pagetitle'] = 'Your house';
    $txt['title_text_1'] = 'Currently you have';
    $txt['title_text_2'] = 'here you can store';
    $txt['title_text_3'] = 'Pokemons in verblijven.<br><br>
									  * Pokemon wegbrengen, daar kun je de pokemons naar je huis laten gaan.<br>
									  * Pokemon ophalen, daar kun je de pokemons terughalen naar je hand.<br><br>
									  Je kunt pas een Pokemon ophalen als je minimaal 1 plaats vrij hebt in je hand.';
    $txt['pokemon_bring_away'] = 'Send Pok&eacute;mon home';
    $txt['pokemon_pick_up'] = 'Pickup Pok&eacute;mon';
    $txt['box'] = 'Carboard box';
    $txt['little_house'] = 'Small house';
    $txt['normal_house'] = 'normal house';
    $txt['big_house'] = 'villa';
    $txt['hotel'] = 'hotel';
    $txt['places_over'] = 'spots available';
    $txt['#'] = '#';
    $txt['clamour_name'] = 'Nickname';
    $txt['level'] = 'Level';
    $txt['bring_away'] = 'Pickup';
    $txt['button_take'] = 'Pickup';
    $txt['button_bring'] = 'Send home';
    $txt['empty'] = 'Empty';
}

######################## POKEDEX ########################
elseif($page == 'pokedex'){
    #Screen
    $txt['pagetitle'] = 'Pokedex';
    $txt['seen'] = 'Seen';
    $txt['had'] = 'Had';
    $txt['have'] = 'Owned';
    $txt['#'] = '#';
    $txt['pokemon'] = 'Pok&eacute;mon';
    $txt['name'] = 'Name';
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
    $txt['success_deleted'] = $_POST['deletenaam'].' is no longer a buddy.';
    $txt['alert_buddy_not_yourself'] = 'You cannot add yourself as a buddy.';
    $txt['alert_username_dont_exist'] = 'Unknown user.';
    $txt['alert_already_buddy'] = $_POST['buddynaam'].' already is a buddy or is awaiting approval.';
    $txt['alert_is_blocked'] = $_POST['buddynaam'].' is in your blocklist.';
    $txt['success_add'] = $_POST['buddynaam'].' has received a new buddy request.';
    $txt['alert_receiver_blocked'] = $_POST['buddynaam'].' has blocked you, you cannot add '.$_POST['buddynaam'].' as a buddy.';
    $txt['alert_receiver_blocked'] = $_POST['buddynaam'].' has blocked you, you cannot add '.$_POST['buddynaam'].' as a buddy.';
    $txt['alert_communication_ban'] = 'You cannot send a buddy request to '.$_POST['buddynaam'].' as you have a communication ban.';

    #Screen
    $txt['pagetitle'] = 'Buddylist';
    $txt['title_text'] = '<img src="images/icons/groep.png" width="16" height="16" /> <strong>Send a buddy request to your friends.</strong>';
    $txt['username'] = 'Username:';
    $txt['#'] = '#';
    $txt['country'] = 'Country';
    $txt['status'] = 'Status';
    $txt['actions'] = 'Actions';
    $txt['offline'] = 'Offline';
    $txt['online'] = 'Online';
    $txt['send_message'] = 'Send message';
    $txt['donate_silver'] = 'Donate silver';
    $txt['delete_buddy'] = 'Delete buddy';
    $txt['no_buddys'] = 'You do not have any buddies.';
    $txt['button'] = 'Add buddy';
}

######################## POKEMON INFO ########################
elseif($page == 'blocklist'){
    #Alerts
    $txt['success_deleted'] = $_POST['deletenaam'].' is no longer on your blocklist.';
    $txt['alert_block_yourself'] = 'You cannot block yourself.';
    $txt['alert_unknown_username'] = 'Username unknown.';
    $txt['alert_already_in_blocklist'] = $_POST['blocknaam'].' is already in your blocklist.';
    $txt['alert_is_your_buddy'] = $_POST['blocknaam'].' is your buddy, remove him first.';
    $txt['alert_admin_block'] = 'You cannot block an admin.';
    $txt['success_blocked'] = $_POST['blocknaam'].' has been blocked.';

    #Screen
    $txt['pagetitle'] = 'Blocklist';
    $txt['title_text'] = '<img src="images/icons/blokkeer.png" border="0" /> <strong>Block users.</strong><br />If you block a player they cannot send you or receive your messages.';
    $txt['username'] = 'Username:';
    $txt['button'] = 'Block user';
    $txt['*'] = '*';
    $txt['#'] = '#';
    $txt['country'] = 'Country';
    $txt['status'] = 'Status';
    $txt['actions'] = 'Actions';
    $txt['offline'] = 'Offline';
    $txt['online'] = 'Online';
    $txt['block_delete'] = 'Remove';
    $txt['nobody_blocked'] = 'You have no blocklist.';
    $txt['button_add'] = 'Add';
}

######################## SEARCH USER ########################
elseif($page == 'search-user'){
    #Screen
    $txt['pagetitle'] = 'Search user';
    $txt['title_text'] = '<img src="images/icons/groep_magnify.png" border="0" /> <strong>Search other users.</strong>';
    $txt['username'] = 'Username';
    $txt['#'] = '#';
    $txt['country'] = 'Country';
    $txt['rank'] = 'Rank';
    $txt['status'] = 'Status';
    $txt['offline'] = 'Offline';
    $txt['online'] = 'Online';
    $txt['button'] = 'Search';
}

######################## PROFILE ########################
elseif($page == 'profile'){
    #Screen
    $txt['pagetitle'] = 'Profiel van '.$_GET['player'];
    $txt['offline'] = 'Offline';
    $txt['online'] = 'Online';
    $txt['username'] = 'Speelnaam:';
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
    $txt['pokemon'] = 'Pokemon:';
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
    $txt['give_pokemon'] = 'Geef pokemon';
    $txt['give_pack'] = 'Geef pack';
    $txt['team'] = 'Team:';
    $txt['buddies'] = 'Buddies:';
    $txt['badges'] = 'Badges';
    $txt['no_badges_from'] = 'Nog geen badges van';
    $txt['no_profile_insert'] = 'Profiel niet opgemaakt.';
}

######################## WORK ########################
elseif($page == 'work'){
    #Alerts
    #Alerts
    $txt['alert_nothing_selected'] = 'Please select a job.';
    $txt['and'] = 'and';
    $txt['seconds'] = 'seconds';
    $txt['minutes'] = 'minutes';
    $txt['minute'] = 'minutes';
    $txt['success_work_1'] = 'You are working now for';
    $txt['success_work_2'] = '';

    #Screen
    $txt['pagetitle'] = 'Werken';
    $txt['#'] = '#';
    $txt['work_name'] = 'Work';
    $txt['duration'] = 'Time';
    $txt['turnover'] = 'Profit';
    $txt['chance'] = 'Chance';
    $txt['button'] = 'Work';

    $txt['work_1'] = 'Sell lemonade on the square';
    $txt['work_2'] = 'Help in the PokeMarkt';
    $txt['work_3'] = 'Deliver the PokeMagazine';
    $txt['work_4'] = 'Clean the Pok&eacute;moncenter';
    $txt['work_5'] = 'Challenge Team Rocket for a golf session';
    $txt['work_6'] = 'Scavenge valuables in the city';
    $txt['work_7'] = 'Keep a Pok&eacute;mon demonstration on the city square';
    $txt['work_8'] = 'Medically experiment on your Pok&eacute;mon';
    $txt['work_9'] = 'Let your Pok&eacute;mon freestyle in the park';
    $txt['work_10'] = 'Help agent Jenny';
    $txt['work_11'] = 'Go stealing with your Pok&eacute;mon';
    $txt['work_12'] = 'Let your Pok&eacute;mon rob a casino';
}

######################## TRADERS ########################
elseif($page == 'traders'){
    #Alerts
    $txt['alert_dont_have_1'] = 'je hebt geen';
    $txt['alert_dont_have_2'] = 'bij je.';

    $txt['alert_i_have_1'] = 'ik heb';
    $txt['alert_i_have_2'] = 'net geruild, sorry.';
    $txt['success_traders_change'] = 'bedankt voor de ruil, zorg goed voor';
    $txt['success_traders_refresh'] = 'Succesvol de pokemon ge-refreshed!';

    #Screen
    $txt['pagetitle'] = 'Pokemon verkopers';
    $txt['title_text'] = 'Hier kun je een pokemon ruilen met Kayl, Wayne en Remy.<br />
										De level van de pokemon word gebasseerd op het level van de pokemon die je inruilt.<br /><br />
										Je moet de pokemon die je wilt ruilen wel bij je hebben.<br />
										Als je 2 dezelfde pokemon hebt, word de eerste die je bij je hebt geruild.';
    $txt['kayl_no_pokemon'] = 'Sorry mate, ik heb alle pokemon die ik wil.';
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

    $txt['remy_no_pokemon'] = 'Sorry, ik ben op het moment niet op zoek naar een pokemon.';
    $txt['remy_text_1'] = 'Hallo ik ben Remy.<br />
								Ik ben al een heletijd opzoek naar een <strong>';
    $txt['remy_text_2'] = '</strong>, heb jij die misschien?<br />
								Ik zou hem graag willen ruilen voor mijn <strong>';
    $txt['remy_text_3'] = '</strong>.';

    $txt['refresh_pokemon'] = 'Refresh de pokemon';
    $txt['button_traders_refresh'] = 'Refresh pokemon';
}

######################## RACE INVITE ########################
elseif($page == 'race-invite'){
    #Alerts
    $txt['alert_no_races_today'] = 'You are out of challenges today.';
    $txt['alert_no_player'] = 'Please fill in a player.';
    $txt['alert_not_yourself'] = 'You cannot challenge yourself.';
    $txt['alert_unknown_amount'] = 'Invalid amount.';
    $txt['alert_no_amount'] = 'Please fill in an amount.';
    $txt['alert_unknown_what'] = 'Choose if you would like to race for silver or gold.';
    $txt['alert_not_enough_silver_or_gold'] = 'You do not have enough silver or gold.';
    $txt['alert_user_unknown'] = 'User does not exist.';
    $txt['alert_opponent_not_in'] = 'is not in';
    $txt['alert_opponent_not_casual'] = 'is not rank Casual.';
    $txt['alert_no_admin'] = 'You cannot challenge an admin for a race.';
    $txt['success'] = 'You\'ve succesfully challenged '.$_POST['naam'].' for a race!';

    #Screen
    $txt['pagetitle'] = 'Race';
    $txt['title_text'] = '<img src="images/icons/vlag.png" width="16" height="16" alt="Race" /> <strong>Challenge another player for a race!</strong> <img src="images/icons/vlag.png" width="16" height="16" alt="Race" />';
    $txt['races_left_today'] = 'Race invites left:';
    $txt['player'] = 'Username:';
    $txt['silver_or_gold'] = 'Silver or gold:';
    $txt['amount'] = 'Amount:';
    $txt['button'] = 'Challenge';
    $txt['races_opened'] = 'Open challenges';
    $txt['races_deleted_3_days'] = 'When a race is older than 3 days it will be removed.';
    $txt['#'] = '#';
    $txt['opponent'] = 'Opponent';
    $txt['price'] = 'Bet';
    $txt['when'] = 'Challenged on';
    $txt['no_races_opened'] = 'You have no open challenges.';
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
    $txt['alert_no_username'] = 'Geen speelnaam ingevuld.';
    $txt['alert_steal_from_yourself'] = 'Je kunt niet van jezelf stelen.';
    $txt['alert_username_dont_exist'] = 'Speelnaam bestaat niet.';
    $txt['alert_username_incorrect_signs'] = 'Speelnaam bevat ongeldige tekens.';
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
    $txt['title_text'] = 'Je kunt hier je pokemon laten stelen van een tegenstander.<br />Als het lukt nemen ze zoveel mogelijk geld mee! 	 										 								  Als het mislukt heb je kans dat je in de gevangenis komt.<br />
									  Er mag maximaal 1 rank tussen jou en je tegenstander zitten. Rank Junior en lager mag je niet beroven.<br /><br />';

    $txt['steal_premium_text'] = 'Premiumaccount leden mogen 3 keer per dag iemand beroven. <a href="index.php?page=area-market"><strong>Word hier premium!</strong></a><br><br>';
    $txt['steal_how_much_1'] = ' Je kunt vandaag nog <strong>';
    $txt['steal_how_much_2'] = '</strong> keer je pokemon laten stelen.';
    $txt['username'] = 'Speelnaam:';
    $txt['button'] = 'Steel!';
}

######################## SPY ########################
elseif($page == 'spy'){
    #Alerts
    $txt['alert_no_username'] = 'Geen speelnaam ingevuld.';
    $txt['alert_spy_yourself'] = 'Je kunt jezelf niet bespioneren.';
    $txt['alert_username_dont_exist'] = 'Speelnaam bestaat niet.';
    $txt['alert_not_enough_silver'] = 'Je hebt niet genoeg Silver.';
    $txt['alert_admin_spy'] = 'Je kunt geen admin bespioneren.';
    $txt['alert_spy_failed'] = 'Het bespioneren is mislukt.';
    $txt['alert_spy_failed_jail_1'] = 'Team Rocket is opgepakt!<br> Je zit nu';
    $txt['alert_spy_failed_jail_2'] = 'min en';
    $txt['alert_spy_failed_jail_3'] = 'sec in de gevangenis.';
    $txt['success_spy'] = 'Spionage was succesvol.';

    #Screen
    $txt['pagetitle'] = 'Bespioneren';
    $txt['username'] = 'Speelnaam:';
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
    $txt['success_lvl_choose'] = 'Je kunt nu pokemon van lvl '.$_POST['lvl'].' tegen komen.';

    #Screen
    $txt['pagetitle'] = 'Level kiezen';
    $txt['title_text'] = 'Hier kun je kiezen welke level pokemon je kunt tegenkomen.<br />
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
    $txt['premiumpacks'] = 'Premium packs';
    $txt['premiumtext'] = 'Zodra je een premium pack koopt word je een premium lid. Je hebt dan meer opties en voordelen in het spel!<br />
Enkele voorbeelden zijn:<br />
- Meer inbox en gebeurtenis ruimte.<br />
- Meer Race en steel mogelijkheden.<br />
- Je kunt vaker per dag met het geluksrad spelen.<br />
- Je kunt tegen elkaar duelleren.<br />
- De tijd van het pokemon center is aanzienlijk lager.<br />
- Je pokemon krijgen 5% extra EXP.<br />
- En nog veel meer!';
    $txt['valuepacks'] = 'Andere packs';
    $txt['valuetext'] = 'Deze packs zijn zeer handig in het spel, voor Gold kun je namelijk Master Balls kopen etc.';
    $txt['buy'] = 'Koop';
}

######################## POKEMON CENTER ########################
elseif($page == 'pokemoncenter'){
    #Alerts
    $txt['minute'] = 'minute';
    $txt['minutes'] = 'minutes';
    $txt['seconds'] = 'seconds';
    $txt['success_pokecenter_premium'] = 'Your Pok&eacute;mon have been cared for.';
    $txt['success_pokecenter'] = 'Your pokemon will be cared for in:';

    #Screen
    $txt['pagetitle'] = 'Pok&eacute;moncenter';
    $txt['title_text_premium'] = 'Help your Pok&eacute;mon back to strength.';
    $txt['title_text_normal'] = 'Going to the Pok&eacute;moncenter takes about 10 seconds.<br/>VIP members dont have a wait time.<br><a href="index.php?page=area-market"><strong>Become a VIP!</strong></a>';
    $txt['all'] = 'All';
    $txt['who'] = 'Pok&eacute;mon';
    $txt['health'] = 'Health';
    $txt['nvt'] = 'Not applicable';
    $txt['button'] = 'Restore';
}

######################## MARKET ########################
elseif($page == 'market'){
    #Alerts
    $txt['alert_itemplace'] = 'Note: You only have one item spot left, you cannot buy a new item.';
    $txt['alert_not_enough_money'] = 'Not enough silver or gold.';
    $txt['alert_itembox_full_1'] = 'Item box full, you can buy';
    $txt['alert_itembox_full_2'] = 'more.';
    $txt['success_market'] = 'You have a ';
    $txt['alert_nothing_selected'] = 'Please select an item.';
    $txt['alert_pokedex_chip'] = 'You must own a Pokedex before buying a Pokedex Chip.';
    $txt['alert_not_enough_place'] = 'Your item box is full.';
    $txt['alert_hand_full'] = 'You already have 6 Pok&eacute;mon in your team.';
    $txt['alert_not_in_stock'] = 'Product not available.';

    #Screen
    $txt['pagetitle'] = 'Market';
    $txt['balls'] = 'Balls';
    $txt['potions'] = 'Potions';
    $txt['items'] = 'Items';
    $txt['spc_items'] = 'Special items';
    $txt['stones'] = 'Stones';
    $txt['attacks'] = 'Attacks';
    $txt['pokemon'] = 'Pok&eacute;mon';

    if($_GET['shopitem'] == 'balls'){
        $txt['pagetitle'] .= ' - Balls';
        $txt['button_balls'] = 'Buy pokeballs';
    }
    elseif($_GET['shopitem'] == 'potions'){
        $txt['pagetitle'] .= ' - Potions';
        $txt['button_potions'] = 'Buy potions';
    }
    elseif($_GET['shopitem'] == 'items'){
        $txt['pagetitle'] .= ' - Items';
        $txt['button_items'] = 'Buy items';
    }
    elseif($_GET['shopitem'] == 'specialitems'){
        $txt['pagetitle'] .= ' - Special items';
        $txt['button_spc_items'] = 'Buy special items';
    }
    elseif($_GET['shopitem'] == 'stones'){
        $txt['pagetitle'] .= ' - Stones';
        $txt['button_stones'] = 'Buy stones';
    }
    elseif($_GET['shopitem'] == 'attacks'){
        $txt['pagetitle'] .= ' - Attacks';
        $txt['button_attacks'] = 'Buy attacks';
        $txt['market_attack_types'] = 'Pok&eacute;mon can learn these attacks.';
    }
    elseif($_GET['shopitem'] == 'pokemon'){
        $txt['pagetitle'] .= ' - Pok&eacute;mon';
        $txt['button_pokemon'] = 'Buy Pok&eacute;mon';
        $txt['not_rare'] = 'Common';
        $txt['middle_rare'] = 'A little rare';
        $txt['rare'] = 'Very rare';
        $txt['out_of_stock_1'] = 'All Pok&eacute;mon are sold out.';
        $txt['out_of_stock_2'] = '';
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
    $txt['success_market'] = 'Je koopt';
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
    $txt['alert_nothing_selected'] = 'Please select a home.';
    $txt['alert_you_own_this_house'] = 'You already own this home';
    $txt['alert_not_enough_silver'] = 'You do not have enough silver to buy this home.';
    $txt['alert_already_have_villa'] = 'You already have a Hotel, that is the best home you can buy.';
    $txt['alert_you_have_better_now'] = 'You cannot buy a home that is lower than your current home';
    $txt['success_house_1'] = 'You\'ve bought a';
    $txt['success_house_2'] = '';

    #Screen
    $txt['pagetitle'] = 'Broker';
    $txt['house1'] = 'Cardboard box';
    $txt['house2'] = 'Small house';
    $txt['house3'] = 'Normal house';
    $txt['house4'] = 'Villa';
    $txt['house5'] = 'Hotel';
    $txt['title_text'] = 'At the broker you can buy a home for your Pok&eacute;mon.<br />
							  You now own a';
    $txt['house'] = 'Home';
    $txt['price'] = 'Price';
    $txt['description'] = 'Description';
    $txt['button'] = 'Buy';
}

######################## POKEMARKET ########################
elseif($page == 'pokemarket'){
    #Screen
    $txt['pokeballs'] = 'Pok&eacute;balls';
    $txt['potions'] = 'Potions';
    $txt['items'] = 'Items';
    $txt['vitamins'] = 'Vitamins';
    $txt['stones'] = 'Stones';
    $txt['eggs'] = 'Eggs';
    $txt['attacks'] = 'Attacks';
}

######################## TRAVEL ########################
elseif($page == 'travel'){
    #Alerts
    $txt['alert_no_world'] = 'Please select a world.';
    $txt['alert_already_in_world'] = 'You already are in '.$_POST['wereld'].'.';
    $txt['alert_world_invalid'] = $_POST['wereld'].' is not a valid wereld.';
    $txt['alert_not_enough_money'] = 'You do not have enough silver to sail to '.$_POST['wereld'].'';
    $txt['success_travel'] = 'You have arrived in '.$_POST['wereld'].', the trip will cost';
    $txt['alert_not_everything_selected'] = 'Please check all the required fields.';
    $txt['alert_not_your_pokemon'] = 'This Pok&eacute;mon is not yours.';
    $txt['alert_no_surf'] = 'This Pok&eacute;mon does not have a Surf attack.';
    $txt['alert_not_strong_enough'] = 'This Pok&eacute;mon is strong enough.';
    $txt['success_surf'] = 'Your Pok&eacute;mon sucessfully surfed to '.$_POST['wereld'].'.';

    #Screen
    $txt['pagetitle'] = 'Reis naar een andere wereld';
    $txt['title_text'] = 'Rent a boat and travel to another world.';
    $txt['#'] = '#';
    $txt['world'] = 'World';
    $txt['price'] = 'Price per Pok&eacute;mon';
    $txt['price_total'] = 'Total price';
    $txt['button_travel'] = 'Travel';
    $txt['title_text_surf'] = 'If your Pok&eacute;mon has a attack called \'Surf\' and is above level 80.<br />
								 Then you can surf without any costs to another world!';
    $txt['pokemon'] = 'Pok&eacute;mon';
    $txt['button_surf'] = 'Surf';
    $txt['button_fly'] = 'Fly';
}

######################## TRANSFERLIST ########################
elseif($page == 'transferlist'){
    #Alerts
    $txt['something_went_wrong'] = 'Something went wrong.';
    $txt['max_carry'] = 'You cannot carry more than 6 Pok&eacute;mon.';
    $txt['cancelled_trade'] = 'Pok&eacute;mon trade cancelled.';
    $txt['no_pokemon_in_trade'] = 'There are no Pok&eacute;mon on the Tradecenter.';

    #Screen
    $txt['trade_info'] = '<h3>Welcome in the Tradecenter.</h3><br/>
		   Here you can offer Pok&eacute;mon which you wish to trade with other players<br/><br/>';
    $txt['title_text_1'] = 'You now have:';
    $txt['title_text_2'] = 'Note: check the stats and attacks of the Pok&eacute;mon.';
    $txt['#'] = '#';
    $txt['pokemon'] = 'Pok&eacute;mon';
    $txt['clamour_name'] = 'Nickname';
    $txt['name'] = 'Name';
    $txt['offer'] = 'Offer';
    $txt['level'] = 'Level';
    $txt['price'] = 'Price';
    $txt['owner'] = 'Owner';
    $txt['buy'] = 'Buy';
    $txt['add'] = 'Add';
}

######################## DAYCARE ########################
elseif($page == 'daycare'){
    #Alerts
    $txt['alert_not_your_pokemon'] = 'Dit is niet jou pokemon.';
    $txt['alert_hand_full'] = 'Je hebt al 6 pokemon bij je.';
    $txt['alert_no_eggs'] = 'Er zijn geen eieren voor jou.';
    $txt['success_egg'] = 'Je hebt succesvol het eitje aangenomen.';
    $txt['alert_already_in_daycare'] = 'Pokemon is al bij de daycare.';
    $txt['alert_already_lvl_100'] = 'Pokemon is al level 100.';
    $txt['alert_daycare_full'] = 'Je kunt niet meer pokemon bij de daycare hebben.';
    $txt['success_bring'] = 'U heeft uw pokemon succesvol weggebracht.';
    $txt['alert_not_enough_silver'] = 'Je hebt niet genoeg silver.';
    $txt['success_take'] = 'U heeft uw pokemon succesvol opgehaald.';
    $txt['alert_no_pokemon'] = 'Je moet wel een pokemon bij je hebben als je &egrave;&egrave;n naar de daycare wilt brengen.';

    #Screen
    $txt['pagetitle'] = 'Dagverblijf';
    $txt['egg_text'] = 'Heej!<br /><br />
							  We hebben een ei gevonden in onze daycare, wil jij het ei hebben?<br /><br />
							  <input type="submit" name="accept" value="Ja graag!" class="text_long"><input type="submit" name="dontaccept" value="Nee dankje." class="text_long" style="margin-left:10px;">';
    $txt['normal_user'] = 'You can only bring 1 Pok&eacute;mon to the daycare, VIP members can bring up to 2 Pok&eacute;mon to the daycare.';
    $txt['premium_user'] = 'You can only bring 2 Pok&eacute;mon to the daycare.';
    $txt['title_text'] = 'Binging a Pok&eacute;mon to the daycare costs about <img src="images/icons/silver.png" title="Silver" /> 250 per Pok&eacute;mon.<br />
			For every new level <img src="images/icons/silver.png" title="Silver" /> 500 will be added to that amount.<br />
			<small>Once you pickup your Pok&eacute;mon it will be triggered to evolving and learn new attacks.</small>';
    $txt['give_pokemon_text'] = 'Bring a Pok&eacute;mon to the daycare:';
    $txt['button_bring'] = 'Give';
    $txt['take_pokemon_text'] = 'Pok&eacute;mon that currently are in the daycare';
    $txt['#'] = '#';
    $txt['name'] = 'Name';
    $txt['level'] = 'Level';
    $txt['levelup'] = 'Level up';
    $txt['cost'] = 'Costs';
    $txt['buy'] = 'Buy';
    $txt['button_take'] = 'Pick up';
}

######################## NAME SPECIALIST ########################
elseif($page == 'name-specialist'){
    #Alerts
    $txt['alert_no_pokemon_in_hand'] = 'There are no Pok&eacute;mon in your team.';
    $txt['alert_nothing_selected'] = 'Please select a Pok&eacute;mon.';
    $txt['alert_not_enough_silver'] = 'You do not have enough silver.';
    $txt['alert_name_too_long'] = 'Please pick a name which isn\'t longer than 12 characters.';
    $txt['alert_not_your_pokemon'] = 'This Pok&eacute;mon isn\'t yours.';
    $txt['success_namespecialist'] = 'The nickname of your Pok&eacute;mon is:';

    #Screen
    $txt['pagetitle'] = 'Namenspecialist';
    $txt['title_text'] = 'Here you can give your Pok&eacute;mon a nickname!<br />
							The costs per Pok&eacute;mon is';
    $txt['#'] = '#';
    $txt['name_now'] = 'Name';
    $txt['button'] = 'Change name';
}

######################## SHINY SPECIALIST ########################
elseif($page == 'shiny-specialist'){
    #Alerts
    $txt['alert_no_pokemon_selected'] = 'Please select a Pok&eacute;mon.';
    $txt['alert_pokemon_is_egg'] = 'This isn\'t a Pok&eacute;mon yet.';
    $txt['alert_not_your_pokemon'] = 'This isn\'t your Pok&eacute;mon.';
    $txt['alert_already_shiny'] = 'Your Pok&eacute;mon already is a shiny.';
    $txt['alert_pokemon_not_in_hand'] = 'This Pok&eacute;mon isn\'t in your team.';
    $txt['alert_not_enough_gold'] = 'You do not have enough gold.';
    $txt['success'] = 'Your Pok&eacute;mon is a shiny Pok&eacute;mon!';

    #Screen
    $txt['pagetitle'] = 'Shiny specialist';
    $txt['title_text'] = 'Scientists have discovered something new: <br />
By giving a pokemon a lot of gold, a pokemon changes to a shining shiny <br />
For each pokemon, the amount of gold is different, for example, rare pokemon need more gold. <br />
Here you can make a pokemon shiny by giving him the necessary gold.';
    $txt['#'] = '#';
    $txt['gold_need'] = 'Gold';
    $txt['button'] = 'Go!';
}

######################## JAIL ########################
elseif($page == 'jail'){
    #Alerts
    $txt['alert_already_broke_out'] = $_POST['naam'].' already has escaped.';
    $txt['alert_already_free'] = $_POST['naam'].' is already out.';
    $txt['success_bust'] = 'You\'ve successfully helped '.$_POST['naam'].' with escaping.';
    $txt['alert_bust_failed_1'] = 'The attempt to escape failed you and '.$_POST['naam'].'';
    $txt['alert_bust_failed_2'] = 'are now in jail.';
    $txt['alert_not_enough_silver'] = 'You do not have enough silver to buy out '.$_POST['naam'];
    $txt['success_bought'] = 'You successfully bailed out '.$_POST['naam'].' for';

    #Screen
    $txt['title_text'] = 'Break out other players from jail, if you fail you will end up in jail as well. <br />
							  If you succeed you will gain experience and you fellow friend will be free!<br />
							  You can also buy out another user, this will succeed but you will gain no experience.
							  ';
    $txt['#'] = '#';
    $txt['username'] = 'Username';
    $txt['country'] = 'Country';
    $txt['time'] = 'Time';
    $txt['cost'] = 'Costs';
    $txt['buy_out'] = 'Bail out';
    $txt['bust'] = 'Break out';
    $txt['button_buy'] = 'Bail out';
    $txt['button_bust'] = 'Break out';
    $txt['nobody_injail_1'] = 'Currently there are no users in';
    $txt['nobody_injail_2'] = 'in jail.';
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
    $txt['button'] = 'Gooi!';
}

######################## WHO IS IT QUIZ ########################
elseif($page == 'who-is-it-quiz'){
    #Alerts
    $txt['alert_wait'] = 'Je kunt over
							  <strong><span id=uur3></span></strong> uur
							  <strong><span id=minuten3> </span>&nbsp;minuten</strong> en 
							  <strong><span id=seconden3></span>&nbsp;seconden</strong> de quiz nog eens doen.';
    $txt['alert_choose_a_pokemon'] = 'Geen pokemon gekozen.';
    $txt['alert_no_answer'] = 'Er is geen antwoord bekend.';
    $txt['success_win'] = 'Het antwoord is goed! Je hebt <img src="images/icons/silver.png" title="Silver"> 200 gewonnen! Je mag de quiz over een uur weer doen.';
    $txt['success_lose_1'] = 'Helaas, het antwoord was';
    $txt['success_lose_2'] = 'Probeer het over 1 uur weer.';

    #Screen
    $txt['pagetitle'] = 'Wie is het Quiz';
    $txt['who_is_it'] = 'Wie is het?';
    $txt['title_text'] = '<strong>Wie is het Quiz.</strong><br />
							  Je kunt hier 1 keer per uur raden wat de naam van de pokemon is.<br />
							  Als het antwoord goed is, verdien je <img src="images/icons/silver.png" title="Silver"> 200!';
    $txt['choose_a_pokemon'] = 'Kies een pokemon';
    $txt['button'] = 'Goo!';
}

######################## WHEEL OF FORTUNE ########################
elseif($page == 'wheel-of-fortune'){
    #Alerts
    $txt['alert_itemplace'] = 'Let op: U heeft geen itemplek over, dus u kunt geen Item winnen.';
    $txt['alert_no_more_wof'] = 'Je kan vandaag geen geluksrad meer doen.';
    $txt['win_100_silver'] = 'Je wint <img src="images/icons/silver.png" title="Silver"> 100 cash!';
    $txt['win_250_silver'] = 'Je wint <img src="images/icons/silver.png" title="Silver"> 250 cash!';
    $txt['win_ball'] = 'Je wint een';
    $txt['alert_itembox_full'] = 'Je item box is vol!';
    $txt['lose_jailzone'] = 'OH! Jail zone, je zit in jail!';
    $txt['win_spc_item'] = 'WOW! Je wint een Special item:';
    $txt['win_stone'] = 'Je wint een';
    $txt['win_tm'] = 'Je wint';

    #Screen
    $txt['pagetitle'] = 'Geluksrad';
    $txt['title_text_1'] = 'Je hebt nog';
    $txt['title_text_2'] = 'pogingen over vandaag.';
    $txt['premiumtext'] = '<br>Premiumleden kunnen dit 3x per dag doen. <a href="index.php?page=area-market"><strong>Word hier premium!</strong></a>';
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
    $txt['button'] = 'Koop!';
    $txt['only_premium'] = '* Alleen beschikbaar voor Premiumleden.';
    $txt['buy_tickets'] = 'Koop kaartjes:';
}

######################## Forum Categories ########################
elseif($page == 'forum-categories'){
    #Alerts
    $txt['alert_no_name'] = 'Please fill in a name.';
    $txt['alert_name_too_short'] = 'Name is too short, min. 3 characters.';
    $txt['alert_name_too_long'] = 'Name is too long, max. 20 characters.';
    $txt['alert_no_icon'] = 'Fill in an icon.';
    $txt['alert_icon_doenst_exist'] = 'Icon does not exist.';
    $txt['alert_name_already_taken'] = 'Category already exists.';
    $txt['success_add_category'] = 'Successfully created a new category.';
    $txt['success_edit_category'] = 'Successfully modified a category.';

    #Screen
    $txt['pagetitle'] = 'Forum';
    $txt['game-forum'] = ''.GLOBALDEF_SITENAME.' forum';
    $txt['#'] = '#';
    $txt['name'] = 'Name';
    $txt['threads'] = 'Topics';
    $txt['messages'] = 'Posts';
    $txt['last_post'] = 'Last post';
    $txt['nothing_posted'] = '';
    $txt['edit_category'] = 'Edit category';
    $txt['add_category'] = 'Add';
    $txt['name_of_category'] = 'Category name:';
    $txt['icon_url'] = 'Icon URL:';
    $txt['button'] = 'Add';
}

######################## Forum threads ########################
elseif($page == 'forum-threads'){
    #Alerts
    $txt['alert_no_name'] = 'Please fill in a name.';
    $txt['alert_name_too_short'] = 'Name is too short, min. 3 characters.';
    $txt['alert_name_too_long'] = 'Name is too long, max. 20 characters.';
    $txt['alert_name_already_taken'] = 'Topic already exists.';
    $txt['success_add_thread'] = 'You\'ve stared a topic, open it to add a post';
    $txt['success_edit_thread'] = 'Successfully modified a topic.';
    $txt['success_changed_status'] = 'Successfully modified a topic.';

    #Screen
    $txt['pagetitle'] = 'Forum';
    $txt['game-forum'] = ''.GLOBALDEF_SITENAME.' forum';
    $txt['#'] = '#';
    $txt['title'] = 'Titel';
    $txt['maker'] = 'Author';
    $txt['messages'] = 'Posts';
    $txt['last_post'] = 'Last post';
    $txt['no_threads'] = 'There are no topics in this category.';
    $txt['no_last_post'] = 'No posts.';
    $txt['open_thread'] = 'Open topic';
    $txt['close_thread'] = 'Close topic';
    $txt['edit_thread'] = 'Edit topic';
    $txt['thread_is_open'] = 'Topic is open';
    $txt['thread_is_closed'] = 'Topic is closed';
    $txt['add_thread'] = 'Start a topic:';
    $txt['english_topics'] = 'Altijd een engelse benaming.';
    $txt['name_of_thread'] = 'Topic:';
    $txt['button'] = 'Add';
}

######################## Forum messages ########################
elseif($page == 'forum-messages'){
    #Alerts
    $txt['alert_no_text'] = 'Please fill in a post.';
    $txt['alert_already_send'] = 'This post has been sent earlier.';
    $txt['success_post_message'] = 'Response successfull.';
    $txt['alert_not_admin'] = 'You do not have the rights.';
    $txt['alert_message_doesnt_exist'] = 'This post does not exist.';
    $txt['success_edit_message'] = 'Succesfully edited the post.';
    $txt['success_message_delete'] = 'Successfully removed.';

    #Screen
    $txt['topic_closed'] = 'Note: this topic is <strong>closed</strong>.';
    $txt['no_messages'] = 'There are no posts.';
    $txt['quote_this_message'] = 'Quote post';
    $txt['edit_this_message'] = 'Edit post';
    $txt['delete_this_message'] = 'Remove post';
    $txt['first_login'] = 'You need to login to respond.';
    $txt['topic_closed_no_reply'] = 'This topic is <strong>closed</strong>.';
    $txt['add_message'] = 'Reply:';
    $txt['button'] = 'Send';
}

######################## Beginning ########################
elseif($page == 'beginning'){
    #Screen
    $txt['pagetitle'] = 'Het begin';
    $txt['title_text'] = 'Welkom op '.GLOBALDEF_SITEDOMAIN.'.<br />
							  Mijn naam is professor Oak. <br />
							  Dit zijn de regels van '.GLOBALDEF_SITEDOMAIN.'.<br /><br />
							  * Je mag maar 1 account hebben.<br />
							  * Niet schelden tegen andere spelers.<br />
							  * Niet adverteren voor andere spellen.<br /><br />
							  Als je je niet aan deze regels houdt word je verbannen van de site.';
    $txt['button']	= 'Ga verder';
}

######################## Choose Pokemon ########################
elseif($page == 'choose-pokemon'){
    #Alerts
    $txt['alert_no_pokemon'] = 'Je hebt geen pokemon gekozen.';
    $txt['alert_pokemon_unknown'] = 'Pokemon dat je gekozen hebt is niet beschikbaar.';
    $txt['success'] = 'Je hebt succesvol een pokemon van Professor Oak gekregen.';

    #Screen
    $txt['pagetitle'] = 'Kies een Pokemon';
    $txt['title_text'] = 'Okee, genoeg gepraat over al die regels.<br /><br />
							  Ik wil jou een pokemon geven, omdat ik denk dat je er klaar voor bent.<br />
							  Hieronder is een lijst met pokemon wat ik voor je beschikbaar heb.
							  Kies diegene die jij wilt:';
    $txt['#'] = '#';
    $txt['starter_pokemon'] = 'Starter Pokemon';
    $txt['normal_pokemon'] = 'Normale Pokemon';
    $txt['baby_pokemon'] = 'Baby Pokemon';
    $txt['starter_name'] = 'Starter naam';
    $txt['type'] = 'Type';
    $txt['normal_name'] = 'Normaal naam';
    $txt['baby_name'] = 'Baby naam';
    $txt['no_pokemon_this_world'] = 'Er zijn geen Baby Pokemon in deze wereld.';
    $txt['button']	= 'Kies';
}

######################## Error page ########################
elseif($page == 'error'){
    #Screen
    $txt['pagetitle'] = 'Error';
    $txt['title_text'] = 'Sorry, deze pagina is niet toegankelijk voor jou. Log in met een account om deze pagina te zien.';
}

########################## Fising ##########################
elseif($page == 'fishing'){
    #Alerts
    $txt['alert_no_fishing_rod'] = 'You have no Fishing rod.';
    $txt['fish'] = '';
    $txt['alert_cant_fish_without'] = 'You can\'t fish without a';
    $txt['alert_no_premium'] = 'You cannot fish today.<br/>Premium members can fish up to 6 times a day. <a href="index.php?page=area-market"><strong>become premium here</strong></a>!';
    $txt['alert_cant_fish'] = 'You cannot fish today.';

    $txt['with_a'] = 'With a';
    $txt['youve'] = 'you\'ve caught a';
    $txt['caught'] = '';
    $txt['the_jury'] = 'The jury gave';
    $txt['points'] = 'points';
    $txt['granted'] = 'for the catch.';

    #Screen
    $txt['title'] = 'Fishing';
    $txt['fishing_text'] = 'Welcome in the fishing championship.<br/> The one who catches the biggest fish will receive the grand prize!<br/><br/>
        <b>1e place:</b> 2000 <img src="images/icons/silver.png"><br>
        <b>2de place:</b> 1500 <img src="images/icons/silver.png"><br>
        <b>3de place:</b> 1000 <img src="images/icons/silver.png"><br><br>

        <small><b><font color=red>Note:</font></b> Every new catch will overwrite your last score.</small>';
    $txt['fish_rod'] = 'Fishing rod';
    $txt['start'] = 'Start';
    $txt['topscore_today'] = 'Top score today';
    $txt['topscore_yesterday'] = 'Top score yesterday';
    $txt['user'] = 'User';
    $txt['score'] = 'Points';
}

######################## Attack Map ########################
elseif($page == 'attack/attack_map'){
    #Alerts
    $txt['alert_no_fishing_rod'] = 'You have no Fishing rod, go to the store to buy one.';
    $txt['alert_no_cave_suit'] = 'You have no Cave suit, go to the store to buy one.';
    $txt['alert_no_pokemon'] = 'The Pok&eacute;mon in your team are hurt, go to the Pok&eacute;moncenter.';

    #Screen
    $txt['to_pokemoncenter'] = 'Go to the Pok&eacute;moncenter.';
    $txt['title_text'] = 'Choose an area where you want to fight a Pok&eacute;mon!';
}

######################## Attack Gyms ########################
elseif($page == 'attack/gyms'){
    #Alerts
    $txt['alert_itemplace'] = 'Note: You have no more room in your itembox, if an item drops there will be no room to store it.';
    $txt['alert_rank_too_less'] = 'Your do not have the rank for this gym yet.';
    $txt['alert_wrong_world'] = 'You are in the wrong world.';
    $txt['alert_gym_finished'] = 'You already finished this gym.';
    $txt['alert_no_pokemon'] = 'You have no Pok&eacute;mon in your team.';
    $txt['begindood'] = "The Pok&eacute;mon in your team are hurt, go to the Pok&eacute;moncenter.";

    #Screen
    $txt['pagetitle'] = 'Gyms';
    $txt['finished'] = 'Finished';
    $txt['rank_too_less'] = 'Rank too low';
    $txt['leader'] = 'Leader:';
    $txt['from_rank'] = 'From rank';
    $txt['challenge'] = 'Challenge';
}

######################## Attack Duel invite ########################
elseif($page == 'attack/duel/invite'){
    #Alerts
    $txt['alert_not_yourself'] = 'You can\'t challenge yourself.';
    $txt['alert_unknown_amount'] = 'Please fill in an amount.';
    $txt['alert_not_enough_silver'] = 'You do not have enough silver.';
    $txt['alert_all_pokemon_ko'] = 'All your Pok&eacute;mon are knock out.';
    $txt['alert_opponent_not_in'] = 'is not in';
    $txt['alert_opponent_not_traveller'] = 'does not have the rank Traveller.';
    $txt['alert_opponent_duelevent_off'] = 'has disabled duel invites.';
    $txt['alert_opponent_already_fighting'] = 'is currently in a battle.';
    $txt['waiting_for_accept'] = 'has been challanged, wait untill the invite is accepted.';
    $txt['alert_opponent_no_silver'] = 'Your opponent doesn\'t have enough silver.';
    $txt['alert_opponent_no_health'] = 'Your opponent doesn\'t have alive Pok&eacute;mon.';
    $txt['alert_user_unknown'] = 'User does not exist.';

    #Screen
    $txt['title_text'] = '<p><img src="images/icons/duel.png" /> <strong>Challenge another user for a duel.</strong> <img src="images/icons/duel.png" /><br />
    Note: The user needs to be online.</p>';
    $txt['player'] = 'Username:';
    $txt['money'] = 'Bet:';
    $txt['button_duel'] = 'Challenge';
    $txt['waiting'] = 'Waiting';
}

######################## Attack Duel invited ########################
elseif($page == 'attack/duel/invited'){
    #Alerts
    $txt['alert_not_enough_silver'] = 'You do not have enough silver.';
    $txt['alert_all_pokemon_ko'] = 'All your Pok&eacute;mon are knock out.';
    $txt['success_accepted'] = 'You\'ve accepted the duel.';
    $txt['success_cancelled'] = 'You\'ve cancelled the duel.';
    $txt['alert_too_late'] = 'invited you for a duel. You where to late with your response.';

    #Screen
    $txt['pagetitle'] = 'You are beeing challenged';
    $txt['dueltext_1'] = 'The duel has a bet of:';
    $txt['dueltext_2'] = 'challenges you for a duel.';
    $txt['accept'] = 'Accept';
    $txt['cancel'] = 'Deny';
}

######################## Attack Wild ########################
elseif($page == 'attack/wild/wild-attack'){
    #Screen
    $txt['you_won'] = 'You\'ve won.';
    $txt['you_lost'] = 'You\'ve lost.';
    $txt['you_lost_1'] = 'You lost <img src=\'images/icons/silver.png\' title=\'Silver\'>';
    $txt['you_lost_2'] = '<br><a href=\'?page=pokemoncenter\'>Click here to go to the Pok&eacute;moncenter.</a>';
    $txt['you_first_attack'] = 'You\'re the first to attack.';
    $txt['opponent_first_attack'] = 'is the first to attack.';
    $txt['opponents_turn'] = 'is attacking.';
    $txt['your_turn'] = 'You\'re attacking.';
    $txt['have_to_change_1'] = 'Your';
    $txt['have_to_change_2'] = 'is knock out, you nee swtich Pok&eacute;mon.';
    $txt['next_time_wait'] = 'Wait until the battle is over.';
    $txt['fight_finished'] = 'The battle is finished.';
    $txt['success_catched_1'] = 'You\'ve cought';
    $txt['success_catched_2'] = '!';
    $txt['no_item_selected'] = 'Please choose an item';
    $txt['potion_no_pokemon_selected'] = 'Please choose a Pok&eacute;mon';
    $txt['busy_with_attack'] = 'Attacking..';
    $txt['have_already'] = 'You already have';
    $txt['a_wild'] = 'a wild';
    $txt['potion_text'] = 'Which pokemon do you want to give the';
    $txt['*'] = '*';
    $txt['pokemon'] = 'Pokemon';
    $txt['level'] = 'Level';
    $txt['health'] = 'Health';
    $txt['potion_egg_text'] = 'Not applicable';
    $txt['button_potion'] = 'Give';
    $txt['attack'] = 'Attack';
    $txt['change'] = 'Switch';
    $txt['items'] = 'Items';
    $txt['button_item'] = 'Use';
    $txt['must_attack'] = 'has to attack';
    $txt['is_ko'] = 'is knock out';
    $txt['flinched'] = 'is flinched';
    $txt['sleeps'] = 'sleeps.';
    $txt['awake'] = 'has woken up.';
    $txt['frozen'] = 'is frozen.';
    $txt['no_frozen'] = 'is defrosting.';
    $txt['not_paralyzed'] = 'is no longer paralyzed.';
    $txt['paralyzed'] = 'is paralyzed.';
    $txt['fight_over'] = 'The battle is over.';
    $txt['choose_another_pokemon'] = 'Choose anothe Pok&eacute;mon.';
    $txt['use_attack_1'] = 'used';
    $txt['use_attack_2'] = ' and hit. Your Pok&eacute;mon is knocked out.<br />';
    $txt['use_attack_2_hit'] = 'And hit.';
    $txt['did'] = 'Uses';
    $txt['hit!'] = 'and hit!';
    $txt['your_attack_turn'] = '<br />It is your turn now.';
    $txt['opponent_choose_attack'] = 'Chooses an attack.';

    $txt['pagetitle'] = 'Wilde pokemon gevecht';

    //Start Fight
    $txt['begindood'] = "All the Pok&eacute;mon in your team are knocked out.";
    $txt['opponent_error'] = "Error: No opponent found.";

    //Attack General
    $txt['success_catched_1'] = "You've caught ";
    $txt['success_catched_2'] = ". The battle finished.";
    $txt['new_pokemon_dead']   = " cannot battle because he's knocked out.";
    $txt['not_your_turn'] = " is attacking.";

    //Change Pokemon
    $txt['change_block'] = "You can't switch, You've been hit by block";
    $txt['change_egg']  = "You cannot use an egg!";
    $txt['success_change_1']  = "Switching Pok&eacute;mon.";
    $txt['success_change_2'] = "is attacking..";
    $txt['success_change_you_attack'] = "You've switched Pok&eacute;mon, you can attack now";

    //Use Pokeball
    $txt['ball_choose'] = "Choose an item or attack.";
    $txt['hand_house_full'] = "You have no more room left for a new Pok&eacute;mon.";
    $txt['ball_have'] = "You have to use a Pok&eacute;ball.";
    $txt['ball_amount'] = "You do not have a ";
    $txt['ball_throw_1'] = "You threw a ";
    $txt['ball_throw_2'] = ". ";
    $txt['ball_success'] = " was caught.";
    $txt['ball_failure'] = " has escaped.";
    $txt['ball_success_2'] = " is in your house.";

    //Use potion
    $txt['potion_choose'] = "Choose an item or attack.";
    $txt['potion_have'] = "Use the potion.";
    $txt['potion_life_full'] = " already has full health.";
    $txt['potion_amount'] = "You cannot ";
    $txt['potion_life_zero_1'] = "You cant restore ";
    $txt['potion_life_zero_2'] = "";
    $txt['potion_give_1'] = "You gave a ";
    $txt['potion_give_2'] = "  ";
    $txt['potion_give'] = "You've used a ";
    $txt['potion_give_end_1'] = " is cured";
    $txt['potion_give_end_2'] = " has healed";
    $txt['potion_give_end_3'] = " has healed";

    //Run
    $txt['success_run'] = "You've escaped from ";
    $txt['failure_run'] = "You could not escape from ";

    //Function
    $txt['recieve'] = "You receive";
    $txt['recieve_boost'] = "a boosted";
    $txt['recieve_premium_boost'] = "a premium boosted";
    $txt['recieve_boost_and_premium'] = "a boosted and premium boosted";

    $txt['exp_points'] = "experience gain.";
}
######################## Trainer Attack ########################
elseif($page == 'attack/trainer/trainer-attack'){
    #Screen
    $txt['you_won'] = 'Jij hebt gewonnen.';
    $txt['you_lost'] = 'Jij hebt verloren.';
    $txt['you_lost_1'] = 'Je verliest <img src=\'images/icons/silver.png\' title=\'Silver\'>';
    $txt['you_lost_2'] = '<br><a href=\'?page=pokemoncenter\'>Klik hier om naar de pokemon center te gaan.</a>';
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
    $txt['potion_no_pokemon_selected'] = 'Je moet wel een pokemon kiezen!';
    $txt['busy_with_attack'] = 'Bezig met aanval.';
    $txt['have_already'] = 'Je hebt al een';
    $txt['a_wild'] = 'a wild';
    $txt['potion_text'] = 'Which pokemon do you want to give the';
    $txt['*'] = '*';
    $txt['pokemon'] = 'Pokemon';
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
    $txt['choose_another_pokemon'] = 'Kies een andere pokemon.';
    $txt['use_attack_1'] = 'deed';
    $txt['use_attack_2'] = ', hij raakt. Je pokemon is Knock Out.<br />';
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
    $txt['begindood'] = "Al je pokemon die je opzak hebt zijn knock out.";
    $txt['opponent_error'] = "Error: Geen tegenstander Bekend.";

    //Attack General
    $txt['new_pokemon_dead']   = " kan niet vechten. Hij is knock out!";
    $txt['not_your_turn'] = " moet aanvallen.";

    //Change Pokemon
    $txt['change_block'] = "Je bent geraakt door Block je kunt niet ruilen!";
    $txt['change_egg']  = "Je kunt geen ei inbrengen!";
    $txt['success_change_1']  = "Je wisselt van pokemon.";
    $txt['success_change_2'] = "mag nu aanvallen.";
    $txt['success_change_you_attack'] = "Je wisselt van pokemon. Je mag nu aanvallen";

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
    $txt['you_lost_2'] = '<br><a href=\'?page=pokemoncenter\'>Klik hier om naar de pokemon center te gaan.</a>';
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
    $txt['pokemon_is_ko'] = 'Pokemon is knock out';
    $txt['opponent_have_changed_you_attack'] = 'heeft gewisseld, jij mag nu aanvallen.';
    $txt['you_have_changed_opponent_attack'] = 'heeft gewisseld en mag aanvallen.';
    $txt['you_have_to_change'] = ', raak! <br />Jij moet nu wisselen.';
    $txt['opponent_have_to_change'] = 'Tegenstander moet wisselen.';
    $txt['youre_defeated'] = ', Jij bent verslagen.';
    $txt['busy_with_attack'] = 'Bezig met aanval.';
    $txt['*'] = '*';
    $txt['pokemon'] = 'Pokemon';
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
    $txt['choose_another_pokemon'] = 'Kies een andere pokemon.';
    $txt['use_attack_1'] = 'deed';
    $txt['use_attack_2'] = ', hij raakt. Je pokemon is Knock Out.<br />';
    $txt['use_attack_2_hit'] = ', hij raakt.';
    $txt['did'] = 'deed';
    $txt['hit!'] = ', raak!';
    $txt['your_attack_turn'] = '<br />Het is nu jouw beurt.';
    $txt['opponent_choose_attack'] = 'kiest een aanval.';
    $txt['opponent_choose_pokemon'] = 'Kiest een pokemon.';

    $txt['pagetitle'] = 'Trainer gevecht';
}
######################## Catched ########################
elseif($page == 'catched'){
    #Screen
    $txt['shiny'] = "Shiny";
    $txt['normal'] = "Normal";
    $txt['amount_caught'] = "Has been caught ".$query2." times.";
}
elseif($page == 'clan-invite'){
    #Alerts
    $txt['alert_no_clan_leader'] = "You are not a leader of a clan.";
    $txt['alert_no_name'] = "Please fill in a name.";
    $txt['alert_clan_full'] = "Your clan is full, upgrade your clan to allow more members.";
    $txt['alert_already_in_clan'] = "The player you are trying to invite already has a clan.";
    $txt['alert_player_does_not_exist'] = "The player you are trying to invite doesn't exist.";
    $txt['invite_text'] = '<img src="images/icons/blue.png" width="16" height="16" class="imglower"> ' . $gebruiker['username'] . ' has invited you to his clan, named <strong>' . $clan['clan_naam'] . '</strong>.<a href="?page=clan-invite2&id=' . $claninputid . '&code=' . $code . '&accept=1">Accept</a>, <a href="?page=clan-invite2&id=' . $claninputid . '&code=' . $code . '&accept=0">Refuse</a>.';
    $txt['invite_sent'] = 'Invite sent to ' . $_POST['naam'] . '';

    #Screen
    $txt['invite_a_player_for_clan'] = "Invite a player for ".$clan['clan_naam'].".";
    $txt['max_invite_text'] = "<p>A level ".$clan['clan_level']." clan can have ".$clanmembers." members.</p><p>You can invite ".$claninvites." members.</p>";
    $txt['invite_button'] = "Invite";
    $txt['outstanding_invites'] = "No pending invites.";
    $txt['no_clan'] = "<center>You do not have a clan, create a clan <a href='?page=clan-make'>here</a>.</center>";
}