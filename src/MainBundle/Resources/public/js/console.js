/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//var jqconsole = $('#console').jqconsole('', '');

var Exec = function () {

    $('#console').html("");

    var jqconsole = $('#console').jqconsole('', '');

    var getOutput = function () {
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
                repondre();
            }
        };

    };
    getOutput();

};
