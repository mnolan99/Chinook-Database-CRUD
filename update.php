<!DOCTYPE html>
<html lang="en-gb">
    <head>
        <title>Dynamic Web</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <link rel="stylesheet" href="styles.css">
    </head>

    <body>
        <?php
        // Connect to the database
        include('databaseConnect.php');

        echo"<div class=\"header\">";
        echo "<button id=\"homeButton\" onclick=\"window.location.href = 'index.php'\">Home</button><h1>Chinook Music Store</h1>";
        echo"</div>";

        // Get ID of selected album row from URL
        $albumID = $_GET['id'];
        $count = 0;

        // Query the database to get the tracks from the selected album
        $sql = "SELECT * FROM tracks INNER JOIN albums ON tracks.AlbumId = albums.AlbumId WHERE tracks.AlbumId=$albumID ";
        $result = $conn->query($sql);
        $result2 = $conn->query($sql);

        // Query the database to get the artist's name from the selected album
        $sqlArtistName = "SELECT * FROM albums INNER JOIN artists ON albums.ArtistId = artists.ArtistId WHERE albums.AlbumId=$albumID";
        $resultArtistName = $conn->query($sqlArtistName);
        $first = true;

        // Set the artist's name to the data of returned query
        while ($rowArtist = $resultArtistName->fetch_assoc()) {
            if ($first === true) {
                $artistNameForm = $rowArtist['Name'];
                $first = false;
            }
        }

        // Set the album name and tracks to the data of returned query
        $first1 = true;
        while ($row = $result->fetch_assoc()) {
            if ($first1 === true) {
                echo "<h2>" . $row['Title'] . "</h2>";
                $albumNameForm = $row['Title'];
                $albumTitleForm = $row['Name'];
                $first1 = false;
            }
        }
        ?>

        <!-- Form to allow users to update an album from the database. 
        Each field is required and a customer popup will be displayed if a value is not entered 
        The forms will be pre-populated using the album name, artist name, and track names from the previous SQL queries.
        On submission, the script from updatesql.php will be run -->
        <form method="post" action="updatesql.php?id=<?php echo $albumID ?>">
            <div>
                Album: &nbsp; &nbsp;<input type="text" name="albumNames" autocomplete="off" size="40" value="<?php echo $albumNameForm; ?>" required 
                                           oninvalid="this.setCustomValidity('You must enter an album name')"
                                           oninput="this.setCustomValidity('')"><br>
                Artist: &nbsp; &nbsp; &nbsp;<input type = "text" name = "artistNames" autocomplete="off" size="40" value="<?php echo $artistNameForm; ?>" required
                                                   oninvalid="this.setCustomValidity('You must enter an artist\'s name')"
                                                   oninput="this.setCustomValidity('')"><br>
                                                   <?php
                                                   while ($row = $result2->fetch_assoc()) {
                                                       $count++;
                                                       $albumTitleForm = $row['Name'];
                                                       echo "<div class=\"input-group-append\">Track " . $count . ": &nbsp;
                                <input type = \"text\" name = \"titles[]\" class = \"form-control m-input\" autocomplete = \"off\" size=\"40\" value = \"$albumTitleForm\" required
                                                                   oninvalid=\"this.setCustomValidity('You must enter an artist\'s name')\"
                                                                   oninput=\"this.setCustomValidity('')\"><br></div>";
                                                   }
                                                   ?>

                <!-- Submit button to update the album details in database -->
                <input type = "submit" name = "submit" value = "Update" id = "insertButton2">

            </div>
        </form>
    </body>
</html>