<nav class="navbar fixed-top bg-body-tertiary">
    <div class="container-fluid">
        <div class="navbar-brand d-flex">
            <button class="hamburger btn btn-link">
                <span class="hamburger-inner"></span>
            </button>
            @section('breadcrumbs')
                <ol class="breadcrumb d-none d-sm-flex align-items-center">
                    @php
                        $segments = array_filter(explode('/', str_replace(route('hymer.dashboard'), '', Request::url())));
                        $url = route('hymer.dashboard');
                    @endphp
                    @if(count($segments) == 0)
                        <li class="active"><i class="hymer-boat"></i> {{ __('hymer::generic.dashboard') }}</li>
                    @else
                        <li class="active">
                            <a href="{{ route('hymer.dashboard')}}"><i class="hymer-boat"></i> {{ __('hymer::generic.dashboard') }}</a>
                        </li>
                        @foreach ($segments as $segment)
                            @php
                                $url .= '/' . $segment;
                                $breadcrumb = urldecode($segment);
                                $dataType = Hymer::model('DataType')->where('slug', $breadcrumb)->first();
                                if (!empty($dataType)) {
                                    $breadcrumb = $dataType->display_name_singular;
                                } else {
                                    $breadcrumb = ucfirst($breadcrumb);
                                }
                            @endphp
                            @if ($loop->last)
                                <li>{{ empty($dataType) ? __('hymer::generic.' . urldecode($segment)) : $breadcrumb }}</li>
                            @else
                                <li>
                                    <a href="{{ $url }}">{{ $breadcrumb }}</a>
                                </li>
                            @endif
                        @endforeach
                    @endif
                </ol>
            @show
        </div>
        <ul class="d-flex align-items-center navbar-right" style="height: 60px; list-style: none;">
            <li class="dropdown profile">
                <a href="#" class="dropdown-toggle text-end" data-bs-toggle="dropdown" role="button"
                   aria-expanded="false">
                    <img src="{{ $user_avatar }}" class="profile-img" alt="Avatar">
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li class="profile-img d-flex align-items-center">
                        <img src="{{ $user_avatar }}" class="profile-img" alt="Avatar">
                        <div class="profile-body">
                            <h5>{{ Auth::user()->name }}</h5>
                            <h6>{{ Auth::user()->email }}</h6>
                        </div>
                    </li>
                    <li class="divider"></li>
                    <?php $nav_items = config('hymer.dashboard.navbar_items'); ?>
                    @if(is_array($nav_items) && !empty($nav_items))
                    @foreach($nav_items as $name => $item)
                    <li {!! isset($item['classes']) && !empty($item['classes']) ? 'class="'.$item['classes'].'"' : '' !!}>
                        @if(isset($item['route']) && $item['route'] == 'hymer.logout')
                        <form action="{{ route('hymer.logout') }}" method="POST">
                            {{ csrf_field() }}
                            <div class="d-grid">
                                <button type="submit" class="btn btn-danger">
                                    @if(isset($item['icon_class']) && !empty($item['icon_class']))
                                        <i class="{!! $item['icon_class'] !!}"></i>
                                    @endif
                                    {{__($name)}}
                                </button>
                            </div>
                        </form>
                        @else
                        <a href="{{ isset($item['route']) && Route::has($item['route']) ? route($item['route']) : (isset($item['route']) ? $item['route'] : '#') }}" {!! isset($item['target_blank']) && $item['target_blank'] ? 'target="_blank"' : '' !!}>
                            @if(isset($item['icon_class']) && !empty($item['icon_class']))
                            <i class="{!! $item['icon_class'] !!}"></i>
                            @endif
                            {{__($name)}}
                        </a>
                        @endif
                    </li>
                    @endforeach
                    @endif
                </ul>
            </li>
        </ul>
    </div>
</nav>
