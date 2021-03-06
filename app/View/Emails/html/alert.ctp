<?php
$e_desc = $mail['event']['description'];
$e_with = '';
$dateFrom = date('l, d.m.Y - H:i', $mail['event']['event_from']);
$dateTo = date('l, d.m.Y - H:i', $mail['event']['event_to']);

foreach($mail['users'] as $friend) {
    if($mail['me']['User']['username'] !== $friend) {
        $e_with .= $friend . ', ';
    }
}
$e_with = rtrim($e_with, ', ');
if(count($mail['users']) == 5) {
    $e_with = $e_with . ' (you all have this event)';    
}

if(isset($mail['edit']) && $mail['edit']) {
    $verb = 'updated';
} else {
    $verb = 'created';
}
?>


<strong><?php echo $mail['me']['User']['username']?></strong> <?php echo $verb; ?> an event 
<br/><br/>from:<br/><strong><?php echo $dateFrom; ?></strong>
<br/>
to:
<br/><strong><?php echo $dateTo; ?></strong>
<br/><br/>with: 
<br/><strong><?php echo $e_with; ?></strong>
<br/><br/>description: <br/>"<i><?php echo $e_desc !== '' ? nl2br($e_desc) : 'no description'; ?></i>"

