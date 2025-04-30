<?php

namespace App\Filament\Pages;

use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Table;
use App\Models\Application;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ActionColumn;
use Filament\Tables\Columns\SelectColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Concerns\InteractsWithTable;

class Applications extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.applications';

    public static function canAccess(): bool
    {
        if (auth()->user()->hasRole('Employee')) {
            return true;
        }
        return false;
    }

    public function tableQuery(): Builder
    {
        return Application::query()
            ->whereHas('jobPost', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->with(['user', 'jobPost']);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->tableQuery())
            ->columns([
                TextColumn::make('user.name')->label('Applicant Name')->searchable(),
                TextColumn::make('jobPost.title')->label('Job Title')->searchable(),
                SelectColumn::make('status')
                    ->label('Status')
                    ->options([
                        Application::REJECTED => 'Rejected',
                        Application::ACTIVE => 'Active',
                        Application::PENDING => 'Pending',
                    ])

                    ->sortable()
                    ->searchable()
                    ->afterStateUpdated(function ($record, $state) {
                        $record->update(['status' => $state]);
                        Notification::make()->title('Status Updated Successfulyy')->success()->send();
                    }),
                TextColumn::make('resume')
                    ->label('Resume')
                    ->getStateUsing(function ($record) {
                        return $record->user?->getFirstMediaUrl(User::RESUME);
                    })
                    ->url(fn($state) => $state ?: null, true)
                    ->openUrlInNewTab()
                    ->formatStateUsing(fn($state) => $state ? 'View' : 'Not Uploaded')

            ])
            ->defaultSort('id', 'desc');
    }
}
