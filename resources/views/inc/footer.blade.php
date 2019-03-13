    
    <script src="{{ asset('DataTables/jquery-3.3.1.js') }}"></script>
    <!-- <script src="{{ asset('template/vendor/jquery/jquery.min.js') }}"></script> -->

    <script src="{{ asset('js/select2.min.js') }}"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="{{ asset('template/vendor/bootstrap/js/bootstrap.min.js') }}"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="{{ asset('template/vendor/metisMenu/metisMenu.min.js') }}"></script>

    <!-- Morris Charts JavaScript -->
    <script src="{{ asset('template/vendor/raphael/raphael.min.js') }}"></script>
    <script src="{{ asset('template/vendor/morrisjs/morris.min.js') }}"></script>

    <script src="{{ asset('DataTables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('DataTables/dataTables.bootstrap.min.js') }}"></script>
    {{-- <script src="{{ asset('jquery-validation/additional-methods.min.js') }}"></script> --}}
    <script src="{{ asset('jquery-validation/jquery.validate.min.js') }}"></script>
    
    <script>
        $(function() {
			$('#datatables').DataTable();
		} );
		$(function() {
			$('#datatables2').DataTable();
		} );
		$(function() {
			$('#datatables3').DataTable();
        } );
        $(function() {
			$('#datatables4').DataTable();
        } );
    </script>  
    
    @yield('additional_script')
    
    <!-- Menu Toggle Script -->
    <script>
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
    </script>

</body>

</html>
