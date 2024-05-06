<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get("/", [PostController::class, "index"])->name("index");

Route::controller(UserController::class)->prefix("users")->group(function (){
    Route::get("/{user}", "show")->name("users.show");

    Route::middleware("guest")->group(function (){
        Route::post("/", "store")->name("users.store");
    });
    Route::middleware("auth")->group(function ()
    {
        Route::get("/{user}/edit", "edit")->name("users.edit");
        Route::put("/{user}", "update")->name("users.update");
        Route::delete("/{user}", "destroy")->name("users.destroy");
    });
});

Route::controller(PostController::class)->prefix("posts")->group(function (){
    Route::get("/{post}", "show")->name("posts.show");
    Route::middleware("auth")->group(function ()
    {
        Route::get("/create", "create")->name("posts.create");
        Route::post("/", "store")->name("posts.store");
        Route::get("/{post}/edit", "edit")->name("posts.edit");
        Route::put("/{post}", "update")->name("posts.update");
        Route::delete("/{post}", "destroy")->name("posts.destroy");
    });
});

Route::controller(AuthController::class)->prefix("auth")->group(function (){
    Route::post("/login", "login")->middleware("guest")->name("auth.login");
    Route::get("/logout", "logout")->middleware("auth")->name("auth.logout");
});
