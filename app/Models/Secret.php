<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTime;
use DateInterval;

class Secret extends Model
{
    use HasFactory;

    public $table = 'secrets';

    protected $fillable = [
		'hash',
		'secretText',
		'created_at',
		'expires_at',
		'remainingViews',
	];

    /**
     * Write a SecretObject as String
     *
     * @param \App\Models\Secret $secretObject
     * @return String;
     */
    public static function write($secretObject)
    {
        return "secret: ".$secretObject->secretText." hash: ".$secretObject->hash." ".
        "created_at: ".$secretObject->created_at." expires_at: ".$secretObject->expires_at." ".
        "remainingViews: ".$secretObject->remainingViews;
    }

    /**
     * Check is available?
     *
     * @param \App\Models\Secret $secretObject
     * @return String;
     */
    public static function secretCheck($secretObject)
    {
        return $secretObject->secretText;
    }

    /**
     * Find a SecretObject with param hash
     *
     * @param String $hash
     * @return \App\Models\Secret;
     */
    public static function findSecret($hash)
    {
        $dateNow = new DateTime();

        return Secret::where('hash', $hash)
        ->where('remainingViews', '>', 0)        
        ->where('expires_at', '>', $dateNow)
        ->first();
    }

    /**
     * Decrease remaining counter
     *
     */
    public function decreaseViewCounter()
    {
        if ($this->remainingViews > 0) {
            $this->remainingViews--;
            $this->save();
        }
    }
}
