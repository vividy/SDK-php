<?php 

include "../config.inc.php";
include "../sdk/payname.inc.php";

$Payname = new Payname(PAYNAME_ID,PAYNAME_SECRET);

echo json_encode(array('success'=>$Payname->saveToken()));

exit;

?>