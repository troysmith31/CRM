<?php


//Include the function library
require 'Include/Config.php';
require 'Include/Functions.php';


use ChurchCRM\dto\SystemConfig;
use ChurchCRM\Utils\RedirectUtils;
$sSQL = "DELETE FROM person_per WHERE typeofmbr<>3;";
$bval = RunQuery($sSQL);

$sSQL = "
ALTER TABLE `person_per`
DROP FOREIGN KEY typeofmbr,
DROP COLUMN `fam_Name`, 
DROP COLUMN `fam_WeddingDate`, 
DROP COLUMN `fam_ScanCheck`,
DROP COLUMN `fam_ScanCredit`,
DROP COLUMN `fam_SendNewsLetter`,
DROP COLUMN `fam_DateDeactivated`, 
DROP COLUMN `fam_OkToCanvass`,
DROP COLUMN `fam_Canvasser`,
DROP COLUMN `fam_Latitude`,
DROP COLUMN `fam_Longitude`,
DROP COLUMN `typeofmbr`,
DROP COLUMN `oldid`;
";
$bval = RunQuery($sSQL);

$sSQL = "DROP table typeofmbr;";
$bval = RunQuery($sSQL);


//RedirectUtils::Redirect('index.php');
header("Refresh: 3 url=index.php");
echo 'Update completed! Redirecting after 3 seconds.';
?>