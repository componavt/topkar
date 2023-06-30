    <!-- JavaScripts -->
<script src="/js/jquery-3.1.0.min.js"></script>
<script src="/js/bootstrap.min.js"></script>

    @yield('footScriptExtra')

    <script type="text/javascript">
        $(document).ready(function(){
            $.ajaxSetup({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
            });
            @yield('jqueryFunc')
        });
    </script>
    
