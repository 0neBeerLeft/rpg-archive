<?php
//Load Safety Script
include("includes/security.php");
//Load language
$page = 'attack/trainer/trainer-attack';
//Goeie taal erbij laden voor de page
include_once('language/language-pages.php');
//Include Attack Functions
include("attack/attack.inc.php");

$aanval_log = aanval_log($_SESSION['trainer']['aanval_log_id']);
$trainer = mysql_fetch_array(mysql_query("SELECT * FROM `trainer` WHERE `naam`='" . $aanval_log['trainer'] . "'"));
if (empty($trainer['badge'])) {
    $return_link = "attack/attack_map";
    $gym = 1;
} else {
    $return_link = "attack/gyms";
    $gym = 0;
}

//Player in log is diffirent then loggedin
if ($aanval_log['user_id'] != $_SESSION['id']) {
    //End Attack
    remove_attack($aanval_log['id']);
    //Send back to home
    header("Location: index.php?page=home");
    unset($_SESSION['trainer']['duel_id']);
} else {
    //Load All Openent Info
    $computer_info = computer_data($aanval_log['tegenstanderid']);
    //Make all letters small
    $computer_info['naam_klein'] = strtolower($computer_info['naam']);
    //Change name for male and female
    $computer_info['naam_goed'] = computer_naam($computer_info['naam']);

    //Calculate Life in Procent for Computer
    if ($computer_info['leven'] != 0) $computer_life_procent = round(($computer_info['leven'] / $computer_info['levenmax']) * 100);
    else $computer_life_procent = 0;

    //Shiny
    $computer_info['map'] = "pokemon";
    $computer_info['star'] = "none";
    if ($computer_info['shiny'] == 1) {
        $computer_info['map'] = "shiny";
        $computer_info['star'] = "block";
    }

    //Load All Pokemon Info
    $pokemon_info = pokemon_data($aanval_log['pokemonid']);
    $pokemon_info['naam_klein'] = strtolower($pokemon_info['naam']);
    $pokemon_info['naam_goed'] = pokemon_naam($pokemon_info['naam'], $pokemon_info['roepnaam']);

    //Calculate Life in Procent for Pokemon
    if ($pokemon_info['leven'] != 0) $pokemon_life_procent = round(($pokemon_info['leven'] / $pokemon_info['levenmax']) * 100);
    else $pokemon_life_procent = 0;

    //Calculate Exp in procent for pokemon
    if ($pokemon_info['expnodig'] != 0) $pokemon_exp_procent = round(($pokemon_info['exp'] / $pokemon_info['expnodig']) * 100);
    else $pokemon_exp_procent = 0;

    //Shiny
    $pokemon_info['map'] = "pokemon";
    $pokemon_info['star'] = "none";
    if ($pokemon_info['shiny'] == 1) {
        $pokemon_info['map'] = "shiny";
        $pokemon_info['star'] = "block";
    }

    ?>
    <script language="javascript">
        var speler_attack
        var timer
        var next_turn_timer
        var attack_timer = 0
        var speler_wissel

        //If div is ready
        $("#message").ready(function () {
            //Show Starting Screen
            if ((("<? echo $aanval_log['laatste_aanval']; ?>" == "spelereersteaanval") || ("<? echo $aanval_log['laatste_aanval']; ?>" == "computereersteaanval")) && ("<? echo $_SESSION['trainer']['begin_zien']; ?>" == 1)) {
                //Set Images
                $("#img_pokemon").attr("src", "images/you/back/<? echo $gebruiker['character']; ?>.png")
                $("#img_trainer").attr("src", "images/trainers/<? echo $aanval_log['trainer']; ?>.png")
                setTimeout("show_start_text();", 500)
                $("#message").html("<? echo $txt['start_0'] . $aanval_log['trainer'] . $txt['start_1']; ?>")
                $("#pokemon_text").hide()
                $("#trainer_naam").html("<? echo $aanval_log['trainer'] . " " . $txt['appears']; ?>.")
                $.ajax({
                    type: "GET",
                    url: "attack/trainer/trainer-stop-start.php"
                });
            }
            //Write Start Text
            else if ("<? echo $aanval_log['laatste_aanval']; ?>" == "spelereersteaanval") {
                speler_attack = 1
                $("#message").html("<?php echo $txt['you_first_attack']; ?>")
            }
            else if ("<? echo $aanval_log['laatste_aanval']; ?>" == "computereersteaanval") {
                speler_attack = 0
                speler_wissel = 0;
                $("#message").html("<? echo $computer_info['naam_goed'] . ' ' . $txt['opponent_first_attack']; ?>")
                next_turn()
            }
            else if ("<? echo $aanval_log['laatste_aanval']; ?>" == "pokemon") {
                speler_attack = 0
                next_turn()
                $("#message").html("<? echo $computer_info['naam_goed'] . ' ' . $txt['opponents_turn']; ?>")
            }
            else if ("<? echo $aanval_log['laatste_aanval']; ?>" == "computer") {
                speler_attack = 1
                $("#message").html("<?php echo $txt['your_turn']; ?>")
            }
            else if ("<? echo $aanval_log['laatste_aanval']; ?>" == "speler_wissel") {
                speler_attack = 0
                speler_wissel = 1
                $("#message").html("<? echo $pokemon_info['naam_goed'] . ' ' . $txt['have_to_change']; ?>")
            }
            else if ("<? echo $aanval_log['laatste_aanval']; ?>" == "trainer_wissel") {
                speler_attack = 0
                speler_wissel = 0
                $("#message").html("<? echo $computer_info['naam_goed'] . ' ' . $txt['opponent_have_to_change_1'] . ' ' . $aanval_log['trainer'] . ' ' . $txt['opponent_have_to_change_2']; ?>")
                trainer_change()
            }
            else if ("<? echo $aanval_log['laatste_aanval']; ?>" == "klaar") {
                speler_attack = 1
                $("#message").html("<?php echo $txt['fight_finished']; ?>")
                setTimeout("location.href='index.php?page=<? echo $return_link; ?>'", 500)
            }
            else if ("<? echo $aanval_log['laatste_aanval']; ?>" == "end_screen") {
                speler_attack = 0
                speler_wissel = 0
                show_end_screen()
            }
            else {
                $("#message").html("Foutcode: 0001\nInfo:<? echo $aanval_log['laatste_aanval']; ?>")
            }
        });
    </script>
    <?

    //Player Pokemon In Hand
    for ($inhand = 1; $player_hand = mysql_fetch_assoc($pokemon_sql); $inhand++) {
        //Check Wich Pokemon is infight
        if ($player_hand['id'] == $pokemon_info['id']) $infight = 1;
        else $infight = 0;
        if ($player_hand['ei'] == 1) {
            $player_hand['naam'] = "??";
            $player_hand['wild_id'] = "??";
        }
        ?>

        <script>
            //If div is ready
            $("div[id='change_pokemon']").ready(function () {
                //Is pokemon in fight, so yes, don't show
                if (<? echo $infight; ?> == 1
                )
                {
                    if (<? echo $player_hand['shiny']; ?> == 1
                )
                    {
                        $("div[id='change_pokemon'][name='<? echo $inhand; ?>']").css({backgroundImage: "url(images/shiny/icon/<? echo strtolower($player_hand['wild_id']); ?>.gif)"});
                        $("div[id='change_pokemon'][name='<? echo $inhand; ?>']").attr("title", "<? echo $player_hand['naam']; ?> \nLife: <? echo $player_hand['leven']; ?>/<? echo $player_hand['levenmax']; ?>");
                    }
                else
                    {
                        $("div[id='change_pokemon'][name='<? echo $inhand; ?>']").css({backgroundImage: "url(images/pokemon/icon/<? echo strtolower($player_hand['wild_id']); ?>.gif)"});
                        $("div[id='change_pokemon'][name='<? echo $inhand; ?>']").attr("title", "<? echo $player_hand['naam']; ?> \nLife: <? echo $player_hand['leven']; ?>/<? echo $player_hand['levenmax']; ?>");
                    }
                }
                else
                if ("Ja" == "<? echo $player_hand['baby']; ?>") {
                    $("div[id='change_pokemon'][name='<? echo $inhand; ?>']").css({backgroundImage: "url(images/icons/egg.gif)"});
                    $("div[id='change_pokemon'][name='<? echo $inhand; ?>']").attr("title", "Egg");
                    $("div[id='change_pokemon'][name='<? echo $inhand; ?>']").show()
                }
                //Pokemon is not in fight, show.
                else {
                    if (<? echo $player_hand['id']; ?> !=
                    ""
                )
                    {
                        if (<? echo $player_hand['shiny']; ?> == 1
                    )
                        {
                            $("div[id='change_pokemon'][name='<? echo $inhand; ?>']").css({backgroundImage: "url(images/shiny/icon/<? echo strtolower($player_hand['wild_id']); ?>.gif)"});
                            $("div[id='change_pokemon'][name='<? echo $inhand; ?>']").attr("title", "<? echo $player_hand['naam']; ?> \nLife: <? echo $player_hand['leven']; ?>/<? echo $player_hand['levenmax']; ?>");
                        }
                    else
                        {
                            $("div[id='change_pokemon'][name='<? echo $inhand; ?>']").css({backgroundImage: "url(images/pokemon/icon/<? echo strtolower($player_hand['wild_id']); ?>.gif)"});
                            $("div[id='change_pokemon'][name='<? echo $inhand; ?>']").attr("title", "<? echo $player_hand['naam']; ?> \nLife: <? echo $player_hand['leven']; ?>/<? echo $player_hand['levenmax']; ?>");
                        }
                        $("div[id='change_pokemon'][name='<? echo $inhand; ?>']").show()
                    }
                }
            });
        </script>
        <?
    }
    //Set Player hand query counter on 0
    mysql_data_seek($pokemon_sql, 0);
    ?>
    <script type="text/javascript" src="attack/javascript/attack.js"></script>
    <script language="javascript">

        function show_start_text() {
            $("#img_pokemon").attr("src", "images/<? echo $pokemon_info['map']; ?>/back/<? echo $pokemon_info['wild_id']; ?>.gif");
            $("#img_trainer").attr("src", "images/<? echo $computer_info['map']; ?>/<? echo $computer_info['wild_id']; ?>.gif");
            $("#pokemon_naam").html("<? echo $pokemon_info['naam_goed']; ?>")
            $("#pokemon_level").html("<? echo $pokemon_info['level']; ?>")
            $("#trainer_naam").html("<? echo $computer_info['naam_goed']; ?>")
            $("#pokemon_text").show()
            if ("<? echo $aanval_log['laatste_aanval']; ?>" == "spelereersteaanval") {
                speler_attack = 1
                speler_wissel = 1
                $("#message").html("<?php echo $txt['you_first_attack']; ?>")
            }
            else if ("<? echo $aanval_log['laatste_aanval']; ?>" == "computereersteaanval") {
                speler_attack = 0
                speler_wissel = 0;
                $("#message").html("<? echo $computer_info['naam_goed'] . ' ' . $txt['opponent_first_attack']; ?>")
                next_turn()
            }
        }

        function show_end_screen(id) {
            $.get("attack/trainer/trainer-finish.php?aanval_log_id="+<? echo $aanval_log['id']; ?>+
            "&sid=" + Math.random(), function (data) {
                request = data.split(" | ")
                if (request[4] == 1) {
                    if (request[0] == "") $("#message").html("<? echo $txt['defeated_1'] . ' ' . $aanval_log['trainer'] . ' ' . $txt['defeated_2']; ?>" + request[1] + " Silver.")
                    else if (request[2] == "1") $("#message").html("<? echo $txt['defeated_1'] . ' ' . $aanval_log['trainer'] . ' ' . $txt['defeated_masterball']; ?>" + request[1] + " Silver.")
                    else $("#message").html("<? echo $txt['defeated_1'] . ' ' . $aanval_log['trainer'] . ' ' . $txt['get_badge_1']; ?> " + request[0] + " <?php echo $txt['get_badge_2']; ?> " + request[1] + " Silver.")
                    if ((<? echo $gebruiker['Badge case']; ?> == 0) &&
                    (<? echo $gym; ?> == 0
                ))
                    $('#message').append("<?php echo $txt['no_badgecase']; ?>")
                    $("#message").append(request[3])
                    $("#trainer_" + id).attr("src", "images/icons/pokeball_black.gif")
                }
                else {
                    $("#message").html("<? echo $aanval_log['trainer'] . ' ' . $txt['has_defeated_you_1']; ?>")
                    if (request[1] > 0) $("#message").append("<?php echo $txt['has_defeated_you_2']; ?> " + request[1] + " Silver. ")
                    $("#message").append("<?php echo $txt['has_defeated_you_3']; ?>")
                }
                $("#pokemon_text").hide()
                $("#trainer_naam").html("<? echo $aanval_log['trainer']; ?>.")
                //Set Images
                $("#img_pokemon").attr("src", "images/you/back/<? echo $gebruiker['character']; ?>.png")
                $("#img_trainer").attr("src", "images/trainers/<? echo $aanval_log['trainer']; ?>.png")

                setTimeout("location.href='index.php?page=<? echo $return_link; ?>'", 500)
            }
        )
            ;
        }

        //Change attack status
        function attack_status(msg) {
            request = msg.split(" | ")

            var time = 250
            if (request[7] < 25) time = 500
            else if (request[7] < 50) time = 500
            else if (request[7] < 100) time = 500
            else if (request[7] < 150) time = 500
            else if (request[7] < 200) time = 500
            else if (request[7] < 250) time = 500
            else if (request[7] >= 250) time = 500

            if (request[5] == 1) leven_verandering(request[2], request[4], request[3]);
            if (request[4] == 'pokemon') $("#leven").html(request[2]);
            attack_timer = setTimeout("attack_status_2('" + msg + "');", time)
        }

        function attack_status_2(msg) {
            clearTimeout(attack_timer)
            request = msg.split(" | ")

            $("#message").html(request[0])
            if (request[4] == "pokemon") {
                life_procent = Math.round((request[2] / request[3]) * 100)
                $("#" + request[8] + "_life").width(life_procent + '%')
                $("#" + request[8] + "_leven").html(request[2])
                $("#" + request[8] + "_leven_max").html(request[3])
                $("div[id='change_pokemon'][name='" + request[9] + "']").attr("title", "<? echo $pokemon_info['naam']; ?> \nLife:" + request[2] + "/" + request[3] + "");
                $("#leven").html(request[2]);
            }

            if (request[4] == "pokemon") {
                if (request[6] == 1) setTimeout("show_end_screen();", 500)
                else if (request[2] == 0) speler_wissel = 1
                else {
                    speler_attack = 1
                    speler_wissel = 1
                }
            }
            else if (request[4] == "computer") {
                speler_attack = 0
                speler_wissel = 0
                if (request[2] == 0) {
                    exp_change(request[10], request[11])
                    if (request[6] == 0) {
                        next_turn_timer = setTimeout('trainer_change()', 500)
                    }
                    else if (request[6] == 1) {
                        $("#trainer_" + request[8]).attr("src", "images/icons/pokeball_black.gif")
                        setTimeout("show_end_screen();", 500)
                    }
                }
                else if (request[1] == 1) next_turn()
            }
        }

        //Change Pokemon Function
        function change_pokemon_status(msg) {
            //Get php variables
            request = msg.split(" | ")
            //Send message
            $("#message").html(request[0])
            //Stop Life Change
            clearTimeout(timer);
            //Change was succesfull
            if (request[1] == 1) {
                //Change Pokemon in fight name, level and attacks
                $("#pokemon_naam").html(request[3])
                $("#pokemon_level").html(request[4])
                $("button:eq(1)").html(request[5])
                $("button:eq(2)").html(request[6])
                $("button:eq(3)").html(request[7])
                $("button:eq(4)").html(request[8])

                //set initial life
                $("#leven").html(request[10])
                $("#levenmax").html(request[11])
                //Create image for new pokemon in fight
                if (request[14] == 1) {
                    var map = "shiny"
                    $("#pokemon_star").show()
                }
                else {
                    var map = "pokemon"
                    $("#pokemon_star").hide()
                }
                $("#img_pokemon").attr("src", "images/" + map + "/back/" + request[15] + ".gif")
                //Show all pokemon in your hand
                $("div[id*='change_pokemon'][name*='1']").show()
                $("div[id*='change_pokemon'][name*='2']").show()
                $("div[id*='change_pokemon'][name*='3']").show()
                $("div[id*='change_pokemon'][name*='4']").show()
                $("div[id*='change_pokemon'][name*='5']").show()
                $("div[id*='change_pokemon'][name*='6']").show()
                //Hide the new pokemon that is in fight
                $("div[id*='change_pokemon'][name*='" + request[9] + "']").hide()
                //Change the HP Status from new pokemon in fight
                var pokemon_life_procent = Math.round((request[10] / request[11]) * 100)
                $("#pokemon_life").width(pokemon_life_procent + '%')
                //Change EXP Status from new pokemon in fight
                var exp_procent = Math.round((request[12] / request[13]) * 100)
                $("#pokemon_exp").width(exp_procent + '%')
                //Computer make next turn
                if (request[2] == 1) {
                    speler_attack = 0
                    speler_wissel = 0
                    next_turn()
                }
                else {
                    speler_attack = 1
                    speler_wissel = 1
                }
            }
        }

        //Use item function
        function use_item_status(msg) {
            //Get php variables
            request = msg.split(" | ")
            //Send message
            $("#message").html(request[0])
            //change amount of item
            var option = $("option[title=" + request[5] + "][name=" + request[3] + "]")
            //Set New Amount
            var amount = request[2]
            //If Amount is smaller than 1, amount -> 0
            if (request[2] < 1) {
                amount = 0;
                option.css({backgroundColor: "silver"})
            }
            //Change tekst
            option.html(option.val() + " (" + amount + ")")
            //It was a potion
            if (request[5] == "Potion") {
                //The pokemon in fight life has to change
                if (request[8] == 1) leven_verandering(request[6], 'pokemon', request[7])
                $("#leven").html(request[6])
                //Potion screen has to go away
                $("#potion_screen").hide()
                //Calculate new life for pokemon
                var green = Math.round(request[6] / request[7] * 100);
                //Set new life for potion screen
                $("#" + request[11] + "_green").width(green + 'px')
                $("#" + request[11] + "_red").width(100 - green + 'px')
                $("#" + request[11] + "_leven").html(request[6])
                //Change pokemon change field title
                $("div[id=change_pokemon][name=" + request[9] + "]").attr("title", request[10] + " \nLife: " + request[6] + "/" + request[7])
                //Computer make next turn
                if (request[1] == 1) {
                    speler_attack = 0
                    speler_wissel = 0
                    next_turn()
                }
            }
            else if (request[5] == "Pokeball") {
                speler_attack = 0
                speler_wissel = 0
                //Computer make next turn
                if (request[1] == 1) next_turn()
                //Attack finished
                else setTimeout("location.href='?page=<? echo $return_link; ?>'", 500)
            }
        }

        //Try To Run Function
        function attack_run_status(msg) {
            //Get php variables
            request = msg.split(" | ")
            //Send message
            $("#message").html(request[0])
            if (request[1] == 1) setTimeout("location.href='?page=<? echo $return_link; ?>'", 500)
            //Computer make next turn
            if (request[1] == 0) {
                speler_attack = 0
                speler_wissel = 0
                next_turn()
            }
        }

        function trainer_change_pokemon(msg) {
            request = msg.split(" | ")
            $("#message").html(request[0])
            $("#trainer_naam").html(request[1])
            $("#img_trainer").attr("src", "images/pokemon/" + request[6] + ".gif")
            var computer_life_procent = Math.round((request[3] / request[2]) * 100)
            $("#computer_life").width(computer_life_procent + '%')
            $("#trainer_" + request[5]).attr("src", "images/icons/pokeball_black.gif")
            $("#trainer_" + request[5]).attr("title", "Dead")
            if (request[4] == 1) next_turn()
            else {
                speler_attack = 1
                speler_wissel = 1
            }
        }

        //Make Computer Do Attack
        function next_turn() {
            clearTimeout(next_turn_timer)
            next_turn_timer = setTimeout('computer_attack()', 500)
        }

        //Player Can Do Stuff
        $(document).ready(function () {
            //Player Do Attack
            $("button[id='aanval']").click(function () {
                if (speler_attack == 1) {
                    if ($(this).html() != "") {
                        speler_attack = 0
                        $("#message").html($("#pokemon_naam").html() + " doet " + $(this).html() + ".")
                        $("#potion_screen").hide()
                        //alert($(this).html())
                        $.ajax({
                            type: "GET",
                            url: "attack/trainer/trainer-do_attack.php?attack_name=" + $(this).html() + "&wie=pokemon&aanval_log_id="+<? echo $aanval_log['id']; ?>+
                            "&sid="+Math.random(),
                            success: attack_status
                        });
                    }
                }
            });

            //Player Make Change Pokemon
            $("div[id='change_pokemon']").click(function () {
                if (speler_wissel == 1) {
                    if (($(this).attr("name") != "") && ($(this).attr("title")) != "Egg") {
                        $("#potion_screen").hide()
                        $.ajax({
                            type: "GET",
                            url: "attack/attack_change_pokemon.php?opzak_nummer=" + $(this).attr("name") + "&computer_info_name=<? echo $computer_info['naam']; ?>&aanval_log_id="+<? echo $aanval_log['id']; ?>+
                            "&sid="+Math.random(),
                            success: change_pokemon_status
                        });
                    }
                }
            });

            //Player Using Item
            $("button[id='use_item']").click(function () {
                if (speler_attack == 1) {
                    if ($('#item').val() == "Kies") $("#message").html("<?php echo $txt['no_item_selected']; ?>")
                    $("#item_name").html($('#item').val())
                    $("#message").html()
                    $("#potion_screen").show()
                }
            });

            //Player is Using Potion
            $("button[id='use_potion']").click(function () {
                if (speler_attack == 1) {
                    if ($("input[name='potion_pokemon_id']:checked").val() == undefined) $("#message").html("<?php echo $txt['potion_no_pokemon_selected']; ?>")
                    else {
                        $.ajax({
                            type: "GET",
                            url: "attack/attack_use_potion.php?item=" + $("#item_name").html() + "&computer_info_name=<? echo $computer_info['naam']; ?>&option_id=" + $('#item :selected').attr("name") + "&potion_pokemon_id=" + $("input[name='potion_pokemon_id']:checked").val() + "&aanval_log_id="+<? echo $aanval_log['id']; ?>+
                            "&sid="+Math.random(),
                            success: use_item_status
                        });
                        $("#potion_screen").hide()
                    }
                }
            });
        });

        //Computer Do Attack
        (function ($) {
            computer_attack = function () {
                if (speler_attack == 0) {
                    $("#message").html("<?php echo $txt['busy_with_attack']; ?>")
                    $("#potion_screen").hide()
                    $.ajax({
                        type: "GET",
                        url: "attack/trainer/trainer-do_attack.php?attack_name=undifined&wie=computer&aanval_log_id="+<? echo $aanval_log['id']; ?>+
                        "&sid="+Math.random(),
                        success: attack_status
                    });
                }
            };
        })(jQuery);

        //Computer Change Pokemon
        (function ($) {
            trainer_change = function () {
                if (speler_attack == 0) {
                    $("#potion_screen").hide()
                    $.ajax({
                        type: "GET",
                        url: "attack/trainer/trainer-change-pokemon.php?pokemon_info_name=<? echo $pokemon_info['naam']; ?>&computer_info_name=<? echo $computer_info['naam']; ?>&aanval_log_id="+<? echo $aanval_log['id']; ?>+
                        "&sid="+Math.random(),
                        success: trainer_change_pokemon
                    });
                }
            };
        })(jQuery);

    </script>

    <?
    if ($gebruiker['battleScreen']) {
        $battleScreen = "battlearea";
        $battleShadow = 'text-shadow:1px 1px 1px #000;';
    } else {
        $battleScreen = "battleareaoff";
        $battleShadow = '';
    }
    ?>
    <center>
        <table class="<?= $battleScreen ?> trainer">
            <tr>
                <td>
                    <div style="padding:0px 0 5px 0px;">
                        <div class="new_bar2">
                            <div style="padding: 15px 0 0 120px;">
                                <img src="../images/battlescreen/lvl.png" style="padding:0 0 0 30px;">
                                <font size="3" style="<?= $battleShadow ?>">
                                    <strong><?= $computer_info['level'] ?></strong> </font>
                            </div>
                            <div style="padding:0px 0 0 80px;">
                                <div class="hp_red">
                                    <div class="hp_progress" id="computer_life" style="width: <?php echo $computer_life_procent; ?>%"></div>
                                </div>
                            </div>
                            <div align="left" style="padding: 12px 0px 0px 10px;">
                                <font style="<?= $battleShadow ?>" size="3"><strong id="trainer_naam"><?= $computer_info['naam_goed'] ?> </strong></font><br/>
                                <?
                                $trainer_pok = mysql_query("SELECT `id`, `leven` FROM `pokemon_wild_gevecht` WHERE `aanval_log_id`='" . $aanval_log['id'] . "' ORDER BY `id`");
                                while ($trainer_pokemon = mysql_fetch_array($trainer_pok)) {
                                    if ($trainer_pokemon['leven'] > 0) echo '<img id="trainer_' . $trainer_pokemon['id'] . '" src="./images/icons/pokeball.gif" width="14" height="14" alt="Alive" title="Alive" />';
                                    else echo '<img id="trainer_' . $trainer_pokemon['id'] . '" src="./images/icons/pokeball_black.gif" width="14" height="14" "Dead" title="Dead" />';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </td>
                <td>
                    <img id="img_trainer" src="images/<? echo $computer_info['map'] . "/" . $computer_info['wild_id']; ?>.gif" style="position:relative;top:110px;left:205px;"/>
                </td>
            </tr>
            <tr>
                <td>
                    <img id="img_pokemon" src="images/<? echo $pokemon_info['map']; ?>/back/<? echo $pokemon_info['wild_id']; ?>.gif" style="position:relative;top:50px;left:155px;"/>
                </td>
                <td>
                    <div style="padding:100px 0 0 130px;">
                        <div class="new_bar">
                            <div style="padding:16px 0 0px 10px;"><strong> <font size="3" style="<?= $battleShadow ?>">
                                        <span id="pokemon_naam" style="float:left;"><?= $pokemon_info['naam_goed'] ?> </span></strong></font>
                                <span id="pokemon_star" style="display:none;"></span>
                            </div>
                            <font size="3" style="<?= $battleShadow ?>"> <strong>
                                    <i><img src="../images/battlescreen/lvl.png" style="padding:0 0 0 30px;">
                                        <span id="pokemon_level" style="padding:0px 0 0px 5px;"><?= $pokemon_info['level'] ?></span>
                                    </i> </strong> </font>
                            <div style="position:relative;top:-3px;left: 110px;">
                                <div class="hp_red">
                                    <div class="hp_progress" id="pokemon_life"
                                         style="width: <?php echo $pokemon_life_procent; ?>%"></div>
                                </div>
                            </div>
                            <div style="position:relative;bottom:5px;color:#fff;float:right;right:55px;font:italic bold 13px Arial,Helvetica,sans-serif;">
                                <span id="leven"><?= $pokemon_info['leven'] ?></span> /
                                <span id="levenmax"><?= $pokemon_info['levenmax'] ?></span>
                            </div>
                            <div style="position:relative;bottom:2px;left:68px;">
                                <div class="exp_blue">
                                    <div class="exp_progress" id="pokemon_exp"
                                         style="width: <?php echo $pokemon_exp_procent; ?>%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
        <table cellpadding=0 cellspacing=0 width="660">
            <tr>
                <td colspan=4>
                    <HR>
                </td>
            </tr>
            <tr>
                <td colspan=4 style="height:50px;">
                    <div id="message" align="center"></div>
                <td>
            </tr>
            <tr>
                <td colspan=4>
                    <HR>
                </td>
            </tr>
        </table>
        <span id="potion_screen" style="display:none;">
      <table cellpadding=0 cellspacing=0 width=450 border=0>   
        <tr>
          <td colspan=4>
            <center>
              <b><?php echo $txt['potion_text']; ?> <span id="item_name"></span>?</b>
            </center>
            <br/>
          </td>
        </tr>
        <tr> 
          <td width=50><strong><center><?php echo $txt['*']; ?></center></strong></td>
          <td width=70><strong><center><?php echo $txt['pokemon']; ?></center></strong></td>
          <td width=80><strong><center><?php echo $txt['level']; ?></center></strong></td>
          <td width=250><strong><?php echo $txt['health']; ?></strong></td>
        </tr>
          <?

          //Show all pokemon inhand
          while ($player_hand = mysql_fetch_assoc($pokemon_sql)) {
              //Load Right info for the pokemon in hand
              $player_hand_good = pokemonei($player_hand);
              echo '<tr>';
              //Als pokemon geen baby is
              if ($player_hand_good['baby'] != "Ja") echo '<td><center><input type="radio" name="potion_pokemon_id" value="' . $player_hand['id'] . '"/></center></td>';
              else echo '<td><center><input type="radio" id="niet" name="niet" disabled/></center></td>';

              echo '<td><center><img src="' . $player_hand_good['animatie'] . '" width=32 height=32></center></td>';
              echo '<td><center>' . $player_hand_good['level'] . '</center></td>';
              //Als pokemon geen baby is
              if ($player_hand_good['baby'] != "Ja") {
                  echo '<td>
  				    <div class="bar_red">
      				  <div class="progress" id="' . $player_hand['id'] . '_life" style="width: ' . $player_hand_good['levenprocent'] . '%"></div>
      			  </div></td>';
              } else echo "<td>" . $txt['potion_egg_text'] . "</td>";
              echo "</tr>";
          }
          //Set Player hand query counter on 0
          mysql_data_seek($pokemon_sql, 0);
          ?>
          <tr>
          <td colspan=4><button id="use_potion" class="button_mini" style="min-width: 75px;"><?php echo $txt['button_potion']; ?></button></td>
        </tr>
      </table>
      <table width="600">
        <tr>
          <td><HR></td>
        </tr>
      </table>
    </span>
        <table cellpadding=0 cellspacing=0 width="660">
            <tr>
                <td width=284>
                    <style>
                        #aanval {
                            width: 10em;
                            height: 3em;
                        }

                        #use_item {
                            width: 10em;
                            height: 3em;
                        }
                    </style>

                    <table cellpadding=0 cellspacing=0 width=284 border=0>
                        <tr>
                            <td colspan=2 height=20><strong><?php echo $txt['attack']; ?></strong></td>
                        </tr>
                        <tr>
                            <td width=140>
                                <button id="aanval" class="button" style="min-width: 70px;"><? echo $pokemon_info['aanval_1']; ?></button>
                            </td>
                            <td width=144>
                                <button id="aanval" class="button" style="margin-left:4px;min-width: 70px;"><? echo $pokemon_info['aanval_2']; ?></button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <button id="aanval" class="button" style="min-width: 70px;"><? echo $pokemon_info['aanval_3']; ?></button>
                            </td>
                            <td>
                                <button id="aanval" class="button" style="margin-left:4px;min-width: 70px;"><? echo $pokemon_info['aanval_4']; ?></button>
                            </td>
                        </tr>
                    </table>
                </td>
                <td width=176>
                    <table width=176 cellpadding=0 cellspacing=0 border=0>
                        <tr>
                            <td>
                                <div style="padding: 0px 8px 0px 8px;"><strong><?php echo $txt['change']; ?></strong>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td height=54>
                                <div style="padding: 0px 8px 0px 8px;">
                                    <div id="change_pokemon" name="1" title="" style="display:none; background-image: url(); float: left; height: 32px; width: 32px; border: 0px; cursor: pointer;"/>
                                </div>
                                <div id="change_pokemon" name="2" title="" style="display:none; background-image: url(); float: left; height: 32px; width: 32px; border: 0px; cursor: pointer;"/>
                                </div>
                                <div id="change_pokemon" name="3" title="" style="display:none; background-image: url(); float: left; height: 32px; width: 32px; border: 0px; cursor: pointer;"/>
                                </div>
                                <div id="change_pokemon" name="4" title="" style="display:none; background-image: url(); float: left; height: 32px; width: 32px; border: 0px; cursor: pointer;"/>
                                </div>
                                <div id="change_pokemon" name="5" title="" style="display:none; background-image: url(); float: left; height: 32px; width: 32px; border: 0px; cursor: pointer;"/>
                                </div>
                                <div id="change_pokemon" name="6" title="" style="display:none; background-image: url(); float: left; height: 32px; width: 32px; border: 0px; cursor: pointer;"/>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
                <td width=140>
                    <table width=140 cellpadding=0 cellspacing=0 border=0>
                        <tr>
                            <td height=20><strong><?php echo $txt['items']; ?></strong></td>
                        </tr>
                        <tr>
                            <td><?php
                                //Items laden
                                $sql2 = mysql_query("SELECT `naam`, `wat` FROM `items` WHERE `wat`='potion'");

                                //Als eerste Kies weergeven
                                $itemm[0] = "Kies";
                                $item2[0] = "Kies";

                                //Items opbouwen.
                                for ($i = 1; $items = mysql_fetch_array($sql2); $i++) {
                                    //Makkelijk naam toewijzen
                                    $naamm = $items['naam'];
                                    //Als de gebruiker er wel 1 van heeft dan weergeven,
                                    if ($gebruiker[$naamm] > 0) {
                                        $itemm[$i] = $naamm . " (" . $gebruiker[$naamm] . ")";
                                        if ($naamm == "Bike") $itemm[$i] = $naamm;
                                        $item2[$i] = $naamm;
                                        $style[$i] = "white";
                                    } else {
                                        $itemm[$i] = $naamm . "(0)";
                                        $item2[$i] = $naamm;
                                        $style[$i] = "silver";
                                        $disabled[$i] = "disabled";
                                    }
                                    $title[$i] = ucfirst($items['wat']);
                                }

                                //Laten zien in keuze lijst.
                                echo '<select id="item" class="text_select" onChange="if(this.options[this.selectedIndex].state==\'disabled\') this.selectedIndex=0">';
                                for ($i = 0; $i < sizeof($item2); $i++) {
                                    echo '<option title="' . $title[$i] . '" value="' . $item2[$i] . '" name=' . $i . ' style="background-color:' . $style[$i] . ';" state=' . $disabled[$i] . ' class="balk_zwart">' . $itemm[$i] . '</option>';
                                }
                                echo ' </select>';
                                ?></td>
                        <tr>
                            <td>
                                <button id="use_item" class="button"><?php echo $txt['button_item']; ?></button>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </center>
    <?php
}
?>