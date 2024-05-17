<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get("/", [PostController::class, "index"])->name("index");

Route::controller(UserController::class)->prefix("users")->group(function (){

    Route::middleware("guest")->group(function (){
        Route::post("/", "store")->name("users.store");
        Route::get("/create", "create")->name("users.create");
    });
    Route::middleware("auth")->group(function ()
    {
        Route::get("/{id}/edit", "edit")->name("users.edit");
        Route::put("/{id}", "update")->name("users.update");
        Route::delete("/{id}", "destroy")->name("users.destroy");
    });
    Route::get("/{id}", "show")->name("users.show");
});

Route::controller(PostController::class)->prefix("posts")->group(function (){
    Route::middleware("auth")->group(function ()
    {
        Route::post("/", "store")->name("posts.store");
        Route::get("/{id}/edit", "edit")->name("posts.edit");
        Route::put("/{id}", "update")->name("posts.update");
        Route::delete("/{id}", "destroy")->name("posts.destroy");
    });
    Route::get("/{id}", "show")->name("posts.show");
});

Route::controller(AuthController::class)->prefix("auth")->group(function (){
    Route::post("/logout", "logout")->middleware("auth")->name("auth.logout");
    Route::post("/tryLogin", "login")->middleware("guest")->name("auth.tryLogin");
});
