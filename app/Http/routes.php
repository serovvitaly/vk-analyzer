<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

function get_group_by_gname($gname){

    if ( empty($gname) ) return null;

    $url = 'https://api.vk.com/method/groups.getById?group_id='.$gname.'&v=5.45';

    $response = json_decode( file_get_contents($url) );

    if ( ! property_exists($response, 'response') ) {

        return null;
    }

    return $response->response[0];

}

Route::get('/tm/{group_id}', function ($group_id) {

    $grouping_size = 60 * 10; // Группировка по времени, в секундах

    $dates_arr = \DB::table('vk_posts')->select('date')->where('owner_id', '=', $group_id)->get();

    if ( empty($dates_arr) ) {
        return view('group.time-line', ['average_arr' => []]);
    }

    $by_days_arr = [];

    foreach ($dates_arr as $date) {

        $time = strtotime($date->date);

        $y_m_d = date('Y-m-d', $time);

        $start_day_time = strtotime($y_m_d . ' 00:00:00');

        $seconds_from_day_start = $time - $start_day_time;

        $block_number = ceil($seconds_from_day_start / $grouping_size);

        $h_i_s = date('H:i:s', $start_day_time + $block_number * $grouping_size);

        if ( ! array_key_exists($y_m_d, $by_days_arr) or ! array_key_exists($h_i_s, $by_days_arr[$y_m_d]) ) {
            $by_days_arr[$y_m_d][$h_i_s] = 0;
        }

        $by_days_arr[$y_m_d][$h_i_s]++;
    }

    $average_arr = [];

    for ($tm = strtotime(date('Y-m-d 00:00:00')); $tm <= strtotime(date('Y-m-d 23:59:59')); $tm += $grouping_size) {
        $h_i_s = date('H:i:s', $tm);
        $average_arr[$h_i_s] = 0;
    }

    foreach ($by_days_arr as $y_m_d => $h_i_s_counts_arr) {

        foreach ($h_i_s_counts_arr as $h_i_s => $count) {

            if ( ! array_key_exists($h_i_s, $average_arr) ) {
                continue;
            }

            $average_arr[$h_i_s] += $count;
        }
    }

    ksort($average_arr);

    return view('group.time-line', ['average_arr' => $average_arr]);
});

Route::get('/phpinfo', function () {
    phpinfo();
});

Route::get('/test2', function (Illuminate\Http\Request $request) {

    dispatch( new \App\Jobs\VkApi\GetPostsForPullComments );

    return;


    $sphinx = new \App\Services\Sphinx\SphinxClient;

    $sphinx->SetServer('localhost', 9312);

    $result = $sphinx->Query('мультфильм', 'test1');

    return $result;
});

Route::get('/test', function (Illuminate\Http\Request $request) {

    $group_id = $request->get('gid');

    if ( ! $group_id ) {

        $group_id = $request->get('gname');
    }

    $group = get_group_by_gname($group_id);

    if ( ! $group ) {

        return 'Не найдена группа';
    }

    $url = 'https://api.vk.com/method/wall.get?owner_id=-'.$group->id.'&v=5.45&count=100&extended=1&fields=description,members_count';

    $items_arr = json_decode(file_get_contents($url))->response->items;
//return $items_arr;
    $data = [];

    $reposts_counts_arr = [];
    $likes_counts_arr = [];
    $comments_counts_arr = [];

    $posts_arr = [];

    $reposts_ids_arr = [];
    $likes_ids_arr = [];
    $comments_ids_arr = [];

    foreach ($items_arr as $item) {

        $id         = (int) $item->id;
        $comments   = (int) $item->comments->count;
        $likes      = (int) $item->likes->count;
        $reposts    = (int) $item->reposts->count;

        $posts_arr[$id] = $item;

        if (! array_key_exists($reposts, $reposts_counts_arr)) {
            $reposts_counts_arr[$reposts]['count'] = 1;
        } else {
            $reposts_counts_arr[$reposts]['count']++;
        }
        $reposts_ids_arr[$reposts][] = $id;

        if (! array_key_exists($likes, $likes_counts_arr)) {
            $likes_counts_arr[$likes]['count'] = 1;
        } else {
            $likes_counts_arr[$likes]['count']++;
        }
        $likes_ids_arr[$likes][] = $id;

        if (! array_key_exists($comments, $comments_counts_arr)) {
            $comments_counts_arr[$comments]['count'] = 1;
        } else {
            $comments_counts_arr[$comments]['count']++;
        }
        $comments_ids_arr[$comments][] = $id;
    }

    $reposts_max_count  = max( array_keys($reposts_counts_arr) );
    $likes_max_count    = max( array_keys($reposts_counts_arr) );
    $comments_max_count = max( array_keys($reposts_counts_arr) );

    $total_max_count = max($reposts_max_count, $likes_max_count, $comments_max_count);

    for ($i = 1; $i <= $total_max_count; $i++) {

        $comments   = 0;
        $likes      = 0;
        $reposts    = 0;

        if (array_key_exists($i, $reposts_counts_arr))  $reposts    = $reposts_counts_arr[$i]['count'];
        if (array_key_exists($i, $likes_counts_arr))    $likes      = $likes_counts_arr[$i]['count'];
        if (array_key_exists($i, $comments_counts_arr)) $comments   = $comments_counts_arr[$i]['count'];

        $data[$i] = (object) [
            'num'       => $i,
            'reposts'   => $reposts,
            'likes'     => $likes,
            'comments'  => $comments,
        ];
    }

    return view('go', [
        'data'              => $data,
        'group_name'        => $group->name,
        'reposts_ids_arr'   => $reposts_ids_arr,
        'likes_ids_arr'     => $likes_ids_arr,
        'comments_ids_arr'  => $comments_ids_arr,
        'posts_arr'         => $posts_arr,
    ]);

});

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    //
});

Route::resource('vk-group', 'VkGroupController');