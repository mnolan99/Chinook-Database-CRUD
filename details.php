<!DOCTYPE html>
<html lang="en-gb">
    <head>
        <title>Dynamic Web</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="styles.css">
    </head>

    <body>
        <?php
        // Connect to database
        include('databaseConnect.php');

        echo"<div class=\"header\">";
        echo "<button id=\"homeButton\" onclick=\"window.location.href = 'index.php'\">Home</button><h1>Chinook Music Store</h1>";
        echo"</div>";

        // Get ID of selected album row from URL
        $albumID = $_GET['id'];

        // Query the database to get the tracks relating to the selected album
        $sql = "SELECT * FROM tracks INNER JOIN albums ON tracks.AlbumId = albums.AlbumId WHERE tracks.AlbumId=$albumID ";
        $result = $conn->query($sql);
        $first = true;

        // Display the album title once then display the tracklist in an ordered list
        while ($row = $result->fetch_assoc()) {
            if ($first === true) {
                echo "<h2>" . $row['Title'] . "</h2>";
                $first = false;
                echo "<p> Tracklist: </p>";
                echo "<ol>";
            }
            echo "<li>"
            . $row['Name']
            . "</li>";
        }
        echo "</ol>";

        // Run the SQL query again to get the album ID and display a button linking to the update page related to that album
        $result1 = $conn->query($sql);
        $first1 = true;
        while ($row = $result1->fetch_assoc()) {
            if ($first1 === true) {
                echo "<button id=\"updateButton\" onclick=\"window.location.href = 'update.php?id={$row['AlbumId']}'\">Update</button>";
                $first1 = false;
            }
        }
        ?>
    </body>
</html>