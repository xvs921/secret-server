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

    public static function write($secretObject)
    {
        return "secret: ".$secretObject->secretText."\nhash: ".$secretObject->hash."\n".
        "created_at: ".$secretObject->created_at."\nexpires_at: ".$secretObject->expires_at."\n".
        "remainingViews: ".$secretObject->remainingViews."\n";
    }

    public static function secretCheck($secretObject)
    {
        return $secretObject->secretText;
    }

    public static function findSecret($hash)
    {
        $dateNow = new DateTime();

        return Secret::where('hash', $hash)
        ->where('remainingViews', '>', 0)        
        ->where('expires_at', '>', $dateNow)
        ->first();
    }

    public function decreaseViewCounter()
    {
        if ($this->remainingViews > 0) {
            $this->remainingViews--;
            $this->save();
        }
    }
}
