<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Address;
use App\Models\Company;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;

class UserController extends Controller
{
    public function getAllUsers()
    {
        return User::all();
    }

    public function createUser(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'required',
            'email' => 'required|email|',
            'phone' => 'required|min:1',
            'website' => 'required|min:1',
        ]);

        try {
            $addresId = $this->addAddress($request);
            $companyId = $this->addCompany($request);

            $user = new User;
            $user->name = $request->name;
            $user->username = $request->username;
            $user->email = $request->email;
            $user->address = $addresId;
            $user->phone = $request->phone;
            $user->website = $request->website;
            $user->company = $companyId;
            $user->save();
            return 'User Created';
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function addAddress($request)
    {
        $request->validate([
            'street' => 'required',
            'suite' => 'required',
            'city' => 'required',
            'zipcode' => 'required|min:1',
            'lat' => 'required|min:1',
            'lng' => 'required|min:1',
        ]);

        $geo = array(
            "lat" => $request->lat,
            "lng" => $request->lng
        );
        $geoJson = json_encode($geo);

        try {
            $address = new Address();
            $address->street = $request->street;
            $address->suite = $request->suite;
            $address->city = $request->city;
            $address->zipcode = $request->zipcode;
            $address->geo = $geoJson;
            $address->save();

            return $address->id;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function addCompany($request)
    {
        $request->validate([
            'name' => 'required',
            'catchPhrase' => 'required',
            'bs' => 'required',
        ]);

        try {
            $company = new Company();
            $company->name = $request->name;
            $company->catchPhrase = $request->catchPhrase;
            $company->bs = $request->bs;
            $company->save();

            return $company->id;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getUserCount()
    {
        return User::all()->count();
    }
}
