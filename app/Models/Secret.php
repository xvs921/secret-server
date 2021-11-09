<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Secret extends Model
{
    use HasFactory;

    public string $hash;
    public string $secretText;
    public DateTime $createdAt;
    public DateTime $expiresAt;
    public int $remainingViews = 3;
    private string $hashMethod = 'sha256';

    function __construct($paramSecretText) {
        $secretText = $paramSecretText;
        $hash = hash($hashMethod, $paramSecretText);
        $dtCreate = new DateTime();
        $createdAt = $dt->format('Y-m-d\TH:i:s.u');
        $dtExpire = $dt->add(new DateInterval('P10D'))->format('Y-m-d\TH:i:s.u');
    }
}
