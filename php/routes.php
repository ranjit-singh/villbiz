<?php
require 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

$app->get('/users','getUsers');
$app->get('/properties/:type','getProperties');
$app->get('/coffee/price','getCoffeePrice');
$app->get('/pepper/price','getPepperPrice');
$app->get('/close/price','getClosePrice');
$app->get('/news','getNews');
$app->get('/logout','logOut');
$app->get('/verifyotp/:id/:otp','verifyOtp');
$app->get('/resentotp/:mobile','resentOtp');
$app->get('/properties/search/:type','propertiesSearch');
$app->get('/location/:type','getLocation');
$app->get('/userprofile/:id','getUserProfile');
$app->get('/verifyresetpasswordotp/:mobile/:otp','verifyResetPasswordOtp');

$app->post('/createuser', 'addUsers');
$app->post('/login','userLogin');
$app->post('/contactus','addContactus');
$app->post('/createproperties','addProperties');
$app->post('/search/:id','searchProperties');
$app->post('/coffeeprice','addCoffeePrice');
$app->post('/news','addNews');
$app->post('/pepperprice','addPepperPrice');
$app->post('/closeprice','addClosePrice');
$app->post('/setpassword','setNewPassword');

$app->post('/properties/:id','updateProperties');
$app->post('/coffeeprice/:id','updateCoffeePrice');
$app->post('/news/:id','updateNews');
$app->post('/pepperprice/:id','updatePepperPrice');
$app->post('/closeprice/:id','updateClosePrice');

$app->delete('/properties/:id','deleteProperties');
$app->delete('/coffee/price/:id','deleteCoffeePrice');
$app->delete('/pepper/price/:id','deletePepperPrice');
$app->delete('/close/price/:id','deleteClosePrice');
$app->delete('/news/:id','deleteNews');
$app->delete('/properties/image/:id/:imagename','deleteImage');


$app->run();
?>
