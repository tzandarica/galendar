<?php
$e_date = date('D, d.m.Y', $mail['event']['event_from']) .' - '. $mail['event']['event_to'];
$e_desc = $mail['event']['description'];
$e_with = '';
foreach($mail['users'] as $friend) {
    $e_with .= $friend . ',';
}
?>
You have an event on: 
<br/><strong><?php echo $e_date; ?></strong>
<br/>width: 
<br/><strong><?php echo rtrim($e_with, ','); ?></strong>
<br/>desc: <i><?php echo $e_desc; ?></i>