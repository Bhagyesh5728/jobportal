<?php

namespace App\Filament\Resources\JobPostResource\Pages;

use App\Filament\Resources\JobPostResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageJobPosts extends ManageRecords
{
    protected static string $resource = JobPostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Create Job Post')->modalWidth('md')->successNotificationTitle('Job Post Created Successfully')->createAnother(false),
        ];
    }
}
