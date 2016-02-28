<?php
//$e_date = date('l, d.m.Y', $mail['event']['event_from']) .' - '. date('l, d.m.Y', $mail['event']['event_to']);
$e_desc = $mail['event']['description'];
$e_with = '';
$dateFrom = date('l, d.m.Y H:i:s', $mail['event']['event_from']);
$dateTo = date('l, d.m.Y H:i:s', $mail['event']['event_to']);
//debug($mail);
foreach($mail['users'] as $friend) {
    if($mail['me']['User']['username'] !== $friend) {
        $e_with .= $friend . ', ';
    }
}
if(count($mail['users']) == 5) {
    $e_with = rtrim($e_with, ', ');
    $e_with = $e_with . ' (you all have this event)';    
}
?>


<?php echo $mail['me']['User']['username']?> created an event from: 
<br/><br/><strong><?php echo $dateFrom; ?></strong>
<br/>
to:
<br/><strong><?php echo $dateTo; ?></strong>
<br/><br/>with: 
<br/><strong><?php echo $e_with; ?></strong>
<br/><br/>description: "<i><?php echo $e_desc !== '' ? nl2br($e_desc) : 'no description'; ?></i>"

