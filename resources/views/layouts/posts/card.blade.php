<div class="post__card d-flex border border-secondary border-2 rounded mb-4 p-2">
    <div class="d-flex justify-content-center flex-column gap-2 w-100 px-3">
        <div class="text-center">
            <a class="h2 text-decoration-none link-secondary"
               href="{{route("posts.show", $postDto->id)}}">{{$postDto->title}}</a>
        </div>
        <div class="text-left">
            <span class="fw-bold">Автор: <a class="text-left text-decoration-none link-secondary fw-normal"
                                            href="{{route("users.show", $postDto->user_id)}}">
                {{$username}}
            </a>
            </span>

        </div>
        <div class="text-center text-truncate">{{$postDto->body}}</div>
        <div class="text-left"><span
                class="fw-bold">Дата создания: </span>{{$postDto->created_at->toDateString()}} {{$postDto->created_at->toTimeString()}}
        </div>
        <div class="text-left"><span
                class="fw-bold">Дата последнего изменения: </span>{{$postDto->updated_at->toDateString()}} {{$postDto->updated_at->toTimeString()}}
        </div>
        @if($canEdit)
            <div class="post__card-buttons d-flex justify-content-center gap-4">
                <div>
                    <a class="btn btn-success text-white" href="{{route("posts.edit", $postDto->id)}}">Редактировать</a>
                </div>
                <form action="{{route("posts.destroy", $postDto->id)}}" method="post">
                    @csrf
                    @method("DELETE")
                    <button class="btn btn-danger text-white" type="submit">Удалить</button>
                </form>
            </div>
        @endif
    </div>
</div>
