<?php

namespace App\Http\Controllers\API;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Intervention\Image;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function index()
    {
        return $users= User::latest()->paginate(10);
    }

    public function profile(){
        return auth('api')->user();
    }

    public function updateProdileInfo(Request $request){
        $user = auth('api')->user();

        $this->validate($request, [
            'name' => 'required|regex:/^[\pL\s\-]+$/u',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => 'sometimes|required|string|min:6'
        ]);

        $currentPhoto = $user->photo;
        if ($request->photo != $currentPhoto){
            $name = time().'.' . explode('/', explode(':', substr($request->photo, 0, strpos($request->photo, ';')))[1])[1];

            \Image::make($request->photo)->save(public_path('img/profile/').$name);

            $request->merge(['photo' => $name]);
        }

        if (!empty($request->password)){
            $request->merge(['password' => Hash::make($request['passord'])]);
        }
        $user->update($request->all());
        return ['message' => 'success'];
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|regex:/^[\pL\s\-]+$/u',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6'
        ]);
        return User::create([
            'name'=> $request['name'],
            'email'=> $request['email'],
            'type'=> $request['type'],
            'photo'=> $request['photo'],
            'bio'=> $request['bio'],
            'password'=> Hash::make($request['password'])
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $userById= User::findOrFail($id);
        $this->validate($request, [
            'name' => 'required|regex:/^[\pL\s\-]+$/u',
            'email' => 'required|email|unique:users,email,'.$userById->id,
            'password' => 'sometimes|string|min:6'
        ]);
        $userById->update($request->all());

        return ['message'=> 'Update Successfully'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user= User::findOrFail($id);
        $user->delete();

        return ['message'=> 'User Deleted'];
    }
}
