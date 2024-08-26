<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\ClassResponsible;
use App\Models\SubGrade;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(50);

        return inertia('User/Index', ['users' => $users]);
    }

    public function create()
    {
        return inertia('User/Create', ['userTypes' => UserType::all(['id', 'name'])]);
    }

    public function store(CreateUserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'user_type_id' => $request->user_type_id,
            'is_active' => $request->is_active == 1 ? true : false,
            'password' => Hash::make($request->password),
        ]);

        // Upload user photo
        if ($request->hasFile('photo')) {
            $user->photo = 'profile-photos/' . $this->upload($request);
            $user->save();
        }

        return redirect('users');
    }

    public function edit(User $user)
    {
        $subGrades = SubGrade::all(['id', 'name']);
        $responsibles = ClassResponsible::where('teacher_id', $user->id)->pluck('id');
        return inertia('User/Edit', ['user' => $user, 'userTypes' => UserType::all(['id', 'name']), 'subGrades' => $subGrades, 'resposibles' => $responsibles]);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $user->name = $request->name;
        $user->email = $request->email;
        $user->user_type_id = $request->user_type_id;
        $user->is_active = $request->is_active == 1 ? true : false;


        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        // Upload user photo
        if ($request->hasFile('photo')) {
            $existingFilePath = $user->photo;

            if ($existingFilePath) {
                $explodeFile = explode('storage/', $existingFilePath);
                Storage::delete('public/' . end($explodeFile));
            }

            $user->photo = 'profile-photos/' . $this->upload($request);
        }

        if ($file = $request->file('signature')) {
            $fileName = time().$file->getClientOriginalName();

            $file->move(public_path().'/images/', $fileName);


            $user->signature = $fileName;
        }

        $user->save();

        return redirect('users');
    }

    public function upload($request)
    {
        if ($request->hasFile('photo')) {
            $filename = time() . '-' . $request->photo->getClientOriginalName();
            $request->photo->storeAs('profile-photos', $filename, 'public');

            return $filename;
        }
    }
}
