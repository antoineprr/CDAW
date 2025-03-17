<?php

class UsersController extends Controller {

	public function __construct($name, $request) {
		parent::__construct($name, $request);
	}

	public function processRequest()
    {
         switch ($this->request->getHttpMethod()) {
            case 'GET':
				return $this->getAllUsers();
                break;
        }
        return Response::errorResponse("unsupported parameters or method in users");
    }

    protected function getAllUsers()
    {
        $users = User::getList();
        $response = Response::okResponse(json_encode(
            array_map(function($user) {
                return $user->getProps(); 
            }, $users)));
        return $response;
    }
}