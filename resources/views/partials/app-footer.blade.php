<footer class="app-footer">
    <div class="site-footer-right">
        {!! __('hymer::theme.footer_copyright') !!} <a href="https://imyxuan.site" target="_blank">Mac</a>
        @php $version = Hymer::getVersion(); @endphp
        @if (!empty($version))
            - {{ $version }}
        @endif
    </div>
</footer>
