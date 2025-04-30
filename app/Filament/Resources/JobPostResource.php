<?php

namespace App\Filament\Resources;

use Auth;
use Filament\Forms;
use Filament\Tables;
use App\Models\JobPost;
use App\Models\SavedJob;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Hidden;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\JobPostResource\Pages;

class JobPostResource extends Resource
{
    protected static ?string $model = JobPost::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    
    public static function canCreate(): bool
    {
        if (Auth::user()->hasRole('Employee')) {
            return true;
        }
        return false;
    }
    public static function canEdit(Model $record): bool
    {
        if (Auth::user()->hasRole('Employee')) {
            return true;
        }
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        if (Auth::user()->hasRole('Employee')) {
            return true;
        }
        return false;
    }

    public static function canViewAny(): bool
    {
        if (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Employee') ) {
            return true;
        }
        return false;
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->placeholder('Title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->placeholder("Description")
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('company')
                    ->required()
                    ->placeholder('Company')
                    ->maxLength(255),
                Forms\Components\TextInput::make('location')
                    ->required()
                    ->placeholder('Location')
                    ->maxLength(255),
                Hidden::make('user_id')
                    ->default(Auth::id()),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {   
        $table = $table->modifyQueryUsing(function (Builder $query) {
            if (!Auth::user()->hasRole('Admin')) {
                return $query->where('user_id', Auth::id());
            }
    
            return $query;
        });
        
        return $table
            ->columns(array_filter([
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('company')->searchable(),
                Tables\Columns\TextColumn::make('location')->searchable(),

                !Auth::user()->hasRole('Admin')
                ? Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn($state) => match ((int) $state) {
                        JobPost::REJECTED => 'Rejected',
                        JobPost::ACTIVE => 'Active',
                        JobPost::PENDING => 'Pending',
                        default => 'Unknown',
                    })
                    ->color(fn($state) => match ((int) $state) {
                        JobPost::REJECTED => 'danger',
                        JobPost::ACTIVE => 'success',
                        JobPost::PENDING => 'warning',
                        default => 'gray',
                    })
                    ->sortable()
                : null,

                Auth::user()->hasRole('Admin')
                ? Tables\Columns\SelectColumn::make('status')
                    ->label('Status')
                    ->options([
                        JobPost::ACTIVE => 'Active',
                        JobPost::PENDING => 'Pending',
                        JobPost::REJECTED => 'Rejected',
                    ])
                    ->afterStateUpdated(function ($state, $record) {
                        $record->status = $state;
                        $record->save();
                        Notification::make()->title('Status Updated Successfully!!')->success()->send();
                    })
                    ->sortable()
                : null,
            ]))
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->tooltip('Edit')
                    ->iconButton()
                    ->modalWidth('md')
                    ->hidden(fn () => !Auth::user()->hasRole('Employee'))
                    ->successNotificationTitle('User Updated Successfully'),

                Tables\Actions\DeleteAction::make()
                    ->tooltip('Delete')
                    ->iconButton()
                    ->successNotificationTitle('User Deleted Successfully')
                    ->hidden(fn () => !Auth::user()->hasRole('Employee')),
            ])
            ->actionsColumnLabel('Actions')
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageJobPosts::route('/'),
        ];
    }
}
