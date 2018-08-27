<?php

use Phalcon\Paginator\Factory;

use Phalcon\Paginator\Adapter\Model as PaginatorModel;

class UserController extends ControllerBase
{
    public function listAction() {
        $this->getDataPaginator();
        // The data set to paginate
        $users  = User::find();
        // Create a Model paginator
        $paginator = new PaginatorModel(
            [
                'data'  => $users,
                'limit' => $this->limit,
                'page'  => $this->page
            ]
        );
        $page = $paginator->getPaginate();
        return $this->sendResponseHttp($page);
    }

    public function saveAction() {
        $body = $this->request->getJsonRawBody();

        $user = new User();
        $user->email = $body->email;
        $user->passwordl = $body->pass;

        if ($user->save()) {
            return $this->sendResponseHttp($user);
        } else {
            return $this->sendResponseHttp($user->getMessages(), true, 409, 'SQL');
        }
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

