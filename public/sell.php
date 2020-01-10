<?php
    require("../includes/config.php"); 
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        # stores all the symbols of shares the user own in a varialbe
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

        # stores all the current user's transations that had the stock they are trying to sell 
        $symbol = CS50::query("SELECT * FROM portfolios WHERE symbol = ? AND user_id = ?", $_POST["symbol"], $_SESSION["id"]);

       
        if (count($symbol) == 1)
        {
            
            $stock = lookup($_POST["symbol"]);
            
            
            $new_cash = $symbol[0]["shares"] * $stock["price"];
            # removes the shares being sold from the user's account
            CS50::query("DELETE FROM portfolios WHERE user_id = ? AND symbol = ?", $_SESSION["id"], $_POST["symbol"]);
            # adds cash to user's balance in user table
            CS50::query("UPDATE users SET cash = cash + ? WHERE id = ?", $new_cash, $_SESSION["id"]);
            # adds transaction into the log
            CS50::query("INSERT INTO log (user_id, transaction, times, symbol, shares, price) VALUES(?, ?, ?, ?, ?, ?)", $_SESSION["id"], false, date("Y-m-d H:i:s"), $_POST["symbol"], $symbol[0]["shares"], $stock["price"]);   
            redirect("/");
        }
        else
        {       
            apologize("Invalid symbol.");
        }
    }
?>
