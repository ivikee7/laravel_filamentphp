<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Filament\Admin\Resources\UserResource;
use App\Models\User;
use Filament\Resources\Pages\Page;

class IDCards extends Page
{
    protected static string $resource = UserResource::class;

    protected static string $view = 'filament.admin.resources.user-resource.pages.i-d-cards';

    public $users;

    public function mount(): void
    {
        $this->users = User::all(); // Fetch all users
    }

    public function getUsers()
    {
        return User::all();
    }
}
