<?php
    require("../includes/config.php"); 

    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        #stores all transactions in the log that belongs to the current user in vairable history
        $history = CS50::query("SELECT * FROM log WHERE user_id = ?", $_SESSION["id"]);
        
        if(count($history) == 0) {
            apologize("There is no history for you!");
        }
        render("history.php", ["history" => $history, "title" => "Portfolio"]);
    }
    else
    {
        apologize("You can't access this page.");
    }

?>
