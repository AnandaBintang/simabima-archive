<x-filament-panels::page>
    <style>
        /* ── Main area ───────────────────────────────────────────────── */
        .ae-main  { display: flex; flex-direction: column; gap: 1.5rem; }

        /* ── Section label ───────────────────────────────────────────── */
        .ae-section-label {
            font-size: .75rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase;
            color: rgb(var(--gray-500,107 114 128)); margin-bottom: .75rem;
        }

        /* ── Card grids ──────────────────────────────────────────────── */
        .ae-grid-l1 { display: grid; grid-template-columns: repeat(2,1fr); gap: 1rem; }
        .ae-grid-l2 { display: grid; grid-template-columns: repeat(2,1fr); gap: .75rem; }
        @media(min-width:768px)  { .ae-grid-l1 { grid-template-columns: repeat(3,1fr); } }
        @media(min-width:1024px) { .ae-grid-l1 { grid-template-columns: repeat(4,1fr); } }
        @media(min-width:768px)  { .ae-grid-l2 { grid-template-columns: repeat(3,1fr); } }

        /* ── Cards ───────────────────────────────────────────────────── */
        .ae-card {
            display: flex; align-items: center; gap: .875rem; padding: 1rem 1.125rem;
            border-radius: .75rem; border: 2px solid transparent; cursor: pointer;
            background: #fff; border-color: rgb(var(--gray-200,229 231 235));
            transition: transform .18s, box-shadow .18s, border-color .18s;
            box-shadow: 0 1px 3px rgba(0,0,0,.08);
        }
        .dark .ae-card { background: rgba(255,255,255,.05); border-color: rgba(255,255,255,.1); }

        .ae-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,.1);
            border-color: rgb(var(--primary-400,96 165 250));
        }
        .ae-card.active {
            border-color: rgb(var(--primary-500,59 130 246));
            background: rgba(var(--primary-500,59 130 246),.06);
        }
        .dark .ae-card.active { background: rgba(var(--primary-500,59 130 246),.15); }

        .ae-card-icon {
            display: flex; align-items: center; justify-content: center;
            width: 2.75rem; height: 2.75rem; border-radius: .625rem; flex-shrink: 0;
        }
        .ae-card-icon svg { width: 1.5rem; height: 1.5rem; }
        .icon-blue   { background: rgba(var(--primary-500,59 130 246),.12); color: rgb(var(--primary-600,37 99 235)); }
        .dark .icon-blue { background: rgba(var(--primary-500,59 130 246),.22); color: rgb(var(--primary-400,96 165 250)); }
        .icon-yellow { background: rgba(234,179,8,.12); color: rgb(161,98,7); }
        .dark .icon-yellow { background: rgba(234,179,8,.2); color: rgb(250 204 21); }

        .ae-card-name  { font-weight: 600; font-size: .875rem; line-height: 1.3; }
        .ae-card-meta  { font-size: .75rem; opacity: .55; margin-top: .15rem; }

        /* ── Loading states ──────────────────────────────────────── */
        .ae-card { position: relative; overflow: hidden; }
        .ae-card.ae-card-busy {
            opacity: .6;
            cursor: wait;
            pointer-events: none;
        }
        /* Spinning overlay on the specific card being loaded */
        .ae-card-spinner {
            display: none;
            position: absolute; inset: 0;
            align-items: center; justify-content: center;
            background: rgba(255,255,255,.55);
            border-radius: .625rem;
            z-index: 1;
        }
        .ae-card-spinner svg {
            width: 1.25rem; height: 1.25rem;
            animation: ae-spin .7s linear infinite;
            color: rgb(var(--primary-600, 37 99 235));
        }
        @keyframes ae-spin { to { transform: rotate(360deg); } }
        /* Show spinner while that specific wire target is loading */
        .ae-card-spinner[data-show] { display: flex; }
        /* Dim the content section while sub-data is fetching */
        .ae-section-loading {
            opacity: .45;
            pointer-events: none;
            transition: opacity .15s;
        }
        /* Skeleton pulse for table area */
        .ae-skeleton {
            display: none;
            flex-direction: column;
            gap: .6rem;
            padding: .5rem 0;
        }
        .ae-skeleton.ae-skeleton-show { display: flex; }
        .ae-skeleton-row {
            height: 2.5rem;
            border-radius: .5rem;
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: ae-shimmer 1.2s infinite;
        }
        @keyframes ae-shimmer { to { background-position: -200% 0; } }

        /* ── Empty state ─────────────────────────────────────────────── */
        .ae-empty {
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            height: 100%; min-height: 14rem; opacity: .45; gap: .5rem; text-align: center;
        }
        .ae-empty svg { width: 3rem; height: 3rem; }

        /* ── Archive table ───────────────────────────────────────────── */
        .ae-table-wrap {
            overflow-x: auto; border-radius: 1rem;
            box-shadow: 0 4px 24px rgba(42,56,144,.10), 0 1px 4px rgba(0,0,0,.06);
            border: 1.5px solid rgba(42,56,144,.13);
        }
        .dark .ae-table-wrap {
            border-color: rgba(255,255,255,.10);
            box-shadow: 0 4px 24px rgba(0,0,0,.35);
        }
        .ae-table { width: 100%; border-collapse: collapse; font-size: .875rem; }

        /* Header */
        .ae-table thead { background: linear-gradient(90deg, #2A3890 0%, #3d50b7 100%); }
        .dark .ae-table thead { background: linear-gradient(90deg, #1e2a6e 0%, #2A3890 100%); }
        .ae-table th {
            padding: .85rem 1rem; text-align: left; font-size: .72rem; font-weight: 700;
            letter-spacing: .07em; text-transform: uppercase; color: rgba(255,255,255,.88);
            border: none; white-space: nowrap;
        }
        .ae-table th:first-child { border-radius: 1rem 0 0 0; padding-left: 1.25rem; }
        .ae-table th:last-child  { border-radius: 0 1rem 0 0; }

        /* Rows */
        .ae-table tbody tr { border-bottom: 1px solid rgb(var(--gray-100,243 244 246)); transition: background .15s; }
        .dark .ae-table tbody tr { border-bottom-color: rgba(255,255,255,.06); }
        .ae-table tbody tr:last-child { border-bottom: none; }
        .ae-table tbody tr:nth-child(even) { background: rgba(42,56,144,.03); }
        .dark .ae-table tbody tr:nth-child(even) { background: rgba(255,255,255,.025); }
        .ae-table tbody tr:hover { background: rgba(205,171,47,.10) !important; }
        .dark .ae-table tbody tr:hover { background: rgba(205,171,47,.07) !important; }
        .ae-table td { padding: .8rem 1rem; }
        .ae-table td:first-child { padding-left: 1.25rem; }

        /* Row number */
        .ae-td-num { font-size: .75rem; color: rgba(var(--gray-400,156 163 175),1); font-weight: 600; text-align: center; min-width: 2rem; }

        /* Document number badge */
        .ae-docnum {
            display: inline-block; font-family: 'Courier New', monospace; font-size: .78rem;
            font-weight: 600; background: rgba(42,56,144,.08); color: #2A3890;
            border: 1px solid rgba(42,56,144,.18); border-radius: .4rem; padding: .15rem .55rem;
        }
        .dark .ae-docnum { background: rgba(255,255,255,.08); color: rgba(255,255,255,.72); border-color: rgba(255,255,255,.14); }

        /* Document name */
        .ae-docname { font-weight: 600; color: rgb(var(--gray-900,17 24 39)); line-height: 1.45; }
        .dark .ae-docname { color: rgba(255,255,255,.88); }

        /* Category pill */
        .ae-cat-pill {
            display: inline-block; font-size: .695rem; font-weight: 600; letter-spacing: .03em;
            padding: .25rem .7rem; border-radius: 9999px;
            background: rgba(205,171,47,.14); color: #6b5200;
            border: 1px solid rgba(205,171,47,.35); white-space: nowrap;
        }
        .dark .ae-cat-pill { background: rgba(205,171,47,.18); color: #f5d060; border-color: rgba(205,171,47,.28); }

        /* Date cell */
        .ae-date { display: inline-flex; align-items: center; gap: .3rem; font-size: .8rem; color: rgb(var(--gray-500,107 114 128)); white-space: nowrap; }
        .dark .ae-date { color: rgba(255,255,255,.48); }
        .ae-date svg { width: .8rem; height: .8rem; flex-shrink: 0; opacity: .7; }

        /* Download button */
        .ae-dl-btn {
            display: inline-flex; align-items: center; gap: .35rem; font-size: .78rem;
            font-weight: 600; color: #fff !important;
            background: linear-gradient(135deg, #2A3890 0%, #3d50b7 100%);
            border-radius: .5rem; padding: .3rem .8rem; text-decoration: none !important;
            transition: opacity .15s, transform .1s; white-space: nowrap;
            box-shadow: 0 1px 4px rgba(42,56,144,.3);
        }
        .ae-dl-btn:hover { opacity: .82; transform: translateY(-1px); }
        .ae-dl-btn svg { width: .82rem; height: .82rem; flex-shrink: 0; }
        .ae-dl-none { font-size: .82rem; color: rgba(var(--gray-400,156 163 175),1); }

        /* Archive count badge on section label */
        .ae-count-badge {
            display: inline-flex; align-items: center; justify-content: center;
            min-width: 1.6rem; height: 1.5rem; padding: 0 .55rem; border-radius: 9999px;
            font-size: .72rem; font-weight: 700; background: rgba(42,56,144,.10);
            color: #2A3890; margin-left: .45rem; vertical-align: middle;
        }
        .dark .ae-count-badge { background: rgba(255,255,255,.12); color: rgba(255,255,255,.75); }

        .ae-badge {
            display: inline-block; padding: .2rem .6rem; border-radius: 9999px;
            font-size: .7rem; font-weight: 600; letter-spacing: .03em;
        }
        .badge-active   { background: rgba(34,197,94,.12); color: rgb(21,128,61); }
        .badge-inactive { background: rgba(239,68,68,.12); color: rgb(185,28,28); }
        .dark .badge-active   { background: rgba(34,197,94,.2);  color: rgb(74,222,128); }
        .dark .badge-inactive { background: rgba(239,68,68,.2);  color: rgb(252,165,165); }
    </style>

    <div class="ae-main">

            @if ($this->activeGroup)

                {{-- ── LEVEL-1 CARDS ─────────────────────────────────── --}}
                <div>
                    <p class="ae-section-label">{{ $this->activeGroup->name }}</p>
                    <div class="ae-grid-l1">
                        @foreach ($this->activeGroup->children as $unit)
                            <button
                                wire:click="selectUnit({{ $unit->id }})"
                                wire:loading.class="ae-card-busy"
                                wire:target="selectUnit({{ $unit->id }})"
                                wire:loading.attr="disabled"
                                class="ae-card {{ $activeUnitId === $unit->id ? 'active' : '' }}"
                            >
                                {{-- Spinner overlay (only on this card while its target loads) --}}
                                <span
                                    class="ae-card-spinner"
                                    wire:loading.attr="data-show"
                                    wire:target="selectUnit({{ $unit->id }})"
                                >
                                    <x-heroicon-o-arrow-path />
                                </span>
                                <span class="ae-card-icon icon-blue">
                                    <x-heroicon-o-folder-open />
                                </span>
                                <span>
                                    <span class="ae-card-name">{{ $unit->name }}</span>
                                    <span class="ae-card-meta">
                                        @if ($unit->children->isNotEmpty())
                                            {{ $unit->children->count() }} sub bagian
                                        @else
                                            {{ $unit->archives_count }} arsip
                                        @endif
                                    </span>
                                </span>
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- ── LEVEL-2 CARDS (sub_unit — UPT branches) ──────── --}}
                @if ($this->activeUnit && $this->activeUnit->children->isNotEmpty())
                    <div>
                        <p class="ae-section-label">Sub Bagian — {{ $this->activeUnit->name }}</p>
                        <div class="ae-grid-l2">
                            @foreach ($this->activeUnit->children as $sub)
                                <button
                                    wire:click="selectSubUnit({{ $sub->id }})"
                                    wire:loading.class="ae-card-busy"
                                    wire:target="selectSubUnit({{ $sub->id }})"
                                    wire:loading.attr="disabled"
                                    class="ae-card {{ $activeSubUnitId === $sub->id ? 'active' : '' }}"
                                >
                                    <span
                                        class="ae-card-spinner"
                                        wire:loading.attr="data-show"
                                        wire:target="selectSubUnit({{ $sub->id }})"
                                    >
                                        <x-heroicon-o-arrow-path />
                                    </span>
                                    <span class="ae-card-icon icon-yellow">
                                        <x-heroicon-o-folder />
                                    </span>
                                    <span>
                                        <span class="ae-card-name">{{ $sub->name }}</span>
                                        <span class="ae-card-meta">{{ $sub->archives_count }} arsip</span>
                                    </span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- ── ARCHIVE TABLE ─────────────────────────────────── --}}
                @if ($this->leafUnit)
                    @php $archives = $this->leafUnit->archives()->with(['category', 'user'])->latest()->get(); @endphp

                    {{-- Skeleton shown while table data is loading --}}
                    <div
                        class="ae-skeleton"
                        wire:loading.class="ae-skeleton-show"
                        wire:target="selectUnit,selectSubUnit"
                    >
                        <div class="ae-skeleton-row" style="width:40%; height:1rem;"></div>
                        <div class="ae-skeleton-row"></div>
                        <div class="ae-skeleton-row"></div>
                        <div class="ae-skeleton-row"></div>
                    </div>

                    <div
                        wire:loading.class="ae-section-loading"
                        wire:target="selectUnit,selectSubUnit"
                    >
                        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:.75rem; flex-wrap:wrap; gap:.5rem;">
                            <p class="ae-section-label" style="margin-bottom:0">
                                File Arsip — {{ $this->leafUnit->name }}
                                <span class="ae-count-badge">{{ $archives->count() }}</span>
                            </p>
                            <a
                                href="{{ \App\Filament\Resources\ArchiveResource::getUrl('index') }}?filters[organization_unit_id][value]={{ $this->leafUnit->id }}"
                                style="font-size:.8rem; color:rgb(var(--primary-600,37 99 235)); text-decoration:none; font-weight:600; white-space:nowrap;"
                            >
                                Lihat semua →
                            </a>
                        </div>

                        @if ($archives->isEmpty())
                            <div class="ae-empty">
                                <x-heroicon-o-inbox />
                                <span>Belum ada arsip di unit ini.</span>
                            </div>
                        @else
                            <div class="ae-table-wrap">
                                <table class="ae-table">
                                    <thead>
                                        <tr>
                                            <th style="width:3rem;">#</th>
                                            <th>No. Dokumen</th>
                                            <th>Nama Dokumen</th>
                                            <th>Kategori</th>
                                            <th>Tanggal</th>
                                            <th style="width:6rem;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($archives as $i => $archive)
                                            <tr>
                                                <td class="ae-td-num">{{ $i + 1 }}</td>
                                                <td>
                                                    <span class="ae-docnum">
                                                        {{ $archive->document_number ?? '—' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="ae-docname">{{ $archive->document_name }}</span>
                                                </td>
                                                <td>
                                                    @if ($archive->category?->name)
                                                        <span class="ae-cat-pill">{{ $archive->category->name }}</span>
                                                    @else
                                                        <span class="ae-dl-none">—</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="ae-date">
                                                        <x-heroicon-o-calendar-days />
                                                        {{ $archive->document_date?->format('d M Y') ?? '—' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if ($archive->file_path)
                                                        <a
                                                            href="{{ \Storage::url($archive->file_path) }}"
                                                            target="_blank"
                                                            class="ae-dl-btn"
                                                        >
                                                            <x-heroicon-o-arrow-down-tray />
                                                            Unduh
                                                        </a>
                                                    @else
                                                        <span class="ae-dl-none">—</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                @endif

            @else
                <div class="ae-empty">
                    <x-heroicon-o-folder-open />
                    <span>Pilih grup di sebelah kiri untuk mulai menjelajah arsip.</span>
                </div>
            @endif

    </div>
</x-filament-panels::page>
