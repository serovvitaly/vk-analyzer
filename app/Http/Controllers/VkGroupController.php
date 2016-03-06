<?php

namespace App\Http\Controllers;

use App\Models\VkGroupModel;
use Illuminate\Http\Request;

use App\Http\Requests;

class VkGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query  = $request->get('query');
        $source = $request->get('source');

        switch ($source) {
            case \App\Models\VkGroupModel::SOURCE_VK:

                $groups = [];

                return view('group.list-vk', ['groups' => $groups]);

            case \App\Models\VkGroupModel::SOURCE_DB:

                $groups = \App\Models\VkGroupModel::where('name', 'like', $query)->orWhere('id', '=', $query)->paginate();

                return view('group.list', ['groups' => $groups]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //VkGroupModel::create();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $url = 'https://api.vk.com/method/groups.getById?group_id='.$id.'&v=5.45&fields=description,members_count';

        $response = file_get_contents($url);

        $json = json_decode($response);

        if (property_exists($json, 'error')) {

            return $json->error->error_msg;
        }

        $group = $json->response[0];

        $group_posts = self::getWall($group->id);

        $group->posts_count = $group_posts->count;

        return view('group.info', ['group' => $group]);
    }

    public static function getWall($owner_id)
    {
        $url = 'https://api.vk.com/method/wall.get?owner_id=-'.$owner_id.'&v=5.45';

        $response = file_get_contents($url);

        $json = json_decode($response);

        if (property_exists($json, 'error')) {

            throw new \VkApiException($json->error->error_msg, $json->error->error_code);
        }

        return $json->response;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
