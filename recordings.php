<?php
$directory  = "videos"; 
$images = scandir($directory);
$ignore = Array(".", "..");
$count=1;
echo '<table border=1>';
foreach($images as $dispimage){
    if(!in_array($dispimage, $ignore)){
    echo "<tr id='del$count'><td>$count</td><td>$dispimage</td><td><input type='button' id='delete$count' value='Delete' onclick='deleteFile(\"$dispimage\",$count,\"$directory\");'></td></tr>";
    $count++;
    }
}
echo '</table>';
?>
<script type="text/javascript" src="/js/jquery-2.1.3.min.js"></script>
<script type="text/javascript">
function deleteFile(fname,rowid,directory)
{
    $.ajax({ url: "deletefile.php",
        data: {"filename":fname,"directory":directory},
        type: 'post',
        success: function(output) {
          alert(output);
          $("#del"+rowid).remove();
        }
    });
}
</script>