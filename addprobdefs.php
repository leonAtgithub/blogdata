<?php
require "DB/connect.inc.php";
$property = $_POST['property'];
$property = addslashes($property);
if ((strlen($property)<2) OR (strlen($property)>30))
{
	echo "zu wenig oder zu viel input!";
}
else{
if (isset($_POST['button']))
{
	if($_POST['select'] == "bool")
{
	$typ = "v_bool";
}
	if($_POST['select'] == "numeric")
{
	$typ = "v_numeric";
}
	if($_POST['select'] == "link")
{
	$typ = "v_link";
}

	$sql = "insert into pfs_propdef set typ='$typ', adverb='$property'";
	$result = $db->query($sql);
	if(db_errno)
{
echo "property bereits vorhanden!!";
}
	echo $sql;

}
}
?>

<html>
<body>
<form action="" method="POST">
<center>
	<textarea name="property">"add property!"</textarea>
<select name="select">
	<option value="bool">bool</option>
	<option value="numeric">numeric</option>
	<option value="link">link</option>
</select>
	<input type="submit" name="button" class="btn">
</center>
</form>

<pre><?php echo print_r($_POST);
print_r(db_errno);
 ?> </pre>

</body>
</html>
