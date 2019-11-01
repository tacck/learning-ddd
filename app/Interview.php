<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    protected $fillable = ['screening_id', 'screening_date', 'interview_number', 'screening_step_result', 'recruiter_id'];
}
