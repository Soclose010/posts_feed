<form class="w-fit d-flex flex-column m-auto" method="post" action="{{route("auth.tryLogin")}}">
    @csrf
    <div class="mb-3">
        <label for="email" class="form-label">@lang("main.email")</label>
        <input type="email" class="@error("login", "email") is-invalid @enderror form-control" id="email"
               placeholder="@lang("main.email")" name="email" value="{{old("email")}}" required>
        @error("email")
        <div class="alert alert-danger mt-2">{{ $message }}</div>
        @enderror
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">@lang("main.password")</label>
        <input type="password" class="@error("login", "password") is-invalid @enderror form-control" id="password"
               placeholder="@lang("main.password")" name="password" required>
        @error("password")
        <div class="alert alert-danger mt-2">{{ $message }}</div>
        @enderror
    </div>
    @error("login")
    <div
        class="alert alert-danger mt-2">@lang("main.login_error")</div>
    @enderror
    <button type="submit" class="w-50 m-auto btn btn-primary mb-3">@lang("main.login")</button>
    <a class="w-50 m-auto btn btn-info text-white text-break"
       href="{{route("users.create")}}">@lang("main.register")</a>
</form>
