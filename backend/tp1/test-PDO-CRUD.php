<?php
	require_once("initPDO.php");

    class User
    {
        protected $props;
        public function __construct($props = array()) { $this->props = $props; }
        public function __get($prop) { return $this->props[$prop]; }
        public function __set($prop, $val) { $this->props[$prop] = $val; }

        static function getAllUsers($pdo)
        {
            $request = $pdo->query("select * from users");
            $request->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, get_called_class());
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

        function deleteUser($pdo){
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
            $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
            $stmt->execute();
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
