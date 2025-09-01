<!-- jQuery -->
<script src="{{ asset('/back_assets/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('/back_assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('/back_assets/dist/js/adminlte.min.js') }}"></script>
<!-- SweetAlert2-->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

<script>
    @if(session('error'))
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true,
            onOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })

        Toast.fire({
            icon: 'error',
            title: '{{ session('error') }}'
        })
    @endif
</script>