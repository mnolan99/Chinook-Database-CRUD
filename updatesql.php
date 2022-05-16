<!DOCTYPE html>
<html lang="en-gb">
    <head>
        <title>Dynamic Web</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    </head>

    <body>
        <?php
        // Connect to the database
        include('databaseConnect.php');

        // Get id, albumName, ArtistName, and tracks from the URL and posted data from the update form
        $albumID = $_GET['id'];
        $albumName = $_POST["albumNames"];
        $artistName = $_POST["artistNames"];
        $tracks = $_POST["titles"];
        $count = count($tracks);

        // Query the database to update the album name to the new value that was entered in the update form
        $sqlUpdateAlbum = "UPDATE albums SET Title = '$albumName' WHERE albumId = '$albumID'";
        $conn->query($sqlUpdateAlbum);

        // Get the artistID from selected album using an SQL query
        $sqlArtistID = "SELECT artistId FROM albums WHERE albums.AlbumId=$albumID";
        $resultArtistID = $conn->query($sqlArtistID);
        while ($rowArtist = $resultArtistID->fetch_assoc()) {
            $artistNameID = $rowArtist['artistId'];
        }
        // Query the database to update the artist name to the new value that was entered in the update form
        $sqlUpdateArtist = "UPDATE artists INNER JOIN albums ON artists.ArtistId = albums.ArtistId SET Name = "
                . "'$artistName' WHERE artists.ArtistId = "
                . "'$artistNameID' AND albumId = '$albumID'";
        $conn->query($sqlUpdateArtist);

        // Get the trackId for the current selected album by querying the database
        $sqlTrackId = "SELECT TrackId FROM tracks WHERE tracks.AlbumId=$albumID";
        $resultTrackID = $conn->query($sqlTrackId);
        // For each trackID, insert the id into a new array
        while ($rowTrackID = $resultTrackID->fetch_assoc()) {
            $TrackNameID[] = $rowTrackID['TrackId'];
        }
        // for each of the tracks in the update form, get the trackID from the array and the new track name value
        // Query the database to update the trackName using the trackID value and new track name
        for ($i = 0; $i < $count; $i++) {
            $updateTrackNameIDIndex = $TrackNameID[$i];
            $updateTracksIndex = $tracks[$i];
            $sqlTrackUpdateNew = "UPDATE tracks INNER JOIN albums ON tracks.AlbumId = "
                    . "albums.AlbumId INNER JOIN artists ON albums.ArtistId "
                    . "= artists.ArtistId SET "
                    . "tracks.Name = \"" . $updateTracksIndex . "\""
                    . " WHERE artists.ArtistId = \"" . $artistNameID . "\""
                    . " AND albums.albumId = \""
                    . $albumID . "\"" . " AND tracks.trackId = \""
                    . $updateTrackNameIDIndex . "\"";
            $conn->query($sqlTrackUpdateNew);
        }

        // Redirect to index.php
        header('Location: index.php');
        ?>
    </body>
</html>