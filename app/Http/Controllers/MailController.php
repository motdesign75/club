<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Template;
use App\Models\Member;
use App\Services\TemplateParser;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class MailController extends Controller
{

    public function create()
    {
        $tenantId = auth()->user()->tenant_id;

        $templates = Template::where(
            'tenant_id',
            $tenantId
        )->orderBy('name')->get();


        $members = Member::where(
            'tenant_id',
            $tenantId
        )
        ->whereNull('exit_date')
        ->orderBy('last_name')
        ->get();


        return view(
            'mail.create',
            compact('templates', 'members')
        );
    }



    public function send(Request $request)
    {

        $request->validate([
            'template_id' => [
                'required',
                Rule::exists('templates', 'id')->where('tenant_id', auth()->user()->tenant_id),
            ],
            'members' => 'required|array',
            'members.*' => [
                'integer',
                Rule::exists('members', 'id')->where('tenant_id', auth()->user()->tenant_id),
            ],
        ]);


        $tenant = auth()->user()->tenant;


        $template = Template::where('tenant_id', $tenant->id)
            ->findOrFail($request->template_id);

        $fromAddress = $tenant->mail_from_address ?: config('mail.from.address');
        $fromName = $tenant->mail_from_name ?: ($tenant->name ?: config('mail.from.name'));
        $replyToAddress = $tenant->email && $tenant->email !== $fromAddress
            ? $tenant->email
            : null;


        foreach ($request->members as $memberId) {

            $member = Member::where('tenant_id', $tenant->id)
                ->find($memberId);

            if (!$member) continue;

            if (!$member->email) continue;


            $html = TemplateParser::parse(
                $template->body,
                $member
            );


            Mail::send(
                'mail.layout',
                [
                    'body' => $html,
                    'tenant' => $tenant
                ],
                function ($mail) use ($member, $template, $fromAddress, $fromName, $replyToAddress, $tenant) {

                    $mail->to($member->email)

                        ->subject(
                            $template->subject ?? 'Nachricht'
                        )

                        ->from(
                            $fromAddress,
                            $fromName
                        );

                    if ($replyToAddress) {
                        $mail->replyTo($replyToAddress, $tenant->name ?? $fromName);
                    }

                }
            );

        }


        return back()->with(
            'success',
            'Serienmail gesendet'
        );

    }

}
