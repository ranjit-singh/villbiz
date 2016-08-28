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
          location.replace('/villbiz/admin');
        }
        $('select').material_select();
        $('ul.tabs').tabs();
        $('.scrollspy').scrollSpy();
        $('#propType').change(function(){
          villbizApp.callGet('/php/properties/'+this.value, responsePropCallBack);
        });
        $(".button-collapse").sideNav();
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
      if(response.info.status)villbizApp.callGet('/php/properties', responsePropCallBack);
    }
    function responsePropCallBack(response){
      response=JSON.parse(response);
      var propList=response.prop, htmlList='';
      propList.forEach(function(value,index,arr){
        htmlList+='<tr><td>'+value.sno+'</td><td>'+value.type+'</td><td>'+value.title+'</td><td>'+value.cost+'</td><td>'+value.location+'</td>'
                    +'<td>'+value.description+'</td><td>'+value.image+'</td><td class="text-center admin-action">'
                    +'<i class="jif-pencil text-blue" title="Edit"></i><i class="jif-trash text-red" title="Delete"></i></td></tr>';
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
              +'<td class="text-center admin-action"><i class="jif-pencil text-blue" title="Edit"></i><i class="jif-trash text-red" title="Delete"></i></td></tr>';
      });
      $('#coffeePriceList').html(htmlList);
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
              +'<td class="text-center admin-action"><i class="jif-pencil text-blue" title="Edit"></i><i class="jif-trash text-red" title="Delete"></i></td></tr>';
      });
      $('#pepperPriceList').html(htmlList);
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
               +'<td class="text-center admin-action"><i class="jif-pencil text-blue" title="Edit"></i><i class="jif-trash text-red" title="Delete"></i></td></tr>';
      });
      $('#closePriceList').html(htmlList);
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
      htmlList+='<tr><td>'+index+'</td><td>'+value.news+'</td><td class="text-center admin-action"><i class="jif-pencil text-blue" title="Edit"></i><i class="jif-trash text-red" title="Delete"></i></td></tr>';
      });
      $('#newsList').html(htmlList);
    }

    function logOut(){
      villbizApp.callGet('/php/logout', function(resp){
        docCookies.removeItem('PHPSESSID');
        location.replace('/villbiz/admin');
      });
    }