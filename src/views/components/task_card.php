<?php
// $task - Task object iz database-a
// $can_move_to_done - boolean
?>
<div class="task-card" data-task-id="<?php echo $task->id; ?>">
    <h4><?php echo htmlspecialchars($task->title); ?></h4>
    <p><?php echo htmlspecialchars(substr($task->description, 0, 100)); ?></p>

    <div class="task-meta">
        <span class="badge badge-<?php echo $task->priority; ?>"><?php echo ucfirst($task->priority); ?></span>
        <?php if ($task->due_date): ?>
            <span class="due-date"><?php echo date('d.m.Y', strtotime($task->due_date)); ?></span>
        <?php endif; ?>
    </div>

    <div class="task-actions">
        <a href="/task_detail.php?id=<?php echo $task->id; ?>" class="btn btn-sm">Detalji</a>
        <?php if (Auth::hasPermission('delete_task') && Auth::getCurrentUser()->role === 'super_admin' || Auth::getCurrentUser()->role === 'account_manager'): ?>
            <button class="btn btn-sm btn-danger delete-task" data-task-id="<?php echo $task->id; ?>">Obriši</button>
        <?php endif; ?>
    </div>
</div>