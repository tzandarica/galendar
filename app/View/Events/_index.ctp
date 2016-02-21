<nav class="navbar navbar-default nav-menu">
    <div class="navbar-header pull-left">
        <a href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'logout')); ?>" class="btn btn-warning navbar-btn"><span class="glyphicon glyphicon-off"></span></a>
    </div>
    <div class="navbar-header pull-right">
        <a href="<?php echo $this->Html->url(array('action' => 'add')); ?>" class="btn btn-success navbar-btn"><span class="glyphicon glyphicon-plus"> Add</span></a>
        <a href="<?php echo $this->Html->url(array('action' => 'all')); ?>" class="btn btn-info navbar-btn"><span class="glyphicon glyphicon-th-list"> List</span></a>
    </div>
</nav>

<?php
foreach($all as $ev) { //debug($ev);
    $me = 3; // TODO: get from sessison my ID
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
                    // TODO: get friends with at event
                    $eventFriends .= '<span class="label label-warning custom-text-label">'.$friend['username'].'</span>';
                }
            }
        }
        $eventFriends .= '</div>';
    }
    
    $eventCreator = $friendsArr[$ev['Event']['uid']]['username'];
    if($ev['Event']['covered'] > 0) {
        $eventCover = $friendsArr[$ev['Event']['covered']]['username'];
    }
    $eventEdit = '';
    if($me == $ev['Event']['uid']) {
        $url = $this->Html->url(array('action' => 'edit', $ev['Event']['id']));
        $eventEdit .= '<div class="center-block pull-right">';
        $eventEdit .= '<a href="'.$url.'" class="btn btn-success pull-right">Edit</a>';
        $eventEdit .= '</div>';
    }
    
//    debug($friendsArr);
?>

<div class="panel toggle <?php echo $panelClass; ?>">
    <div class="panel-body">
        <h5>
            <span class="label label-primary custom-text-label"><?php echo $eventCreator; ?></span>
            <span class="date"><strong><?php echo $date; ?></strong></span>
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

<?php
}
    echo $this->Html->script('list');
    echo $this->fetch('script');
?>