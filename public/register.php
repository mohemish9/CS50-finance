<?php

 
    require("../includes/config.php");

    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {

        render("register_form.php", ["title" => "Register"]);
    }


    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
   
        if (empty($_POST["username"]))
        {
            apologize("You must provide your username.");
        }
        else if (empty($_POST["password"]))
        {
            apologize("You must provide your password.");
        }
        else if (empty($_POST["confirmation"]))
        {
            apologize("You must provide your password confirmation.");
        }
        else if ($_POST["password"] !== $_POST["confirmation"])
        {
            apologize("You must provide same passwords.");
        }
        

        $rows = CS50::query("INSERT IGNORE INTO users (username, hash, cash) VALUES(?, ?, 10000.0000)", $_POST["username"], password_hash($_POST["password"], PASSWORD_DEFAULT));
        
        if($rows == false)
        {
            apologize("You must change your username because its used before.");
        }
        else
        {

            $rows = CS50::query("SELECT LAST_INSERT_ID() AS id");
            

            $_SESSION["id"] = $rows[0]["id"];
            

            redirect("/");
        }
    }

?>