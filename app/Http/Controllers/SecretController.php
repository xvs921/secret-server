<?php

namespace App\Http\Controllers;

use App\Models\Secret;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\SecretResource;
use DateTime;
use DateTimeZone;
use DateInterval;

class SecretController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	public function index() {
        $secrets = Secret::get();
		return view('welcome', [
			'secrets' => $secrets,
		]);
	}

    /**
     * Create a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createSecret()
    {
        try {
            request()->validate([
                'secret' => 'required|max:150',
                'expiresDays' => 'required|max:10',
                'remainingViews' => 'required|max:10',
            ]);
        } catch (Throwable $e) {
            return $this->getBadResponse(request(), 'Invalid input', 405);
        }

        $expire = request('expiresDays');
        $createdAt = new DateTime();
        $expiresAt = new DateTime();
        $expiresAt->add(new DateInterval('PT'.$expire.'M'));

        try {
            $newSecret = Secret::create([
                'hash' => hash('sha256', request('secret')),
                'secretText' => request('secret'),
                'created_at' => $createdAt,
                'expires_at' => $expiresAt,
                'remainingViews' => request('remainingViews'),
            ]);
            return $this->getResponse(request(), $newSecret, 200);
        } catch (Throwable $e) {
            return $this->getBadResponse(request(), 'Invalid input', 405);
        }
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
    public function getSecret($secretString)
    {
        $foundSecret = Secret::findSecret($secretString);
        $result = $foundSecret->secretCheck($foundSecret);
    	if (isset($result)) {
    		$foundSecret->decreaseViewCounter();
    		return $this->getResponse(request(), $foundSecret, 200);
    	}
        else{
            return $this->getBadResponse(request(), 'Secret not found', 404);
        }
    }

    public function getResponse($request, $secret, $status)
    {
    	$response;
        if($request->header('accept') && $request->header('accept') == 'application/json'){
            $response = response(new SecretResource($secret), $status);
        }
        else if($request->header('accept') && $request->header('accept') == 'application/xml'){
            $response = response()->xml($secret);
        }
        else{
            $response = new SecretResource($secret);
        }
		return $response;
    }

    public function getBadResponse($request, $message, $status)
    {
    	$response;
        if($request->header('accept') && $request->header('accept') == 'application/json'){
            $response = response(new SecretResource(['error' => $message]), $status);
        }
        else if($request->header('accept') && $request->header('accept') == 'application/xml'){
            $response = response()->xml(['error' => $message]);
        }
        else{
            $response = new SecretResource(['error' => $message]);
        }
		return $response;
    }
}
