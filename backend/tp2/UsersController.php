<?php

class UsersController {

    private $requestMethod;
    private $userId;

    public function __construct($requestMethod, $userId = null)
    {
        $this->requestMethod = $requestMethod;
        $this->userId = $userId;
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if (isset($this->userId)) {
                    $response = $this->getUser($this->userId);
                } else {
                    $response = $this->getAllUsers();
                };
                break;
            case 'POST':
                $response = $this->createUser();
                break;
            case 'DELETE':
                $response = $this->deleteUser($this->userId);
                break;
            case 'PUT':
                $response = $this->updateUser($this->userId);
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }

    private function getAllUsers()
    {
        $users = UserModel::getAllUsers();
        $response = [];
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        
        $usersArray = [];
        foreach ($users as $user) {
            $usersArray[] = [
                'id' => $user->getId(),
                'name' => $user->getName(),
                'email' => $user->getEmail()
            ];
        }
        $response['body'] = json_encode($usersArray);
        return $response;
    }

    private function getUser($userId)
    {
        $user = UserModel::getUserById($userId);
        if (!$user) {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode([
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail()
        ]);
        return $response;
    }

    private function createUser() {
        $user = new UserModel();
        
        $input = json_decode(file_get_contents('php://input'), true);
        if (isset($input['name']) && isset($input['email'])) {
            $user->setName($input['name']);
            $user->setEmail($input['email']);
        }
       
        
        if (empty($user->getName())) {
            $response['status_code_header'] = 'HTTP/1.1 400 Bad Request';
            $response['body'] = json_encode(['message' => 'Missing required fields: name']);
            return $response;
        }

        if (UserModel::getUserByName($user->getName())) {
            $response['status_code_header'] = 'HTTP/1.1 409 Conflict';
            $response['body'] = json_encode(['message' => 'User with this name already exists']);
            return $response;
        }
    
        if ($user->createUser()) {
            $response['status_code_header'] = 'HTTP/1.1 201 Created';
            $response['body'] = json_encode([
                'message' => 'User created successfully',
                'user' => [
                    'name' => $user->getName(),
                    'email' => $user->getEmail()
                ]
            ]);
        } else {
            $response['status_code_header'] = 'HTTP/1.1 500 Internal Server Error';
            $response['body'] = json_encode(['message' => 'Failed to create user']);
        }
        return $response;
    }

    private function deleteUser($userId)
    {
        if (!$userId) {
            $response['status_code_header'] = 'HTTP/1.1 400 Bad Request';
            $response['body'] = json_encode(['message' => 'Missing user ID']);
            return $response;
        }
        
        $user = UserModel::getUserById($userId);
        
        if (!$user) {
            $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
            $response['body'] = json_encode(['message' => 'User not found']);
            return $response;
        }
        
        if ($user->deleteUser()) {
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode(['message' => 'User deleted successfully']);
        } else {
            $response['status_code_header'] = 'HTTP/1.1 500 Internal Server Error';
            $response['body'] = json_encode(['message' => 'Failed to delete user']);
        }
        
        return $response;
    }

    private function updateUser($userId){
        $user = UserModel::getUserById($userId);
        if (!$user) {
            return $this->notFoundResponse();
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        $originalName = $user->getName();
        
        if (isset($input['name']) && isset($input['email'])) {
            $user->setName($input['name']);
            $user->setEmail($input['email']);
        }
        
        if (empty($user->getName())) {
            $response['status_code_header'] = 'HTTP/1.1 400 Bad Request';
            $response['body'] = json_encode(['message' => 'Missing required fields: name']);
            return $response;
        }
        
        if ($user->getName() !== $originalName) {
            $existingUser = UserModel::getUserByName($user->getName());
            if ($existingUser && $existingUser->getId() != $userId) {
                $response['status_code_header'] = 'HTTP/1.1 409 Conflict';
                $response['body'] = json_encode(['message' => 'User with this name already exists']);
                return $response;
            }
        }
        
        if ($user->updateUser()) {
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode([
                'message' => 'User updated successfully',
                'user' => [
                    'id' => $user->getId(),
                    'name' => $user->getName(),
                    'email' => $user->getEmail()
                ]
            ]);
        } else {
            $response['status_code_header'] = 'HTTP/1.1 500 Internal Server Error';
            $response['body'] = json_encode(['message' => 'Failed to update user']);
        }
        
        return $response;
    }

    private function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = null;
        return $response;
    }
}