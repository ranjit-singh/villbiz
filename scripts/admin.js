
var file, flist;
(function() {
var input = document.getElementById('upload-icon');
$(input).bind("change",function(evt) {
    var i = 0, len = this.files.length, reader;
    flist=this.files;
    for (; i < len; i++) {
    file = this.files[i];
    var permittedFileType = ['png', 'jpg', 'jpeg','bmp', 'gif' ];
    var fval = file.name.split('.').pop().toLowerCase();
    var resultFile = validate_filetype(fval, permittedFileType);
    if (resultFile === true) {
      if (window.FileReader) {
        reader = new FileReader();
        reader.onloadend = function(e) {
        };
        reader.readAsDataURL(file);
      }
      // var totalsz = 0;
      // totalsz = file.size;
      // if (totalsz > 512000) {
      //   alert("Image max size allowed upto 500 KB");
      // }
    }
    else {
      alert("Invalid image format.\n Try with png, jpg, bmp, gif formats.");
    }
  }
});
function validate_filetype(fext, ftype) {
  for ( var num in ftype) {
    if (fext == ftype[num])
      return true;
  }
  return false;
}
})();
    $(document).ready(function(){
        villbizApp.initialize();
        if(!docCookies.getItem('PHPSESSID')){
          //location.replace('/villbiz/admin');
        }
        $('select').material_select();
        $('ul.tabs').tabs();
        $('.scrollspy').scrollSpy();
        $('#propType').change(function(){
          villbizApp.callGet('/php/properties/'+this.value, responsePropCallBack);
        });
        $(".button-collapse").sideNav();
        $('.modal-trigger').leanModal();
    });
    function addProperties(){
      var formdata = new FormData();
          for(var i in flist){
            formdata.append('uploadimage[]', flist[i]);
          }
          formdata.append('title', $('#title').val());
          formdata.append('type', $('#propType').val());
          formdata.append('location', $('#location').val());
          formdata.append('price', $('#price').val());
          formdata.append('desc', $('#description').val());
          villbizApp.callPost('/php/createproperties', formdata, propCallBack);
          return false;
    }
    function propCallBack(response){
      response=JSON.parse(response);
      if(response.info.status)villbizApp.callGet('/php/properties/'+$('#propType').val(), responsePropCallBack);
      document.getElementById('create-properties-form').reset();
    }
    function responsePropCallBack(response){
      response=JSON.parse(response);
      var propList=response.prop, htmlList='';
      propList.forEach(function(value,index,arr){
        var imgArray=(value.image).split(','), img_thumb='';
        for(var i in imgArray){
          img_thumb+='<li><img src="../php/upload/'+imgArray[i]+'"><a href="javascript:deleteImage(\''+value.id+'\', \''+value.type+'\', \''+imgArray[i]+'\');" class="jif-cancel-1 modal-trigger"></a></li>';
        }
        htmlList+='<tr><td>'+value.sno+'</td><td>'+value.type+'</td><td>'+value.title+'</td><td>'+value.cost+'</td><td>'+value.location+'</td>'
                    +'<td class="td-ell">'+value.description+'</td><td class="propimgtd"><ul class="propimg">'+img_thumb+'</ul></td><td class="text-center admin-action">'
                    +'<i class="jif-pencil text-blue" onclick="editProperties(\''+value.id+'\', \''+value.title+'\', \''+value.location+'\', \''+value.description+'\', \''+value.cost+'\', \''+value.image+'\')" title="Edit"></i><a href="javascript:deleteProperties(\''+value.id+'\',\''+value.type+'\');" title="Delete" class="jif-trash text-red modal-trigger" id='+value.id+'></a></td></tr>';
      });
      $('#listProp').html(htmlList);
    };

    function getCoffeePrice(){
      villbizApp.callGet('/php/coffee/price', coffeeCallBack);
    }
    function addCoffeePrice(){
      var coffeeObj={};
      coffeeObj.trader=$('#trader').val();
      coffeeObj.city=$('#city').val();
      coffeeObj.ap=$('#ap').val();
      coffeeObj.ac=$('#ac').val();
      coffeeObj.rp=$('#rp').val();
      coffeeObj.rc=$('#rc').val();
      villbizApp.callPost('/php/coffeeprice', JSON.stringify(coffeeObj), function(response){
        response=JSON.parse(response);
        if(response.info.status)villbizApp.callGet('/php/coffee/price', coffeeCallBack);
      });
      return false;
    }

    function coffeeCallBack(response){
      response=JSON.parse(response);
      var htmlList='';
      response.price.forEach(function(value,index,arr){
      htmlList+='<tr><td>'+index+'</td><td>'+value.trader+'</td><td>'+value.city+'</td><td>'+value.ap+'</td><td>'+value.ac+'</td><td>'+value.rp+'</td><td>'+value.rc+'</td>'
              +'<td class="text-center admin-action"><i class="jif-pencil text-blue" title="Edit"></i><a href="javascript:deleteCoffeePrice(\''+value.id+'\');" title="Delete" class="jif-trash text-red modal-trigger"></a></td></tr>';
      });
      $('#coffeePriceList').html(htmlList);
       $('.modal-trigger').leanModal();
    }
    function getPepperPrice(){
      villbizApp.callGet('/php/pepper/price', pepperCallBack);
    }
    function addPepperPrice(){
      var pepperObj={};
      pepperObj.trader=$('#ptrader').val();
      pepperObj.city=$('#pcity').val();
      pepperObj.quantity=$('#quantity').val();
      pepperObj.brand=$('#brand').val();
      pepperObj.price=$('#pprice').val();
      villbizApp.callPost('/php/pepperprice', JSON.stringify(pepperObj), function(response){
        response=JSON.parse(response);
        if(response.info.status)villbizApp.callGet('/php/pepper/price', pepperCallBack);
      });
      return false;
    }

    function pepperCallBack(response){
      response=JSON.parse(response);
      var htmlList='';
      response.price.forEach(function(value,index,arr){
      htmlList+='<tr><td>'+index+'</td><td>'+value.trader+'</td><td>'+value.city+'</td><td>'+value.quantity+'</td><td>'+value.brand+'</td><td>'+value.price+'</td>'
              +'<td class="text-center admin-action"><i class="jif-pencil text-blue" title="Edit"></i><a href="javascript:deletePepperPrice(\''+value.id+'\');" title="Delete" class="jif-trash text-red modal-trigger"></a></td></tr>';
      });
      $('#pepperPriceList').html(htmlList);
       $('.modal-trigger').leanModal();
    }

    function addCloseingPrice(){
      var changeObj={};
      changeObj.name=$('#chname').val();
      changeObj.price=$('#chprice').val();
      changeObj.change=$('#clchange').val();
      changeObj.changepercent=$('#changepercent').val();
      villbizApp.callPost('/php/closeprice', JSON.stringify(changeObj), function(response){
        response=JSON.parse(response);
        if(response.info.status)villbizApp.callGet('/php/close/price', closeCallBack);
      });
      return false;
    }
    function getClosePrice(){
      villbizApp.callGet('/php/close/price', closeCallBack);
    }
    function closeCallBack(response){
      response=JSON.parse(response);
      var htmlList='';
      response.price.forEach(function(value,index,arr){
      htmlList+='<tr><td>'+index+'</td><td>'+value.name+'</td><td>'+value.price+'</td><td>'+value.clchange+'</td><td>'+value.change_percent+'</td>'
               +'<td class="text-center admin-action"><i class="jif-pencil text-blue" title="Edit"></i><a href="javascript:deleteClosePrice(\''+value.id+'\');" title="Delete" class="jif-trash text-red modal-trigger"></a></td></tr>';
      });
      $('#closePriceList').html(htmlList);
       $('.modal-trigger').leanModal();
    }

    function addNews(){
      var newsObj={};
      newsObj.news=$('#newsdescription').val();
      villbizApp.callPost('/php/news', JSON.stringify(newsObj), function(response){
        response=JSON.parse(response);
        if(response.info.status)villbizApp.callGet('/php/news', newsCallBack);
      });
      return false;
    }
    function getNews(){
      villbizApp.callGet('/php/news', newsCallBack);
    }
    function newsCallBack(response){
      response=JSON.parse(response);
      var htmlList='';
      response.news.forEach(function(value,index,arr){
      htmlList+='<tr><td>'+index+'</td><td>'+value.news+'</td><td class="text-center admin-action"><i class="jif-pencil text-blue" title="Edit"></i><a href="javascript:deleteNews(\''+value.id+'\');" title="Delete" class="jif-trash text-red modal-trigger"></a></td></tr>';
      });
      $('#newsList').html(htmlList);
       $('.modal-trigger').leanModal();
    }

    function logOut(){
      villbizApp.callGet('/php/logout', function(resp){
        docCookies.removeItem('PHPSESSID');
        location.replace('/villbiz/admin');
      });
    }

    function editProperties(id, title, location, desc, price, img){
        var formdata = new FormData();
            for(var i in flist){
              formdata.append('uploadimage[]', flist[i]);
            }
            
        var prop_title=$('#title'),
            prop_location=$('#location'),
            prop_price=$('#price'),
            prop_desc=$('#description'),
            img=img.split(',');
            prop_title.val(title);
            prop_location.val(location);
            prop_price.val(price);
            prop_desc.val(desc);
        var imgList='';
            for(var i in img){
              imgList+='<li><img src="../php/upload/'+img[i]+'"><a href="#delete-image-popup" class="jif-cancel-1 modal-trigger"></a></li>';
            }
            $('#prop-img-list').removeClass('hide').html(imgList);
            //villbizApp.callPost('/php/updateproperties/'+id, formdata, propCallBack);
            return false;
    }
   function deleteProperties(id, type){
     $('#delete-property-popup').openModal();
     $('#confirm-delete-prop').unbind('click').click(function(evt){
      villbizApp.callDelete('/php/properties/'+id, function(resp){
        resp=JSON.parse(resp);
        $('#delete-property-popup').closeModal();
        if(resp.info.status)villbizApp.callGet('/php/properties/'+type, responsePropCallBack);
      });
     });
   }
   function deleteCoffeePrice(id){
     $('#delete-property-popup').openModal();
     $('#confirm-delete-prop').unbind('click').click(function(evt){
      villbizApp.callDelete('/php/coffee/price/'+id, function(resp){
        resp=JSON.parse(resp);
        $('#delete-property-popup').closeModal();
        if(resp.info.status)villbizApp.callGet('/php/coffee/price', coffeeCallBack);
      });
     });
   }
   function deletePepperPrice(id){
     $('#delete-property-popup').openModal();
     $('#confirm-delete-prop').unbind('click').click(function(evt){
      villbizApp.callDelete('/php/pepper/price/'+id, function(resp){
        resp=JSON.parse(resp);
        $('#delete-property-popup').closeModal();
        if(resp.info.status)villbizApp.callGet('/php/pepper/price', pepperCallBack);
      });
     });
   }
   function deleteClosePrice(id){
     $('#delete-property-popup').openModal();
     $('#confirm-delete-prop').unbind('click').click(function(evt){
      villbizApp.callDelete('/php/close/price/'+id, function(resp){
        resp=JSON.parse(resp);
        $('#delete-property-popup').closeModal();
        if(resp.info.status)villbizApp.callGet('/php/close/price', closeCallBack);
      });
     });
   }
   function deleteNews(id){
     $('#delete-property-popup').openModal();
     $('#confirm-delete-prop').unbind('click').click(function(evt){
      villbizApp.callDelete('/php/news/'+id, function(resp){
        resp=JSON.parse(resp);
        $('#delete-property-popup').closeModal();
        if(resp.info.status)villbizApp.callGet('/php/news', newsCallBack);
      });
     });
   }
   function deleteImage(id, type, imageName){
     $('#delete-property-popup').openModal();
     $('#confirm-delete-prop').unbind('click').click(function(evt){
      villbizApp.callDelete('/php/properties/image/'+id+'/'+imageName, function(resp){
        resp=JSON.parse(resp);
        $('#delete-property-popup').closeModal();
        if(resp.info.status)villbizApp.callGet('/php/properties/'+type, responsePropCallBack);
      });
     });
   }