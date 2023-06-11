<?
if(isset($_POST['accept'])){
//accept friend request
    if (empty($_POST['id']) or empty($_POST['requester'])) {
        
        echo showAlert("red", 'Selecteer een verzoek.'); refresh(3, "?page=buddies");

    }else{
        $request_id = $_POST['id'];
        $friendrequester = $_POST['requester'];
        
        $N = count($request_id);
        for($i=0; $i < $N; $i++)
        {
            $event = $_SESSION['naam'].' heeft je buddy verzoek geaccepteerd.';
            
            $result = $db->prepare("UPDATE `friends` SET `status`='1' WHERE `id`=:upid AND `to`=:to;
                                      INSERT INTO gebeurtenis (datum, ontvanger_id, bericht, gelezen)
                                      VALUES (NOW(), :requester, :event, '0')");
            $result->bindParam(':upid', $request_id[$i], PDO::PARAM_INT);
            $result->bindValue(':to', $_SESSION['id'], PDO::PARAM_STR);
            $result->bindValue(':requester', $friendrequester, PDO::PARAM_STR);
            $result->bindValue(':event', $event, PDO::PARAM_STR);
            $result = $result->execute();
        }
        
        echo showAlert("green", "Je hebt het buddy verzoek geaccepteerd."); refresh(3, "?page=buddies");
        
    }
}
if(isset($_POST['deny'])){
    //deny friend request
    if (empty($_POST['id']) or empty($_POST['requester'])) {
        
        echo showAlert("red", "Selecteer een verzoek."); refresh(3, "?page=buddies");
        
    }else{
        $request_id = $_POST['id'];
        $friendrequester = $_POST['requester'];
        
        $N = count($request_id);
        for($i=0; $i < $N; $i++)
        {
        
            $event = $_SESSION['naam'].' heeft je buddy verzoek afgewezen.';
            
            $result = $db->prepare("DELETE FROM `friends` WHERE `id`=:upid AND `to`=:to;
                                  INSERT INTO gebeurtenis (datum, ontvanger_id, bericht, gelezen)
                                  VALUES (NOW(), :requester, :event, '0')");
            $result->bindParam(':upid', $request_id[$i], PDO::PARAM_INT);
            $result->bindValue(':to', $_SESSION['id'], PDO::PARAM_STR);
            $result->bindValue(':requester', $friendrequester, PDO::PARAM_STR);
            $result->bindValue(':event', $event, PDO::PARAM_STR);
            $result = $result->execute();
            
        }
        
        echo showAlert("blue", "Je hebt het buddy verzoek afgewezen."); refresh(3, "?page=buddies");
        
    }
}

if(isset($_POST['setfriend'])) {
    //addfriend
    if (isset($_POST['add'])) {
        if (empty($_POST['add'])) {
            
            echo showAlert("red","Selecteer een buddy."); refresh(3, "?page=buddies");
        
        } else {


            $addFriend = getUserID($_POST['add']);

            //fetch friendrequest data
            $checkFriendRequests = $db->prepare("SELECT * FROM `friends` WHERE (`from` = :requester AND `to` = :request_to) OR  (`from` = :request_to AND `to` = :requester)");
            $checkFriendRequests->execute(array(
                ":requester" => $_SESSION['id'],
                ":request_to" => $addFriend,
            ));
            $checkFriendRequestsResult = $checkFriendRequests->fetch();

            if ($checkFriendRequestsResult) {

                echo showAlert("red","Je buddy verzoek is al verstuurd."); refresh(4, "?page=buddies");
        
            } else {
                
                $event = '<img src="images/icons/blue.png" width="16" height="16" class="imglower"> '.$_SESSION['naam'].' heeft je een buddy verzoek verstuurd. Ga naar <a href="?page=buddies">buddies</a> om hem te accepteren of af te wijzen.';
                
                if($addFriend) {

                    $q = "INSERT INTO `friends`(`from`,`to`,`date`) values(:requester,:request_to,NOW());
                          INSERT INTO gebeurtenis (datum, ontvanger_id, bericht, gelezen)
                          VALUES (NOW(), :request_to, :event, '0')";
                    $st = $db->prepare($q);
                    $start = $st->execute(array(
                        ":requester" => $_SESSION['id'],
                        ":request_to" => $addFriend,
                        ":event" => $event,
                    ));
                    
                    echo showAlert("green", "Je buddy verzoek is verzonden."); refresh(4, "?page=buddies");
                    
                }else{
                
                    echo showAlert("red", "De gekozen gebruiker bestaat niet, probeer het nog eens."); refresh(4, "?page=buddies");
                
                }
            }
        }
    }
}

if(isset($_POST['remove'])){
    //deny friend request

    if (empty($_POST['id'])) {
        
        echo showAlert("info","Please select a request."); refresh(4, "?page=buddies");
        
    }else{
        $request_id = $_POST['id'];

        $N = count($request_id);
        for($i=0; $i < $N; $i++)
        {

            //fetch friendrequest data
            $checkFriendRequests = $db->prepare("SELECT * FROM `friends` WHERE `id` = :request_id");
            $checkFriendRequests->execute(array(
                ":request_id" => $request_id[$i],
            ));
            $checkFriendRequestsResult = $checkFriendRequests->fetch();
            
            if($checkFriendRequestsResult['from'] == $_SESSION['id'] OR $checkFriendRequestsResult['to'] == $_SESSION['id']) {
                if ($checkFriendRequestsResult['from'] == $_SESSION['id']) {
                    $messageReceiver = $checkFriendRequestsResult['to'];
                } else {
                    $messageReceiver = $checkFriendRequestsResult['from'];
                }

                $event = $_SESSION['naam'].' heeft je vriendschap verbroken.';
                
                $result = $db->prepare("DELETE FROM `friends` WHERE `id`=:upid AND `from`=:fromuser OR `to`=:fromuser;
                          INSERT INTO gebeurtenis (datum, ontvanger_id, bericht, gelezen)
                          VALUES (NOW(), :touser, :event, '0')");
                $result->bindParam(':upid', $request_id[$i], PDO::PARAM_INT);
                $result->bindValue(':fromuser', $_SESSION['id'], PDO::PARAM_STR);
                $result->bindValue(':touser', $messageReceiver, PDO::PARAM_STR);
                $result->bindValue(':event', $event, PDO::PARAM_STR);
                $result->execute();
            }else{
            
            echo showAlert("blue","Selecteer een verzoek."); refresh(4, "?page=buddies");

            }

        }
        
        echo showAlert("blue","Je hebt de buddy verwijderd."); refresh(4, "?page=buddies");
        
    }
}
?>


<h3>Buddies toevoegen</h3>
<table width="100%">
    <tr>
        <td>
        <form role="form" method="post">

        <input class="text_long" type="text" name="add" value="<?if(isset($_GET['buddy'])){echo $_GET['buddy'];}?>">

        <button type="submit" name="setfriend" class="button pull-right">Toevoegen</button>
        </form>
        </td>
    </tr>
</table>

<h3>Openstaande verzoeken</h3>

<table width="100%">
    <thead>
    <tr>
        <th>#</th>
        <th>Buddy</th>
        <th>Aangevraagd</th>
    </tr>
    </thead>

    <tbody>
    <form method="post" name="open">
    <?
    $q = "SELECT * FROM `friends` WHERE `to` = '{$_SESSION['id']}' AND `status` = 0";
    $st = $db->prepare($q);

    if ($st->execute()) {//if this returns true, the query was successful

        //loop through the table
        while ($friends = $st->fetch(PDO::FETCH_ASSOC)) {

            echo '<tr>';
            echo '<td><input type="checkbox" id="' . $friends['id'] . '" name="id[]" value="' . $friends["id"] . '"/><input type="hidden" value="' . $friends['from'] . '" name="requester" id="'.$friends["id"].'" ></td>';
            echo '<td><label for="'.$friends["id"].'">' . getUsername($friends['from']) . '</label></td>';
            echo '<td><label for="'.$friends["id"].'">' . $friends['date'] . '</label></td>';
            echo '</tr>';
        }
    }
    ?>
        <tr>
            <td colspan="3" align="right"><br/>
                <button class="button" type="submit" name="accept">Accepteren</button>
                <button class="button" type="submit" name="deny">Afwijzen</button>
            </td>
        </tr>
    </form>
    </tbody>
</table>

<h3>Mijn buddies</h3>
    
<table width="100%">
    <thead>
    <tr>
        <th>#</th>
        <th>Buddy</th>
        <th>Bevriend sinds</th>
    </tr>
    </thead>

    <tbody>
    <form method="post" name="current">
    <?
        $q = "SELECT *
        FROM gebruikers G, friends F
        WHERE
        CASE

        WHEN F.to = '{$_SESSION['id']}'
        THEN F.from = G.user_id
        WHEN F.from = '{$_SESSION['id']}'
        THEN F.to = G.user_id
        END

        AND
        F.status='1'";
        $st = $db->prepare($q);

        if ($st->execute()) {//if this returns true, the query was successful

            //loop through the query
            while ($friends = $st->fetch(PDO::FETCH_ASSOC)) {
                echo '<tr>';
                echo '<td><input type="checkbox" id="' . $friends['id'] . '" name="id[]" value="' . $friends["id"] . '"/></td>';
                echo '<td><label for="'.$friends["id"].'">' . $friends['username'] . '</label></td>';
                echo '<td><label for="'.$friends["id"].'">' . $friends['date'] . '</label></td>';
                echo '</tr>';
            }
        }
    ?>
    <tr>
        <td colspan="3" align="right">
            <button class="button" type="submit" name="remove">Verwijder buddy</button>
        </td>
    </tr>
    </form>
    </tbody>
</table>