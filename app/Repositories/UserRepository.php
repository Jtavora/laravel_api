<?php

namespace App\Repositories;
use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function index(){
        return User::all();
    }

    public function getById($id){
        return User::findOrFail($id);
    }

    public function store(array $data){
        $user = User::create([
            'name'=>$data['name'],
            'email'=>$data['email'],
            'password'=>Hash::make($data['password'])
        ]);

        return $user;
    }

    public function update(array $data,$id){
        return User::whereId($id)->update($data);
    }

    public function delete($id){
        User::destroy($id);
    }

    public function login($data){
        $user = User::where('email', $data->email)->first();

        if (!$user || !Hash::check($data->password, $user->password)) {
            $response['success'] = false;
            $response['message'] = "User {$data->email} not found or Password is incorrect";

            return $response;
        }

        $token = $user->createToken('my-app-token')->plainTextToken;

        $response = [
            'success' => true,
            'user' => $user,
            'token' => $token
        ];

        return $response;
    }

    public function logout(){
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Tokens Revoked'
        ];
    }
}
