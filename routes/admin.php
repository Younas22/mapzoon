<?php

use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\BlogPostController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
        Route::post('login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
    });

    Route::middleware('auth')->group(function () {
        Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('roles/data', [RoleController::class, 'data'])
            ->middleware('permission:roles.view')
            ->name('roles.data');

        Route::get('roles', [RoleController::class, 'index'])
            ->middleware('permission:roles.view')
            ->name('roles.index');

        Route::post('roles', [RoleController::class, 'store'])
            ->middleware('permission:roles.create')
            ->name('roles.store');

        Route::get('roles/{role}/edit', [RoleController::class, 'edit'])
            ->middleware('permission:roles.edit')
            ->name('roles.edit');

        Route::put('roles/{role}', [RoleController::class, 'update'])
            ->middleware('permission:roles.edit')
            ->name('roles.update');

        Route::delete('roles/{role}', [RoleController::class, 'destroy'])
            ->middleware('permission:roles.delete')
            ->name('roles.destroy');

        Route::get('users/data', [UserController::class, 'data'])
            ->middleware('permission:users.view')
            ->name('users.data');

        Route::get('users', [UserController::class, 'index'])
            ->middleware('permission:users.view')
            ->name('users.index');

        Route::get('users/create', [UserController::class, 'create'])
            ->middleware('permission:users.create')
            ->name('users.create');

        Route::post('users', [UserController::class, 'store'])
            ->middleware('permission:users.create')
            ->name('users.store');

        Route::get('users/{user}/edit', [UserController::class, 'edit'])
            ->middleware('permission:users.edit')
            ->name('users.edit');

        Route::put('users/{user}', [UserController::class, 'update'])
            ->middleware('permission:users.edit')
            ->name('users.update');

        Route::delete('users/{user}', [UserController::class, 'destroy'])
            ->middleware('permission:users.delete')
            ->name('users.destroy');

        Route::get('users/{user}', [UserController::class, 'show'])
            ->middleware('permission:users.view')
            ->name('users.show');

        Route::get('leads/data', [LeadController::class, 'data'])
            ->middleware('permission:leads.view')
            ->name('leads.data');

        Route::get('leads/export', [LeadController::class, 'export'])
            ->middleware('permission:leads.view')
            ->name('leads.export');

        Route::get('leads', [LeadController::class, 'index'])
            ->middleware('permission:leads.view')
            ->name('leads.index');

        Route::post('leads', [LeadController::class, 'store'])
            ->middleware('permission:leads.create')
            ->name('leads.store');

        Route::get('leads/{lead}/edit', [LeadController::class, 'edit'])
            ->middleware('permission:leads.edit')
            ->name('leads.edit');

        Route::put('leads/{lead}', [LeadController::class, 'update'])
            ->middleware('permission:leads.edit')
            ->name('leads.update');

        Route::patch('leads/{lead}/status', [LeadController::class, 'updateStatus'])
            ->middleware('permission:leads.edit')
            ->name('leads.status');

        Route::patch('leads/{lead}/assign', [LeadController::class, 'updateAssignee'])
            ->middleware('permission:leads.edit')
            ->name('leads.assign');

        Route::post('leads/{lead}/notes', [LeadController::class, 'storeNote'])
            ->middleware('permission:leads.edit')
            ->name('leads.notes.store');

        Route::delete('leads/{lead}', [LeadController::class, 'destroy'])
            ->middleware('permission:leads.delete')
            ->name('leads.destroy');

        Route::get('leads/{lead}', [LeadController::class, 'show'])
            ->middleware('permission:leads.view')
            ->name('leads.show');

        Route::get('categories/data', [CategoryController::class, 'data'])
            ->middleware('permission:blogs.view')
            ->name('categories.data');

        Route::get('categories', [CategoryController::class, 'index'])
            ->middleware('permission:blogs.view')
            ->name('categories.index');

        Route::post('categories', [CategoryController::class, 'store'])
            ->middleware('permission:blogs.create')
            ->name('categories.store');

        Route::get('categories/{category}/edit', [CategoryController::class, 'edit'])
            ->middleware('permission:blogs.edit')
            ->name('categories.edit');

        Route::put('categories/{category}', [CategoryController::class, 'update'])
            ->middleware('permission:blogs.edit')
            ->name('categories.update');

        Route::delete('categories/{category}', [CategoryController::class, 'destroy'])
            ->middleware('permission:blogs.delete')
            ->name('categories.destroy');

        Route::get('tags/data', [TagController::class, 'data'])
            ->middleware('permission:blogs.view')
            ->name('tags.data');

        Route::get('tags', [TagController::class, 'index'])
            ->middleware('permission:blogs.view')
            ->name('tags.index');

        Route::post('tags', [TagController::class, 'store'])
            ->middleware('permission:blogs.create')
            ->name('tags.store');

        Route::get('tags/{tag}/edit', [TagController::class, 'edit'])
            ->middleware('permission:blogs.edit')
            ->name('tags.edit');

        Route::put('tags/{tag}', [TagController::class, 'update'])
            ->middleware('permission:blogs.edit')
            ->name('tags.update');

        Route::delete('tags/{tag}', [TagController::class, 'destroy'])
            ->middleware('permission:blogs.delete')
            ->name('tags.destroy');

        Route::get('blog-posts/data', [BlogPostController::class, 'data'])
            ->middleware('permission:blogs.view')
            ->name('blog-posts.data');

        Route::get('blog-posts', [BlogPostController::class, 'index'])
            ->middleware('permission:blogs.view')
            ->name('blog-posts.index');

        Route::get('blog-posts/create', [BlogPostController::class, 'create'])
            ->middleware('permission:blogs.create')
            ->name('blog-posts.create');

        Route::post('blog-posts', [BlogPostController::class, 'store'])
            ->middleware('permission:blogs.create')
            ->name('blog-posts.store');

        Route::get('blog-posts/{blog_post}/edit', [BlogPostController::class, 'edit'])
            ->middleware('permission:blogs.edit')
            ->name('blog-posts.edit');

        Route::put('blog-posts/{blog_post}', [BlogPostController::class, 'update'])
            ->middleware('permission:blogs.edit')
            ->name('blog-posts.update');

        Route::get('blog-posts/{blog_post}/preview', [BlogPostController::class, 'preview'])
            ->middleware('permission:blogs.view')
            ->name('blog-posts.preview');

        Route::delete('blog-posts/{blog_post}', [BlogPostController::class, 'destroy'])
            ->middleware('permission:blogs.delete')
            ->name('blog-posts.destroy');

        Route::get('my-tasks', [TaskController::class, 'myTasks'])->name('tasks.mine');

        Route::get('tasks/data', [TaskController::class, 'data'])
            ->middleware('permission:tasks.view')
            ->name('tasks.data');

        Route::get('tasks', [TaskController::class, 'index'])
            ->middleware('permission:tasks.view')
            ->name('tasks.index');

        Route::post('tasks', [TaskController::class, 'store'])
            ->middleware('permission:tasks.create')
            ->name('tasks.store');

        Route::get('tasks/{task}/edit', [TaskController::class, 'edit'])
            ->middleware('permission:tasks.edit')
            ->name('tasks.edit');

        Route::put('tasks/{task}', [TaskController::class, 'update'])
            ->middleware('permission:tasks.edit')
            ->name('tasks.update');

        Route::delete('tasks/{task}', [TaskController::class, 'destroy'])
            ->middleware('permission:tasks.delete')
            ->name('tasks.destroy');

        // The routes below intentionally carry no permission: middleware — TaskPolicy
        // grants the task's assignee/creator access too, so gating at the route
        // level would lock them out of their own work. Authorization happens via
        // $this->authorize()/FormRequest::authorize() inside the controller instead.
        Route::patch('tasks/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.status');
        Route::patch('tasks/{task}/progress', [TaskController::class, 'updateProgress'])->name('tasks.progress');

        Route::patch('tasks/{task}/assign', [TaskController::class, 'updateAssignee'])
            ->middleware('permission:tasks.edit')
            ->name('tasks.assign');

        Route::post('tasks/{task}/notes', [TaskController::class, 'storeNote'])->name('tasks.notes.store');
        Route::post('tasks/{task}/comments', [TaskController::class, 'storeComment'])->name('tasks.comments.store');

        Route::post('tasks/{task}/subtasks', [TaskController::class, 'storeSubtask'])->name('tasks.subtasks.store');
        Route::patch('tasks/{task}/subtasks/{subtask}', [TaskController::class, 'toggleSubtask'])->name('tasks.subtasks.toggle');
        Route::delete('tasks/{task}/subtasks/{subtask}', [TaskController::class, 'destroySubtask'])->name('tasks.subtasks.destroy');

        Route::post('tasks/{task}/attachments', [TaskController::class, 'storeAttachment'])->name('tasks.attachments.store');
        Route::get('tasks/{task}/attachments/{attachment}/download', [TaskController::class, 'downloadAttachment'])->name('tasks.attachments.download');
        Route::delete('tasks/{task}/attachments/{attachment}', [TaskController::class, 'destroyAttachment'])->name('tasks.attachments.destroy');

        Route::get('tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
    });
});
