<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

use Illuminate\Support\Facades\Auth;
 
class Controller extends BaseController
{
  // 525600 minutes in 1 year
  protected function respondWithToken($token)
    {
        return response()->json([
			'token'      => $token,
			'token_type' => 'bearer',
			'expires_in' => Auth::factory()->getTTL() * 525600
        ], 200);
    }

    protected function respondTokenWithDeviceInfo($token, $device)
    {
    	return response()->json([
			'primary_phone_number' => $device->primary_phone_number,
			'id'                   => $device->id,
			'token'                => $token,
			'token_type'           => 'bearer',
			'expires_in'           => Auth::factory()->getTTL() * 525600
        ], 200);
    }
}
