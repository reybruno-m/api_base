<?php

namespace App\Http\Controllers\Mail;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use App\Http\Controllers\ActivityController;
use App\Models\User;

use App\Mail\Users\CreateAccount;
use App\Mail\Users\EmailUpdate;
use App\Mail\Users\ForgottenPassword;

use Illuminate\Support\Facades\Validator;

class UserEmailController extends Controller
{
    public $activity;

    public function __construct(){
        $this->middleware('auth:api');
        $this->activity = new ActivityController();
    }

    # Envia al usuario recientemente registrado un enlace de verificación de cuenta. 
    public function createAccount($user){

        if($user){
            Mail::to($user->email)->send(new CreateAccount($user));
            $this->activity->store("users", "EMAIL", $user->id, "Código de verificación de cuenta enviado");

            return response()->json([
                'success' => true, 
                'message' => "Se envió un código de verificación a su email."
            ], 200 );
        }
    }

    # Envia al usuario un enlace de verificación de cuenta, se utiliza cuando se modifica la dirección de email.
    public function emailUpdate($user){

        if($user){
            Mail::to($user->email)->send(new EmailUpdate($user));
            $this->activity->store("users", "EMAIL", $user->id, "Email Modificado, Código de verificación de cuenta enviado");

            return response()->json([
                'success' => true, 
                'message' => "Se envió un código de verificación a su email."
            ], 200 );
        }
    }

    # Envia al usuario un enlace de restablecimiento de clave. 
    public function forgottenPassword($user){

        if($user){
            Mail::to($user->email)->send(new ForgottenPassword($user));
            $this->activity->store("users", "EMAIL", $user->id, "Solicitud de restablecimiento de clave generada.");
            
            return response()->json([
                'success' => true, 
                'message' => "Se envió un código de restablecimiento a su email."
            ], 200 );
        }
    }
    
}
