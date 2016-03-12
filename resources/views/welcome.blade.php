@extends('layout')

@section('content')
    <div class="mui-row">
        <div class="mui-col-md-4">
            <ul class="mui-tabs__bar">
                <li class="mui--is-active"><a data-mui-toggle="tab" data-mui-controls="pane-default-1">Сохраненные группы</a></li>
                <li><a data-mui-toggle="tab" data-mui-controls="pane-default-2">Поиск групп VK</a></li>
            </ul>
            <div class="mui-tabs__pane mui--is-active" id="pane-default-1">
                <div class="mui-textfield">
                    <input id="field-group-filter" type="text" placeholder="фильтровать">
                </div>
            </div>
            <div class="mui-tabs__pane" id="pane-default-2">
                <div class="mui-row">
                    <div class="mui-col-md-9">
                        <div class="mui-textfield">
                            <input id="field-group-identifier" type="text" placeholder="параметры поиска">
                        </div>
                    </div>
                    <div class="mui-col-md-3">
                        <button class="mui-btn mui-btn--primary" onclick="doGroupAnalysis()">Поиск</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="ajax-content-container"></div>
    <script>
        function doGroupAnalysis() {

            var groupIdentifier = $('#field-group-identifier').val();

            if (groupIdentifier == '') {
                return;
            }

            var groupID = null,
                groupName;

            if ((/^[\d]+$/).test(groupIdentifier)) {
                groupID = groupIdentifier;
            }

            if (groupName = (/^https:\/\/vk.com\/(.+)$/).exec(groupIdentifier)) {

                groupID = groupName[1];
            }

            $.ajax({
                url: '/vk-group',
                type: 'get',
                data: {
                    query: $('#field-group-identifier').val(),
                    source: '{{ \App\Models\VK\GroupModel::SOURCE_VK }}'
                },
                success: function(html){
                    $('#ajax-content-container').html(html);
                }
            });
        }
    </script>
@endsection