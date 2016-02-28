<?php
    echo $this->Html->css('jquery-ui/jquery-ui.theme.min');
    echo $this->Html->css('jquery-ui/jquery-ui.structure.min');
    echo $this->fetch('css');
?>

<script type="text/javascript">
    var ids = [];
    var page = 'add';
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
            <input type="text" class="btn btn-default form-select" value="<?php echo Date('d.m.Y', strtotime("+1 days")); ?>" id="dela" name="from" readonly>
            - 
            <input type="text" class="btn btn-default form-select" value="<?php echo Date('d.m.Y', strtotime("+1 days")); ?>" id="panala" name="to" readonly>
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
                        echo '<option value="'.$hour.'">'.$hour.'</option>';
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
                        echo '<option value="'.$hour.'">'.$hour.'</option>';
                    }
                ?>
            </select>
        </div>
        <hr/>
        <div class="center-block">
            <div id="users-place">
                <span id="info">No users selected...</span>
            </div>
            <a href="#" id="add-users" class="btn btn-default users" data-toggle="modal" data-target="#friendsModal">
                <span class="glyphicon glyphicon-user"></span>
                <span class="glyphicon glyphicon-plus"></span>
            </a>
        </div>
        <hr/>
        <div class="center-block">
            <textarea class="form-control" rows="3" id="description"></textarea>
            <label class="checkbox-inline">
                <input type="checkbox" id="isAlertMail"> alert mail
            </label>
            <label class="checkbox-inline">
                <input type="checkbox" id="isPrivate"> private
            </label>
        </div>
    </div>
</div>

<!--<div class="center-block bottom-buttons" id="edit-group">
    <input type="button" class="btn btn-success pull-right form-select" id="edit" value="Edit">
</div>-->

<div class="center-block bottom-buttons" id="create-group">
    <a href="<?php echo $this->Html->url(array('action' => 'all')); ?>" class="btn btn-danger pull-left form-select"><span class="glyphicon glyphicon-ban-circle"></span></a>
    <a href="javascript:void(0)" class="btn btn-success pull-right form-select" id="save"><span class="glyphicon glyphicon-floppy-disk"></span></a>
</div>

<!--<div class="center-block bottom-buttons" id="update-group">
    <a href="index.html" class="btn btn-danger pull-left form-select">Cancel</a>
    <input type="button" class="btn btn-success pull-right form-select" name="update" id="update" value="Update">
</div>-->

</div>

<!-- Modal -->
<div class="modal fade" id="friendsModal" tabindex="-1" role="dialog" aria-labelledby="friendsModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="list-group centered" id="friendsList">
                    <?php
                        foreach($friends as $friend) {
                            $un = ucfirst($friend['username']);
                            $id = $friend['id'];
                            
                            echo '<button type="button" class="list-group-item" value="'.$id.'" title="'.$un.'">';
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