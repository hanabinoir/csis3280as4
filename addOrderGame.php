<?php
$myCon = mysqli_connect("localhost","root","","gamebuydb");

function table($myCon){
	$cardNum = "";
	$grandTotal = 0;
	$fhTitle = fopen("title.txt","r");
	$custID = $_COOKIE['id'];
	$sql = "select * from ordertbl where ord_cust_id = $custID";
	$res = mysqli_query($myCon,$sql);
	if(fgets($fhTitle) == ""){
		print '<table width="70%" align="center" cellpadding="6" cellspacing="3">';
		print '<tr><td><p align="left">Your Current Cart is EMPTY!,
		But below are your current orders from before</td></tr>';
		print '</table>';
	}
	
	print '<table width="70%" border="3" align="center" cellpadding="6" cellspacing="3">';
	print '
	<tr>
		<td width="44%"><div align="center"><strong>Title</strong></div></td>
	      <td width="9%"><div align="center"><strong>ID</strong></div></td>
	      <td width="15%"><div align="center"><strong>Qty</strong></div></td>
	      <td width="32%"><div align="center"><strong>Price/Copy</strong></div></td>
	</tr>
	';
	for($row = 1; $row <= mysqli_num_rows($res); $row++){
		$rec = mysqli_fetch_assoc($res);
		$id = $rec["ord_gme_id"];
		$titleSrch = "select gme_title from gametbl where gme_id = $id";
		$titleRes = mysqli_query($myCon,$titleSrch);
		$title = mysqli_fetch_assoc($titleRes)['gme_title'];
		$qty = intval($rec["ord_qty"]);
		$price = doubleval($rec["ord_price"]);
		$total = $qty * $price;
		
		$grandTotal += $total;
		print "
		<tr>
			<td><div align=\"center\">$title</div></td>
			<td><div align=\"center\">$id</div></td>
			<td><div align=\"center\">$qty</div></td>
			<td><div align=\"center\">$price</div></td>
		</tr>
		";
	}
	$grandTotal = "$".number_format($grandTotal,'2');
	print "
	<tr>
		<td colspan=\"3\" align=\"right\"><strong>Total:</strong></td>
		<td align=\"center\"><strong>$grandTotal</strong></td>
	</tr>
	";
	print "</table>";
	print '<p align="center">&nbsp;</p>';
	
	if(fgets($fhTitle) == ""){
		print '<p align="center">PLEASE PRESS BROWSER BACK BUTTON TO RETRY';
	}
	else{
		print '<p align="center">Enter your Credit Card Number: ';
		print "  <input type=\"text\" name=\"card\" value=\"$cardNum\"/>";
		print '<p align="center">
		    <input type="submit" name="checkout" id="button" value="CheckOut" />
		   Or 
		   <input type="submit" name="logout" id="logout" value="Log Out" />
		  </p>';
	}
	print '</p>';
	fclose($fhTitle);
}
	
	if(isset($_POST["logout"])){
		header("Location: gameBuyLogin.php");
		exit();
	}

function process($myCon){
	if(empty($_POST["card"])){
		$cardNum = "";
		print "<p align=\"center\"><strong>
				PLEASH PRESS BROWSER BACK BUTTON AND RE-ENTER YOUR CREDIT CARD NUMBER
				</strong></p>";
		if(isset($_POST["logout"])){
			header("location: gamBuyLogin.php");
			exit();
		}
	}
	else{/*
		$cardNum = $_POST["card"];
		$custID = $_COOKIE['id'];
		$checktbl = "select * from ordertbl where ord_cust_id = $custID";
		$resCheck = mysqli_query($myCon,$checktbl);
		if(mysqli_num_rows($resCheck) === 0){
			setcookie("processed","<p align=\"center\"><strong>
			Order has ALREADY been processed !!!
			</strong></p>");
			header("location: processGameOrders.php");
			exit();
		}
		else{
			$del = "delete from ordertbl where ord_cust_id = $custID";
			mysqli_query($myCon,$del);
			
		}*/
		
		header("location: processGameOrders.php");
		exit();
	}
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
  if(!isset($_POST['checkout'])){
  	table($myCon);
  }
  else{
  	process($myCon);
  }
  ?>
  
</form>
<p align="center">&nbsp;</p>
<p align="center"></p>
</body>
</html>