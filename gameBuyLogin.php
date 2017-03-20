<?php
$myCon = mysqli_connect("localhost","root","","gamebuydb");
$correct = TRUE;
$error_lname_1 = "";
$error_lname_2 = "";
$error_pwd_1 = "";
$error_pwd_2 = "";
$error_pwd_3 = "";
$setLname = "";
$setPwd = "";


if(isset($_POST["login"])){
	$lname = trim($_POST["lname"]);
	$pwd = trim($_POST["pwd"]);

	if(empty($lname)){
		$error_lname_1 = "<font color ='red'> ***Your lastname ?***</font>";
		$correct = FALSE;
	} else {
		$setLname = $lname;
		if(strlen(trim($lname)) > 20){
			$error_lname_2 = "<font color ='red'> ***Your lastname has TOO many characters?***</font>";
			$correct = FALSE;
		}
	}

	if(empty($pwd)){
		$error_pwd_1 = "<font color ='red'> ****Your password ?***</font>";
		$correct = FALSE;
	} else {
		$setPwd = $pwd;
		if(strlen($pwd) !== 7){
			$error_pwd_2 = "<font color ='red'> ***Your password MUST HAVE 7 characters ?***</font>";
			$correct = FALSE;
		}

		if(mysqli_connect_errno()){
			printf("connection failed: %s\n",mysqli_connect_error());
			exit();
		}
		else{
			$srchMatch = "select cust_passw,cust_lname from customertbl 
			where cust_lname = '$lname' and cust_passw = '$pwd'";
			$resMatch = mysqli_query($myCon, $srchMatch);
			
			if($resMatch !== FALSE){
				if(mysqli_num_rows($resMatch) == 0){
					$error_pwd_3 = "<font color ='red'>***Your password DO NOT MATCH, 
					Please Re-enter***</font>";
					$correct = FALSE;
				}
			}
			else
				print "error".mysqli_error($myCon);
		}
	}
	
	if($correct !== FALSE){
		$srchFname = "select cust_fname,cust_lname,cust_id from customertbl where cust_lname = '$lname'";
		$resFname = mysqli_query($myCon,$srchFname);
		
		if($resFname !== FALSE){
			if(mysqli_num_rows($resFname) !== 0){
				for($row = 1; $row <= mysqli_num_rows($resFname); $row++){
					$recFname = mysqli_fetch_assoc($resFname);
					$fName = $recFname["cust_fname"];
					$lName = $recFname["cust_lname"];
					$fullName = $fName." ".$lName;
					$id = $recFname["cust_id"];
					setcookie("fullName",$fullName);
					setcookie("id",$id);
								
				}
			}
		}
		else
			print "error".mysqli_error($myCon);
		
		$custID = "select * from ordertbl where ord_cust_id = $id";
		$resCustID = mysqli_query($myCon,$custID);
		if($resCustID !== FALSE){
			if(mysqli_num_rows($resCustID) !== 0){
				header("Location: addOrderGame.php");
				exit();	
			}
			else {
				header("Location:titleSrch.php");
				exit();
			}
		}
		else
			print "error".mysqli_error($myCon);
	}
}
		/*$txtFname = "fname.txt";
		$txtLname = "lname.txt";
		$fhFname = fopen($txtFname,"w");
		$fhLname = fopen($txtLname,"w");
		
		
		$setLname = "";
		$setPwd = "";
		
		if(file_exists("card.txt")){
			if(file_exists("lname.txt") && $_POST["lname"] == fgets(fopen("lname.txt","r")))
			header("Location: addOrderGame.php");
			else
			header("Location:titleSrch.php");
			
			fwrite($fhFname,$recFname["cust_fname"]);
			fwrite($fhLname,$lname);
			exit();
		}
		else{
			header("Location:titleSrch.php");
			fwrite($fhFname,$recFname["cust_fname"]);
			fwrite($fhLname,$lname);
			exit();
		}*/
	

if(isset($_POST["newMember"])){
	header("Location:addNewCust.php");
	exit();
}
?>

<html>
<head>
<title>Member Login</title>
</head>
<body>
<h2 align="center"><b>Game Buy</b></h2>
<h2 align="center"><b>Member Login</b></h2>

<form action="<?php print($_SERVER['PHP_SELF']); ?>" method="POST">
	<table width="1108" border="0" align="center" cellpadding="0" cellspacing="5">
	  <tr>
	    <td width="489" align="right">Enter Lastname (MAX: 20 characters)</td>
	    <td width="604"><input type="text" size="20" name="lname" value="<?php print($setLname)?>"/>&nbsp;&nbsp;&nbsp;&nbsp;<?php print($error_lname_1); print($error_lname_2);?></td>
      </tr>
	  <tr>
	    <td align="right">Enter Your Password (7 characters)</td>
	    <td><input type="password" size="7" name="pwd" value="<?php print($setPwd)?>"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php print($error_pwd_1); print($error_pwd_2);?></td>
      </tr>
	  <tr>
	    <td>&nbsp;</td>
	    <td><input type="submit" value="Submit" name="login">
	      &nbsp;&nbsp;&nbsp;
        <input type="reset" value="Clear"></td>
      </tr>
</table>      
      	<p align="center"><?php print "$error_pwd_3"; ?></p>
  

	

<p align="center"><font color="blue"><strong>For New Members, Please login here </strong></font>
<input type="submit" name="newMember" value="New Member">
</p>
</form>
</body>
</html>

