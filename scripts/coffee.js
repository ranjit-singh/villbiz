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
});

