@extends('hymer::master')
@section('css')
<style>
    .small-box {
        border-radius: .25rem;
        box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
        display: block;
        margin-bottom: 20px;
        position: relative;
    }
    .small-box > .inner {
        padding: 10px;
    }
    .small-box h3 {
        font-size: 2.2rem;
        font-weight: 700;
        margin: 0 0 10px;
        padding: 0;
        white-space: nowrap;
    }
    .small-box p {
        font-size: 1rem;
    }
    .small-box .icon {
        color: rgba(0, 0, 0, .15);
    }
    .small-box .icon > i {
        font-size: 70px;
        line-height: 70px;
        position: absolute;
        right: 15px;
        top: 15px;
        transition: transform .3s linear;
    }
    .small-box:hover .icon > i {
        transform: scale(1.1);
    }
    .small-box > .small-box-footer {
        background-color: rgba(0, 0, 0, .1);
        color: rgba(255, 255, 255, .8);
        display: block;
        padding: 3px 0;
        position: relative;
        text-align: center;
        text-decoration: none;
        z-index: 10;
    }

    .small-box {
        color: #fff;
    }

    .small-box > .small-box-footer {
        color: #fff;
    }
</style>
@stop
@section('content')
    <div class="page-content">
        @include('hymer::alerts')
        @include('hymer::dimmers')
        <div class="row">
            @foreach($dataTypes as $dataType)
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $dataType->count }}</h3>
                        <p>{{ $dataType->display_name_plural }}</p>
                    </div>
                    <div class="icon">
                        <i class="{{ $dataType->icon }}"></i>
                    </div>
                    <a href="{{ route('hymer.' . $dataType->slug . '.index') }}" class="small-box-footer">
                        {{ __('hymer::generic.more_info') }}
                        <i class="hymer-double-right"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
@stop

@section('javascript')

@stop
