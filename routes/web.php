<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Guest\HomeController as GuestHomeController;
use App\Http\Controllers\Admin\HomeController as AdminHomeController;
use App\Http\Controllers\Admin\ProjectController;

Route::get('/', [GuestHomeController::class, "index"])->name("guest.home");

Route::middleware(["auth", "verified"])->name("admin.")->prefix("admin")->group(function(){
    Route::patch("/projects/{project}/restore", [ProjectController::class,"restore"])->name("projects.trash.restore");
    Route::get("/projects/trash", [ProjectController::class, "trash"])->name("projects.trash.index");
    Route::get("/", [AdminHomeController::class,"index"])->name("home");
    Route::delete("/projects/{project}/drop",[ProjectController::class,"drop"])->name("projects.trash.drop");
    Route::delete("/projects/drop", [ProjectController::class, "dropAll"])->name("projects.trash.dropAll");

    Route::get("/projects", [ProjectController::class,"index"])->name("projects.index");
    Route::get("/projects/create", [ProjectController::class,"create"])->name("projects.create");
    Route::post("/projects", [ProjectController::class,"store"])->name("projects.store");
    Route::get("/projects/{project}", [ProjectController::class,"show"])->name("projects.show");
    Route::get("/projects{project}/edit", [ProjectController::class,"edit"])->name("projects.edit");
    Route::put("/projects/{project}", [ProjectController::class,"update"])->name("projects.update");
    Route::delete("/projects/{project}", [ProjectController::class,"destroy"])->name("projects.destroy");
    Route::patch("/projects/{project}/toggle", [ProjectController::class, "togglePubblication"])->name("projects.toggle");
   
});

Route::middleware('auth')->name("profile.")->prefix("/profile")->group(function () {
    Route::get('/', [ProfileController::class, 'edit'])->name('edit');
    Route::patch('/', [ProfileController::class, 'update'])->name('update');
    Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
});

require __DIR__.'/auth.php';