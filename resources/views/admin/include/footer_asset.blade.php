<!-- Mainly scripts -->
<script>
    var base_url = "{{ url('/') }}" + '/';
</script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>
<script src="{{ asset('admin-assets/js/plugins/metisMenu/jquery.metisMenu.js') }}"></script>
<script src="{{ asset('admin-assets/js/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>

<!-- Custom and plugin javascript -->
<script src="{{ asset('admin-assets/js/inspinia.js') }}"></script>
<script src="{{ asset('admin-assets/js/plugins/pace/pace.min.js') }}"></script>
<!-- basic form  -->
<script src="{{ asset('admin-assets/js/plugins/iCheck/icheck.min.js') }}"></script>
<script>
    $(document).ready(function () {
        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
        });
    });
</script>

<!-- basic form    -->


<!-- Jasny -->
<script src="{{ asset('admin-assets/js/plugins/jasny/jasny-bootstrap.min.js')}}"></script>

<!-- every page will have separate vue script and it will be pushed dynamically

for more infromation check laravel docs   -->

<!-- select 2 -->
<!-- Select2 -->
<script src="{{ asset('admin-assets/js/plugins/select2/select2.full.min.js') }}"></script>
<!-- select 2 -->
@stack('script')
