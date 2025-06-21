<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionsResource\Pages;
use App\Models\transaction;
// use Filament\Actions\Action;
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
use Illuminate\Support\Facades\Http; // Ini client HTTP bawaan Laravel
use Filament\Notifications\Notification; // Untuk notifikasi ke admin
use Filament\Tables\Actions\Action;

class TransactionsResource extends Resource
{
    protected static ?string $model = transaction::class;
    protected static ?string $navigationGroup = 'Management';
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

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
                TextColumn::make('id')
                    ->label('Nama Anggota')
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('Nama Anggota')
                    ->searchable(),

                TextColumn::make('membershipPackage.name')
                    ->label('Paket Keanggotaan'),

                TextColumn::make('amount')
                    ->label('Jumlah')
                    ->money('IDR', true),

                TextColumn::make('status')
                ->label('Status')
                ->badge() // Ini bikin jadi badge
                ->color(fn (string $state): string => match ($state) {
                    'pending' => 'warning',
                    'Confirmed' => 'success',
                    'Rejected' => 'danger', // Kita tambahin status 'Rejected'
                    default => 'gray',
                }),

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
            // EditAction::make(), // Kita ganti ini dengan yang lebih canggih

            // === INI BAGIAN PALING SERUNYA ===

            Action::make('konfirmasi')
                ->label('Konfirmasi')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                // Tombol ini hanya muncul jika statusnya 'pending'
                ->visible(fn ($record) => $record->status === 'pending')
                ->action(function ($record) {
                    try {
                        // Kirim sinyal (HTTP Request) ke Express
                        $response = Http::patch(config('services.express.url') . '/transactions/' . $record->id . '/status', [
                            'status' => 'Confirmed',
                        ]);

                        // Jika request gagal, lempar error
                        $response->throw();

                        // Update status di database Laravel juga biar UI langsung berubah
                        $record->update(['status' => 'Confirmed', 'confirmed_at' => now()]);
                        
                        // Kasih notifikasi sukses ke admin
                        Notification::make()
                            ->title('Pembayaran Berhasil Dikonfirmasi!')
                            ->body('Membership untuk ' . $record->user->name . ' sekarang aktif.')
                            ->success()
                            ->send();
                            
                    } catch (\Exception $e) {
                        // Kalau gagal, kasih notifikasi error
                        Notification::make()
                            ->title('Gagal Mengkonfirmasi Pembayaran')
                            ->body('Error: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),

            Action::make('tolak')
                ->label('Tolak')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation() // Biar admin ga salah pencet
                ->visible(fn ($record) => $record->status === 'pending')
                ->action(function ($record) {
                     try {
                        // Kirim request ke Express untuk menolak
                        $response = Http::patch(config('services.express.url') . '/transactions/' . $record->id . '/status', [
                            'status' => 'Rejected',
                        ]);
                        $response->throw();

                        // Update status di DB Laravel
                        $record->update(['status' => 'Rejected']);

                        Notification::make()
                            ->title('Pembayaran Ditolak')
                            ->info()
                            ->send();

                    } catch (\Exception $e) {
                         Notification::make()
                            ->title('Gagal Menolak Pembayaran')
                            ->body('Error: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
                
            // Tombol edit bawaan bisa tetap ada jika diperlukan
            Tables\Actions\EditAction::make(),
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
