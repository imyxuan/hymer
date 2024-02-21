@if($artisan_output)
    <pre>
        <i class="close-output hymer-x">{{ __('hymer::compass.commands.clear_output') }}</i><span class="art_out">{{ __('hymer::compass.commands.command_output') }}:</span>{{ trim(trim($artisan_output,'"')) }}
    </pre>
@endif

@foreach($commands as $command)
    <div class="command" data-command="{{ $command->name }}">
        <code>php artisan {{ $command->name }}</code>
        <small>{{ $command->description }}</small><i class="hymer-terminal"></i>
        <form action="{{ route('hymer.compass.post') }}" class="cmd_form" method="POST">
            {{ csrf_field() }}
            <input type="text" name="args" autofocus class="form-control" placeholder="{{ __('hymer::compass.commands.additional_args') }}">
            <input type="submit" class="btn btn-primary float-end delete-confirm"
                    value="{{ __('hymer::compass.commands.run_command') }}">
            <input type="hidden" name="command" id="hidden_cmd" value="{{ $command->name }}">
        </form>
    </div>
@endforeach
