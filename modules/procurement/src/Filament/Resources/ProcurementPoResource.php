<?php

namespace Modules\Procurement\Filament\Resources;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Modules\Procurement\Filament\Resources\ProcurementPoResource\Pages;
use Modules\Procurement\Models\Po;

class ProcurementPoResource extends Resource
{
    protected static ?string $model = Po::class;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('supplier_id')->required(),
            Forms\Components\TextInput::make('amount')->numeric()->required(),
            Forms\Components\Select::make('status')->options([
                'draft' => 'draft',
                'sent' => 'sent',
                'received' => 'received',
                'matched' => 'matched',
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('PO'),
                Tables\Columns\TextColumn::make('supplier_id'),
                Tables\Columns\TextColumn::make('status'),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('approve')
                    ->action(fn (\Illuminate\Support\Collection $records) => $records->each->send()),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProcurementPos::route('/'),
        ];
    }
}
