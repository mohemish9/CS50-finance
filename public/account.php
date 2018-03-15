<?php

    
    require("../includes/config.php"); 

    
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {

        $user = CS50::query("SELECT * FROM users WHERE id = ?", $_SESSION["id"]);

        render("account_form.php", ["user" => $user[0], "title" => "Setting"]);
    }

    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {

        $user = CS50::query("SELECT * FROM users WHERE id = ?", $_SESSION["id"]);
        $user = $user[0];
        

        if (!password_verify($_POST["opassword"], $user["hash"]))
        {
            apologize("Wrong old password.");
        }
        

        if (!empty($_POST["username"]) && $_POST["username"] !== $user["username"])
        {
            CS50::query("UPDATE users SET username = ? WHERE id = ?", $_POST["username"], $_SESSION["id"]);
        }
        if (!empty($_POST["password"]) && !empty($_POST["confirmation"]) &&
                 $_POST["password"] == $_POST["confirmation"])
        {
            CS50::query("UPDATE users SET hash = ? WHERE id = ?", password_hash($_POST["password"], PASSWORD_DEFAULT), $_SESSION["id"]);
        }

        redirect("/");
    }

?>
