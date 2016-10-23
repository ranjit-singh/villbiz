<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Vilbiz</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta id="viewport" name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>
    <meta description="Villbiz – coffee tea agriculture business rubber house plots recognized as the talented and the best agencies in the world. We aim to create a meeting point where people across the world can come to find properties. A place to share knowledge and experience, give and receive constructive and respectful critiques" />
    <meta name="keywords" content="villbiz, coffee, tea, agriculture, business, rubber, house, plots" />

    <link rel="shortcut icon" href="assets/images/fav.png">
    <link href="assets/fonts/css/font.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/materialize.css">
    <link rel="stylesheet" type="text/css" href="styles/style.css">
  </head>
  <body>
  <!-- <div id="fb-root"></div>
  <script>(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.8&appId=194643794309548";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));</script> -->
  <?php
	require 'php/database.php';
	$id=$_GET['aid'];
	$sql="select * from properties where sno='".$id."' && status='ACTIVE' order by sno asc";
	$db = getConnection();
    $stmt = $db->query($sql);
    $result = $stmt->fetchAll(PDO::FETCH_OBJ);
    $db=null;
    $imgList=explode(",",$result[0]->image);
    $type=$result[0]->type;
  ?>
    <!--[if lt IE 10]>
      <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->
    <div class="wrapper main-view-container">
      <div id="header"></div>
      <div id="mainViewContainer">
      <div class="view-detail-ext-container">
    <h5 class="text-center view-detail-modal-title" id="prop-name"></h5>
    <hr>
    <div class="container">
      <div class="row">
        <div class="col s12 l5">
          <div class="slider view-detail-slider">
              <ul class="slides">
              	  <?php
              	  	for($i=0; $i < count($imgList); $i++){
              	  	?>
              	  		<li><img src="php/upload/<?php echo $imgList[$i]?>"></li>
              	    <?php
              	  	}
              	  ?>
              </ul>
          </div>
          <hr>
          <div class="col s12 no-padding">
            <div class="view-detail-ad-id">Ad id : <?php echo $result[0]->sno?></div>
          </div>
          <div class="col 12 no-padding view-detail-left">
            <h5>Information</h5>
            <div class="view-detail-location">
              <i class="jif-location text-green"></i>
              <span class="font-600">Location : </span>
              <span><?php echo $result[0]->location?></span>
            </div>
            <div class="view-detail-location margin-top-10">
              <i class="jif-rupee text-green"></i>
              <span class="font-600">Price : </span>
              <span><?php echo $result[0]->cost?></span>
            </div>
          </div>
          <hr>
          <div class="col s12 no-padding view-detail-left">
            <h5>Contact Us</h5>
            <div class="view-detail-location">
              <div class="left view-detail-ph">
                <i class="jif-call text-green"></i>
                <span>+91 9844157265</span>
              </div>
              <div class="left">
                <i class="jif-mail text-green"></i>
                <span>estates@villbiz.com</span>
              </div>
            </div>
          </div>
          <hr>
          <div class="col s12 no-padding view-detail-left">
            <div class="view-detail-location margin-top-10">
              <div class="left view-detail-sm margin-right-10 social-media-prop-share">
              <!--   <div class="fb-share-button" data-href="http://villbiz.com/" data-layout="button_count" data-size="small" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fdevelopers.facebook.com%2Fdocs%2Fplugins%2F&amp;src=sdkpreparse">Share</a></div> -->
              <iframe width="59" height="20" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true" src="https://www.facebook.com/plugins/share_button.php?href=http://villbiz.com/details.php?aid=<?php echo $id ?>&layout=button&size=small&mobile_iframe=true&width=59&height=20&appId=194643794309548"></iframe>
              </div>
              <div class="left view-detail-sm gplus-icon">
                <!-- Place this tag where you want the share button to render. -->
                <div class="g-plus" data-action="share" data-annotation="none" data-href="http://villbiz.com/details.php?aid=<?php echo $id ?>"></div>

                <!-- Place this tag after the last share tag. -->
                <script type="text/javascript">
                  (function() {
                    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
                    po.src = 'https://apis.google.com/js/platform.js';
                    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
                    var type='<?=$type?>';
                    switch(type){
			            case 'coffee': document.getElementById('prop-name').innerHTML='Coffee Estate';
			                  break;
			            case 'agriculture': document.getElementById('prop-name').innerHTML='Agriculture Land';
			                  break;
			            case 'tea': document.getElementById('prop-name').innerHTML='Rubber & Tea Estates';
			                  break;
			            case 'house': document.getElementById('prop-name').innerHTML='Houses & Plots';
			                  break;
			            case 'business': document.getElementById('prop-name').innerHTML='Business';
			                  break;
			       }
                  })();
                </script>
              </div>
            </div>
          </div>
          <hr class="no-margin">
        </div>
        <div class="col s12 l7 view-detail-prop-maindetail">
          <h5 class="text-dark"><?php echo $result[0]->title?></h5>
          <div class="green-orange-border-sep"></div>
          <p>
            <?php
            echo $result[0]->description;
            ?>
          </p>
          <p>
        </div>
      </div>
    </div>
  </div>

      </div>
      <div id="footer"></div>
    </div>
    <div id="loader-wrapper">
    <div id="loader"></div>
    </div>
    <script src="scripts/jquery.min.js"></script>
    <script src="scripts/materialize.min.js"></script>
    <script src="scripts/cookies.js"></script>
    <script src="scripts/common.js"></script>
    <script type="text/javascript">
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
      $( "#footer" ).load( "views/home/footer.html" );
	});
	</script>
  </body>
</html>