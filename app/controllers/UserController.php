<?php

use Phalcon\Paginator\Factory;

use Phalcon\Paginator\Adapter\Model as PaginatorModel;

class UserController extends ControllerBase
{

    public function indexAction()
    {
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
        $this->sendResponse($page);
    }

    public function listAction() {
        // The data set to paginate
        $users = User::find();

        // Create a Model paginator
        $paginator = new PaginatorModel(
            [
                'data'  => $users,
                'limit' => 10,
                'page'  => 1
            ]
        );

        $page = $paginator->getPaginate();

        $this->sendResponse($page);
    }

}

