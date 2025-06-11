<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\AppConfig;

class HomeController extends Controller
{
    public function checkAppVersion()
    {
        try {
            $version = AppConfig::first();

            return ResponseFormatter::success($version, 'App version retrieved successfully');

        } catch (\Throwable $e) {
            return ResponseFormatter::error([
                'success' => false,
                'message' => 'Error retrieving app version',
                'error' => $e->getMessage(),
            ], 'Error retrieving app version', 500);

        }
    }

}
