<?php

use Phalcon\Mvc\Router\Group as RouterGroup;

class UserRoutes extends RouterGroup {
    
    public function initialize() {

        $this->setPaths(
            [
                'controller' => 'user'
            ]
        );

        $this->setPrefix('/user');

        $this->addGet(
            '/{0,1}((\?(.+))+|\?{0,1})',
            [
                'action' => 'list'
            ]
        )->setName('listUser');

        $this->addGet(
            '/([0-9]+)(/{0,1})',
            [
                'action' => 'get',
                'id'     => 1
            ]
        )->setName('getUser');

        $this->addPost(
            '(/save(/{0,1})|/{0,1})',
            [
                'action' => 'save'
            ]
        )->setName('addUser');

        $this->addPut(
            '(/edit(/{0,1})|/{0,1})',
            [
                'action' => 'edit'
            ]
        )->setName('editUser');

        $this->addDelete(
            '(/edit(/{0,1})|/{0,1})',
            [
                'action' => 'delete'
            ]
        )->setName('deleteUser');

    }

}