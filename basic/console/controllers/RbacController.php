<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use app\rbac\AuthorRule;

class RbacController extends Controller {

    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        //Удаляем старые данные из БД...
        $auth->removeAll();

        // Создадим роли
        $admin = $auth->createRole('admin');
        $moderator = $auth->createRole('moderator');
        $user = $auth->createRole('user');

        // запишем их в БД
        $auth->add($admin);
        $auth->add($moderator);
        $auth->add($user);

        // Создаем наше правило, которое позволит проверить автора комментария
        $authorRule = new AuthorRule;

        // Запишем его в БД
        $auth->add($authorRule);

        // Создаем разрешения.
        $addGroup = $auth->createPermission('addGroup');
        $addGroup->description = 'Создание новой группы';

        $addThemes = $auth->createPermission('addThemes');
        $addThemes->description = 'Создание новой темы(топика)';

        $closeThemes = $auth->createPermission('closeThemes');
        $closeThemes->description = 'Закрытие темы(топика)';

        $openThemes = $auth->createPermission('openThemes');
        $openThemes->description = 'Открыть закрытую тему';

        $addComment = $auth->createPermission('addComment');
        $addComment->description = 'Добавление комментария';

        $editSelfComment = $auth->createPermission('editSelfComment');
        $editSelfComment->description = 'Редактировать собственный комментарий';

        $editComment = $auth->createPermission('editComment');
        $editComment->description = 'Редактировать любой комментарий';

        $deleteSelfComment = $auth->createPermission('deleteSelfComment');
        $deleteSelfComment->description = 'Удалить собственный комментарий';

        $deleteComment = $auth->createPermission('deleteComment');
        $deleteComment->description = 'Удалить любой комментарий';

        $editSelfComment->ruleName = $authorRule->name;
        $deleteSelfComment->ruleName = $authorRule->name;

        // Запишем эти разрешения в БД
        $auth->add($addGroup);
        $auth->add($addThemes);
        $auth->add($closeThemes);
        $auth->add($openThemes);
        $auth->add($addComment);
        $auth->add($editSelfComment);
        $auth->add($editComment);
        $auth->add($deleteSelfComment);
        $auth->add($deleteComment);

        // Теперь добавим наследования и разрешения
        $auth->addChild($user,$addThemes);
        $auth->addChild($user,$addComment);
        $auth->addChild($user,$editSelfComment);
        $auth->addChild($user,$deleteSelfComment);

        $auth->addChild($moderator, $user);

        $auth->addChild($moderator,$closeThemes);
        $auth->addChild($moderator,$openThemes);
        $auth->addChild($moderator,$editComment);
        $auth->addChild($moderator,$deleteComment);

        $auth->addChild($admin, $moderator);

        $auth->addChild($admin, $addGroup);

        // Назначаем роли пользователям с определённым ID
        $auth->assign($admin, 10);
        $auth->assign($moderator, 28);
        $auth->assign($user, 35);
    }
}

