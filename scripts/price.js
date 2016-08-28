$(document).ready(function(){
	villbizApp.initialize();
	$(".dropdown-button").dropdown({hover: true});
	$('.modal-trigger').leanModal();
  if(docCookies.getItem('PHPSESSID')){
    init();
  }else{
    $('#login-modal').openModal();
  }
});
function init(){
    villbizApp.callGet('/php/coffee/price', coffeeCallBack);
      function coffeeCallBack(response){
        response=JSON.parse(response);
        var htmlList='';
        response.price.forEach(function(value,index,arr){
            htmlList+='<tr><td>'+value.trader+'</td><td>'+value.city+'</td><td>'+value.ap+'</td><td>'+value.ac+'</td><td>'+value.rp+'</td><td>'+value.rc+'</td></tr>';
          });
        $('#CoffeePrice').html(htmlList);
      };
 
      villbizApp.callGet('/php/close/price', closeCallBack);
      function closeCallBack(response){
        response=JSON.parse(response);
        var htmlList='';
        response.price.forEach(function(value,index,arr){
            htmlList+='<tr><td>'+value.name+'</td><td>'+value.price+'</td><td>'+value.clchange+'</td><td>'+value.change_percent+'</td></tr>';
          });
        $('#closePrice').html(htmlList);
      };
      villbizApp.callGet('/php/news', newsCallBack);
      function newsCallBack(response){
        response=JSON.parse(response);
        $('#newsList').html(response.news.length > 0 ? response.news[0].news:'');
      };
}
function loadPrice(evt){
      	if(evt.checked){
      		$('#coffeeTable').hide();
      		$('#pepperTable').show();
      		villbizApp.callGet('/php/pepper/price', pepperCallBack);
      	}else{
      		$('#pepperTable').hide();
      		$('#coffeeTable').show();
      	}
 }

  function pepperCallBack(response){
  	response=JSON.parse(response);
  	var htmlList='';
  	response.price.forEach(function(value,index,arr){
        htmlList+='<tr><td>'+value.trader+'</td><td>'+value.city+'</td><td>'+value.quantity+'</td><td>'+value.brand+'</td><td>'+value.price+'</td></tr>';
      });
  	$('#PepperPrice').html(htmlList);
  };

  function showLoginForm(){
    $('#signup-modal').closeModal();
    $('#login-modal').openModal();
  }
  function showSignupForm(){
    $('#login-modal').closeModal();
    $('#signup-modal').openModal();
  }
  
  function userSignup(){
    if($('#password').val()!=$('#confirmpassword').val())
    {
      alert("Password is not matching. please confirm correct password");
      return false;
    }
    var usrInfo={};
        usrInfo.name=$('#fullname').val();
        usrInfo.email=$('#email').val();
        usrInfo.mobile=$('#mobilenumber').val();
        usrInfo.password=$('#password').val();
        usrInfo.isAdmin="normal";
        villbizApp.callPost('/php/createuser', JSON.stringify(usrInfo), function(resp){
          resp=JSON.parse(resp);
          villbizApp.setData('uid', resp.info.id);
          villbizApp.setData('mobile', usrInfo.mobile);alert(villbizApp.getData('uid'));
          $('#signupForm').fadeOut('500');
          $('#userOtpApproval').fadeIn('500');
        });
      return false;
  }

  function userOtpApproval(){
    var otp=$('#mobOtp').val();
    villbizApp.callGet('/php/verifyotp/'+villbizApp.getData('uid')+'/'+otp, function(response){
          $('#userOtpApproval').addClass('hide');
          $('#signupsucess').removeClass('hide');
        });
      return false;
  }
  function reSentOtp(){
    villbizApp.callGet('/php/resentotp/'+villbizApp.getData('mobile'), function(response){
          //response=JSON.parse(response);
        });
  }
  function login() {
    var username = $("#loginusername").val();
    var password = $("#loginpassword").val();
    var userObj={};
          userObj.userName=username;
          userObj.password=password;
          userObj.isAdmin="normal";
          villbizApp.callPost('/php/login', JSON.stringify(userObj), function(response){
            response=JSON.parse(response);
            if(response.status){
             $('#login-modal').closeModal();
             init();
            }
          });
      return false;
  }