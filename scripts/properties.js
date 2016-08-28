  $(document).ready(function(){
      $('.slider').slider({full_width: true});
      $('select').material_select();
      $('ul.tabs').tabs();
      $('.modal-trigger').leanModal();
      $('.view-detail-slider').slider('pause');
      $('.indicator-item').click(function(){
          $('.view-detail-slider').slider('pause');
      });
  });
