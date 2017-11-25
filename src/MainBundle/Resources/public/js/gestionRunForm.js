function enableCompileOnly() {
    $("input[name='" + FORM_NAME + "[inputMode]']").prop('disabled', true);
    $("#" + FORM_NAME + "_inputs").prop('hidden', true);

    $(".inputModeOption").addClass("disabled");
    $("#" + FORM_NAME + "_launchParameters").prop("disabled", true);
}

function disableCompileOnly() {
    $("input[name='" + FORM_NAME + "[inputMode]']").prop('disabled', false);

    if ($("input[name='" + FORM_NAME + "[inputMode]']").val() == 'text') {
        console.log('Enable inputs textarea')
        $("#" + FORM_NAME + "_inputs").prop('hidden', false);
    } else {
        console.log('Disable inputs textarea')
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
})


//Activation / Désactivation de la textarea pour les input en fonction du choix du mode d'input
$("input[name='" + FORM_NAME + "[inputMode]']").change(function () {
    if ($(this).val() == 'text') {
        console.log('Enable inputs textarea')
        $("#" + FORM_NAME + "_inputs").prop('hidden', false);
    } else {
        console.log('Disable inputs textarea')
        $("#" + FORM_NAME + "_inputs").prop('hidden', true);
    }
});

function toogleExecutionFormView() {
    $("#executionForm-container").toggleClass("execution-shown");
    $("#executionForm-container").toggleClass("execution-hidden");
}

$("#runOptionToggle").click(toogleExecutionFormView);

$("#close-execution-form").click(toogleExecutionFormView);

$("#run-btn").click(function () {
    $("#executionForm-container").removeClass("execution-shown");
    $("#executionForm-container").addClass("execution-hidden");


    if (currentFile != -1) {
        files[currentFile].content = editor.getValue();
    }

    console.log("RUN !");
    $("#" + FORM_NAME + "_files").val(JSON.stringify(files));
    $("#" + FORM_NAME + "_language").val($('.choix-langage-selected').attr("data-id"));
    Exec();
});
