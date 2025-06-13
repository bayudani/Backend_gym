<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionsResource\Pages;
use App\Models\transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\BulkActionGroup;

class TransactionsResource extends Resource
{
    protected static ?string $model = transaction::class;
    protected static ?string $navigationGroup = 'Management';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('userId')
                ->label('Nama Anggota')
                ->relationship('user', 'name')
                ->searchable()
                ->required(),

            Select::make('membership_package_id')
                ->label('Paket Keanggotaan')
                ->relationship('membershipPackage', 'name')
                ->searchable()
                ->required(),

            Forms\Components\TextInput::make('amount')
                ->numeric()
                ->required()
                ->label('Jumlah'),

            FileUpload::make('proof_image')
                ->label('Bukti Transfer')
                // defaults image value
                
                ->image()
                ->directory('bukti-transfer'),

            Select::make('status')
                ->options([
                    'pending' => 'Pending',
                    'Confirmed' => 'Confirmed',
                ])
                ->default('pending'),

            DateTimePicker::make('confirmed_at')
                ->label('Dikonfirmasi Pada'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Nama Anggota')
                    ->searchable(),

                TextColumn::make('membershipPackage.name')
                    ->label('Paket Keanggotaan'),

                TextColumn::make('amount')
                    ->label('Jumlah')
                    ->money('IDR', true),

                TextColumn::make('status')
                    ->label('Status'),

                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime(),

                TextColumn::make('confirmed_at')
                    ->label('Dikonfirmasi Pada')
                    ->since(),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransactions::route('/create'),
            'edit' => Pages\EditTransactions::route('/{record}/edit'),
        ];
    }
}
