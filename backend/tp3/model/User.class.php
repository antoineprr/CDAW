<?php

class User extends Model {

   protected static $table_name = 'USER';

    // load all users from Db
    public static function getList() {
        $stm = parent::exec('USER_LIST');
        $users = $stm->fetchAll();
        return $users;
    }

    public static function getUserById($id) {
        $stm = parent::exec('USER_GET_WITH_ID' , array('id' => $id));
        $user = $stm->fetch();
        if ($user) {
            return $user;
        }
        return false;
    }

    public static function updateUser($id) {
        $input = json_decode(file_get_contents('php://input'), true);
        $stm = parent::exec('USER_UPDATE' , array('id' => $id, 'name' => $input['name'], 'email' => $input['email']));
        $user = $stm->fetch();
        if ($user) {
            return $user;
        }
        return false;
    }
}