<?php

use App\Livewire\Dashboard;
use Illuminate\Support\Facades\Route;
use App\Livewire\Projects\Index as ProjectsIndex;
use App\Livewire\Projects\Form as ProjectsForm;
use App\Livewire\Projects\Tasks\Index as ProjectTasksIndex;
use App\Livewire\Tasks\Index as TasksAllIndex;
use App\Livewire\Projects\Tasks\Create as ProjectTasksCreate;
use App\Livewire\Projects\Tasks\Edit as ProjectTasksEdit;
use App\Livewire\Clients\Index as ClientsIndex;
use App\Livewire\Clients\Show as ClientsShow;
use App\Livewire\Gantt\Index as GanttIndex;
use App\Livewire\Passwords\Index as PasswordsIndex;

//Route::view('/', 'welcome');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    Route::view('profile', 'profile')->name('profile');

    Route::get('/projects', ProjectsIndex::class)->name('projects.index');
    Route::get('/projects/create', ProjectsForm::class)->name('projects.create');
    Route::get('/projects/{project}/edit', ProjectsForm::class)->name('projects.edit');

    // Al hacer click en un proyecto => /projects/{project}/tasks
    Route::get('/projects/{project}/tasks', ProjectTasksIndex::class)->name('projects.tasks.index');
    Route::get('/projects/{project}/tasks/create', ProjectTasksCreate::class)->name('projects.tasks.create');
    Route::get('/projects/{project}/tasks/{task}/edit', ProjectTasksEdit::class)->name('projects.tasks.edit');

    // Link del menú "Tareas" => listado global con columna proyecto
    Route::get('/tasks', TasksAllIndex::class)->name('tasks.index');
    Route::get('/gantt', GanttIndex::class)->name('gantt.index');

    Route::get('/clients', ClientsIndex::class)->name('clients.index');
    Route::get('/clients/{client}', ClientsShow::class)->name('clients.show');

    Route::get('/passwords', PasswordsIndex::class)->name('passwords.index');
});

require __DIR__.'/auth.php';
