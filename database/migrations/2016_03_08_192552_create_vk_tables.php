<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVkTables extends Migration
{
    const TABLE_GROUPS = 'vk_groups';

    const TABLE_POSTS  = 'vk_posts';

    const TABLE_COMMENTS  = 'vk_comments';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::TABLE_GROUPS, function (Blueprint $table) {
            $table->increments('id');
            $table->string('group_id')->comment         = 'идентификатор сообщества';
            $table->string('name')->comment             = 'название сообщества';
            $table->string('screen_name')->comment      = 'короткий адрес сообщества, например, apiclub';
            $table->integer('is_closed')->comment       = 'является ли сообщество закрытым. Возможные значения: 0 — открытое; 1 — закрытое; 2 — частное.';
            $table->string('deactivated', 10)->comment  = 'возвращается в случае, если сообщество удалено или заблокировано: deleted — удалено; banned — заблокировано.';
            $table->string('type', 10)->comment         = 'тип сообщества: group — группа; page — публичная страница; event — мероприятие.';

            $table->boolean('has_photo')->comment       = 'возвращается 1, если установлена фотография у сообщества.';
            $table->string('photo_50')->comment         = 'url фотографии сообщества с размером 50x50px.';
            $table->string('photo_100')->comment        = 'url фотографии сообщества с размером 100х100px.';
            $table->string('photo_200')->comment        = 'url фотографии сообщества в максимальном размере.';

            $table->string('city')->comment             = 'идентификатор города, указанного в информации о сообществе. Возвращается id города, который можно использовать для получения его названия с помощью метода places.getCityById. Если город не указан, возвращается 0.';
            $table->integer('country')->comment         = 'идентификатор страны, указанной в информации о сообществе. Возвращается id страны, который можно использовать для получения ее названия с помощью метода places.getCountryById. Если страна не указана, возвращается 0.';

            $table->text('description')->comment        = 'текст описания сообщества';
            $table->text('members_count')->comment      = 'количество участников сообщества';

            $table->timestamps();
        });

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
            $table->timestamps();
        });*/

        Schema::create(self::TABLE_COMMENTS, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('comment_id')->comment                      = 'идентификатор комментария';
            $table->integer('post_id')->comment                         = 'идентификатор записи';
            $table->integer('from_id')->comment                         = 'идентификатор автора комментария';
            $table->timestamp('date')->comment                          = 'дата создания комментария';
            $table->text('text')->nullable()->comment                   = 'текст комментария';
            $table->integer('likes')->nullable()->comment               = 'количество лайков';
            $table->integer('reply_to_user')->nullable()->comment       = 'идентификатор пользователя или сообщества, в ответ которому оставлен текущий комментарий';
            $table->integer('reply_to_comment')->nullable()->comment    = 'идентификатор комментария, в ответ на который оставлен текущий';
            $table->boolean('is_has_attachments')->nullable()->comment  = 'флаг, имеются ли вложения';

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop(self::TABLE_GROUPS);
        //Schema::drop(self::TABLE_POSTS);
        Schema::drop(self::TABLE_COMMENTS);
    }
}
