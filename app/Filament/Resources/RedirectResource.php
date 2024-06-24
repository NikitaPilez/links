<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RedirectResource\Pages;
use App\Filament\Resources\RedirectResource\RelationManagers;
use App\Models\Redirect;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;

class RedirectResource extends Resource
{
    protected static ?string $model = Redirect::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                Tables\Columns\TextColumn::make('date')->label('Дата'),
                Tables\Columns\TextColumn::make('count')->label('Кол-во'),
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
            'index' => Pages\ListRedirects::route('/'),
            'create' => Pages\CreateRedirect::route('/create'),
            'edit' => Pages\EditRedirect::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = static::$model::query();

//        $query
//            ->select(
//                DB::raw('ANY_VALUE(id) as id'),
//                DB::raw('DATE(created_at) as date'),
//                'geo',
////                DB::raw('COUNT(*) as redirect_count')
//            )
//            ->groupBy('date')->orderBy('date');

        $query->select(
            DB::raw('COUNT(id) as count'),
            DB::raw('DATE(created_at) as date'),
        )
            ->groupBy('date')->orderBy('date');

        return $query;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }
}
