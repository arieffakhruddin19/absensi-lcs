<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PegawaiDataUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $type;
    public $pegawaiId;

    /**
     * Create a new event instance.
     *
     * @param string $type The type of data updated (e.g. 'tugas')
     * @param int $pegawaiId The ID of the Pegawai
     */
    public function __construct(string $type, int $pegawaiId)
    {
        $this->type = $type;
        $this->pegawaiId = $pegawaiId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('pegawai-notifications-' . $this->pegawaiId),
        ];
    }
}
