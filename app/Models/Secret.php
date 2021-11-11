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
		'createdAt',
		'expiresAt',
		'remainingViews',
	];

    public string $hash;
    public string $secretText;
    public DateTime $createdAt;
    public DateTime $expiresAt;
    public int $remainingViews = 3;
    private string $hashMethod = 'sha256';
    public int $expireDays = 1;

    function __construct($paramSecretText, $expireDays) {
        $this->secretText = $paramSecretText;
        $this->hash = hash('sha256', $paramSecretText);
        $this->createdAt = new DateTime();
        //$this->expiresAt = $this->createdAt->add(new DateInterval('P'.$expireDays.'D'));
    }
}
