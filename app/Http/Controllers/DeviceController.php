<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Device;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class DeviceController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth', ['except' => ['register', 'login']]);
    }


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

        $credentials = $request->only(['primary_phone_number', 'password']);
        $device = Device::create($credentials);

        return $this->respondTokenWithDeviceInfo(
            Auth::attempt($credentials),
            $device
        );

    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'primary_phone_number' => 'required',
            'password'             => 'required',
        ]);

        $credentials = $request->only(['primary_phone_number', 'password']);
        if (! $token = Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $this->respondTokenWithDeviceInfo($token, Auth::user());
    }

    public function refreshToken()
    {
       return Auth::refresh(true, true);
    }

}
