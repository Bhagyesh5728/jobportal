<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\ViewAction;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function canCreate(): bool
    {
        if (Auth::user()->hasRole('Admin')) {
            return true;
        }
        return false;
    }
    public static function canEdit(Model $record): bool
    {
        if (Auth::user()->hasRole('Admin')) {
            return true;
        }
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        if (Auth::user()->hasRole('Admin')) {
            return true;
        }
        return false;
    }

    public static function canViewAny(): bool
    {
        if (Auth::user()->hasRole('Admin') ) {
            return true;
        }
        return false;
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->placeholder('Email')
                    ->unique(ignoreRecord: true)
                    ->email()
                    ->required()
                    ->maxLength(255),
                Select::make('role')
                    ->options([
                        'Admin' => 'Admin',
                        'Employee' => 'Employee',
                    ])
                    ->native(false)
                    ->placeholder('Select Role')
                    ->required()
                    ->default(fn($record) => $record?->getRoleNames()->first()),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->revealable()
                    ->placeholder('Password')
                    ->required()
                    ->hidden(function ($operation) {
                        return $operation === 'edit';
                    })
                    ->maxLength(255),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        $table = $table->modifyQueryUsing(function (Builder $query) {
            return $query->where('id', '!=', auth()->user()->id);
        });

        return $table
            ->recordAction(null)
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('dob')
                    ->label('Role')
                    ->badge()
                    ->color('primary')
                    ->getStateUsing(fn($record) => $record->getRoleNames()->first() ?? 'No Role')
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('roles')
                    ->label(__('Role') . ':')
                    ->native(false)
                    ->relationship('roles', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->iconButton()->modalWidth('md')->successNotificationTitle('User Updated Successfully'),
                Tables\Actions\DeleteAction::make()->iconButton()->successNotificationTitle('User Deleted Successfully'),
            ])->actionsColumnLabel('Actions');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageUsers::route('/'),
        ];
    }
}
