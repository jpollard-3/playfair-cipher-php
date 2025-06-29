<?php
// Playfair example driver script.
// by John Pollard III
// c. 2003

include "playfair_inc.php";

$key = $_POST['key'] ?? '';
$data = $_POST['data'] ?? '';
$action = $_POST['action'] ?? "encode";

$pf = new playfair_cipher();

if ($action == "decode" && $key != '' && $data != '')
{ echo $pf->decode($data,$key); } else { echo $pf->encode($data,$key); }

?>