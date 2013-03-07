<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
<link rel="stylesheet" type="text/css" href="static/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="static/css/custom.css">
</head>
<body>
<div class="container">
<?php
require "DB/connect.inc.php";?>

<header>
<h1> SUCHEN! </h1>
</header>
<div class="navbar">
  <div class="navbar-inner">
    <a class="brand" href="#">Blogdata</a>
    <ul class="nav">
      <li ><a href="addentry.php"> Einträge hinzufügen </a></li>
      <li> <a href="addprop.php"> Properties definieren </a> </li>
      <li><a href="#">Link</a></li>
      <li> <a href="search.php"> Suchen </a> </li>
    </ul>
  </div>
</div>
<form action="" method="POST">
<table class="table">

<thead>

<tr><th>ID</th><th>PROPERTY</th> <th>TYP</th><th>Search for..</th></tr>

</thead><tbody>
<?php
echo "2 Attriute auswählen!";
if(isset($_POST['check']))
{
$arr = $_POST['check'];
$string = array();
$x = 0;
		foreach($arr as $value)
		{
		$string[$x] = $value;
		$x++;
		}
}	
echo $string[0], $string[1];
?>
<?php 

 

$sql = "select * from pfs_propdef LIMIT 10 ";

$result = mysqli_query($db,$sql); 

//var_dump(mysqli_fetch_array($result));
$index = 0;
while ($myrow = mysqli_fetch_array($result))

{

   

    printf("<tr><td>%s</td><td>%s</td><td>%s</td><td><input type='checkbox' value=%s name=%s</td></>
        </td></tr>\n",

	    $myrow['id'],
            $myrow['adverb'],
            $myrow['typ'],
	    $myrow['id'],
	    "check[]");
$index++;
}
echo "<input type='hidden' name='anzahlcheckbox' value='$index'>";?>
<input type="submit" name="button" class="btn">

 


</tbody>
</form>
<?php
include_once "markdown/markdown.php";
$mquery=<<<EOT
select distinct en.body from pfs_props a inner join pfs_props b on a.ent_id = b.ent_id join pfs_eintraege as en on en.id=a.ent_id where a.pfs_propdef_id=$string[0] and b.pfs_propdef_id=$string[1];


 
EOT;
mysqli_query($db,$mquery);



$result=$db->query($mquery);
while ($row = $result->fetch_array())
{
    echo "<div class='entry'> <p>";
    print(Markdown($row["body"]));
    echo "<h3>Attributes: </h3> </br>";
    echo $row['adverb'];
    echo "</p>";
    echo "<a href='addentry.php?id=".$row["id"]."' class='btn'> Edit </a></div >";    
}
?>
</div>
</body>
