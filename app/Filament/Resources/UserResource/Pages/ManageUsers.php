<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Models\User;
use Filament\Actions;
use App\Filament\Resources\UserResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;

class ManageUsers extends ManageRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Create User')->modalWidth('md')->successNotificationTitle('User Created Successfully')->createAnother(false)
            ->action(function ($data) {
                $data['password'] = bcrypt($data['password']);
                $data['email_verified_at'] = now();
                $user= User::create($data);
                $user->assignRole($data['role']);

                return Notification::make()->success()->title('User Created Successfully')->send();
            }),
        ];
    }
}
