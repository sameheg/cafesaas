<?php

namespace Modules\Franchise\Filament\Resources;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Modules\Franchise\Models\FranchiseBranch;

class FranchiseBranchResource extends Resource
{
    protected static ?string $model = FranchiseBranch::class;
    protected static ?string $navigationGroup = 'Franchise';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->required(),
            Forms\Components\Textarea::make('overrides')->default('{}'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('tenant_id'),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('bulkSync')
                    ->label('Bulk Sync')
                    ->action(fn ($records) => null),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFranchiseBranches::route('/'),
            'create' => Pages\CreateFranchiseBranch::route('/create'),
            'edit' => Pages\EditFranchiseBranch::route('/{record}/edit'),
        ];
    }
}
