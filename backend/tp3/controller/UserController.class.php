<?php

class UserController extends Controller {

    public function __construct($name, $request) {
        parent::__construct($name, $request);
    }

    public function processRequest()
    {
        switch ($this->request->getHttpMethod()) {
            case 'GET':
                $params = $this->request->getParams();
                if (count($params) == 1) {
                    if (isset($params[0])) {
                        return $this->getUser($params[0]);
                    }
                }
            break;
            case 'PUT':
                $params = $this->request->getParams();
                if (count($params) == 1) {
                    if (isset($params[0])) {
                        return $this->updateUser($params[0]);
                    }
                }
            break;
        }
        return Response::errorResponse("unsupported parameters or method in users");
    }

    protected function getUser($id)
    {
        $user = User::getUserById($id);
        if ($user) {
            $response = Response::okResponse(json_encode($user->getProps()));
            return $response;
        }
        return Response::notFoundResponse(json_encode(array('message' => 'User not found')));
    }

    protected function updateUser($id)
    {
        $user = User::getUserById($id);
        if (!$user) {
            return Response::notFoundResponse(json_encode(array('message' => 'User not found')));
        }
        $user = $user->updateUser($id);
        if ($user) {
            $response = Response::okResponse(json_encode($user->getProps()));
            return $response;
        }
        return Response::notFoundResponse(json_encode(array('message' => 'User not found')));

    }
}