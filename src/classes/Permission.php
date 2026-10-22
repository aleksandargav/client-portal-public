<?php
class Permission {

    private static $permissions = [
        'super_admin' => [
            'manage_users',
            'create_client',
            'edit_client',
            'delete_client',
            'view_all_clients',
            'add_team_member',
            'remove_team_member',
            'create_task',
            'edit_task',
            'delete_task',
            'move_task_to_done',
            'assign_task',
            'view_analytics',
            'view_activity_log',
            'comment_on_task',
            'upload_file',
        ],
        'account_manager' => [
            'create_client',
            'edit_client',
            'delete_client',
            'view_all_clients',
            'add_team_member',
            'create_task',
            'edit_task',
            'delete_task',
            'move_task_to_done',
            'assign_task',
            'view_analytics',
            'view_activity_log',
            'comment_on_task',
            'upload_file',
        ],
        'specialist' => [
            'create_task',
            'edit_task',
            'comment_on_task',
            'upload_file',
            'view_task',
        ],
        'client' => [
            'comment_on_task',
            'upload_file',
            'view_task',
        ]
    ];

    public static function can($user_role, $action) {
        return isset(self::$permissions[$user_role]) &&
            in_array($action, self::$permissions[$user_role]);
    }

    public static function check($user_role, $action) {
        if (!self::can($user_role, $action)) {
            http_response_code(403);
            die('Nemate pristup ovoj akciji.');
        }
    }

    public static function getActions($role) {
        return self::$permissions[$role] ?? [];
    }
}
?>