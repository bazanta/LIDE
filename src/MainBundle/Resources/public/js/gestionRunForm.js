function enableCompileOnly(){
  $("input[name='execution[inputMode]']").prop('disabled', true);
  $("#execution_inputs").prop('disabled', true);

  $(".inputModeOption").addClass("disabled");
}

function disableCompileOnly(){
  $("input[name='execution[inputMode]']").prop('disabled', false);

  if( $( "input[name='execution[inputMode]']" ).val() == 'text'){
    $("#execution_inputs").prop('disabled', false);
  }else{
    $("#execution_inputs").prop('disabled', true);
  }

  $(".inputModeOption").removeClass("disabled");
}

$("#execution_compileOnly").change(function (){
  if( $(this).is(":checked")){
    enableCompileOnly();
  }
  else{
    disableCompileOnly();
  }
})


//Activation / Désactivation de la textarea pour les input en fonction du choix du mode d'input
$("input[name='execution[inputMode]']").change(function(){
  if( $( this ).val() == 'text'){
    console.log('Enable inputs textarea')
    $("#execution_inputs").prop('disabled', false);
  }else{
    console.log('Disable inputs textarea')
    $("#execution_inputs").prop('disabled', true);
  }
});

$( document ).ready(function(){
})
