<?php
if(isset($_POST["logout"])){
	header("Location: gameBuyLogin.php");
	exit();
}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>addOrderGame</title>
</head>

<body>
<h2 align="center"><b>Game Buy</b></h2>
<h2 align="center"><b>Order So Far for <?php echo $_COOKIE['fullName']?></b></h2>
<p align="center">&nbsp;</p>
<form id="form1" name="form1" method="post" action="<?php print($_SERVER['PHP_SELF']); ?>">
  <?php
  $myCon = mysqli_connect("localhost","root","","gamebuydb");
  $custID = $_COOKIE['id'];
  $checktbl = "select * from ordertbl where ord_cust_id = $custID";
	$resCheck = mysqli_query($myCon,$checktbl);
		if(mysqli_num_rows($resCheck) == 0){
			print "<p align=\"center\"><strong>
			Order has ALREADY been processed !!!
			</strong></p>";
		}
  print "<p align=\"center\"><strong>
		Thank You, Please Close Your Browser to Exit
		</strong></p>";
		
	$del = "delete from ordertbl where ord_cust_id = $custID";
	mysqli_query($myCon,$del);
  ?>
  <p align="center">
   Or 
   <input type="submit" name="logout" id="logout" value="Log Out" />
  </p>
</form>
<p align="center">&nbsp;</p>
<p align="center"></p>
</body>
</html>