<?php

namespace App\Http\Controllers;

use App\DataTransferObjects\PostDto;
use App\Exceptions\ExistedEmailException;
use App\Http\Requests\Post\PostCreateRequest;
use App\Http\Requests\Post\PostUpdateRequest;
use App\Services\Post\PostService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{

    public function __construct(private readonly PostService $service)
    {
    }

    public function index()
    {
        return view("index");
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
        //
    }

    public function edit(string $id)
    {
        //
    }

    /**
     * @throws ExistedEmailException
     */
    public function update(PostUpdateRequest $request, string $id): RedirectResponse
    {
        $dto = PostDto::fromUpdateRequest($request, $id);
        $postDto = $this->service->update($dto);
        return redirect()->route("post.show", $postDto->id);
    }

    public function destroy(string $id): RedirectResponse
    {
        $this->service->delete($id);
        return redirect()->route("index");
    }
}
