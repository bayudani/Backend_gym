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
    protected static ?string $navigationGroup = 'Member area';

    protected static ?string $navigationIcon = 'heroicon-o-qr-code';

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
                Tables\Filters\SelectFilter::make('member_profile_id')
                    ->label('Anggota')
                    ->relationship('memberProfile', 'full_name')
                    ->searchable(),

                Tables\Filters\Filter::make('scan_time')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('Mulai Tanggal'),
                        Forms\Components\DatePicker::make('until')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['from'], fn($q) => $q->whereDate('scan_time', '>=', $data['from']))
                            ->when($data['until'], fn($q) => $q->whereDate('scan_time', '<=', $data['until']));
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (!$data['from'] && !$data['until']) return null;

                        $from = $data['from'] ?? 'awal';
                        $until = $data['until'] ?? 'sekarang';

                        return "Scan antara $from - $until";
                    }),
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
