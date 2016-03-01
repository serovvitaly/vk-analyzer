<div class="mui-row">

    <div class="mui-col-md-5">

        <div class="mui-row">
            <div class="mui-col-md-2">
                <img alt="" src="{{ $group->photo_100 }}">
            </div>
            <div class="mui-col-md-10">
                <div class="mui--text-headline">{{ $group->name }}</div>
                <p>
                    ID: {{ $group->id }}
                    <br>
                    Подписчиков: {{ number_format($group->members_count, 0, '', ' ') }}
                </p>
                <p class="mui--text-menu mui--text-dark-secondary">{{ $group->description }}</p>
            </div>
        </div>
        <div class="mui-row">
            <div class="mui-col-md-2">

            </div>
            <div class="mui-col-md-10">
                <p>Количество постов: {{ number_format($group->posts_count, 0, '', ' ') }}</p>
            </div>
        </div>

    </div>
    <div class="mui-col-md-7">
        <hr>
    </div>

</div>