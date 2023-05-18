

<!DOCTYPE html>
<html lang="en">

  @include('layouts.head')
<body class="vertical-layout" >
    <!-- Start Infobar Setting Sidebar -->
    <div class="infobar-settings-sidebar-overlay"></div>
    <!-- End Infobar Setting Sidebar -->
    <!-- Start Containerbar -->
    <div id="containerbar" >
        <!-- Start Leftbar -->
        <div class="leftbar">
            <!-- Start Sidebar -->
            @include('layouts.sidebar')
            <!-- End Sidebar -->
        </div>
        <!-- End Leftbar -->
        <!-- Start Rightbar -->
        <div class="rightbar">
            <!-- Start Topbar Mobile -->
            @include('layouts.mobiletop')
            <!-- Start Topbar -->
            <div class="topbar">
                <!-- Start row -->
                @include('layouts.nav')
                <!-- End row -->
            </div>
            <!-- End Topbar -->
            <div id="app">
                {{-- <div class="loader" v-show="!visible">
                    <div class="dot">L</div>
                    <div class="dot">O</div>
                    <div class="dot">A</div>
                    <div class="dot">D</div>
                    <div class="dot">I</div>
                    <div class="dot">N</div>
                    <div class="dot">G</div>
                    <div class="cogs">
                      <div class="cog cog0">
                        <div class="bar"></div>
                        <div class="bar"></div>
                        <div class="bar"></div>
                        <div class="bar"></div>
                      </div>
                      <div class="cog cog1">
                        <div class="bar"></div>
                        <div class="bar"></div>
                        <div class="bar"></div>
                        <div class="bar"></div>
                      </div>
                      <div class="cog cog2">
                        <div class="bar"></div>
                        <div class="bar"></div>
                        <div class="bar"></div>
                        <div class="bar"></div>
                      </div>
                    </div>
                  </div> --}}
            <router-view></router-view>

            <vue-progress-bar></vue-progress-bar>
            </div>
            <!-- Start Footerbar -->
            <div class="footerbar">
                <footer class="footer">
                    <p class="mb-0">© 2020 Tamilzorous Info Tech - All Rights Reserved.</p>
                </footer>
            </div>
            <!-- End Footerbar -->
        </div>
        <!-- End Rightbar -->
    </div>
    <!-- End Containerbar -->
    <!-- Start js -->

    <!-- End js -->
    @auth
    @php 
    $rolelist = DB::table('role')->where('id',Auth::user()->role)->first();

    $parseList = $rolelist->permission;
    // dd(Auth::user()->role);
    @endphp
    <input type="hidden" name="userRolePermission" id="userRolePermission" value="{{$parseList}}">
<script>
    window.user = @json(auth()->user());
    window.permission = JSON.parse(document.getElementById("userRolePermission").value);
</script>
@endauth


<script src="{{ mix('/js/app.js') }}"></script>
{{-- <script>
  if(navigator.onLine){
    alert('online');
   } else {
    alert('offline');
   }
  
  </script> --}}
</body>
@include('layouts.script')

</html>
