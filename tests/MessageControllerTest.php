<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Device;
use App\Message;

class MessageControllerTest extends TestCase
{
	use DatabaseMigrations;

    

    /**
     * @test
     */
    public function it_require_message()
    {
        $device = factory(Device::class)->create();
        
        $this->actingAs($device);

        $data = [
            'device_id'    => $device->id,
            'phone_number' => $device->primary_phone_number,
            'message'      => ''
        ];

        $route = $this->json('POST', '/api/device/send/message', $data);


        $content = (array) json_decode($route->response->getContent());

        $route->seeJson($content);
    }

    /**
     * @test
     */
    public function it_require_phone_number()
    {
        $device = factory(Device::class)->create();
        
        $this->actingAs($device);

        $data = [
            'device_id'    => $device->id,
            'phone_number' => '',
            'message' => 'This is my sample message'
        ];

        $route = $this->json('POST', '/api/device/send/message', $data);


        $content = (array) json_decode($route->response->getContent());

        $route->seeJson($content);
    }


    /**
     * @test
     */
    public function it_require_device_id()
    {
        $device = factory(Device::class)->create();
        
        $this->actingAs($device);

        $data = [
            'device_id'    => '',
            'phone_number' => $device->primary_phone_number,
            'message' => 'This is my sample message'
        ];

        $route = $this->json('POST', '/api/device/send/message', $data);


        $content = (array) json_decode($route->response->getContent());

        $route->seeJson($content);
    }

    /**
     * @test
     */
    public function it_must_be_logged_in()
    {
        $device = factory(Device::class)->create();
        

        $data = [
            'device_id'    => $device->id,
            'phone_number' => $device->primary_phone_number,
            'message' => 'This is my sample message'
        ];

        $route = $this->json('POST', '/api/device/send/message', $data);

        $this->assertEquals('Unauthorized.', $route->response->getContent());
    }

    /**
     * @test
     */
    public function it_can_store_message_by_device()
    {
        $device = factory(Device::class)->create();
        
        $this->actingAs($device);

        $data = [
            'device_id'    => $device->id,
            'phone_number' => $device->primary_phone_number,
            'message' => 'This is my sample message'
        ];

        $route = $this->json('POST', '/api/device/send/message', $data);

        $content = $route->response->original->first();
        

        $route->seeJson([
            'phone_number' => $content->phone_number,
            'message'      => $content->message,
            'code'         => $content->code,
            'created_at'   => $content->created_at
        ]);

    }


}
