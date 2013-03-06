<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
<link rel="stylesheet" type="text/css" href="static/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="static/css/custom.css">
</head>
<body>
<div class="container">
<div class="navbar">
  <div class="navbar-inner">
    <a class="brand" href="#">Blogdata</a>
    <ul class="nav">
      <li ><a href="addentry.php"> Einträge hinzufügen </a></li>
      <li> <a href="addprop.php"> Properties hinzufügen </a> </li>
      <li><a href="#">Link</a></li>
    </ul>
  </div>
</div>
<?php
require "DB/connect.inc.php";
$property = $_POST['property'];
$property = addslashes($property);
$selected =array('v_bool','v_numeric','v_link');
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
		 		if (is_numeric($_POST['id'])) // EDIT LINK
				{ 
					$id=(int)$_POST['id'];
            				$sql = "update pfs_propdef set typ='$typ', adverb='$property' where `id`=$id" ;
	       				$result = $db->query($sql);
            				echo '<pre>' . $sql .'</pre>';
					header('Location: http://homepages.uni-regensburg.de/~mel29425/blogdata/addprop.php');
					exit;   
					

				}
				else
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
}
elseif (isset($_GET['id']))

{

    echo "GET:";

    //post id via get erhalten --> abruf ausder Datenbank und füllen des formulars

    if (is_numeric($_GET['id']))

    {   

        $id=(int)$_GET['id'];

        $sql = "select * from pfs_propdef where `id`=$id ";
        $result = mysqli_query($db,$sql); 

        //var_dump(mysqli_fetch_array($result));

        $val=mysqli_fetch_array($result);


    }   

    else 

    {

        echo "Netter versuch";

    }
}
elseif (isset($_GET['deleteID']))

{

    echo "GET:";

    //post id via get erhalten --> abruf ausder Datenbank und füllen des formulars

    if (is_numeric($_GET['deleteID']))

    {   

        $id=(int)$_GET['deleteID'];
        $sql = "delete from pfs_propdef where `id`=$id";
        $result = mysqli_query($db,$sql); 
	$sql = "delete from pfs_props where `pfs_propdef_id`=$id ";
	$result = mysqli_query($db,$sql); 
        //var_dump(mysqli_fetch_array($result));

    }   

    else 

    {

        echo "Netter versuch";

    }

}
?>


<form action="" method="POST">
<center>
	<p>"Add property"</p>
	<?php echo $E['error']?>
	<input name="property" value='<?php echo $val["adverb"] ?>'></input>
<select name="select">
	<option value="v_bool" <?php if ($val["typ"] == $selected[0]) { 
              echo 'selected="selected"';}?>
             >bool</option>
	<option value="v_numeric"  <?php if ($val["typ"] == $selected[1]) { 
              echo 'selected="selected"';}?>>numeric</option>
	<option value="v_link"<?php if ($val["typ"] == $selected[2]) { 
              echo 'selected="selected"';}?>>link</option>
</select>
	<input type="submit" name="button" class="btn">
	<input type="hidden" value="<?php echo $id; ?>" name="id">
</center>
</form>

<pre><?php echo print_r($_POST);
print_r(db_errno);
print_r($_GET);
 ?> </pre>


<table class="table">

<thead>

<tr><th>ID</th><th>PROPERTY</th> <th>TYP</th><th>EDIT</th><th>DELETE</th></tr>

</thead><tbody>

<?php 

 

$sql = "select * from pfs_propdef LIMIT 10 ";

$result = mysqli_query($db,$sql); 

//var_dump(mysqli_fetch_array($result));

while ($myrow = mysqli_fetch_array($result))

{

   

    printf("<tr><td>%s </td><td>%s</td><td>%s</td><td><a href='%s'>Edit</a></td><td><a href='%s'>Delete</a></td></tr>\n",

            $myrow['id'],

            $myrow['adverb'],

            $myrow['typ'],

            "?id=".$myrow['id'],
	    "?deleteID=".$myrow['id']);

}

 

 

?>

</tbody>

</table>
</div>
</body>
</html>
