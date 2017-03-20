<?php
if(isset($_POST["cr"])){
	if(file_exists("test.txt")){
		print "file exist";
	}
	else
	fwrite(fopen("test.txt","w"),"record");
}
if(isset($_POST["del"])){
	unlink("fname.txt");
}
?>
<html>
	<body>
		<form method="post" action="<?php print($_SERVER['PHP_SELF']); ?>">
			<input type="submit" name="cr" value="create"/>
			<input type="submit" name="del" value="delete"/>
		</form>
	</body>
</html>