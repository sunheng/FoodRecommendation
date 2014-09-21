
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
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css">
  </head>

  <body>

    <div class="site-wrapper">

      <div class="site-wrapper-inner">

        <div class="cover-container">

          <div class="masthead clearfix">
            <div class="inner">
              <!-- <h3 class="masthead-brand">Rec Me Food</h3> -->
              <!-- <ul class="nav masthead-nav">
                <li class="active"><a href="#">Home</a></li>
                <li><a href="#">Features</a></li>
                <li><a href="#">Contact</a></li>
              </ul> -->
            </div>
          </div>

          <!--MAIN-->
          <div class="inner cover main">
            <h1 class="cover-heading question">"What would you recommend at...?"</h1>
            <p class="lead">
              <!-- <form method="post" action="php/sample.php"> -->
                <div class="col-md-7 dropdown">
                  <input type="text" name="term" id="dropdownMenu1" class="form-control search-bar restaurant dropdown-toggle" data-toggle="dropdown" placeholder="Enter a restaurant">
                  <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                    <li class="dropdownMessage" role="presentation"><a role="menuitem" tabindex="-1"><em>It may be a particular food</em></a></li>
                    <!-- <li role="presentation" class="divider"></li> -->
                    <!-- <li role="presentation"><a role="menuitem" tabindex="-1" href="#">Separated link</a></li> -->
                  </ul>
                </div>
                <div class="col-md-4">
                    <input type="text" name="location" class="form-control search-bar location" placeholder="Near">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-info btn-lg submit">
                      <span class="glyphicon glyphicon-search"></span> 
                    </button>
                </div>
              <!-- </form> -->
            </p>
          </div>


          <!--SECONDARY-->
          <div class="inner cover secondary">
            <h1 class="cover-heading topmeals">Top Meals</h1>
            <!-- <div class="chart">
              <div style="width: 40px;">4</div> <p>name </p>
              <div style="width: 80px;">8</div>
              <div style="width: 150px;">15</div>
              <div style="width: 160px;">16</div>
              <div style="width: 230px;">23</div>
              <div style="width: 420px;">42</div>
            </div> -->
            <div class="recData"></div>
            <button type="submit" class="btn btn-info btn-lg back">
              <span class="glyphicon glyphicon-arrow-left"></span> 
            </button>
          </div>

          <div id="loaderImg"><img src="img/ajax-loader.gif" alt=""/></div>

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
    <script src="js/jquery.transit.min.js" type="text/javascript"></script>
    <script src="http://d3js.org/d3.v3.min.js" charset="utf-8"></script>
    <script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
    <script>
      $(document).ready(function() {
        $('.secondary').hide();
        $('#loaderImg').hide();
        var city = geoplugin_city();
        var state = geoplugin_region();
        $('.location').val(city + ", " + state);

        var restaurantList = [];
        var restaurantNames = [];
        var restaurantURL = '';
        var restaurantReviews = '';

        $('.search-bar').on('keyup', function(e){
          e.preventDefault();
          $term = $('.restaurant').val();
          $location = $('.location').val();
          
          var xhr = $.ajax({
            url: 'php/yelp_api.php',
            type: 'post',
            data: {'term': $term, 'location': $location},
            success: function(data, status) {
              var restaurantList = [];
              $('.dropdownMessage').hide();
              $('li.options').remove();
              data = data.substring(data.indexOf('{'));
              var dataLines = data.split('\n');
              restaurantList = [];
              restaurantNames = [];
              for (var i = 0; i < dataLines.length - 1; i++) {
                var jsonFormat = JSON.parse(dataLines[i]);
                if (!jsonFormat.hasOwnProperty('error')) {
                  // console.log(jsonFormat);
                  restaurantList.push(jsonFormat);
                  restaurantNames.push(jsonFormat.name);
                  $('.dropdown-menu').append('<li class="options" data-url="' + jsonFormat.url +'" data-reviews="' + jsonFormat.review_count +'" role="presentation"><a role="menuitem" tabindex="-1">' + jsonFormat.name + '</a></li>');
                }
              }
              $('.options').click(function() {
                restaurantURL = $(this).data('url');
                restaurantReviews = $(this).data('reviews');
                $('.restaurant').val($(this).text());
              });
            }
          }); // end ajax call
          
        }); //end keyup
        var recData = {};
        $('.submit').click(function() {
          $('body').css('background-color', '#fff');
          $('.main').transition({ y: 600 });

          var aggregator = $.ajax({
            url: 'php/yelp_review_aggregator.php',
            type: 'post',
            data: {'url': restaurantURL, 'reviewCount': restaurantReviews},
            beforeSend: function() {
              $('#loaderImg').show();
            },
            success: function(data, status) {
                $('#loaderImg').hide();
                $('.secondary').fadeIn()
               .append('<a target="_blank" href="' + restaurantURL + '">' + restaurantURL + '</a>');
                // console.log(JSON.parse(data));
                console.log(data);
                recData = JSON.parse(data);
                var keys = Object.keys(recData);
                if (keys.length <= 0) {
                  $('.recData').append('<h4> Not enough data. </h4><br/>');
                } 
                keys.forEach(function(key, i){
                  if (i < 3) {
                    $('.recData').append('<h4>' + key.toUpperCase() + '</h4><br/>');
                  }
                });
            }
          }); // end ajax call
          $('.back').click(function() {
            $('body').css('background-color', '#f2ae72');
            $('.secondary').hide();
            $('.main').transition({ y: 0 });
          });
        });
      });
    </script>
  </body>
</html>
