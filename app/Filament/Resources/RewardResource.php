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
                    'confirmed' => 'Confirmed',
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
                // point member

                Tables\Columns\TextColumn::make('memberProfile.point')
                    ->label('Poin Member')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('itemReward.name')
                    ->label('Reward')
                    ->sortable(),
                    // image

                Tables\Columns\TextColumn::make('itemReward.points')
                    ->label('Poin')
                    ->sortable(),
                // image

                Tables\Columns\ImageColumn::make('itemReward.image')
                    ->label('Image')
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
                //     ->searchable(),
                
                Tables\Filters\SelectFilter::make('itemReward.name')
                ->label('Jenis Reward')
                ->relationship('itemReward', 'name')
                ->options(fn() => Reward::query()
                        ->select('itemReward.name')
                        ->distinct()
                        // ->pluck('reward_type', 'reward_type')
                        ->toArray()),
            ])

            ->actions([
                Tables\Actions\EditAction::make(),
                // --- INI DIA TOMBOL AKSI BARUNYA ---
                Tables\Actions\Action::make('confirm')
                    ->label('Konfirmasi')
                    ->icon('heroicon-o-check-circle')->color('info')
                    ->visible(fn(Reward $record): bool => $record->reward_status === 'pending')
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi Reward')
                    ->modalDescription('Anda yakin ingin konfirmasi.')
                    ->action(function (Reward $record) {
                        $record->update(['reward_status' => 'confirmed']);
                        Notification::make()->title('Selesai!')->body('Reward telah ditandai sebagai dikonfirmasi, dan menunggu member untuk mengambil.')->success()->send();
                    }),

                // --- AKSI 2 (BARU): DARI CONFIRMED -> CLAIMED (Finalisasi oleh Admin) ---
                Tables\Actions\Action::make('markAsClaimed')
                    ->label('Tandai Sudah Diambil & potong point')
                    ->icon('heroicon-o-check-badge')->color('success')
                    ->visible(fn(Reward $record): bool => $record->reward_status === 'confirmed')
                    ->requiresConfirmation()
                    ->modalHeading('Selesaikan Klaim Reward')
                    ->modalDescription('Pastikan reward ini sudah benar-benar diserahkan ke member. dan point member akan langsung dipotong')
                    ->action(function (Reward $record) {
                        DB::transaction(function () use ($record) {
                            $member = $record->memberProfile;
                            $itemReward = $record->itemReward;

                            if ($member->point < $itemReward->points) {
                                Notification::make()->title('Gagal! Poin Member Tidak Cukup')->danger()->send();
                                return;
                            }

                            $member->decrement('point', $itemReward->points);
                            $record->update(['reward_status' => 'claimed']); // Status jadi 'confirmed'

                            Notification::make()->title('Reward Berhasil Diklaim!')->body('Poin member telah dikurangi sebesar ' . $itemReward->points . ' poin.!')->success()->send();
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
