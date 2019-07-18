<?php


//Include the function library
require 'Include/Config.php';
require 'Include/Functions.php';


use ChurchCRM\dto\SystemConfig;
use ChurchCRM\Utils\RedirectUtils;


// create typeofmbr table
$sSQL = "CREATE TABLE IF NOT EXISTS `typeofmbr` (
    `typeid` tinyint(4) NOT NULL,
    `Description` tinytext NOT NULL,
    PRIMARY KEY (`typeid`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
  
 $bval = RunQuery($sSQL);

 //echo "<script>alert('Create typeofmbr " . $bval . "');</script>";

 // may fail if server under hight load.  use UPDLOCK to correct.
$sSQL = "IF NOT EXISTS (SELECT * FROM typeofmbr LIMIT 1)
        THEN
            INSERT INTO `typeofmbr` (`typeid`, `Description`) VALUES (1, 'Business'), (2, 'Family'), (3, 'Person');
        END IF";

$bval = RunQuery($sSQL);

//echo "<script>alert('Alter person_per');</script>";
// add new colums to person_per table so we can import famly_fam data
$sSQL = "IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='person_per' AND column_name='fam_Familyname')
        THEN
                ALTER TABLE `person_per` ADD COLUMN (
                `fam_Name` varchar(50),
                `fam_WeddingDate` date,
                `fam_ScanCheck` text,
                `fam_ScanCredit` text,
                `fam_SendNewsLetter` enum('FALSE','TRUE') NOT NULL DEFAULT 'FALSE',
                `fam_DateDeactivated` date DEFAULT NULL,
                `fam_OkToCanvass` enum('FALSE','TRUE') NOT NULL DEFAULT 'FALSE',
                `fam_Canvasser` smallint(5) UNSIGNED NOT NULL DEFAULT '0',
                `fam_Latitude` double DEFAULT NULL,
                `fam_Longitude` double DEFAULT NULL,
                `typeofmbr` tinyint(3) DEFAULT NULL,
                `oldid` int(11) UNSIGNED NOT NULL),
                ADD CONSTRAINT `typeofmbr` FOREIGN KEY (`typeofmbr`) REFERENCES `typeofmbr` (`typeid`);
        END IF";

$bval = RunQuery($sSQL);

/*
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

DROP table typeofmbr;

DELETE FROM person_per WHERE per_LastName IS NULL;
*/

//echo "<script>alert('Update typeofmbr');</script>";
// update typeofmbr to 3, indicating Person
$sSQL = "IF NOT EXISTS (SELECT typeofmbr from person_per WHERE typeofmbr = 3 LIMIT 1) THEN
                UPDATE person_per
                SET typeofmbr = 3;
        END IF";
$bval = RunQuery($sSQL);

//echo "<script>alert('Copy family to members " . $bval . "');</script>";


// copy family to person_per table if no family's are already in person_per table
$sSQL = "IF NOT EXISTS (SELECT per_ID FROM person_per WHERE typeofmbr = 2 LIMIT 1)
        THEN
        INSERT INTO person_per (fam_Name, per_Address1, per_Address2, per_City, per_State, per_Zip, per_Country, 
                per_HomePhone, per_WorkPhone, per_CellPhone, per_Email, fam_WeddingDate, per_DateEntered, 
                per_DateLastEdited, per_EnteredBy, per_EditedBy, fam_scanCheck, fam_scanCredit, fam_SendNewsLetter, 
                fam_DateDeactivated, fam_OkToCanvass, fam_Canvasser, fam_Latitude, fam_Longitude, per_Envelope, oldid, typeofmbr)
        SELECT Fam_Name, fam_Address1, fam_Address2, fam_City, fam_State, fam_Zip, fam_Country, 
                fam_HomePhone, fam_WorkPhone, fam_CellPhone, fam_Email, fam_WeddingDate, fam_DateEntered, 
                fam_DateLastEdited, fam_EnteredBy, fam_EditedBy, fam_scanCheck, fam_scanCredit, fam_SendNewsLetter, 
                fam_DateDeactivated, fam_OkToCanvass, fam_Canvasser, fam_Latitude, fam_Longitude, fam_Envelope, fam_ID, 2 FROM family_fam;
        END IF";

$bval = RunQuery($sSQL);

// copy family to person_per table and update fam_ID of required tables

// pain points:
// canvassdata_can: can_famID
// church_location_person: person_id
// egive_egv: egv_famID
// email_message_pending_emp: ??
// email_recipient_pending_erp: ??
// events_event: primary_contact_person_id, secondary_contact_person_id
// event_attend: person_id
// family_custom: fam_ID.  move to person_custom: per_ID ???
// family_custom_master.  move to person_custom_master  ???
// family_fam: no change, keep old data
// note_nte: nte_per_ID, nte_fam_ID.  these field can be combined into one since fam and per tables will be combined
// paddlenum_pn: ??
// person2group2role_p2g2r: ??
// person2volunteeropp_p2vo: ??
// person_per: move family's to this table
// pledge_plg: plg_famID
// query_qry: qry_SQL data update
// tokens: reference_id
// typeofmbr: new table to hold type of member; 1-Business, 2-Family, 3-Person
// userconfig_ucfg: ucfg_per_id

// update related tables with new fam_ID!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!  need work here incase script is ran second time
$sSQL = "SELECT * FROM person_per WHERE typeofmbr = 2 AND oldid IS NOT NULL LIMIT 1;";
$rsCnt = RunQuery($sSQL);
  
if (mysqli_num_rows($rsCnt) > 0) {
      
        //echo "<script>alert('per_ID: " . mysqli_num_rows($rsCnt) . "');</script>";
        // select family's that have been imported
        $sSQL = "SELECT * from person_per WHERE typeofmbr = 2 and oldid IS NOT NULL;";

        $rsPerson = RunQuery($sSQL);

        extract(mysqli_fetch_array($rsPerson));

        //echo "<script>alert('Remaining Count: " . count($rsPerson) . "');</script>";
        // update fam_ID in other tables here with new per_ID assigned to imported families 
        while ($aRow = mysqli_fetch_array($rsPerson)) {
                
                $per_ID = $aRow['per_ID'];
                $oldid = $aRow['oldid'];
                
                
                //canvassdata_can: can_famID
                $sSQL = "UPDATE canvassdata_can
                        SET can_famID = $per_ID
                        WHERE can_famID = $oldid;";
                
                //RunQuery($sSQL);

                // egive_egv: egv_famID
                $sSQL = "UPDATE egive_egv
                        SET egv_famID = $per_ID
                        WHERE egv_famID = $oldid;";
                
                //RunQuery($sSQL);

                // family_custom: fam_ID
                $sSQL = "UPDATE family_custom
                SET fam_ID = $per_ID
                WHERE fam_ID = $oldid;";

                //RunQuery($sSQL);

                // note_nte: nte_per_ID, nte_fam_ID
                $sSQL = "UPDATE note_nte
                SET per_ID = $per_ID
                WHERE fam_ID = $oldid;";

                //RunQuery($sSQL);

                //pledge_plg: plg_famID
                $sSQL = "UPDATE pledge_plg
                SET plg_famID = $per_ID
                WHERE plg_famID = $oldid;";

                //RunQuery($sSQL);
        
    }
 
}
//RedirectUtils::Redirect('index.php');
header("Refresh: 3 url=index.php");
echo 'Update completed! Redirecting after 3 seconds.';
?>