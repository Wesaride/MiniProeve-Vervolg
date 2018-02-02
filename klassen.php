<?php
include("check.php");
include("connect.php");
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>MiniProeve</title>
        <link type="text/css" rel="stylesheet" href="stylesheet.css">
        <link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <link type="text/css" rel="stylesheet" media="screen,projection" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/css/materialize.min.css" />
    </head>
    <body>
        <?php
        include("navbar.php");
        include("ModalAddKlas.php");
        include("ModalAddCohort.php");
        include("ModalEditKlas.php");
        include("ModalDeleteKlas.php");
        ?>
        <div class="row" style="margin-bottom: auto;">
            <div class="col s12 m4 l3 sidebar">
                <br>
                <button data-target="ModalAddCohort" class="btn modal-trigger" style="min-width: 200px; margin-left: 15px;">Cohort Toevoegen</button>

                <?php
                $error = '';
                $get_cohort = "SELECT * FROM cohort";
                $result_cohort = $conn->query($get_cohort);
                if ($result_cohort->num_rows > 0) {
                    ?>
                    <select name="selected_cohort" required>
                        <option selected="selected" disabled>Kies een Cohort</option>
                        <?php
                        while ($row_cohort = $result_cohort->fetch_assoc()) {
                            ?>
                            <option value="<?php echo $row_cohort["cohort_id"] ?>"><?php echo $row_cohort["cohort_jaar"] ?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <?php
                }
                ?>
            </div>
            <div class="col s12 m8 l9">
                <h4>Overzicht klassen<a data-target="ModalAddKlas" class="btn-floating btn-small waves-effect waves-light green btn modal-trigger"><i class="material-icons" >add</i></a></h4>
                <table id="show_klas" class="hide">
                    <thead>
                        <tr>
                            <th>Klas</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody name="tbody">

                    </tbody>
                </table>
            </div>
        </div>
        <!--EINDE CODE VOOR KLAS TOEVOEGEN BACKEND -->
        <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/js/materialize.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                // the "href" attribute of .modal-trigger must specify the modal ID that wants to be triggered
                $(".modal-trigger").leanModal();
                $("select").material_select();
                $(".button-collapse").sideNav();
                $(".lean-overlay").click(function(){ $("div.lean-overlay").remove(); });

                $("select[name=selected_cohort]").on('change', function () {
                    cohort_id = this.value;
                    //alert(cohort_id);

                    //Leegmaken ingevulde shit komt hier
                    $("tbody[name=tbody]").empty();

                    // ophalen van informatie, met ajax
                    $.ajax({
                        type: 'GET',
                        url: 'json_show_klas.php',
                        data: {id: cohort_id},
                        dataType: 'json',
                        success: function (data) {
                            //alert(data);
                            $.each(data, function (index, element) {
                                $("#show_klas").find('tbody')
                                    .append($('<tr>', {id: element.klas_id})
                                        .append($('<td>', {text: element.klas_name},))
                                        .append($('<td><a data-target="ModalEditKlas" class="EditKlas btn-floating btn-large waves-effect waves-light yellow btn modal-trigger2"><i class="material-icons" >edit</i></a>'))
                                        .append($('<td><a data-target="ModalDeleteKlas" class="DeleteKlas btn-floating btn-large waves-effect waves-light red btn modal-trigger2"><i class="material-icons">delete</i></a>'))
                                    );
                                $("#show_klas").removeClass("hide");
                            });
                            
                            // modal-trigger verandert naar modal-trigger2 zodat modal-trigger niet meerdere overlays creert.
                            $(".modal-trigger2").leanModal();
                            // Edit button
                                $(".EditKlas").on('click', function () {
                                    // waarde van het geselecteerde id ophalen
                                    id_klas = $(this).parent().parent().attr('id');
                                    //console.log(id_student);

                                    // Velden leeg maken
                                    document.getElementById("klas_id").value = "";
                                    document.getElementById("klas_naam").value = "";

                                    // ophalen van informatie, met ajax om naam/omschrijving kerntaak op te halen
                                    $.ajax({
                                        type: 'GET',
                                        url: 'json_edit_klas.php',
                                        data: {id: id_klas},
                                        dataType: 'json',
                                        success: function (data) {
                                            $("#klas_id").val(data.id);
                                            $("#klas_naam").val(data.name);
                                            $("#klas_naam").removeClass("hide");
                                        },
                                        error: function () {
                                            console.log('error');
                                        }
                                    });
                                });
                                
                                // DELETE BUTTON
                                $(".DeleteKlas").on('click', function () {
                                    // ophalen van het id
                                    var klas_id = $(this).parent().parent().attr('id');
                                    console.log(klas_id);
                                    // link aanpassen
                                    $("#delhref").attr("href", "delete_klas.php?id=" + klas_id);
                                });
                            
                        }
                    });
                });
            });
        </script>
    </body>
</html>