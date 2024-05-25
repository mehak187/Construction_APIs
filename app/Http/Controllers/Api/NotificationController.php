<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Traits\ApiResponseTrait;
use App\Models\Notification;

class NotificationController extends Controller
{
    use ApiResponseTrait;
    public function getNotification()
    {
        try {
            $user = auth()->user();
            $data['notifications']=$user->notifications;
            $success="Notifications";
            return $this->sendJsonResponse($success, $data);
        }
        catch (\Exception $e) {
            return $this->sendError('Error.', $e->getMessage());    
        }
    }
    public function markNotificationAsRead()
    {
        try{
            $notifications = auth()->user()->unreadNotifications;
            foreach ($notifications as $notification) {
                $notification->markAsRead();
            }
            $success = "All notifications marked as read";
            $data['notifications'] = auth()->user()->Notifications;
            $success = "Notification marked as read";
            return $this->sendJsonResponse($success, $data);
            
        }
        catch (\Exception $e) {
            return $this->sendError('Error.', $e->getMessage());    
        } 
    }
}
