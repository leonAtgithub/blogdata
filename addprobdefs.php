<html>
<body>
<?php
require "DB/connect.inc.php";
$property = $_POST['property'];
$property = addslashes($property);
$selected =array('v_bool','v_link','v_numeric');
$typ =$_POST['select'];
$E=array();

	if (isset($_POST['button']))
	{
		if ((strlen($property)<2) OR (strlen($property)>30))
			{
				$E['error']='zu wenig oder zu viel input';
			}
	else{
		if(in_array($typ,$selected))
		{
			$sql = "insert into pfs_propdef set typ='$typ', adverb='$property'";
			$result = $db->query($sql);
			if(db_errno)
			{
				$E['error'] = 'property bereits vorhanden';
			}

			echo $sql;

		}
	}
}
?>


<form action="" method="POST">
<center>
	<p>"Add property"</p>
	<?php echo $E['error']?>
	<input name="property"></input>
<select name="select">
	<option value="v_bool">bool</option>
	<option value="v_numeric">numeric</option>
	<option value="v_link">link</option>
</select>
	<input type="submit" name="button" class="btn">
</center>
</form>

<pre><?php echo print_r($_POST);
print_r(db_errno);
 ?> </pre>

</body>
</html>
