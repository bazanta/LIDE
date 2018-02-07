var selectedTheme = $("#ace-theme-selector").val();
var fontSize = $("#editor-font-size-selector").val();
var themeConsole =$("#console-theme-selector").val();

function setAceTheme(theme) {
    editor.setTheme('ace/theme/'+theme);
}

function setEditorFontSize(fontSize){
    $("#editor").css("font-size", fontSize);
}

function setConsoleTheme(theme){
    var divConsole = $("#console");
    var opt = $("#console-theme-selector option");
    for(var i  =0; i < opt.length; ++i){
        divConsole.removeClass("console-" + opt[i].value);
    }

    divConsole.addClass(theme);
}

$("#ace-theme-selector").change(function(){
    setAceTheme($(this).val());
});

$("#editor-font-size-selector").change(function () {
    setEditorFontSize($(this).val() + "pt");
})

$("#options-ok").click(function () {
    selectedTheme = editor.getTheme();
    fontSize = $("#editor-font-size-selector").val();
    $('#modal-options').modal('hide');
    synchroniseEditorAndSelectedTab();

    // Enregistrement en base
    var formName = $(this).data('form');
    var form = $('form[name="'+formName+'"]').serialize();
    $.ajax({
        type: "POST",
        url: urlUpdateInterface,
        data: form,
        success: function (response) {
            //console.log(response);
        }
    });
});

$("#console-theme-selector").change(function () {
    setConsoleTheme("console-" + $(this).val());
});

$('#options-cancel').click(function () {
    $("#ace-theme-selector").val(selectedTheme);
    if(editor.getTheme() != selectedTheme){
        editor.setTheme(selectedTheme);
    }

    $("#editor-font-size-selector").val(fontSize);
    $("#editor").css("font-size", fontSize + "pt");

    $("#console-theme-selector").val(themeConsole);

    $('#modal-options').modal('hide');
});

$(document).ready(function () {
    setAceTheme(selectedTheme);
    setConsoleTheme("console-" + themeConsole);
    setEditorFontSize(fontSize);
})