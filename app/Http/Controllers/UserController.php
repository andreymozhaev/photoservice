<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserRequest;
use App\Photo;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //Регистрация (готово)
    public function signup(UserRequest $request)
    {
        $user=User::create($request->all());
        return response()->json($user->id,201);
    }

    //Аутентификация (готово)
    public function login(LoginRequest $request)
    {
        if($user=User::where('phone',$request->phone)->first()and Hash::check($request->password,$user->password))
        {
            return response()->json(['token'=>$user->generateToken()],200);
        }
        return response()->json(['login'=>'Incorrect login or password'],404);
    }

    //Выход (готово)
    public function logout()
    {
        Auth::user()->api_token=null;
        Auth::user()->save();
        return response()->noContent()->setStatusCode(200);
    }

    //Шаринг (готово)
    public function share(Request $request,User $user)
    {
        $myPhotos=Photo::where('owner_id',Auth::user()->id)->get(['id'])->pluck('id');
        $collection=collect($request->photos);
        $intersect=$collection->intersect($myPhotos);
        $user->photos()->syncWithoutDetaching($intersect);
        return response()->json(['existing_photos'=>$user->photos()->pluck('photos.id')],200);
    }

    //Поиск (готово)
    public function search(Request $request)
    {
        $query=User::query();
        foreach (explode(' ',$request->search) as $word)
        {
            $query->where(DB::raw("CONCAT(first_name,' ',surname,' ',phone)"),'LIKE','%'.$word.'%')
            ->where('id','!=',Auth::user()->id);
        }
        $users=$query->get(['id','first_name','surname','phone']);
        return response()->json($users,200);
    }
}
