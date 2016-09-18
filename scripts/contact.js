
  $(document).ready(function(){
      villbizApp.initialize();
  });
  function addContact(){
  	var contObj={};
  	contObj.name=$('#name').val();
  	contObj.email=$('#email').val();
  	contObj.mobile=$('#mobile').val();
  	contObj.message=$('#message').val();
    villbizApp.callPost('/php/contactus', JSON.stringify(contObj), function(response){
        response=JSON.parse(response);
        if(response.info.status){
          $('form').fadeOut('500');
          $('.contactus-successmsg').removeClass('hide');
        }
      });
    return false;
  }
