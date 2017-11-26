
var selectedTheme = editor.getTheme();
var fontSize = "12pt";

$("#editor-font-size-selector").val(fontSize);

$("#ace-theme-selector").change(function(){
    editor.setTheme($( this ).val());
});

$("#editor-font-size-selector").change(function () {
    $("#editor").css("font-size", $(this).val());
})

$("#options-ok").click(function () {
    selectedTheme = editor.getTheme();
    fontSize = $("#editor-font-size-selector").val();
    $('#modal-options').modal('hide');
});

$('#options-cancel').click(function () {
    $("#ace-theme-selector").val(selectedTheme);
    $("#editor-font-size-selector").val(fontSize);
    $('#modal-options').modal('hide');
});