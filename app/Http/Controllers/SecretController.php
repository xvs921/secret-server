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
            'remainingViews' => 'required|max:10',
        ]);

        $createdAt = new DateTime();
        $expiresAt = $createdAt->add(new DateInterval('P'.$expireDays.'D'));

        try {
            $newSecret = Secret::create([
                'hash' 		 => hash('sha256', request('secret')),
                'secretText' => request('secret'),
                'created_at' => $createdAt,
                'expires_at'	 => $expiresAt,
                'remainingViews' => request('remainingViews'),
            ]);
        } catch (Throwable $e) {
            return response()->preferredFormat(['message' => 'Error with create new secret'], 404);
        }
        return $this->getResponse(request(), $newSecret, 200);

    }

    /**
     * Display the specified item.
     *
     * @param  \App\Models\Secret  $secret
     * @return \Illuminate\Http\Response
     */
    public function secretFromForm()
    {
        return $this->getSecret(request('secret'));
    }

    /**
     * Display the specified item.
     *
     * @param  String  $secret
     * @return \Illuminate\Http\Response
     */
    public function getSecret($secret)
    {
        $foundSecret = Secret::findSecret($hash);

    	if ($foundSecret) {
    		$foundSecret->decreaseViewCounter();
    		return $this->getResponse(request(), $foundSecret, 200);
    	}

    	return response()->preferredFormat(['message' => 'Secret Not Found'], 404);  
    }

    public function getResponse($request, $secret, $status)
    {
    	$response;
        if(request()->header('accept') && request()->header('accept') == 'application/json'){
            $response = response(new SecretResource($secret), $status);
        }
        else if(request()->header('accept') && request()->header('accept') == 'application/xml'){
            $response = response()->preferredFormat($secret, $status, [], class_basename($secret));
        }
        else{
            $response = new SecretResource($secret);
        }
		return $response;
    }
}
