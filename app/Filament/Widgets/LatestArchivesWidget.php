<?php

namespace App\Filament\Widgets;

use App\Models\Archive;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestArchivesWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    // Disable default 5s polling
    protected ?string $pollingInterval = null;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Arsip Terbaru';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Archive::query()
                    ->with(['organizationUnit.parent.parent', 'category', 'user'])
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('document_name')
                    ->label('Nama Dokumen')
                    ->limit(50),
                Tables\Columns\TextColumn::make('document_number')
                    ->label('No. Surat'),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge(),
                Tables\Columns\TextColumn::make('organizationUnit.full_path')
                    ->label('Unit Organisasi')
                    ->wrap(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pengunggah'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Unggah')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->paginated(false);
    }
}
