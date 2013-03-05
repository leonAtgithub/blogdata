<!DOCTYPE html>
<html lang="en">
<head>
<title>Blog</title>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
<script src="static/js/jquery.js"></script>
<script src="static/js/bootstrap.js"></script>
<link rel="stylesheet" type="text/css" href="static/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="static/css/custom.css">
</head>
<body>
<header>
<h1> Annotated Blog</h1>

</header>
<div class="container">
<ul class="nav nav-pills">
<li>  </li>

</ul>

<div class="navbar">
  <div class="navbar-inner">
    <a class="brand" href="#">Blogdata</a>
    <ul class="nav">
      <li ><a href="addentry.php"> Einträge hinzufügen </a></li>
      <li> <a href="addprobdefs.php"> Properties definieren </a> </li>
      <li><a href="#">Link</a></li>
    </ul>
  </div>
</div>
<?php
require "DB/connect.inc.php";
include_once "markdown/markdown.php";

function format($text)
{
    return htmlentities($text,ENT_COMPAT | ENT_HTML5,'UTF-8');
}


$result=$db->query("select * from pfs_eintraege");
while ($row = $result->fetch_array())
{
    echo "<div class='entry'> <p>";
    print(Markdown($row["body"]));
    echo "<h3>Attributes: </h3>";
    echo "</p>";
    echo "<a href='addentry.php?id=".$row["id"]."' class='btn'> Edit </a></div >";    
}





$db->close();
?>
</div>
</body>
</html>
