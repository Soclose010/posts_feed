<div class="user__card d-flex flex-column gap-3 border border-secondary border-2 rounded p-3 text-break">
    <div class="h3 text-center">@lang("main.profile")</div>
    <div><span class="fw-bold">@lang("main.username"): </span> {{$userDto->username}}</div>
    @if($canEdit)
        <div><span class="fw-bold">@lang("main.email"): </span> {{$userDto->email}}</div>
    @endif

    <div class="user__card-buttons d-flex gap-2 justify-content-center align-items-center">
        <a class="btn btn-info text-white" href="{{route("index")}}">@lang("main.home")</a>
        @if($canEdit)
            <a class="btn btn-success text-white" href="{{route("users.edit", $id)}}">@lang("main.edit")</a>
            <form class="" action="{{route("auth.logout")}}" method="post">
                @csrf
                <button class="btn btn-danger" type="submit">@lang("main.logout")</button>
            </form>
        @endif
    </div>

</div>
