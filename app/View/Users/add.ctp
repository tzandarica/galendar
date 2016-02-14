<?php
    echo $this->Html->css('jquery-ui/jquery-ui.theme.min');
    echo $this->Html->css('jquery-ui/jquery-ui.structure.min');
    echo $this->fetch('css');
?>

<nav class="navbar navbar-default nav-menu">
    <div class="navbar-header pull-left">
        <a href="#" class="btn btn-warning navbar-btn"><span class="glyphicon glyphicon-off"></span></a>
    </div>
    <div class="navbar-header pull-right">
        <a href="index.html" class="btn btn-success navbar-btn"><span class="glyphicon glyphicon-home"> Home</span></a>
        <a href="#" class="btn btn-info navbar-btn"><span class="glyphicon glyphicon-th-list"> List</span></a>
    </div>
</nav>

<div class="panel panel-default toggle">
    <div class="panel-body">
        <div class="center-block">
            Data: <!-- value input => today + 3 days -->
            <input type="text" class="btn btn-default form-select" value="16-02-2016" id="dela" name="from" readonly>
            - 
            <input type="text" class="btn btn-default form-select" value="16-02-2016" id="panala" name="to" readonly>
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
<!--                <span class="label label-primary custom-text-label">Xti</span>-->
                <!--                            <span class="label label-primary custom-text-label">Deea</span>
                                            <span class="label label-primary custom-text-label">Juju</span>-->
                <!--<span class="label label-primary custom-text-label">Ada</span>-->
            </div>
            <a href="#" id="add-users" class="btn btn-default users" data-toggle="modal" data-target="#friendsModal">
                <span class="glyphicon glyphicon-user"></span>
                <span class="glyphicon glyphicon-plus"></span>
            </a>
            <!--<input type="hidden" id="friends" name="frineds">-->
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
    <a href="index.html" class="btn btn-danger pull-left form-select">Cancel</a>
    <input type="button" class="btn btn-success pull-right form-select" name="save" id="save" value="Save">
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
            <!--                    <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="friendsModalLabel">Add</h4>
                                </div>-->
            <div class="modal-body">
                <div class="list-group centered" id="friendsList">
                    <button type="button" class="list-group-item" value="1">
                        <span class="label label-primary custom-text-label">Xti</span>
                    </button>
                    <button type="button" class="list-group-item" value="2">
                        <span class="label label-primary custom-text-label">Deea</span>
                    </button>
                    <button type="button" class="list-group-item" value="3">
                        <span class="label label-primary custom-text-label">Juju</span>
                    </button>
                    <button type="button" class="list-group-item" value="4">
                        <span class="label label-primary custom-text-label">Trd</span>
                    </button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <!--<button type="button" class="btn btn-primary">Save changes</button>-->
            </div>
        </div>
    </div>
</div>
    
<?php
    echo $this->Html->script('add');
    echo $this->Html->script('jquery-ui/jquery-ui.min');
    echo $this->fetch('script');
?>