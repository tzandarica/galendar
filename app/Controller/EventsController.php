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
class EventsController extends AppController {

    public function index() {
        echo 'index';
    }

    /*
     * if alert & no user => email to all
     * if alert & no user & private => private stays; alert uncheck and disable
     * if user/s selected => alert check and disable [private available] email to selected
     * if user/s selected & private selected => alert check and disable & private selected [email to selected; visible for selected]
     * if private & no user => visible for me only
     * 
     * 
     */
    public function add() {
        $request = $this->request;
        $hours = array();
        $uid = 3; // TODO: get from session

        for ($i = 8; $i <= 23; $i++) {
            $hours[] = $i;
        }
        $this->set('hours', $hours);

        $friends = $this->getFriends(true, $uid);
        $this->set('friends', $friends);

        if ($request->is('post')) {
            $data = $request->data;

            $event_from = strtotime($data['from_date'] . ' ' . $data['from_hour']);
            $event_to = strtotime($data['to_date'] . ' ' . $data['to_hour']);
            $eventArr = array(
                'event_from' => $event_from,
                'event_to' => $event_to,
                'friends' => $data['friends'],
                'description' => $data['description'],
                'is_private' => $data['is_private'],
                'is_alert_mail' => $data['is_alert_mail'],
                'uid' => $uid
            );
            $this->Event->set($eventArr);
            $this->Event->save();
        }
    }

    public function all() {
        $uid = 3; // TODO: get from session

        if ($this->request->is('post')) {
            $req = $this->request;
            debug($req);
        }

        $conditions = array(
            'deleted' => 0,
            'OR' => array('uid' => $uid, 'FIND_IN_SET ('.$uid.', friends)')
        );

        $all = $this->Event->find('all', array('conditions' => $conditions));
        $this->set('all', $all);

        $friends = $this->getFriends(true);
        $this->set('friendsArr', $friends);
    }

    public function edit($id = null) {
        if (!$this->Event->exists($id)) {
            throw new NotFoundException(__('Invalid ID'));
        }
        
        $event = $this->Event->find('first', array('conditions' => array('id' => $id)));
        $eventData = array(
            'from_date' => date('d.m.Y', $event['Event']['event_from']),
            'to_date' => date('d.m.Y', $event['Event']['event_to']),
            'from_hour' => date('H:i', $event['Event']['event_from']),
            'to_hour' => date('H:i', $event['Event']['event_to']),
            'description' => $event['Event']['description'],
            'is_alert_mail' => $event['Event']['is_alert_mail'],
            'is_private' => $event['Event']['is_private'],
            'friends' => $event['Event']['friends'],
            'uid' => $event['Event']['uid'],
        );
        $this->set('event', $eventData);
        
        $request = $this->request;
        $hours = array();
        $uid = 3; // TODO: get from session

        for ($i = 8; $i <= 23; $i++) {
            $hours[] = $i;
        }
        $this->set('hours', $hours);

        $friends = $this->getFriends(true, $uid);
        $this->set('friendsArr', $friends);

        if ($request->is('post')) {
            $data = $request->data;

            $event_from = strtotime($data['from_date'] . ' ' . $data['from_hour']);
            $event_to = strtotime($data['to_date'] . ' ' . $data['to_hour']);
            $eventArr = array(
                'event_from' => $event_from,
                'event_to' => $event_to,
                'friends' => $data['friends'],
                'description' => $data['description'],
                'is_private' => $data['is_private'],
                'is_alert_mail' => $data['is_alert_mail'],
                'uid' => $uid
            );
            if(!is_null($id)) {
                $eventArr['id'] = $id;
            }
            $this->Event->set($eventArr);
            $this->Event->save();
            
            $this->redirect('all');
        }
    }

    public function getFriends($toReturn = false, $uid = false) {
        $this->loadModel('User');
        $res = array();
        $conditions = array('deleted' => 0);
        
        if ($uid) { // $uid = session user id
            $uid = 3; //TODO: luat din sesiune 
            $conditions['id !='] = $uid;
        }

        $users = $this->User->find('all', array('conditions' => $conditions));
        foreach ($users as $user) {
            $res[$user['User']['id']] = $user['User'];
        }

        if (!$toReturn) {
            echo json_encode($res);
        } else {
            return $res;
        }
    }

    public function getFriend($id) {
        $this->loadModel('User');
        $user = $this->User->find('first', array('conditions' => array('id' => $id)));

        return $user;
    }

     public function delete($id = null) {
        $this->autoRender = false;
        $this->layout = "";
        if (!$this->Event->exists($id)) {
            throw new NotFoundException(__('Invalid ID'));
        }
        
        $request = $this->request;
        if ($request->is('post')) {
            $eventArr = array('id' => $id, 'deleted' => 1);
            $this->Event->set($eventArr);
            $this->Event->save();
        }
     }
}