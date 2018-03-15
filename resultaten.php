<?php
include("check.php");
include("connect.php");

if (isset($_POST['post_klas'])){
    $_SESSION['session_klas'] = $_POST['post_klas'];
}
if (isset($_POST['post_student'])){
    $_SESSION['session_student'] = $_POST['post_student'];
}
//bereid session locals voor
$session_klas = $session_student = "";
if (isset($_SESSION['session_klas'])){
    $session_klas = $_SESSION['session_klas'];
}
if (isset($_SESSION['session_student'])){
    $session_student = $_SESSION['session_student'];
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
        ?>
        <div class="row">
            <div class="col s12 m4 l3 sidebar">
                <br />
                <?php //haal klassen records op
                //$session_klas = "";
                /*if (isset($_SESSION['session_klas'])){
                    $session_klas = $_SESSION['session_klas'];
                }*/
                $sql_klas = "SELECT * FROM klas";
                $result_klas = $conn->query($sql_klas);
                if ($result_klas->num_rows > 0) {
                    ?>
                    <select name="selected_klas" required>
                        <?php
                        if (isset($_SESSION['session_klas'])){
                            echo '<option disabled>Kies een klas</option>';
                        }
                        else{
                            echo "<option selected='selected' disabled>Kies een klas</option>";
                        }
                        while ($row_klas = $result_klas->fetch_assoc()) {
                            if (isset($_SESSION['session_klas'])){
                                if ($_SESSION['session_klas'] == $row_klas['klas_id']){
                                    $selectedvalue = "selected='selected'";
                                }
                                else{
                                    $selectedvalue = "";
                                }
                            }
                            echo "<option " . $selectedvalue . " value=" . $row_klas['klas_id'] . ">" . $row_klas['klas_naam'] . "</option>";
                        }
                        ?>
                    </select>
                    <?php
                }
                ?>
                
                <select name="selected_student" class="">
                </select>
                
                <br />
                <div>Zoek op student</div>
                <div style="bottom: 0;">
                    <div style="float:left; width:20px;">
                        <i class="material-icons">search</i>
                    </div>
                    <div style="margin-left: 30px;">
                        <input name="search" style="background-color: white; width:100%; height: 25px;">
                    </div>
                </div>
                
                <select name="selected_search_student" class="">
                </select>
                
            </div>
            <div style="overflow: scroll; height: 85%" class="col s12 m8 l9" margin="0 auto">
                <h4>Resultaten en Beoordelen</h4>
                <table>
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Cohort</th>
                        <th>Klas</th>
                        <th>OV-Nummer</th>
                    </tr>
                </thead>
                </table>
                <ul name="resultaten_content" id="content" class="collapsible" data-collapsible="accordion"></ul>
                
                <!--voorbeeld collapsibles voor resultatenoverzicht-->
                <ul class="collapsible" data-collapsible="accordion"><!--listgroup-->
                    <li><!--collapsible listelement-->
                        <div class="collapsible-header">First</div>
                        <div class="collapsible-body">
                            <div class="fluid-container">
                                <div class="row">
                                    <div class="col s3">Kerntaak</div>
                                    <div class="col s3">Werkproces</div>
                                    <div class="col s2">Criterium</div>
                                    <div class="col s1">
                                        <div class="blue-grey darken-1 cardclick" data-criterium="3" data-id="1">
                                            <div class="white-text">
                                                <p>1</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col s1">
                                        <div class="blue-grey darken-1 cardclick" data-criterium="3" data-id="2">
                                            <div class="white-text">
                                                <p>2</p>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col s1">
                                        <div class="blue-grey darken-1 cardclick" data-criterium="3" data-id="3">
                                            <div class="white-text">
                                                <p>3</p>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col s1">
                                        <div class="blue-grey darken-1 cardclick" data-criterium="3" data-id="4">
                                            <div class="white-text">
                                                <p>4</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
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
                
                //onchange selected_klas
                //haal studenten op en geef weer in sidebar
                $("select[name=selected_klas]").on('change', function () {
                    //selecteerde id posten naar deze pagina, om straks in de session te gooien
                    klas_id = this.value;
                    $.post('resultaten.php', {post_klas: klas_id});
                    // Student dropdown leeg maken:
                    $("select[name=selected_student]").empty().append($('<option>', {
                        value: 0,
                        text: "Kies een student"
                    }));
                    // ophalen van studenten met ajax
                    $.ajax({
                        type: 'GET',
                        url: 'json_show_student.php',
                        data: {id: klas_id},
                        dataType: 'json',
                        success: function (data) {
                            //alert(data);
                            $.each(data, function (index, element) {
                                //creer de content voor de dropdown menu voor studenten
                                if ('<?php echo $session_student?>' === element.student_id){
                                    $("select[name=selected_student]").append($('<option>', {
                                        value: element.student_id,
                                        text: element.student_name,
                                        selected: 1
                                    }));
                                }
                                else{
                                    $("select[name=selected_student]").append($('<option>', {
                                        value: element.student_id,
                                        text: element.student_name
                                    }));
                                }
                            });
                            // toepassen css
                            // ** material only! **
                            $("select[name=selected_student]").material_select();
                            // als alles is opgehaald. Select weer laten zien.
                            $("select[name=selected_student]").closest('.select-wrapper').removeClass("hide");
                            
                            <?php //trigger het laten zien van de volgende lijst alleen als deze gevuld is. Wouter heeft hierbij geholpen.
                            if (isset($_SESSION['session_student'])){
                            ?>
                                if (typeof(<?php echo $_SESSION['session_student']?>) !== "undefined"){
                                    $("select[name=selected_student]").trigger('change');
                                    //alert('jahoor');
                                }
                            <?php
                            }
                            ?>
                        },
                        error: function () {
                            //drop menu content is leeg
                        }
                    });
                });
                //trigger het laten zien van de gekozen klas als de session_klas bestaat
                <?php
                if (isset($_SESSION['session_klas'])){
                ?>
                    if (typeof(<?php echo $_SESSION['session_klas']?>) !== "undefined"){
                        $("select[name=selected_klas]").trigger('change');
                    }
                <?php
                }
                ?>
                
                //onchange search
                //haal studenten op en geef weer in sidebar
                $("select[name=search]").on('change', function () {
                    search_term = this.value;
                    //alert(search_term);
                    //hide door klas gekozen studentenlijst
                    //un-hide de search studentenlijst
                    //een knoppie met onclick JS functie die de query start? of iedere keypress een nieuwe query?
                    //hoedanook die search SQL moet er komen
                    //ook een .ajax blokje voor het interpreteren van de records
                    
                    $.post('resultaten.php', {post_search: search});
                    // Student dropdown leeg maken:
                    $("select[name=selected_search_student]").empty().append($('<option>', {
                        value: 0,
                        text: "Kies een student"
                    }));
                    // ophalen van studenten met ajax
                    $.ajax({
                        type: 'GET',
                        url: 'json_show_student.php',
                        data: {id: klas_id},
                        dataType: 'json',
                        success: function (data) {
                            //alert(data);
                            $.each(data, function (index, element) {
                                //creer de content voor de dropdown menu voor studenten
                                if ('<?php echo $session_student?>' === element.student_id){
                                    $("select[name=selected_student]").append($('<option>', {
                                        value: element.student_id,
                                        text: element.student_name,
                                        selected: 1
                                    }));
                                }
                                else{
                                    $("select[name=selected_student]").append($('<option>', {
                                        value: element.student_id,
                                        text: element.student_name
                                    }));
                                }
                            });
                            // toepassen css
                            // ** material only! **
                            $("select[name=selected_student]").material_select();
                            // als alles is opgehaald. Select weer laten zien.
                            $("select[name=selected_student]").closest('.select-wrapper').removeClass("hide");
                            
                            <?php //trigger het laten zien van de volgende lijst alleen als deze gevuld is. Wouter heeft hierbij geholpen.
                            if (isset($_SESSION['session_student'])){
                            ?>
                                if (typeof(<?php echo $_SESSION['session_student']?>) !== "undefined"){
                                    $("select[name=selected_student]").trigger('change');
                                    //alert('jahoor');
                                }
                            <?php
                            }
                            ?>
                        },
                        error: function () {
                            //drop menu content is leeg
                        }
                    });
                });
                
                //TODO: -Dit codeblok werkt niet. Genereer Resultatenoverzicht uit dataset. Zie wireframe en HTML.
                //Selecteer student, show content
                $("select[name=selected_student]").on('change', function () {
                    student_id = this.value;
                    //show student and show beoordelingen
                    $.post('resultaten.php', {post_student: student_id});
                    $("div[name=resultaten_content]").empty();
                    $.ajax({
                        type: 'GET',
                        url: 'json_show_beoordelingen.php',
                        data: {id: student_id},
                        dataType: 'json',
                        success: function (data) {
                            lastproeve = lastkerntaak = lastwerkproces = "";
                            writekerntaak = "";
                            writewerkproces = "";
                            content = "div[name=resultaten_content]";
                            $.each(data, function (index, element) {
                                //console.log(element.criterium_id, element.criterium_naam);
                                //check new proeve
                                if (lastproeve !== element.proeve_naam){
                                    //start new proeve
                                    $(content)
                                        .append($('<li>')
                                            .append($('<div>',{class: 'collapsible-header', text: element.proeve_naam}))
                                            .append($('<div>',{class: 'collapsible-body'>}))
                                                .append($('<div>',{class: 'fluid-container', id: element.proeve_naam}))
                                        );
                                    lastproeve = element.proeve_naam;
                                }
                                
                                //check new kerntaak
                                if (lastkerntaak !== element.kerntaak_naam) { writekerntaak = element.kerntaak_naam; } 
                                else { lastkerntaak = element.kerntaak_naam; }
                                //check new werkproces
                                if (lastwerkproces !== element.werkproces_naam) { writewerkproces = element.werkproces_naam; } 
                                else { lastwerkproces = element.werkproces_naam; }
                                
                                //append the entire row
                                content = "#"+element.proeve_naam;
                                $(content)
                                    .append($('')
//                                       TODO: bla
                                    );
                                
                                $("div[name=resultaten_content]")
                                    .append($('<tr>', {id: element.normering_id})
                                        .append($('<td>', {text: element.normering_name},))
                                        .append($('<td><button data-target="ModalEditNormering" name="EditNormering" class="EditNormering btn-floating btn-large waves-effect waves-light yellow btn modal-trigger2"><i class="material-icons" >edit</i></button>'))
                                        .append($('<td><button data-target="ModalDeleteNormering" name="DeleteNormering" class="DeleteNormering btn-floating btn-large waves-effect waves-light red btn modal-trigger2"><i class="material-icons">delete</i></button>'))
                                    );
                                //$("select[name=criteria]").material_select();
                                //$("select[name=selected_criteria]").show();
                                if(!$("table[id=geen_resultaten]").hasClass("hide")){
                                    $("table[id=geen_resultaten]").addClass("hide");
                                }
                                $("table[id=show_normering]").removeClass("hide");
                            });
                            $(".modal-trigger2").leanModal();
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
                
                //click on a beoordelingbutton, change colors, update DB beoordeling
                //TODO: update Beoordeling onclick
                $('.cardclick').click(function() {
                    // alert( $(this).data("id") );
                    //alert( $("#ovnr").html() )
                    //alert( $(this).data("criterium") );
                    var criterium = $(this).data("criterium");

                    $("[data-criterium]").each(function(){
                        if (criterium === $(this).data('criterium')) {
                            $(this).removeClass("green").addClass("blue-grey");
                        }
                    });

                    $(this).removeClass("blue-grey").addClass("green");

                });
            });
        </script>
    </body>
</html>
