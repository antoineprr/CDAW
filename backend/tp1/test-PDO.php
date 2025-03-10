<?php
    // initialise une variable $pdo connecté à la base locale
	require_once("initPDO.php");    // cf. doc / cours

	$request = $pdo->query("select * from users");
    // à vous de compléter...
    // afficher un tableau HTML avec les donnéees en utilisant fetch(PDO::FETCH_OBJ)
?>
    <h1>Users</h1>
    <hr>
    <table>
        <tr>
            <th>id</th>
            <th>name</th>
            <th>email</th>
        </tr>
        <?php
            while($row = $request->fetch(PDO::FETCH_OBJ)){
                echo "<tr>";
                echo "<td>".$row->id."</td>";
                echo "<td>".$row->name."</td>";
                echo "<td>".$row->email."</td>";
                echo "</tr>";
            }
        ?>
    </table>
    <hr>

<?php
    /*** close the database connection ***/
    $pdo = null;
