<?php

namespace App\Http\Controllers;

use App\Http\Requests\PhotoRequest;
use App\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class PhotoController extends Controller
{
    //Просмотр списка фотографий (готово)
    public function index()
    {
        $photos=Photo::all(['id','name','url','owner_id']);
        foreach ($photos as $photo)
        {
            $photo->users=$photo->users()->pluck('users.id');
        }
        return response()->json($photos,200);
    }

    //Просмотр одного фото (готово)
    public function show(Photo $photo)
    {
        $photo->users=$photo->users()->pluck('users.id');
        return response()->json($photo->only('id','name','url','owner_id','users'),200);
    }

    //Удаление фото (не удаляется сам файл)
    public function destroy(Photo $photo)
    {
        if($photo->owner_id!=Auth::user()->id)
        {
            return response()->noContent()->setStatusCode(403);
        }
        //$photo->delete();
        return response()->noContent()->setStatusCode(204);
    }

    //Загрузка фото (готово)
    public function store(PhotoRequest $request)
    {
        $path=$request->file('photo')->store('public');
        $photo=new Photo();
        $photo->name='Untitled';
        $photo->owner_id=Auth::user()->id;
        $photo->url=$path;
        $photo->save();
        return response()->json($photo->only('id','name','url'),201);
    }

    //Изменение фото (надо проверить, старый файл не удаляется)
    public function update(Request $request, Photo $photo)
    {
        if($photo->owner_id!=Auth::user()->id)
        {
            return response()->noContent()->setStatusCode(403);
        }
        if($request->name!=null)
        {
            $photo->name=$request->name;
        }
        if($request->photo!=null) {
            $base64_str = substr($request->photo, strpos($request->photo, ",") + 1);
            $image = base64_decode($base64_str);
            $imageName = time().".png";
            //File::put($photo->url,$image);
            File::put(storage_path('app/public').'/'.$imageName,$image);
            $photo->url = 'public/'.$imageName;
        }
        $photo->save();
        return response()->json($photo->only('id','name','url'),200);
    }
}
