<?php

namespace App\Livewire;

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TaskComponent extends Component
{
    public $user;
    public $tasks = [];
    public $modal = false;
    public $modalShare = false;
    public $title;
    public $id;
    public $editingTask = null;
    public $description;
    public $users;
    public $user_id;
    public $permission;
    public $shared = false;
    public function render()
    {
        return view('livewire.task-component');
    }
    public function mount()
    {
        $this->users = User::where('id', '!=', auth()->user()->id)->get();
        $this->user = Auth::user()->name;
        $this->getTask();
    }
    public function getTask()
    {
        //$tasks = Auth::user()->tasks;
        $this->tasks = Auth::user()->sharedTasks()->get();
        //$this->tasks = $shared->merge($tasks);
    }

    public function clearFields()
    {
        $this->title = '';
        $this->description = '';
        $this->id = '';
        $this->editingTask = null;
        $this->shared = false;
    }

    public function openCreateModal(Task $task = null)
    {
        if ($task) {
            $this->editingTask = $task;
            $this->title = $task->title;
            $this->description = $task->description;
            $this->id = $task->id;
            $this->shared = $task->shared;
        } else {
            $this->clearFields();
        }
        $this->modal = true;
    }
    public function deleteTask(Task $task)
    {
        $task->delete();
        $this->clearFields();
        $this->closeCreateModal();
        $this->getTask();
    }

    public function closeCreateModal()
    {
        $this->modal = false;
    }

    public function createTask()
    {
        if ($this->editingTask->id) {
            $task = Task::find($this->editingTask->id);
            $task->update(

                [
                    'title' => $this->title,
                    'description' => $this->description

                ]

            );
        } else {

            $task = new Task();
            $task->title = $this->title;
            $task->description = $this->description;
            $task->save();
            Auth::user()->sharedTasks()->attach($task->id, ['permission' => 'owner']);
        }
        $this->clearFields();
        $this->closeCreateModal();
        $this->getTask();
    }
    public function openShareModal(Task $task)
    {
        $this->editingTask = $task;
        $this->modalShare = true;
    }
    public function closeShareModal()
    {
        $this->modalShare = false;
    }
    public function shareTask()
    {
        $user = User::find($this->user_id);
        $user->sharedTasks()->attach($this->editingTask->id, ['permission' => $this->permission]);
        $task = Task::find($this->editingTask->id);
        $task->update(
            [
                'shared' => 1
            ]
        );
        $this->clearFields();
        $this->closeShareModal();
        $this->getTask();
    }
    public function unShareTask(Task $task)
    {
        DB::delete('delete from task_user where task_id = ? and user_id != ?', [$task->id, auth()->user()->id]);
        $task = Task::find($task->id);
        $task->update(
            [
                'shared' => 0
            ]
        );
        $this->getTask();
    }
}
