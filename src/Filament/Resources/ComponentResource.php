<?php

namespace Cachet\Filament\Resources;

use Cachet\Enums\ComponentStatusEnum;
use Cachet\Filament\Resources\ComponentResource\Pages;
use Cachet\Models\Component;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ComponentResource extends Resource
{
    protected static ?string $model = Component::class;

    protected static ?string $navigationIcon = 'cachet-components';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->columns(2)->schema([
                    Forms\Components\TextInput::make('name')
                        ->label(__('Name'))
                        ->required()
                        ->maxLength(255)
                        ->autocomplete(false),
                    Forms\Components\ToggleButtons::make('status')
                        ->label(__('Status'))
                        ->inline()
                        ->columnSpanFull()
                        ->options(ComponentStatusEnum::class)
                        ->required(),
                    Forms\Components\MarkdownEditor::make('description')
                        ->label(__('Description'))
                        ->maxLength(255)
                        ->columnSpanFull(),
                    Forms\Components\Select::make('component_group_id')
                        ->relationship('group', 'name')
                        ->searchable()
                        ->preload()
                        ->label(__('Component Group')),
                    Forms\Components\TextInput::make('link')
                        ->label(__('Link'))
                        ->url()
                        ->hint(__('An optional link to the component.')),
                ]),

                Forms\Components\Section::make()->columns(2)->schema([
                    Forms\Components\KeyValue::make('meta')
                        ->columnSpanFull(),
                    Forms\Components\Toggle::make('enabled')
                        ->required(),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('order')
                    ->label(__('Order'))
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('group.name')
                    ->label(__('Group'))
                    ->sortable(),
                Tables\Columns\IconColumn::make('enabled')
                    ->label(__('Enabled'))
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Created at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('Updated at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label(__('Deleted at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->reorderable('order')
            ->emptyStateHeading(__('Components'))
            ->emptyStateDescription(__('Components represent the various parts of your system that can affect the status of your status page.'));
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
            'index' => Pages\ListComponents::route('/'),
            'create' => Pages\CreateComponent::route('/create'),
            'edit' => Pages\EditComponent::route('/{record}/edit'),
        ];
    }

    public static function getLabel(): ?string
    {
        return __('Component');
    }

    public static function getPluralLabel(): ?string
    {
        return __('Components');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::outage()->count();
    }

    public static function getNavigationBadgeColor(): string
    {
        if ((int) static::getNavigationBadge() > 0) {
            return 'danger';
        }

        return 'success';
    }
}
