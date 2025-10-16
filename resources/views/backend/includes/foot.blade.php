<!-- jQuery 3.7.1 -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

<!-- CoreUI and necessary plugins-->
{{--<script src="node_modules/@coreui/coreui/dist/js/coreui.bundle.min.js"></script>--}}
{{--<script src="node_modules/simplebar/dist/simplebar.min.js"></script>--}}
<script>
    const header = document.querySelector('header.header');

    document.addEventListener('scroll', () => {
        if (header) {
            header.classList.toggle('shadow-sm', document.documentElement.scrollTop > 0);
        }
    });

</script>
<!-- Plugins and scripts required by this view-->
{{--<script src="node_modules/chart.js/dist/chart.umd.js"></script>--}}
{{--<script src="node_modules/@coreui/chartjs/dist/js/coreui-chartjs.js"></script>--}}
{{--<script src="node_modules/@coreui/utils/dist/umd/index.js"></script>--}}
{{--<script src="js/main.js"></script>--}}

<!-- sweetalert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Common Scripts -->
@include('backend.includes.common_scripts')

<!-- Custom Scripts -->
@stack('scripts')