<div class="sidebar">
    <!-- Start Logobar -->
    <div class="logobar">
        <a href="/dashboard" class="logo logo-large">
            <img src="{{asset('images/ISMT_Logo4.jpeg')}}" class="img-fluid" alt="logo">
            {{-- <img src="assets/images/logo.svg" class="img-fluid" alt="logo"> --}}
            {{-- <h4>ISMT TECH</h4> --}}
        </a>
        <a href="index.html" class="logo logo-small">
            <img src="{{asset('images/ISMT_Logo3.jpeg')}}" class="img-fluid" alt="logo">
        </a>
    </div>
    @php 
    $rolelist = DB::table('role')->where('id',Auth::user()->role)->first();
    
    $parseList = json_decode($rolelist->permission);
    // dd($parseList);
    @endphp
    
    <!-- End Logobar -->
    <!-- Start Navigationbar -->
    <div class="navigationbar">
        <ul class="vertical-menu">
            @if(in_array("dashboard",$parseList) != '')
            <li>
                <a href="/dashboard">
                    <i class="mdi mdi-desktop-mac-dashboard" style="font-size: 20px;color: #717c99;"></i><span>Dashboard</span>
                </a>
            </li>
            @endif
            @if(in_array("customer_list",$parseList) != '')
            <li>
                <a href="/userdetails">
                    <img src="{{asset('assets/images/svg-icon/ledger.png')}}" class="img-fluid" alt="userdetails"><span>Ledgers</span>
                </a>
            </li>
            @endif
            {{-- <li>
                <a href="/vcidetails">
                    <img src="assets/images/svg-icon/basic.svg" class="img-fluid" alt="dashboard"><span>VCI Details</span>
                </a>
            </li> --}}
            <li> 
                <a href="javaScript:void();">
                    <img src="{{asset('assets/images/svg-icon/yearns.png')}}" class="img-fluid" alt="dashboard"><span>Yarns</span><i class="feather icon-chevron-right pull-right"></i>
                </a> 
                <ul class="vertical-submenu">
                    @if(in_array("all_products_list",$parseList) != '')
                    <li><a href="/vcidetails">All</a></li>
                    @endif
                    @if(in_array("sold_products_list",$parseList) != '')
                    <li><a href="/sold">Sold</a></li>
                    @endif
                    @if(in_array("unsold_products_list",$parseList) != '')
                    <li><a href="/unsold">Unsold</a></li>
                    @endif
                </ul>
            </li>
            @if(in_array("subscription_list",$parseList) != '')
            <li>
                <a href="/subscription">
                    <img src="{{asset('assets/images/svg-icon/warp.png')}}" class="img-fluid" alt="dashboard"><span>Warp Detail</span>
                </a>
            </li>
            @endif
            @if(in_array("ecu_list",$parseList) != '')
            <li>
                <a href="/ecudetails">
                    <i class="mdi mdi-arrange-bring-forward" style="font-size: 20px;color: #717c99;"></i><span>Tari Twisting</span>
                </a> 
            </li>
            @endif
            {{-- <li> 
                <a href="javaScript:void();">
                    <i class="mdi mdi-bike" style="font-size: 20px;color: #717c99;"></i><span>Product</span><i class="feather icon-chevron-right pull-right"></i>
                </a> 
                <ul class="vertical-submenu">
                    @if(in_array("vehicle_details_list",$parseList) != '')
                    <li><a href="/vehicledetails">Vehicle Details</a></li>
                    @endif
                    @if(in_array("vehicle_mapping_list",$parseList) != '')
                    <li><a href="/vehiclemapping">Vehicle Mapping</a></li>
                    @endif
                </ul>
            </li> --}}
            @if(in_array("vehicle_details_list",$parseList) != '')
            <li>
                <a href="/vehicledetails">
                    <i class="mdi mdi-bike" style="font-size: 20px;color: #717c99;"></i><span>ITC Inward</span>
                </a>
            </li>
            @endif
            <li> 
                <a href="javaScript:void();">
                    <img src="{{asset('assets/images/svg-icon/widgets.svg')}}" class="img-fluid" alt="userdetails"><span>Top Work Bill</span><i class="feather icon-chevron-right pull-right"></i>
                </a> 
                <ul class="vertical-submenu">
                    @if(in_array("live_data_list",$parseList) != '')
                    <li><a href="/livedata">Live Parameters</a></li>
                    @endif
                    @if(in_array("write_data_list",$parseList) != '')
                    <li><a href="/writedata">Write Data</a></li>
                    @endif
                    @if(in_array("dtc_data_list",$parseList) != '')
                    <li><a href="/dtcdata">DTC</a></li>
                    @endif
                    @if(in_array("io_control_data_list",$parseList) != '')
                    <li><a href="iodata">I/O Control</a></li>
                    @endif
                    @if(in_array("routine_control_data_list",$parseList) != '')
                    <li><a href="routinedata">Routine Control</a></li>
                    @endif
                </ul>
            </li>
            @if(in_array("apk_management_list",$parseList) != '')
            <li>
                <a href="/apkmgmt">
                    <i class="mdi mdi-bike" style="font-size: 20px;color: #717c99;"></i><span>Contra</span>
                </a>
            </li>
            @endif
            @if(in_array("users_list",$parseList) != '')
            <li>
                <a href="/employee">
                    <img src="{{asset('assets/images/svg-icon/user.svg')}}" class="img-fluid" alt="userdetails"><span>Users</span>
                </a>
            </li>
            @endif 
            @if(in_array("complaint_list",$parseList) != '')
            <li>
                <a href="/complaints">
                    <i class="mdi mdi-folder-edit" style="font-size: 20px;color: #717c99;"></i><span>Complaints</span>
                </a>
            </li>
            @endif
            @if(in_array("hex_file_list",$parseList) != '')
            <li>
                <a href="/hexfile">
                    <i class="mdi mdi-file" style="font-size: 20px;color: #717c99;"></i><span>Wearving</span>
                </a>
            </li>
            @endif
            @if(in_array("ecu_flash_list",$parseList) != '')
            <li>
                <a href="/ecuflash">
                    <i class="mdi mdi-file" style="font-size: 20px;color: #717c99;"></i><span>Weaft Colours</span>
                </a>
            </li>
            @endif
            <li>
                <a href="javaScript:void();">
                    <img src="{{asset('assets/images/svg-icon/components.svg')}}" class="img-fluid" alt="userdetails"><span>Payment</span><i class="feather icon-chevron-right pull-right"></i>
                </a>
                <ul class="vertical-submenu">
                    @if(in_array("subscription_plan_list",$parseList) != '')
                    <li><a href="/subsplan">Subscription Plan</a></li>
                    @endif
                    @if(in_array("payment_mode_list",$parseList) != '')
                    <li><a href="/paymentmode">Payment Mode</a></li>
                    @endif
                    @if(in_array("vehicle_type_list",$parseList) != '')
                    <li><a href="/vehtype">Vehicle Type</a></li>
                    @endif
                    @if(in_array("vehicle_function_list",$parseList) != '')
                    <li><a href="/vehfunc">Vehicle Functions</a></li>
                    @endif
                </ul>
            </li>
            <li> 
                <a href="javaScript:void();">
                    <img src="{{asset('assets/images/svg-icon/settings.svg')}}" class="img-fluid" alt="dashboard"><span>Settings</span><i class="feather icon-chevron-right pull-right"></i>
                </a> 
                @if(in_array("role_list",$parseList) != '')
                <ul class="vertical-submenu">
                    <li><a href="/role">Role</a></li>
                    <li><a href="/healthteststructure">Health Test Structure</a></li>
                    <li><a href="/language">Language</a></li>
                </ul>
                @endif
            </li>
        </ul>
    </div>
    <!-- End Navigationbar -->
</div>