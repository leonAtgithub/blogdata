<!DOCTYPE html>
<html lang="en">
<head>
<title>Blog</title>
<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
<script src="static/js/jquery.js"></script>
<script type="text/javascript" src="static/js/jquery.markitup.js"></script>
<script type="text/javascript" src="static/js/markdown_set.js"></script>
<script src="static/js/bootstrap.js"></script>
<link rel="stylesheet" type="text/css" href="static/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="static/css/custom.css">
<link rel="stylesheet" type="text/css" href="static/css/markitup.css">

</head>
<body>
<script language="javascript">
$(document).ready(function()	{
    $('#markdown').markItUp(myMarkdownSettings);
});
</script>
<header>
<h1> Eintrag hinzufügen</h1>

</header>
<div class="container">
<ul class="nav nav-pills">
<li>  </li>

</ul>

<div class="navbar">
  <div class="navbar-inner">
    <a class="brand" href="./">Blogdata</a>
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
$debug=$_POST["text"];
if (isset($_POST['button']))
{
    $text=trim($_POST["text"]);
    //$text = preg_replace('/\s\s+/', ' ', $text);
    if (!empty($text))
    {
        
        $text_sql="body='".addslashes($text) ."'";//das ist bisschen doppelt gemoopelt aber mei was solls
    }
    else 
    {
        $E["texterr"]="Das Textfeld darf nicht leer sein.";
    }
    
    //eigentlich nur ein if check oben drüber trotzdem wird status ob fehler über len(err) abgefragt
    
    if (!count($E))
    {
        $sql="insert into pfs_eintraege set ". $text_sql ;
        $db->query($sql);
    }
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
<form action="" method="Post">
<fieldset>
<legend>Eintrag erstellen</legend>
 
    <label>Text</label>
    <label class="control-label" for="inputError" > <?php echo $E["texterr"];?> </label>
    <textarea id="markdown" name="text" ><?php  echo $val["text"];?></textarea>
   <input type="hidden" name="id" value="<?php echo $id ?>" >
   <input type="submit" name="button" class="btn"> 
    </div> 





</fieldset>
</form>
<pre> 
<?php
echo var_dump($sql);
$db->close();
?> </pre>
</div>


</div>
</body>
</html>
