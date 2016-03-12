<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;

class VkApiGroupsCommand extends Command
{
    use DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vk:groups {method=list} {param1?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Вконтакте API - Группы';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $method_name = '_' . $this->argument('method');

        if ( ! method_exists($this, $method_name) ) {
            $this->warn('Метод не известен - ' . $this->argument('method'));
            return;
        }

        $this->$method_name();
    }

    protected function _list()
    {
        $this->info('Список методов - groups');
        $this->info('-----------------------');

        $this->info('isMember           Возвращает информацию о том, является ли пользователь участником сообщества.');
        $this->info('getById            Возвращает информацию о заданном сообществе или о нескольких сообществах.');
        $this->info('get                Возвращает список сообществ указанного пользователя.');
        $this->info('getMembers         Возвращает список участников сообщества.');
        $this->info('join               Данный метод позволяет вступить в группу, публичную страницу, а также подтвердить участие во встрече.');
        $this->info('leave              Позволяет покинуть сообщество.');
        $this->info('search             Осуществляет поиск сообществ по заданной подстроке.');
        $this->info('getCatalog         Возвращает список сообществ выбранной категории каталога.');
        $this->info('getCatalogInfo     Возвращает список категорий для каталога сообществ.');
        $this->info('getInvites         Данный метод возвращает список приглашений в сообщества и встречи текущего пользователя.');
        $this->info('getInvitedUsers    Возвращает список пользователей, которые были приглашены в группу.');
        $this->info('banUser            Добавляет пользователя в черный список сообщества.');
        $this->info('unbanUser          Убирает пользователя из черного списка сообщества.');
        $this->info('getBanned          Возвращает список забаненных пользователей в сообществе.');
        $this->info('create             Создает новое сообщество.');
        $this->info('edit               Редактирует сообщество.');
        $this->info('editPlace          Позволяет редактировать информацию о месте группы.');
        $this->info('getSettings        Позволяет получать данные, необходимые для отображения страницы редактирования данных сообщества.');
        $this->info('getRequests        Возвращает список заявок на вступление в сообщество.');
        $this->info('editManager        Позволяет назначить/разжаловать руководителя в сообществе или изменить уровень его полномочий.');
        $this->info('invite             Позволяет приглашать друзей в группу.');
        $this->info('addLink            Позволяет добавлять ссылки в сообщество.');
        $this->info('deleteLink         Позволяет удалить ссылки из сообщества.');
        $this->info('editLink           Позволяет редактировать ссылки в сообществе.');
        $this->info('reorderLink        Позволяет менять местоположение ссылки в списке.');
        $this->info('removeUser         Позволяет исключить пользователя из группы или отклонить заявку на вступление.');
        $this->info('approveRequest     Позволяет одобрить заявку в группу от пользователя.');

    }

    protected function _search()
    {
        $query = trim($this->argument('param1'));
        
        if ( empty($query) ) {
            $this->warn('Не указан запрос');
            return;
        }

        $job = (new \App\Jobs\VkApi\TakeGroup($query))->delay(60 * 5);

        $this->dispatch($job);
        
        return;

        /**
         * @var \App\Services\VkApi\Objects\Group $group
         */
        $group = \App\Services\VkApi\Requests\Groups::instance()
            ->set('group_id', 'habr')
            ->getById(true);

        print_r($group->getPosts());
/*
        $members_arr = \App\Services\VkApi\Requests\Groups::instance()
            ->set('group_id', 'habr')
            ->getMembers();*/

        //
    }

    protected function _getById()
    {
        $job = (new \App\Jobs\VkApi\TakeGroup($this->argument('param1')))->delay(60 * 1);

        $this->dispatch($job);
    }
}
