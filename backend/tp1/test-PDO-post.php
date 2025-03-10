<?php
	require_once("initPDO.php");

    if (isset($_POST['name']) && isset ($_POST['email'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $request = $pdo->prepare("insert into users (name, email) values (:name, :email)");
        $request->bindParam(':name', $name);
        $request->bindParam(':email', $email);
        $request->execute();
    }

	$request = $pdo->query("select * from users");
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
    <br>
    <form action="test-PDO-post.php" method="post">
        <label for="name">name : </label>
        <input type="text" name="name" id="name">
        <br>
        <label for="email">email : </label>
        <input type="email" name="email" id="email">
        <br>
        <input type="submit" value="Add">
    </form>

<?php
    $pdo = null;
