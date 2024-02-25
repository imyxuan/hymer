@if (isset($isModelTranslatable) && $isModelTranslatable)
    <div class="language-selector mb-3">
        <div class="btn-group btn-group-sm" role="group" aria-label="Basic radio toggle button group">
            @foreach(config('hymer.multilingual.locales') as $lang)
                <input
                    type="radio"
                    class="btn-check"
                    name="i18n_selector"
                    id="{{$lang}}"
                    autocomplete="off"
                    {{ ($lang === config('hymer.multilingual.default')) ? "checked" : "" }}
                >
                <label class="btn btn-outline-primary" for="{{$lang}}">{{ strtoupper($lang) }}</label>
            @endforeach
        </div>
    </div>
    <div class="clear-both"></div>
@endif
