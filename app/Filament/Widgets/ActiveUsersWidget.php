<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class ActiveUsersWidget extends BaseWidget
{
    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Pengguna Aktif';

    protected ?string $pollingInterval = null;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                User::query()
                    ->withCount('archives as total_uploads')
                    ->withSum('archives', 'download_count')
                    ->having('total_uploads', '>', 0)
                    ->orHaving('archives_sum_download_count', '>', 0)
                    ->orderByDesc('archives_sum_download_count')
            )
            ->columns([
                Tables\Columns\TextColumn::make('rank')
                    ->label('#')
                    ->rowIndex()
                    ->alignCenter()
                    ->width('3rem'),
                Tables\Columns\ImageColumn::make('profile_photo_path')
                    ->label('Foto')
                    ->circular()
                    ->disk('public')
                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->name) . '&background=2A3890&color=fff')
                    ->width(32)
                    ->height(32),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('organizationUnit.name')
                    ->label('Unit Organisasi'),
                Tables\Columns\TextColumn::make('total_uploads')
                    ->label('Total Upload')
                    ->badge()
                    ->color('info')
                    ->alignCenter()
                    ->sortable(),
                Tables\Columns\TextColumn::make('archives_sum_download_count')
                    ->label('Total Download')
                    ->badge()
                    ->color('success')
                    ->alignCenter()
                    ->sortable()
                    ->default(0),
            ])
            ->paginated([5, 10, 25])
            ->defaultPaginationPageOption(5)
            ->emptyStateIcon('heroicon-o-users')
            ->emptyStateHeading('Belum ada aktivitas')
            ->emptyStateDescription('Pengguna yang mengunggah atau mengunduh arsip akan muncul di sini.');
    }
}
