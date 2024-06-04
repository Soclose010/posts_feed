<?php

namespace App\Http\Controllers;

use App\DataTransferObjects\PostFilterDto;
use App\DataTransferObjects\PostDto;
use App\Http\Requests\Post\PostCreateRequest;
use App\Http\Requests\Post\PostFilterRequest;
use App\Http\Requests\Post\PostUpdateRequest;
use App\Services\Post\PostService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Spatie\FlareClient\Http\Exceptions\NotFound;

class PostController extends Controller
{

    public function __construct(private readonly PostService $service)
    {
    }

    public function index(PostFilterRequest $request)
    {
        $dto = PostFilterDto::fromFilterRequest($request);
        $postPaginator = $this->service->getPaginate(5, $dto);
        $userId = Auth::id();
        $current_locale = app()->getLocale();
        $locales = config('app.available_locales');
        return view("index", compact("postPaginator", "userId", "current_locale", "locales"));
    }

    public function create()
    {
        return view("layouts.posts.create");
    }


    public function store(PostCreateRequest $request): RedirectResponse
    {
        $dto = PostDto::fromCreateRequest($request, Auth::id());
        $postDto = $this->service->create($dto);
        return redirect()->route("posts.show", $postDto->id);
    }

    public function show(string $id)
    {
        try {
            $postDto = $this->service->getWithUsername($id);
        } catch (NotFound) {
            abort(404);
        }
        return view("layouts.posts.show", compact("postDto"));
    }

    public function edit(string $id)
    {
        try {
            $postDto = $this->service->get($id);
        } catch (NotFound) {
            abort(404);
        }
        Gate::authorize("edit", $postDto->user_id);
        return view("layouts.posts.edit", compact("postDto"));
    }

    public function update(PostUpdateRequest $request, string $id): RedirectResponse
    {
        $dto = PostDto::fromUpdateRequest($request, $id);
        try {
            $postDto = $this->service->update($dto);
        } catch (NotFound) {
            abort(404);
        }
        return redirect()->route("posts.show", $postDto->id);
    }

    public function destroy(string $id): RedirectResponse
    {
        try {
            $this->service->delete($id);
        } catch (NotFound) {
            abort(404);
        }
        return redirect()->back();
    }
}
