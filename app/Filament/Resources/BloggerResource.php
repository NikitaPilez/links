<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BloggerResource\Pages;
use App\Filament\Resources\BloggerResource\RelationManagers;
use App\Models\Blogger;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BloggerResource extends Resource
{
    protected static ?string $model = Blogger::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationLabel = 'Блогеры';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->label('Имя')->required(),
                Forms\Components\TextInput::make('alias')->label('Алиас')->required(),
                Forms\Components\TextInput::make('comment')->label('Комментарий'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->toggleable()->searchable()->label('Имя'),
                Tables\Columns\TextColumn::make('alias')->toggleable()->searchable()->label('Алиас'),
                Tables\Columns\TextColumn::make('comment')->toggleable()->searchable()->label('Комментарий'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListBloggers::route('/'),
            'create' => Pages\CreateBlogger::route('/create'),
            'edit' => Pages\EditBlogger::route('/{record}/edit'),
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
