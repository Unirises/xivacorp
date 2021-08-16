<div class="copyright text-center text-muted">
    @if(Route::is('register') || Route::is('login') || Route::is('password.*'))
    <h3 style="color: white">Powered By:</h3>
    <img src="{{ asset('argon') }}/img/brand/vavs.png" style="max-width: 25%" alt=""><br><br>
    @else
    <h3 style="color: black">Powered By:</h3>
    <img src="{{ asset('argon') }}/img/brand/logoonlypng.png" style="max-width: 25%" alt=""><br><br>
    @endif
    <div class="d-flex justify-content-center" style="height: 50px">
    <img src="{{ asset('argon') }}/img/brand/FDA.jpg" style="height: 100%" alt=""><br>
        <img src="{{ asset('argon') }}/img/brand/DOH.jpg" style="height: 100%" alt=""><br>
        <img src="{{ asset('argon') }}/img/brand/BNI.jpg" style="height: 100%" alt=""><br>
    </div>  

    <br>
    &copy; {{ now()->year }} <a href="https://xivahealth.io" class="font-weight-bold ml-1" target="_blank">xivahealth</a>
</div>