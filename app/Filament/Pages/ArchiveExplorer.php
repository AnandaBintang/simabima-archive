<?php

namespace App\Filament\Pages;

use App\Models\OrganizationUnit;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;

class ArchiveExplorer extends Page
{
    protected string $view = 'filament.pages.archive-explorer';

    protected static ?string $title = 'Eksplorasi Arsip';

    // ── State ─────────────────────────────────────────────────────────────
    /** Active group (Sekretariat / Bidang / UPT) — synced with ?group= URL param */
    #[Url(as: 'group')]
    public ?int $activeGroupId = null;

    /** ID of the selected level-1 card (unit) */
    public ?int $activeUnitId = null;

    /** ID of the selected level-2 card (sub_unit) — UPT only */
    public ?int $activeSubUnitId = null;

    public function mount(): void
    {
        // If no group in URL, default to first group
        if (! $this->activeGroupId) {
            $this->activeGroupId = OrganizationUnit::groups()->value('id');
        }
    }

    /** Reset child selections whenever the group changes (URL navigation). */
    public function updatedActiveGroupId(): void
    {
        $this->activeUnitId    = null;
        $this->activeSubUnitId = null;
    }

    // ── Filament Navigation ───────────────────────────────────────────────

    /**
     * Register one navigation item per group so Filament sidebar shows
     * a collapsible "Eksplorasi Arsip" dropdown with Sekretariat / Bidang / UPT.
     */
    public static function getNavigationItems(): array
    {
        $groups = Cache::remember('org_unit_groups_nav', 3600, fn () => OrganizationUnit::groups()->get());

        return $groups
            ->map(fn (OrganizationUnit $group) =>
                NavigationItem::make($group->name)
                    ->url(static::getUrl(['group' => $group->id]))
                    ->isActiveWhen(
                        fn () => request()->query('group') == (string) $group->id
                            && request()->is('*/archive-explorer*')
                    )
                    ->group('Eksplorasi Arsip')
                    ->sort($group->order)
                    ->icon('heroicon-o-folder-open')
            )
            ->all();
    }

    // ── Computed Properties ───────────────────────────────────────────────

    /** Memoized per-request via Livewire v3 #[Computed] — avoids re-query on multi-access */
    #[Computed]
    public function activeGroup(): ?OrganizationUnit
    {
        if (! $this->activeGroupId) {
            return null;
        }

        $group = OrganizationUnit::with([
            'children' => fn ($q) => $q
                ->withCount('archives')
                ->with([
                    'children' => fn ($q2) => $q2->withCount('archives'),
                ]),
        ])->find($this->activeGroupId);

        if (! $group) {
            return null;
        }

        // UPT staff: filter children to only show their own UPT branch
        $user = Auth::user();
        if ($user && $user->isStaff() && $user->organization_unit_id) {
            // Check if this group is the "UPT" group
            if (strtolower($group->name) === 'upt') {
                // Find which direct child of this group the user belongs to
                $userUnit = $user->organizationUnit;
                $uptBranch = $userUnit;
                while ($uptBranch && $uptBranch->parent_id && $uptBranch->parent_id !== $group->id) {
                    $uptBranch = $uptBranch->parent;
                }

                if ($uptBranch && $uptBranch->parent_id === $group->id) {
                    // Filter children to only the user's UPT branch
                    $group->setRelation(
                        'children',
                        $group->children->filter(fn ($child) => $child->id === $uptBranch->id)->values()
                    );
                }
            }
        }

        return $group;
    }

    #[Computed]
    public function activeUnit(): ?OrganizationUnit
    {
        if (! $this->activeUnitId) {
            return null;
        }

        return OrganizationUnit::with([
            'children' => fn ($q) => $q->withCount('archives'),
        ])->find($this->activeUnitId);
    }

    #[Computed]
    public function activeSubUnit(): ?OrganizationUnit
    {
        return $this->activeSubUnitId
            ? OrganizationUnit::find($this->activeSubUnitId)
            : null;
    }

    /**
     * Returns the "leaf" unit whose archives should be shown in the table.
     * - If a sub_unit is selected → use it.
     * - If a unit with NO children is selected → use it.
     * - Otherwise → null (don't show table yet).
     */
    #[Computed]
    public function leafUnit(): ?OrganizationUnit
    {
        if ($this->activeSubUnitId) {
            return $this->activeSubUnit;
        }

        if ($this->activeUnit && $this->activeUnit->children->isEmpty()) {
            return $this->activeUnit;
        }

        return null;
    }

    // ── Actions ───────────────────────────────────────────────────────────

    public function selectUnit(int $id): void
    {
        $this->activeUnitId    = $id;
        $this->activeSubUnitId = null;
    }

    public function selectSubUnit(int $id): void
    {
        $this->activeSubUnitId = $id;
    }
}
