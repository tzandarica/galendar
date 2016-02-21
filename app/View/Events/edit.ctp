<?php
    echo $this->Html->css('jquery-ui/jquery-ui.theme.min');
    echo $this->Html->css('jquery-ui/jquery-ui.structure.min');
    echo $this->fetch('css');
?>

<script type="text/javascript">
    var ids = [];
    var page = 'edit';
</script>

<nav class="navbar navbar-default nav-menu">
    <div class="navbar-header pull-left">
        <a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'logout')); ?>" class="btn btn-warning navbar-btn"><span class="glyphicon glyphicon-off"></span></a>
        <a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'edit')); ?>" class="btn btn-default navbar-btn"><span class="glyphicon glyphicon-edit"></span></a>
    </div>
    <div class="navbar-header pull-right">
        <a href="<?php echo $this->Html->url(array('action' => 'all')); ?>" class="btn btn-info navbar-btn"><span class="glyphicon glyphicon-th-list"></span></a>
    </div>
</nav>

<div class="panel panel-default toggle">
    <div class="panel-body">
        <div class="center-block">
            Data: <!-- value input => today + 3 days -->
            <input type="text" class="btn btn-default form-select" value="<?php echo $event['from_date'] ?>" id="dela" name="from" readonly>
            - 
            <input type="text" class="btn btn-default form-select" value="<?php echo $event['to_date'] ?>" id="panala" name="to" readonly>
        </div>

        <hr/>

        <div class="center-block">
            Ora: &nbsp;
            <select class="form-control form-select" id="from-hours">
                <?php
                    foreach($hours as $hour) {
                        if($hour < 10) {
                            $hour = '0' . $hour . ':00';
                        } else {
                            $hour = $hour . ':00';
                        }
                        $selected = '';
                        if($hour == $event['from_hour']) {
                            $selected = 'selected';
                        }
                        echo '<option value="'.$hour.'" '.$selected.'>'.$hour.'</option>';
                    }
                ?>
            </select> 
            - 
            <select class="form-control form-select" id="to-hours">
                <?php
                    foreach($hours as $hour) {
                        if($hour < 10) {
                            $hour = '0' . $hour . ':00';
                        } else {
                            $hour = $hour . ':00';
                        }
                        $selected = '';
                        if($hour == $event['to_hour']) {
                            $selected = 'selected';
                        } elseif($event['from_hour']+1 > $hour) {
                            $selected = 'disabled';
                        }
                        echo '<option value="'.$hour.'" '.$selected.'>'.$hour.'</option>';
                    }
                ?>
            </select>
        </div>

        <hr/>

        <div class="center-block">
            <div id="users-place">
                <?php
                    $friends = '';
                    $exp = explode(',', $event['friends']);
                    if($exp[0] !== '') {
                        echo '<script type="text/javascript">';
                        foreach($exp as $friend) {
                            echo 'ids.push('.$friend.');';
                            $friends .= '<span class="label label-primary custom-text-label" id="friend_'.$friend.'">'.$friendsArr[$friend]['username'].'</span>';
                        }
                        echo '</script>';
                    } else {
                        $friends .= '<span id="info">No users selected...</span>';
                    }
                    echo $friends;
                ?>
            </div>
            <a href="#" id="add-users" class="btn btn-default users" data-toggle="modal" data-target="#friendsModal">
                <span class="glyphicon glyphicon-user"></span>
                <span class="glyphicon glyphicon-plus"></span>
            </a>
        </div>

        <hr/>
        <?php
            $emailC = '';
            $emailD = '';
            $privateC = '';
            if($event['friends'] == '') {
                $friends = false;
                if($event['is_private']) {
                    $emailC = '';
                    $emailD = 'disabled';
                    $privateC = 'checked'; 
                } else {
                    $privateC = '';
                    $emailD = '';
                    if($event['is_alert_mail']) {
                        $emailC = 'checked';
                    } else {
                        $emailC = '';
                    }
                }             
            } else {
                $friends = true;
                $emailC = 'checked';
                $emailD = 'disabled';
                if($event['is_private']) {
                    $privateC = 'checked';
                } else {
                    $privateC = '';
                }
            }
        ?>
        <div class="center-block">
            <textarea class="form-control" rows="3" id="description"><?php echo $event['description']; ?></textarea>
            <label class="checkbox-inline">
                <input type="checkbox" id="isAlertMail" <?php echo $emailC .' '. $emailD; ?>> alert mail
            </label>
            <label class="checkbox-inline">
                <input type="checkbox" id="isPrivate" <?php echo $privateC; ?>> private
            </label>
        </div>

    </div>

</div>

<!--<div class="center-block bottom-buttons" id="edit-group">
    <input type="button" class="btn btn-success pull-right form-select" id="edit" value="Edit">
</div>-->

<!--<div class="center-block bottom-buttons" id="create-group">
    <a href="index.html" class="btn btn-danger pull-left form-select">Cancel</a>
    <input type="button" class="btn btn-success pull-right form-select" name="save" id="save" value="Save">
</div>-->

<div class="center-block bottom-buttons" id="update-group">
    <a href="<?php echo $this->Html->url(array('action' => 'all')); ?>" class="btn btn-danger pull-left form-select"><span class="glyphicon glyphicon-ban-circle"></span></a>
    <a href="javascript:void(0)" class="btn btn-warning" id="delete"><span class="glyphicon glyphicon-trash"></span></a>
    <a href="javascript:void(0)" class="btn btn-success pull-right form-select" id="update"><span class="glyphicon glyphicon-floppy-disk"></span></a>
</div>

</div>

<!-- Modal -->
<div class="modal fade" id="friendsModal" tabindex="-1" role="dialog" aria-labelledby="friendsModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="list-group centered" id="friendsList">
                    <?php
                        foreach($friendsArr as $friend) {
                            $un = ucfirst($friend['username']);
                            $id = $friend['id'];
                            $activeClass = '';
                            if(in_array($id, explode(',', $event['friends']))) {
                                $activeClass = 'active';
                            }
                            
                            echo '<button type="button" class="list-group-item '.$activeClass.'" value="'.$id.'" title="'.$un.'">';
                            echo '<span class="label label-primary custom-text-label">'.$un.'</span>';
                            echo '</button>';
                        }
                    ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
    
<?php
    echo $this->Html->script('common');
    echo $this->Html->script('add');
    echo $this->Html->script('jquery-ui/jquery-ui.min');
    echo $this->fetch('script');
?>