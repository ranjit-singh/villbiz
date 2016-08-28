$(document).ready(function(){
      villbizApp.initialize();
      $( "#header" ).load( "app/views/home/header.html" );
      $( "#mainViewContainer" ).load( "app/views/home/main.html", function(){
        $('.slider').slider({full_width: true});
        $('select').material_select();
        $('ul.tabs').tabs();
        $('.scrollspy').scrollSpy();
        $('.modal-trigger').leanModal();
        $('.view-detail-slider').slider('pause');
        $('.indicator-item').click(function(){
            $('.view-detail-slider').slider('pause');
        });
      });
      $( "#footer" ).load( "app/views/home/footer.html" );
  });
function searchProperties(isSearchById){
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
      
      villbizApp.callPost('/php/search'+type, JSON.stringify(locObj), function(resp){
        resp=JSON.parse(resp);
         $( "#mainViewContainer" ).load( "app/views/properties/coffee.html", function(){
            var htmlList='';
             resp.result.forEach(function(value, indx, arr){
               var imgList=(value.image).split(',');
               htmlList+='<div class="property-card-container"><div class="card"><div class="card-image waves-effect waves-block waves-light">'
                      +'<a href="detail.html" target="_blank"><img class="activator" src="php/upload/'+imgList[0]+'"><div class="prop-ad-id">'+value.sno+'</div></a> </div>'
                      +'<div class="card-content"><div class="card-content-body"><div class="property-title font-600">'+value.title+'</div>'
                    +'<div class="property-divider"></div><div class="property-location"><span class="font-600">Location : </span><span>'+value.location+'</span></div><div class="property-divider"></div>'
                    +'<div class="property-price font-600"><i class="jif-rupee"></i><span>'+value.cost+'</span></div></div>'
                    +'<div class="card-content-footer"><div class="property-detail-btn">'
                    +'<a href="javascript:showPropDetail(\''+value.sno+'\', \''+value.title+'\',\''+value.description+'\', \''+value.cost+'\', \''+value.type+'\', \''+value.image+'\', \''+value.location+'\');" class="waves-effect waves-light btn modal-trigger">View Detail</a></div></div></div></div></div>';
             });
             $('#properties-list').html(htmlList);
             switch(resp.result[0].type){
                case 'coffee': $('#prop-name-count').html('Coffee Estates - <span>'+resp.result.length+' Items</span>');
                      break;
                case 'agriculture': $('#prop-name-count').html('Agriculture Land - <span>'+resp.result.length+' Items</span>');
                      break;
                case 'tea': $('#prop-name-count').html('Rubber & Tea Estates - <span>'+resp.result.length+' Items</span>');
                      break;
                case 'house': $('#prop-name-count').html('Houses & Plots - <span>'+resp.result.length+' Items</span>');
                      break;
                case 'business': $('#prop-name-count').html('Business - <span>'+resp.result.length+' Items</span>');
                      break;
             }
         });
      });
      return false;
}
function showPropDetail(aid, title,desc,cost,type,img,location){
  var imgList=img.split(','), imgHtml='';
      $('#prop-detail-modal').openModal();
      for(var i in imgList){
        imgHtml+='<li><img src="php/upload/'+imgList[i]+'"></li>';
      }
      $('#img-list-banner').html(imgHtml);
      $('#prop-aid').html('Ad id : '+aid);
      $('#location-detail').html(location);
      $('#prop-cost').html(cost);
      $('#prop-title').html(title);
      $('#prop-description').html(desc);
     
      $('.slider').slider({full_width: true});
      $('.view-detail-slider').slider('pause');
      $('.indicator-item').click(function(){
          $('.view-detail-slider').slider('pause');
      });
       switch(type){
            case 'coffee': $('#prop-name').html('Coffee Estate');
                  break;
            case 'agriculture': $('#prop-name').html('Agriculture Land');
                  break;
            case 'tea': $('#prop-name').html('Rubber & Tea Estates');
                  break;
            case 'house': $('#prop-name').html('Houses & Plots');
                  break;
            case 'business': $('#prop-name').html('Business');
                  break;
         }
}
