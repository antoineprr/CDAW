<?php

class UserModel
{
    protected $id;
    protected $name;
    protected $email;

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setEmail($email) {
        $this->email = $email;
    }
    
    public static function getAllUsers() {
        $pdo = DatabaseConnector::current();
        $request = $pdo->query("SELECT * FROM users");
        $request->setFetchMode(PDO::FETCH_CLASS, 'UserModel');
        return $request->fetchAll();
    }

    public static function getUserById($id) {
        $pdo = DatabaseConnector::current();
        $request = $pdo->prepare("SELECT * FROM users WHERE id = :id");
        $request->bindValue(':id', $id, PDO::PARAM_INT);
        $request->execute();
        $request->setFetchMode(PDO::FETCH_CLASS, 'UserModel');
        $result = $request->fetch();
        return $result ? $result : null;
    }

    public static function getUserByName($name) {
        $pdo = DatabaseConnector::current();
        $request = $pdo->prepare("SELECT * FROM users WHERE name = :name");
        $request->bindValue(':name', $name, PDO::PARAM_STR);
        $request->execute();
        $request->setFetchMode(PDO::FETCH_CLASS, 'UserModel');
        $result = $request->fetch();
        return $result ? $result : null;
    }

    public function createUser(){
        try {
            $pdo = DatabaseConnector::current();
            $request = $pdo->prepare("INSERT INTO users (name, email) VALUES (:name, :email)");
            $request->bindValue(':name', $this->name, PDO::PARAM_STR);
            $request->bindValue(':email', $this->email, PDO::PARAM_STR);
            $result = $request->execute();
            
            if ($result) {
                $this->id = $pdo->lastInsertId();
            }
            
            return $result;
        } catch (PDOException $e) {
            return false;
        }
    }
    
    public function deleteUser() {
        try {
            $pdo = DatabaseConnector::current();
            $request = $pdo->prepare("DELETE FROM users WHERE id = :id");
            $request->bindValue(':id', $this->id, PDO::PARAM_INT);
            return $request->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function updateUser(){
        try {
            $pdo = DatabaseConnector::current();
            $request = $pdo->prepare("UPDATE users SET name = :name, email = :email WHERE id = :id");
            $request->bindValue(':name', $this->name, PDO::PARAM_STR);
            $request->bindValue(':email', $this->email, PDO::PARAM_STR);
            $request->bindValue(':id', $this->id, PDO::PARAM_INT);
            return $request->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
}