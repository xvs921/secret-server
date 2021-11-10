<?php

namespace App\Http\Controllers;

use App\Models\Secret;
use Illuminate\Http\Request;

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
        $secret->save();
        return $secret->toJson(JSON_PRETTY_PRINT);
    }

    /**
     * Display the specified item.
     *
     * @param  \App\Models\Secret  $secret
     * @return \Illuminate\Http\Response
     */
    public function getSecret(Secret $secret)
    {
        $secret = Secret::where('secret', $secret)->first();
        return $secret->toJson(JSON_PRETTY_PRINT);
    }
}
