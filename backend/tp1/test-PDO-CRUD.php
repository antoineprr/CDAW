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

        static function getUserById($pdo, $id)
        {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, get_called_class());
            return $stmt->fetch();
        }

        static function showAllUsersAsTable($pdo)
        {
            $users = User::getAllUsers($pdo);
            echo "<table><tr>
                <th>id</th>
                <th>name</th>
                <th>email</th>
                <th>delete</th>
                <th>update</th>
            </tr>";
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
                <td>
                    <form method='get'>
                        <input type='hidden' name='action' value='delete' />
                        <input type='hidden' name='id' value='".$this->id."' />
                        <button type='submit'>Delete</button>
                    </form>
                </td>
                <td>
                    <form method='get'>
                        <input type='hidden' name='action' value='update' />
                        <input type='hidden' name='id' value='".$this->id."' />
                        <button type='submit'>Update</button>
                    </form>
                </td>
            </tr>";
        }

        function deleteUser($pdo){
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
            $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
            $stmt->execute();
        }

        function createUser($pdo){
            $stmt = $pdo->prepare("INSERT INTO users (name, email) VALUES (:name, :email)");
            $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
            $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
            $stmt->execute();
        }

        function updateUser($pdo){
            $stmt = $pdo->prepare("UPDATE users SET name=:name, email=:email WHERE id=:id");
            $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
            $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
            $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
            $stmt->execute();
        }
    }
?>
    <h1>Users</h1>
    <?php
        
        User::showAllUsersAsTable($pdo);
        
        if (isset($_GET['action'])) {
            if ($_GET['action'] == 'delete' && isset($_GET['id']) && is_numeric($_GET['id'])) {
                $user = User::getUserById($pdo, $_GET['id']);
                if ($user) {
                    $user->deleteUser($pdo);
                    header("Location: test-PDO-CRUD.php");
                    exit;
                }
            }
            if ($_GET['action'] == 'update' && isset($_GET['id']) && is_numeric($_GET['id'])) {
                $user = User::getUserById($pdo, $_GET['id']);
                if ($user) {
                    echo "<form method='post' action='test-PDO-CRUD.php'>
                            <input type='hidden' name='action' value='update'/>
                            <input type='hidden' name='id' value='".$user->id."' />
                            <input type='text' name='name' value='".$user->name."' />
                            <input type='text' name='email' value='".$user->email."' />
                            <button type='submit'>Update</button>
                          </form><hr>";
                }
            }
        }
        if (isset($_POST['action']) && $_POST['action'] == 'update' && isset($_POST['id']) && is_numeric($_POST['id'])) {
            $user = User::getUserById($pdo, $_POST['id']);
            if ($user) {
                $user->name = $_POST['name'];
                $user->email = $_POST['email'];
                $user->updateUser($pdo);
                header("Location: test-PDO-CRUD.php");
                exit;
            }
        }
        if (isset($_POST['action']) && $_POST['action'] == 'create') {
            if (User::getUserById($pdo, $_POST['id'])) {
                echo "User already exists";
            }
            $user = new User();
            $user->name = $_POST['name'];
            $user->email = $_POST['email'];
            $user->createUser($pdo);
            header("Location: test-PDO-CRUD.php");
            exit;
        }
        echo '<form method="post" action="test-PDO-CRUD.php">
                <input type="hidden" name="action" value="create"/>
                <input type="text" name="name" placeholder="name"/>
                <input type="text" name="email" placeholder="email"/>
                <button type="submit">Add user</button>
              </form>';
    ?>
    <br>

<?php
    $pdo = null;
