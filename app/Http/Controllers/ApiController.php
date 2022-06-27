<?php

namespace App\Http\Controllers;

use App\Mail\PasswordReset;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ApiController extends Controller
{
    public function getUserCompanies(Request $request): array
    {
        /** @var User $user */
        $user = $request->user();

        if (!$user) {
            return [
                'status' => 400,
                'message' => 'Oops. Something went wrong'
            ];
        }

        return [
            'status' => 200,
            'message' => 'OK.',
            'data' => $user->companiesRelation
        ];
    }

    public function createUserCompany(Request $request): array
    {
        /** @var array $data */
        $data = [
            'title' => $request->title,
            'phone' => $request->phone,
            'description'  => $request->description
        ];

        /** @var User $user */
        $user = $request->user();

        if (!$user) {
            return [
                'status' => 400,
                'message' => 'Oops. Something went wrong'
            ];
        }

        /** @var array $validate */
        $validate = $this->validateData($data, [
            'title' => 'required|string|min:3|max:50',
            'phone' => 'required|string|min:10|max:25',
            'description'  => 'required|string|min:10|max:255'
        ]);

        if ($validate['fails']) {
            return [
                'status' => 400,
                'message' => $validate['errors']
            ];
        }

        $user->companiesRelation()->create($data);

        return [
            'status' => 201,
            'message' => 'Company created.'
        ];
    }

    public function registerUser(Request $request): array
    {
        /** @var array $data */
        $data = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => $request->password
        ];

        $password = Hash::make($request->password);

        /** @var array $validate */
        $validate = $this->validateData($data, [
            'first_name' => 'required|string|min:3|max:50',
            'last_name' => 'required|string|min:3|max:50',
            'email' => 'required|unique:App\Models\User,email|email',
            'phone' => 'required|min:10',
            'password' => 'required|min:3|max:50'
        ]);

        if ($validate['fails']) {
            return [
                'status' => 400,
                'message' => $validate['errors']
            ];
        }

        $data['password'] = Hash::make($request->password);
        User::create($data);


        return [
            'status' => 201,
            'message' => 'User successfully created.'
        ];
    }

    public function authUser(Request $request): array
    {
        /** @var array $data */
        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];

        /** @var array $validate */
        $validate = $this->validateData($data, [
            'email' => 'required|email',
            'password' => 'required|string|min:3|max:50'
        ]);

        if ($validate['fails']) {
            return [
                'status' => 400,
                'message' => $validate['errors']
            ];
        }

        /** @var User $user */
        $user = User::query()->where('email', $request->email)->first();

        if (!$user) {
            return [
                'status' => 400,
                'message' =>  'This user does not exists.'
            ];
        }

        if (Auth::attempt($data))
        {
            Auth::user();

            return [
                'status' => 200,
                'message' => 'You are successfully logged in.'
            ];
        }

        return [
            'status' => 400,
            'message' => 'Wrong email or password',
        ];
    }

    public function recoverUserPassword(Request $request): array
    {
        /** @var array $validate */
        $validate = $this->validateData([
            'email' => $request->email,
        ], [
           'email' => 'required|string|exists:App\Models\User,email'
        ]);

        if ($validate['fails']) {
            return [
                'status' => 400,
                'message' => $validate['errors']
            ];
        }

        /** @var string $password */
        $password = Str::random(10);

        User::query()->where('email', $request->email)->update([
            'password' => Hash::make($password)
        ]);

        Mail::to($request->email)->send(new PasswordReset($password));

        return [
            'status' => 200,
            'message' => 'New password sent to your email.'
        ];
    }

    private function validateData(array $data, array $rules): array
    {
        /** @var Validator $validator */
        $validator = Validator::make($data, $rules);

        return [
            'fails' => $validator->fails(),
            'errors' => $validator->errors(),
        ];
    }
}
