<?php

namespace App\Filament\Resources\Schedules\Pages;

use App\Models\SchoolYear;
use App\Filament\Pages\BaseCreateRecord;
use Illuminate\Support\Facades\Redirect;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Schedules\ScheduleResource;

class CreateSchedule extends BaseCreateRecord
{
    protected static string $resource = ScheduleResource::class;

    public function mount(): void
    {
        // CEK: Apakah ada tahun ajaran aktif?
        $hasActive = SchoolYear::where('is_active', true)->exists();

        if (!$hasActive) {
            // CEK: Apakah ada tahun ajaran (meski tidak aktif)?
            $hasAny = SchoolYear::exists();

            // Redirect ke halaman tahun ajaran menggunakan redirect()
            if ($hasAny) {
                // Ada tahun ajaran tapi tidak aktif
                $this->redirect(route('filament.admin.resources.school-years.index'));
            } else {
                // Tidak ada tahun ajaran sama sekali
                $this->redirect(route('filament.admin.resources.school-years.create'));
            }

            // Tambahkan notification
            \Filament\Notifications\Notification::make()
                ->title('Perhatian')
                ->body($hasAny
                    ? 'Aktifkan tahun ajaran terlebih dahulu!'
                    : 'Buat tahun ajaran terlebih dahulu!')
                ->warning()
                ->send();

            return; // Stop eksekusi
        }

        parent::mount();
    }
}
