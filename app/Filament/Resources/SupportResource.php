<?php

namespace App\Filament\Resources;

use App\Models\Support;

use Filament\Tables\Table;
use Filament\Forms\Form;
use Filament\Resources\Resource;

use Filament\Tables\Filters\SelectFilter;
// use Filament\Tables\Filters\TrashedFilter;

use Filament\Tables\Columns\TextColumn;

// use Filament\Forms\Components\TextInput;

// use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

// use App\Filament\Resources\SupportResource\RelationManagers;
use App\Filament\Resources\SupportResource\Pages\EditSupport;
use App\Filament\Resources\SupportResource\Pages\ListSupports;
use App\Filament\Resources\SupportResource\Pages\CreateSupport;

class SupportResource extends Resource
{
    protected static ?string $model = Support::class;
    protected static ?string $navigationIcon = 'heroicon-o-lifebuoy';

    public static function getNavigationGroup(): string
    {
        return __('sidebar.admin.support');
    }

    public static function getNavigationLabel(): string
    {
        return __('support.support');
    }

    public static function getPluralModelLabel(): string
    {
        return __('support.support');
    }

    public static function getModelLabel(): string
    {
        return __('support.support');
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label(__('support.fields.user'))
                    ->numeric()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('message')
                    ->label(__('support.fields.message')),
                TextColumn::make('status')
                    ->label(__('support.fields.status'))
                    ->badge()
                    ->state(fn($record): string => $record->status)
                    ->formatStateUsing(fn(string $state): string => [
                        'open' => __('support.status.open'),
                        'closed' => __('support.status.closed'),
                    ][$state] ?? $state)
                    ->color(fn(string $state): string => [
                        'open' => 'success',
                        'closed' => 'danger',
                    ][$state] ?? 'secondary')
                    ->icon(fn(string $state): string => [
                        'open' => 'heroicon-o-check-circle',
                        'closed' => 'heroicon-o-x-circle',
                    ][$state] ?? 'heroicon-o-question-mark-circle'),
                TextColumn::make('created_at')
                    ->label(__('support.fields.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('support.fields.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'open' => __('support.status.open'),
                        'closed' => __('support.status.closed'),
                    ])
                    ->native(false)
                    ->label(__('support.fields.status')),
            ])
            ->actions([
                //TODO: add custom action to view support details and reply
            ])
            ->bulkActions([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSupports::route('/'),
            // 'create' => CreateSupport::route('/create'),
            // 'edit' => EditSupport::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['user']);
    }
}
