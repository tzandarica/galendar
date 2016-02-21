<nav class="navbar navbar-default nav-menu">
    <div class="navbar-header pull-left">
        <a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'logout')); ?>" class="btn btn-warning navbar-btn"><span class="glyphicon glyphicon-off"></span></a>
    </div>
    <div class="navbar-header pull-right">
        <a href="<?php echo $this->Html->url(array('controller' => 'events', 'action' => 'all')); ?>" class="btn btn-info navbar-btn"><span class="glyphicon glyphicon-th-list"></span></a>
    </div>
</nav>

<div class="panel panel-default toggle">
    <div class="panel-body">
        <div class="center-block">
<?php
    echo $this->Form->create('User', array(
            'url' => array('controller' => 'users', 'action' => 'edit', $id),
            'id' => 'editForm',
            'inputDefaults' => array(
                    'label' => false,
                    'div' => array('class' => 'form-group')
                ),
            'class' => 'form-horizontal'
        )
    );

    echo $this->Session->flash();
    echo $this->Form->input('current_password', array('placeholder' => 'Current password', 'class' => 'form-control', 'type' => 'password', 'div' => 'col-sm-10'));
    echo $this->Form->input('new_password', array('placeholder' => 'New password', 'class' => 'form-control', 'type' => 'password', 'div' => 'col-sm-10'));
    echo $this->Form->input('repeat_password', array('placeholder' => 'Repeat new password', 'class' => 'form-control', 'type' => 'password', 'div' => 'col-sm-10'));
    echo $this->Form->input('Save', array('type' => 'submit', 'class' => 'btn btn-success', 'div' => 'col-sm-10'));
?>

        </div>
    </div>
</div>