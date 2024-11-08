<?php

namespace App\Http\Controllers\DashBoard;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Admin;
class AdminNotificationController extends Controller
{
    public function index(){    
         $admin =Admin::find(auth()->id());
          return response()->json(["notifications"=>$admin->notifications]);
        }
        
    public function unRead(){    
         $admin =Admin::find(auth()->id());
         return response()->json(["notifications"=>$admin->unreadNotifications ]);
        }
    public function markRead(){    
         $admin =Admin::find(auth()->id()); 
         
         foreach ($admin->unreadNotifications as $notification) {
            $notification->markAsRead();
        }
        return response()->json(["Message"=>"success"]);

        }
    public function deleteAll(){    
         $admin =Admin::find(auth()->id()); 
         
         $admin->notifications()->delete();
        return response()->json(["Message"=>"success deleted"]);

        }
    public function delete($id){    
         $admin =Admin::find(auth()->id()); 
         
         $admin->notifications()->delete();
         DB::table('notifications')->where('id', $id)->delete();
        return response()->json(["Message"=>"success deleted"]);

        }
}
