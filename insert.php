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

        // Query the database to get the max(AlbumID) from albums and get the artistID from artists 
        $sqlGetAlbumMax = "SELECT albumId FROM albums ORDER BY albumId DESC";
        $sqlGetArtists = "SELECT * FROM artists";
        $getAlbumMax = $conn->query($sqlGetAlbumMax);

        // Get the first row of returned query (ablbumID + 1)
        $first = true;
        while ($rowMax = $getAlbumMax->fetch_assoc()) {
            if ($first === true) {
                $albumMax = reset($rowMax) + 1;
                $first = false;
            }
        }

        // If the insert button is clicked, run SQL queries to insert form values into albums, tracks, and artists table
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $submit = $_POST["submit"];
            if ($submit === "Insert") {
                $albumName = $_POST["albumName"];
                $artistName = $_POST["artistName"];
                $track = $_POST["title"];
                $sql = "INSERT INTO albums( albumId, Title, ArtistId ) SELECT MAX( albumId ) + 1, '$albumName', MAX( ArtistId )+1 FROM albums";
                $conn->query($sql);
                foreach ($track as $value) {
                    $sql2 = "INSERT INTO tracks( TrackId, Name, AlbumId ) SELECT MAX( TrackId ) + 1, '$value','$albumMax' FROM tracks";
                    $conn->query($sql2);
                }
                $sql3 = "INSERT INTO artists( ArtistId, Name) SELECT MAX( ArtistId ) +1, '$artistName' FROM artists";
                $conn->query($sql3);

                // Redirect to index.php
                header('Location: index.php');
            }
        }
        ?>

        <!-- Form to allow users to insert a new album into the database. 
        Each field is required and a customer popup will be displayed if a value is not entered -->
        <form method="post" action="insert.php">
            Album: &nbsp; <input type="text" name="albumName" autocomplete="off" placeholder="Enter Album" size="40" required 
                                 oninvalid="this.setCustomValidity('You must enter an album name')"
                                 oninput="this.setCustomValidity('')"><br>
            Artist: &nbsp; &nbsp; <input type = "text" name = "artistName" list = "artists" autocomplete="off" 
                                         placeholder="Enter Artist" size="40" required
                                         oninvalid="this.setCustomValidity('You must enter an artist\'s name')"
                                         oninput="this.setCustomValidity('')"><br>

            <!-- For each of the artists in the database, display their names as an option for the dropdown datalist -->
            <datalist id = "artists">
                <?php
                foreach ($conn->query($sqlGetArtists) as $rowArtists) {
                    echo "<option value=\"$rowArtists[Name]\"/>";
                }
                ?>
            </datalist>
            Track 1: &nbsp;<input type="text" name="title[]" placeholder="Enter Track" autocomplete="off" size="40" required
                                  oninvalid="this.setCustomValidity('You must enter a track title')"
                                  oninput="this.setCustomValidity('')">

            <!-- Add row and submit buttons to insert the album into the database -->
            <div id="newRow"></div>
            <button id="addRow2" type="button">Add Track</button>
            <input type="submit" name="submit" value="Insert" id="insertButton2">
        </form>

        <script>
            // JavaScript function to add a row (album track) when inserting a new album
            let count = 1;
            $("#addRow2").click(function () {
                var html = '';
                html += '<div id="inputFormRow">';
                html += '<div class="input-group mb-3">';
                html += '<div class="input-group-append">';
                html += 'Track ' + ++count + ': &nbsp;<input type="text" name="title[]" class="form-control m-input" placeholder="Enter Track" autocomplete="off" size="40" required  oninvalid="this.setCustomValidity(\'You must enter a track title\')" oninput="this.setCustomValidity(\'\')">';
                html += '<button id="removeRow" type="button" class="btn btn-danger">Remove Track</button>';
                html += '</div>';
                html += '</div>';
                $('#newRow').append(html);

            });

            // JavaScript function to remove a row (album track) when inserting a new album
            $(document).on('click', '#removeRow', function () {
                $(this).closest('#inputFormRow').remove();
            });
        </script>
    </body>
</html>