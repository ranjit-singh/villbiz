
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
      });
    return false;
  }
