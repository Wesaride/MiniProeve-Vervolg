
<div id="ModalAddNormering" class="modal" style="height:100%">
    <div class="modal-header" style="padding-left: 24px;">
        <h5>Normering toevoegen</h5>
    </div>
    <div class="modal-content">
            <label>Om een normering toe te voegen selecteert u eerst het criterium waar de normering onder valt. </label>
            <br><br>

            <!-- CODE VOOR CRITERIUM TOEVOEGEN BACK-END -->
            <?php
            $get_cohort_criterium = "SELECT * FROM cohort";
            $result_cohort_criterium = $conn->query($get_cohort_criterium);
            if ($result_cohort_criterium->num_rows > 0) {
                ?>
                <form method="POST">
                    <script>
                    </script>
                    <div id="ModalAddNormeringCohort"></div>
                    <div id="ModalAddNormeringProeve"></div>
                    <div id="ModalAddNormeringKerntaak"></div>
                    <div id="ModalAddNormeringWerkproces"></div>
                    <div id="ModalAddNormeringCriteria"></div>
                    
                    <textarea class="" id="normering_omsch" style="min-height: 100px;" rows="6" cols="20" name="normering_omsch" placeholder="Normering omschrijving" ></textarea><br><br>
                    <button class="btn waves-effect waves-light" type="submit" name="new_normering_submit">Versturen</button>
                    <a href="#!" class="modal-action modal-close waves-effect waves-green btn btn-success ">Sluiten</a>
                </form>
                <?php
            }
            if (isset($_POST['new_normering_submit'])) {
                if (isset($_POST['session_cohort'])) {
                    if (isset($_POST['session_proeve'])) {
                        if (isset($_POST['session_kerntaak'])) {
                            if (isset($_POST['session_werkproces'])) {
                                if (isset($_POST['session_criteria'])) {
                                    if (isset($_POST['normering_omsch'])) {
                                        $criterium_proeve = $_POST['modal_proeve'];
                                        $criterium_kerntaak = $_POST['modal_kerntaak'];
                                        $criterium_werkproces = $_POST['modal_werkproces'];
                                        $criterium_criteria = $_POST['modal_criteria'];
                                        $normering_omsch = $_POST['normering_omsch'];
                                        //$add_normering_sql = "INSERT INTO criterium_normering (normering_omsch, werkproces_criterium_id) VALUES ('" . $selected_cohort . "', '" . $selected_proeve . "', '" . $selected_kerntaak . "','" . $selected_werkproces . "','" . $selected_criteria . "','" . $normering_omsch . "')";
                                        //echo "<meta http-equiv='refresh' content='0'>";
                                        //if ($conn->query($add_normering_sql) === TRUE) {
                                        //    echo "Normering is toegevoegd";
                                        //} else {
                                        //    echo "Probeer opnieuw";
                                        //}
                                    } else {
                                        echo "Geen omschrijving ingevuld";
                                    }
                                }
                            }
                        }
                    }
                }
            }
            ?>       
            <!--EINDE CODE VOOR WERKPROCES TOEVOEGEN BACKEND + -->

    </div>
</div>