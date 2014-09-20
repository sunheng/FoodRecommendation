
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title></title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/cover.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
  </head>

  <body>

    <div class="site-wrapper">

      <div class="site-wrapper-inner">

        <div class="cover-container">

          <div class="masthead clearfix">
            <div class="inner">
              <h3 class="masthead-brand">Rec Me Food</h3>
              <!-- <ul class="nav masthead-nav">
                <li class="active"><a href="#">Home</a></li>
                <li><a href="#">Features</a></li>
                <li><a href="#">Contact</a></li>
              </ul> -->
            </div>
          </div>

          <!--MAIN-->
          <div class="inner cover main">
            <h1 class="cover-heading">"What would you recommend at..."</h1>
            <p class="lead">
              <input type="text" class="form-control search-bar" placeholder="Enter Restaurant">
            <p class="lead">
              <a href="javascript:void(0)" class="search btn btn-lg btn-info">Search</a>
            </p>
          </div>


          <!--SECONDARY-->
          <div class="inner cover secondary">
            <h1 class="cover-heading">Second Page</h1>
          </div>

          <div class="mastfoot">
            <div class="inner">
              <!-- <p>Cover template for <a href="http://getbootstrap.com">Bootstrap</a>, by <a href="https://twitter.com/mdo">@mdo</a>.</p> -->
            </div>
          </div>

        </div>

      </div>

    </div>



    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script>
      $('.secondary').hide();
      $('.search').click(function() {
        $('.main').hide();
        $('.secondary').show();
      });
    </script>
  </body>
</html>
