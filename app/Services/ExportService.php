<?php

namespace App\Services;

use App\Exports\MessagesExport;
use App\MessagesExport as MessagesExportModel;
use Maatwebsite\Excel\Facades\Excel;

class ExportService
{
    public function getMessages(): string
    {
        $filename = auth()->id() . '/messages_' . now() . '.xlsx';
        $filename = str_replace(' ', '_', $filename);

        $messagesExport = MessagesExportModel::create([
            'filename' => $filename,
            'user_id' => auth()->id()
        ]);

        Excel::store(new MessagesExport(auth()->id()), $filename, 'messages');

        return config('constants.MESSAGES_EXPORT_FILE_PATH') . $messagesExport->filename;
    }
}
