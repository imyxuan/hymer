@if (isset($isModelTranslatable) && $isModelTranslatable)
    <div class="language-selector">
        <div class="btn-group btn-group-sm" role="group" data-bs-toggle="buttons">
            @foreach(config('hymer.multilingual.locales') as $lang)
                <div class="form-check form-switch">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        id="{{$lang}}"
                        {{ ($lang === config('hymer.multilingual.default')) ? " checked" : "" }}
                    >
                    <label class="form-check-label" for="flexSwitchCheckDefault">{{ strtoupper($lang) }}</label>
                </div>
            @endforeach
        </div>
    </div>
@endif
