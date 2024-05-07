<?php

namespace App\Http\Controllers;

use App\DataTransferObjects\PostDto;
use App\Http\Requests\Post\PostCreateRequest;
use App\Http\Requests\Post\PostUpdateRequest;
use App\Services\Post\PostService;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{

    public function __construct(private readonly PostService $service)
    {
    }

    public function index()
    {
        //
    }

    public function create()
    {
        //
    }


    public function store(PostCreateRequest $request): void
    {
        $dto = PostDto::fromCreateRequest($request, Auth::id());
        $postDto = $this->service->create($dto);
        redirect(route("post.show", $postDto->id));
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(PostUpdateRequest $request, string $id): void
    {
        $dto = PostDto::fromUpdateRequest($request, $id, Auth::id());
        $postDto = $this->service->update($dto);
        redirect(route("post.show", $postDto->id));
    }

    public function destroy(string $id): void
    {
        $this->service->delete($id);
        redirect(route("index"));
    }
}
