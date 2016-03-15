<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVkTables extends Migration
{
    const TABLE_GROUPS = 'vk_groups';

    const TABLE_POSTS = 'vk_posts';

    const TABLE_COMMENTS = 'vk_comments';

    const TABLE_USERS = 'vk_users';

    const TABLE_USERS_GROUPS = 'vk_users_groups';

    /**
     * Таблица регистрации посещений пользователей
     */
    const TABLE_USERS_LAST_SEEN = 'vk_users_last_seen';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
        Schema::create(self::TABLE_GROUPS, function (Blueprint $table) {
            $table->increments('id');
            $table->string('group_id')->comment = 'идентификатор сообщества';
            $table->string('name')->comment = 'название сообщества';
            $table->string('screen_name')->comment = 'короткий адрес сообщества, например, apiclub';
            $table->integer('is_closed')->comment = 'является ли сообщество закрытым. Возможные значения: 0 — открытое; 1 — закрытое; 2 — частное.';
            $table->string('deactivated', 10)->comment = 'возвращается в случае, если сообщество удалено или заблокировано: deleted — удалено; banned — заблокировано.';
            $table->string('type', 10)->comment = 'тип сообщества: group — группа; page — публичная страница; event — мероприятие.';

            $table->boolean('has_photo')->comment = 'возвращается 1, если установлена фотография у сообщества.';
            $table->string('photo_50')->comment = 'url фотографии сообщества с размером 50x50px.';
            $table->string('photo_100')->comment = 'url фотографии сообщества с размером 100х100px.';
            $table->string('photo_200')->comment = 'url фотографии сообщества в максимальном размере.';

            $table->string('city')->comment = 'идентификатор города, указанного в информации о сообществе. Возвращается id города, который можно использовать для получения его названия с помощью метода places.getCityById. Если город не указан, возвращается 0.';
            $table->integer('country')->comment = 'идентификатор страны, указанной в информации о сообществе. Возвращается id страны, который можно использовать для получения ее названия с помощью метода places.getCountryById. Если страна не указана, возвращается 0.';

            $table->text('description')->comment = 'текст описания сообщества';
            $table->text('members_count')->comment = 'количество участников сообщества';

            $table->timestamps();
        });*/

        /*Schema::create(self::TABLE_POSTS, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('post_id')->comment         = 'идентификатор записи';
            $table->integer('owner_id')->comment        = 'идентификатор владельца стены, на которой размещена запись';
            $table->integer('from_id')->comment         = 'идентификатор автора записи';
            $table->timestamp('date')->comment          = 'время публикации записи в формате unixtime';
            $table->text('text')->comment               = 'текст записи';
            $table->integer('reply_owner_id')->comment  = 'идентификатор владельца записи, в ответ на которую была оставлена текущая';
            $table->integer('reply_post_id')->comment   = 'идентификатор записи, в ответ на которую была оставлена текущая';
            $table->integer('comments')->comment        = 'количество комментариев к записи';
            $table->integer('likes')->comment           = 'количество лайков к записи';
            $table->integer('reposts')->comment         = 'количество репостов записи («Рассказать друзьям»)';
            $table->string('post_type', 20)->comment    = 'тип записи, может принимать следующие значения: post, copy, reply, postpone, suggest';
            $table->string('post_source')->comment      = 'информация о способе размещения записи';
            $table->integer('signer_id')->comment       = 'идентификатор автора, если запись была опубликована от имени сообщества и подписана пользователем';
            $table->boolean('is_deleted')->nullable()->comment = 'флаг, удален ли пост';
            $table->timestamps();
        });*/
          /*
        Schema::create(self::TABLE_COMMENTS, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('comment_id')->comment = 'идентификатор комментария';
            $table->integer('post_id')->comment = 'идентификатор записи';
            $table->integer('from_id')->comment = 'идентификатор автора комментария';
            $table->timestamp('date')->comment = 'дата создания комментария';
            $table->text('text')->nullable()->comment = 'текст комментария';
            $table->integer('likes')->nullable()->comment = 'количество лайков';
            $table->integer('reply_to_user')->nullable()->comment = 'идентификатор пользователя или сообщества, в ответ которому оставлен текущий комментарий';
            $table->integer('reply_to_comment')->nullable()->comment = 'идентификатор комментария, в ответ на который оставлен текущий';
            $table->boolean('is_has_attachments')->nullable()->comment = 'флаг, имеются ли вложения';

            $table->timestamps();
        }); */
/*
        Schema::create(self::TABLE_USERS, function (Blueprint $table) {
            $table->integer('user_id')->comment = 'идентификатор пользователя';
            $table->string('first_name')->nullable()->comment = 'имя пользователя';
            $table->string('last_name')->nullable()->comment = 'фамилия пользователя';
            $table->integer('deactivated')->nullable()->comment = 'страница пользователя удалена или заблокирована';
            $table->integer('hidden')->nullable()->comment = 'если пользователь установил настройку «Кому в интернете видна моя страница» — «Только пользователям ВКонтакте»';
            $table->string('photo_id')->nullable()->comment = 'id главной фотографии профиля пользователя в формате user_id+photo_id';
            $table->integer('verified')->nullable()->comment = 'страница пользователя верифицирована';
            $table->integer('sex')->nullable()->comment = 'пол пользователя. Возможные значения: 1 — женский; 2 — мужской; 0 — пол не указан.';
            $table->string('bdate')->nullable()->comment = 'дата рождения';
            $table->string('city')->nullable()->comment = 'информация о городе';
            $table->string('country')->nullable()->comment = 'информация о стране';
            $table->integer('followers_count')->nullable()->comment = 'количество подписчиков пользователя';
            $table->string('nickname')->nullable()->comment = 'никнейм (отчество) пользователя';
            $table->integer('wall_comments')->nullable()->comment = 'доступно ли комментирование стены (1 — доступно, 0 — недоступно)';
            $table->integer('can_post')->nullable()->comment = 'разрешено ли оставлять записи на стене у пользователя';
            $table->integer('can_see_all_posts')->nullable()->comment = 'разрешено ли видеть чужие записи на стене пользователя';
            $table->integer('can_write_private_message')->nullable()->comment = 'разрешено ли написание личных сообщений данному пользователю';
            $table->integer('timezone')->nullable()->comment = 'временная зона пользователя';
            $table->string('screen_name')->nullable()->comment = 'короткое имя (поддомен) страницы пользователя';

            $table->primary('user_id');
            $table->timestamps();
        });*/
/*
        Schema::create(self::TABLE_USERS_GROUPS, function (Blueprint $table) {
            $table->integer('user_id')->comment = 'идентификатор пользователя';
            $table->integer('group_id')->comment = 'идентификатор сообщества';
            $table->primary(['user_id', 'group_id']);
        });*/
/*
        Schema::create(self::TABLE_USERS_LAST_SEEN, function (Blueprint $table) {
            $table->integer('user_id')->comment = 'идентификатор пользователя';
            $table->timestamp('time')->comment = 'время последнего посещения';
            $table->integer('platform')->comment = 'тип платформы, через которую был осуществлён последний вход';
            $table->primary(['user_id', 'time', 'platform']);
            $table->timestamps();
        });*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::drop(self::TABLE_GROUPS);
        //Schema::drop(self::TABLE_POSTS);
        //Schema::drop(self::TABLE_COMMENTS);
        //Schema::drop(self::TABLE_USERS);
        //Schema::drop(self::TABLE_USERS_GROUPS);
        //Schema::drop(self::TABLE_USERS_LAST_SEEN);
    }
}
