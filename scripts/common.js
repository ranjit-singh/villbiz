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
        apiService.callDelete=function(serviceName, callback){
            $.ajax({
                        url:window.location.origin+'/villbiz'+serviceName,
                        type:'DELETE',
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
        apiService.setData=function(keyName, value){
            apiService[keyName]=value;
        };
        apiService.getData=function(keyName){
            return apiService[keyName];
        };  
    };

    return apiService;
})();
(function($) {
    $.QueryString = (function(a) {
        if (a == "") return {};
        var b = {};
        for (var i = 0; i < a.length; ++i)
        {
            var p=a[i].split('=', 2);
            if (p.length != 2) continue;
            b[p[0]] = decodeURIComponent(p[1].replace(/\+/g, " "));
        }
        return b;
    })(window.location.search.substr(1).split('&'))
})(jQuery);