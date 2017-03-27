
<?php


$file_name = 'Degokkers.zip';
header('Content-Type: application/zip');
header("Content-Transfer-Encoding: Binary"); 
header("Content-disposition: attachment; filename=\"" . basename($file_name) . "\""); 
readfile($file_name); // do the double-download-dance (dirty but worky)


?>



