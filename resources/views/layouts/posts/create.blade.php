@extends("layouts.main")

@section("title", "Создание поста")
<div class="container d-flex justify-content-center">
    <div class="row mt-5 w-50">
        <div class="h2 text-center">Создание поста</div>
        <form class="d-flex flex-column m-auto" method="post" action="{{route("posts.store")}}">
            @csrf
            <div class="mb-3">
                <label for="title" class="form-label">Заголовок</label>
                <input type="text" class="@error("title") is-invalid @enderror form-control" id="title"
                       placeholder="Заголовок" name="title" value="{{old("title")}}" required>
                @error("title")
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="body">Текст поста</label>
                <textarea class="@error("body") is-invalid @enderror form-control post__textarea" placeholder="Текст поста" id="body"
                          name="body"

                >{{old("body")}}</textarea>

            </div>
            <button type="submit" class="w-50 m-auto btn btn-primary mb-3">Создать</button>
        </form>
    </div>
</div>

@section("content")
@endsection



