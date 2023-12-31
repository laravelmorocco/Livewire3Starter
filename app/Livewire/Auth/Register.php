<?php

declare(strict_types=1);

namespace App\Livewire\Auth;

use App\Enums\Status;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Spatie\Permission\Models\Role;

#[Layout('components.layouts.guest')]
class Register extends Component
{
    public $name = '';

    public $email = '';

    public $password = '';

    public $passwordConfirmation = '';

    public $phone;

    public $city;

    public $country;

    public $company_name;

    public $company_type;

    public $company_size;

    public function mount()
    {
        $this->city = 'Casablanca';
        $this->country = 'Morocco';
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|numeric',
            'password' => 'required|min:8|same:passwordConfirmation',
        ]);

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'phone' => $this->phone,
            'company_name' => $this->company_name,
            'company_type' => $this->company_type,
            'company_size' => $this->company_size,
            'city' => $this->city,
            'country' => $this->country,
            'status' => Status::INACTIVE, // Set status to inactive by default
        ]);

        $role = Role::where('name', 'client')->first();

        $user->assignRole($role);

        event(new Registered($user));

        Auth::login($user, true);

        switch (true) {
            case $user->hasRole('admin'):
                $homePage = '/admin/dashboard';
                break;
            default:
                $homePage = '/';
                break;
        }

        return $this->redirect($homePage, navigate: true);

    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
