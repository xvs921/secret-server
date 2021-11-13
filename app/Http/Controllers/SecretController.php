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
        $createdAt->setTimezone(new DateTimeZone('Europe/Budapest'));
        $expiresAt = $createdAt->add(new DateInterval('PT'.$expire.'M'));
        $createdAt->setTimezone(new DateTimeZone('Europe/Budapest'));

        try {
            $newSecret = Secret::create([
                'hash' => hash('sha256', request('secret')),
                'secretText' => request('secret'),
                'created_at' => $createdAt,
                'expires_at' => $expiresAt,
                'remainingViews' => request('remainingViews'),
            ]);
        } catch (Throwable $e) {
            return $this->getBadResponse(request(), 'Invalid input', 405);
        }
        return $this->getResponse(request(), $this->getDataCollection($newSecret), 200);

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
        $foundSecret = Secret::findSecret($secret);

    	if ($foundSecret) {
    		$foundSecret->decreaseViewCounter();
    		return $this->getResponse(request(), $this->getDataCollection($foundSecret), 200);
    	}

        return $this->getBadResponse(request(), 'Secret not found', 404);
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

    public function getDataCollection($secretObject)
    {
        return [
            "hash" => '"'.$secretObject->hash.'"',
            "secretText" => '"'.$secretObject->secretText.'"',
            "createdAt" => '"'.$secretObject->created_at->format('Y-m-dTH:i:sZ').'"',
            "expiresAt" => '"'.$secretObject->expires_at->format('Y-m-dTH:i:sZ').'"',
            "remainingViews" => $secretObject->remainingViews
        ];
    }
}
