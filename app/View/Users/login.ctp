<?php
    echo $this->Form->create('User', array(
            'url' => array('controller' => 'users', 'action' => 'login'),
            'id' => 'loginForm',
            'inputDefaults' => array(
                    'label' => false,
                    'div' => array('class' => 'form-group')
                ),
            'class' => 'form-horizontal'
        )
    );

    echo $this->Session->flash();
    echo $this->Form->input('username', array('placeholder' => 'Username', 'class' => 'form-control', 'type' => 'text', 'div' => 'col-sm-10'));
    echo $this->Form->input('password', array('placeholder' => 'Password', 'class' => 'form-control', 'type' => 'password', 'div' => 'col-sm-10'));
    echo $this->Form->input('Sign in', array('type' => 'submit', 'class' => 'btn btn-success', 'div' => 'col-sm-10'));