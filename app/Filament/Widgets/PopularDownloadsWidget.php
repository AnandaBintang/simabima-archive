<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ArchiveResource;
use App\Models\Archive;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;

class PopularDownloadsWidget extends BaseWidget
{
    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 'full';

    // TableWidget::$heading is static — must match
    protected static ?string $heading = 'Dokumen Paling Sering Diunduh';

    // Disable default 5s polling
    protected ?string $pollingInterval = null;

    public function table(Table $table): Table
    {
        $query = Archive::query()
            ->with(['organizationUnit.parent.parent', 'category'])
            ->where('download_count', '>', 0)
            ->orderByDesc('download_count')
            ->limit(10);

        $user = Auth::user();
        if ($user && $user->isStaff()) {
            $unitIds = $user->getAccessibleUnitIds();
            if ($unitIds !== null) {
                $query->whereIn('organization_unit_id', $unitIds);
            }
        }

        return $table
            ->query($query)
            ->columns([
                Tables\Columns\TextColumn::make('rank')
                    ->label('#')
                    ->rowIndex()
                    ->alignCenter()
                    ->width('3rem'),
                Tables\Columns\TextColumn::make('document_name')
                    ->label('Nama Dokumen')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge(),
                Tables\Columns\TextColumn::make('organizationUnit.full_path')
                    ->label('Unit Organisasi')
                    ->wrap(),
                Tables\Columns\TextColumn::make('download_count')
                    ->label('Jumlah Unduhan')
                    ->badge()
                    ->color('success')
                    ->alignCenter()
                    ->sortable(),
            ])
            ->paginated(false)
            ->emptyStateIcon('heroicon-o-arrow-down-tray')
            ->emptyStateHeading('Belum ada unduhan')
            ->emptyStateDescription('Dokumen yang diunduh akan muncul di sini.')
            ->recordUrl(
                fn (Archive $record): string => ArchiveResource::getUrl('edit', ['record' => $record])
            );
    }
}
