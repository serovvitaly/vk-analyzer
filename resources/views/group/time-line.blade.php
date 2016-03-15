@extends('layout')

@section('content')
    <script src="https://www.gstatic.com/charts/loader.js"></script>

    <script>
        google.charts.load('current', {packages: ['corechart', 'bar']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Время', 'Количество'],
                @foreach($average_arr as $time => $count)
                    ['{{ $time }}', {{ $count }}],
                @endforeach
            ]);

            var chart = new google.visualization.ColumnChart(document.getElementById('time-line-post'));

            var options = {
                title: 'Среднее время публикиции постов',
                hAxis: {
                    title: 'Время размещения',
                    format: 'H:mm'/*,
                    viewWindow: {
                        min: [7, 30, 0],
                        max: [17, 30, 0]
                    }*/
                },
                vAxis: {
                    title: 'Количество постов'
                }
            };
            chart.draw(data, options);
        }
    </script>

    <div id="time-line-post" style="width: 1000px; height: 400px;"></div>
@endsection