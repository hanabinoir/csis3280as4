<?php
$myCon = mysqli_connect("localhost","root","","gamebuydb");
$correct = TRUE;
$error_fname_1 = "";
$error_fname_2 = "";
$error_lname_1 = "";
$error_lname_2 = "";
$error_email_1 = "";
$error_email_2 = "";
$error_pwd_1 = "";
$error_pwd_2 = "";
$error_pwd_3 = "";
$error_pwd_4 = "";
$error_pwd_5 = "";
$setFname = "";
$setLname = "";
$setEmail = "";
$setPwd = "";

if(isset($_POST["register"])){
	$fname = trim($_POST["fname"]);
	$lname = trim($_POST["lname"]);
	$email = trim($_POST["email"]);
	$pwd = trim($_POST["pwd"]);
	
	if(empty($fname)){
		$error_fname_1 = "<td width=35%><font color ='red'> ***Your Firstname ?***</font></td>";
		$correct = FALSE;
	} else {
		$setFname = $fname;
		if(strlen($fname) > 20){
			$error_fname_2 = "<td><font color ='red'> ***Your Firstname has TOO many characters?***</font></td>";
			$correct = FALSE;
		}
	}

	if(empty($lname)){
		$error_lname_1 = "<td><font color ='red'>***Your Lastname ?***</font></td>";
		$correct = FALSE;
	} else {
		$setLname = $lname;
		if(strlen(trim($lname)) > 20){
			$error_lname_2 = "<td><font color ='red'>***Your Lastname has TOO many characters?***</font></td>";
			$correct = FALSE;
		}
	}

	if(empty($email)){
		$error_email_1 = "<td><font color ='red'>***Your e-mail ?***</font></td>";
		$correct = FALSE;
	} else {
		$setEmail= $email;
		if(strlen(trim($email)) > 20){
			$error_email_2 = "<td><font color ='red'>***Your e-mail has TOO many characters?***</font></td>";
			$correct = FALSE;
		}
	}

	if(empty($pwd)){
		$error_pwd_1 = "<td><font color ='red'>***Your Password ?***</font></td>";
		$correct = FALSE;
	} else {
		$setPwd = $pwd;
		if(strlen(trim($pwd)) !== 7){
			$error_pwd_2 = "<td><font color ='red'>***Your password must be 7 characters***</font></td>";
			$correct = FALSE;
		}
		if(is_numeric(trim($pwd))){
			$error_pwd_3 = "<td><font color ='red'>***Your password cannot be numeric ***</font></td>";
			$correct = FALSE;
		}
		if(strtolower(trim($pwd)) !== trim($pwd) || 
		!ctype_alpha(substr(trim($pwd),0,1)) && !is_numeric(trim($pwd))){
			$error_pwd_4 = "<td><font color ='red'>***Invalid character ***</font></td>";
			$correct = FALSE;
		}
		if(mysqli_connect_errno()){
			printf("connection failed: %s\n",mysqli_connect_error());
			exit();
		}
		else{
			$srchPwd = "select cust_passw from customertbl where cust_lname like '$lname'";
			$resPwd = mysqli_query($myCon, $srchPwd);
			
			if($resPwd !== FALSE){
				if(mysqli_num_rows($resPwd) !== 0){
					for($row = 1; $row <= mysqli_num_rows($resPwd); $row++){
						$recPwd = mysqli_fetch_assoc($resPwd);
						if(trim($pwd) == $recPwd["cust_passw"]){
							$error_pwd_5 = "<td><font color ='red'>Password is prohibited, please Re-enter ***</font></td>";
							$correct = FALSE;
						}
					}
				}
			}
			else
				print "error".mysqli_error($myCon);
		}
	}
	
	if($correct !== FALSE){
		if(mysqli_connect_errno()){
			printf("connection failed: %s\n",mysqli_connect_error());
			exit();
		}
		else{
			$sql = "insert into customertbl (cust_fname, cust_lname, cust_email, cust_passw)
					values ('$fname', '$lname', '$email', '$pwd')";
					
			$res = mysqli_query($myCon, $sql);
			
			if($res !== FALSE){
				if(mysqli_affected_rows($myCon) > 0){
					fwrite(fopen("lname.txt","w",$lname));
					fclose(fopen("lname.txt"));
					
					$setFname = "";
					$setLname = "";
					$setEmail = "";
					$setPwd = "";
					
					header("Location:titleSrch.php");
					exit();
				}
			} else
				print "error".mysqli_error($myCon);
			mysqli_close($myCon);
		}
	}
}
?>

<html>
<head>
<title>addNewCust</title>
</head>
<body>
<h2 align="center"><b>Game Buy</b></h2>
<h2 align="center"><b>New Member</b></h2>
<p align="center"><br>
</p>

<form method="post" action="<?php print($_SERVER['PHP_SELF']); ?>">
  <table width="75%" border="3" align="center" cellpadding="0" cellspacing="3">
    <tr>
      <td width="50%" align="right">Enter your <strong>First name</strong>(MAX 20 chars.)</td>
      <td><input type="text" name="fname" value="<?php print($setFname)?>"/></td>
	  <td><?php echo($error_fname_1); echo ($error_fname_2);?> </td>   </tr>
    <tr>
      <td align="right">Enter your <strong> Last name</strong> (MAX 20 chars.)</td>
      <td><input type="text" name="lname" value="<?php print($setLname)?>"/></td>
	  <td><?php print($error_lname_1); print ($error_lname_2);?></td>
    </tr>
    <tr>
      <td align="right">Your <strong>e-mail</strong> address (MAX 20 chars.)</td>
      <td><input type="text" name="email" value="<?php print($setEmail)?>"/></td>
	  <td><?php print($error_email_1); print ($error_email_2);?></td>
    </tr>
    <tr>
      <td align="right"><p>Your <strong>password</strong></p>
        <ul>
          <li>MUST BE 7 CHARACTERS</li>
          <li><strong>CANNOT</strong> BE ALL DIGITS</li>
          <li><strong>MUST BEGIN</strong> with a lowercase LETTER of<br>
            the alphabet</li>
          <li><strong>ONLY lowercase LETTERS OF THE<br>
            ALPHABET ALLOWED </strong></li>
        </ul></td>
      <td><input type="text" name="pwd" value="<?php print($setPwd)?>"/></td>
	  <td><?php print($error_pwd_1); print($error_pwd_2);print($error_pwd_3);print($error_pwd_4);print($error_pwd_5);?></td>
    </tr>
    <tr>
      <td align="right">&nbsp;</td>
      <td><strong>
        <input type="submit" name="register" value="Submit">
      </strong></td>
    </tr>
  </table>
</form>
