<?php
include("check.php");
include("connect.php");

if (isset($_POST['post_cohort'])){
    $_SESSION['session_cohort'] = $_POST['post_cohort'];
}
if (isset($_POST['post_proeve'])){
    $_SESSION['session_proeve'] = $_POST['post_proeve'];
}
if (isset($_POST['post_kerntaak'])){
    $_SESSION['session_kerntaak'] = $_POST['post_kerntaak'];
}

$session_cohort = $session_proeve = $session_kerntaak = $session_werkproces = $session_criteria = "";

if (isset($_SESSION['session_cohort'])){
    $session_cohort = $_SESSION['session_cohort'];
}
if (isset($_SESSION['session_proeve'])){
    $session_proeve = $_SESSION['session_proeve'];
}
if (isset($_SESSION['session_kerntaak'])){
    $session_kerntaak = $_SESSION['session_kerntaak'];
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
        include("ModalAddWerkproces.php");
        include("ModalEditWerkproces.php");
        include("ModalDeleteWerkproces.php");
        ?>
        <div class="row" style="margin-bottom: auto;">
            <div class="col s12 m4 l3" style="background-color: gray; height: 100%;">
                <br>
                <?php
                $session_cohort = "";
                if (isset($_SESSION['session_cohort'])){
                    $session_cohort = $_SESSION['session_cohort'];
                }
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
                            if (isset($_SESSION['session_cohort'])){
                                if ($_SESSION['session_cohort'] == $row_kerntaak['cohort_id']){
                                    $selectedvalue = "selected='selected'";
                                }
                                else{
                                    $selectedvalue = "";
                                }
                            }
                            echo "<option " . $selectedvalue . " value=" . $row_cohort['cohort_id'] . ">" . $row_cohort['cohort_jaar'] . "</option>";
                        }
                        ?>
                    </select>
                    <?php
                }
                ?>
            </div>
            <div class="col s12 m8 l9">
                <h4>Overzicht werkprocessen <a data-target="ModalAddWerkproces" class="btn-floating btn-small waves-effect waves-light green btn modal-trigger"><i class="material-icons" >add</i></a></h4>
                <table id="show_werkproces" class="hide">
                    <thead>
                        <tr>
                            <th>Werkprocesnaam</th>
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
        <script src="http://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/js/materialize.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                // the "href" attribute of .modal-trigger must specify the modal ID that wants to be triggered
                $('.modal-trigger').leanModal();
                $('select').material_select();
                $(".button-collapse").sideNav();
                
                //select dropdown cohorten
                $("select[name=selected_cohort]").on('change', function () {
                    cohort_id = this.value;
                    $.post('werkprocessen.php',{post_cohort: cohort_id});
                    
                    $.ajax({
                        type: 'GET',
                        url: 'json_show_proeve.php',
                        data: {id: cohort_id},
                        dataType: 'json',
                        success: function (data){
                            $.each(data, function (index, element) {
                                //creer de content voor de dropdown menu voor werkprocessen
                                if ('<?php echo $session_proeve?>' === element.proeve_id){
                                    $("select[name=selected_proeve]").append($('<option>', {
                                        value: element.proeve_id,
                                        text: element.proeve_name,
                                        selected: 1
                                    }));
                                }
                                else{
                                    $("select[name=selected_proeve]").append($('<option>', {
                                        value: element.proeve_id,
                                        text: element.proeve_name
                                    }));
                                }
                            });
                            
                            // toepassen css
                            $("select[name=selected_proeve]").material_select();
                            $("select[name=selected_kerntaak]").material_select();
                            
                            // als alles is opgehaald, volgende dropdown laten zien (hide weghalen)
                            $("select[name=selected_proeve]").closest('.select-wrapper').removeClass("hide");
                            
                            //als het "geen resultaten" scherm bestaat. Verstop het.
                            if (!$("table[id=geen_resultaten]").hasClass("hide")){
                                $("table[id=geen_resultaten]").addClass("hide");
                            }
                            
                            //trigger het laten zien van de volgende lijst alleen als deze gevuld is. Wouter heeft hierbij geholpen.
                      <?php if (isset($_SESSION['session_proeve'])){ ?>
                                if (typeof(<?php echo $_SESSION['session_proeve']?>) !== "undefined"){
                                    $("select[name=selected_proeve]").trigger('change');
                                    //alert('jahoor');
                                }
                      <?php } ?>
                        },
                        error: function (){
                            //laat zien op de pagina dat er geen resultaten zijn
                            $("table[id=geen_resultaten]").removeClass("hide");
                            
                            //hide de andere dropdown menus
                            if (!$("select[name=selected_proeve]").closest('.select-wrapper').hasClass("hide")){
                                $("select[name=selected_proeve]").closest('.select-wrapper').addClass("hide");
                            }
                            if (!$("select[name=selected_kerntaak]").closest('.select-wrapper').hasClass("hide")){
                                $("select[name=selected_kerntaak]").closest('.select-wrapper').addClass("hide");
                            }
                        }
                    });
                });
                
                //select dropdown proeve, show kerntaken
                $("select[name=selected_proeve]").on('change', function () {
                    proeve_id = this.value;
                    $.post('werkprocessen.php',{post_proeve: proeve_id});
                    
                    $.ajax({
                        type: 'GET',
                        url: 'json_show_kerntaak.php',
                        data: {id: proeve_id},
                        dataType: 'json',
                        success: function (data){
                            $.each(data, function (index, element) {
                                //creer de content voor de dropdown menu voor kerntaken
                                if ('<?php echo $session_kerntaak?>' === element.kerntaak_id){
                                    $("select[name=selected_kerntaak]").append($('<option>', {
                                        value: element.kerntaak_id,
                                        text: element.kerntaak_name,
                                        selected: 1
                                    }));
                                }
                                else{
                                    $("select[name=selected_kerntaak]").append($('<option>', {
                                        value: element.kerntaak_id,
                                        text: element.kerntaak_name
                                    }));
                                }
                            });
                            
                            // toepassen css
                            $("select[name=selected_kerntaak]").material_select();
                            
                            // als alles is opgehaald, volgende dropdown laten zien (hide weghalen)
                            $("select[name=selected_kerntaak]").closest('.select-wrapper').removeClass("hide");
                            
                            //als het "geen resultaten" scherm bestaat. Verstop het.
                            if (!$("table[id=geen_resultaten]").hasClass("hide")){
                                $("table[id=geen_resultaten]").addClass("hide");
                            }
                            
                            //trigger het laten zien van de volgende lijst alleen als deze gevuld is. Wouter heeft hierbij geholpen.
                      <?php if (isset($_SESSION['session_kerntaak'])){ ?>
                                if (typeof(<?php echo $_SESSION['session_kerntaak']?>) !== "undefined"){
                                    $("select[name=selected_kerntaak]").trigger('change');
                                    //alert('jahoor');
                                }
                      <?php } ?>
                        },
                        error: function (){
                            //laat zien op de pagina dat er geen resultaten zijn
                            $("table[id=geen_resultaten]").removeClass("hide");
                            
                            //hide de andere dropdown menus
                            if (!$("select[name=selected_kerntaak]").closest('.select-wrapper').hasClass("hide")){
                                $("select[name=selected_kerntaak]").closest('.select-wrapper').addClass("hide");
                            }
                        }
                    });
                });
                
                //Select  
                $("select[name=selected_werkproces]").on('change', function () {
                    kerntaak_id = this.value;
                    $.post('normering.php', {post_kerntaak: kerntaak_id});
                    //alert(kerntaak_id);

                    $("tbody[name=tbody]").empty();

                    // ophalen van informatie, met ajax
                    $.ajax({
                        type: 'GET',
                        url: 'json_show_werkproces.php',
                        data: {id: kerntaak_id},
                        dataType: 'json',
                        success: function (data) {
                            //console.log(data);
                            $.each(data, function (index, element) {
                                $("#show_werkproces").find('tbody')
                                    .append($('<tr>', {id: element.id})
                                        .append($('<td>', {text: element.name},))
                                        .append($('<td><button data-target="ModalEditWerkproces" class="EditWerkproces btn-floating btn-large waves-effect waves-light yellow btn modal-trigger2"><i class="material-icons" >edit</i></button>'))
                                        .append($('<td><button data-target="ModalDeleteWerkproces" class="DeleteWerkproces btn-floating btn-large waves-effect waves-light red btn modal-trigger2"><i class="material-icons">delete</i></button>'))
                                    );
                                //$('#show_klas').append($('<td>', {value: element.klas_id, text: element.name}, '</td>'));
                                $("#show_werkproces").removeClass("hide");
                                if (!$("table[id=geen_resultaten]").hasClass("hide")){
                                    //als het "geen resultaten" scherm bestaat. Verstop het.
                                    $("table[id=geen_resultaten]").addClass("hide");
                                }
                            });
                            $(".modal-trigger2").leanModal();

                            //Edit button Werkproces
                            $(".EditWerkproces").on('click', function () {
                                // waarde van het geselecteerde id ophalen
                                id_werkproces = $(this).parent().parent().attr('id');
                                //alert(id_werkproces);

                                // Velden leeg maken
                                document.getElementById("werkproces_id").value = "";
                                document.getElementById("werkproces_naam").value = "";

                                // ophalen van informatie, met ajax om naam/omschrijving werkproces op te halen
                                $.ajax({
                                    type: 'GET',
                                    url: 'json_edit_werkproces.php',
                                    data: {id: id_werkproces},
                                    dataType: 'json',
                                    success: function (data) {
                                        $("#werkproces_id").val(data.id);
                                        $("#werkproces_naam").val(data.name);
                                        $("#werkproces_naam").removeClass("hide");
                                    }
                                });
                            });
                            
                            // Delete button Werkproces
                            $(".DeleteWerkproces").on('click', function () {
                                
                                // ophalen van het id
                                var werkproces_id = $(this).parent().parent().attr('id');
                                //console.log(werkproces_id);
                                // link aanpassen
                                $("#delhref").attr("href", "delete_werkproces.php?id=" + werkproces_id);
                            });
                        },
                        error: function () {
                            if (!$("table[id=show_werkproces]").hasClass("hide")){
                                //haalt overzicht werkproces weg
                                $("table[id=show_werkproces]").addClass("hide");
                            }
                            //laat "geen resultaten" tab zien
                            $("table[id=geen_resultaten]").removeClass("hide");
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
            });
        </script>
    </body>
</html>
