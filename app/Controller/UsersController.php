<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('AppController', 'Controller');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class UsersController extends AppController {

    public function populateTable() {
        $this->autoRender = false;
        $this->layout = "";
        $this->User->query('TRUNCATE TABLE users');
        $this->User->query('ALTER TABLE users AUTO_INCREMENT = 1');

        $data = array(
            array('username' => 'ada', 'password' => md5('golfee')),
            array('username' => 'deea', 'password' => md5('golfee')),
            array('username' => 'juju', 'password' => md5('golfee')),
            array('username' => 'trd', 'password' => md5('golfee')),
            array('username' => 'xti', 'password' => md5('golfee'))
        );

        $this->User->saveMany($data);
    }

    public function login() {
        if ($this->request->is('post')) {
            $data = $this->request->data;

            $user = $data['User']['username'];
//            if($user == 'juju') { // for tests only
                $pass = md5($data['User']['password']);
                $conditions = array('username' => $user, 'password' => $pass);
                $query = $this->User->find('first', array('conditions' => $conditions));

                if (!empty($query)) {
                    $this->Session->write('user_id', $query['User']['id']);
                    $this->Session->write('user_token', md5($query['User']['username'] . $query['User']['password']));

                    return $this->redirect(array('controller' => 'events', 'action' => 'all'));
                } else {
                    $this->Session->setFlash('Username / Password do not match!<br/>Try again.');
                }
//            } else { // for tests only
//                $this->Session->setFlash('work in progress ... keep your idea ;)');
//            }
        }
    }

    public function logout() {
        $this->Session->destroy();

        return $this->redirect('login');
    }

    public function edit() {
        $id = intval($this->Session->read('user_id'));
        $userData = array();

        $this->set('id', $id);
        
        $user = $this->User->find('first', array('conditions' => array('id' => $id)));
        $this->set('email', $user['User']['email']);

        if ($this->request->is('post')) {
            $data = $this->request->data;
            
//            if($data['User']['current_password'] == '' || $data['User']['new_password'] == '' || $data['User']['repeat_password'] == '') {
//                $this->Session->setFlash('All fields are mandatory');
//                return $this->redirect(array('action' => 'edit', $id));
//            }
            
            if($data['User']['current_password'] !== '') {
                if(md5($data['User']['current_password']) !== $user['User']['password']) {
                    $this->Session->setFlash('Current password not correct');
                    return $this->redirect(array('action' => 'edit', $id));
                } elseif ($data['User']['new_password'] !== $data['User']['repeat_password']) {
                    $this->Session->setFlash("New password / Repeat password don't match");
                    return $this->redirect(array('action' => 'edit', $id));
                } else {
                    $userData['id'] = $id;
                    $userData['password'] = md5($data['User']['new_password']);
                }     
                $what = 'Password ';
            }
            
            if($data['User']['password'] !== '') {
                if($data['User']['email'] !== $user['User']['email']) {
                    $userData['id'] = $id;
                    $userData['email'] = $data['User']['email'];
                }
                
                $what = 'Email ';
            }
            
            if(isset($userData['password']) && isset($userData['email'])) {
                $what = 'Password & Email ';
            }
            
            $this->User->set($userData);
            $this->User->save();

            $this->Session->setFlash($what . 'changed');

            return $this->redirect(array('action' => 'edit', $id));
        }
    }

}
