<?php

namespace App\Http\Controllers;

use App\Events\UserEvent;
use App\Models\notifications;
use App\Models\User;
use Illuminate\Http\Request;

class InformationsController extends Controller
{
    public function index(){
        return view("Admin.informations.index");  
    }




    public function change_picture_statut(Request $request){
        $id_user = $request->input('id_user' ?? null);
        $user = User::find($id_user);
        if($user){
            if (is_null($user->photo_verified_at)) {
                $user->photo_verified_at = now();
    
                //make notification
                event(new UserEvent($user->id));
                $notification = new notifications();
                $notification->titre = "Votre photo de profil a été validé !";
                $notification->id_user_destination = $user->id;
                $notification->type = "alerte";
                $notification->destination = "user";
                $notification->message = "Nous vous informons que votre photo de profil a été validé par les administrateurs";
                $notification->save();
            } else {
                $user->photo_verified_at = null;
            }
            $user->save();

            //return with success message
            return redirect()->back()->with("success","Le changement a été éffectuer !");
        }
    }
}
