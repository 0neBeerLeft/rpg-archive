<?php
//Script laden zodat je nooit pagina buiten de index om kan laden
include("includes/security.php");

if (isset($error)) {
    echo $error;
}
?>
    <center>
        <table width="600" border="0">
            <tr>
                <td>
                    <center><?php echo $txt['title_text']; ?><br/><br/><a href='?page=pokemoncenter'><img
                                    src='/images/pokemoncenter.gif' title='Pokemoncenter'><br/>Naar het Pok&eacute;moncenter.</a><br/><br/>Zeer
                        zeldzame Pok&eacute;mon kans: <b>3:1000</b><br/><br/></center>
                </td>
            </tr>
        </table>
    </center>
<?php
if ($gebruiker['wereld'] == "Kanto") {
    echo "<center>
    <table width='580' style='border: 1px solid #000000;' cellspacing='0' cellpadding='0'>
      <tr>
        <td><table width='580' border='0' cellspacing='0' cellpadding='0'>
          <tr>
            <td width='346' height='179'><form method='post' name='Lavagrot'><input type='image' onClick='Lavagrot.submit();' src='images/attackmap/kanto/lavagrot.gif' width='346' height='179' alt='Firecave' /><input type='hidden' value='1' name='gebied'></form></td>
            <td width='234' height='179'><form method='post' name='Vechtschool'><input type='image' onClick='Vechtschool.submit();' src='images/attackmap/kanto/vechtschool.gif' width='234' height='179' alt='Fighting gym' /><input type='hidden' value='2' name='gebied'></form></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table width='580' border='0' cellspacing='0' cellpadding='0'>
          <tr>
            <td width='216' height='249'><form method='post' name='Gras'><input type='image' onClick='Gras.submit();' src='images/attackmap/kanto/grasveld.gif' width='216' height='249' alt='Grass field' /><input type='hidden' value='3' name='gebied'></form></td>
            <td width='123' height='249'><form method='post' name='Spookhuis'><input type='image' onClick='Spookhuis.submit();' src='images/attackmap/kanto/spookhuis.gif' width='123' height='249' alt='Ghosthouse' /><input type='hidden' value='4' name='gebied'></form></td>
            <td width='241' height='249'><form method='post' name='Grot'><input type='image' onClick='Grot.submit();' src='images/attackmap/kanto/grot.gif' width='241' height='249' alt='Cave' /><input type='hidden' value='5' name='gebied'></form></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table width='580' border='0' cellspacing='0' cellpadding='0'>
          <tr>
            <td width='305'height='172'><form method='post' name='Water'><input type='image' onClick='Water.submit();' src='images/attackmap/kanto/water.gif' width='305' height='172' alt='Water' /><input type='hidden' value='6' name='gebied'></form></td>
            <td width='275' height='172'><form method='post' name='Strand'><input type='image' onClick='Strand.submit();' src='images/attackmap/kanto/strand.gif' width='275' height='172' alt='Beach'/><input type='hidden' value='7' name='gebied'></form></td>
          </tr>
        </table></td>
      </tr>
    </table>
	</center>";
} elseif ($gebruiker['wereld'] == "Johto") {
    echo "<center>
    <table width='580' style='border: 1px solid #000000;' cellspacing='0' cellpadding='0'>
      <tr>
        <td><table width='580' border='0' cellspacing='0' cellpadding='0'>
          <tr>
            <td width='346' height='179'><form method='post' name='Lavagrot'><input type='image' onClick='Lavagrot.submit();' src='images/attackmap/johto/lavagrot.gif' width='346' height='179' alt='Firecave' /><input type='hidden' value='1' name='gebied'></form></td>
            <td width='234' height='179'><form method='post' name='Vechtschool'><input type='image' onClick='Vechtschool.submit();' src='images/attackmap/johto/vechtschool.gif' width='234' height='179' alt='Fighting gym' /><input type='hidden' value='2' name='gebied'></form></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table width='580' border='0' cellspacing='0' cellpadding='0'>
          <tr>
            <td width='216' height='249'><form method='post' name='Gras'><input type='image' onClick='Gras.submit();' src='images/attackmap/johto/grasveld.gif' width='216' height='249' alt='Grass field' /><input type='hidden' value='3' name='gebied'></form></td>
            <td width='123' height='249'><form method='post' name='Spookhuis'><input type='image' onClick='Spookhuis.submit();' src='images/attackmap/johto/spookhuis.gif' width='123' height='249' alt='Ghosthouse' /><input type='hidden' value='4' name='gebied'></form></td>
            <td width='241' height='249'><form method='post' name='Grot'><input type='image' onClick='Grot.submit();' src='images/attackmap/johto/grot.gif' width='241' height='249' alt='Cave' /><input type='hidden' value='5' name='gebied'></form></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table width='580' border='0' cellspacing='0' cellpadding='0'>
          <tr>
            <td width='305'height='172'><form method='post' name='Water'><input type='image' onClick='Water.submit();' src='images/attackmap/johto/water.gif' width='305' height='172' alt='Water' /><input type='hidden' value='6' name='gebied'></form></td>
            <td width='275' height='172'><form method='post' name='Strand'><input type='image' onClick='Strand.submit();' src='images/attackmap/johto/strand.gif' width='275' height='172' alt='Beach'/><input type='hidden' value='7' name='gebied'></form></td>
          </tr>
        </table></td>
      </tr>
    </table>
	</center>";
} elseif ($gebruiker['wereld'] == "Hoenn") {
    echo "<center>
    <table width='580' style='border: 1px solid #000000;' cellspacing='0' cellpadding='0'>
      <tr>
        <td><table width='580' border='0' cellspacing='0' cellpadding='0'>
          <tr>
            <td width='346' height='179'><form method='post' name='Lavagrot'><input type='image' onClick='Lavagrot.submit();' src='images/attackmap/hoenn/lavagrot.gif' width='346' height='179' alt='Firecave' /><input type='hidden' value='1' name='gebied'></form></td>
            <td width='234' height='179'><form method='post' name='Vechtschool'><input type='image' onClick='Vechtschool.submit();' src='images/attackmap/hoenn/vechtschool.gif' width='234' height='179' alt='Fighting gym' /><input type='hidden' value='2' name='gebied'></form></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table width='580' border='0' cellspacing='0' cellpadding='0'>
          <tr>
            <td width='216' height='249'><form method='post' name='Gras'><input type='image' onClick='Gras.submit();' src='images/attackmap/hoenn/grasveld.gif' width='216' height='249' alt='Grass field' /><input type='hidden' value='3' name='gebied'></form></td>
            <td width='123' height='249'><form method='post' name='Spookhuis'><input type='image' onClick='Spookhuis.submit();' src='images/attackmap/hoenn/spookhuis.gif' width='123' height='249' alt='Ghosthouse' /><input type='hidden' value='4' name='gebied'></form></td>
            <td width='241' height='249'><form method='post' name='Grot'><input type='image' onClick='Grot.submit();' src='images/attackmap/hoenn/grot.gif' width='241' height='249' alt='Cave' /><input type='hidden' value='5' name='gebied'></form></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table width='580' border='0' cellspacing='0' cellpadding='0'>
          <tr>
            <td width='305'height='172'><form method='post' name='Water'><input type='image' onClick='Water.submit();' src='images/attackmap/hoenn/water.gif' width='305' height='172' alt='Water' /><input type='hidden' value='6' name='gebied'></form></td>
            <td width='275' height='172'><form method='post' name='Strand'><input type='image' onClick='Strand.submit();' src='images/attackmap/hoenn/strand.gif' width='275' height='172' alt='Beach'/><input type='hidden' value='7' name='gebied'></form></td>
          </tr>
        </table></td>
      </tr>
    </table>
	</center>";
} elseif ($gebruiker['wereld'] == "Sinnoh") {
    echo "<center>
    <table width='580' style='border: 1px solid #000000;' cellspacing='0' cellpadding='0'>
      <tr>
        <td><table width='580' border='0' cellspacing='0' cellpadding='0'>
          <tr>
            <td width='346' height='179'><form method='post' name='Lavagrot'><input type='image' onClick='Lavagrot.submit();' src='images/attackmap/sinnoh/lavagrot.gif' width='346' height='179' alt='Firecave' /><input type='hidden' value='1' name='gebied'></form></td>
            <td width='234' height='179'><form method='post' name='Vechtschool'><input type='image' onClick='Vechtschool.submit();' src='images/attackmap/sinnoh/vechtschool.gif' width='234' height='179' alt='Fighting gym' /><input type='hidden' value='2' name='gebied'></form></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table width='580' border='0' cellspacing='0' cellpadding='0'>
          <tr>
            <td width='216' height='249'><form method='post' name='Gras'><input type='image' onClick='Gras.submit();' src='images/attackmap/sinnoh/grasveld.gif' width='216' height='249' alt='Grass field' /><input type='hidden' value='3' name='gebied'></form></td>
            <td width='123' height='249'><form method='post' name='Spookhuis'><input type='image' onClick='Spookhuis.submit();' src='images/attackmap/sinnoh/spookhuis.gif' width='123' height='249' alt='Ghosthouse' /><input type='hidden' value='4' name='gebied'></form></td>
            <td width='241' height='249'><form method='post' name='Grot'><input type='image' onClick='Grot.submit();' src='images/attackmap/sinnoh/grot.gif' width='241' height='249' alt='Cave' /><input type='hidden' value='5' name='gebied'></form></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table width='580' border='0' cellspacing='0' cellpadding='0'>
          <tr>
            <td width='305'height='172'><form method='post' name='Water'><input type='image' onClick='Water.submit();' src='images/attackmap/sinnoh/water.gif' width='305' height='172' alt='Water' /><input type='hidden' value='6' name='gebied'></form></td>
            <td width='275' height='172'><form method='post' name='Strand'><input type='image' onClick='Strand.submit();' src='images/attackmap/sinnoh/strand.gif' width='275' height='172' alt='Beach'/><input type='hidden' value='7' name='gebied'></form></td>
          </tr>
        </table></td>
      </tr>
    </table>
	</center>";
} elseif ($gebruiker['wereld'] == "Unova") {
    echo "<center>
    <table width='580' style='border: 1px solid #000000;' cellspacing='0' cellpadding='0'>
      <tr>
        <td><table width='580' border='0' cellspacing='0' cellpadding='0'>
          <tr>
            <td width='346' height='179'><form method='post' name='Lavagrot'><input type='image' onClick='Lavagrot.submit();' src='images/attackmap/unova/lavagrot.gif' width='346' height='179' alt='Firecave' /><input type='hidden' value='1' name='gebied'></form></td>
            <td width='234' height='179'><form method='post' name='Vechtschool'><input type='image' onClick='Vechtschool.submit();' src='images/attackmap/unova/vechtschool.gif' width='234' height='179' alt='Fighting gym' /><input type='hidden' value='2' name='gebied'></form></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table width='580' border='0' cellspacing='0' cellpadding='0'>
          <tr>
            <td width='216' height='249'><form method='post' name='Gras'><input type='image' onClick='Gras.submit();' src='images/attackmap/unova/grasveld.gif' width='216' height='249' alt='Grass field' /><input type='hidden' value='3' name='gebied'></form></td>
            <td width='123' height='249'><form method='post' name='Spookhuis'><input type='image' onClick='Spookhuis.submit();' src='images/attackmap/unova/spookhuis.gif' width='123' height='249' alt='Ghosthouse' /><input type='hidden' value='4' name='gebied'></form></td>
            <td width='241' height='249'><form method='post' name='Grot'><input type='image' onClick='Grot.submit();' src='images/attackmap/unova/grot.gif' width='241' height='249' alt='Cave' /><input type='hidden' value='5' name='gebied'></form></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table width='580' border='0' cellspacing='0' cellpadding='0'>
          <tr>
            <td width='305'height='172'><form method='post' name='Water'><input type='image' onClick='Water.submit();' src='images/attackmap/unova/water.gif' width='305' height='172' alt='Water' /><input type='hidden' value='6' name='gebied'></form></td>
            <td width='275' height='172'><form method='post' name='Strand'><input type='image' onClick='Strand.submit();' src='images/attackmap/unova/strand.gif' width='275' height='172' alt='Beach'/><input type='hidden' value='7' name='gebied'></form></td>
          </tr>
        </table></td>
      </tr>
    </table>
	</center>";
} elseif ($gebruiker['wereld'] == "Kalos") {
    echo "<center>
    <table width='580' style='border: 1px solid #000000;' cellspacing='0' cellpadding='0'>
      <tr>
        <td><table width='580' border='0' cellspacing='0' cellpadding='0'>
          <tr>
            <td width='346' height='179'><form method='post' name='Lavagrot'><input type='image' onClick='Lavagrot.submit();' src='images/attackmap/kalos/lavagrot.gif' width='346' height='179' alt='Firecave' /><input type='hidden' value='1' name='gebied'></form></td>
            <td width='234' height='179'><form method='post' name='Vechtschool'><input type='image' onClick='Vechtschool.submit();' src='images/attackmap/kalos/vechtschool.gif' width='234' height='179' alt='Fighting gym' /><input type='hidden' value='2' name='gebied'></form></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table width='580' border='0' cellspacing='0' cellpadding='0'>
          <tr>
            <td width='216' height='249'><form method='post' name='Gras'><input type='image' onClick='Gras.submit();' src='images/attackmap/kalos/grasveld.gif' width='216' height='249' alt='Grass field' /><input type='hidden' value='3' name='gebied'></form></td>
            <td width='123' height='249'><form method='post' name='Spookhuis'><input type='image' onClick='Spookhuis.submit();' src='images/attackmap/kalos/spookhuis.gif' width='123' height='249' alt='Ghosthouse' /><input type='hidden' value='4' name='gebied'></form></td>
            <td width='241' height='249'><form method='post' name='Grot'><input type='image' onClick='Grot.submit();' src='images/attackmap/kalos/grot.gif' width='241' height='249' alt='Cave' /><input type='hidden' value='5' name='gebied'></form></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table width='580' border='0' cellspacing='0' cellpadding='0'>
          <tr>
            <td width='305'height='172'><form method='post' name='Water'><input type='image' onClick='Water.submit();' src='images/attackmap/kalos/water.gif' width='305' height='172' alt='Water' /><input type='hidden' value='6' name='gebied'></form></td>
            <td width='275' height='172'><form method='post' name='Strand'><input type='image' onClick='Strand.submit();' src='images/attackmap/kalos/strand.gif' width='275' height='172' alt='Beach'/><input type='hidden' value='7' name='gebied'></form></td>
          </tr>
        </table></td>
      </tr>
    </table>
	</center>";
}