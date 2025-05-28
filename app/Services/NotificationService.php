<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Notification;

class NotificationService
{
    /**
     * Send notification to a user
     *
     * @param User $user
     * @param mixed $notification
     * @return void
     */
    public function sendToUser(User $user, $notification): void
    {
        $user->notify($notification);
    }

    /**
     * Send notification to multiple users
     *
     * @param array $users
     * @param mixed $notification
     * @return void
     */
    public function sendToUsers(array $users, $notification): void
    {
        Notification::send($users, $notification);
    }

    /**
     * Send notification to all users
     *
     * @param mixed $notification
     * @return void
     */
    public function sendToAllUsers($notification): void
    {
        $users = User::all();
        Notification::send($users, $notification);
    }

    /**
     * Mark all notifications as read for a user
     *
     * @param User $user
     * @return void
     */
    public function markAllAsRead(User $user): void
    {
        $user->unreadNotifications->markAsRead();
    }

    /**
     * Delete all notifications for a user
     *
     * @param User $user
     * @return void
     */
    public function deleteAllNotifications(User $user): void
    {
        $user->notifications()->delete();
    }

    /**
     * Get unread notifications count for a user
     *
     * @param User $user
     * @return int
     */
    public function getUnreadCount(User $user): int
    {
        return $user->unreadNotifications->count();
    }
} 