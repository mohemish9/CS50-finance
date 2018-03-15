<?php


    require("../includes/config.php"); 

  
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {

        render("deposit_form.php", ["title" => "Deposit"]);
    }

 
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
 
        if (empty($_POST["amount"]))
        {
            apologize("You must provide amount");
        }

        CS50::query("UPDATE users SET cash = cash + ? WHERE id = ?", $_POST["amount"], $_SESSION["id"]);

        redirect("/");
    }

?>
