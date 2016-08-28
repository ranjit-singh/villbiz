/* Common app functionality */

var villbizApp = (function () {
    "use strict";
    var apiService = {};

    // Common initialization function (to be called from each page)
    apiService.initialize = function () {
        apiService.properties={
            "coffee":"Coffee Estates",
            "agriculture":"Agriculture Land",
            "tea":"Rubber & Tea Estates",
            "house":"Houses & Plots",
            "business":"Business"
        };
        /* call get api */
        apiService.callGet=function(serviceName, callback){
          return $.ajax({
                        url:window.location.origin+'/villbiz'+serviceName,
                        type:'GET',
                        beforeSend:function(xhr){
                          $('#loader-wrapper').fadeIn('500');
                        },
                        success:function(res){
                          callback(res);
                        }, error:function(err){
                            //
                        }, complete:function(){
                            $('#loader-wrapper').fadeOut('500');
                        }
                  });
        };

        /* call post api */
        apiService.callPost=function(serviceName, dataObj, callback){
            $.ajax({
                        url:window.location.origin+'/villbiz'+serviceName,
                        type:'POST',
                        data:dataObj,
                        cache: false,
                        contentType: false,
                        processData: false,
                        beforeSend:function(xhr){
                          $('#loader-wrapper').fadeIn('500');
                        },
                        success:function(res){
                          callback(res);
                        }, error:function(err){
                          //callback(err);
                        }, complete:function(){
                            $('#loader-wrapper').fadeOut('500');
                        }
                  });
        };
        apiService.callDelete=function(serviceName, dataObj, callback){
            $.ajax({
                        url:window.location.origin+'/villbiz'+serviceName,
                        type:'DELETE',
                        data:dataObj,
                        beforeSend:function(xhr){
                           $('#loader-wrapper').fadeIn('500');
                        },
                        success:function(res){
                          callback(res);
                        }, error:function(err){
                            //
                        }, complete:function(){
                            $('#loader-wrapper').fadeOut('500');
                        }
                  });
        };
        apiService.setData=function(key, value){
            apiService.key=value;
        };
        apiService.getData=function(key){
            return apiService.key;
        };  
    };

    return apiService;
})();
