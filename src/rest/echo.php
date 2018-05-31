<?php

// $str = "Start POST: ";
// foreach($_POST as $key => $value)
// {
//     $str = $str." POST parameter '$key' has '$value' ";
// }
// $str = $str."End POST:";

// $str = $str."Start GET: ";
// foreach($_GET as $key => $value)
// {
//     $str = $str." GET parameter '$key' has '$value' ";
// }
// $str = $str."End GET:";

// $str = json_decode($_POST['json'], true);

$data = json_decode(file_get_contents("php://input"), true);

$str = "Start POST Array: ";
foreach ($data as $key => $value) {
 $str = $str."KVP -  Key: $key Value: $value ";
}
$str = $str."End POST:";

exit(json_encode($data));
?>
