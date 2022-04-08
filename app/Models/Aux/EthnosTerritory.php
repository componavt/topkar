<?php

namespace App\Models\Aux;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EthnosTerritory extends Model
{
    use HasFactory;
    use \App\Traits\Methods\getNameAttribute;
}
