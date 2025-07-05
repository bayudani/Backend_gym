<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RewardResource\Pages;
use App\Models\Reward;
use App\Models\MemberProfile;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class RewardResource extends Resource
{
    protected static ?string $model = Reward::class;
    protected static ?string $navigationGroup = 'Member area';

    protected static ?string $navigationIcon = 'heroicon-o-gift';
    protected static ?string $navigationLabel = 'Reward Member';
    protected static ?string $pluralModelLabel = 'Rewards';
    protected static ?string $modelLabel = 'Reward';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('member_profile_id')
                ->label('Member')
                ->relationship('memberProfile', 'full_name')
                ->searchable()
                ->required(),

            Forms\Components\Select::make('itemReward.id')
                ->label('Reward')
                ->relationship('itemReward', 'name')
                ->searchable()
                ->preload()

                ->required(),

            Forms\Components\Select::make('reward_status')
                ->label('Status')
                ->options([
                    'pending' => 'Pending',
                    'claimed' => 'Claimed',
                ])
                ->default('pending')
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('memberProfile.full_name')
                    ->label('Nama Member')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('itemReward.name')
                    ->label('Reward')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('reward_status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'claimed',
                    ])
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('reward_status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'claimed' => 'Claimed',
                    ]),

                // Tables\Filters\SelectFilter::make('member_profile_id')
                //     ->label('Member')
                //     ->relationship('memberProfile', 'full_name')
                //     ->searchable(),

                // Tables\Filters\SelectFilter::make('reward_type')
                //     ->label('Jenis Reward')
                //     ->options(fn() => Reward::query()
                //         ->select('reward_type')
                //         ->distinct()
                //         ->pluck('reward_type', 'reward_type')
                //         ->toArray()),
            ])

            ->actions([
                Tables\Actions\EditAction::make(),
                // --- INI DIA TOMBOL AKSI BARUNYA ---
            Tables\Actions\Action::make('confirm')
                ->label('Konfirmasi')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                // Tampilkan tombol ini HANYA jika statusnya 'pending'
                ->visible(fn (Reward $record): bool => $record->reward_status === 'pending')
                // Minta konfirmasi pop-up sebelum menjalankan aksi
                ->requiresConfirmation()
                // Di sinilah logic utama terjadi
                ->action(function (Reward $record) {
                    // Gunakan transaksi database untuk keamanan
                    DB::transaction(function () use ($record) {
                        $member = $record->memberProfile;
                        $itemReward = $record->itemReward;

                        // 1. Validasi ulang: Cek apakah poin member masih cukup
                        if ($member->point < $itemReward->points) {
                            Notification::make()
                                ->title('Gagal! Poin Member Tidak Cukup')
                                ->body("Poin member saat ini: {$member->point}. Poin dibutuhkan: {$itemReward->points}.")
                                ->danger()
                                ->send();
                            // Hentikan proses
                            return;
                        }

                        // 2. Kurangi poin member
                        $member->decrement('point', $itemReward->points);

                        // 3. Ubah status reward menjadi 'claimed'
                        $record->update(['reward_status' => 'claimed']);

                        // 4. Beri notifikasi sukses
                        Notification::make()
                            ->title('Reward Berhasil Dikonfirmasi')
                            ->success()
                            ->send();
                    });
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRewards::route('/'),
            'create' => Pages\CreateReward::route('/create'),
            'edit' => Pages\EditReward::route('/{record}/edit'),
        ];
    }
}
