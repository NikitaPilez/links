<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GenerateLinkResource\Pages;
use App\Filament\Resources\GenerateLinkResource\RelationManagers;
use App\Models\Blogger;
use App\Models\Domain;
use App\Models\GenerateLink;
use App\Models\Link;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class GenerateLinkResource extends Resource
{
    protected static ?string $model = GenerateLink::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Сгенерированные ссылки';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Основное')
                    ->collapsible()
                    ->schema([
                    Forms\Components\Select::make('link_id')
                        ->relationship('link', 'name')
                        ->searchable()
                        ->preload()
                        ->options(function () {
                            $user = auth()->user();

                            if ($user->role === 1) {
                                return Link::pluck('name', 'id');
                            }

                            return $user->links()->pluck('name', 'links.id');
                        })
                        ->label('Ссылка')
                        ->required(),
                    Forms\Components\Select::make('blogger_id')
                        ->relationship('blogger', 'name')
                        ->options(function () {
                            $user = auth()->user();

                            if ($user->role === 1) {
                                return Blogger::pluck('name', 'id');
                            }

                            return $user->bloggers()->pluck('name', 'bloggers.id');
                        })
                        ->searchable()
                        ->preload()
                        ->required()
                        ->label('Блогер'),
                    Forms\Components\Select::make('domain_id')
                        ->relationship('domain', 'name')
                        ->options(function () {
                            return Domain::where('is_active', true)->pluck('name', 'id');
                        })
                        ->searchable()
                        ->preload()
                        ->required()
                        ->label('Домен'),
                    Forms\Components\TextInput::make('scenario')
                        ->label('Сценарий')
                        ->required(),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('link.name')->sortable()->searchable()->label('Ссылка'),
                TextColumn::make('blogger.name')->sortable()->searchable()->label('Блоггер'),
                TextColumn::make('domain.name')->sortable()->searchable()->label('Домен'),
                TextColumn::make('scenario')->sortable()->searchable()->label('Сценарий'),
                TextColumn::make('generated_link')
                    ->copyable()
                    ->copyableState(fn (string $state): string => "https://{$state}")
                    ->sortable()
                    ->searchable()->label('Сгенерированная ссылка'),
            ])
            ->filters([
                Filter::make('blogger')
                    ->query(function (Builder $query, array $data) {
                        $query->whereHas('blogger', function (Builder $subQuery) use ($data) {
                            $subQuery->where('name', 'like', '%' . $data['blogger'] . '%');
                        });
                    })
                    ->form([
                        Forms\Components\TextInput::make('blogger')
                            ->label('Блоггер')
                            ->placeholder('Введите название блоггера')
                            ->required(),
                    ]),
                Filter::make('link')
                    ->query(function (Builder $query, array $data) {
                        $query->whereHas('link', function (Builder $subQuery) use ($data) {
                            $subQuery->where('name', 'like', '%' . $data['link'] . '%');
                        });
                    })
                    ->form([
                        Forms\Components\TextInput::make('link')
                            ->label('Ссылка')
                            ->placeholder('Введите ссылку')
                            ->required(),
                    ]),
                Filter::make('generated_link')
                    ->query(function (Builder $query, array $data) {
                        $query->where('generated_link', 'like', '%' . $data['generated_link'] . '%');
                    })
                    ->form([
                        Forms\Components\TextInput::make('generated_link')
                            ->label('Сгенерированная ссылка')
                            ->placeholder('Введите ссылку')
                            ->required(),
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListGenerateLinks::route('/'),
            'create' => Pages\CreateGenerateLink::route('/create'),
            'edit' => Pages\EditGenerateLink::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        /** @var Model $model */
        $model = static::getModel();
        $query = $model::query();

        /** @var User $user */
        $user = auth()->user();

        if ($user->role == 0) {
            $query->where('user_id', $user->id);
        }

        return $query;
    }

    public static function canEdit(Model $record): bool
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return true;
        }

        $generatedLinks = GenerateLink::query()->where('user_id', $user->id)->pluck('id')->toArray();

        return in_array($record->id, $generatedLinks);
    }
}
