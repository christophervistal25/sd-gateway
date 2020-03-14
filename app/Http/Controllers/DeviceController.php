<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Device;
use Illuminate\Support\Facades\Hash;

class DeviceController extends Controller
{

    public function message(int $device_id)
    {
        $deviceMessages = Device::with('messages')->find($device_id)
                                       ->messages;
        $messages = $deviceMessages;
        Device::find($device_id)->messages()->delete();
        return $messages;
    }

    public function register(Request $request)
    {
        $this->validate($request, [
            'primary_phone_number' => 'required|min:11|max:11|unique:devices',
            'password'             => 'required',
        ]);

        return Device::create($request->all());

    }

    public function login(Request $request)
    {
        $device = Device::where('primary_phone_number', $request->primary_phone_number)
                ->first();
                

        if ($device && Hash::check($request->password, $device->password)) {
            return response()->json(['info' => $device, 'message' => $device->messages]);
        }

        return response()->json(['message' => 'Unauthorized'], 401);


    }

}
