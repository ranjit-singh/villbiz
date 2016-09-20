var type=docCookies.getItem('type');
$(document).ready(function(){
    villbizApp.initialize();
    $('select').material_select();
    $('ul.tabs').tabs();
    $('.modal-trigger').leanModal();
    $('#propType').change(function(){
      villbizApp.callGet('/php/location/'+this.value, function(resp){
        resp=JSON.parse(resp);
    var htmlList='<option value="" disabled selected>Search Location</option>';
        resp.result.forEach(function(value,index,arr){
            htmlList+='<option value="'+value.location+'">'+value.location+'</option>';
        });
          $('#locationList').html(htmlList);
          $('select').material_select();
      });
    });
    $(".prop-checkbox").change(function(){
      if(this.checked){
        $(".tile-list-box").addClass('property-list-view');
      } else {
        $(".tile-list-box").removeClass('property-list-view');
      }
    });

    
     switch(type){
            case 'coffee': {
                        $("#properties, #mob-properties").closest('li').addClass('active').siblings().removeClass('active');
                        villbizApp.callGet('/php/properties/'+type, successCallBack);
                        break;
                  }
            case 'agriculture': {
                        $("#properties, #mob-properties").closest('li').addClass('active').siblings().removeClass('active');
                        villbizApp.callGet('/php/properties/'+type, successCallBack);
                        break;
                }
                  
            case 'tea': {
                        $("#properties, #mob-properties").closest('li').addClass('active').siblings().removeClass('active');
                        villbizApp.callGet('/php/properties/'+type, successCallBack);
                        break;
                      }
            case 'house': {
                        $("#properties, #mob-properties").closest('li').addClass('active').siblings().removeClass('active');
                        villbizApp.callGet('/php/properties/'+type, successCallBack);
                        break;
                      }
            case 'business': {
                        $("#properties, #mob-properties").closest('li').addClass('active').siblings().removeClass('active');
                        villbizApp.callGet('/php/properties/'+type, successCallBack);
                        break;
                      }
      }
    $('#searchProp').unbind('click').click(function(e){
      e.stopPropagation();
      searchPropertiesByPage(false);
    });
    $('#searchByIdFrm').unbind('submit').submit(function(e){
      e.stopPropagation();
      searchPropertiesByPage(true);
      return false;
    });
    function searchPropertiesByPage(isSearchById){
       var locObj={}, type='/null';
        if(isSearchById){
          locObj.flag=false;
          locObj.searchId=$('#searchId').val();
        }
        else{
          locObj.flag=true;
          locObj.locationList=$('#locationList').val();
          type='/'+$('#propType').val();
        }
        villbizApp.callPost('/php/search'+type, JSON.stringify(locObj), successCallBack);
    }

    function successCallBack(resp){
        resp=JSON.parse(resp);
        if(resp.status && resp.result.length > 0){
           $('#prop-name-count').html(villbizApp.properties[resp.result[0].type]+' - <span>'+resp.result.length+' Items</span>');
         var htmlList='';
             resp.result.forEach(function(value, indx, arr){
               var imgList=(value.image).split(',');
               htmlList+='<div class="property-card-container"><div class="card"><div class="card-image waves-effect waves-block waves-light">'
                      +'<a href="detail.html?aid='+value.sno+'" target="_blank"><img class="activator" src="php/upload/'+imgList[0]+'"><div class="prop-ad-id">'+value.sno+'</div></a> </div>'
                      +'<div class="card-content"><div class="card-content-body"><div class="property-title font-600">'+value.title+'</div>'
                    +'<div class="property-divider"></div><div class="property-location text-ellipsis"><span class="font-600">Location : </span><span>'+value.location+'</span></div><div class="property-divider"></div>'
                    +'<div class="property-price font-600 text-ellipsis"><i class="jif-rupee"></i>&nbsp;<span>'+value.cost+'</span></div></div>'
                    +'<div class="card-content-footer"><div class="property-detail-btn">'
                    +'<a href="javascript:showPropDetail(\''+value.sno+'\', \''+value.title+'\',\''+value.description+'\', \''+value.cost+'\', \''+value.type+'\', \''+value.image+'\', \''+value.location+'\');" class="waves-effect waves-light btn modal-trigger">View Detail</a></div></div></div></div></div>';
             });
             $('#properties-list').html(htmlList);
        }
      }
});
