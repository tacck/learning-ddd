<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Screening extends Model
{
    protected $fillable = ['apply_date', 'status', 'applicant_email_address'];
}
