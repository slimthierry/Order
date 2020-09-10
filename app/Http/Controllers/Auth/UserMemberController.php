<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\UserMember;
use Illuminate\Validation\Validator;
use App\Http\Resources\Auth\UserMembersResource;

class UserMemberController extends Controller
{
    /**
     * Protect update and delete methods, only for authenticated users.
     *
     *
     * @return Unauthorized
     */
    private $validator;

    public function __construct(validator $validator)
    {
    //   $this->middleware('auth:api')->except(['index']);

        $this->validator = $validator;
        // Auth::shouldUse('userMember');

    }
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response

     */
    public function index()
    {
        // dd(Auth::check());
        if (Auth::guest()||Auth::user()->profil_id !==1) {
            return response()->json(['error' => 'You are not authorised to do this operation.'], 403);
        }
        $listUsers = UserMember::all();
        return $listUsers;

        // Using Paginate method
        // return UserMembersResource::collection(UserMember::all());
        // Using Paginate method
        // return UserMembersResource::collection(UserMember::with('ratings')->paginate(10));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return (Auth::check())?
        (Auth::user()->profil_id ==1 || Auth::user()->id == $id)?
        new UserMembersResource(UserMember::with('Profils')->findOrFail($id))
        :
        response()->json(['error' => 'You can only check your own account.'], 403)
        :
        response()->json(['error' => 'Sorry you are not allow to do this operation.'], 403);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserMember $userMember)
    {
            $validator = $this->validator::make($request->all(), [
            'firstname' => 'required|string|max:255|min:2',
            'lastname' => 'required|string|max:255|min:2',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
            'profil_id' => 'required',
            'role' => 'required',
            'payment_method_id' =>'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // check if currently authenticated user is the user owner
        if(Auth::guest())return response()->json(['error' => 'You are not authorised to do this operation.'], 403);
        if ($request->user()->profil_id !==1 && $request->user()->id !== $userMember->id) {
            return response()->json(['error' => 'You can only edit your own account.'], 403);
        }

        $request['password']= Hash::make($request['password']);
        if($userMember->profil_id !==1)
        $userMember->update($request->only(['email', 'firtname', 'lastname', 'password']));
        else
        $userMember->update($request->all());

        return new UserMembersResource($userMember);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Auth::user()->profil_id !==1) {
            return response()->json(['error' => 'You are not authorised to do this operation.'], 403);
        }
        $deleteUserById = UserMember::find($id)->delete();
        return response()->json([], 204);
    }

}
