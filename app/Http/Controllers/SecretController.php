<?php

namespace App\Http\Controllers;

use App\Models\Secret;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SecretController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	public function index() {
		return view('welcome', [
			'title' => 'All Tasks',
		]);
	}

    /**
     * Create a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createSecret()
    {
        request()->validate([
            'secret' => 'required|max:150',
            'expiresDays' => 'required|max:10',
        ]);

        $secret = new Secret(request('secret'),request('expiresDays'));
        $dbReq = DB::table('secrets')->insert([
            'hash' => $secret->hash,
            'secretText' => $secret->secretText,
            'created_at' => $secret->createdAt,
            'expires_at' => $secret->expiresAt,
            'remainingViews' => $secret->remainingViews,
        ]);
        echo $secret->toJson(JSON_PRETTY_PRINT);
    }

    /**
     * Display the specified item.
     *
     * @param  \App\Models\Secret  $secret
     * @return \Illuminate\Http\Response
     */
    public function secretFromForm()
    {
        redirect('/v1/secret/'.request('secret'));
    }

    /**
     * Display the specified item.
     *
     * @param  String  $secret
     * @return \Illuminate\Http\Response
     */
    public function getSecret($secret)
    {
        $secretObject = Secret::where('secret', $secret)->first();
        echo 'Obj:'.$secretObject->hash;
    }
}
