<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LinkResource\Pages;
use App\Filament\Resources\LinkResource\RelationManagers;
use App\Models\Link;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LinkResource extends Resource
{
    protected static ?string $model = Link::class;

    protected static ?string $navigationLabel = 'Ссылки';

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->label('Имя')->required(),
                Forms\Components\TextInput::make('url')->label('Ссылка')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->toggleable()->searchable()->label('Имя'),
                Tables\Columns\TextColumn::make('url')->toggleable()->searchable()->label('Ссылка'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('id', 'desc');
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
            'index' => Pages\ListLinks::route('/'),
            'create' => Pages\CreateLink::route('/create'),
            'edit' => Pages\EditLink::route('/{record}/edit'),
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
            $query->whereHas('users', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
        }

        return $query;
    }
}
