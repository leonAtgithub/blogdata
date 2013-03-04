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
<h1> Eintrag hinzufügen</h1>

</header>
<div class="container">
<ul class="nav nav-pills">
<li>  </li>

</ul>

<div class="navbar">
  <div class="navbar-inner">
    <a class="brand" href="#">Notes</a>
    <ul class="nav">
      <li class="active"><a href="addentry.php"> Einträge hinzufügen </a></li>
      <li> <a href="addprop.php"> Properties hinzufügen </a> </li>
      <li><a href="#">Link</a></li>
    </ul>
  </div>
</div>
<?php
require "DB/connect.inc.php";

/*
Allgemeiner workflow für dieses form:
-if post ---> eingaben validieren und wenn hidden_id update statt create eintragen in pfs_eintrage
- if get ---> record mit der entpsrechenden id holen und formular füllen icl... hidden field 

Später todo --> properties hinzufügen
*/
$E=array(); //Fehler array E bleibt leer wenn keine Fehler sodas if (!len(array))==True

if (isset($_POST['button']))
{
   //Validierung post 
}
elseif (isset($_GET['id']))
{
    //eintrags id via get erhalten --> abruf ausder Datenbank und füllen des formulars
    if (is_numeric($_GET['id']))
    {   
        $id=(int)$_GET['id'];
    }   
    else 
    {
        echo "Netter versuch";
    }
}
else 
{
    // keine Daten übertragen --> setzte nur einfaches formular 
}



?>
<div class="entry">
<form action="./" method="Post">
<fieldset>
<legend>Eintrag erstellen</legend>
 
    <label>Text</label>
    <label class="control-label" for="inputError" > <?php echo $E["texterror"];?> </label>
    <textarea  name="text" > 
    <?php  echo "something";?>
    </textarea>
   <input type="hidden" name="id" value="<?php echo $id ?>" >
    </div> 




<input type="submit" name="button" class="btn"> 
</fieldset>
</form>
</div>

<?php
$db->close();
?>
</div>
</body>
</html>
