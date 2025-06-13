<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AbsensResource\Pages;
use App\Filament\Resources\AbsensResource\RelationManagers;
use App\Models\absen;
use App\Models\Absens;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AbsensResource extends Resource
{
    protected static ?string $model = absen::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('member_profile_id')
                    ->relationship('memberProfile', 'full_name')
                    ->searchable()
                    ->required(),
                Forms\Components\DateTimePicker::make('scan_time')
                    ->required()
                    ->label('Waktu Scan'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('memberProfile.full_name')
                    ->label('Nama Anggota'),
                Tables\Columns\TextColumn::make('scan_time')
                    ->dateTime()
                    ->label('Waktu Scan'),
            ])->filters([
                Tables\Filters\SelectFilter::make('member_profile_id')
                    ->relationship('memberProfile', 'full_name')
                    ->label('Anggota'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListAbsens::route('/'),
            'create' => Pages\CreateAbsens::route('/create'),
            'edit' => Pages\EditAbsens::route('/{record}/edit'),
        ];
    }
}
