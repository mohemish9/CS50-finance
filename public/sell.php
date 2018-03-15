<?php


    require("../includes/config.php"); 


    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {

        $symbols = CS50::query("SELECT symbol FROM portfolios WHERE user_id = ?", $_SESSION["id"]);
        

        if(count($symbols) == 0)
        {
            apologize("Nothing to sell.");
        }
        

        render("sell_form.php", ["symbols" => $symbols, "title" => "Sell"]);
    }

 
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
 
        if (empty($_POST["symbol"]))
        {
            apologize("You must choose a symbol.");
        }

        
        $symbol = CS50::query("SELECT * FROM portfolios WHERE symbol = ? AND user_id = ?", $_POST["symbol"], $_SESSION["id"]);

       
        if (count($symbol) == 1)
        {
            
            $stock = lookup($_POST["symbol"]);
            
            
            $new_cash = $symbol[0]["shares"] * $stock["price"];

            
            CS50::query("DELETE FROM portfolios WHERE user_id = ? AND symbol = ?", $_SESSION["id"], $_POST["symbol"]);
            
            
            CS50::query("UPDATE users SET cash = cash + ? WHERE id = ?", $new_cash, $_SESSION["id"]);
            
            
            CS50::query("INSERT INTO history (user_id, transaction, timestamp, symbol, shares, price) VALUES(?, ?, ?, ?, ?, ?)", $_SESSION["id"], false, date("Y-m-d H:i:s"), $_POST["symbol"], $symbol[0]["shares"], $stock["price"]);
            
            redirect("/");
        }
        else
        {
            
            apologize("Invalid symbol.");
        }
    }

?>
