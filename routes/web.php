<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get("/", [PostController::class, "index"])->name("index");

Route::controller(UserController::class)->prefix("users")->group(function (){
    Route::get("/{id}", "show")->name("users.show");

    Route::middleware("guest")->group(function (){
        Route::get("/create", "create")->name("users.create");
        Route::post("/", "store")->name("users.store");
    });
    Route::middleware("auth")->group(function ()
    {
        Route::get("/{id}/edit", "edit")->name("users.edit");
        Route::put("/{id}", "update")->name("users.update");
        Route::delete("/{id}", "destroy")->name("users.destroy");
    });
});

Route::controller(PostController::class)->prefix("posts")->group(function (){
    Route::get("/{id}", "show")->name("posts.show");
    Route::middleware("auth")->group(function ()
    {
        Route::get("/create", "create")->name("posts.create");
        Route::post("/", "store")->name("posts.store");
        Route::get("/{id}/edit", "edit")->name("posts.edit");
        Route::put("/{id}", "update")->name("posts.update");
        Route::delete("/{id}", "destroy")->name("posts.destroy");
    });
});

Route::controller(AuthController::class)->prefix("auth")->group(function (){
    Route::post("/login", "login")->middleware("guest")->name("auth.login");
    Route::get("/logout", "logout")->middleware("auth")->name("auth.logout");
});
