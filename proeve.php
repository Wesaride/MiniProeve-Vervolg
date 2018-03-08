<?php
include("check.php");
include("connect.php");

if (isset($_POST['post_cohort'])){
    $_SESSION['session_cohort'] = $_POST['post_cohort'];
}
$session_cohort = "";
if (isset($_SESSION['session_cohort'])){
   $session_cohort = $_SESSION['session_cohort'];
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
        include("ModalAddProeve.php");
        include("ModalEditProeve.php");
        include("ModalDeleteProeve.php");
        ?>
        <div class="row" style="margin-bottom: auto;">
            <div class="col s12 m4 l3" style="background-color: gray; height: 100%;">
                <br>
                <?php
                $get_cohort = "SELECT * FROM cohort";
                $result_cohort = $conn->query($get_cohort);
                if ($result_cohort->num_rows > 0) {
                    ?>
                    <select name="selected_cohort" required>
                        <?php
                        if (isset($_SESSION['session_cohort'])){
                            echo '<option disabled>Kies een cohort</option>';
                        }
                        else{
                            echo "<option selected='selected' disabled>Kies een cohort</option>";
                        }
                        while ($row_cohort = $result_cohort->fetch_assoc()) {
                            $selectedvalue = "";
                            if (isset($_SESSION['session_cohort'])){
                                if ($_SESSION['session_cohort'] == $row_cohort['cohort_id']){
                                    $selectedvalue = "selected='selected'";
                                }
                            }
                            echo "<option " . $selectedvalue . " value=" . $row_cohort['cohort_id'] . ">" . $row_cohort['cohort_jaar'] . "</option>";
                        }
//                        while ($row_cohort = $result_cohort->fetch_assoc()) {
                            ?>
                            <?php
//                        }
                        ?>
                    </select>
                <?php
                }
                ?>
            </div>
            <div class="col s12 m8 l9" style="overflow: scroll; height: 85%;">
                <h4>Overzicht Proeve<a data-target="ModalAddProeve" class="btn-floating btn-small waves-effect waves-light green btn modal-trigger"><i class="material-icons" >add</i></a></h4>
                <table id="show_proeve" class="hide">
                    <thead>
                        <tr>
                            <th>proeve</th>
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
        <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/js/materialize.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                // the "href" attribute of .modal-trigger must specify the modal ID that wants to be triggered
                $('.modal-trigger').leanModal();
                $('select').material_select();
                $(".button-collapse").sideNav();
                
                $("select[name=selected_cohort]").on('change', function () {
                    proeve_id = this.value;
                    $.post('proeve.php', {post_cohort: proeve_id});
                    $("tbody[name=tbody]").empty();
                    // ophalen van informatie, met ajax
                    
                    $.ajax({
                        type: 'GET',
                        url: 'json_show_proeve.php',
                        data: {id: proeve_id},
                        dataType: 'json',
                        success: function (data) {
                            //alert(data);
                            $.each(data, function (index, element) {
                                $("#show_proeve").find('tbody')
                                    .append($('<tr>', {id: element.proeve_id})
                                        .append($('<td>', {text: element.proeve_name}, ))
                                        .append($('<td>', {text: element.proeve_email}, ))
                                        .append($('<td><button data-target="ModalEditproeve" class="Editproeve btn-floating btn-large waves-effect waves-light yellow btn modal-trigger2"><i class="material-icons" >edit</i></button>'))
                                        .append($('<td><button data-target="ModalDeleteproeve" class="Deleteproeve btn-floating btn-large waves-effect waves-light red btn modal-trigger2"><i class="material-icons">delete</i></button>'))
                                    );
                                $("#show_proeve").removeClass("hide");
                                if (!$("table[id=geen_resultaten]").hasClass("hide")){
                                    //als het "geen resultaten" scherm bestaat. Verstop het.
                                    $("table[id=geen_resultaten]").addClass("hide");
                                }
                            });
                            $(".modal-trigger2").leanModal();
                            // Edit button
                            $("button[name=EditProeve]").on('click', function () {
                                // waarde van het geselecteerde id ophalen
                                id_proeve = $(this).data("id");
                                // Velden leeg maken
                                document.getElementById("proeve_id").value = "";
                                document.getElementById("proeve_naam").value = "";
                                // ophalen van informatie, met ajax om naam/omschrijving cohort op te halen
                                $.ajax({
                                type: 'GET',
                                    url: 'json_edit_proeve.php',
                                    data: {id: id_proeve},
                                    dataType: 'json',
                                    success: function (data) {
                                        $("#proeve_id").val(data.id);
                                        $("#proeve_naam").val(data.name);
                                        $("#proeve_naam").removeClass("hide");
                                    }
                                });
                            });
                            // DELETE BUTTON
                            $("button[name=DeleteProeve]").click(function (event) {
                                event.preventDefault();
                                // ophalen van het id
                                var proeve_id = $(this).data("id");
                                //alert(proeve_id);
                                // link aanpassen
                               $("#delhref").attr("href", "delete_proeve.php?id=" + proeve_id);
                            });
                        },
                        error: function () {
                            if (!$("table[id=show_proeve]").hasClass("hide")){
                                //haalt overzicht proeve weg
                                $("table[id=show_proeve]").addClass("hide");
                            }
                            //laat "geen resultaten" tab zien
                            $("table[id=geen_resultaten]").removeClass("hide");
                        }
                    });
                });
                <?php
                if (isset($_SESSION['session_cohort'])){
                ?>
                    if (typeof(<?php echo $_SESSION['session_cohort']?>) !== "undefined"){
                        $("select[name=selected_cohort]").trigger('change');
                    }
                <?php
                }
                ?>
            });
        </script>
    </body>
</html>
