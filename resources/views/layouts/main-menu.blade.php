
<main-menu>
    <div class="logo"></div>
    <div class="search"></div>
    @auth
        <div>
            <a href="#">PROFILE</a>
        </div>
        <div class="logout">
            <a href="#"><img src="./img/logout.png" alt="logout"></a>
        </div>
    @endauth
    @guest
        <div class="sign-in">
            <a href="#">SIGN IN</a>    
        </div>
    @endguest
    {{-- <ul>
        <li><a href="#">Home</a></li>
        <li><a href="#">Movies</a></li>
        <li><a href="#">Series</a></li>
        <li><a href="#">Incoming</a></li>
        @auth
        <li><a href="#">Profile</a></li>    
        @endauth
        @guest
        <li><a href="#">SignIn</a></li>
        @endguest
    </ul> --}}
</main-menu>
<incoming-slider />
