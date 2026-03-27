<?php

namespace App\Repository\Implement;

use App\Repository\interfaces\UserRepositoryInterface;
use App\Models\User;

class UserRepository implements UserRepositoryInterface {
    public function all() {
        return User::all();
    }

    public function create(array $data) {
        return User::create([
            'name'=>$data['name'],
            'email'=>$data['email'],
            'password'=>bcrypt($data['password']),
            'role'=>$data['role']
        ]);
    }

    public function find($id) {
        return User::find($id);
    }

    public function update($id, array $data) {
        $user = User::find($id);
        $user->update([
            'name'=>$data['name'],
            'email'=>$data['email'],
            'password'=>bcrypt($data['password']),
            'role'=>$data['role']
        ]);
        return $user;
    }

    public function delete($id) {
        $user = User::find($id);
        $user->delete();
        return $user;
    }

    public function findByEmail(string $email) {
        return User::where('email', $email)->first();
    }

    public function attachDomains($userId, array $domainIds) {
        $user = User::find($userId);
        $user->domains()->attach($domainIds);
    }
}