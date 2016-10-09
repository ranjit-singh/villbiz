
var file, flist;
window.imageAvailable=[];
window.deletedImage=[];
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
      this.value='';
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
          location.replace('/villbiz/admin');
        }
        $('select').material_select();
        $('ul.tabs').tabs();
        $('.scrollspy').scrollSpy();
        $('#propType').change(function(){
          villbizApp.callGet('/php/properties/'+this.value, responsePropCallBack);
        });
        $(".button-collapse").sideNav();
        $('.modal-trigger').leanModal();
        init();
    });
    function init(){
      $('#btn-prop').val('ADD PROPERTY');
      $('#prop-img-list').html('');
      $('#create-properties-form').unbind('submit').submit(function(evt){
          evt.stopPropagation();
          addProperties(true, $('#propType').val());
          return false;
      });
      $('#btn-coffee-price').val('Add Coffee Price');
      document.getElementById('coffee-price-form').reset();
      $('#coffee-price-form').unbind('submit').submit(function(evt){
          evt.stopPropagation();
          addCoffeePrice(true);
          return false;
      });
      $('#btn-pepper-price').val('Add Pepper Price');
      document.getElementById('pepper-price-form').reset();
      $('#pepper-price-form').unbind('submit').submit(function(evt){
          evt.stopPropagation();
          addPepperPrice(true);
          return false;
      });
      $('#btn-close-price').val('Add Close Price');
      document.getElementById('close-price-form').reset();
      $('#close-price-form').unbind('submit').submit(function(evt){
          evt.stopPropagation();
          addClosingPrice(true);
          return false;
      });
      $('#btn-news').val('Add News');
      document.getElementById('news-form').reset();
      $('#news-form').unbind('submit').submit(function(evt){
          evt.stopPropagation();
          addNews(true);
          return false;
      });
    }
    function addProperties(isNew, type, id){
      var formdata = new FormData();
          for(var i in flist){
            if(flist[i].name)formdata.append('uploadimage[]', flist[i]);
          }
          formdata.append('title', $('#title').val());
          formdata.append('type', $('#propType').val());
          formdata.append('location', $('#location').val());
          formdata.append('price', $('#price').val());
          formdata.append('desc', $('#description').val());
          if(isNew){
            villbizApp.callPost('/php/createproperties', formdata, function(response){
              response=JSON.parse(response);
              if(response.info.status){
                init();
                villbizApp.callGet('/php/properties/'+type, responsePropCallBack);
                document.getElementById('create-properties-form').reset();
            }
            });
          }else{
            formdata.append('imagelist', imageAvailable);
            formdata.append('deletedImage', deletedImage);
            villbizApp.callPost('/php/properties/'+id, formdata, function(response){
              response=JSON.parse(response);
              if(response.info.status){
                init();
                villbizApp.callGet('/php/properties/'+type, responsePropCallBack);
                document.getElementById('create-properties-form').reset();
            }
            });
          }
    }

    function responsePropCallBack(response){
      response=JSON.parse(response);
      var propList=response.result, htmlList='';
      propList.forEach(function(value,index,arr){
        var imgArray=(value.image).split(','), img_thumb='';
        for(var i in imgArray){
          if(imgArray[i]!=="")img_thumb+='<li><img src="../php/upload/'+imgArray[i]+'"><a href="javascript:deleteImage(\''+value.id+'\', \''+value.type+'\', \''+imgArray[i]+'\');" class="jif-cancel-1 modal-trigger"></a></li>';
        }
        villbizApp.setData(value.id, value.description);
        htmlList+='<tr><td>'+value.sno+'</td><td>'+value.type+'</td><td>'+value.title+'</td><td>'+value.cost+'</td><td>'+value.location+'</td>'
                    +'<td class="td-ell">'+value.description+'</td><td class="propimgtd"><ul class="propimg">'+img_thumb+'</ul></td><td class="text-center admin-action">'
                    +'<i class="jif-pencil text-blue" onclick="editProperties(\''+value.id+'\', \''+value.title+'\', \''+value.location+'\', \''+value.cost+'\', \''+value.image+'\', \''+value.type+'\')" title="Edit"></i><a href="javascript:deleteProperties(\''+value.id+'\',\''+value.type+'\');" title="Delete" class="jif-trash text-red modal-trigger" id='+value.id+'></a></td></tr>';
      });
      $('#listProp').html(htmlList);
    };

    function getCoffeePrice(){
      villbizApp.callGet('/php/coffee/price', coffeeCallBack);
    }
    function addCoffeePrice(isNew, id){
      var coffeeObj={};
      coffeeObj.trader=$('#trader').val();
      coffeeObj.city=$('#city').val();
      coffeeObj.ap=$('#ap').val();
      coffeeObj.ac=$('#ac').val();
      coffeeObj.rp=$('#rp').val();
      coffeeObj.rc=$('#rc').val();
      if(isNew){
        villbizApp.callPost('/php/coffeeprice', JSON.stringify(coffeeObj), function(response){
        response=JSON.parse(response);
        if(response.info.status){
          init();
          villbizApp.callGet('/php/coffee/price', coffeeCallBack);
        }
      });
      }else{
        villbizApp.callPost('/php/coffeeprice/'+id, JSON.stringify(coffeeObj), function(response){
        response=JSON.parse(response);
        if(response.info.status){
          init();
          villbizApp.callGet('/php/coffee/price', coffeeCallBack);
        }
      });
      }
    }

    function coffeeCallBack(response){
      response=JSON.parse(response);
      var htmlList='';
      response.price.forEach(function(value,index,arr){
      htmlList+='<tr><td>'+index+'</td><td>'+value.trader+'</td><td>'+value.city+'</td><td>'+value.ap+'</td><td>'+value.ac+'</td><td>'+value.rp+'</td><td>'+value.rc+'</td>'
              +'<td class="text-center admin-action"><i class="jif-pencil text-blue" title="Edit" onclick="editCoffeePrice(\''+value.id+'\', \''+value.trader+'\', \''+value.city+'\', \''+value.ap+'\', \''+value.ac+'\', \''+value.rp+'\', \''+value.rc+'\')"></i>'
              +'<a href="javascript:deleteCoffeePrice(\''+value.id+'\');" title="Delete" class="jif-trash text-red modal-trigger"></a></td></tr>';
      });
      $('#coffeePriceList').html(htmlList);
       $('.modal-trigger').leanModal();
    }
    function getPepperPrice(){
      villbizApp.callGet('/php/pepper/price', pepperCallBack);
    }
    function addPepperPrice(isNew, id){
      var pepperObj={};
      pepperObj.trader=$('#ptrader').val();
      pepperObj.city=$('#pcity').val();
      pepperObj.quantity=$('#quantity').val();
      pepperObj.brand=$('#brand').val();
      pepperObj.price=$('#pprice').val();
      if(isNew){
        villbizApp.callPost('/php/pepperprice', JSON.stringify(pepperObj), function(response){
        response=JSON.parse(response);
        if(response.info.status){
          init();
          villbizApp.callGet('/php/pepper/price', pepperCallBack);
        }
      });
      }else{
        villbizApp.callPost('/php/pepperprice/'+id, JSON.stringify(pepperObj), function(response){
        response=JSON.parse(response);
        if(response.info.status){
          init();
          villbizApp.callGet('/php/pepper/price', pepperCallBack);
        }
      });
      }
    }

    function pepperCallBack(response){
      response=JSON.parse(response);
      var htmlList='';
      response.price.forEach(function(value,index,arr){
      htmlList+='<tr><td>'+index+'</td><td>'+value.trader+'</td><td>'+value.city+'</td><td>'+value.quantity+'</td><td>'+value.brand+'</td><td>'+value.price+'</td>'
              +'<td class="text-center admin-action"><i class="jif-pencil text-blue" title="Edit" onclick="editPepperPrice(\''+value.id+'\', \''+value.trader+'\', \''+value.city+'\', \''+value.quantity+'\', \''+value.brand+'\', \''+value.price+'\')"></i>'
              +'<a href="javascript:deletePepperPrice(\''+value.id+'\');" title="Delete" class="jif-trash text-red modal-trigger"></a></td></tr>';
      });
      $('#pepperPriceList').html(htmlList);
       $('.modal-trigger').leanModal();
    }

    function addClosingPrice(isNew, id){
      var changeObj={};
      changeObj.name=$('#chname').val();
      changeObj.price=$('#chprice').val();
      changeObj.change=$('#clchange').val();
      changeObj.changepercent=$('#changepercent').val();
      if(isNew){
        villbizApp.callPost('/php/closeprice', JSON.stringify(changeObj), function(response){
        response=JSON.parse(response);
        if(response.info.status){
          init();
          villbizApp.callGet('/php/close/price', closeCallBack);
        }
        });
      }else{
        villbizApp.callPost('/php/closeprice/'+id, JSON.stringify(changeObj), function(response){
        response=JSON.parse(response);
         if(response.info.status){
          init();
          villbizApp.callGet('/php/close/price', closeCallBack);
        }
      });
      }
      
    }
    function getClosePrice(){
      villbizApp.callGet('/php/close/price', closeCallBack);
    }
    function closeCallBack(response){
      response=JSON.parse(response);
      var htmlList='';
      response.price.forEach(function(value,index,arr){
      htmlList+='<tr><td>'+index+'</td><td>'+value.name+'</td><td>'+value.price+'</td><td>'+value.clchange+'</td><td>'+value.change_percent+'</td>'
               +'<td class="text-center admin-action"><i class="jif-pencil text-blue" title="Edit" onclick="editClosePrice(\''+value.id+'\', \''+value.name+'\', \''+value.price+'\', \''+value.clchange+'\', \''+value.change_percent+'\')"></i>'
               +'<a href="javascript:deleteClosePrice(\''+value.id+'\');" title="Delete" class="jif-trash text-red modal-trigger"></a></td></tr>';
      });
      $('#closePriceList').html(htmlList);
       $('.modal-trigger').leanModal();
    }

    function addNews(isNew, id){
      var newsObj={};
      newsObj.news=$('#newsdescription').val();
      if(isNew){
        villbizApp.callPost('/php/news', JSON.stringify(newsObj), function(response){
        response=JSON.parse(response);
         if(response.info.status){
          init();
          villbizApp.callGet('/php/news', newsCallBack);
        }
      });
      }else{
        villbizApp.callPost('/php/news/'+id, JSON.stringify(newsObj), function(response){
        response=JSON.parse(response);
        if(response.info.status){
          init();
          villbizApp.callGet('/php/news', newsCallBack);
        }
      });
      }
      
    }
    function getNews(){
      villbizApp.callGet('/php/news', newsCallBack);
    }
    function newsCallBack(response){
      response=JSON.parse(response);
      var htmlList='';
      response.news.forEach(function(value,index,arr){
      htmlList+='<tr><td>'+index+'</td><td>'+value.news+'</td><td class="text-center admin-action">'
      +'<i class="jif-pencil text-blue" title="Edit" onclick="editNews(\''+value.id+'\', \''+value.news+'\')"></i>'
      +'<a href="javascript:deleteNews(\''+value.id+'\');" title="Delete" class="jif-trash text-red modal-trigger"></a></td></tr>';
      });
      $('#newsList').html(htmlList);
      $('.modal-trigger').leanModal();
    }

    function getContacts(){
      villbizApp.callGet('/php/contactus', contactCallBack);
    }

    function contactCallBack(response){
        response=JSON.parse(response);
        var htmlList='';
        response.contact.forEach(function(value,index,arr){
          htmlList+='<tr><td>'+index+'</td><td>'+value.name+'</td><td>'+value.email+'</td><td>'+value.mobile+'</td>'
          +'<td>'+value.message+'</td><td>'+value.created_date+'</td><td class="text-center admin-action"><a href="javascript:deleteContact(\''+value.id+'\');" title="Delete" class="jif-trash text-red modal-trigger"></a></td></tr>';
        });
        $('#contactusList').html(htmlList);
        $('.modal-trigger').leanModal();
    }
    function logOut(){
      villbizApp.callGet('/php/logout', function(resp){
        docCookies.removeItem('PHPSESSID');
        location.replace('/admin');
      });
    }
    
    function editProperties(id, title, location, price, img, type){
            deletedImage=[];
            $('#propType').val(type).change();
            $('#btn-prop').val('UPDATE PROPERTY');
            
        var img=img.split(','), desc=villbizApp.getData(id);
            imageAvailable=img.slice();
            $('#title').val(title);
            $('#location').val(location);
            $('#price').val(price);
            $('#description').val(desc);
            if(imageAvailable.indexOf("")!=-1)imageAvailable.splice(imageAvailable.indexOf(""), 1);
        var imgList='';
            for(var i in imageAvailable){
              if(imageAvailable!=="")imgList+='<li><img src="../php/upload/'+imageAvailable[i]+'"><a href="javascript:removeImage(\''+imageAvailable[i]+'\');" class="jif-cancel-1 modal-trigger"></a></li>';
            }
            $('#prop-img-list').removeClass('hide').html(imgList);
            $('#create-properties-form').unbind('submit').submit(function(evt){
                  evt.stopPropagation();
                  addProperties(false, type, id);
                  return false;
              });
    }

    function editCoffeePrice(id,trader,city,ap,ac,rp,rc){
      $('#btn-coffee-price').val('Update Coffee Price');
       $('#coffee-price-form').unbind('submit').submit(function(evt){
          evt.stopPropagation();
          addCoffeePrice(false, id);
          return false;
      });
      $('#trader').val(trader);
      $('#city').val(city);
      $('#ap').val(ap);
      $('#ac').val(ac);
      $('#rp').val(rp);
      $('#rc').val(rc);
    }

    function editPepperPrice(id,trader,city,quantity,brand,price){
      $('#btn-pepper-price').val('Update Pepper Price');
      $('#pepper-price-form').unbind('submit').submit(function(evt){
          evt.stopPropagation();
          addPepperPrice(false, id);
          return false;
      });
      $('#ptrader').val(trader);
      $('#pcity').val(city);
      $('#quantity').val(quantity);
      $('#brand').val(brand);
      $('#pprice').val(price);
    }

    function editClosePrice(id,name,price,changepercent,change_percent){
      $('#btn-close-price').val('Update Close Price');
      $('#close-price-form').unbind('submit').submit(function(evt){
          evt.stopPropagation();
          addClosingPrice(false, id);
          return false;
      });
      $('#chname').val(name);
      $('#chprice').val(price);
      $('#clchange').val(changepercent);
      $('#changepercent').val(change_percent);
    }
    function editNews(id, news){
      $('#btn-news').val('Update News');
      $('#news-form').unbind('submit').submit(function(evt){
          evt.stopPropagation();
          addNews(false, id);
          return false;
      });
      $('#newsdescription').val(news);
    }
    function removeImage(imageName){
      var imgList='';
      deletedImage.push(imageAvailable.slice(imageAvailable.indexOf(imageName), 1));
      imageAvailable.splice(imageAvailable.indexOf(imageName), 1);
      for(var i in imageAvailable){
              imgList+='<li><img src="../php/upload/'+imageAvailable[i]+'"><a href="javascript:removeImage(\''+imageAvailable[i]+'\');" class="jif-cancel-1 modal-trigger"></a></li>';
      }
      $('#prop-img-list').removeClass('hide').html(imgList);
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

   function deleteContact(id){
     $('#delete-property-popup').openModal();
     $('#confirm-delete-prop').unbind('click').click(function(evt){
      villbizApp.callDelete('/php/contactus/'+id, function(resp){
        resp=JSON.parse(resp);
        $('#delete-property-popup').closeModal();
        if(resp.info.status)villbizApp.callGet('/php/contactus', contactCallBack);
      });
     });
   }