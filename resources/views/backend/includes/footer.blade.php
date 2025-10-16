<footer class="footer px-4">
    <div>
        <a href="{{ $configurations['footer_copyright_url'] ?? env('COPYRIGHT_URL','/') }}" target="_blank">
            {{ $configurations['footer_copyright_title'] ?? env('COPYRIGHT_TITLE', 'Laravel') }}
        </a> &copy;
        <script>
            document.write(new Date().getFullYear());
        </script> - All Rights Reserved.
    </div>
    <div class="ms-auto">Developed by&nbsp;<a href="{{ url(env('DEVELOPER_URL','/')) }}" target="_blank">{{
            env('DEVELOPER','Laravel') }}</a></div>
</footer>