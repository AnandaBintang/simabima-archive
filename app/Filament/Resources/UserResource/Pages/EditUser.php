<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    public $profilePhotoUpload;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function saveProfilePhoto(): void
    {
        if (! $this->profilePhotoUpload) {
            return;
        }

        /** @var TemporaryUploadedFile $file */
        $file = $this->profilePhotoUpload;
        $record = $this->getRecord();

        // Delete old photo
        if ($record->profile_photo_path) {
            Storage::disk('public')->delete($record->profile_photo_path);
        }

        $path = $file->storeAs(
            'profile-photos',
            Str::ulid() . '.' . $file->getClientOriginalExtension(),
            'public'
        );

        $record->update(['profile_photo_path' => $path]);
        $this->profilePhotoUpload = null;

        $this->redirect($this->getResource()::getUrl('edit', ['record' => $record]));
    }

    public function deleteProfilePhoto(): void
    {
        $record = $this->getRecord();

        if ($record->profile_photo_path) {
            Storage::disk('public')->delete($record->profile_photo_path);
            $record->update(['profile_photo_path' => null]);
        }

        $this->redirect($this->getResource()::getUrl('edit', ['record' => $record]));
    }
}
