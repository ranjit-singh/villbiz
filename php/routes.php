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
$app->post('/createuser', 'addUsers');
$app->post('/contactus','addContactus');
$app->post('/createproperties','addProperties');
$app->post('/login','userLogin');
$app->delete('/properties/:id',    'deleteProperties');
$app->post('/search/:id','searchProperties');
$app->post('/coffeeprice','addCoffeePrice');
$app->post('/news','addNews');
$app->post('/pepperprice','addPepperPrice');
$app->post('/closeprice','addClosePrice');
$app->get('/verifyotp/:id/:otp','verifyOtp');
$app->get('/resentotp/:mobile','resentOtp');
$app->get('/properties/search/:type','propertiesSearch');
$app->get('/location/:type','getLocation');
/*
$app->post('/updates', 'insertUpdate');
$app->delete('/updates/delete/:update_id','deleteUpdate');
$app->get('/users/search/:query','getUserSearch');
*/
$app->run();
?>
