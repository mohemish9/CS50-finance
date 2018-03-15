<?php


    require("../includes/config.php"); 


    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {

        render("quote_form.php", ["title" => "Quote"]);
    }


    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {

        if (empty($_POST["symbol"]))
        {
            apologize("You must provide a symbol.");
        }
        

        $stock = lookup($_POST["symbol"]);

        if($stock == false)
        {
            apologize("You are looking up for unknown symbol.");
        }
        else
        {

            render("quote.php", ["name" => $stock["name"], "symbol" => $stock["symbol"], "price" => $stock["price"]]);
        }
    }

?>
