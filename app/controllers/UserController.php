<?php

use Phalcon\Paginator\Factory;

use Phalcon\Paginator\Adapter\Model as PaginatorModel;

class UserController extends ControllerBase
{
    
    public function loginAction() {
        //$this->view->pick('index/index');
        $body       = $this->request->getJsonRawBody();
        $email      = $body->email;
        $password   = $body->password;

        $user = User::findFirst(
            [
                'email = :email: AND passwordl = :password:',
                'bind' => [
                    'email'     => $email,
                    'password'  => $password
                ]
            ]
        );
        if ($user !== false) {
            return $this->sendResponseHttp($user, false, 200, 'Login');
        } else {
            $user = new User();
            if (!strlen($email) > 0) {
                $user->addMessage('El emial es obligatorio', 'email', 'identifier');
            }
            if (!strlen($password) > 0) {
                $user->addMessage('El password es obligatorio', 'email', 'identifier');
            }
            $user->addMessage('Email/Password incorrectos', 'login', 'identifier');
            $messages = $this->interpretMessages($user->getMessages());
            return $this->sendResponseHttp($messages, true, 404, 'Not login');
        }
    }

    public function listAction() {
        $this->getDataPaginator();

        $users  = User::find();
        
        $paginator = new PaginatorModel(
            [
                'data'  => $users,
                'limit' => $this->limit,
                'page'  => $this->page
            ]
        );
        $page = $paginator->getPaginate();
        //die(var_dump($this->request->getHTTPReferer()));
        //die(var_dump();
        return $this->sendResponseHttp($page);
    }

    private function getUser($id = null) {
        if (!is_null($id) && strlen($id) > 0) {
            $user = User::findFirst($id);
            if ($user !== false) {
                return $user;
            } else {
                return null;
            }        
        } else {
            $user = new User();
            $user->addMessage('El Id es obligatorio', 'id', 'identifier');
            $messages = $this->interpretMessages($user->getMessages());
            return $this->sendResponseHttp($messages, true, 409, 'Not Content');
        }
    }

    public function getAction($id = null) {
        $user = $this->getUser($id);
        if (!is_null($user) && $user instanceof User) {
            return $this->sendResponseHttp($user);
        } else if (is_null($user)) {
            return $this->sendResponseHttp(array());
        }
        return $user;
    }

    public function saveAction() {
        $body = $this->request->getJsonRawBody();

        $user = new User();
        $user->email = $body->email;
        $user->passwordl = $body->pass;

        // $user->save()
        if ($user->create() === true) {
            return $this->sendResponseHttp($user);
        } else {
            $messages = $this->interpretMessages($user->getMessages());
            return $this->sendResponseHttp($messages, true, 409, 'SQL');
        }
    }

    public function editAction() {
        $body = $this->request->getJsonRawBody();

        $user = new User();
        $user->setId($body->id); 
        $user->setEmail($body->email);
        $user->SetPasswordl($body->pass);

        if ($user->update() === true) {
            return $this->sendResponseHttp($user);
        } else {
            $messages = $this->interpretMessages($user->getMessages());
            return $this->sendResponseHttp($messages, true, 409, 'SQL');
        }
    }

    public function deleteAction() {
        $body = $this->request->getJsonRawBody();
        $user = $this->getUser(isset($body->id) ? $body->id : null);
        if (!is_null($user) && $user instanceof User) {
            if ($user->delete() !== false) {
                return $this->sendResponseHttp($user, false, 200, 'Ok');
            } else {
                $messages = $this->interpretMessages($user->getMessages());
                return $this->sendResponseHttp($messages, true, 409, 'SQL');
            }
        } else if (is_null($user)) {
            $user = new User();
            $user->addMessage('El usuario no existe', 'user', 'delete');
            $messages = $this->interpretMessages($user->getMessages());
            return $this->sendResponseHttp($messages, true, 409, 'Not action');
        }
        return $user; 
    }

    public function indexAction() {
        /*echo json_encode(Users::find([
            'limit' => 1
        ]));*/

       /*$phql = "select * from users";
       $users = $this->db->query($phql)->fetchAll(Phalcon\Db::FETCH_ASSOC);;
       echo json_encode($users); */

        /*$users = Users::query()->execute();
        echo json_encode($users);*/

        /*$builder = $this->modelsManager->createBuilder()
            ->columns('id, email')
            ->from('users')
            ->orderBy('email');

        $options = [
            'builder' => $builder,
            'limit'   => 20,
            'page'    => 1,
            'adapter' => 'queryBuilder',
        ];

        $paginator = Factory::load($options);
        echo json_encode($builder);*/

        // The data set to paginate
        $robots = User::find();

        // Create a Model paginator, show 10 rows by page starting from $currentPage
        $paginator = new PaginatorModel(
            [
                "data"  => $robots,
                "limit" => 1,
                "page"  => 1,
            ]
        );

        // Get the paginated results
        $page = $paginator->getPaginate();
        //$this->view->disable();
        return $this->sendResponse($page);
    }
}

