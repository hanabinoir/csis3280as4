<?php

$myCon = mysqli_connect("localhost","root","","gamebuydb");
$fileType = "type.txt";
$fileTitle = "title.txt";
$fileSrchby = "srchby.txt";
$fileOrderby = "orderby.txt";
$fhType = fopen($fileType,"r");
$fhTitle = fopen($fileTitle,"r");
$fhSrchby = fopen($fileSrchby,"r");
$fhOrderby = fopen($fileOrderby,"r");
$inType = fgets($fhType);
$inTitle = fgets($fhTitle);
$srchby = fgets($fhSrchby);
$orderby = fgets($fhOrderby);
$srchGame = "";

if(substr($inType,-1) == ","){
	$inType = substr($inType,0,-1);
}

switch($srchby){
	case "begin":
	$srchGame = "select * from gametbl where gme_type in ($inType) 
	and lower (gme_title) like lower ('$inTitle%') order by $orderby";
	break;
	
	case "within":
	$srchGame = "select * from gametbl where gme_type in ($inType) 
	and lower (gme_title) like lower ('%$inTitle%') order by $orderby";
	break;
	
	case "match":
	$srchGame = "select * from gametbl where gme_type in ($inType) 
	and lower (gme_title) like lower ('$inTitle') order by $orderby";
	break;
}

$res = mysqli_query($myCon,$srchGame);

function table($res){
	$color = "";
	$type = "";
	
	for($row = 1; $row <= mysqli_num_rows($res); $row++){
		$rec = mysqli_fetch_assoc($res);
		$title = $rec["gme_title"];
		$id = $rec["gme_id"];
		$dateAvail = date("m-d-Y",strtotime($rec["gme_date_avail"]));
		$today = date("m-d-Y");
		$price = doubleval($rec["gme_price"]);
		switch($rec["gme_type"]){
			case "f":
			$color = "bgcolor=\"green\"";
			$type = "First Person shooter";
			break;
				
			case "p":
			$color = "bgcolor=\"blue\"";
			$type = "Role Play";
			break;
				
			case "r":
			$color = "bgcolor=\"gray\"";
			$type = "Real Time Strategy";
			break;
			
			case "s":
			$color = "bgcolor=\"orange\"";
			$type = "Simulation";
			break;
			
			case "t":
			$color = "bgcolor=\"pink\"";
			$type = "Turn Based";
			break;
		}
		
		if($dateAvail <= $today){
			$col5 = "
			<select name = \"select_$id\">
				<option value=\"1\">1</option>
				<option value=\"2\">2</option>
				<option value=\"3\">3</option>
				<option value=\"4\">4</option>
				<option value=\"5\">5</option>
			</select>
			";
			$col6 = "<input type=\"checkbox\" name=\"add[]\" value=\"$id\"/>";	
		}
		else{
			$col5 = $dateAvail;
			$col6 = "";
		}
			
		print "
		<tr>
			<td align=\"center\" $color>
			<input type=\"hidden\" name=\"title_$id\" value=\"$title\"/>$title</td>
			<td align=\"center\">$type</td>
			<td align=\"center\">$id</td>
			<td align=\"center\">
			<input type=\"hidden\" name=\"price_$id\" value=\"$price\"/>$price</td>
			<td align=\"center\">$col5</td>
			<td align=\"center\">$col6</td>
		</tr>
		";
	}
}
	

if(isset($_POST["Submit"])){
	if(!empty($_POST["add"])){
		$custID = $_COOKIE['id'];
		$srchID = "select * from ordertbl where ord_cust_id = $custID";
		$resID = mysqli_query($myCon,$srchID);
		$fhTitle = fopen("title.txt","w+");
		for($i=0;$i<count($_POST["add"]);$i++){
			$id = $_POST["add"][$i];
			$qty = $_POST["select_$id"];
			$price = $_POST["price_$id"];
			$title = $_POST["title_$id"];
			
			if($resID !== FALSE){
				if(mysqli_num_rows($resID) !== 0){
					/*$add = "insert into ordertbl (ord_cust_id,ord_gme_id,ord_qty,ord_price) 
					values ('$custID','$id','$qty','$price')";
					mysqli_query($myCon,$add);*/
					$update = "update ordertbl set ord_qty = $qty
					where ord_cust_id = $custID and ord_gme_id = $id";
					mysqli_query($myCon,$update);
				}
				else{
					/*$update = "update ordertbl set ord_qty = $qty
					where ord_cust_id = $custID and ord_gme_id = $id";
					mysqli_query($myCon,$update);*/
					$add = "insert into ordertbl (ord_cust_id,ord_gme_id,ord_qty,ord_price) 
					values ('$custID','$id','$qty','$price')";
					mysqli_query($myCon,$add);
				}
			}
			else
				print "error".mysqli_error($myCon);
			
			fwrite($fhTitle,$title."\r\n");
		}
	}
	else
		fwrite(fopen("title.txt","w"),"");
	header("location: addOrderGame.php");
	exit();
}

?>

<h2 align="center"><b>Game Buy</b></h2>
<h2 align="center"><b>Title Search Results</b></h2>
<p align="center">&nbsp;</p>
<form id="form1" name="form1" method="post" action="<?php print($_SERVER['PHP_SELF']); ?>">
  <table width="90%" border="3" align="center" cellpadding="3" cellspacing="3">
    <tr>
      <th scope="col"><strong>Title</strong></th>
      <th scope="col">Type</th>
      <th scope="col">id</th>
      <th scope="col">Price/Copy</th>
      <th scope="col">Qty</th>
      <th scope="col">Add to<br /> Cart</th>
    </tr>
    <?php 
    if($res !== FALSE){
		table($res);
	}
	else{
		print "error".mysqli_error($myCon);
	}
	?>
  </table>
  <p align="center">&nbsp;</p>
  <p align="center">
    <input type="submit" name="Submit" id="button" value="Submit" />
    <input type="reset" value="Clear" />
  </p>
</form>
<p align="center"><b></b></p>
