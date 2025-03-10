<?php
	require_once("initPDO.php");

    class User
    {
        protected $id;
        protected $name;
        protected $email;

        static function getAllUsers($pdo)
        {
            $request = $pdo->query("select * from users");
            $request->setFetchMode(PDO::FETCH_CLASS, 'User');
            return $request->fetchAll();
        }

        static function showAllUsersAsTable($pdo)
        {
            $users = User::getAllUsers($pdo);
            echo "<table><tr><th>id</th><th>name</th><th>email</th></tr><hr>";
            foreach ($users as $user){
                echo $user->toHtml();
            }
            echo "</table><hr>";
        }

        function toHtml()
        {
            return "<tr>
                <td>".$this->id."</td>
                <td>".$this->name."</td>
                <td>".$this->email."</td>
            </tr>";
        }
    }
?>
    <h1>Users</h1>
    <?php
        User::showAllUsersAsTable($pdo);
    ?>
    <br>

<?php
    $pdo = null;
