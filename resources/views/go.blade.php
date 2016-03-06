<html>
<head>
    <script src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['bar']});
        google.charts.setOnLoadCallback(drawChart);

        var reposts_ids_arr = {!! json_encode($reposts_ids_arr) !!};
        var likes_ids_arr = {!! json_encode($likes_ids_arr) !!};
        var comments_ids_arr = {!! json_encode($comments_ids_arr) !!};

        var posts_arr = {!! json_encode($posts_arr) !!};

        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Количество постов', 'Репосты', 'Лайки', 'Комменты'],
        @foreach($data as $item)
                ['{{ $item->num }}', {{ $item->reposts }}, {{ $item->likes }}, {{ $item->comments }}],
        @endforeach
            ]);

            var options = {
                chart: {
                    title: '{!! $group_name !!}',
                    subtitle: 'Sales, Expenses, and Profit: 2014-2017',
                }
            };

            var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

            chart.draw(data, options);

            google.visualization.events.addListener(chart, 'select', function(e){
                var select = chart.getSelection()[0];

                var ids;

                switch (select.column) {
                    case 1:
                        ids = reposts_ids_arr[select.row + 1];
                        break;
                    case 2:
                        ids = likes_ids_arr[select.row + 1];
                        break;
                    case 3:
                        ids = comments_ids_arr[select.row + 1];
                        break;
                }

                $('#list').html('');

                $.each(ids, function(index, id){

                    var post = posts_arr[id];

                    var postEl = $('<p><a target="_blank" href="https://vk.com/public'+post.from_id.toString().substr(1)+'?w=wall'+post.from_id+'_'+post.id+'">'+post.text+'</a><br>Репосты: '+post.reposts.count+'<br>Лайки: '+post.likes.count+'<br>Комменты: '+post.comments.count+'</p>');

                    $('#list').append(postEl);
                });
            });
        }
    </script>
</head>
<body>
<div id="columnchart_material" style="width: 1500px; height: 700px;"></div>
<div id="list"></div>
</body>
</html>