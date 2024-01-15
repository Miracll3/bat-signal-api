<?php

namespace App\Actions\WayneEnterpriseAPI;

class GetUrl
{
    public static function handle()
    {
        $baseUrl = 'https://wayne.fusebox-staging.co.za/api/v1/';
        return $baseUrl;
    }
}