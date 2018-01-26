/* Auteur : Valentine Rahier
 *
 */

var jqconsole;

var Exec = function () {

    $('#console').html("");

    jqconsole = $('#console').jqconsole('', '');

    var getOutput = function () {
        $("#btnStop").prop("disabled", false);
        $.ajax({
            url: PATH_CONSOLE_EXEC,
            type: "POST",
            data: new FormData(document.getElementById(FORM_NAME)),
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                onSuccess(data);
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
            var reponse = $.parseJSON(data);
            console.log(reponse);
            jqconsole.Write(reponse.reponse, 'jqconsole-output');
            if (reponse.fin === "no") {
                var form = $("mainbundle_execution[inputMode]:checked").val()
                // Vérification mode intéractif
                if (form == "it") {
                    repondre();
                } else {
                    $("#btnStop").click();
                    alert("Attention, les 'sleep' ou boucle infinie durant un programe entraine l'arrêt de l'exécution.");                    
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

