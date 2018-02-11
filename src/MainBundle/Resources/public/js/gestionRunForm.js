function enableCompileOnly() {
    $("input[name='" + FORM_NAME + "[inputMode]']").prop('disabled', true);
    $("#" + FORM_NAME + "_inputs").prop('hidden', true);

    $(".inputModeOption").addClass("disabled");
    $("#" + FORM_NAME + "_launchParameters").prop("disabled", true);
}

function disableCompileOnly() {
    $("input[name='" + FORM_NAME + "[inputMode]']").prop('disabled', false);

    if ($("input[name='" + FORM_NAME + "[inputMode]']").val() == 'text') {
        $("#" + FORM_NAME + "_inputs").prop('hidden', false);
    } else {
        $("#" + FORM_NAME + "_inputs").prop('hidden', true);
    }
    $(".inputModeOption").removeClass("disabled");
    $("#" + FORM_NAME + "_launchParameters").prop("disabled", false);

}

$("#" + FORM_NAME + "_compileOnly").change(function () {
    if ($(this).is(":checked")) {
        enableCompileOnly();
    }
    else {
        disableCompileOnly();
    }
});


//Synchronisation input des options de compilation avec le texte d'aide situé en dessous
$("#" + FORM_NAME + "_compilationOptions").on('input', function () {
    $("#compileCMD-options").text( $( this ).val());
});

//Activation / Désactivation de la textarea pour les input en fonction du choix du mode d'input
$("input[name='" + FORM_NAME + "[inputMode]']").change(function () {
    if ($(this).val() == 'text') {
        $("#" + FORM_NAME + "_inputs").prop('hidden', false);
    } else {
        $("#" + FORM_NAME + "_inputs").prop('hidden', true);
    }
});

function run(){
    if (currentFile != -1) {
        files[currentFile].content = editor.getValue();
    }
    console.log("RUN !");
    $("#" + FORM_NAME + "_files").val(JSON.stringify(files));
    $("#" + FORM_NAME + "_language").val($('.choix-langage-selected').attr("data-id"));
    Exec();
}

$("#run-btn").click(run);

$("#exec-form-run-btn").click(function(){
    $("#executionForm-container").modal('hide');
    run();
});
