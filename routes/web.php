<?php



$router->get('/', function () {
	// return 'Welcome to SDGateway';
});








$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('/device/login', 'DeviceController@login');
    $router->post('/device/register', 'DeviceController@register');

    $router->post('/device/refresh', 'DeviceController@refreshToken');
    
    $router->post('/device/send/message', 'MessageController@store');
    $router->get('/device/messages/{device_id}', 'DeviceController@message');
});

