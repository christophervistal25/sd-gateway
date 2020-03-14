<?php


$router->get('/', function () {
	echo 'Working in progress';
});


$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('/device/login', 'DeviceController@login');
    $router->post('/device/register', 'DeviceController@register');

    $router->post('/device/send/message', 'MessageController@store');
    $router->get('/device/messages/{device_id}', 'DeviceController@message');
});
