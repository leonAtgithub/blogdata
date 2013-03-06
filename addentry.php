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
<h1> Eintrag hinzufügen:</h1>

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
      <li> <a href="addprop.php"> Properties definieren </a> </li>
      <li><a href="#">Link</a></li>
    </ul>
  </div>
</div>
<?php
require "DB/connect.inc.php";
function format($text)
{
    return htmlentities($text,ENT_COMPAT | ENT_HTML5,'UTF-8');
}
function fetch_all($result)
{
    $rows=array();
    while ($row=$result->fetch_array())
    {// --> einfach ein fetch all weil diese php versiond as ned unterstützt        
        $rows[]=$row;
    }
    return $rows;
}
/*
Allgemeiner workflow für dieses form:
-if post ---> eingaben validieren und wenn hidden_id update statt create eintragen in pfs_eintrage
- if get ---> record mit der entpsrechenden id holen und formular füllen icl... hidden field 

Später todo --> properties hinzufügen
*/
$E=array(); //Fehler array E bleibt leer wenn keine Fehler sodas if (!len(array))==True
//$debug=$_POST["text"];
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
    
    if (is_numeric($_POST["nr_props"]))
    {   /*Hier wird es haarig: Das hidden Feld "nr_props" bestimmt wieviel variablen properties in POST übertragen werden
          daraus holt die folgende foreach ersmal alle propdef ids raus. Anschließend werden die typ definitionen geholt
          (sortiert nach id) und es wird verglichen ob die eingeben values sich in die entsprechend row values verwandeln lassen
                  
          
        */
        $propdefids=array(); //Für den lookup der definitionen --> speziell das typen feld
        $propdefdict=array();
        foreach(range(1,$_POST["nr_props"]) as $nr)
        {
            $propkey="prop".$nr;
            $valuekey="value".$nr;
            $propdefids[]=(int)$_POST[$propkey];//wichtig sonst injections möglich
            $propdefdict[(int)$_POST[$propkey]]=$_POST[$valuekey];
            
        }
        //kleiner hack wenn nur ein attribute geschrieben wird --> if ansonsten sort
        sort($propdefids);
        $sql_snippet= (count($propdefids)<=1)? $propdefids[0]:implode(" OR id=",$propdefids) ;
        $lookup_sql="select typ,id,adverb from pfs_propdef where id=".$sql_snippet." ";
        $lookup_sql.="ORDER BY id";
        $result=$db->query($lookup_sql );
        $typ_def=fetch_all($result);
        
        
        $sql_prop_value_array=array();
        foreach ($typ_def as $row)
        {
           
           $propid=$row["id"];
           $proptyp=$row["typ"] ;
           echo "<h2> $proptyp </h2>";
           
           $propref_id_sql=" pfs_propdef_id='"."$propid'"." , ";
           
           $propadverb=$row["adverb"];
           switch ($proptyp)
           {
                case "v_numeric":
                    if (is_numeric($propdefdict[$propid]))
                    {
                        $sql_prop_value_array[]=$propref_id_sql.$proptyp."='".$propdefdict[$propid]."'";
                    }
                    else
                    {
                        $E["properr"]="Property wurde als numerisch definiert, nicht numerischer Wert übertragen. ";
                    }
                break;
                case "v_bool":
                    if (is_numeric($propdefdict[$propid]))
                    {
                        $sql_prop_value_array[]=$propref_id_sql.$proptyp."='".$propdefdict[$propid]."'";
                    }
                    else
                    {
                        $E["properr"]="Property wurde als bool definiert, kein boolscher Wert übertragen. ";
                    }
                    break;//TODO weitermachen mit den Abfragen und typen casting gegen das propdefdict (user)
                    
                case "v_link":
                    if (is_numeric($propdefdict[$propid]))
                    {
                       $sql_prop_value_array[]=$propref_id_sql.$proptyp."='".$propdefdict[$propid]."'";
                    }
                    break;
                
           }
        
        }
    }

    
    if (!count($E))
    {
        $sql="insert into pfs_eintraege set ". $text_sql ;
        $result=$db->query($sql);
        if ($result)
        {
            $eintrag_id=$db->insert_id;
            foreach($sql_prop_value_array as $sqlbit)
            {
                $sql_prop_value="insert into pfs_props set ent_id='"."$eintrag_id' , ". $sqlbit;
                $result=$db->query($sql_prop_value);
            }
            
        }
    }
}
elseif (isset($_GET['id']))
{
    //eintrags id via get erhalten --> abruf ausder Datenbank und füllen des formulars
    // jetzt einfügen der monster query um alle attribute etc.. zu bekommen 
    if (is_numeric($_GET['id']))
    {   
        $id=(int)$_GET['id'];
        $result=$db->query("select * from pfs_eintraege where id=$id");
        $val=$result->fetch_array();
       
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
    <textarea  name="text" ><?php  echo format($val["body"]);?></textarea>
    <label>Properties:</label>
    <fieldset>
    
    <?php 
    //db abfrage um verfügbare properties zu bekommen
    $result=$db->query("select adverb,id from pfs_propdef");
    $rows=fetch_all($result);
    
    echo "<h3>". $E['properr'] ."</h3>";
    echo "<select name='"."prop1"."'>";
    foreach($rows as $row)
    {
        
        echo "<option value='".$row['id']."' >".$row['adverb']."</option>";
    }
    echo "<input type='text' name='value1' value='"."Dummy value"."' ></input>";
    echo "</select>";
    ?>
    
    
    
   <input type="hidden" name="nr_props" value="<?php echo 2 ;//Anzahl der properties ?>" >
   <input type="hidden" name="id" value="<?php echo $id ?>" > </br>
   <input type="submit" name="button" class="btn"> 
    </div> 





</fieldset>
</form>
<pre> 
<?php
//echo var_dump($_POST);
echo "Typ definitionen:\n";
echo var_dump($sql_prop_value_array);
echo "Propdefdict:";
var_dump($propdefdict);
echo "\n Sql generated:";
echo var_dump($sql_value);
//print_r($rows);
?>
Errors: \n
<?php
print_r($E);
/*$msql=<<<EOT
select e.id, body ,adverb, 
case 
    when v_numeric IS not null then v_numeric 
    when v_bool IS not null then v_bool
    when v_link IS NOT NULL then v_link
end as value
from pfs_eintraege as e 
join pfs_props as p on e.id = p.ent_id 
join pfs_propdef as pf on p.pfs_propdef_id = pf.id 
EOT;
$result= $db->query($msql);
print_r(fetch_all($result));*/
$db->close();
?> </pre>
</div>


</div>
</body>
</html>
