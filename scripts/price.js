$(document).ready(function(){
	villbizApp.initialize();
	$(".dropdown-button").dropdown({hover: true});
	$('.modal-trigger').leanModal();
  if(docCookies.getItem('PHPSESSID') && docCookies.getItem('uid')){
    $('.logedin-user').removeClass('hide');
    $('.pricing-emptystate').addClass('hide');
    $('#login-required').fadeIn('500');
    villbizApp.callGet('/php/userprofile/'+docCookies.getItem('uid'), function(resp){
      resp=JSON.parse(resp);
      villbizApp.setData('profile', resp.profile);
      $('#user-name').html(resp.profile.name);
    });
    init();
  }else{
    docCookies.removeItem('PHPSESSID');
    docCookies.removeItem('uid');
    $('#login-required').fadeOut('500');
    $('#login-modal').openModal();
  }
});
function init(){
    var utc = new Date().toJSON().slice(0,10);
    $('.price-date').html(utc);
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
          if(docCookies.getItem('PHPSESSID')){
            villbizApp.callGet('/php/pepper/price', pepperCallBack);
          }else{
            $('#login-modal').openModal();
          }
      	}else{
          $('#pepperTable').hide();
          $('#coffeeTable').show();
          if(docCookies.getItem('PHPSESSID')){
            //init();
          }else{
            $('#login-modal').openModal();
          }
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
    $('#user-login-form').removeClass('hide');
    $('#user-forgot-password-form').addClass('hide');
  }
  function showSignupForm(){
    $('#login-modal').closeModal();
    $('#signup-modal').openModal();
  }
  
  function showForgotPasswordForm(){
    $('#user-login-form').addClass('hide');
    $('#user-forgot-password-form').removeClass('hide');
  }
  function userSignup(){
    if($('#password').val()!=$('#confirmpassword').val())
    {
      $('#signup-error-message').removeClass('hide').find('p').html('Passwords does not match.');
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
          if(resp.info.status){
            villbizApp.setData('uid', resp.info.id);
            villbizApp.setData('mobile', usrInfo.mobile);
            $('#signupForm').fadeOut('500');
            $('#userOtpApproval').fadeIn('500');
          }else{
            $('#signup-error-message').removeClass('hide').find('p').html(resp.info.message);
          }
        });
      return false;
  }

  function userOtpApproval(){
    var otp=$('#mobOtp').val();
    villbizApp.callGet('/php/verifyotp/'+villbizApp.getData('uid')+'/'+otp, function(response){
          response=JSON.parse(response);
          if(response.info.status){
            $('#userOtpApproval').addClass('hide');
            $('#signupsucess').removeClass('hide');
          }else{
            $('#otp-error-message').removeClass('hide');
          }
        });
      return false;
  }
  function reSentOtp(){
    villbizApp.callGet('/php/resentotp/'+villbizApp.getData('mobile'), function(response){
          response=JSON.parse(response);

        });
  }

  function userResetPassword(){
     villbizApp.setData('mobile', $('#forgot-password-mobile').val());
     villbizApp.callGet('/php/resentotp/'+$('#forgot-password-mobile').val(), function(response){
          response=JSON.parse(response);
          if (response.info.status) {
              $('#user-forgot-password-form').addClass('hide');
              $('#verifyResetOtp').removeClass('hide');
          }else{
              $('#forgot-error-message').removeClass('hide');
          }
     });
     return false;
  }

  function verifyPasswordResetOtp(){
    villbizApp.callGet('/php/verifyresetpasswordotp/'+villbizApp.getData('mobile')+'/'+$('#forgot-password-otp').val(), function(response){
          response=JSON.parse(response);
          if(response.info.status){
            $('#verifyResetOtp').addClass('hide');
            $('#setNewPassword').removeClass('hide');
          }else{
            $('#verify-password-reset-otp').removeClass('hide');
          }
     });
    return false;
  }

  function userSetPassword(){
    var newPass=$('#new-password').val(), cnewPass=$('#confirm-new-password').val();
    if(newPass!=cnewPass)
    {
      $('#password-mismatch-error-message').removeClass('hide');
      return false;
    }
    var resetPassObj={};
    resetPassObj.password=newPass;
    resetPassObj.mobile=villbizApp.getData('mobile');
    villbizApp.callPost('/php/setpassword', JSON.stringify(resetPassObj), function(response){
      response=JSON.parse(response);
      if(response.info.status){
        $('#setNewPassword').addClass('hide');
        $('#resetsucess').removeClass('hide');
      }
    });
    return false;
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
              $('#login-required').fadeIn('500');
              $('#user-name').html(response.profile.name);
              $('.pricing-emptystate').addClass('hide');
              villbizApp.setData('profile', response.profile);
              docCookies.setItem('uid', response.profile.id);
              $('.logedin-user').removeClass('hide');
              $('#login-modal').closeModal();
              init();
            }else{
              $('#error-message').removeClass('hide');
            }
          });
      return false;
  }
  function logOut(){
      villbizApp.callGet('/php/logout', function(resp){
        $('.logedin-user').addClass('hide');
        docCookies.removeItem('PHPSESSID');
        docCookies.removeItem('uid');
        location.replace('/');
      });
    }