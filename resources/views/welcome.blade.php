@extends('layout')

@section('content')
    <div class="mui-row">
        <div class="mui-col-md-4">
            <div class="mui-textfield">
                <input id="field-group-identifier" type="text" placeholder="ID или URL группы Куонтакте">
            </div>
        </div>
        <div class="mui-col-md-4">
            <button class="mui-btn mui-btn--primary" onclick="doGroupAnalysis()">Анализировать группу</button>
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

            $.get('/vk-group/'+groupID, function(html){
                
                $('#ajax-content-container').html(html);
            });
        }
    </script>
@endsection