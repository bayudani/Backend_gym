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
                Tables\Actions\Action::make('klaim')
                    ->label('Klaim Reward')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn($record) => $record->reward_status === 'pending')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $member = $record->memberProfile;

                        if ($member->point >= 20) {
                            $member->point -= 20;
                            $member->save();

                            $record->update(['reward_status' => 'claimed']);

                            Notification::make()
                                ->title("Reward berhasil diklaim!")
                                ->success()
                                ->send();
                        } else {
                            Notification::make()
                                ->title("Point tidak cukup!")
                                ->danger()
                                ->send();
                        }
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
