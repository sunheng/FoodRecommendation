
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
              <form method="post" action="php/sample.php">
                <div class="col-md-7">
                  <input type="text" name="term" class="form-control search-bar restaurant" placeholder="Enter a restaurant">
                </div>
                <div class="col-md-4">
                    <input type="text" name="location" class="form-control search-bar location" placeholder="Near">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-info btn-lg submit">
                      <span class="glyphicon glyphicon-search"></span> 
                    </button>
                </div>
              </form>
            </p>
            <p class="lead test">
              safd
            </p>
          </div>


          <!--SECONDARY-->
          <!-- <div class="inner cover secondary">
            <h1 class="cover-heading">Second Page</h1>
          </div> -->

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
    <script language="JavaScript" src="http://www.geoplugin.net/javascript.gp" type="text/javascript"></script>
    <script>
      $(document).ready(function() {
        var city = geoplugin_city();
        var state = geoplugin_region();
        $('.location').val(city + ", " + state);

        $('.submit').on('click', function(e){
          e.preventDefault();

        });
      });
    </script>
  </body>
</html>
