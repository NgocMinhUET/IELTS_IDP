<script src="{{ asset('build/vendors/popper/popper.min.js') }}"></script>
<script src="{{ asset('build/vendors/bootstrap/bootstrap.min.js') }}"></script>
<script src="{{ asset('build/vendors/anchorjs/anchor.min.js') }}"></script>
<script src="{{ asset('build/vendors/is/is.min.js') }}"></script>
<script src="{{ asset('build/vendors/fontawesome/all.min.js') }}"></script>
<script src="{{ asset('build/vendors/lodash/lodash.min.js') }}"></script>
<script src="{{ asset('build/vendors/list.js/list.min.js') }}"></script>
<script src="{{ asset('build/vendors/feather-icons/feather.min.js') }}"></script>
<script src="{{ asset('build/vendors/dayjs/dayjs.min.js') }}"></script>
<script src="{{ asset('build/assets/js/phoenix.js') }}"></script>
<script
        src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
        crossorigin="anonymous"></script>

<script>
    var navbarTopStyle = window.config.config.phoenixNavbarTopStyle;
    var navbarTop = document.querySelector('.navbar-top');
    if (navbarTopStyle === 'darker') {
        navbarTop.setAttribute('data-navbar-appearance', 'darker');
    }

    var navbarVerticalStyle = window.config.config.phoenixNavbarVerticalStyle;
    var navbarVertical = document.querySelector('.navbar-vertical');
    if (navbarVertical && navbarVerticalStyle === 'darker') {
        navbarVertical.setAttribute('data-navbar-appearance', 'darker');
    }
</script>