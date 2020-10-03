<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#006600" />

        <title>Imo Learning Centre</title>

        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        {{-- <link rel="stylesheet" href="https://unpkg.com/@popperjs/core@2"> --}} 

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css">
        <link  href="{{asset('css/app.css')}}" type="text/css" rel="stylesheet">
        <link  href="{{asset('css/bootstrap.min.css')}}" type="text/css" rel="stylesheet">
        <link  href="{{asset('css/now-ui-kit.css?v=1.3.0')}}" type="text/css" rel="stylesheet">
        <link rel="shortcut icon" href="/images/logo.jpg" type="image/x-icon">
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

  <!-- CSS Just for demo purpose, don't include it in your project -->
        <script>
            var tutor = localStorage.getItem('typeTutor')
            var user = localStorage.getItem('typeStudent')
            if (user !== null || tutor !== null) {
                window.onload = function() {
            inactivityTime(); 
            }
         
 var inactivityTime = function () {
    var time;
    document.onload = resetTimer;
    document.onmousemove = resetTimer;
    document.onmousedown = resetTimer; // touchscreen presses
    document.ontouchstart = resetTimer;
    document.onclick = resetTimer;     // touchpad clicks
    document.onkeypress = resetTimer;
    document.addEventListener('scroll', resetTimer, true);

    function logout() {
        Echo.leave('online-tutors')
        localStorage.removeItem('typeTutor')
        localStorage.removeItem('typeStudent')
        alert('You have been logged out due to inactivity');
        window.location.reload()
     
      
    }

    function resetTimer() {
        clearTimeout(time);
        time = setTimeout(logout, 900000)
        // 1000 milliseconds = 1 second
    }
};
}
        </script>
     
    </head>
    <body class="landing-page sidebar-collapse">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg bg-primary fixed-top navbar-transparent " color-on-scroll="400">
          <div class="container">
            <div class="dropdown button-dropdown">
              <a href="#pablo" class="dropdown-toggle" id="navbarDropdown" data-toggle="dropdown">
                <span class="button-bar"></span>
                <span class="button-bar"></span>
                <span class="button-bar"></span>
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-header">Dropdown header</a>
                <a class="dropdown-item" href="#">Action</a>
                <a class="dropdown-item" href="#">Another action</a>
                <a class="dropdown-item" href="#">Something else here</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#">Separated link</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#">One more separated link</a>
              </div>
            </div>
            <div class="navbar-translate">
              <a class="navbar-brand" href="" rel="tooltip" title=" E-Learning Api" data-placement="bottom" target="_blank">
              E-Learning Api
              </a>
              <button class="navbar-toggler navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-bar top-bar"></span>
                <span class="navbar-toggler-bar middle-bar"></span>
                <span class="navbar-toggler-bar bottom-bar"></span>
              </button>
            </div>
            <div class="collapse navbar-collapse justify-content-end" id="navigation" data-nav-image="{{asset('img/blurred-image-1.jpg')}}">
              <ul class="navbar-nav">
                <li class="nav-item">
                  <a class="nav-link" href="../index.html">Documentation</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="">Have an issue?</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" rel="tooltip" title="Follow us on Twitter" data-placement="bottom" href="https://twitter.com/CreativeTim" target="_blank">
                    <i class="fab fa-twitter"></i>
                    <p class="d-lg-none d-xl-none">Twitter</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" rel="tooltip" title="Like us on Facebook" data-placement="bottom" href="https://www.facebook.com/CreativeTim" target="_blank">
                    <i class="fab fa-facebook-square"></i>
                    <p class="d-lg-none d-xl-none">Facebook</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" rel="tooltip" title="Follow us on Instagram" data-placement="bottom" href="https://www.instagram.com/CreativeTimOfficial" target="_blank">
                    <i class="fab fa-instagram"></i>
                    <p class="d-lg-none d-xl-none">Instagram</p>
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </nav>
        <!-- End Navbar -->
        <div class="wrapper">
          <div class="page-header page-header-small">
            <div class="page-header-image" data-parallax="true" style="background-image: url('{{asset('img/bg6.jpg')}}');">
            </div>
            <div class="content-center">
              <div class="container">
                <h1 class="title">This is our great company.</h1>
                <div class="text-center">
                  <a href="#pablo" class="btn btn-primary btn-icon btn-round">
                    <i class="fab fa-facebook-square"></i>
                  </a>
                  <a href="#pablo" class="btn btn-primary btn-icon btn-round">
                    <i class="fab fa-twitter"></i>
                  </a>
                  <a href="#pablo" class="btn btn-primary btn-icon btn-round">
                    <i class="fab fa-google-plus"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>
          <div class="section section-about-us">
            <div class="container">
              <div class="row">
                <div class="col-md-8 ml-auto mr-auto text-center">
                  <h2 class="title">Who we are?</h2>
                  <h5 class="description">According to the National Oceanic and Atmospheric Administration, Ted, Scambos, NSIDClead scentist, puts the potentially record low maximum sea ice extent tihs year down to low ice extent in the Pacific and a late drop in ice extent in the Barents Sea.</h5>
                </div>
              </div>
              <div class="separator separator-primary"></div>
              <div class="section-story-overview">
                <div class="row">
                  <div class="col-md-6">
                    <div class="image-container image-left" style="background-image: url('{{asset('img/login.jpg')}}')">
                      <!-- First image on the left side -->
                      <p class="blockquote blockquote-primary">"Over the span of the satellite record, Arctic sea ice has been declining significantly, while sea ice in the Antarctichas increased very slightly"
                        <br>
                        <br>
                        <small>-NOAA</small>
                      </p>
                    </div>
                    <!-- Second image on the left side of the article -->
                    <div class="image-container" style="background-image: url('{{asset('img/bg3.jpg')}}')"></div>
                  </div>
                  <div class="col-md-5">
                    <!-- First image on the right side, above the article -->
                    <div class="image-container image-right" style="background-image: url('{{asset('img/bg1.jpg')}}')"></div>
                    <h3>So what does the new record for the lowest level of winter ice actually mean</h3>
                    <p>The Arctic Ocean freezes every winter and much of the sea-ice then thaws every summer, and that process will continue whatever happens with climate change. Even if the Arctic continues to be one of the fastest-warming regions of the world, it will always be plunged into bitterly cold polar dark every winter. And year-by-year, for all kinds of natural reasons, there’s huge variety of the state of the ice.
                    </p>
                    <p>
                      For a start, it does not automatically follow that a record amount of ice will melt this summer. More important for determining the size of the annual thaw is the state of the weather as the midnight sun approaches and temperatures rise. But over the more than 30 years of satellite records, scientists have observed a clear pattern of decline, decade-by-decade.
                    </p>
                    <p>The Arctic Ocean freezes every winter and much of the sea-ice then thaws every summer, and that process will continue whatever happens with climate change. Even if the Arctic continues to be one of the fastest-warming regions of the world, it will always be plunged into bitterly cold polar dark every winter. And year-by-year, for all kinds of natural reasons, there’s huge variety of the state of the ice.
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="section section-team text-center">
            <div class="container">
              <h2 class="title">Here is our team</h2>
              <div class="team">
                <div class="row">
                  <div class="col-md-4">
                    <div class="team-player">
                      <img src="{{asset('img/avatar.jpg')}}" alt="Thumbnail Image" class="rounded-circle img-fluid img-raised">
                      <h4 class="title">Romina Hadid</h4>
                      <p class="category text-primary">Model</p>
                      <p class="description">You can write here details about one of your team members. You can give more details about what they do. Feel free to add some
                        <a href="#">links</a> for people to be able to follow them outside the site.</p>
                      <a href="#pablo" class="btn btn-primary btn-icon btn-round"><i class="fab fa-twitter"></i></a>
                      <a href="#pablo" class="btn btn-primary btn-icon btn-round"><i class="fab fa-instagram"></i></a>
                      <a href="#pablo" class="btn btn-primary btn-icon btn-round"><i class="fab fa-facebook-square"></i></a>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="team-player">
                      <img src="{{asset('img/ryan.jpg')}}" alt="Thumbnail Image" class="rounded-circle img-fluid img-raised">
                      <h4 class="title">Ryan Tompson</h4>
                      <p class="category text-primary">Designer</p>
                      <p class="description">You can write here details about one of your team members. You can give more details about what they do. Feel free to add some
                        <a href="#">links</a> for people to be able to follow them outside the site.</p>
                      <a href="#pablo" class="btn btn-primary btn-icon btn-round"><i class="fab fa-twitter"></i></a>
                      <a href="#pablo" class="btn btn-primary btn-icon btn-round"><i class="fab fa-linkedin"></i></a>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="team-player">
                      <img src="{{asset('img/eva.jpg')}}" alt="Thumbnail Image" class="rounded-circle img-fluid img-raised">
                      <h4 class="title">Eva Jenner</h4>
                      <p class="category text-primary">Fashion</p>
                      <p class="description">You can write here details about one of your team members. You can give more details about what they do. Feel free to add some
                        <a href="#">links</a> for people to be able to follow them outside the site.</p>
                      <a href="#pablo" class="btn btn-primary btn-icon btn-round"><i class="fab fa-google-plus"></i></a>
                      <a href="#pablo" class="btn btn-primary btn-icon btn-round"><i class="fab fa-youtube"></i></a>
                      <a href="#pablo" class="btn btn-primary btn-icon btn-round"><i class="fab fa-twitter"></i></a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="section section-contact-us text-center">
            <div class="container">
              <h2 class="title">Want to work with us?</h2>
              <p class="description">Your project is very important to us.</p>
              <div class="row">
                <div class="col-lg-6 text-center col-md-8 ml-auto mr-auto">
                  <div class="input-group input-lg">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="now-ui-icons users_circle-08"></i>
                      </span>
                    </div>
                    <input type="text" class="form-control" placeholder="First Name...">
                  </div>
                  <div class="input-group input-lg">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="now-ui-icons ui-1_email-85"></i>
                      </span>
                    </div>
                    <input type="text" class="form-control" placeholder="Email...">
                  </div>
                  <div class="textarea-container">
                    <textarea class="form-control" name="name" rows="4" cols="80" placeholder="Type a message..."></textarea>
                  </div>
                  <div class="send-button">
                    <a href="#pablo" class="btn btn-primary btn-round btn-block btn-lg">Send Message</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
       
        </div>
        <!--   Core JS Files   -->
        <script src="{{asset('js/core/jquery.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('js/core/popper.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('js/core/bootstrap.min.js')}}" type="text/javascript"></script>
        <script src="{{asset('js/plugins/bootstrap-switch.js')}}" type="text/javascript"></script>
        <script src="{{asset('js/now-ui-kit.js?v=1.3.0')}}" type="text/javascript"></script>
      
       
      
      </body>
</html>
