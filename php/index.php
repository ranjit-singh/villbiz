<?php
require 'database.php';
require 'routes.php';
  function getGUID(){
    if (function_exists('com_create_guid')){
        $uuid=com_create_guid();
        $uuid=substr($uuid, 1, strlen($uuid)-2);
        return $uuid;
    }else{
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid =substr($charid, 0, 8).$hyphen
              .substr($charid, 8, 4).$hyphen
              .substr($charid,12, 4).$hyphen
              .substr($charid,16, 4).$hyphen
              .substr($charid,20,12);
        return $uuid;
    }
  }
  function getRealIpAddr()
  {
      if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
      {
        $ip=$_SERVER['HTTP_CLIENT_IP'];
      }
      elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
      {
        $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
      }
      else
      {
        $ip=$_SERVER['REMOTE_ADDR'];
      }
      return $ip;
  }

  function checkValidate(){
    try {
      if( $_COOKIE["PHPSESSID"] ){
          return 1;
        }else{
          echo '{"error":{"message":"You are not authorize.", "status":false}}';
          return 0;
        }
    } catch (Exception $e) {
      echo '{"error":{"message":"You are not authorize.", "status":false}}';
      return 0;
    }
    
  }

  function getProperties($type){
        try {
          $sql = "select sno,id,title,type,cost,location,description,image from properties where type='".$type."' && status='ACTIVE' order by sno desc";
          $db = getConnection();
          $stmt = $db->query($sql);
          $properties = $stmt->fetchAll(PDO::FETCH_OBJ);
          $db = null;
          echo '{"result": ' . json_encode($properties) . ', "status":true}';
        } catch (PDOException $e) {
          error_log($e->getMessage(), 3, 'phperror.log'); //Write error log
          echo '{"error":{"text":'. $e->getMessage() .'}}';
        }

  }
  function getCoffeePrice(){
        try {
          if(checkValidate()){
          $sql = "select id,trader,city,ap,ac,rp,rc from coffee_price where status='ACTIVE' order by created_date desc";
          $db = getConnection();
          $stmt = $db->query($sql);
          $properties = $stmt->fetchAll(PDO::FETCH_OBJ);
          $db = null;
          echo '{"price": ' . json_encode($properties) . ', "status":true}';
          }
        } catch (PDOException $e) {
          error_log($e->getMessage(), 3, 'phperror.log'); //Write error log
          echo '{"error":{"text":'. $e->getMessage() .'}}';
        }
  }
  function getPepperPrice(){
        try {
          if(checkValidate()){
            $sql = "select id,trader,city,quantity,brand,price from pepper_price where status='ACTIVE' order by created_date desc";
          $db = getConnection();
          $stmt = $db->query($sql);
          $properties = $stmt->fetchAll(PDO::FETCH_OBJ);
          $db = null;
          echo '{"price": ' . json_encode($properties) . ', "status":true}';
        }
        } catch (PDOException $e) {
          error_log($e->getMessage(), 3, 'phperror.log'); //Write error log
          echo '{"error":{"text":'. $e->getMessage() .'}}';
        }
  }

  function getClosePrice(){
        try {
          if(checkValidate()){
          $sql = "select id,name,price,clchange,change_percent from closing_price where status='ACTIVE' order by created_date desc";
          $db = getConnection();
          $stmt = $db->query($sql);
          $properties = $stmt->fetchAll(PDO::FETCH_OBJ);
          $db = null;
          echo '{"price": ' . json_encode($properties) . ', "status":true}';
          }
        } catch (PDOException $e) {
          error_log($e->getMessage(), 3, 'phperror.log'); //Write error log
          echo '{"error":{"text":'. $e->getMessage() .'}}';
        }
  }

  function getNews(){
        try {
          if(checkValidate()){
          $sql = "select id,news from news where status='ACTIVE' order by created_date desc";
          $db = getConnection();
          $stmt = $db->query($sql);
          $properties = $stmt->fetchAll(PDO::FETCH_OBJ);
          $db = null;
          echo '{"news": ' . json_encode($properties) . ', "status":true}';
        }
        } catch (PDOException $e) {
          error_log($e->getMessage(), 3, 'phperror.log'); //Write error log
          echo '{"error":{"text":'. $e->getMessage() .'}}';
        }
  }

  function userLogin(){
    $loginInfo = json_decode(file_get_contents("php://input")); 
    $password=md5($loginInfo->password);
    try {
    $sql = "select id,name,email,mobile,isAdmin,tokenKey from user_signup where email='".$loginInfo->userName."' || mobile='".$loginInfo->userName."' && password='".$password."' && status='ACTIVE'";
    $db = getConnection();
    $stmt = $db->query($sql);
    $users = $stmt->fetchAll(PDO::FETCH_OBJ);
    $count  = count($users);
    $db = null;
    $timeout = 36000 * 1000 + time();
    if($count > 0){
      if($loginInfo->isAdmin=='admin' && $users[0]->isAdmin==$loginInfo->isAdmin){
        if(empty($a)) session_start();
        setcookie("role", $loginInfo->isAdmin, $timeout, "/", 'villbiz.com', false, false);
        echo '{"users": "You are successfully loggedin!", "status":true, "profile":'.json_encode($users[0]).'}';
      }
      else if($loginInfo->isAdmin=='normal' && $users[0]->isAdmin==$loginInfo->isAdmin){
        if(empty($a)) session_start();
        setcookie("role", $loginInfo->isAdmin, $timeout, "/", 'villbiz.com', false, false);
        echo '{"users": "You are successfully loggedin!", "status":true, "profile":'.json_encode($users[0]).'}';
      }
      else
        echo '{"users": "You are not authorize to do this operation! please contact admin", "status":false}';
    }else{
      echo '{"users": "Invalid Username or Password!", "status":false}';
    }
    } catch(PDOException $e) {
    error_log($e->getMessage(), 3, 'phperror.log'); //Write error log
    echo '{"error":{"text":'.$e->getMessage().'}}';
   }
  }

  function logOut(){
    session_start();
    session_destroy();
     if (isset($_SERVER['HTTP_COOKIE'])) {
      $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
      foreach($cookies as $cookie) {
          $parts = explode('=', $cookie);
          $name = trim($parts[0]);
          setcookie($name, '', time()-1000);
          setcookie($name, '', time()-1000, '/');
      }
  }
  }

  function getUsers() {
        try {
          if(checkValidate()){
          $sql = "SELECT id,name,email,mobile,isAdmin,created_date FROM user_signup ORDER BY created_date DESC";
          $db = getConnection();
          $stmt = $db->query($sql);
          $users = $stmt->fetchAll(PDO::FETCH_OBJ);
          $db = null;
          echo '{"users": ' . json_encode($users) . ', "status":true}';
        }
        } catch(PDOException $e) {
        error_log($e->getMessage(), 3, 'phperror.log'); //Write error log
        echo '{"error":{"text":'. $e->getMessage() .'}}';
       }
  }

  function getUserProfile($id) {
        try {
          if(checkValidate()){
            $sql = "select id,name,email,mobile FROM user_signup where id='".$id."'";
            $db = getConnection();
            $stmt = $db->query($sql);
            $profile = $stmt->fetchAll(PDO::FETCH_OBJ);
            $db = null;
            echo '{"profile": ' . json_encode($profile[0]) . ', "status":true}';
          }
        } catch(PDOException $e) {
        error_log($e->getMessage(), 3, 'phperror.log'); //Write error log
        echo '{"error":{"text":'. $e->getMessage() .'}}';
       }
  }

  function getContact(){
      try {
          if(checkValidate()){
            $sql = "select * FROM contactus where order by created_date desc";
            $db = getConnection();
            $stmt = $db->query($sql);
            $contact = $stmt->fetchAll(PDO::FETCH_OBJ);
            $db = null;
            echo '{"contact": ' . json_encode($contact) . ', "status":true}';
          }
        } catch(PDOException $e) {
        error_log($e->getMessage(), 3, 'phperror.log'); //Write error log
        echo '{"error":{"text":'. $e->getMessage() .'}}';
       }
  }

 function addContactus(){
  $contactInfo = json_decode(file_get_contents("php://input"));
  $ip=getRealIpAddr();
    try {
      if($contactInfo && $contactInfo->name && $contactInfo->email && $contactInfo->mobile && $contactInfo->message){
       $name=$contactInfo->name;
       $email=$contactInfo->email;
       $mobile=$contactInfo->mobile;
       $message=$contactInfo->message;
        $sql="insert into contactus(id,name,email,mobile,message,created_date,ip,status) values ('".getGUID()."' ,'".$name."' ,'".$email."' ,'".$mobile."','".$message."','".date("Y-m-d h:i:s")."','".$ip."','ACTIVE')";
         $db = getConnection();
         $result = $db->query($sql);
         $db = null;
        $success = '{"info":{"message":"Contact Added Successfully.","status":true}}';
        echo $success;
     }else{
       echo '{"info":{"message":"Mandatory fields are  missing.","status":false}}';
     }
    } catch (Exception $e) {
      error_log($e->getMessage(), 3, 'phperror.log'); //Write error log
      echo '{"info":{"message":"Error in api call","status":'.$e->getMessage().'}}';
    }
 }
 function addUsers(){
    $userInfo = json_decode(file_get_contents("php://input"));
    $ip=getRealIpAddr();
    $userId=getGUID();
    $otp_code = mt_rand(10, 1100000);
    $apiKey = md5(uniqid(rand(), true));
    $db = getConnection();
    try {
      if($userInfo && $userInfo->name && $userInfo->password && $userInfo->mobile){
       $name=$userInfo->name;
       $email=$userInfo->email;
       $mobile=$userInfo->mobile;
       $password=md5($userInfo->password);
       $isAdmin=$userInfo->isAdmin;
       $tokenKey=null;
       $icon=null;
       $checkMobile="select mobile from user_signup where mobile='".$mobile."'";
       $mobResult = $db->query($checkMobile);
       $mobileCount  = count($mobResult->fetchAll(PDO::FETCH_OBJ));

       $checkEmail="select email from user_signup where email='".$email."'";
       $emailResult = $db->query($checkEmail);
       $emailCount  = count($emailResult->fetchAll(PDO::FETCH_OBJ));
       if($mobileCount > 0){
        echo '{"info":{"message":"Mobile Number already Registered.","status":false}}';
        return;
       }else if($emailCount){
        echo '{"info":{"message":"Email already Registered.","status":false}}';
        return;
       }
       $sql="insert into user_signup(id,name,email,mobile,password,isAdmin,tokenKey,created_date,modified_date,ip,status) values ('".$userId."' ,'".$name."' ,'".$email."' ,'".$mobile."','".$password."','".$isAdmin."','".$apiKey."','".date("Y-m-d h:i:s")."','".date("Y-m-d h:i:s")."','".$ip."','INACTIVE')";
         
         $result = $db->query($sql);
         $sqlOtp="insert into mobileotp(id,otp,mobile,status) values ('".$userId."' ,'".$otp_code."','".$mobile."','ACTIVE')";
         $result = $db->query($sqlOtp);
         $db = null;
         $success = '{"info":{"message":"'.sendSMS($mobile, $otp_code, false).'", "id":"'.$userId.'", "status":true}}';
         echo $success;
     }else{
       echo '{"info":{"message":"Mandatory fields are  missing.","status":false}}';
     }
    } catch (Exception $e) {
      error_log($e->getMessage(), 3, 'phperror.log'); //Write error log
      $rollBack="delete from user_signup where id='".$userId."';delete from mobileotp where id='".$userId."'";
      $result = $db->query($rollBack);
      $db = null;
      echo '{"info":{"message":"'.$e->getMessage().'","status":false}}';
    }
 }
 function resentOtp($mobile){
      try {
          $sql = "select * from user_signup where mobile='".$mobile."'";
          $db = getConnection();
          $stmt = $db->query($sql);
          $mobOtp = $stmt->fetchAll(PDO::FETCH_OBJ);
          $count  = count($mobOtp);
          if($count > 0){
             $otp_code = mt_rand(10, 1100000);
             echo '{"info":{"message":"'.sendSMS($mobile, $otp_code, true).'", "mobile":"'.$mobile.'", "status":true}}';
          }else{
             echo '{"info":{"message":"Mobile Number not Registered","status":false}}';
          }
         
      } catch (Exception $e) {
        echo "Error in sms sent ".$e->getMessage();
      }  
 }
 function sendSMS($mobile, $otp, $isReSent)
  {
    try {
      if($isReSent){
         $sqlOtp="update mobileotp set otp='".$otp."', status='ACTIVE' where mobile='".$mobile."'";
         $db = getConnection();
         $result = $db->query($sqlOtp);
         $db = null;
      }
      $smsMessage = urlencode('One Time Password for VILLBIZ is '.$otp.'. Please use the password to verify user registration Thank you!');
      $smsUrl='http://ntransapi.alertsindia.in/Desk2web/SendSMS.aspx?UserName=villbz&password=hrbcbncns&MobileNo='.$mobile.'&SenderID=VILLBZ&CDMAHeader=VILLBZ&Message='.$smsMessage.'&isFlash=False';
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $smsUrl);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $result = curl_exec($ch);
      curl_close($ch);
      return "A SMS SENT SUCCESSFULLY TO ".$mobile;
    } catch (Exception $e) {
      echo "Error in sms sent ".$e->getMessage();
    }
    
  }

 function verifyOtp($id, $otp){
        try {
         $sql = "select * from mobileotp where id='".$id."' && otp='".$otp."' && status='ACTIVE'";
          $db = getConnection();
          $stmt = $db->query($sql);
          $mobOtp = $stmt->fetchAll(PDO::FETCH_OBJ);
          $count  = count($mobOtp);
          if($count > 0){
            $sql = "update mobileotp set status='INACTIVE' where id='".$id."'";
            $stmt = $db->query($sql);
            $updateSql = "update user_signup set status='ACTIVE' where id='".$id."'";
            $stmt = $db->query($updateSql);
            $db = null;
            $success = '{"info":{"message":"Your account has been created successfully.", "id":"'.$id.'", "status":true}}';
            echo $success;
          }else{
            $success = '{"info":{"message":"Invalid otp provided.", "id":"'.$id.'", "status":false}}';
            echo $success;
          }
        } catch (Exception $e) {
          echo '{"info":{"message":"Error in api call","status":'.$e->getMessage().'}}';
        }
 }

 function verifyResetPasswordOtp($mobile, $otp){
        try {
         $sql = "select * from mobileotp where otp='".$otp."' && status='ACTIVE'";
          $db = getConnection();
          $stmt = $db->query($sql);
          $mobOtp = $stmt->fetchAll(PDO::FETCH_OBJ);
          $count  = count($mobOtp);
          if($count > 0){
            $sql = "update mobileotp set status='INACTIVE' where mobile='".$mobile."'";
            $stmt = $db->query($sql);
            $updateSql = "update user_signup set status='ACTIVE' where mobile='".$mobile."'";
            $stmt = $db->query($updateSql);
            $db = null;
            $success = '{"info":{"message":"Reset Your New Password.", "mobile":"'.$mobile.'", "status":true}}';
            echo $success;
          }else{
            $success = '{"info":{"message":"Invalid otp provided.", "mobile":"'.$mobile.'", "status":false}}';
            echo $success;
          }
        } catch (Exception $e) {
          echo '{"info":{"message":"Error in api call","status":'.$e->getMessage().'}}';
        }
 }

 function setNewPassword(){
    $passInfo = json_decode(file_get_contents("php://input"));
    try {
      if($passInfo->password && $passInfo->mobile){
          $password=$passInfo->password;
          $mobile=$passInfo->mobile;

          $sql="update user_signup set password='".md5($password)."' where mobile='".$mobile."'";
          $db = getConnection();
          $result = $db->query($sql);
          $db = null;
          $success = '{"info":{"message":"Password Updated Successfully.","status":true}}';
          echo $success;
     }else{
          echo '{"info":{"message":"Mandatory fields are  missing.","status":false}}';
     }
    } catch (Exception $e) {
      error_log($e->getMessage(), 3, 'phperror.log'); //Write error log
      echo '{"info":{"message":"Error in api call","status":'.$e->getMessage().'}}';
    }
 }

 function addProperties(){
  if(checkValidate()){
   try {
     if($_POST['title'] && $_POST['type'] && $_POST['location'] && $_POST['price'] && $_POST['desc']){
        $title=$_POST['title'];
        $type=$_POST['type'];
        $desc=$_POST['desc'];
        $location=$_POST['location'];
        $cost=$_POST['price'];
        $isUploaded=false;
        $status='ACTIVE';
        $failImage=array();
        $fileList=array();
        if(count($_FILES)){
          for ($i = 0; $i < count($_FILES['uploadimage']['name']); $i++){
            $filename = basename($_FILES['uploadimage']['name'][$i]);
            $filename=str_replace(" ","", $filename);
            $uid=date('YmdHis');
            $filename=$uid."_".$filename;
            $newname = dirname(__FILE__).'/upload/'.$filename;
            
            if (move_uploaded_file($_FILES['uploadimage']['tmp_name'][$i],$newname)) {
                $isUploaded=true;
                array_push($fileList, $filename);
            }else{
                array_push($failImage, $filename);
            }
          }
        }else{
          //array_push($fileList, 'emptycard.png');
        }
        
        $sql="insert into properties(id,title,type,description,cost,location,status,created_date,modified_date,image) values ('".getGUID()."' ,'".$title."' ,'".$type."' ,'".$desc."','".$cost."','".$location."','".$status."','".date("Y-m-d h:i:s")."','".date("Y-m-d h:i:s")."','".implode(",",$fileList)."')";
        $db = getConnection();
        $result = $db->query($sql);
        $db = null;
        $success = '{"info":{"message":"Property Added Successfully.","status":true,"Failed":'.json_encode($failImage).'}}';
        echo $success;
     }else{
       echo '{"info":{"message":"Mandatory fields are  missing.","status":false}}';
     }
   } catch (Exception $e) {
     error_log($e->getMessage(), 3, 'phperror.log'); //Write error log
     echo '{"info":{"message":"Mandatory fields are  missing.","status":"Exception'.$e->getMessage().'"}}';
   }
 }
 }

 function addCoffeePrice(){
  if(checkValidate()){
   $coffeeInfo = json_decode(file_get_contents("php://input"));
    try {
      if($coffeeInfo && $coffeeInfo->trader && $coffeeInfo->city && $coffeeInfo->ap && $coffeeInfo->ac && $coffeeInfo->rp && $coffeeInfo->rc){
       $trader=$coffeeInfo->trader;
       $city=$coffeeInfo->city;
       $ap=$coffeeInfo->ap;
       $ac=$coffeeInfo->ac;
       $rp=$coffeeInfo->rp;
       $rc=$coffeeInfo->rc;
        $sql="insert into coffee_price(id,trader,city,ap,ac,rp,rc,created_date,modified_date,status) values ('".getGUID()."' ,'".$trader."' ,'".$city."' ,'".$ap."','".$ac."','".$rp."','".$rc."','".date("Y-m-d h:i:s")."','".date("Y-m-d h:i:s")."','ACTIVE')";
         $db = getConnection();
         $result = $db->query($sql);
         $db = null;
        $success = '{"info":{"message":"Price Added Successfully.","status":true}}';
        echo $success;
     }else{
       echo '{"info":{"message":"Mandatory fields are  missing.","status":false}}';
     }
    } catch (Exception $e) {
      error_log($e->getMessage(), 3, 'phperror.log'); //Write error log
      echo '{"info":{"message":"Error in api call","status":'.$e->getMessage().'}}';
    }
  }
 }

 function addPepperPrice(){
  if(checkValidate()){
   $papperInfo = json_decode(file_get_contents("php://input"));
    try {
      if($papperInfo && $papperInfo->trader && $papperInfo->city && $papperInfo->quantity && $papperInfo->brand && $papperInfo->price){
       $trader=$papperInfo->trader;
       $city=$papperInfo->city;
       $brand=$papperInfo->brand;
       $quantity=$papperInfo->quantity;
       $price=$papperInfo->price;
        $sql="insert into pepper_price(id,trader,city,brand,quantity,price,created_date,modified_date,status) values ('".getGUID()."' ,'".$trader."' ,'".$city."' ,'".$brand."','".$quantity."','".$price."','".date("Y-m-d h:i:s")."','".date("Y-m-d h:i:s")."','ACTIVE')";
         $db = getConnection();
         $result = $db->query($sql);
         $db = null;
        $success = '{"info":{"message":"Price Added Successfully.","status":true}}';
        echo $success;
     }else{
       echo '{"info":{"message":"Mandatory fields are  missing.","status":false}}';
     }
    } catch (Exception $e) {
      error_log($e->getMessage(), 3, 'phperror.log'); //Write error log
      echo '{"info":{"message":"Error in api call","status":'.$e->getMessage().'}}';
    }
  }
 }

 function addClosePrice(){
  if(checkValidate()){
   $closeInfo = json_decode(file_get_contents("php://input"));
    try {
      if($closeInfo && $closeInfo->name && $closeInfo->price && $closeInfo->change && $closeInfo->changepercent){
       $name=$closeInfo->name;
       $price=$closeInfo->price;
       $change=$closeInfo->change;
       $changepercent=$closeInfo->changepercent;
        $sql="insert into closing_price(id,name,price,clchange,change_percent,created_date,modified_date,status) values ('".getGUID()."' ,'".$name."' ,'".$price."' ,'".$change."','".$changepercent."','".date("Y-m-d h:i:s")."','".date("Y-m-d h:i:s")."','ACTIVE')";
         $db = getConnection();
         $result = $db->query($sql);
         $db = null;
        $success = '{"info":{"message":"Price Added Successfully.","status":true}}';
        echo $success;
     }else{
       echo '{"info":{"message":"Mandatory fields are  missing.","status":false}}';
     }
    } catch (Exception $e) {
      error_log($e->getMessage(), 3, 'phperror.log'); //Write error log
      echo '{"info":{"message":"Error in api call","status":'.$e->getMessage().'}}';
    }
  }
 }

 function addNews(){
  if(checkValidate()){
   $newsInfo = json_decode(file_get_contents("php://input"));
    try {
      if($newsInfo && $newsInfo->news){
       $news=$newsInfo->news;
        $sql="insert into news(id,news,created_date,modified_date,status) values ('".getGUID()."' ,'".$news."','".date("Y-m-d h:i:s")."','".date("Y-m-d h:i:s")."','ACTIVE')";
         $db = getConnection();
         $result = $db->query($sql);
         $db = null;
        $success = '{"info":{"message":"News Created Successfully.","status":true}}';
        echo $success;
     }else{
       echo '{"info":{"message":"Mandatory fields are  missing.","status":false}}';
     }
    } catch (Exception $e) {
      error_log($e->getMessage(), 3, 'phperror.log'); //Write error log
      echo '{"info":{"message":"Error in api call","status":'.$e->getMessage().'}}';
    }
  }
 }

 function updateProperties($id){
  if(checkValidate()){
   try {
     if($_POST['title'] && $_POST['location'] && $_POST['price'] && $_POST['desc']){
        $title=$_POST['title'];
        $desc=$_POST['desc'];
        $location=$_POST['location'];
        $cost=$_POST['price'];
        $availableFile =$_POST['imagelist'];
        $deletedImage =$_POST['deletedImage'];
     	  $uid=date('YmdHis');
        $availableFile=explode(",", $availableFile);
        $deletedImage=explode(",", $deletedImage);

     	  $failImage=array();
        $fileList=array();

        if($availableFile[0]!="")$fileList=array_merge($fileList, $availableFile);
        if(count($_FILES)){
          for ($i = 0; $i < count($_FILES['uploadimage']['name']); $i++){
              $filename = basename($_FILES['uploadimage']['name'][$i]);

              $filename=str_replace(" ","", $filename);
              $uid=date('YmdHis');
              $filename=$uid."_".$filename;
              $newname = dirname(__FILE__).'/upload/'.$filename;
              
              if (move_uploaded_file($_FILES['uploadimage']['tmp_name'][$i],$newname)) {
                  $isUploaded=true;
                  array_push($fileList, $filename);
              }else{
                  array_push($failImage, $filename);
              }
            
          }
        }
        $sql="update properties set title='".$title."',description='".$desc."',cost='".$cost."',location='".$location."',modified_date='".date("Y-m-d h:i:s")."', image='".implode(",",$fileList)."' where id='".$id."'";
        $db = getConnection();
        $result = $db->query($sql);
        $db = null;

        for($i=0; $i < count($deletedImage); $i++){
          if($deletedImage[$i]!==""){
            $deleteFileName = dirname(__FILE__).'/upload/'.$deletedImage[$i];
            unlink($deleteFileName);
         }
        }
        $success = '{"info":{"message":"Property Updated Successfully.","status":true}}';
        echo $success;
     }else{
       echo '{"info":{"message":"Mandatory fields are  missing.","status":false}}';
     }
   } catch (Exception $e) {
    error_log($e->getMessage(), 3, 'phperror.log'); //Write error log
     echo '{"info":{"message":"Mandatory fields are  missing.","status":"Exception'.$e->getMessage().'"}}';
   }
 }
 }

 function updateCoffeePrice($id){
  if(checkValidate()){
   $coffeeInfo = json_decode(file_get_contents("php://input"));
    try {
      if($coffeeInfo && $coffeeInfo->trader && $coffeeInfo->city && $coffeeInfo->ap && $coffeeInfo->ac && $coffeeInfo->rp && $coffeeInfo->rc){
       $trader=$coffeeInfo->trader;
       $city=$coffeeInfo->city;
       $ap=$coffeeInfo->ap;
       $ac=$coffeeInfo->ac;
       $rp=$coffeeInfo->rp;
       $rc=$coffeeInfo->rc;
        $sql="update coffee_price set trader='".$trader."',city='".$city."',ap='".$ap."',ac='".$ac."',rp='".$rp."',rc='".$rc."',modified_date='".date("Y-m-d h:i:s")."' where id='".$id."'";
         $db = getConnection();
         $result = $db->query($sql);
         $db = null;
        $success = '{"info":{"message":"Price Updated Successfully.","status":true}}';
        echo $success;
     }else{
       echo '{"info":{"message":"Mandatory fields are  missing.","status":false}}';
     }
    } catch (Exception $e) {
      error_log($e->getMessage(), 3, 'phperror.log'); //Write error log
      echo '{"info":{"message":"Error in api call","status":'.$e->getMessage().'}}';
    }
  }
 }

 function updatePepperPrice($id){
  if(checkValidate()){
   $papperInfo = json_decode(file_get_contents("php://input"));
    try {
      if($papperInfo && $papperInfo->trader && $papperInfo->city && $papperInfo->quantity && $papperInfo->brand && $papperInfo->price){
       $trader=$papperInfo->trader;
       $city=$papperInfo->city;
       $brand=$papperInfo->brand;
       $quantity=$papperInfo->quantity;
       $price=$papperInfo->price;
        $sql="update pepper_price set trader='".$trader."',city='".$city."',brand='".$brand."',quantity='".$quantity."',price='".$price."',modified_date='".date("Y-m-d h:i:s")."' where id='".$id."'";
         $db = getConnection();
         $result = $db->query($sql);
         $db = null;
        $success = '{"info":{"message":"Price Updated Successfully.","status":true}}';
        echo $success;
     }else{
       echo '{"info":{"message":"Mandatory fields are  missing.","status":false}}';
     }
    } catch (Exception $e) {
      error_log($e->getMessage(), 3, 'phperror.log'); //Write error log
      echo '{"info":{"message":"Error in api call","status":'.$e->getMessage().'}}';
    }
  }
 }

  function updateClosePrice($id){
   if(checkValidate()){
   $closeInfo = json_decode(file_get_contents("php://input"));
    try {
      if($closeInfo && $closeInfo->name && $closeInfo->price && $closeInfo->change && $closeInfo->changepercent){
       $name=$closeInfo->name;
       $price=$closeInfo->price;
       $change=$closeInfo->change;
       $changepercent=$closeInfo->changepercent;
        $sql="update closing_price set name='".$name."',price='".$price."',clchange='".$change."',change_percent='".$changepercent."',modified_date='".date("Y-m-d h:i:s")."' where id='".$id."'";
         $db = getConnection();
         $result = $db->query($sql);
         $db = null;
        $success = '{"info":{"message":"Price Updated Successfully.","status":true}}';
        echo $success;
     }else{
       echo '{"info":{"message":"Mandatory fields are  missing.","status":false}}';
     }
    } catch (Exception $e) {
      error_log($e->getMessage(), 3, 'phperror.log'); //Write error log
      echo '{"info":{"message":"Error in api call","status":'.$e->getMessage().'}}';
    }
  }
 }

 function updateNews($id){
  if(checkValidate()){
   $newsInfo = json_decode(file_get_contents("php://input"));
    try {
      if($newsInfo && $newsInfo->news){
       $news=$newsInfo->news;
        $sql="update news set news='".$news."',modified_date='".date("Y-m-d h:i:s")."' where id='".$id."'";
         $db = getConnection();
         $result = $db->query($sql);
         $db = null;
        $success = '{"info":{"message":"News Updated Successfully.","status":true}}';
        echo $success;
     }else{
       echo '{"info":{"message":"Mandatory fields are  missing.","status":false}}';
     }
    } catch (Exception $e) {
      error_log($e->getMessage(), 3, 'phperror.log'); //Write error log
      echo '{"info":{"message":"Error in api call","status":'.$e->getMessage().'}}';
    }
  }
 }

 function deleteProperties($id){
  if(checkValidate()){
   try {
     //$sql="update properties set status='INACTIVE', modified_date='".date("Y-m-d h:i:s")."' where id='".$id."'";
      $selectSql = "select image from properties where id='".$id."'";
      $db = getConnection();
      $stmt = $db->query($selectSql);
      $propImage = $stmt->fetchAll(PDO::FETCH_OBJ);
      if($propImage[0]->image!=="")
      if(strpos($propImage[0]->image, ",")){
        $imgList=explode(",", $propImage[0]->image);
        for($i=0; $i < count($imgList); $i++){
           $deleteFileName = dirname(__FILE__).'/upload/'.$imgList[$i];
           unlink($deleteFileName);
        }
      }else{
           $deleteFileName = dirname(__FILE__).'/upload/'.$propImage[0]->image;
           unlink($deleteFileName);
      }
      
      $sql="delete from properties where id='".$id."'";
      $result = $db->query($sql);
      $db = null;
      $success = '{"info":{"message":"Property Deleted Successfully.","status":true}}';
      echo $success;
   } catch (Exception $e) {
     error_log($e->getMessage(), 3, 'phperror.log'); //Write error log
     echo '{"info":{"message":"Mandatory fields are  missing.","status":"Exception'.$e->getMessage().'"}}';
   }
 }
 }

 function deleteCoffeePrice($id){
  if(checkValidate()){
   try {
     $sql="delete from coffee_price where id='".$id."'";
     $db = getConnection();
     $result = $db->query($sql);
     $db = null;
     $success = '{"info":{"message":"Coffee Price Deleted Successfully.","status":true}}';
     echo $success;
   } catch (Exception $e) {
     error_log($e->getMessage(), 3, 'phperror.log'); //Write error log
     echo '{"info":{"message":"Mandatory fields are  missing.","status":"Exception'.$e->getMessage().'"}}';
   }
 }
 }

 function deletePepperPrice($id){
  if(checkValidate()){
   try {
     $sql="delete from pepper_price where id='".$id."'";
     $db = getConnection();
     $result = $db->query($sql);
     $db = null;
     $success = '{"info":{"message":"Pepper Price Deleted Successfully.","status":true}}';
     echo $success;
   } catch (Exception $e) {
     error_log($e->getMessage(), 3, 'phperror.log'); //Write error log
     echo '{"info":{"message":"Mandatory fields are  missing.","status":"Exception'.$e->getMessage().'"}}';
   }
 }
 }

 function deleteClosePrice($id){
  if(checkValidate()){
   try {
     $sql="delete from closing_price where id='".$id."'";
     $db = getConnection();
     $result = $db->query($sql);
     $db = null;
     $success = '{"info":{"message":"Close Price Deleted Successfully.","status":true}}';
     echo $success;
   } catch (Exception $e) {
     error_log($e->getMessage(), 3, 'phperror.log'); //Write error log
     echo '{"info":{"message":"Mandatory fields are  missing.","status":"Exception'.$e->getMessage().'"}}';
   }
 }
 }

 function deleteNews($id){
  if(checkValidate()){
   try {
     $sql="delete from news where id='".$id."'";
     $db = getConnection();
     $result = $db->query($sql);
     $db = null;
     $success = '{"info":{"message":"News Deleted Successfully.","status":true}}';
     echo $success;
   } catch (Exception $e) {
     error_log($e->getMessage(), 3, 'phperror.log'); //Write error log
     echo '{"info":{"message":"Mandatory fields are  missing.","status":"Exception'.$e->getMessage().'"}}';
   }
 }
 }

 function deleteContact($id){
  if(checkValidate()){
   try {
     $sql="delete from contactus where id='".$id."'";
     $db = getConnection();
     $result = $db->query($sql);
     $db = null;
     $success = '{"info":{"message":"Contact Deleted Successfully.","status":true}}';
     echo $success;
   } catch (Exception $e) {
     error_log($e->getMessage(), 3, 'phperror.log'); //Write error log
     echo '{"info":{"message":"Mandatory fields are  missing.","status":"Exception'.$e->getMessage().'"}}';
   }
 }
 }

 function deleteUser($id){
  if(checkValidate()){
   try {
     $sql="delete from user_signup where id='".$id."'";
     $db = getConnection();
     $result = $db->query($sql);
     $sql="delete from mobileotp where id='".$id."'";
     $result = $db->query($sql);
     $db = null;
     $success = '{"info":{"message":"User Deleted Successfully.","status":true}}';
     echo $success;
   } catch (Exception $e) {
     error_log($e->getMessage(), 3, 'phperror.log'); //Write error log
     echo '{"info":{"message":"Mandatory fields are  missing.","status":"Exception'.$e->getMessage().'"}}';
   }
 }
 }

 function deleteImage($id, $imagename){
  if(checkValidate()){
   try {
     $sql="select image from properties where id='".$id."'";
     $db = getConnection();
     $stmt = $db->query($sql);
     $result = $stmt->fetchAll(PDO::FETCH_OBJ);
     
     $imgList=explode(",",$result[0]->image);
     $position=array_search($imagename, $imgList);
     unset($imgList[$position]);

     $updateSql="update properties set image='".implode(",",$imgList)."' where id='".$id."'";
     $stmt = $db->query($updateSql);
     $db = null;

     $deleteFileName = dirname(__FILE__).'/upload/'.$imagename;
     unlink($deleteFileName);

     $success = '{"info":{"message":"Image Deleted Successfully.","status":true}}';
     echo $success;
   } catch (Exception $e) {
     error_log($e->getMessage(), 3, 'phperror.log'); //Write error log
     echo '{"info":{"message":"Mandatory fields are  missing.","status":"Exception'.$e->getMessage().'"}}';
   }
 }
 }
 
 function searchProperties($id){
   $searchInfo = json_decode(file_get_contents("php://input"));
   try {
     if($searchInfo->flag){
       $location=implode("','",$searchInfo->locationList);
       if(count($searchInfo->locationList) > 0){
        $sql="select * from properties where type='".$id."' && location in('".$location."') && status='ACTIVE' order by sno asc";
        }else{
          $sql="select * from properties where type='".$id."' && status='ACTIVE' order by sno asc";
        }
       
       $db = getConnection();
       $stmt = $db->query($sql);
       $result = $stmt->fetchAll(PDO::FETCH_OBJ);
       $db = null;
     }else{
       $searchId=$searchInfo->searchId;
       $sql="select * from properties where sno='".$searchId."'";
       $db = getConnection();
       $stmt = $db->query($sql);
       $result = $stmt->fetchAll(PDO::FETCH_OBJ);
       $db = null;
     }
     $success = '{"result":'.json_encode($result).',"status":true}';
     echo $success;
   } catch (Exception $e) {
     error_log($e->getMessage(), 3, 'phperror.log'); //Write error log
     echo '{"info":{"message":"Mandatory fields are  missing.","status":"Exception'.$e->getMessage().'"}}';
   }
 }
 function propertiesSearch($type){
  try {
     $sql="select DISTINCT * from properties where type='".$type."' && status='ACTIVE'";
     $db = getConnection();
     $stmt = $db->query($sql);
     $result = $stmt->fetchAll(PDO::FETCH_OBJ);
     $db = null;
     $success = '{"result":'.json_encode($result).',"status":true}';
     echo $success;
   } catch (Exception $e) {
     echo '{"info":{"message":"Mandatory fields are  missing.","status":"Exception'.$e->getMessage().'"}}';
   }

 }

 function getLocation($type){
   try {
     $sql="select DISTINCT location from properties where type='".$type."' && status='ACTIVE' order by location asc";
     $db = getConnection();
     $stmt = $db->query($sql);
     $result = $stmt->fetchAll(PDO::FETCH_OBJ);
     $db = null;
     $success = '{"result":'.json_encode($result).',"status":true}';
     echo $success;
   } catch (Exception $e) {
     echo '{"info":{"message":"Mandatory fields are  missing.","status":"Exception'.$e->getMessage().'"}}';
   }

 }
?>
