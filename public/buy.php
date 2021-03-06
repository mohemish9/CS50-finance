<?php   
    require("../includes/config.php"); 

    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
       
        render("buy_form.php", ["title" => "Buy"]);
    }    
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {   
        if (empty( $_POST["symbol"] ))
        {
           apologize("symbol needed");
        }
        else if(empty($_POST["shares"]))
        {
            apologize("You must provide a number of shares.");
        }
        else if($_POST["shares"] < 1)
        {
            apologize("You must provide a positive number of shares.");
        }

        
        $_POST["shares"] = intval($_POST["shares"]);
        $_POST["symbol"] = strtoupper($_POST["symbol"]);
        $stock = lookup($_POST["symbol"]);
        

        if($stock == false)
        {
            apologize("Invalid symbol.");
        }

        $user = CS50::query("SELECT cash FROM users WHERE id = ?", $_SESSION["id"]);
        $new_cash = $stock["price"] * $_POST["shares"];
        if($user[0]["cash"] < $new_cash)
        {
            apologize("You don't have enought cash to buy this shares.");
        }

        # adds new shares into the user's account 
        CS50::query("INSERT INTO portfolios (user_id, symbol, shares) VALUES(?, ?, ?) ON DUPLICATE KEY UPDATE shares = shares + VALUES(shares)", $_SESSION["id"], $_POST["symbol"], $_POST["shares"]);
        # deducts the cash from the user's balance
        CS50::query("UPDATE users SET cash = cash - ? WHERE id = ?", $new_cash, $_SESSION["id"]);
        # adds this transation to the log
        CS50::query("INSERT INTO log (user_id, transaction, time, symbol, shares, price) VALUES(?, ?, ?, ?, ?, ?)", $_SESSION["id"], true, date("Y-m-d H:i:s"), $_POST["symbol"], $_POST["shares"], $stock["price"]);
        redirect("/");
    }
?>
