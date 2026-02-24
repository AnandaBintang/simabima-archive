<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArchiveResource\Pages;
use App\Models\Archive;
use App\Models\OrganizationUnit;
use BackedEnum;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use UnitEnum;

class ArchiveResource extends Resource
{
    protected static ?string $model = Archive::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-archive-box';

    protected static string|UnitEnum|null $navigationGroup = 'Arsip';

    protected static ?string $navigationLabel = 'Arsip';

    protected static ?string $modelLabel = 'Arsip';

    protected static ?string $pluralModelLabel = 'Arsip';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Section::make('Informasi Dokumen')
                    ->schema([
                        Forms\Components\TextInput::make('document_name')
                            ->label('Nama Dokumen')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('document_number')
                            ->label('Nomor Surat')
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('document_date')
                            ->label('Tanggal Dokumen')
                            ->required()
                            ->default(now()),
                    ])->columns(2),

                Section::make('Klasifikasi')
                    ->schema([
                        Forms\Components\Select::make('organization_unit_id')
                            ->label('Unit Organisasi')
                            ->options(
                                OrganizationUnit::whereIn('type', ['unit', 'sub_unit'])
                                    ->with('parent.parent')
                                    ->orderBy('type')
                                    ->get()
                                    ->mapWithKeys(fn (OrganizationUnit $u) => [
                                        $u->id => $u->full_path,
                                    ])
                            )
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('archive_category_id')
                            ->label('Kategori Arsip')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                    ])->columns(2),

                Section::make('File Dokumen')
                    ->schema([
                        Forms\Components\FileUpload::make('file_path')
                            ->label('File')
                            ->directory('archives')
                            ->disk('public')
                            ->maxSize(51200) // 50 MB
                            ->required()
                            ->storeFileNamesIn('original_filename')
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Hidden::make('user_id')
                    ->default(fn () => Auth::id()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('document_name')
                    ->label('Nama Dokumen')
                    ->searchable()
                    ->sortable()
                    ->limit(40),
                Tables\Columns\TextColumn::make('document_number')
                    ->label('No. Surat')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('document_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('organizationUnit.full_path')
                    ->label('Unit Organisasi')
                    ->sortable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pengunggah')
                    ->sortable(),
                Tables\Columns\TextColumn::make('download_count')
                    ->label('Unduhan')
                    ->sortable()
                    ->alignCenter()
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Unggah')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('archive_category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name'),
                Tables\Filters\SelectFilter::make('organization_unit_id')
                    ->label('Bidang / UPT')
                    ->relationship('organizationUnit', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Actions\Action::make('download')
                    ->label('Download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(function (Archive $record) {
                        $record->increment('download_count');
                        return response()->download(
                            Storage::disk('public')->path($record->file_path),
                            $record->original_filename
                        );
                    }),
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArchives::route('/'),
            'create' => Pages\CreateArchive::route('/create'),
            'edit' => Pages\EditArchive::route('/{record}/edit'),
        ];
    }
}
