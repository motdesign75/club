<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\CustomMemberField;
use App\Models\Tag;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Requests\StoreMemberRequest;
use App\Http\Requests\UpdateMemberRequest;
use App\Services\MemberService;
use App\Services\MembershipService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function __construct(
        protected MemberService $memberService
    ) {}

    public function index()
    {
        $tenantId = app('currentTenant')->id;

        $query = Member::with('tags')->where('tenant_id', $tenantId);

        if (request()->filled('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('member_id', 'like', "%{$search}%");
            });
        }

        if (request()->filled('tag')) {
            $tagId = request('tag');
            $query->whereHas('tags', fn($q) => $q->where('tags.id', $tagId));
        }

        $sortField = request('sort', 'last_name');
        $sortDirection = request('direction', 'asc');
        $allowedFields = ['first_name', 'last_name', 'email', 'member_id', 'entry_date'];
        if (!in_array($sortField, $allowedFields)) {
            $sortField = 'last_name';
        }

        $allMembers = $query->orderBy($sortField, $sortDirection)->get();

        if (request()->filled('status')) {
            $status = request('status');
            $allMembers = $allMembers->filter(fn($m) => $m->status === $status)->values();
        }

        $perPage = 25;
        $currentPage = request()->get('page', 1);
        $members = new LengthAwarePaginator(
            $allMembers->forPage($currentPage, $perPage),
            $allMembers->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        $allTags = Tag::where('tenant_id', $tenantId)->orderBy('name')->get();

        return view('members.index', compact('members', 'sortField', 'sortDirection', 'allTags'));
    }

    public function create(MembershipService $membershipService)
    {
        $memberships = $membershipService->getForTenant();
        $allTags = Tag::where('tenant_id', app('currentTenant')->id)->orderBy('name')->get();

        return view('members.create', compact('memberships', 'allTags'));
    }

    public function store(StoreMemberRequest $request)
    {
        $member = $this->memberService->create($request);

        if ($request->filled('tags')) {
            $member->tags()->sync($request->input('tags'));
        }

        return redirect()->route('members.index')->with('success', 'Mitglied erfolgreich hinzugefügt.');
    }

    public function show(Member $member)
    {
        $this->authorizeMember($member);
        $member->load('customValues');

        $customFields = CustomMemberField::where('tenant_id', $member->tenant_id)
            ->where('visible', true)
            ->orderBy('order')
            ->get();

        return view('members.show', compact('member', 'customFields'));
    }

    public function edit(Member $member, MembershipService $membershipService)
    {
        $this->authorizeMember($member);
        $memberships = $membershipService->getForTenant();
        $member->load('customValues');

        $customFields = CustomMemberField::where('tenant_id', $member->tenant_id)
            ->where('visible', true)
            ->orderBy('order')
            ->get();

        $allTags = Tag::where('tenant_id', $member->tenant_id)->orderBy('name')->get();

        return view('members.edit', compact('member', 'memberships', 'customFields', 'allTags'));
    }

    public function update(UpdateMemberRequest $request, Member $member)
    {
        $this->authorizeMember($member);
        $this->memberService->update($request, $member);

        if ($request->has('tags')) {
            $member->tags()->sync($request->input('tags'));
        } else {
            $member->tags()->sync([]);
        }

        return redirect()->route('members.index')->with('success', 'Mitglied erfolgreich aktualisiert.');
    }

    public function destroy(Member $member)
    {
        $this->authorizeMember($member);

        if ($member->photo && Storage::disk('public')->exists($member->photo)) {
            Storage::disk('public')->delete($member->photo);
        }

        $member->delete();
        return redirect()->route('members.index')->with('success', 'Mitglied gelöscht.');
    }

    public function exportDatenauskunft(Member $member)
    {
        $this->authorizeMember($member);

        $pdf = Pdf::loadView('members.pdf.datenauskunft', ['member' => $member]);
        return $pdf->download("Datenauskunft_{$member->last_name}_{$member->id}.pdf");
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'selected' => 'required|array',
            'selected.*' => 'exists:members,id',
            'action' => 'required|string|in:set_status_aktiv,set_status_passiv,set_status_ehemalig,delete',
        ]);

        $members = Member::whereIn('id', $request->selected)
            ->where('tenant_id', auth()->user()->tenant_id)
            ->get();

        $count = 0;

        foreach ($members as $member) {
            switch ($request->action) {
                case 'set_status_aktiv':
                    $member->status = 'aktiv';
                    $member->save();
                    $count++;
                    break;

                case 'set_status_passiv':
                    $member->status = 'passiv';
                    $member->save();
                    $count++;
                    break;

                case 'set_status_ehemalig':
                    $member->status = 'ehemalig';
                    $member->save();
                    $count++;
                    break;

                case 'delete':
                    $member->delete();
                    $count++;
                    break;
            }
        }

        return redirect()->route('members.index')
            ->with('success', "{$count} Mitglied(er) wurden erfolgreich bearbeitet.");
    }

    private function authorizeMember(Member $member): void
    {
        abort_if($member->tenant_id !== app('currentTenant')->id, 403, 'Unberechtigter Zugriff.');
    }
}
