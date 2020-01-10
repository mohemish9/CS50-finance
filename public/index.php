<?php
    require("../includes/config.php");
    #gets all the shares the belong to current user
    $rows = CS50::query("SELECT * FROM portfolios WHERE user_id = ?", $_SESSION["id"]);
    
    $positions = [];
    foreach ($rows as $row)
    {
        $stock = lookup($row["symbol"]);
        if ($stock !== false)
        {
            $p[] = [
                "name" => $stock["name"],
                "price" => $stock[ "price"],
                "symbol" => $row["symbol"],
                
                "shares" => $row["shares" ]
                
            
            ];
       }
    }

    $rows = CS50::query("SELECT cash FROM users WHE RE id = ?", $_SESSION["id"]);
    render("portfolio.php", ["positions" => $p, "cash" => $rows[0]["cash"], "title" => "Portfolio"]);
?>
