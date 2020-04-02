<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Device;
use App\Message;

class DeviceControllerTest extends TestCase
{
	use DatabaseMigrations;

	/**
	 * @test
	 */
	public function it_required_primary_phone_number_and_password_for_register()
	{
		$route = $this->json('POST', '/api/device/register', [
			'primary_phone_number' => '',
			'password'             => '',
    	]);

    	$content = (array) json_decode($route->response->getContent());

    	$route->seeJson($content);
	}

    /**
     * @test
     */
    public function it_can_store_new_user()
    {
    	$credentials = [
			'primary_phone_number' => '09193693499',
			'password'             => 'password'
     	];

    	$route = $this->json('POST', '/api/device/register', $credentials);

    	$content = json_decode($route->response->getContent());

    	$route->seeJson([
    		'id' => $content->id,
    		'primary_phone_number' => $content->primary_phone_number,
    		'token' => $content->token,
    		'token_type' => $content->token_type,
    		'expires_in' => $content->expires_in
    	]);
    }

    /**
     * @test
     */
    public function it_can_login_a_device()
    {
    	$device = factory(Device::class)->create();

    	$route = $this->json('POST', '/api/device/login', [
			'primary_phone_number' => $device->primary_phone_number,
			'password'             => 'password',
    	]);

    	$content = json_decode($route->response->getContent());

    	$route->seeJson([
    		'token' => $content->token,
    		'token_type' => $content->token_type,
    		'expires_in' => $content->expires_in
    	]);
    }

    /**
     * @test
     */
    public function user_must_be_logged_in_to_get_messages()
    {
    	$device = factory(Device::class)->create();

    	$message = new Message([
			'phone_number' => $device->primary_phone_number,
			'message'      => 'This is a sample message',
        ]);

    	$device->messages()->save($message);

	    $route = $this->json('GET', "/api/device/messages/{$device->id}");

		$this->assertEquals('Unauthorized.', $route->response->getContent());
    }

    /**
     * @test
     */
    public function it_can_get_message_by_device_id()
    {
    	$device = factory(Device::class)->create();

    	$this->actingAs($device);

    	$message[] = new Message([
			'phone_number' => $device->primary_phone_number,
			'message'      => 'This is a sample message',
        ]);

        $message[] = new Message([
			'phone_number' => $device->primary_phone_number,
			'message'      => 'This is a sample message2',
        ]);

    	$device->messages()->saveMany($message);

	    $route = $this->json('GET', "/api/device/messages/{$device->id}");

    	$content = $route->response->original;

    	$this->assertEquals($content->first()->device_id, $device->id);
    	$this->assertArrayHasKey('phone_number', $content->first());
    	$this->assertArrayHasKey('device_id', $content->first());
    	$this->assertArrayHasKey('message', $content->first());
    	$this->assertArrayHasKey('code', $content->first());
    	$this->assertArrayHasKey('created_at', $content->first());
    }
}
