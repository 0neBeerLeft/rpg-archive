<?php 
#Script laden zodat je nooit pagina buiten de index om kan laden
include("includes/security.php");
	
$page = 'area-market';
#Goeie taal erbij laden voor de page
include_once('language/language-pages.php');

?>

<script type="text/javascript" src="javascripts/jquery.colorbox.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		//Examples of how to assign the ColorBox event to elements
		$(".colorbox").colorbox({width:"90%", height:"90%", iframe:true});
		
		//Example of preserving a JavaScript event for inline calls.
		$("#click").click(function(){ 
			$('#click').css({"background-color":"#f00", "color":"#fff", "cursor":"inherit"}).text("<?php echo $txt['colorbox_text']; ?>");
			return false;
		});
	});
</script>
<center>
	<h3><?php echo $txt['premiumpacks'].'</h3>'.$txt['premiumtext']; ?>
<br/><br/>
<?

	if($gebruiker['premium_lock'] != "1") {
		?>
		<div id="premiumpacks">
			<?
			/*promo points code start ->*/
			/*$result = mysql_query("SELECT * FROM gebruikers WHERE referer = '" . $gebruiker['username'] . "' AND account_code = 1");
			$num_rows = mysql_num_rows($result);
			$usedpp = mysql_fetch_object(mysql_query("SELECT promopoints_spent FROM gebruikers WHERE username = '" . $gebruiker['username'] . "'"));
			$promopoints = $num_rows - $usedpp->promopoints_spent;

			if ($_GET["name"] == "promo-riolu-pack" AND $promopoints >= 3) {
				echo "Je hebt een Riolu Pack gekocht.";
				require_once('premium/add_premium.inc.php');
				add_premium($_SESSION['naam'], "Riolu Pack");
				$dbpaid = mysql_query("INSERT INTO `premium_gekocht` (`datum`, `wie`, `wat`, `paycode`,`number`, `status`, `payment_id`, `verwerkt`)
            VALUES ('NOW()', '" . $_SESSION['naam'] . "', 'Riolu Pack', 'promo points', '', 'completed', '', 1) ");
				$updateprocessed = mysql_query("UPDATE gebruikers SET promopoints_spent = promopoints_spent+3 WHERE username = '" . $_SESSION['naam'] . "'");
			}
			if ($_GET["name"] == "promo-raichu-pack" AND $promopoints >= 5) {
				echo "Je hebt een Raichu Pack gekocht.";
				require_once('premium/add_premium.inc.php');
				add_premium($_SESSION['naam'], "Raichu Pack");
				$dbpaid = mysql_query("INSERT INTO `premium_gekocht` (`datum`, `wie`, `wat`, `paycode`,`number`, `status`, `payment_id`, `verwerkt`)
            VALUES ('NOW()', '" . $_SESSION['naam'] . "', 'Raichu Pack', 'promo points', '', 'completed', '', 1) ");
				$updateprocessed = mysql_query("UPDATE gebruikers SET promopoints_spent = promopoints_spent+5 WHERE username = '" . $_SESSION['naam'] . "'");
			}
			if ($_GET["name"] == "promo-scizor-pack" AND $promopoints >= 10) {
				echo "Je hebt een Scizor Pack gekocht.";
				require_once('premium/add_premium.inc.php');
				add_premium($_SESSION['naam'], "Scizor Pack");
				$dbpaid = mysql_query("INSERT INTO `premium_gekocht` (`datum`, `wie`, `wat`, `paycode`,`number`, `status`, `payment_id`, `verwerkt`)
            VALUES ('NOW()', '" . $_SESSION['naam'] . "', 'Scizor Pack', 'promo points', '', 'completed', '', 1) ");
				$updateprocessed = mysql_query("UPDATE gebruikers SET promopoints_spent = promopoints_spent+10 WHERE username = '" . $_SESSION['naam'] . "'");
			}
			if ($_GET["name"] == "promo-heatran-pack" AND $promopoints >= 20) {
				echo "Je hebt een Heatran Pack gekocht.";
				require_once('premium/add_premium.inc.php');
				add_premium($_SESSION['naam'], "Heatran Pack");
				$dbpaid = mysql_query("INSERT INTO `premium_gekocht` (`datum`, `wie`, `wat`, `paycode`,`number`, `status`, `payment_id`, `verwerkt`)
            VALUES ('NOW()', '" . $_SESSION['naam'] . "', 'Heatran Pack', 'promo points', '', 'completed', '', 1) ");
				$updateprocessed = mysql_query("UPDATE gebruikers SET promopoints_spent = promopoints_spent+20 WHERE username = '" . $_SESSION['naam'] . "'");
			}

			if ($promopoints >= 3) {
				*/?><!--
				<table width="660" cellspacing="0" cellpadding="0" class="Riolu_Pack">
					<tr>
						<td width="179"><strong>Riolu Pack</strong></td>
						<td width="170"><img src="images/icons/star-full-little.png" width="16" height="16"
											 alt="Premium"
											 title="Premium" class="imglower"/> 7 <?/* echo $txt['premiumdays']; */?></td>
						<td width="100"><img src="images/icons/silver.png" width="16" height="16" alt="Silver"
											 title="Silver"
											 class="imglower"/> 8.000
						</td>
						<td width="90"><img src="images/icons/gold.png" width="16" height="16" alt="Gold" title="Gold"
											class="imglower"/> 250
						</td>
						<td width="80">&euro; 1.30</td>
						<td width="41"><a href="?page=area-market&name=promo-riolu-pack">Gratis</a>
						</td>
					</tr>
				</table>
			<?/*
			}
			if ($promopoints >= 5) {
				*/?>
				<table width="660" cellspacing="0" cellpadding="0" class="Raichu_Pack">
					<tr>
						<td width="179"><strong>Raichu Pack</strong></td>
						<td width="170"><img src="images/icons/star-full-little.png" width="16" height="16"
											 alt="Premium"
											 title="Premium" class="imglower"/> 14 <?/* echo $txt['premiumdays']; */?></td>
						<td width="100"><img src="images/icons/silver.png" width="16" height="16" alt="Silver"
											 title="Silver"
											 class="imglower"/> 16.000
						</td>
						<td width="90"><img src="images/icons/gold.png" width="16" height="16" alt="Gold" title="Gold"
											class="imglower"/> 5000
						</td>
						<td width="80">&euro; 2.60</td>
						<td width="41"><a href="?page=area-market&name=promo-raichu-pack">Gratis</a>
						</td>
					</tr>
				</table>
			<?/*
			}
			if ($promopoints >= 10) {
				*/?>
				<table width="660" cellspacing="0" cellpadding="0" class="Scizor_Pack">
					<tr>
						<td width="179"><strong>Scizor Pack</strong></td>
						<td width="170"><img src="images/icons/star-full-little.png" width="16" height="16"
											 alt="Premium"
											 title="Premium" class="imglower"/> 30 <?/* echo $txt['premiumdays']; */?></td>
						<td width="100"><img src="images/icons/silver.png" width="16" height="16" alt="Silver"
											 title="Silver"
											 class="imglower"/> 32.000
						</td>
						<td width="90"><img src="images/icons/gold.png" width="16" height="16" alt="Gold" title="Gold"
											class="imglower"/> 1.000
						</td>
						<td width="80">&euro; 5.20</td>
						<td width="41"><a href="?page=area-market&name=promo-scizor-pack">Gratis</a>
						</td>
					</tr>
				</table>
			<?/*
			}
			if ($promopoints >= 20) {
				*/?>
				<table width="660" cellspacing="0" cellpadding="0" class="Heatran_Pack">
					<tr>
						<td width="179"><strong>Heatran Pack</strong></td>
						<td width="170"><img src="images/icons/star-full-little.png" width="16" height="16"
											 alt="Premium"
											 title="Premium" class="imglower"/> 90 <?/* echo $txt['premiumdays']; */?></td>
						<td width="100"><img src="images/icons/silver.png" width="16" height="16" alt="Silver"
											 title="Silver"
											 class="imglower"/> 64.000
						</td>
						<td width="90"><img src="images/icons/gold.png" width="16" height="16" alt="Gold" title="Gold"
											class="imglower"/> 2.000
						</td>
						<td width="80">&euro; 10.40</td>
						<td width="41"><a href="?page=area-market&name=promo-heatran-pack">Gratis</a>
						</td>
					</tr>
				</table>
			--><?/*
			}*/
			/*<- promo points code end*/
			?>
			<?php

            #Retrieve premium packs
            $premiumQuery = "SELECT * FROM premium";
            $premiumsql = $db->prepare($premiumQuery);
            $premiumsql->execute();
            $premiumsql = $premiumsql->fetchAll(PDO::FETCH_ASSOC);

			foreach ($premiumsql as $premium) {
echo '<table width="660" cellspacing="0" cellpadding="0" class="' . str_replace(" ", "_", $premium['naam']) . '">
	<tr>
		<td width="179"><strong>' . $premium['naam'] . '</strong></td>
		<td width="170"><img src="images/icons/star-full-little.png" width="16" height="16" alt="Premium" title="Premium" class="imglower" /> ' . $premium['dagen'] . ' ' . $txt['premiumdays'] . '</td>
		<td width="100"><img src="images/icons/silver.png" width="16" height="16" alt="Silver" title="Silver" class="imglower" /> ' . highamount($premium['silver']) . '</td>
		<td width="90"><img src="images/icons/gold.png" width="16" height="16" alt="Gold" title="Gold" class="imglower" /> ' . $premium['gold'] . '</td>
		<td width="80">&euro; ' . $premium['kosten'] . '</td>
		<td width="41"><a href="?page=area-box&name=' . $premium['naam'] . '"><img src="images/icons/buypremium.gif" border="0" title="' . $txt['buy'] . ' ' . $premium['naam'] . '" /></a></td>
	</tr>
</table>';
			if ($premium['id'] == 3) {
				echo '<h3 style="margin-top:18px;">' . $txt['valuepacks'] . '</h3>' . $txt['valuetext'];
			} elseif ($premium['id'] == 7) {
					echo '<h3 style="margin-top:18px;">' . $txt['premiumrow'] . '</h3>' . $txt['premiumrowtext'];
				}
			}

			?>

		</div>
	<?
	} else {

		echo "<div class='blue'>Je hebt deze maand het maximum aan premium packages gekocht. Morgen mag je weer bestellen.</div>";
	}
?>
<br/><br/>

<span><b>Ben je minderjarig? Vraag dan eerst toestemming aan je ouder(s)/verzorger(s).</b><br/>
Het gebruiken van het belservice systeem is geen verplichting, en doe je dus geheel op eigen risico.
<br/><br/>
Bij eventuele schade kan <?=GLOBALDEF_SITENAME?> niet aansprakelijk worden gesteld. </span></center>