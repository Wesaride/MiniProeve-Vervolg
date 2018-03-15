<?php
//TODO heeft dropdown menus voor cohort en proeve nodig
include("check.php");
include("connect.php");

if (isset($_POST['post_kerntaak'])){
    $_SESSION['session_kerntaak'] = $_POST['post_kerntaak'];
}
if (isset($_POST['post_werkproces'])){
    $_SESSION['session_werkproces'] = $_POST['post_werkproces'];
}
$session_kerntaak = $session_werkproces = "";
if (isset($_SESSION['session_kerntaak'])){
    $session_kerntaak = $_SESSION['session_kerntaak'];
}
if (isset($_SESSION['session_werkproces'])){
   $session_werkproces = $_SESSION['session_werkproces'];
}
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
        include("ModalAddCriterium.php");
        include("ModalEditCriterium.php");
        include("ModalDeleteCriterium.php");
        ?>
        <div class="row" style="margin-bottom: auto;">
            <div class="col s12 m4 l3 sidebar">
                <br />
                <?php
                $get_kerntaak = "SELECT * FROM kerntaak";
                $result_kerntaak = $conn->query($get_kerntaak);
                if ($result_kerntaak->num_rows > 0) {
                    ?>
                    <select name="selected_kerntaak" required>
                        <?php
                        if (isset($_SESSION['session_kerntaak'])){
                            echo '<option disabled>Kies een kerntaak</option>';
                        }
                        else{
                            echo "<option selected='selected' disabled>Kies een kerntaak</option>";
                        }
                        while ($row_kerntaak = $result_kerntaak->fetch_assoc()) {
                            $selectedvalue = "";
                            if (isset($_SESSION['session_kerntaak'])){
                                if ($_SESSION['session_kerntaak'] == $row_kerntaak['kerntaak_id']){
                                    $selectedvalue = "selected='selected'";
                                }
                            }
                            echo "<option " . $selectedvalue . " value=" . $row_kerntaak['kerntaak_id'] . ">" . $row_kerntaak['kerntaak_naam'] . "</option>";
                        }
                        ?>
                    </select>
                    <?php
                }
                ?>
                <select name="selected_werkproces" class="hide">

                </select><br />
                <select name="selected_criteria" class="hide">

                </select><br />
            </div>
            <div style="overflow: scroll; height: 85%" class="col s12 m8 l9">
                <h4>Overzicht criteria
                    <a data-target="ModalAddCriterium" class="btn-floating btn-small waves-effect waves-light green btn modal-trigger">
                        <i class="material-icons" >add</i>
                    </a>
                </h4>

                <table id="show_criterium" class="hide">
                    <thead>
                        <tr>
                            <th>Criterium</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody name="tbody">


                    </tbody>
                </table>
                <table id="geen_resultaten" class="hide">
                    <thead>
                        <tr>
                            <th>Geen resultaten</th>
                        </tr>
                    </thead>
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

                //Haal werkprocessen voor kerntaak op
                $("select[name=selected_kerntaak]").on('change', function () {
                    // waarde van geslecteerde id ophalen
                    kerntaak_id = this.value;
                    $.post('criterium.php', {post_kerntaak: kerntaak_id});
                    //alert(kerntaak_id);

                    // Alles leeg maken:
                    $("select[name=selected_werkproces]").empty().append($('<option>', {
                        value: 0,
                        text: "Kies een werkproces",
                    }));

                    // ophalen van informatie, met ajax
                    $.ajax({
                        type: 'GET',
                        url: 'json_show_werkproces.php',
                        data: {id: kerntaak_id},
                        dataType: 'json',
                        success: function (data) {
                            //alert(data);
                            $.each(data, function (index, element) {
                                //console.log(element.name);
                                //sestype = typeof('<?php //echo $session_werkproces?>');
                                //dbtype = typeof(element.werkproces_id);
                                //alert("types " + sestype + dbtype);
                                //alert(<?php //echo $session_werkproces?> + element.werkproces_id);
                                if ('<?php echo $session_werkproces?>' === element.werkproces_id){
                                    $("select[name=selected_werkproces]").append($('<option>', {
                                        value: element.werkproces_id,
                                        text: element.werkproces_name,
                                        selected: 1
                                    }));
                                }
                                else{
                                    $("select[name=selected_werkproces]").append($('<option>', {
                                        value: element.werkproces_id,
                                        text: element.werkproces_name
                                    }));
                                }
                            });
                            // toepassen css
                            // ** material only! **
                            $("select[name=selected_werkproces]").material_select();
                            // als alles is opgehaald. Select weer laten zien.
                            $("select[name=selected_werkproces]").closest('.select-wrapper').removeClass("hide");
                            if (!$("table[id=geen_resultaten]").hasClass("hide")){
                                //als het "geen resultaten" scherm bestaat. Verstop het.
                                $("table[id=geen_resultaten]").addClass("hide");
                            }
                            <?php
                            if (isset($_SESSION['session_werkproces'])){
                            ?>
                                if (typeof(<?php echo $_SESSION['session_werkproces']?>) !== "undefined"){
                                    $("select[name=selected_werkproces]").trigger('change');
                                    //alert('jahoor');
                                }
                            <?php
                            }
                            ?>
                        },
                        error: function () {
                            //drop menu content is leeg
                            //alert('error');
                            //laat zien op de pagina dat er geen resultaten zijn
                            if (!$("table[id=show_criterium]").hasClass("hide")){
                                //haalt overzicht criterium weg
                                $("table[id=show_criterium]").addClass("hide");
                            }
                            $("table[id=geen_resultaten]").removeClass("hide");
                            //hide de andere dropdown menus
                            if (!$("select[name=selected_werkproces]").closest('.select-wrapper').hasClass("hide")){
                                $("select[name=selected_werkproces]").closest('.select-wrapper').addClass("hide");
                            }
                            //if (!$("select[name=selected_criteria]").closest('.select-wrapper').hasClass("hide")){
                            //    $("select[name=selected_criteria]").closest('.select-wrapper').addClass("hide");
                            //}
                        }
                    });
                });
                <?php
                if (isset($_SESSION['session_kerntaak'])){
                ?>
                    if (typeof(<?php echo $_SESSION['session_kerntaak']?>) !== "undefined"){
                        $("select[name=selected_kerntaak]").trigger('change');
                    }
                <?php
                }
                ?>

                $("select[name=selected_werkproces]").on("change", function () {
                    werkproces_id = this.value;
                    $.post('criterium.php', {post_werkproces: werkproces_id});
                    //alert(werkproces_id);

                    // Table overzicht leegmaken voordat er nieuwe data ingeladen wordt.
                    $("tbody[name=tbody]").empty();

                    // Ophalen informatie met Ajax
                    $.ajax({
                        type: 'GET',
                        url: 'json_show_criterium.php',
                        data: {id: werkproces_id},
                        dataType: 'json',
                        success: function (data) {
                            $.each(data, function (index, element) {
                                //console.log(element.criterium_id, element.criterium_naam);
                                $("#show_criterium").find('tbody')
                                    .append($('<tr>', {id: element.criterium_id})
                                        .append($('<td>', {text: element.criterium_naam},))
                                        .append($('<td><button data-target="ModalEditCriterium" name="EditCriterium" class="EditCriterium btn-floating btn-large waves-effect waves-light yellow btn modal-trigger2"><i class="material-icons" >edit</i></button>'))
                                        .append($('<td><button data-target="ModalDeleteCriterium" name="DeleteCriterium" class="DeleteCriterium btn-floating btn-large waves-effect waves-light red btn modal-trigger2"><i class="material-icons">delete</i></button>'))
                                    );
                                //$("select[name=criteria]").material_select();
                                //$("select[name=selected_criteria]").show();
                                if (!$("table[id=geen_resultaten]").hasClass("hide")){
                                //als het "geen resultaten" scherm bestaat. Verstop het.
                                $("table[id=geen_resultaten]").addClass("hide");
                            }
                                $("table[id=show_criterium]").removeClass("hide");
                            });
                            $(".modal-trigger2").leanModal();


                            // Edit criteria
                            $(".EditCriterium").on('click', function () {
                                // waarde van het geselecteerde id ophalen
                                id_criterium = $(this).parent().parent().attr('id');
                                //alert(id_criterium);


                                // Velden leeg maken
                                document.getElementById("criterium_id").value = "";
                                document.getElementById("criterium_naam").value = "";

                                // ophalen van informatie, met ajax om naam/omschrijving kerntaak op te halen
                                $.ajax({
                                    type: 'GET',
                                    url: 'json_edit_criterium.php',
                                    data: {id: id_criterium},
                                    dataType: 'json',
                                    success: function (data) {
                                        //console.log(data);
                                        $("#criterium_id").val(data.id);
                                        $("#criterium_naam").val(data.name);
                                        $("#criterium_naam").removeClass("hide");
                                    }
                                });
                            });

                            // Delete criteria
                            $(".DeleteWerkproces").on('click', function () {
                                // ophalen van het id
                                var werkproces_criterium_id = $(this).parent().parent().attr('id');
                                //console.log(werkproces_criterium_id);

                                // link aanpassen
                                $("#delhref").attr("href", "delete_criterium.php?id=" + werkproces_criterium_id);
                            });
                        },
                        error: function () {
                            if (!$("table[id=show_criterium]").hasClass("hide")){
                                //haalt overzicht normering weg
                                $("table[id=show_criterium]").addClass("hide");
                            }
                            $("table[id=geen_resultaten]").removeClass("hide");
                        }
                    });
                });

                // Add criteria
                $("select[name=kerntaak_criterium_option]").on('change', function () {
                    // waarde van geslecteerde id ophalen
                    kt = this.value;
                    //alert(kt);

                    // Alles leeg maken:
                    $("select[name=werkproces_criterium_option]").empty().append($('<option>', {
                        value: 0,
                        text: "Kies een werkproces"
                    }));

                    // ophalen van informatie, met ajax
                    $.ajax({
                        type: 'GET',
                        url: 'json_add_criterium.php',
                        data: {id: kt},
                        dataType: 'json',
                        success: function (data) {
                            //alert(data);
                            $.each(data, function (index, element) {
                                //console.log(element.name);
                                $("select[name=werkproces_criterium_option]").append($('<option>', {
                                    value: element.id,
                                    text: element.name
                                }));
                            });
                            // toepassen css
                            // ** material only! **
                            $("select[name=werkproces_criterium_option]").material_select();
                            // als alles is opgehaald. Select weer laten zien.
                            //$("select[name=werkproces]").show();
                            $("select[name=werkproces_criterium_option]").closest('.select-wrapper').removeClass("hide");
                        }
                    });
                });
                $("select[name=werkproces_criterium_option]").on('change', function () {
                    $("input[name=criterium_oms]").removeClass("hide");
                    $("button[name=new_criterium_submit]").removeClass("hide");
                });


            });
        </script>
    </body>
</html>
