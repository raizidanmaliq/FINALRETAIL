<!-- jQuery -->
<script src="{{ asset('/back_assets/plugins/jquery/jquery.min.js') }}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('/back_assets/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('/back_assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- SweetAlert2-->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<!-- ChartJS -->
<script src="{{ asset('/back_assets/plugins/chart.js/Chart.min.js') }}"></script>
<!-- DataTables -->
<script src="{{ asset('/back_assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('/back_assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('/back_assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('/back_assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('/back_assets/plugins/select2/js/select2.full.min.js') }}"></script>
<!-- bs-custom-file-input -->
<script src="{{ asset('/back_assets/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
<!-- Sparkline -->
<script src="{{ asset('/back_assets/plugins/sparklines/sparkline.js') }}"></script>
<!-- JQVMap -->
<script src="{{ asset('/back_assets/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
<script src="{{ asset('/back_assets/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
<!-- jQuery Knob Chart -->
<script src="{{ asset('/back_assets/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
<!-- daterangepicker -->
<script src="{{ asset('/back_assets/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('/back_assets/plugins/daterangepicker/daterangepicker.js') }}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ asset('/back_assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<!-- Summernote -->
<script src="{{ asset('/back_assets/plugins/summernote/summernote-bs4.min.js') }}"></script>
<!-- overlayScrollbars -->
<script src="{{ asset('/back_assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('/back_assets/dist/js/adminlte.js') }}"></script>
<script src="{{ asset('back_assets/dist/js/script.js') }}"></script>
{{-- Validation --}}
<script src="{{ asset('back_assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('back_assets/plugins/jquery-validation/additional-methods.min.js') }}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $(function() {
    $.widget.bridge('uibutton', $.ui.button);
    $('.select2').select2({
      placeholder: $(this).data('placeholder') !== null ? $(this).data('placeholder') : '',
    });
    $('.select2-input').select2({
      tags: true,
    });
    $('.select2-multiple').select2({
      placeholder: $(this).data('placeholder'),
    });
    $('.select2-multiple-input').select2({
      placeholder: $(this).data('placeholder'),
      tags: true,
    });
    $('.data-tables').DataTable();
      bsCustomFileInput.init();
  });

  @if(session('success'))
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
      icon: 'success',
      title: '{{ session('success') }}'
    })
  @endif

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

    function numberFormatToRupiah(number, prefix) {
        var number_string = number.replace(/[^,\d]/g, '').toString(),
        split   = number_string.split(','),
        over    = split[0].length % 3,
        rupiah  = split[0].substr(0, over),
        thousand  = split[0].substr(over).match(/\d{3}/gi);

        if(thousand){
            separator = over ? '.' : '';
            rupiah += separator + thousand.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? rupiah : '');
    }
</script>
