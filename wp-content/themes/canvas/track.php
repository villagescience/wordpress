<?php
$conn = mysql_connect('localhost','root','raspberry') or die('Could not connect.');
mysql_select_db('villagescience',$conn);
$url = $_POST['url'];
$string = "insert into vs_track(url, time_viewed) values('".$url."', now())";
mysql_query($string,$conn);
mysql_close();
?>