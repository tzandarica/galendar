<?php
    echo $this->Html->css('jquery-ui/jquery-ui.theme.min');
    echo $this->Html->css('jquery-ui/jquery-ui.structure.min');
    echo $this->fetch('css');
?>

<nav class="navbar navbar-default nav-menu">
    <div class="navbar-header pull-left">
        <a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'logout')); ?>" class="btn btn-warning navbar-btn"><span class="glyphicon glyphicon-off"></span></a>
        <a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'edit')); ?>" class="btn btn-default navbar-btn"><span class="glyphicon glyphicon-edit"></span></a>
    </div>
    <div class="navbar-header pull-right">
        <?php
            if(!empty($this->request->query)) {
                ?>
        <a href="<?php echo $this->Html->url(array('action' => 'all')); ?>" class="btn btn-info navbar-btn"><span class="glyphicon glyphicon-th-list"></span></a>
        <?php
            }
        ?>
        <a href="javascript:void(0)" class="btn btn-primary" id="search"><span class="glyphicon glyphicon-search"></span></a>
        <a href="<?php echo $this->Html->url(array('action' => 'add')); ?>" class="btn btn-success navbar-btn"><span class="glyphicon glyphicon-plus"></span></a>
    </div>
</nav>

<script type="text/javascript">
    var ids = [];
    var page = 'all';
</script>

<form method="get" action="<?php echo $this->Html->url(array('action' => 'all')); ?>" id="searchForm">
<div class="panel panel-default toggle" id="searchBox" style="display: none">
    <div class="panel-body">
        <div class="center-block">
            Data: <!-- value input => today + 3 days -->
            <?php
                if(isset($this->request->query['from_date'])) { 
                    $from_date = $this->request->query['from_date'];
                } else {
                    $from_date = Date('d.m.Y', strtotime("+3 days"));
                }
                
                if(isset($this->request->query['to_date'])) { 
                    $to_date = $this->request->query['to_date'];
                } else {
                    $to_date = $from_date;
                }
            ?>
            <input type="text" class="btn btn-default form-select" value="<?php echo $from_date; ?>" id="dela" name="from_date" readonly>
            - 
            <input type="text" class="btn btn-default form-select" value="<?php echo $to_date; ?>" id="panala" name="to_date" readonly>
        </div>
        <hr/>
        <div class="center-block">
            Ora: &nbsp;
            <select class="form-control form-select" id="from-hours" name="from_hour">
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
            <select class="form-control form-select" id="to-hours" name="to_hour">
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
                <input type="hidden" name="friends" id="friends_hidden">
            </div>
            <a href="#" id="add-users" class="btn btn-default users" data-toggle="modal" data-target="#friendsModal">
                <span class="glyphicon glyphicon-user"></span>
                <span class="glyphicon glyphicon-plus"></span>
            </a>
            <hr/>
            <a href="javascript:void(0)" id="doSearch" class="btn btn-success">
                <span class="glyphicon glyphicon-search"></span>
            </a>
        </div>
    </div>
</div>
</form>
<?php
foreach($all as $ev) { //debug($ev);
    $me = intval($this->Session->read('user_id'));
    $desc = $ev['Event']['description'];
    $friendsList = $ev['Event']['friends'];
    $from_hour = date('H:i', $ev['Event']['event_from']);
    $to_hour = date('H:i', $ev['Event']['event_to']);
    $from_date = date('d.m.Y', $ev['Event']['event_from']);
    $to_date = date('d.m.Y', $ev['Event']['event_to']);
    
    $panelClass = 'panel-default';
    if($ev['Event']['is_alert_mail'] && 
            (in_array($me, explode(',', $friendsList)) || ($me == $ev['Event']['uid']))
        ) {
        $panelClass = 'panel-danger';
    }
    
    
    $date = '';
    if($from_date == $to_date) {
        $day = date('l', $ev['Event']['event_from']);
        $date = $day . ', ' . $from_date . '<br/>[' . $from_hour . ' - ' . $to_hour . ']'; 
    } else {
        $day_from = date('l', $ev['Event']['event_from']);
        $day_to = date('l', $ev['Event']['event_to']);
        $date = date('l, d.m.Y', $ev['Event']['event_from']) . '<br/>' . date('l, d.m.Y', $ev['Event']['event_to']);
    }
    
    $expFriends = explode(',', $friendsList);
    $eventFriends = '';
    if($expFriends[0] !== '') {
        $eventFriends .= '<div class="users-place-list"><strong>With: </strong>';
        foreach($expFriends as $fid) {
            foreach($friendsArr as $friend) {
                if($fid == $friend['id']) {
                    $eventFriends .= '<span class="label label-warning custom-text-label">'.$friend['username'].'</span>';
                }
            }
        }
        $eventFriends .= '</div>';
    }
    
    $eventCreator = $friendsArr[$ev['Event']['uid']]['username'];
    if($ev['Event']['covered'] > 0) { // TODO: more to come ... stay tuned :D
        $eventCover = $friendsArr[$ev['Event']['covered']]['username'];
    }
    $eventEdit = '';
    if($me == $ev['Event']['uid']) {
        $url = $this->Html->url(array('action' => 'edit', $ev['Event']['id']));
        $eventEdit .= '<div class="center-block pull-right">';
        $eventEdit .= '<a href="'.$url.'" class="btn btn-success pull-right">Edit</a>';
        $eventEdit .= '</div>';
    }
    
    if($ev['Event']['is_private']) {
        $privateStyle = 'style="opacity:0.1"';
    } else {
        $privateStyle = '';
    }
?>

<div class="panel toggle <?php echo $panelClass; ?>">
    <div class="panel-body">
        <h5>
            <span class="label label-primary custom-text-label"><?php echo $eventCreator; ?></span>
            <span class="date" <?php echo $privateStyle; ?>><strong><?php echo $date; ?></strong></span>
        </h5>
        <div class="details">
            <hr/>
            <?php echo $eventFriends; ?>
            <!--<div class="users-place-list"><strong>With: </strong><?php echo $eventFriends; ?></div>-->
            <!--<div class="covered"><span class="label label-success custom-text-label">Deea</span></div>-->
            <div class="desc"><?php echo $desc?></div><br/>
            <?php echo $eventEdit; ?>
        </div>
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
                            
                            if(intval($id) !== $me) {
                                echo '<button type="button" class="list-group-item" value="'.$id.'" title="'.$un.'">';
                                echo '<span class="label label-primary custom-text-label">'.$un.'</span>';
                                echo '</button>';
                            }
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
}
    echo $this->Html->script('common');
    echo $this->Html->script('all');
    echo $this->Html->script('jquery-ui/jquery-ui.min');
    echo $this->fetch('script');
?>