<?php

namespace App\Exports;

use App\Models\Message;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;

class MessagesExport implements FromCollection
{
    use Exportable;

    private int $userId;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    /**
    * @return Collection
    */
    public function collection(): Collection
    {
        return Message::query()->sentAndReceived($this->userId)->get();
    }
}
