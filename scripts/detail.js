$(document).ready(function(){
	villbizApp.initialize();
      $( "#header" ).load( "views/home/header.html", function(e){
      	$('.nav-wrapper').find('ul li:first-child').removeClass('active');
      	$('.nav-wrapper').find('ul li:nth-child(2)').addClass('active');
      });
	  $('.slider').slider({full_width: true});
      $('ul.tabs').tabs();
      $('.scrollspy').scrollSpy();
      $('.view-detail-slider').slider('pause');
      $('.indicator-item').click(function(){
          $('.view-detail-slider').slider('pause');
      });
      var aid=$.QueryString.aid;
      $( "#footer" ).load( "views/home/footer.html" );
       var locObj={}, type='/null';
     	   locObj.flag=false;
	       locObj.searchId=aid;

		   villbizApp.callPost('/php/search'+type, JSON.stringify(locObj), function(resp){
		   		resp=JSON.parse(resp);
		   		if(resp.result.length > 0){
		   		  var img=resp.result[0].image, location=resp.result[0].location, cost=resp.result[0].cost,
		   		  	  title=resp.result[0].title, type=resp.result[0].type, desc=resp.result[0].description;
		   		  var imgList=img.split(','), imgHtml='';
	
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
			      $('#fb-share-iframe').attr('src', 'https://www.facebook.com/plugins/share_button.php?href=http://villbiz.com/detail.html?aid='+aid+'&layout=button&size=small&mobile_iframe=true&width=59&height=20&appId');
			      $('#google-plus-share').attr('data-href', 'http://villbiz.com/details.html?aid='+aid);
		   		}
		   });
});