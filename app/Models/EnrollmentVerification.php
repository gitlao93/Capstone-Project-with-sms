<?php

namespace App\Models;

use Eloquent;

class EnrollmentVerification extends Eloquent
{
    protected $fillable = ['mobile_number', 'code'];
}
