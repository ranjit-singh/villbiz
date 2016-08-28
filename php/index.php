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
  function getProperties($type){
        try {
          $sql = "select sno,id,title,type,cost,location,description,image from properties where type='".$type."' && status='ACTIVE' order by created_date desc";
          $db = getConnection();
          $stmt = $db->query($sql);
          $properties = $stmt->fetchAll(PDO::FETCH_OBJ);
          $db = null;
          echo '{"prop": ' . json_encode($properties) . '}';
        } catch (PDOException $e) {
          //error_log($e->getMessage(), 3, '/var/tmp/phperror.log'); //Write error log
          echo '{"error":{"text":'. $e->getMessage() .'}}';
        }

  }
  function getCoffeePrice(){
        try {
          $sql = "select id,trader,city,ap,ac,rp,rc from coffee_price where status='ACTIVE' order by created_date desc";
          $db = getConnection();
          $stmt = $db->query($sql);
          $properties = $stmt->fetchAll(PDO::FETCH_OBJ);
          $db = null;
          echo '{"price": ' . json_encode($properties) . '}';
        } catch (PDOException $e) {
          //error_log($e->getMessage(), 3, '/var/tmp/phperror.log'); //Write error log
          echo '{"error":{"text":'. $e->getMessage() .'}}';
        }
  }
  function getPepperPrice(){
        try {
          $sql = "select id,trader,city,quantity,brand,price from pepper_price where status='ACTIVE' order by created_date desc";
          $db = getConnection();
          $stmt = $db->query($sql);
          $properties = $stmt->fetchAll(PDO::FETCH_OBJ);
          $db = null;
          echo '{"price": ' . json_encode($properties) . '}';
        } catch (PDOException $e) {
          //error_log($e->getMessage(), 3, '/var/tmp/phperror.log'); //Write error log
          echo '{"error":{"text":'. $e->getMessage() .'}}';
        }
  }

  function getClosePrice(){
        try {
          $sql = "select id,name,price,clchange,change_percent from closing_price where status='ACTIVE' order by created_date desc";
          $db = getConnection();
          $stmt = $db->query($sql);
          $properties = $stmt->fetchAll(PDO::FETCH_OBJ);
          $db = null;
          echo '{"price": ' . json_encode($properties) . '}';
        } catch (PDOException $e) {
          //error_log($e->getMessage(), 3, '/var/tmp/phperror.log'); //Write error log
          echo '{"error":{"text":'. $e->getMessage() .'}}';
        }
  }

  function getNews(){
        try {
          $sql = "select id,news from news where status='ACTIVE' order by created_date desc";
          $db = getConnection();
          $stmt = $db->query($sql);
          $properties = $stmt->fetchAll(PDO::FETCH_OBJ);
          $db = null;
          echo '{"news": ' . json_encode($properties) . '}';
        } catch (PDOException $e) {
          //error_log($e->getMessage(), 3, '/var/tmp/phperror.log'); //Write error log
          echo '{"error":{"text":'. $e->getMessage() .'}}';
        }
  }

  function userLogin(){
    $loginInfo = json_decode(file_get_contents("php://input")); 
    $password=md5($loginInfo->password);
    try {
    $sql = "select id,name,email,mobile,isAdmin from user_signup where email='".$loginInfo->userName."' || mobile='".$loginInfo->userName."' && password='".$password."' && status='ACTIVE'";
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
    //error_log($e->getMessage(), 3, '/var/tmp/phperror.log'); //Write error log
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
        $sql = "SELECT id,name,email FROM user_signup ORDER BY id DESC";
        $db = getConnection();
        $stmt = $db->query($sql);
        $users = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo '{"users": ' . json_encode($users) . '}';
        } catch(PDOException $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/phperror.log'); //Write error log
        echo '{"error":{"text":'. $e->getMessage() .'}}';
       }
     }
     function addContactus(){
      $contactInfo = json_decode(file_get_contents("php://input"));
      //echo json_encode($contactInfo);
      //return;
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
            $success = '{"info":{"message":"Contact Added Successfully.","status":"success"}}';
            echo $success;
         }else{
           echo '{"info":{"message":"Mandatory fields are  missing.","status":"error"}}';
         }
        } catch (Exception $e) {
          //error_log($e->getMessage(), 3, '/var/tmp/phperror.log'); //Write error log
          echo '{"info":{"message":"Error in api call","status":'.$e->getMessage().'}}';
        }
     }
     function addUsers(){
       $userInfo = json_decode(file_get_contents("php://input"));
        $ip=getRealIpAddr();
        try {
          if($userInfo && $userInfo->name && $userInfo->password && $userInfo->mobile){
           $name=$userInfo->name;
           $email=$userInfo->email;
           $mobile=$userInfo->mobile;
           $password=md5($userInfo->password);
           $isAdmin=$userInfo->isAdmin;
           $tokenKey=null;
           $icon=null;
           $userId=getGUID();
           $otp_code = mt_rand(10, 1100000);
            $sql="insert into user_signup(id,name,email,mobile,password,isAdmin,created_date,modified_date,ip,status) values ('".$userId."' ,'".$name."' ,'".$email."' ,'".$mobile."','".$password."','".$isAdmin."','".date("Y-m-d h:i:s")."','".date("Y-m-d h:i:s")."','".$ip."','INACTIVE')";
             $db = getConnection();
             $result = $db->query($sql);
             $sqlOtp="insert into mobileotp(id,otp,mobile,status) values ('".$userId."' ,'".$otp_code."','".$mobile."','ACTIVE')";
             $result = $db->query($sqlOtp);
             $db = null;
             $success = '{"info":{"message":"'.sendSMS($mobile, $otp_code, false).'", "id":"'.$userId.'", "status":"success"}}';
             echo $success;
         }else{
           echo '{"info":{"message":"Mandatory fields are  missing.","status":"error"}}';
         }
        } catch (Exception $e) {
          //error_log($e->getMessage(), 3, '/var/tmp/phperror.log'); //Write error log
          echo '{"info":{"message":"'.$e->getMessage().'","status":"error"}}';
        }
     }
     function resentOtp($mobile){
          $otp_code = mt_rand(10, 1100000);
          sendSMS($mobile, $otp_code, true);
     }
     function sendSMS($mobile=null, $otp=null, $isReSent)
      {
        try {
          if($isReSent){
             $sqlOtp="update mobileotp set otp='".$otp."' where mobile='".$mobile."'";
             $db = getConnection();
             $result = $db->query($sqlOtp);
             $db = null;
          }
          $smsMessage = urlencode('Hello, this is your one-time password '.$otp.' to verify user registration. Thank you!');
          $smsUrl='http://ntransapi.alertsindia.in/Desk2web/SendSMS.aspx?UserName=villbz&password=hrbcbncns&MobileNo='.$mobile.'&SenderID=VILLBZ&CDMAHeader=VILLBZ&Message='.$smsMessage.'&isFlash=False';
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, $smsUrl);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          $result = curl_exec($ch);
          curl_close($ch);
          return "A SMS SENT SUCCESSFULLY TO ".$mobile;
        } catch (Exception $e) {
          echo '{"info":{"message":"Error in api call","status":'.$e->getMessage().'}}';
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
              }
              $db = null;
              $success = '{"info":{"message":"Your account has been created successfully.", "id":"'.$id.'", "status":"success"}}';
              echo $success;
            } catch (Exception $e) {
              echo '{"info":{"message":"Error in api call","status":'.$e->getMessage().'}}';
            }
     }

     function addProperties(){
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
            $filename=$isUploaded ? $filename: null;
           
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
         //error_log($e->getMessage(), 3, '/var/tmp/phperror.log'); //Write error log
         echo '{"info":{"message":"Mandatory fields are  missing.","status":"Exception'.$e->getMessage().'"}}';
       }
     }

     function addCoffeePrice(){
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
            $success = '{"info":{"message":"Price Added Successfully.","status":"success"}}';
            echo $success;
         }else{
           echo '{"info":{"message":"Mandatory fields are  missing.","status":"error"}}';
         }
        } catch (Exception $e) {
          //error_log($e->getMessage(), 3, '/var/tmp/phperror.log'); //Write error log
          echo '{"info":{"message":"Error in api call","status":'.$e->getMessage().'}}';
        }
     }

     function addPepperPrice(){
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
            $success = '{"info":{"message":"Price Added Successfully.","status":"success"}}';
            echo $success;
         }else{
           echo '{"info":{"message":"Mandatory fields are  missing.","status":"error"}}';
         }
        } catch (Exception $e) {
          //error_log($e->getMessage(), 3, '/var/tmp/phperror.log'); //Write error log
          echo '{"info":{"message":"Error in api call","status":'.$e->getMessage().'}}';
        }
     }

     function addClosePrice(){
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
            $success = '{"info":{"message":"Price Added Successfully.","status":"success"}}';
            echo $success;
         }else{
           echo '{"info":{"message":"Mandatory fields are  missing.","status":"error"}}';
         }
        } catch (Exception $e) {
          //error_log($e->getMessage(), 3, '/var/tmp/phperror.log'); //Write error log
          echo '{"info":{"message":"Error in api call","status":'.$e->getMessage().'}}';
        }
     }

     function addNews(){
       $newsInfo = json_decode(file_get_contents("php://input"));
        try {
          if($newsInfo && $newsInfo->news){
           $news=$newsInfo->news;
            $sql="insert into news(id,news,created_date,status) values ('".getGUID()."' ,'".$news."','".date("Y-m-d h:i:s")."','ACTIVE')";
             $db = getConnection();
             $result = $db->query($sql);
             $db = null;
            $success = '{"info":{"message":"News Created Successfully.","status":"success"}}';
            echo $success;
         }else{
           echo '{"info":{"message":"Mandatory fields are  missing.","status":"error"}}';
         }
        } catch (Exception $e) {
          //error_log($e->getMessage(), 3, '/var/tmp/phperror.log'); //Write error log
          echo '{"info":{"message":"Error in api call","status":'.$e->getMessage().'}}';
        }
     }

     function updateProperties($id){
       try {
         if($_POST['title'] && $_POST['location'] && $_POST['price'] && $_POST['desc']){
            $title=$_POST['title'];
            $desc=$_POST['desc'];
            $location=$_POST['location'];
            $cost=$_POST['price'];
            $id=$_POST['id'];
            $filename = basename($_FILES['uploadimage']['name']);
            $filename=str_replace(" ","",$filename);
         	  $uid=date('YmdHis');
         	  $filename=$uid."_".$filename;
            $newname = dirname(__FILE__).'/upload/'.$filename;
            $isUploaded=false;
            $status='active';
            if (move_uploaded_file($_FILES['uploadimage']['tmp_name'],$newname)) {
                $isUploaded=true;
            }
            $image=$isUploaded ? "image=".$filename:'';
            $sql="update properties set title='".$title."',description='".$desc."',cost='".$cost."',location='".$location."',modified_date='".date("Y-m-d h:i:s")."','".$image."') where id='".$id."'";
            $db = getConnection();
            $result = $db->query($sql);
            $db = null;
            $success = '{"info":{"message":"Property Updated Successfully.","status":true}}';
            echo $success;
         }else{
           echo '{"info":{"message":"Mandatory fields are  missing.","status":false}}';
         }
       } catch (Exception $e) {
        //error_log($e->getMessage(), 3, '/var/tmp/phperror.log'); //Write error log
         echo '{"info":{"message":"Mandatory fields are  missing.","status":"Exception'.$e->getMessage().'"}}';
       }
     }

     function updateCoffeePrice($id){
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
            $success = '{"info":{"message":"Price Updated Successfully.","status":"success"}}';
            echo $success;
         }else{
           echo '{"info":{"message":"Mandatory fields are  missing.","status":"error"}}';
         }
        } catch (Exception $e) {
          //error_log($e->getMessage(), 3, '/var/tmp/phperror.log'); //Write error log
          echo '{"info":{"message":"Error in api call","status":'.$e->getMessage().'}}';
        }
     }

     function updatePepperPrice($id){
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
            $success = '{"info":{"message":"Price Updated Successfully.","status":"success"}}';
            echo $success;
         }else{
           echo '{"info":{"message":"Mandatory fields are  missing.","status":"error"}}';
         }
        } catch (Exception $e) {
          //error_log($e->getMessage(), 3, '/var/tmp/phperror.log'); //Write error log
          echo '{"info":{"message":"Error in api call","status":'.$e->getMessage().'}}';
        }
     }

      function updateClosePrice($id){
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
            $success = '{"info":{"message":"Price Updated Successfully.","status":"success"}}';
            echo $success;
         }else{
           echo '{"info":{"message":"Mandatory fields are  missing.","status":"error"}}';
         }
        } catch (Exception $e) {
          //error_log($e->getMessage(), 3, '/var/tmp/phperror.log'); //Write error log
          echo '{"info":{"message":"Error in api call","status":'.$e->getMessage().'}}';
        }
     }

     function updateNews($id){
       $newsInfo = json_decode(file_get_contents("php://input"));
        try {
          if($newsInfo && $newsInfo->news){
           $news=$newsInfo->news;
            $sql="update news set news='".$news."',modified_date='".date("Y-m-d h:i:s")."' where id='".$id."'";
             $db = getConnection();
             $result = $db->query($sql);
             $db = null;
            $success = '{"info":{"message":"News Updated Successfully.","status":"success"}}';
            echo $success;
         }else{
           echo '{"info":{"message":"Mandatory fields are  missing.","status":"error"}}';
         }
        } catch (Exception $e) {
          //error_log($e->getMessage(), 3, '/var/tmp/phperror.log'); //Write error log
          echo '{"info":{"message":"Error in api call","status":'.$e->getMessage().'}}';
        }
     }

     function deleteProperties($id){
       try {
         $sql="update properties set status='INACTIVE', modified_date='".date("Y-m-d h:i:s")."' where id='".id."'";
         $db = getConnection();
         $result = $db->query($sql);
         $db = null;
         $success = '{"info":{"message":"Property Deleted Successfully.","status":true}}';
         echo $success;
       } catch (Exception $e) {
         //error_log($e->getMessage(), 3, '/var/tmp/phperror.log'); //Write error log
         echo '{"info":{"message":"Mandatory fields are  missing.","status":"Exception'.$e->getMessage().'"}}';
       }

     }

     function deleteCoffeePrice($id){
       try {
         $sql="update properties set status='INACTIVE', modified_date='".date("Y-m-d h:i:s")."' where id='".id."'";
         $db = getConnection();
         $result = $db->query($sql);
         $db = null;
         $success = '{"info":{"message":"Property Deleted Successfully.","status":true}}';
         echo $success;
       } catch (Exception $e) {
         //error_log($e->getMessage(), 3, '/var/tmp/phperror.log'); //Write error log
         echo '{"info":{"message":"Mandatory fields are  missing.","status":"Exception'.$e->getMessage().'"}}';
       }

     }

     function deletePepperPrice($id){
       try {
         $sql="update properties set status='INACTIVE', modified_date='".date("Y-m-d h:i:s")."' where id='".id."'";
         $db = getConnection();
         $result = $db->query($sql);
         $db = null;
         $success = '{"info":{"message":"Property Deleted Successfully.","status":true}}';
         echo $success;
       } catch (Exception $e) {
         //error_log($e->getMessage(), 3, '/var/tmp/phperror.log'); //Write error log
         echo '{"info":{"message":"Mandatory fields are  missing.","status":"Exception'.$e->getMessage().'"}}';
       }

     }

     function deleteClosePrice($id){
       try {
         $sql="update properties set status='INACTIVE', modified_date='".date("Y-m-d h:i:s")."' where id='".id."'";
         $db = getConnection();
         $result = $db->query($sql);
         $db = null;
         $success = '{"info":{"message":"Property Deleted Successfully.","status":true}}';
         echo $success;
       } catch (Exception $e) {
         //error_log($e->getMessage(), 3, '/var/tmp/phperror.log'); //Write error log
         echo '{"info":{"message":"Mandatory fields are  missing.","status":"Exception'.$e->getMessage().'"}}';
       }

     }

     function deleteNews($id){
       try {
         $sql="update properties set status='INACTIVE', modified_date='".date("Y-m-d h:i:s")."' where id='".id."'";
         $db = getConnection();
         $result = $db->query($sql);
         $db = null;
         $success = '{"info":{"message":"Property Deleted Successfully.","status":true}}';
         echo $success;
       } catch (Exception $e) {
         //error_log($e->getMessage(), 3, '/var/tmp/phperror.log'); //Write error log
         echo '{"info":{"message":"Mandatory fields are  missing.","status":"Exception'.$e->getMessage().'"}}';
       }

     }
     function deleteImage($id){
       try {
         $sql="update properties set status='INACTIVE', modified_date='".date("Y-m-d h:i:s")."' where id='".id."'";
         $db = getConnection();
         $result = $db->query($sql);
         $db = null;
         $success = '{"info":{"message":"Property Deleted Successfully.","status":true}}';
         echo $success;
       } catch (Exception $e) {
         //error_log($e->getMessage(), 3, '/var/tmp/phperror.log'); //Write error log
         echo '{"info":{"message":"Mandatory fields are  missing.","status":"Exception'.$e->getMessage().'"}}';
       }

     }
     
     function searchProperties($id){
       $searchInfo = json_decode(file_get_contents("php://input"));
       try {
         if($searchInfo->flag){
           $location=implode("','",$searchInfo->locationList);
           $sql="select * from properties where type='".$id."' && location in('".$location."' && status='ACTIVE')";
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
         //error_log($e->getMessage(), 3, '/var/tmp/phperror.log'); //Write error log
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
         $sql="select DISTINCT location from properties where type='".$type."' && status='ACTIVE'";
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
