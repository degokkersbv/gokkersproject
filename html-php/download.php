
<?php
/*Actual filename = 'attachment.zip' (Unknown to the viewers).
When downloaded to be saved as 'mydownload.zip'.
*/
$filename='Degokkers.zip';
header("Content-type: application/zip");
header("Content-Disposition: attachment; filename=$filename");
echo file_get_contents('attachment.zip');
?>



