<!DOCTYPE html>
<html lang="en-gb">
    <head>
        <title>Dynamic Web Coursework Project - B00419772</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="styles.css">

        <!-- JavaScript function to search table for album names and display results -->
        <script>
            function searchFunc() {
                var input, filter, table, tr, td, i, txtValue;
                input = document.getElementById("search");
                filter = input.value.toUpperCase();
                table = document.getElementById("table");
                tr = table.getElementsByTagName("tr");
                for (i = 0; i < tr.length; i++) {
                    td = tr[i].getElementsByTagName("td")[0];
                    if (td) {
                        txtValue = td.textContent || td.innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            tr[i].style.display = "";
                        } else {
                            tr[i].style.display = "none";
                        }
                    }
                }
            }

        </script>
    </head>

    <body>
        <?php
        // Connect to the database
        include('databaseConnect.php');
        echo "<header><h1>Chinook Music Store</h1></header>";

        // Query the database to get a list of all albums and artists from the albums and artists tables, order by albums a-z
        $sql = "SELECT * FROM albums INNER JOIN artists ON albums.ArtistId = artists.ArtistId ORDER BY Title";
        $result = $conn->query($sql);

        // Display a button linking to the insert page and display search bar to search for album names
        echo " <div class='parent'>";
        echo " <div class = 'child'><a href=\"insert.php\" ><button id = \"insertButton\">Insert</button></a></div>";
        echo "  <div class = 'child'><input type = \"text\" id = \"search\" onkeyup = \"searchFunc()\" placeholder = \"Search for album names..\" title = \"Type in an album name\"</div>";
        echo "</div>";

        // Display results of SQL query in a table showing album name, artist name,
        // a link to update page, and delete page relating to the selected album row
        echo "<table  border='1' id=\"table\">"
        . "<tr>"
        . "<th>Album</th>"
        . "<th>Artist</th>"
        . "<th>Update</th>"
        . "<th>Delete</th>"
        . "</tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>"
            . "<td>"
            . "<a href = \"details.php?id={$row['AlbumId']}\">{$row['Title']}</a> </td>"
            . "</td>"
            . "<td>"
            . $row['Name']
            . "</td>"
            . "<td> <a href=\"update.php?id={$row['AlbumId']}\">Update</a> </td>"
            . "<td> <a href=\"delete.php?id={$row['AlbumId']}\">Delete</a> </td>"
            . "</tr>";
        }
        echo "</table>";
        ?>
    </body>
</html>