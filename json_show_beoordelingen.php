<?php
include 'connect.php';
$return[] = array();
$get_student_ov = $_GET['ov'];
$get_show_beoordelingen = "SELECT proeve.proeve_naam, kerntaak.kerntaak_naam, werkproces.werkproces_naam, criterium.criterium_naam,
                    (SELECT normering.normering_id FROM normering WHERE normering.criterium_id = criterium.criterium_id AND normering.waarde = 1) as norm1,
                    (SELECT normering.normering_id FROM normering WHERE normering.criterium_id = criterium.criterium_id AND normering.waarde = 2) as norm2,
                    (SELECT normering.normering_id FROM normering WHERE normering.criterium_id = criterium.criterium_id AND normering.waarde = 3) as norm3,
                    (SELECT normering.normering_id FROM normering WHERE normering.criterium_id = criterium.criterium_id AND normering.waarde = 4) as norm4,
                    beoordeling.normering_id as beoordeling
                    FROM proeve
                    LEFT JOIN kerntaak ON proeve.proeve_id = kerntaak.proeve_id
                    LEFT JOIN werkproces ON kerntaak.kerntaak_id = werkproces.kerntaak_id
                    LEFT JOIN criterium ON werkproces.werkproces_id = criterium.werkproces_id
                    LEFT JOIN beoordeling ON criterium.criterium_id = beoordeling.criterium_id
                    WHERE proeve.proeve_id IN
                        (SELECT formulier.proeve_id FROM formulier WHERE formulier.student_ov = $get_show_beoordelingen);";
$result_show_beoordelingen = $conn->query($get_show_beoordelingen);
if ($result_show_beoordelingen->num_rows > 0) {
    $i = 0;
    while ($row_show_beoordelingen = $result_show_beoordelingen->fetch_assoc()) {
        $return_show_beoordelingen[$i]["proeve_naam"] = $row_show_beoordelingen["proeve.proeve_naam"];
        $return_show_beoordelingen[$i]["kerntaak_naam"] = $row_show_beoordelingen["kerntaak.kerntaak_naam"];
        $return_show_beoordelingen[$i]["werkproces_naam"] = $row_show_beoordelingen["werkproces.werkproces_naam"];
        $return_show_beoordelingen[$i]["criterium_naam"] = $row_show_beoordelingen["criterium.criterium_naam"];
        $return_show_beoordelingen[$i]["normering_1"] = $row_show_beoordelingen["norm1"];
        $return_show_beoordelingen[$i]["normering_2"] = $row_show_beoordelingen["norm2"];
        $return_show_beoordelingen[$i]["normering_3"] = $row_show_beoordelingen["norm3"];
        $return_show_beoordelingen[$i]["normering_4"] = $row_show_beoordelingen["norm4"];
        $return_show_beoordelingen[$i]["beoordeling"] = $row_show_beoordelingen["beoordeling"];
        $i++;
    }
}
echo json_encode($return_show_beoordelingen);
?>