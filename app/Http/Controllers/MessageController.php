<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Device;
use App\Message;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function store(Request $request)
    {
        $this->validate($request, [
            'device_id'    => 'required',
            'phone_number' => 'required',
            'message'      => 'required'
        ]);

        $device = Device::find($request->device_id);

        $message = new Message([
			'phone_number' => $request->phone_number,
			'message'      => $request->message,
        ]);

        $device->messages()->save($message);

        $device = Device::with('messages')->find($device->id);

        return $device->messages;
    }
}
