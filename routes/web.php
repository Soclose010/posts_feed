<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get("/", [PostController::class, "index"]);
Route::resource("users", UserController::class)->except([
    "index"
]);

Route::controller(UserController::class)->prefix("users")->group(function (){
    Route::get("/{user}", "show");

    Route::middleware("guest")->group(function (){
        Route::get("/create", "create");
        Route::post("/", "store");
    });
    Route::middleware("auth")->group(function ()
    {
        Route::get("/{user}/edit", "edit");
        Route::put("/{user}", "update");
        Route::delete("/{post}", "destroy");
    });
});

Route::controller(PostController::class)->prefix("posts")->group(function (){
    Route::get("/{post}", "show");
    Route::middleware("auth")->group(function ()
    {
        Route::get("/create", "create");
        Route::post("/", "store");
        Route::get("/{post}/edit", "edit");
        Route::put("/{post}", "update");
        Route::delete("/{post}", "destroy");
    });
});

Route::controller(AuthController::class)->prefix("auth")->group(function (){
    Route::post("/login", "login")->middleware("guest");
    Route::get("/logout", "logout")->middleware("auth");
});
