<div class="locale__change d-flex flex-column align-items-center">
    @foreach($locales as $locale_name => $available_locale)
        @if($available_locale === $current_locale)
            <span class="ml-2 mr-2 text-gray-700">{{ $locale_name }}</span>
        @else
            <a href="{{route("locale", $available_locale)}}">
                <span>{{ $locale_name }}</span>
            </a>
        @endif
    @endforeach
</div>
