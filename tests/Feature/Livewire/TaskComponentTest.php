<?php

use App\Livewire\TaskComponent;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(TaskComponent::class)
        ->assertStatus(200);
});
