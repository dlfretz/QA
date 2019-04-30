<?php 
    include "../view/header.php";
    include "../model/database.php";
    include "newRosterOrAttendee.php";
    $_POST = array();?>

<!-- PHP for populating roster data -->
<?php 
    $user_id = $_SESSION['id'];
    $MYSQLi = new mysqli(HOST, USER, PASSWORD, DATABASE);
    if ($MYSQLi->connect_errno) {
        printf("Unable to connect to SQL database. Error #%d", $MYSQLi->connect_error);
        die('Failed To Connect, Terminating Script');
    }
    
    $roster_array = array();
    $roster_list = array();
    /*QUERY for filled rosters*/
    if ($statement = $MYSQLi->prepare('SELECT rosters.roster_name, attendees.attendee_id FROM attendees join rosters 
                                        WHERE rosters.roster_host_id = ? 
                                        AND attendees.roster_id = rosters.roster_id
                                        ORDER BY roster_name'))
    {
        $statement->bind_param('s', $user_id);
        $statement->execute();
        if($statement->errno){
            printf($statement->error);
        }
        $result = $statement->get_result();
    
        while($r=$result->fetch_assoc()) {
            if (array_key_exists($r['roster_name'], $roster_array)) {
                $roster_array[$r['roster_name']] .= $r['attendee_id']."<br>";
            }
            else {
                $roster_array[$r['roster_name']] = $r['attendee_id']."<br>";
                $roster_list[] = $r['roster_name'];
            }
        }
        $statement->close(); 
        $result->free();
    }
    /*QUERY for empty rosters*/
    if ($statement = $MYSQLi->prepare('SELECT rosters.roster_name FROM rosters 
                                        WHERE rosters.attendee_count= 0
                                        AND rosters.roster_host_id = ?')) 
    {
        $statement->bind_param('s', $user_id);
        $statement->execute();
        $result = $statement->get_result();
        
        
        while($r=$result->fetch_assoc()) {
            if (!array_key_exists($r['roster_name'], $roster_array)) {
                $roster_list[] = $r['roster_name'];
            }
        } 
    }
    ?>
<!-- end of roster list population code -->


<!-- MAIN PAGE SECTION -->
<h2 style="color:white;margin-left:15px">Add or Modify Rosters</h2>
    <div class="roster_col" >
        <span id="rcol">
            <h3 style="color:#0078AD">Rosters</h3><br>
            <?php foreach($roster_list as $roster) {
                echo('<span class="roster_text" id="rost_');
                echo($roster);
                echo('" onclick="displayRoster(this)">');
                echo($roster);
                echo('</span>');
            }
            ?>
        <form action="#" method="POST">
            <input type="text" placeholder="New Roster?" name="roster_name">
            <input type="submit" value="addRoster" name="addRoster">
        </form>
        </span>
    </div>
    <div class="attendee_col">
        <h3>Attendee List</h3><br>
        <?php foreach($roster_array as $key => $value) {
            echo('<span style="display:none" class="attendee_text" id="att_');
            echo($key);
            echo('">');
            echo($value);
            echo('</span>'); 
        } ?>
        <form action="#" method="POST">
            <input type="hidden"name="selected_roster" id="hidden_selected_roster"value = "">
            <input type="text" name="new_attendee_id">
            <input type="submit" value="addAttendee" name="addAttendee">
        </form> 
    </div>


<!-- displayRoster() is used to populate the list of students who are members of the selected roster -->
<script>
    var $currently_displayed_roster;
    function displayRoster(element) {
        if($currently_displayed_roster) {
            $currently_displayed_roster.style.display="none";
        }
        var $selected_roster = element.innerHTML;
        $currently_displayed_roster = document.getElementById("att_" + $selected_roster)
        $currently_displayed_roster.style.display="block";
        document.getElementById("hidden_selected_roster").value = $selected_roster;
    }
</script>


<?php include "../view/footer.php" ?>