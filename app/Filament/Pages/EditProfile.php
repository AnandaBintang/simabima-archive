<?php

namespace App\Filament\Pages;

use App\Models\OrganizationUnit;
use Filament\Auth\Pages\EditProfile as BaseEditProfile;
use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class EditProfile extends BaseEditProfile
{
    /**
     * Use the full panel layout (with sidebar + topbar).
     */
    public static function isSimple(): bool
    {
        return false;
    }

    /**
     * Extend the default form with all user profile fields.
     */
    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Akun')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('username')
                            ->label('Username')
                            ->required()
                            ->unique(table: 'users', column: 'username', ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(table: 'users', column: 'email', ignoreRecord: true)
                            ->maxLength(255),
                    ]),

                Section::make('Informasi Organisasi')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('organization_unit_id')
                            ->label('Bidang / UPT')
                            ->options(
                                OrganizationUnit::whereIn('type', ['unit', 'sub_unit'])
                                    ->with('parent.parent')
                                    ->get()
                                    ->mapWithKeys(fn ($u) => [$u->id => $u->full_path])
                            )
                            ->searchable()
                            ->nullable(),
                        Forms\Components\TextInput::make('jabatan')
                            ->label('Jabatan')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('unit_kerja')
                            ->label('Unit Kerja')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->label('Nomor Telepon')
                            ->tel()
                            ->maxLength(20),
                    ]),

                Section::make('Ubah Password')
                    ->description('Kosongkan jika tidak ingin mengubah password.')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('current_password')
                            ->label('Password Saat Ini')
                            ->password()
                            ->revealable()
                            ->currentPassword()
                            ->dehydrated(false)
                            ->nullable(),
                        Forms\Components\TextInput::make('password')
                            ->label('Password Baru')
                            ->password()
                            ->revealable()
                            ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->nullable()
                            ->confirmed(),
                        Forms\Components\TextInput::make('password_confirmation')
                            ->label('Konfirmasi Password Baru')
                            ->password()
                            ->revealable()
                            ->dehydrated(false)
                            ->nullable(),
                    ]),
            ]);
    }
}
