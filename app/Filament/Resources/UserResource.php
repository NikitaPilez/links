<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\Blogger;
use App\Models\Link;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Пользователи';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required(fn ($record) => $record === null)
                    ->maxLength(255)
                    ->dehydrateStateUsing(fn ($state) => $state ? Hash::make($state) : null)->dehydrated(fn ($state) => filled($state)),
                Forms\Components\Select::make('role')
                    ->options([
                        1 => 'Admin',
                        0 => 'Manager',
                    ])
                    ->required(),
                Forms\Components\Select::make('bloggers')
                    ->relationship('bloggers', 'name')
                    ->multiple()
                    ->options(Blogger::pluck('name', 'id')->toArray())
                    ->label('Блоггеры'),
                Forms\Components\Select::make('links')
                    ->relationship('links', 'name')
                    ->multiple()
                    ->options(Link::pluck('name', 'id')->toArray())
                    ->label('Ссылки'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('email')->sortable()->searchable(),
                TextColumn::make('role')->sortable()->searchable()
                    ->formatStateUsing(function ($state) {
                    return $state === 1 ? 'Админ' : 'Менеджер';
                }),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function can(string $action, ?Model $record = null): bool
    {
        return auth()->user()->isAdmin();
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->isAdmin();
    }
}
