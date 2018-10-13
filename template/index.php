<html>
    <head>
        <title>Longhorn PHP 2019</title>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.3/jquery.min.js"></script>
        <script>
        $(function() {
            var done = false;
            var count = 0;
            var max = 24;

            function getName() {
                $.getJSON('/rand', function(data) {
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
        </script>
        <style>
        div.winner {
            background-color: #daeddc;
            border: 1px solid #92e89a;
        }
        </style>
    </head>
    <body>
        <div style="text-align: center">
            <img style="margin-top: 7%" src="https://www.longhornphp.com/wp-content/uploads/2018/10/dark-purple-962x1024.png" height="50%"/>
            <div style="height:100px">&nbsp;</div>
            <div style="font-size:60px;padding:10px;font-family:arial" id="name-div"></div>
        </div>
    </body>
</html>
