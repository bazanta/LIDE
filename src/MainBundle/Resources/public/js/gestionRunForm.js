function enableCompileOnly(){
  $("input[name='" + FORM_NAME + "[baseOption][inputMode]']").prop('disabled', true);
  $("#" + FORM_NAME + "baseOption_inputs").prop('hidden', true);

  $(".inputModeOption").addClass("disabled");
  $("#" + FORM_NAME + "_baseOption_launchParameters").prop("disabled", true);
}

function disableCompileOnly(){
  $("input[name='" + FORM_NAME + "[baseOption][inputMode]']").prop('disabled', false);

  if( $( "input[name='" + FORM_NAME + "[baseOption][inputMode]']" ).val() == 'text'){
    console.log('Enable inputs textarea')
    $("#" + FORM_NAME + "_baseOption_inputs").prop('hidden', false);
  }else{
    console.log('Disable inputs textarea')
    $("#" + FORM_NAME + "_baseOption_inputs").prop('hidden', true);
  }
  $(".inputModeOption").removeClass("disabled");
  $("#" + FORM_NAME + "_baseOption_launchParameters").prop("disabled", false);

}

$("#" + FORM_NAME + "_baseOption_compileOnly").change(function (){
  if( $(this).is(":checked")){
    enableCompileOnly();
  }
  else{
    disableCompileOnly();
  }
})


//Activation / Désactivation de la textarea pour les input en fonction du choix du mode d'input
$("input[name='" + FORM_NAME + "[baseOption][inputMode]']").change(function(){
  if( $( this ).val() == 'text'){
    console.log('Enable inputs textarea')
    $("#" + FORM_NAME + "_baseOption_inputs").prop('hidden', false);
  }else{
    console.log('Disable inputs textarea')
    $("#" + FORM_NAME + "_baseOption_inputs").prop('hidden', true);
  }
});

function toogleExecutionFormView(){
  $("#executionForm-container").toggleClass("execution-shown");
  $("#executionForm-container").toggleClass("execution-hidden");

}

$("#runOptionToggle").click(toogleExecutionFormView);

$("#close-execution-form").click(toogleExecutionFormView);

$("run-btn").click(function(){
  Exec();
});
