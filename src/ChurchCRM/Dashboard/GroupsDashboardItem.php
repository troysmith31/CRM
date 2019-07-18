<?php

namespace ChurchCRM\Dashboard;

use ChurchCRM\Dashboard\DashboardItemInterface;
use Propel\Runtime\Propel;
use ChurchCRM\Utils\LoggerUtils;

class GroupsDashboardItem implements DashboardItemInterface {

  public static function getDashboardItemName() {
    return "GroupsDisplay";
  }

  public static function getDashboardItemValue() {
       $sSQL = 'select
        (select count(*) from group_grp) as Groups,
        (select count(*) from group_grp where grp_Type = 4 ) as SundaySchoolClasses,
        (Select count(*) from person_per
          INNER JOIN person2group2role_p2g2r ON p2g2r_per_ID = per_ID
          INNER JOIN group_grp ON grp_ID = p2g2r_grp_ID
          LEFT JOIN family_fam ON fam_ID = per_fam_ID
          where family_fam.fam_DateDeactivated is null and
	            p2g2r_rle_ID = 2 and grp_Type = 4) as SundaySchoolKidsCount
        from dual ;
        ';
        $conn = Propel::getConnection(); 
        $stmt = $conn->prepare($sSQL);
        $stmt->execute();
        $rsQuickStat = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($rsQuickStat as $row) {
          $data = ['groups' => $row['Groups'] - $row['SundaySchoolClasses'], 'sundaySchoolClasses' => $row['SundaySchoolClasses'], 'sundaySchoolkids' => $row['SundaySchoolKidsCount']];
        }

        return $data;
  }

  public static function shouldInclude($PageName) {
    return $PageName=="/Menu.php";
  }

}