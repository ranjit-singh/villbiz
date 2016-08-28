if(docCookies.getItem('PHPSESSID')){
   window.location.href='admin.html';
}else{
	//window.location.href='admin';
}
$(document).ready(function(){
        villbizApp.initialize();
    });
function login(){
      var userObj={};
          userObj.userName=$('#user_name').val();
          userObj.password=$('#password').val();
          userObj.isAdmin="admin";
          villbizApp.callPost('/php/login', JSON.stringify(userObj), function(response){
            response=JSON.parse(response);
            if(response.status){
              window.location.href='admin.html';
            }
          });
      return false;
    }