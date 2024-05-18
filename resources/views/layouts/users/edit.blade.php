@extends("layouts.main")

@section("title", __("main.edit_profile"))

@section("content")
    <div class="container d-flex justify-content-center text-break">
        <div class="row mt-5 w-50">
            <div class="h2 text-center mb-3">@lang("main.edit_profile")</div>
            <form class="d-flex flex-column mb-3" action="{{route("users.update", $userDto->id)}}" method="post">
                @csrf
                @method("PUT")
                <div class="mb-3">
                    <label for="username" class="form-label">@lang("main.new_username")</label>
                    <input type="text" class="form-control @error("username") is-invalid @enderror" id="username"
                           placeholder="@lang("main.new_username")" name="username"
                           value="{{old("username") ?? $userDto->username}}"
                    >
                    @error('username')
                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary m-auto">@lang("main.edit")</button>
            </form>
            <form class="d-flex flex-column mb-3" action="{{route("users.update", $userDto->id)}}" method="post">
                @csrf
                @method("PUT")
                <div class="mb-3">
                    <label for="email" class="form-label">@lang("main.new_email")</label>
                    <input type="email" class="form-control @error("email") is-invalid @enderror" id="email"
                           placeholder="@lang("main.new_email")" name="email"
                           value="{{old("email") ?? $userDto->email}}"
                    >
                    @error('email')
                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary m-auto">@lang("main.edit")</button>
            </form>
            <form class="d-flex flex-column mb-3" action="{{route("users.update", $userDto->id)}}" method="post">
                @csrf
                @method("PUT")
                <div class="mb-3">
                    <label for="password" class="form-label">@lang("main.new_password")</label>
                    <input type="password" class="form-control @error("password") is-invalid @enderror"
                           id="password"
                           placeholder="@lang("main.new_password")" name="password">
                    @error('password')
                    <div class="alert alert-danger mt-2">
                        @foreach($errors->get("password") as $error)
                            <div>{{ $error }}</div>
                        @endforeach</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="password_conf" class="form-label">@lang("main.confirm_new_password")</label>
                    <input type="password" class="form-control"
                           id="password_conf"
                           placeholder="@lang("main.confirm_new_password")" name="password_confirmation">
                </div>
                <button type="submit" class="btn btn-primary m-auto">@lang("main.edit")</button>
            </form>
            <form class="d-flex flex-column gap-3 mb-3" action="{{route("users.destroy", $userDto->id)}}" method="post">
                @csrf
                @method("DELETE")
                <button type="submit" class="btn btn-danger m-auto">@lang("main.delete_profile")</button>
                <a class="btn__home btn btn-info text-white m-auto" href="{{route("index")}}">@lang("main.home")</a>
            </form>

        </div>
    </div>
@endsection
