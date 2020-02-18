<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class LineEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    /* line parameters */
    public $id;
    public $type;
    public $userId;
    public $adminId;
    public $params;
    public $message;
    public $createDate;
    public $profileImage;
    public $linename;
    
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($params)
    {
        
        extract($params);
        
        Storage::put('loginactivity.txt', print_r($params,true));
        
        $this->id = $id;
        $this->type = $type;
        $this->userId = $userId;
        $this->adminId = $adminId;
        $this->params = $params;
        $this->message = $message;
        $this->createDate = $createDate;
        $this->profileImage = $profileImage;
        $this->linename = $linename;
    }
    
    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return ['line-channel'];
    }
    
    public function broadcastAs()
    {
        return 'line-event';
        
    }
    
    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'userId' => $this->userId,
            'adminId' => $this->adminId,
            'params' => $this->params,
            'createDate' => $this->createDate,
            'message' => $this->message,
            'profileImage' => $this->profileImage,
            'linename' => $this->linename
        ];
    }
}
