<?php
if(isset($_POST["Submit"])){
	$title = trim($_POST["title"]);
	$txtTitle = "title.txt";
	$fhTitle = fopen($txtTitle,"w+");
	fwrite($fhTitle,$title);
	
	$srchby = $_POST["srchby"];
	$txtSrchby = "srchby.txt";
	$fhSrchby = fopen($txtSrchby,"w+");
	fwrite($fhSrchby,$srchby);
	
	$orderby = $_POST["orderby"];
	$txtOrderby = "orderby.txt";
	$fhOrderby = fopen($txtOrderby,"w+");
	fwrite($fhOrderby,$orderby);
	
	$type = "";
	$txtType = "type.txt";
	$fhType = fopen($txtType,"w+");
	if(empty($_POST["type"])){
		$type = "'f','p','r','s','t'";
		fwrite($fhType,$type);
	}
	else{
		for($i = 0; $i < count($_POST["type"]); $i++){
			$type = $_POST["type"][$i].",";
			fwrite($fhType,$type);
		}
	}
	
	header("Location:fetchDisplayGame.php");
	exit();
}
?>

<html>
<head>
<title>Member Login</title>
</head>
<body>
<h2 align="center"><b>Game Buy</b></h2>
<h2 align="center"><b>Welcome <?php echo $_COOKIE['fullName']?></b></h2>
<p align="center">&nbsp;</p>

<form action="<?php print($_SERVER['PHP_SELF']); ?>" method="POST">
  <table width="70%" height="175" border="3" align="center" cellpadding="5" cellspacing="3">
    <tr>
      <td width="11%" align="right"><strong>Title</strong></td>
      <td colspan="3"><label for="textfield"></label>
        <div align="center">
          <input name="title" type="text" id="textfield" size="55">
      </div></td>
      <td width="14%"><div align="center">
        <input type="submit" name="Submit" id="button" value="Search">
      </div></td>
    </tr>
    <tr>
      <td rowspan="3">&nbsp;</td>
      <td width="11%" align="right"><strong>Search By:</strong></td>
      <td width="27%"><select name="srchby" id="select">
        <option value="begin">Begin With</option>
        <option value="within" selected="selected">Within Title</option>
        <option value="match">Exact Match</option>
      </select></td>
      <td width="37%" rowspan="3"><p>
        <label>
          <input type="checkbox" name="type[]" value="'f'" id="gameType_0">
          First Person Shooter</label>
        <br>
        <label>
          <input type="checkbox" name="type[]" value="'p'" id="gameType_1">
          Role Play</label>
        <br>
        <label>
          <input type="checkbox" name="type[]" value="'r'" id="gameType_2">
          Real Time Strategy</label>
        <br>
        <label>
          <input type="checkbox" name="type[]" value="'s'" id="gameType_3">
          Simulation</label>
        <br>
        <label>
          <input type="checkbox" name="type[]" value="'t'" id="gameType_4">
          Turn Based</label>
        <br>
        <strong>All Types (If NO check box is selected)</strong></p></td>
      <td rowspan="3">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td align="left" valign="middle"><label>
          <input name="orderby" type="radio" id="orderby_0" value="gme_title asc" checked="CHECKED">
          <strong>Order By Title</strong></label></td>
    </tr>
    <tr>
      <td height="40">&nbsp;</td>
      <td align="left" valign="middle"><label>
        <input type="radio" name="orderby" value="gme_price desc" id="orderby_1">
        <strong>Order By Price (Highest)</strong></label></td>
    </tr>
  </table>
  <p align="center">
    <input type="reset" name="button2" id="button2" value="Clear">
  </p>
</form>
</body>
</html>

