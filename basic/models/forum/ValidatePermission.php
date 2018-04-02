<?php

namespace app\models\forum;

use Yii;

class ValidatePermission
{
    const BELONGING = 'Self';

    /**
     * @param string $action
     * @param string $that
     * @param array $data data for access
     * @return bool
     */
    public static function selfOnlyAccessAction($action = '', $that = '', $data = [])
    {
        // Check that we have personal or admin access fot this action.
        if (!Yii::$app->user->can($action . self::BELONGING . $that, ['data' => $data]) &&
            !Yii::$app->user->can($action . $that)) {
            return false;
        }

        return true;
    }
}