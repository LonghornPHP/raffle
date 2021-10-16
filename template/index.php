<html>
    <head>
        <title>Longhorn PHP 2021</title>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.3/jquery.min.js"></script>
        <script>
          const urlParams = new URLSearchParams(window.location.search);
          const inPersonOnly = urlParams.get('in_person_only');

          if (inPersonOnly === '1' || inPersonOnly === '0') {
            $(function() {
              var count = 0;
              var max = 24;

              function getName() {
                $.getJSON('/rand?in_person_only=' + inPersonOnly, function(data) {
                  $('#name-div').html(data.name);

                  if (count == max) {
                    console.log('stopping: '+data.name);
                    stopFetch();
                    saveName(data.name);
                    $('#name-div').addClass('winner');
                  }
                });
              }

              var fetch = setInterval(function() {
                getName(); count++;
              }, 170);
              function stopFetch() {
                clearInterval(fetch);
              }
              function saveName(name) {
                $.ajax({
                  url: '/name',
                  data: {name: name},
                  type: 'POST',
                  success: function(data) {
                    console.log(data);
                  }
                })
              }
            });
          }
        </script>
        <style>
        div.winner {
            background-color: #daeddc;
            border: 1px solid #92e89a;
        }
        </style>
    </head>
    <body>
        <a href="?in_person_only=0">Everyone</a>
        <a style="margin-left: 2em" href="?in_person_only=1">In-Person Only</a>
        <div style="text-align: center">
            <img style="margin-top: 7%" src="https://www.longhornphp.com/img/logo/dark-purple-horizontal.png" width="90%"/>
            <div style="height:100px">&nbsp;</div>
            <div style="font-size:60px;padding:10px;font-family:arial" id="name-div"></div>
        </div>
    </body>
</html>
