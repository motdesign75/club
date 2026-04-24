<?php

namespace App\Services;

use App\Models\Member;
use App\Models\Tenant;
use Carbon\Carbon;

class TemplateParser
{
    public static function parse(string $text, Member $member): string
    {
        $tenant = Tenant::find($member->tenant_id);

        $vars = [

            '{vorname}' => $member->first_name ?? '',
            '{nachname}' => $member->last_name ?? '',
            '{email}' => $member->email ?? '',
            '{mitgliedsnummer}' => $member->member_id ?? '',

            '{strasse}' => $member->street ?? '',
            '{plz}' => $member->zip ?? '',
            '{ort}' => $member->city ?? '',
            '{land}' => $member->country ?? '',

            '{verein}' => $tenant->name ?? '',

            '{heute}' => Carbon::now()->format('d.m.Y'),

        ];

        return str_replace(
            array_keys($vars),
            array_values($vars),
            $text
        );
    }
}
