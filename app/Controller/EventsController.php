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
App::uses('CakeEmail', 'Network/Email');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class EventsController extends AppController {

    public function beforeFilter() {
        parent::beforeFilter();
        $this->checkLogin();
    }

    public function checkLogin() {
        if (!is_null($this->Session)) {
            if ($this->Session->read('user_token') !== '' && intval($this->Session->read('user_id')) > 0) {
                return true;
            } else {
                return $this->redirect(array('controller' => 'users', 'action' => 'login'));
            }
        } else {
            return $this->redirect(array('controller' => 'users', 'action' => 'login'));
        }
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
        $uid = intval($this->Session->read('user_id'));
        
        $this->set('hours', $this->getHours());

        $friends = $this->getFriends(true, $uid);
        $this->set('friends', $friends);

        if ($request->is('post')) {
            $data = $request->data;
            $receivers = array();
            
            if($data['is_alert_mail'] == 1) { // nu are cum sa fie si privat
                if($data['friends'] !== '') {
                    $exp = explode(',', $data['friends']);
                    foreach($exp as $fid) {
                        $receivers['emails'][] = $friends[$fid]['email'];
                        $receivers['users'][] = $friends[$fid]['username'];
                    }
                } else {
                    foreach($friends as $friend) {
                        $receivers['emails'][] = $friend['email'];
                        $receivers['users'][] = $friend['username'];
                    }
                }
            }
            
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
            
            if(!empty($receivers)) { 
                $this->email($receivers, $eventArr);
            }
            
            $this->redirect('all');
        }
    }

    public function all() {
        $uid = intval($this->Session->read('user_id'));

        $conditions = array(
            'deleted' => 0,
//            'OR' => array('uid' => $uid, 'FIND_IN_SET ('.$uid.', friends)')
        );

        if (!empty($this->request->query)) {
            $data = $this->request->query; //debug($data);die;
            $event_from = strtotime($data['from_date'] . ' ' . $data['from_hour']);
            $event_to = strtotime($data['to_date'] . ' ' . $data['to_hour']);
            
            if($event_from == $event_to) {
                $hours = $this->getHours();
                $event_from = strtotime($data['from_date'] . ' ' . $hours[0]);
                $event_to = strtotime($data['to_date'] . ' ' . $hours[count($hours) - 1]);
            }
            
            if ($data['friends'] !== '') {
                $conditions[] = 'FIND_IN_SET (' . intval($data['friends']) . ', friends)';
            }

            $conditions['event_from >='] = $event_from;
            $conditions['event_to <='] = $event_to;
        } else {
            $conditions['event_from >='] = strtotime(date('d.m.Y') . ' 00:00');
        }

        $conditions[]['OR'] = array('uid' => $uid, 'FIND_IN_SET (' . $uid . ', friends)');
        $conditions[]['OR'] = array('is_private' => 0, 'friends' => '');
//debug($conditions);die;
        $all = $this->Event->find('all', array('conditions' => $conditions, 'order' => 'event_from'));
        $this->set('all', $all);
        
        $this->set('hours', $this->getHours());

        $friends = $this->getFriends(true);
        $this->set('friendsArr', $friends);
    }

    public function edit($id = null) { // TODO: text in email ca-i editat eventu, nu creat
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
        $uid = intval($this->Session->read('user_id'));

        $this->set('hours', $this->getHours());

        $friends = $this->getFriends(true, $uid);
        $this->set('friendsArr', $friends);

        if ($request->is('post')) {
            $data = $request->data;
            $receivers = array();

            if($data['is_alert_mail'] == 1) { // nu are cum sa fie si privat
                if($data['friends'] !== '') {
                    $exp = explode(',', $data['friends']);
                    foreach($exp as $fid) {
                        $receivers['emails'][] = $friends[$fid]['email'];
                        $receivers['users'][] = $friends[$fid]['username'];
                    }
                } else {
                    foreach($friends as $friend) {
                        $receivers['emails'][] = $friend['email'];
                        $receivers['users'][] = $friend['username'];
                    }
                }
            }

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
            if (!is_null($id)) {
                $eventArr['id'] = $id;
            }
            $this->Event->set($eventArr);
            $this->Event->save();
            
            if(!empty($receivers)) { 
                $this->email($receivers, $eventArr, true);
            }

            $this->redirect('all');
        }
    }

    public function getFriends($toReturn = false, $uid = false) {
        $this->loadModel('User');
        $res = array();
        $conditions = array('deleted' => 0);


        if ($uid) { // $uid = session user id
            $uid = intval($this->Session->read('user_id'));
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
    
    public function getHours() {
        $hours = array();
        for ($i = 8; $i <= 23; $i++) {
            $hours[] = $i;
        }
        
        return $hours;
    }

    public function email($receivers, $event, $isEdit = false) {
        $Email = new CakeEmail();
        $Email->config(array( // email hosting galendar.hol.es
            'host' => 'mx1.hostinger.in',
            'username' => 'contact@galendar.hol.es',
            'password' => 'wicked',
            'transport' => 'Mail',
            'tls' => false,
        ));
        
//        $Email->config(array( // email gmail
//            'host' => 'ssl://smtp.gmail.com',
//            'port' => 465,
//            'username' => 'golfeecluj@gmail.com',
//            'password' => 'minigolfisfun',
//            'transport' => 'Smtp',
//            'tls' => false,
//        ));
        
        $uid = intval($this->Session->read('user_id'));
        $me = $this->getFriend($uid);

        $receivers['emails'][] = $me['User']['email'];
        $receivers['users'][] = $me['User']['username'];
        
        $vars = array(
                    'event' => $event, 
                    'users' => $receivers['users'], 
                    'me' => $me
                );
        
        if($isEdit) {
            $vars['edit'] = true;
        }
        
        $Email->template('alert');
        $Email->emailFormat('html')
                ->to($receivers['emails'])
                ->from(array('golfeecluj@gmail.com' => $me['User']['username'] . ' - Golfee'))
                ->sender('golfeecluj@gmail.com')
                ->subject('Galendar alert')
                ->viewVars(array('mail' => $vars));
//                ->send();
//        
        $Email->send();
    }
    
    public function emailtest($how) {
        $this->autoRender = false;
        $this->layout = "";
        $Email = new CakeEmail();
        if($how == 'holes') {
            $Email->config(array( // email hosting galendar.hol.es
                'host' => 'mx1.hostinger.in',
                'username' => 'contact@galendar.hol.es',
                'password' => 'wicked',
                'transport' => 'Mail',
                'tls' => false,
            ));
        } elseif($how == 'golfee') {
            $Email->config(array( // email gmail
                'host' => 'ssl://server-0066.whmpanels.com',
                'port' => 465,
                'username' => 'play@golfee.ro',
                'password' => 'golf-fun-62',
                'transport' => 'Smtp',
                'tls' => false,
            ));
        } elseif($how == 'gmail') {
            $Email->config(array( // email gmail
                'host' => 'ssl://smtp.gmail.com',
                'port' => 465,
                'username' => 'golfeecluj@gmail.com',
                'password' => 'minigolfisfun',
                'transport' => 'Smtp',
                'tls' => false,
            ));
        } elseif($how == '') {
            die('no method received');
        }
        
//        $Email->template('alert');
        $Email->emailFormat('text')
                ->to('kiddykornfreak@gmail.com')
                ->from(array('golfeecluj@gmail.com' => 'Golfee'))
                ->sender('golfeecluj@gmail.com')
                ->subject('Galendar alert')
                ->message($how);
//                ->viewVars(array('mail' => array('event' => $event, 'users' => $receivers['users'], 'me' => $this->getFriend($uid))));
//                ->send();
//        
        $Email->send();
    }

}
