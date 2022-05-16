<!DOCTYPE html>
<html lang="en-gb">
    <head>
        <title>Dynamic Web Coursework Project - B00419772</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>

    <body>
        <?php
        // This is used to connect to the database once so 
        // that the other pages can include this page and query the database
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "chinook";
        $conn = new mysqli($servername, $username, $password, $dbname);
        ?>
    </body>
</html>