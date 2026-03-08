@php
    $record = $getRecord();
    $photoPath = $record?->profile_photo_path;
    $photoUrl = $photoPath ? Storage::disk('public')->url($photoPath) : null;
    $name = $record?->name ?? '';
    $initials = collect(explode(' ', $name))->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))->take(2)->join('');
@endphp

<div
    x-data="{ uploading: false }"
    style="display: flex; align-items: center; gap: 1.5rem;"
>
    {{-- Avatar --}}
    <div style="position: relative; flex-shrink: 0;">
        @if($photoUrl)
            <img
                src="{{ $photoUrl }}"
                alt="{{ $name }}"
                style="width: 96px; height: 96px; border-radius: 9999px; object-fit: cover; border: 2px solid #e5e7eb;"
            >
        @else
            <div style="width: 96px; height: 96px; border-radius: 9999px; background: #2563eb; display: flex; align-items: center; justify-content: center; border: 2px solid #e5e7eb;">
                <span style="font-size: 1.5rem; font-weight: 700; color: #fff;">{{ $initials }}</span>
            </div>
        @endif

        {{-- Loading overlay --}}
        <div
            x-show="uploading"
            x-cloak
            style="position: absolute; inset: 0; border-radius: 9999px; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center;"
        >
            <svg style="width: 2rem; height: 2rem; color: #fff; animation: spin 1s linear infinite;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle style="opacity: 0.25;" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path style="opacity: 0.75;" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
        </div>
    </div>

    {{-- Buttons --}}
    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
        <button
            type="button"
            x-on:click="$refs.photoInput.click()"
            x-bind:disabled="uploading"
            style="display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.375rem 0.75rem; font-size: 0.875rem; font-weight: 500; color: #fff; background: #2563eb; border: none; border-radius: 0.5rem; cursor: pointer;"
            onmouseover="this.style.background='#1d4ed8'"
            onmouseout="this.style.background='#2563eb'"
        >
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" style="width: 1rem; height: 1rem;">
                <path fill-rule="evenodd" d="M1 8a2 2 0 0 1 2-2h.93a2 2 0 0 0 1.664-.89l.812-1.22A2 2 0 0 1 8.07 3h3.86a2 2 0 0 1 1.664.89l.812 1.22A2 2 0 0 0 16.07 6H17a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8Zm9 6a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" clip-rule="evenodd" />
            </svg>
            Ubah Foto
        </button>

        @if($photoUrl)
            <button
                type="button"
                x-bind:disabled="uploading"
                x-on:click="if (confirm('Hapus foto profil?')) { $wire.call('deleteProfilePhoto') }"
                style="display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.375rem 0.75rem; font-size: 0.875rem; font-weight: 500; color: #fff; background: #dc2626; border: none; border-radius: 0.5rem; cursor: pointer;"
                onmouseover="this.style.background='#b91c1c'"
                onmouseout="this.style.background='#dc2626'"
            >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" style="width: 1rem; height: 1rem;">
                    <path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 0 0 6 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 1 0 .23 1.482l.149-.022.841 10.518A2.75 2.75 0 0 0 7.596 19h4.807a2.75 2.75 0 0 0 2.742-2.53l.841-10.52.149.023a.75.75 0 0 0 .23-1.482A41.03 41.03 0 0 0 14 4.193V3.75A2.75 2.75 0 0 0 11.25 1h-2.5ZM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4ZM8.58 7.72a.75.75 0 0 1 .7.8l-.5 5.5a.75.75 0 0 1-1.496-.136l.5-5.5a.75.75 0 0 1 .796-.664Zm2.84 0a.75.75 0 0 1 .796.664l.5 5.5a.75.75 0 1 1-1.496.136l-.5-5.5a.75.75 0 0 1 .7-.8Z" clip-rule="evenodd" />
                </svg>
                Hapus Foto
            </button>
        @endif

        <span style="font-size: 0.75rem; color: #6b7280;">JPG, PNG, maks 2MB</span>
    </div>

    {{-- Hidden file input --}}
    <input
        type="file"
        x-ref="photoInput"
        accept="image/*"
        style="display: none;"
        x-on:change="
            if ($refs.photoInput.files.length) {
                const file = $refs.photoInput.files[0];
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file maksimal 2MB');
                    $refs.photoInput.value = '';
                    return;
                }
                uploading = true;
                $wire.upload(
                    'profilePhotoUpload',
                    file,
                    () => { uploading = false; $wire.call('saveProfilePhoto'); },
                    () => { uploading = false; alert('Gagal mengupload foto'); },
                    (event) => {}
                );
            }
        "
    >

    <style>
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</div>
