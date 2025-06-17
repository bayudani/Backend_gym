<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MemberProfilesResource\Pages;
use App\Filament\Resources\MemberProfilesResource\RelationManagers;
use App\Models\member;
use App\Models\MemberProfiles;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MemberProfilesResource extends Resource
{
    protected static ?string $model = member::class;
    protected static ?string $navigationGroup = 'Member area';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required(),
                TextInput::make('full_name')->required(),
                TextInput::make('phone')->tel(),
                TextInput::make('point')
                    ->label('Poin')
                    ->numeric(),
                // ->readOnly(),
                Textarea::make('addres'),
                // TextInput::make('member_code')->required(),
                DatePicker::make('start_date'),
                DatePicker::make('end_date'),
                Toggle::make('is_active')->label('Aktif?'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('Id_User'),
                Tables\Columns\TextColumn::make('user.name')->label('Nama Pengguna'),
                Tables\Columns\TextColumn::make('full_name')->label('Nama Lengkap'),
                Tables\Columns\TextColumn::make('phone')->label('Telepon'),
                Tables\Columns\TextColumn::make('addres')->label('Alamat'),
                Tables\Columns\TextColumn::make('start_date')->date()->label('Tanggal Mulai'),
                Tables\Columns\TextColumn::make('end_date')->date()->label('Tanggal Berakhir'),
                Tables\Columns\BooleanColumn::make('is_active')->label('Aktif?'),
                Tables\Columns\TextColumn::make('point')
                    ->label('Poin')
                    ->sortable()
                    ->searchable(),
                BadgeColumn::make('point')
                    ->label('Poin')
                    ->colors([
                        'success' => fn($state) => $state >= 1000,
                        'warning' => fn($state) => $state < 1000 && $state >= 500,
                        'danger' => fn($state) => $state < 500,
                    ])
                    ->formatStateUsing(fn($state) => "{$state} poin"),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('is_active')
                    ->options([
                        true => 'Aktif',
                        false => 'Tidak Aktif',
                    ])->label('Status Aktif'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('aktifkan')
                    ->label('Aktifkan')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn($record) => !$record->is_active) // Tampilkan hanya kalau belum aktif
                    ->action(function ($record) {
                        $record->is_active = true;
                        $record->save();

                        \Filament\Notifications\Notification::make()
                            ->title('Member berhasil diaktifkan!')
                            ->success()
                            ->send();
                    }),

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
            'index' => Pages\ListMemberProfiles::route('/'),
            'create' => Pages\CreateMemberProfiles::route('/create'),
            'edit' => Pages\EditMemberProfiles::route('/{record}/edit'),
        ];
    }
}
