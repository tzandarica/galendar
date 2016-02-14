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
    
    public function index() {
        echo 'index';
    }
    
    public function add() {
        $request = $this->request;
        $hours = array();
        
        for($i = 8; $i <= 23; $i++) {
            $hours[] = $i;
        }
        $this->set('hours', $hours);
        
        if($request->is('post')) {
            $data = $request->data;
            
            $event_from = strtotime($data['from_date'] .' '. $data['from_hour']);
            $event_to = strtotime($data['to_date'] .' '. $data['to_hour']);
            $this->Event->set(array(
                'event_from' => $event_from,
                'event_to' => $event_to,
                'friends' => $data['friends'],
                'description' => $data['description'],
                'is_private' => $data['is_private'],
                'is_alert_mail' => $data['is_alert_mail']
            ));
//            debug($data);die;
            
            $this->Event->save($request->data);
        }
    }
    
    public function view() {
        echo 'view';
    }
    
    public function edit() {
        echo 'update';
    }
    
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
    
    // public function delete
}
