<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ApiEmailVerification;
use App\Notifications\apiEmailVerification as NotificationsApiEmailVerification;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
public function update(Request $request, $id)
{
    $user = User::find($id);

    if ($user) 
    {
        $code = rand(100000, 999999);

        ApiEmailVerification::updateOrCreate(
            ['user_id' => $user->id],
            ['evcode' => $code]
        );

        $user->notify(new NotificationsApiEmailVerification($code));

        return response()->json([
            'status' => 1,
            'message' => 'Code successfully sent to user email!',
        ]);
    } else {
        return response()->json([
            'status' => 0,
            'message' => 'Code not sent, user not found!',
        ], 404);
    }
}

}
