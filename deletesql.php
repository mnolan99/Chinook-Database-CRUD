<!DOCTYPE html>
<html lang="en-gb">
    <head>
        <title>Dynamic Web</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>

    <body>
        <?php
        // Connect to the database
        include('databaseConnect.php');

        // Get ID of selected album row from URL
        $artistID = "";
        $albumID = $_GET['id'];

        // Query the database to get the tracks relating to the selected album
        $sql = "SELECT * FROM tracks INNER JOIN albums ON tracks.AlbumId = albums.AlbumId WHERE tracks.AlbumId=$albumID ";
        $result = $conn->query($sql);
        $first = true;

        // Get artistID to delete from database query
        while ($row = $result->fetch_assoc()) {
            if ($first === true) {
                $artistID = $row['ArtistId'];
                $first = false;
            }
        }

        // Run SQL queries to delete selected album from the albums, tracks, and artists tables
        $sqlDeleteAlbum = "DELETE FROM albums WHERE albums.AlbumId = " . $albumID;
        $resultDeleteAlbum = $conn->query($sqlDeleteAlbum);
        $sqlDeleteTrack = "DELETE FROM tracks WHERE tracks.AlbumId = " . $albumID;
        $resultDeleteTrack = $conn->query($sqlDeleteTrack);
        $sqlDeleteArtist = "DELETE FROM artists WHERE artists.ArtistId = " . $artistID;
        $resultDeleteArtist = $conn->query($sqlDeleteArtist);

        // Redirect to index.php
        header('Location: index.php');
        ?>
    </body>
</html>