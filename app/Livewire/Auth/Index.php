<?php

declare(strict_types=1);

namespace App\Livewire\Auth;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.guest')]
class Index extends Component
{
    public $isStoreOwner = true;

    protected $listeners = ['storeOwnerChanged' => 'hideLoginForm'];

    public function hideLoginForm()
    {
        $this->isStoreOwner = false;
    }

    public function render()
    {
        return view('livewire.auth.index');
    }
}
