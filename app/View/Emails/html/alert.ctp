<?php
//$e_date = date('l, d.m.Y', $mail['event']['event_from']) .' - '. date('l, d.m.Y', $mail['event']['event_to']);
$e_desc = $mail['event']['description'];
$e_with = '';
foreach($mail['users'] as $friend) {
    $e_with .= $friend . ', ';
}
?>
You have an event from: 
<br/><strong><?php echo date('l, d.m.Y', $mail['event']['event_from']); ?></strong>
<br/>
to:
<br/><strong><?php echo date('l, d.m.Y', $mail['event']['event_to']); ?></strong>
<br/>width: 
<br/><strong><?php echo rtrim($e_with, ', '); ?></strong>
<br/>description: <i><?php echo $e_desc !== '' ? $e_desc : '-'; ?></i>