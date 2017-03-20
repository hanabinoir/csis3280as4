<?php
$myCon = mysqli_connect("localhost","root","","gamebuydb");
if(mysqli_connect_errno()){
	printf("connection failed: %s\n",mysqli_connect_error());
	exit();
}
else{
	$sql = "load data local infile 'c:/wamp/www/Assign 4/games.txt' into table gametbl fields terminated by ','";
	$res = mysqli_query($myCon,$sql);
	if($res !== FALSE)
		print "record added";
	else
		print mysqli_error($myCon);
	mysqli_close($myCon);
}
?>