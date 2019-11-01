<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SampleDdd\ApplicationService\ScreeningApplicationService;
use SampleDdd\Domain\Repository\ScreeningRepository;

class ScreeningController extends Controller
{
    //
    public function preInterview(Request $request, ScreeningRepository $screeningRepository)
    {
        $service = new ScreeningApplicationService($screeningRepository);
        $service->startFromPreInterview('valid@example.com');

        return view('welcome');
    }
}
