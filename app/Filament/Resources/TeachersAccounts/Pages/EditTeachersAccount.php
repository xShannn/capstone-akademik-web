<?php

namespace App\Filament\Resources\TeachersAccounts\Pages;

use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Placeholder;
use App\Filament\Resources\TeachersAccounts\TeachersAccountResource;

class EditTeachersAccount extends EditRecord
{
    protected static string $resource = TeachersAccountResource::class;

    public bool $emailChanged = false;
    public bool $passwordChanged = false;
    public string $originalEmail = '';

    public function mount($record): void
    {
        parent::mount($record);
        // Simpan email asli saat mount
        $this->originalEmail = $this->record->email;
    }

    protected function getFormSchema(): array
    {
        return [
            Section::make('Informasi Akun')
                ->schema([
                    Placeholder::make('teacher_info')
                        ->content(function () {
                            $teacher = $this->record->teacher;
                            return $teacher
                                ? "Nama: {$teacher->nama_lengkap}\nNIP: {$teacher->nip}\nJabatan: {$teacher->jabatan}"
                                : 'Guru tidak ditemukan';
                        })
                        ->extraAttributes(['class' => 'whitespace-pre-line']),

                    TextInput::make('username')
                        ->label('Username (NIP)')
                        ->required()
                        ->disabled()
                        ->helperText('Username untuk login, tidak bisa diubah'),

                    TextInput::make('email')
                        ->label('Email Sekolah')
                        ->email()
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->helperText('Email untuk login dan notifikasi'),
                ]),

            Section::make('Ubah Password (Opsional)')
                ->description('Hanya isi jika ingin mengganti password')
                ->collapsible()
                ->collapsed()
                ->schema([
                    TextInput::make('new_password')
                        ->label('Password Baru')
                        ->password()
                        ->revealable()
                        ->helperText('Kosongkan jika tidak ingin mengubah password')
                        ->dehydrated(false),

                    TextInput::make('password_confirmation')
                        ->label('Konfirmasi Password Baru')
                        ->password()
                        ->revealable()
                        ->same('new_password')
                        ->dehydrated(false),
                ]),

            Section::make('Informasi Login & Keamanan')
                ->schema([
                    Placeholder::make('password_status')
                        ->label('Status Password')
                        ->content(function () {
                            try {
                                $isDefault = Hash::check('password123', $this->record->password);
                                return $isDefault
                                    ? 'Masih menggunakan password default (password123)'
                                    : 'Password sudah diubah dari default';
                            } catch (\Exception $e) {
                                return 'Tidak dapat memverifikasi status password';
                            }
                        })
                        ->color(function () {
                            try {
                                $isDefault = Hash::check('password123', $this->record->password);
                                return $isDefault ? 'warning' : 'success';
                            } catch (\Exception $e) {
                                return 'gray';
                            }
                        }),
                ]),

            Section::make('Informasi Sistem')
                ->schema([
                    Placeholder::make('created_at')
                        ->label('Dibuat Pada')
                        ->content(fn() => $this->record->created_at->translatedFormat('d F Y H:i:s')),

                    Placeholder::make('updated_at')
                        ->label('Terakhir Diubah')
                        ->content(fn() => $this->record->updated_at->translatedFormat('d F Y H:i:s')),

                    Placeholder::make('last_login')
                        ->label('Terakhir Login')
                        ->content(function () {
                            if (isset($this->record->last_login_at) && $this->record->last_login_at) {
                                return $this->record->last_login_at->translatedFormat('d F Y H:i:s');
                            }
                            return 'Belum pernah login';
                        }),
                ]),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Reset flags
        $this->emailChanged = false;
        $this->passwordChanged = false;

        // Cek email berubah
        if ($this->originalEmail !== $data['email']) {
            $this->emailChanged = true;
        }

        // Cek password diisi
        if (!empty($data['new_password'])) {
            $data['password'] = Hash::make($data['new_password']);
            $this->passwordChanged = true;
        }

        unset($data['new_password'], $data['password_confirmation']);

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('resetPassword')
                ->label('Reset Password')
                ->icon('heroicon-o-lock-open')
                ->color('warning')
                ->action(function () {
                    try {
                        $this->record->update([
                            'password' => Hash::make('password123')
                        ]);

                        Notification::make()
                            ->title('Password Berhasil Direset')
                            ->success()
                            ->body("Password untuk {$this->record->name} telah direset ke default: password123")
                            ->persistent()
                            ->send();

                        $this->redirect(
                            TeachersAccountResource::getUrl('view', ['record' => $this->record->id])
                        );
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Gagal Reset Password')
                            ->danger()
                            ->body("Error: " . $e->getMessage())
                            ->send();
                    }
                })
                ->requiresConfirmation()
                ->modalHeading('Reset Password')
                ->modalDescription('Reset password untuk: ' . $this->record->name)
                ->modalSubheading('Password akan direset menjadi: password123')
                ->modalButton('Reset Sekarang'),

            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function getSavedNotificationTitle(): ?string
    {
        if ($this->passwordChanged) {
            return "Password '{$this->record->name}' berhasil diubah";
        }

        if ($this->emailChanged) {
            return "Email '{$this->record->name}' berhasil diperbarui";
        }

        return "Data '{$this->record->name}' berhasil diperbarui";
    }

    protected function getSavedNotification(): ?Notification
    {
        if ($this->passwordChanged) {
            return Notification::make()
                ->title('Password Berhasil Diubah')
                ->success()
                ->body('Password telah diperbarui')
                ->send();
        }

        if ($this->emailChanged) {
            return Notification::make()
                ->title('Email Berhasil Diperbarui')
                ->success()
                ->body('Email telah diperbarui')
                ->send();
        }

        return Notification::make()
            ->title('Data Berhasil Diperbarui')
            ->success()
            ->send();
    }

    protected function afterSave(): void
    {
        usleep(300000);

        $this->redirect(
            TeachersAccountResource::getUrl('view', ['record' => $this->record->id])
        );
    }
}
