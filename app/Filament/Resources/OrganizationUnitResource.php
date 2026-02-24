<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrganizationUnitResource\Pages;
use App\Models\OrganizationUnit;
use BackedEnum;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class OrganizationUnitResource extends Resource
{
    protected static ?string $model = OrganizationUnit::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-building-office-2';

    protected static string|UnitEnum|null $navigationGroup = 'Master Data';

    protected static ?string $navigationLabel = 'Unit Organisasi';

    protected static ?string $modelLabel = 'Unit Organisasi';

    protected static ?string $pluralModelLabel = 'Unit Organisasi';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Section::make('Informasi Unit')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Unit')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('type')
                            ->label('Tipe')
                            ->options([
                                'bidang' => 'Bidang',
                                'upt' => 'UPT',
                                'unit' => 'Unit / Sub Bagian',
                            ])
                            ->required(),
                        Forms\Components\Select::make('parent_id')
                            ->label('Induk')
                            ->relationship('parent', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'bidang' => 'success',
                        'upt' => 'warning',
                        'unit' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'bidang' => 'Bidang',
                        'upt' => 'UPT',
                        'unit' => 'Unit / Sub Bag',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('parent.name')
                    ->label('Induk')
                    ->sortable()
                    ->default('-'),
                Tables\Columns\TextColumn::make('children_count')
                    ->label('Sub Unit')
                    ->counts('children')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Tipe')
                    ->options([
                        'bidang' => 'Bidang',
                        'upt' => 'UPT',
                        'unit' => 'Unit / Sub Bagian',
                    ]),
                Tables\Filters\SelectFilter::make('parent_id')
                    ->label('Induk')
                    ->relationship('parent', 'name'),
            ])
            ->actions([
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
            'index' => Pages\ListOrganizationUnits::route('/'),
            'create' => Pages\CreateOrganizationUnit::route('/create'),
            'edit' => Pages\EditOrganizationUnit::route('/{record}/edit'),
        ];
    }
}
