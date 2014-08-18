<?php
header("Content-Type: text/html;charset=iso-8859-1");
header("Content-type: application/vnd.ms-excel; name='excel'");
header("Content-Disposition: filename=SEI.xls");
header("Pragma: no-cache");
header("Expires: 0");

echo $_POST['datos_a_enviar1'];
echo $_POST['datos_a_enviar2'];
echo $_POST['datos_a_enviar3'];
echo $_POST['datos_a_enviar4'];
?>