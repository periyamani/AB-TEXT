<div class="row align-items-center">
    <!-- Start col -->
    <div class="col-md-12 align-self-center">
        <div class="togglebar">
            <ul class="list-inline mb-0">
                <li class="list-inline-item">
                    <div class="menubar">
                        <a class="menu-hamburger" href="javascript:void();">
                            <img src="{{asset('assets/images/svg-icon/collapse.svg')}}" class="img-fluid menu-hamburger-collapse" alt="collapse">
                            <img src="{{asset('assets/images/svg-icon/close.svg')}}" class="img-fluid menu-hamburger-close" alt="close">
                        </a>
                    </div>
                </li>
                <!-- <li class="list-inline-item">
                    <div class="searchbar">
                        <form>
                            <div class="input-group">
                                <input type="search" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="button-addon2">
                                <div class="input-group-append">
                                    <button class="btn" type="submit" id="button-addon2"><img src="{{asset('assets/images/svg-icon/search.svg')}}" class="img-fluid" alt="search"></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </li> -->
            </ul>
        </div>
        <div class="infobar">
            <ul class="list-inline mb-0">
                
                <li class="list-inline-item" style="margin-top: 13px;">
                    <div class="profilebar">
                        <div class="dropdown">
                            @php $languageName = DB::table('language')->where('id',Auth::user()->language)->first(); @endphp
                            <a class="dropdown-toggle" href="#" role="button" id="profilelink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="{{asset('assets/images/translate.png')}}" class="img-fluid" alt="profile"><span style="color: #263a5b !important;"> @if($languageName && $languageName->name){{$languageName->name}} @else - @endif</span></a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="profilelink">
                                @php $allLanguage = DB::table('language')->where('status','1')->get(); @endphp
                                
                                <div class="userbox">
                                    <ul class="list-unstyled mb-0">
                                        @if(count($allLanguage) > 0)
                                        @foreach($allLanguage as $lang)
                                        <li class="media dropdown-item">
                                            <a href="#" onclick="setLang({{$lang->id}},{{Auth::user()->id}})" class="profile-icon">{{$lang->name}}</a>
                                        </li>
                                        @endforeach
                                        @else
                                        <li class="media dropdown-item">
                                            <a href="#" class="profile-icon">-</a>
                                        </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>

                
                <li class="list-inline-item" style="margin-top: 13px;">
                    <div class="profilebar">
                        <div class="dropdown">
                            <a class="dropdown-toggle" href="#" role="button" id="profilelink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="{{asset('assets/images/users/profile.svg')}}" class="img-fluid" alt="profile"><span class="feather icon-chevron-down live-icon"></span></a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="profilelink">
                                <div class="dropdown-item">
                                    <div class="profilename">
                                        <h5>{{Auth::user()->name}}</h5>
                                        <span>{{Auth::user()->email}}</span>
                                    </div>
                                </div>
                                <div class="userbox">
                                    <ul class="list-unstyled mb-0">
                                        <li class="media dropdown-item">
                                            <a href="#" class="profile-icon"><img src="{{asset('assets/images/svg-icon/user.svg')}}" class="img-fluid" alt="user">My Profile</a>
                                        </li>
                                        {{-- <li class="media dropdown-item">
                                            <a href="#" class="profile-icon"><img src="assets/images/svg-icon/email.svg" class="img-fluid" alt="email">Email</a>
                                        </li> --}}
                                        <li class="media dropdown-item">
                                            <a href="{{ route('logout') }}"  class="profile-icon"
                                            onclick="event.preventDefault();
                                                          document.getElementById('logout-form').submit();">
                                             <img src="{{asset('assets/images/svg-icon/logout.svg')}}" class="img-fluid" alt="logout"> {{ __('Logout') }}
                                         </a>
            
                                         <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                             @csrf
                                         </form>
                                        </li>
                                        {{-- <li class="media dropdown-item">
                                            <a href="#"><img src="assets/images/svg-icon/logout.svg" class="img-fluid" alt="logout">Logout</a>
                                        </li> --}}
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <!-- End col -->
</div>

<script>
    function setLang(id,userid){
        $.ajax({
            url: "api/setLang",
            type: 'post',
            data : {id:id,user:userid},
            success: function(data){
                if(data.data && data.data.id){
                    window.location.reload();
                }
            }
            });
    }
</script>