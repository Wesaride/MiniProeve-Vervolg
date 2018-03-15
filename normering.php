<?php
include("check.php");
include("connect.php");
        
//dit opent de sessions
if (isset($_POST['post_cohort'])){
    $_SESSION['session_cohort'] = $_POST['post_cohort'];
}
if (isset($_POST['post_proeve'])){
    $_SESSION['session_proeve'] = $_POST['post_proeve'];
}
if (isset($_POST['post_kerntaak'])){
    $_SESSION['session_kerntaak'] = $_POST['post_kerntaak'];
}
if (isset($_POST['post_werkproces'])){
    $_SESSION['session_werkproces'] = $_POST['post_werkproces'];
}
if (isset($_POST['post_criteria'])){
    $_SESSION['session_criteria'] = $_POST['post_criteria'];
}
//voor het resetten van een session
//$_SESSION['session_cohort'] = $_POST['1'];
//$_SESSION['session_proeve'] = $_POST['1'];
//$_SESSION['session_kerntaak'] = $_POST['1'];
//$_SESSION['session_werkproces'] = $_POST['1'];
//$_SESSION['session_criteria'] = $_POST['1'];


//bereid session locals voor
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
if (isset($_SESSION['session_werkproces'])){
    $session_werkproces = $_SESSION['session_werkproces'];
}
if (isset($_SESSION['session_criteria'])){
    $session_criteria = $_SESSION['session_criteria'];
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
        include("ModalAddNormering.php");
        include("ModalEditNormering.php");
        include("ModalDeleteNormering.php");
        ?>
        <div class="row">
            <div class="col s12 m4 l3 sidebar">
                <br />
                <?php
                //een van de dropdown menu's wordt gedaan met php, de rest met javascript (vraag me niet waarom)
                $get_cohort = "SELECT * FROM cohort";
                $result_cohort = $conn->query($get_cohort);
                if ($result_cohort->num_rows > 0) {
                    ?>
                    <select name="selected_cohort" class="sidebar_dropwdown" id="selected_cohort" required>
                        <?php
                        //als de session variable gelijk is aan het dropdown menu, selecteer het d an
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
                        ?>
                    </select>
                    <?php
                }
                //dropdown menu's worden hieronder aangemaakt en worden gevuld in javascript
                ?>
                <select name="selected_proeve" class="sidebar_dropwdown hide" id="selected_proeve">

                </select>
                <select name="selected_kerntaak" class="sidebar_dropwdown hide" id="selected_kerntaak">

                </select>
                <select name="selected_werkproces" class="sidebar_dropwdown hide" id="selected_werkproces">

                </select>
                <select name="selected_criteria" class="sidebar_dropwdown hide" id="selected_criteria">

                </select><br />
            </div>
            <div style="overflow: scroll; height: 85%" class="col s12 m8 l9">
                <h4>Overzicht normeringen <a data-target="ModalAddNormering" id="AddNormering" class="btn-floating btn-small waves-effect waves-light green btn modal-trigger hide"><i class="material-icons" >add</i></a></h4>
                <?php
//                laat de session variables zien. Is voor testing
//                if (isset($_SESSION['session_cohort'])){
//                    echo "A" . $_SESSION['session_cohort'];
//                    
//                }
//                if (isset($_SESSION['session_proeve'])){
//                    echo "B" . $_SESSION['session_proeve'];
//                    
//                }
//                if (isset($_SESSION['session_kerntaak'])){
//                    echo "C" . $_SESSION['session_kerntaak'];
//                    
//                }
//                if (isset($_SESSION['session_werkproces'])){
//                    echo "D" . $_SESSION['session_werkproces'];
//                    
//                }
//                if (isset($_SESSION['session_criteria'])){
//                    echo "E" . $_SESSION['session_criteria'];
//                    
//                }
                ?>
                <table id="show_normering" class="hide">
                    <thead>
                        <tr>
                            <th>Normering</th>
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

                //cohort is veranderd, zoek naar proeven en vul de dropdown menu ermee, dit wordt automatisch getriggerd als de session_cohort session variable bestaat.
                $("select[name=selected_cohort]").on('change', function () {
                    // waarde van geslecteerde id ophalen
                    cohort_id = this.value;
                    $.post('normering.php', {post_cohort: cohort_id});
                    // proeve dropdown menu leeg maken:
                    $("select[name=selected_proeve]").empty().append($('<option>', {
                        value: 0,
                        text: "Kies een proeve"
                    }));
                    //hide de andere dropdown menus
                    if (!$("select[name=selected_kerntaak]").closest('.select-wrapper').hasClass("hide")){
                        $("select[name=selected_kerntaak]").closest('.select-wrapper').addClass("hide");
                    }
                    if (!$("select[name=selected_werkproces]").closest('.select-wrapper').hasClass("hide")){
                        $("select[name=selected_werkproces]").closest('.select-wrapper').addClass("hide");
                    }
                    if (!$("select[name=selected_criteria]").closest('.select-wrapper').hasClass("hide")){
                        $("select[name=selected_criteria]").closest('.select-wrapper').addClass("hide");
                    }
                    // ophalen van werkprocessen, met ajax
                    $.ajax({
                        type: 'GET',
                        url: 'json_show_proeve.php',
                        data: {id: cohort_id},
                        dataType: 'json',
                        success: function (data) {
                            $.each(data, function (index, element) {
                                //creer de content voor de dropdown menu voor proeves en selecteer automatisch degene die gelijk staat aan de session variable
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
                            // ** material only! **
                            $("select[name=selected_proeve]").material_select();
                            // als alles is opgehaald. Select weer laten zien.
                            $("select[name=selected_proeve]").closest('.select-wrapper').removeClass("hide");
                            if (!$("table[id=geen_resultaten]").hasClass("hide")){
                                //als het "geen resultaten" scherm zichtbaar is. Verstop het.
                                $("table[id=geen_resultaten]").addClass("hide");
                            }
                            //trigger het laten zien van de volgende lijst als de session variable daarvoor bestaat. Wouter heeft hierbij geholpen.
                            <?php
                            if (isset($_SESSION['session_proeve'])){
                            ?>
                                if (typeof(<?php echo $_SESSION['session_proeve']?>) !== "undefined"){
                                    $("select[name=selected_proeve]").trigger('change');
                                }
                            <?php
                            }
                            ?>
                        },
                        error: function () {
                            //drop menu content is leeg
                            //laat zien op de pagina dat er geen resultaten zijn
                            $("table[id=geen_resultaten]").removeClass("hide");
                            //hide de andere dropdown menus
                            if (!$("select[name=selected_proeve]").closest('.select-wrapper').hasClass("hide")){
                                $("select[name=selected_proeve]").closest('.select-wrapper').addClass("hide");
                            }
                        }
                    });
                    if (!$("table[id=show_normering]").hasClass("hide")){
                        //haalt overzicht normering weg
                        $("table[id=show_normering]").addClass("hide");
                    }
                });
                //trigger het laten zien van de eerste lijst met de change function. Dit gebeurt na de code voor de eerste onchange om errors proberen te voorkomen
                <?php
                if (isset($_SESSION['session_cohort'])){
                ?>
                    if (typeof(<?php echo $_SESSION['session_cohort']?>) !== "undefined"){
                        $("select[name=selected_cohort]").trigger('change');
                    }
                <?php
                }
                ?>
                
                //proeve is veranderd, zoek naar kerntaken en vul de dropdown menu ermee
                $("select[name=selected_proeve]").on('change', function () {
                    // waarde van geslecteerde id ophalen
                    proeve_id = this.value;
                    $.post('normering.php', {post_proeve: proeve_id});
                    // Kerntaak leeg maken:
                    $("select[name=selected_kerntaak]").empty().append($('<option>', {
                        value: 0,
                        text: "Kies een kerntaak"
                    }));
                    //hide de andere dropdown menus
                    if (!$("select[name=selected_werkproces]").closest('.select-wrapper').hasClass("hide")){
                        $("select[name=selected_werkproces]").closest('.select-wrapper').addClass("hide");
                    }
                    if (!$("select[name=selected_criteria]").closest('.select-wrapper').hasClass("hide")){
                        $("select[name=selected_criteria]").closest('.select-wrapper').addClass("hide");
                    }
                    // ophalen van werkprocessen, met ajax
                    $.ajax({
                        type: 'GET',
                        url: 'json_show_kerntaak.php',
                        data: {id: proeve_id},
                        dataType: 'json',
                        success: function (data) {
                            $.each(data, function (index, element) {
                                //creer de content voor de dropdown menu voor kerntaken en selecteer automatisch degene die gelijk staat aan de session variable
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
                            // ** material only! **
                            $("select[name=selected_kerntaak]").material_select();
                            // als alles is opgehaald. Select weer laten zien.
                            $("select[name=selected_kerntaak]").closest('.select-wrapper').removeClass("hide");
                            if (!$("table[id=geen_resultaten]").hasClass("hide")){
                                //als het "geen resultaten" scherm bestaat. Verstop het.
                                $("table[id=geen_resultaten]").addClass("hide");
                            }
                            //trigger het laten zien van de lijst als de session variable daarvoor bestaat. Wouter heeft hierbij geholpen.
                            <?php
                            if (isset($_SESSION['session_kerntaak'])){
                            ?>
                                if (typeof(<?php echo $_SESSION['session_kerntaak']?>) !== "undefined"){
                                    $("select[name=selected_kerntaak]").trigger('change');
                                }
                            <?php
                            }
                            ?>
                        },
                        error: function () {
                            //drop menu content is leeg
                            //laat zien op de pagina dat er geen resultaten zijn
                            $("table[id=geen_resultaten]").removeClass("hide");
                            //hide de andere dropdown menus
                            if (!$("select[name=selected_kerntaak]").closest('.select-wrapper').hasClass("hide")){
                                $("select[name=selected_kerntaak]").closest('.select-wrapper').addClass("hide");
                            }
                        }
                    });
                    if (!$("table[id=show_normering]").hasClass("hide")){
                        //haalt overzicht normering weg
                        $("table[id=show_normering]").addClass("hide");
                    }
                });
                
                //kerntaak is veranderd, zoek naar werkprocessen en vul de dropdown menu ermee
                $("select[name=selected_kerntaak]").on('change', function () {
                    // waarde van geslecteerde id ophalen
                    kerntaak_id = this.value;
                    $.post('normering.php', {post_kerntaak: kerntaak_id});
                    // Werkproces leeg maken:
                    $("select[name=selected_werkproces]").empty().append($('<option>', {
                        value: 0,
                        text: "Kies een werkproces"
                    }));
                    //hide de andere dropdown menus
                    if (!$("select[name=selected_criteria]").closest('.select-wrapper').hasClass("hide")){
                        $("select[name=selected_criteria]").closest('.select-wrapper').addClass("hide");
                    }
                    // ophalen van werkprocessen, met ajax
                    $.ajax({
                        type: 'GET',
                        url: 'json_show_werkproces.php',
                        data: {id: kerntaak_id},
                        dataType: 'json',
                        success: function (data) {
                            $.each(data, function (index, element) {
                                //creer de content voor de dropdown menu voor werkprocessen en selecteer automatisch degene die gelijk staat aan de session variable
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
                            //trigger het laten zien van de lijst als de session variable daarvoor bestaat. Wouter heeft hierbij geholpen.
                            <?php
                            if (isset($_SESSION['session_werkproces'])){
                            ?>
                                if (typeof(<?php echo $_SESSION['session_werkproces']?>) !== "undefined"){
                                    $("select[name=selected_werkproces]").trigger('change');
                                }
                            <?php
                            }
                            ?>
                        },
                        error: function () {
                            //drop menu content is leeg
                            //laat zien op de pagina dat er geen resultaten zijn
                            $("table[id=geen_resultaten]").removeClass("hide");
                            //hide de andere dropdown menus
                            if (!$("select[name=selected_werkproces]").closest('.select-wrapper').hasClass("hide")){
                                $("select[name=selected_werkproces]").closest('.select-wrapper').addClass("hide");
                            }
                        }
                    });
                    if (!$("table[id=show_normering]").hasClass("hide")){
                        //haalt overzicht normering weg
                        $("table[id=show_normering]").addClass("hide");
                    }
                });
                
                //werkproces is veranderd, zoek naar criteria en vul de dropdown menu ermee
                $("select[name=selected_werkproces]").on("change", function () {
                    
                    // waarde van geslecteerde id ophalen
                    werkproces_id = this.value;
                    $.post('normering.php', {post_werkproces: werkproces_id});
                    //Criteria leeg maken:
                    $("select[name=selected_criteria]").empty().append($('<option>', {
                        value: 0,
                        text: "Kies een criteria"
                    }));
                    // Ophalen informatie met Ajax
                    $.ajax({
                        type: 'GET',
                        url: 'json_show_criterium.php',
                        data: {id: werkproces_id},
                        dataType: 'json',
                        success: function (data) {
                            //creer de content voor de dropdown menu voor criteria en selecteer automatisch degene die gelijk staat aan de session variable
                            $.each(data, function (index, element) {
                                if ('<?php echo $session_criteria?>' === element.criterium_id){
                                    $("select[name=selected_criteria]").append($('<option>', {
                                        value: element.criterium_id,
                                        text: element.criterium_naam,
                                        selected: 1
                                    }));
                                }
                                else{
                                    $("select[name=selected_criteria]").append($('<option>', {
                                        value: element.criterium_id,
                                        text: element.criterium_naam
                                    }));
                                }
                            });
                            // toepassen css
                            // ** material only! **
                            $("select[name=selected_criteria]").material_select();
                            // als alles is opgehaald. Select weer laten zien.
                            $("select[name=selected_criteria]").closest('.select-wrapper').removeClass("hide");
                            if (!$("table[id=geen_resultaten]").hasClass("hide")){
                                //als het "geen resultaten" scherm bestaat. Verstop het.
                                $("table[id=geen_resultaten]").addClass("hide");
                            }
                            
                           //trigger het laten zien van de lijst als de session variable daarvoor bestaat. Wouter heeft hierbij geholpen.
                           <?php
                            if (isset($_SESSION['session_criteria'])){
                            ?>
                                if (typeof(<?php echo $_SESSION['session_criteria']?>) !== "undefined"){
                                    $("select[name=selected_criteria]").trigger('change');
                                }
                            <?php
                            }
                            ?>
               
                        },
                        error: function () {
                            //drop menu content is leeg
                            //laat zien op de pagina dat er geen resultaten zijn
                            $("table[id=geen_resultaten]").removeClass("hide");
                            //hide de andere dropdown menus
                            if (!$("select[name=selected_criteria]").closest('.select-wrapper').hasClass("hide")){
                                $("select[name=selected_criteria]").closest('.select-wrapper').addClass("hide");
                            }
                        }
                    });
                    if (!$("table[id=show_normering]").hasClass("hide")){
                        //haalt overzicht normering weg
                        $("table[id=show_normering]").addClass("hide");
                    }
                });
                
                // *** Ik (Pim) heb geen idee wat dit stuk code doet. Volgens mij is het verantwoordelijk voor een deel van het aanpassen van de dingen op de pagina, wat we toch van plan waren om te veranderen. ***
                $("select[name=kerntaak_option]").on('change', function () {
                    // waarde van geslecteerde id ophalen
                    kt = this.value;
                    //alert(kt);

                    // Alles leeg maken:
                    $("select[name=werkproces_option]").empty().append($('<option>', {
                        value: 0,
                        text: "Kies een werkproces"
                    }));

                    // ophalen van informatie, met ajax
                    $.ajax({
                        type: 'GET',
                        url: 'json_show_werkproces.php',
                        data: {id: kt},
                        dataType: 'json',
                        success: function (data) {
                            //alert(data);
                            $.each(data, function (index, element) {
                                //console.log(element.name);
                                $("select[name=werkproces_option]").append($('<option>', {
                                    value: element.id,
                                    text: element.name
                                }));
                            });
                            // toepassen css
                            // ** material only! **
                            $("select[name=werkproces_option]").material_select();
                            $("select[name=criterium_option]").material_select();
                            // als alles is opgehaald. Select weer laten zien.
                            //$("select[name=werkproces]").show();
                            $("select[name=werkproces_option]").closest('.select-wrapper').removeClass("hide");
                            //$("select[name=criterium_option]").closest('.select-wrapper').removeClass("hide");
                        }
                    });
                    if (!$("table[id=show_normering]").hasClass("hide")){
                        //haalt overzicht normering weg
                        $("table[id=show_normering]").addClass("hide");
                    }
                });
                
                $("select[name=werkproces_option]").on('change', function () {
                    // waarde van geslecteerde id ophalen
                    wp = this.value;
                    //alert(kt);

                    $("select[name=criterium_option]").empty().append($('<option>', {
                        value: 0,
                        text: "Kies een criterium"
                    }));

                    // ophalen van informatie, met ajax
                    $.ajax({
                        type: 'GET',
                        url: 'json_show_criterium.php',
                        data: {id: wp},
                        dataType: 'json',
                        success: function (data) {
                            //alert("data");
                            $.each(data, function (index, element) {
                                //console.log(element.name);
                                $("select[name=criterium_option]").append($('<option>', {
                                    value: element.criterium_id,
                                    text: element.criterium_naam
                                }));
                            });
                            // toepassen css
                            // ** material only! **
                            $("select[name=criterium_option]").material_select();
                            // als alles is opgehaald. Select weer laten zien.
                            //$("select[name=werkproces]").show();
                            //$("select[name=werkproces_option]").closest('.select-wrapper').removeClass("hide");
                            $("select[name=criterium_option]").closest('.select-wrapper').removeClass("hide");
                        }
                    });
                    if (!$("table[id=show_normering]").hasClass("hide")){
                        //haalt overzicht normering weg
                        $("table[id=show_normering]").addClass("hide");
                    }
                });
                
                $("select[name=criterium_option]").on('change', function () {
                    $("textarea[name=normering_naam]").removeClass("hide");
                    $("button[name=new_normering_submit]").removeClass("hide");
                });
               // alert('hier');
                   //$("select[name=selected_criteria]").material_select();
                 //$("select[name=selected_criteria]").show();
                    //$("select[name=selected_criteria]").closest('.select-wrapper').removeClass("hide");         

                //*** einde van het stuk code vaarvan ik geen idee heb wat het doet ***
                
                //criteria is veranderd, laat de resultaten op de pagina zien
                $("select[name=selected_criteria]").on("change", function () {
                    // waarde van geslecteerde id ophalen
                    criteria_id = this.value;
                    $.post('normering.php', {post_criteria: criteria_id});
                    
                    // Table overzicht leegmaken voordat er nieuwe data ingeladen wordt.
                    $("tbody[name=tbody]").empty();
                    // Ophalen informatie met Ajax
                    $.ajax({
                        type: 'GET',
                        url: 'json_show_normering.php',
                        data: {id: criteria_id},
                        dataType: 'json',
                        success: function (data) {
                            $.each(data, function (index, element) {
                                //append alle informatie van criteria op de pagina
                                $("#show_normering").find('tbody')
                                    .append($('<tr>', {id: element.normering_id})
                                        .append($('<td>', {text: element.normering_name},))
                                        .append($('<td><button data-target="ModalEditNormering" class="EditNormering btn-floating btn-large waves-effect waves-light yellow btn modal-trigger2"><i class="material-icons" >edit</i></button>'))
                                        .append($('<td><button data-target="ModalDeleteNormering" class="DeleteNormering btn-floating btn-large waves-effect waves-light red btn modal-trigger2"><i class="material-icons">delete</i></button>'))
                                    );
                                if(!$("table[id=geen_resultaten]").hasClass("hide")){
                                    //als het "geen resultaten" scherm bestaat. Verstop het.
                                    $("table[id=geen_resultaten]").addClass("hide");
                                }
                                $("table[id=show_normering]").removeClass("hide");
                            });
                            $(".modal-trigger2").leanModal();
                            
                            //ook geen idee wat dit precies doet
                            // Edit normering
                            $(".EditNormering").on('click', function () {
                                // waarde van het geselecteerde id ophalen
                                id_normering = $(this).parent().parent().attr('id');
                                //alert(id_normering);


                                // Velden leeg maken
                                document.getElementById("normering_id").value = "";
                                document.getElementById("modal_normering_naam").value = "";

                                // ophalen van informatie, met ajax om naam/omschrijving kerntaak op te halen
                                $.ajax({
                                    type: 'GET',
                                    url: 'json_edit_normering.php',
                                    data: {id: id_normering},
                                    dataType: 'json',
                                    success: function (data) {
                                        //console.log(data);
                                        $("#normering_id").val(data.id);
                                        $("#modal_normering_naam").val(data.name);
                                        $("#modal_normering_naam").removeClass("hide");
                                    }
                                });
                            });
                            
                            //DELETE normering
                            $(".DeleteNormering").on('click', function () {
                                // ophalen van het id
                                var criterium_normering_id = $(this).parent().parent().attr('id');
                                //console.log(werkproces_criterium_id);

                                // link aanpassen
                                $("#delhref").attr("href", "delete_normering.php?id=" + criterium_normering_id);
                            });
                        },
                        error: function () {
                            if (!$("table[id=show_normering]").hasClass("hide")){
                                //haalt overzicht normering weg
                                $("table[id=show_normering]").addClass("hide");
                            }
                            $("table[id=geen_resultaten]").removeClass("hide");
                        }
                    });
                });
                
                //add normering on click
                $("#AddNormering").on('click', function () {
                    // waarde van het geselecteerde id ophalen
                    // cohort_content = $("#selected_cohort").value;
                    var s_cohort = document.getElementById("selected_cohort");
                    var cohort_content = s_cohort.options[s_cohort.selectedIndex].text;
                    
                    var s_proeve = document.getElementById("selected_proeve");
                    var proeve_content = s_proeve.options[s_proeve.selectedIndex].text;
                    
                    var s_kerntaak = document.getElementById("selected_kerntaak");
                    var kerntaak_content = s_kerntaak.options[s_kerntaak.selectedIndex].text;
                    
                    var s_werkproces = document.getElementById("selected_werkproces");
                    var werkproces_content = s_werkproces.options[s_werkproces.selectedIndex].text;
                    
                    var s_criteria = document.getElementById("selected_criteria");
                    var criteria_content = s_criteria.options[s_criteria.selectedIndex].text;
                    
                    $("#ModalAddNormeringCohort").text(cohort_content);
                    $("#ModalAddNormeringProeve").text(proeve_content);
                    $("#ModalAddNormeringKerntaak").text(kerntaak_content);
                    $("#ModalAddNormeringWerkproces").text(werkproces_content);
                    $("#ModalAddNormeringCriteria").text(criteria_content);
                });
                
                //add modal normering knop als alle dropdowns gevuld zijn
                $(".sidebar_dropwdown").on("change", function () {
                    //verschillende aantal dropdowns in dropdown menus creeren errors in de console maar raag genoeg werkt het normaal zonder ongewenste bijwerkingen (anders dan de errors).
                    var s_cohort = document.getElementById("selected_cohort");
                    var check_cohort = s_cohort.options[s_cohort.selectedIndex].value !== "0";
                    
                    var s_proeve = document.getElementById("selected_proeve");
                    var check_proeve = s_proeve.options[s_proeve.selectedIndex].value !== "0";
                    
                    var s_kerntaak = document.getElementById("selected_kerntaak");
                    var check_kerntaak = s_kerntaak.options[s_kerntaak.selectedIndex].value !== "0";
                    
                    var s_werkproces = document.getElementById("selected_werkproces");
                    var check_werkproces = s_werkproces.options[s_werkproces.selectedIndex].value !== "0";
                    
                    var s_criteria = document.getElementById("selected_criteria");
                    var check_criteria = s_criteria.options[s_criteria.selectedIndex].value !== "0";
                    
                    if (check_cohort && check_proeve && check_kerntaak && check_werkproces && check_criteria){
                        $("#AddNormering").removeClass("hide");
                    }else{
                        $("#AddNormering").addClass("hide");
                    }
                });
            });
        </script>
    </body>
</html>
