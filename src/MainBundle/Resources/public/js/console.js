var jqconsole;

var Exec = function () {

    $('#console').html("");

    jqconsole = $('#console').jqconsole('', '');
    var start = true;

    var getOutput = function () {
        $("#btnStop").prop("disabled", false);
        $.ajax({
            url: PATH_CONSOLE_EXEC,
            type: "POST",
            data: new FormData(document.getElementById(FORM_NAME)),
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function() {
                // Activation loading
                $('#loader').addClass('active');
                start = true;
            },
            success: function (data) {
                onSuccess(data);
            },
            complete: function() {
                // Gestion loading
                $('#loader').removeClass('active');
            },
            timeout: 30000
        });

        var repondre = function () {
            jqconsole.Prompt(true, function (input) {
                $.ajax({
                    url: PATH_CONSOLE_ANS,
                    type: "POST",
                    data: {"msg": input},
                    success: function (data) {
                        onSuccess(data);
                    }
                });
            });
        };

        var onSuccess = function (data) {
            try {
                var reponse = $.parseJSON(data);
            } catch (e) {
                // Problème serveur, impossible à parser
            }
            jqconsole.Write(reponse.reponse, 'jqconsole-output');
            if (reponse.fin === "no") {
                if (start == true) {
                    start = false;
                    repondre(); 
                } else {
                    var form = $('input[name="mainbundle_execution[inputMode]"]:checked').val();
                    // Vérification mode intéractif
                    if (form == "it") {
                        repondre();
                    } else {
                        swal({
                            title : 'Attention !',
                            text : "Les 'sleep' ou boucles infinies (programme dépassant le temps autorisé) durant un programe entraine l'arrêt de l'exécution.",
                            type : 'warning'
                        })                                   
                    }
                }                
            } else {
                $("#btnStop").prop("disabled", true);
                jqconsole.Write("\033[32m$ Execution finie.\033[0m", 'jqconsole-output');
            }
        };

    };
    getOutput();

};

$("#btnStop").click(function () {
    $.ajax({
        url: PATH_STOP_CONSOLE,
        type: "POST",
        cache: false,
        success: function () {
            $("#btnStop").prop("disabled", true);
            jqconsole.AbortPrompt();
            jqconsole.Write("\033[31m$ Execution interrompue\033[0m");
        }
    })
});

