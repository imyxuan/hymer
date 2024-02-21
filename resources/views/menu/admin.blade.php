<ol class="dd-list">

@foreach ($items as $item)

    <li class="dd-item" data-id="{{ $item->id }}">
        <div class="float-end item_actions">
            <div class="btn btn-sm btn-danger float-end delete" data-id="{{ $item->id }}">
                <i class="hymer-trash"></i> {{ __('hymer::generic.delete') }}
            </div>
            <div class="btn btn-sm btn-primary float-end edit"
                data-id="{{ $item->id }}"
                data-title="{{ $item->title }}"
                data-url="{{ $item->url }}"
                data-bs-target="{{ $item->target }}"
                data-icon_class="{{ $item->icon_class }}"
                data-color="{{ $item->color }}"
                data-route="{{ $item->route }}"
                data-parameters="{{ json_encode($item->parameters) }}"
            >
                <i class="hymer-edit"></i> {{ __('hymer::generic.edit') }}
            </div>
        </div>
        <div class="dd-handle">
            @if($options->isModelTranslatable)
                @include('hymer::multilingual.input-hidden', [
                    'isModelTranslatable' => true,
                    '_field_name'         => 'title'.$item->id,
                    '_field_trans'        => json_encode($item->getTranslationsOf('title'))
                ])
            @endif
            <span>{{ $item->title }}</span> <small class="url">{{ $item->link() }}</small>
        </div>
        @if(!$item->children->isEmpty())
            @include('hymer::menu.admin', ['items' => $item->children])
        @endif
    </li>

@endforeach

</ol>
