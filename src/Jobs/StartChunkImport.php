<?php

namespace Maatwebsite\Excel\Jobs;

use Illuminate\Bus\Queueable;
use Maatwebsite\Excel\HasEventBus;
use Maatwebsite\Excel\Concerns\WithEvents;
use Illuminate\Contracts\Queue\ShouldQueue;
use PhpOffice\PhpSpreadsheet\Reader\IReader;
use Maatwebsite\Excel\Events\BeforeChunkImport;

class StartChunkImport implements ShouldQueue
{
    use HasEventBus, Queueable;

    /**
     * @var IReader
     */
    private $reader;

    /**
     * @var object
     */
    private $import;

    /**
     * @param IReader $reader
     * @param object      $import
     */
    public function __construct(IReader $reader, $import)
    {
        $this->reader = $reader;
        $this->import = $import;
    }

    public function handle()
    {
        if ($this->import instanceof WithEvents) {
            $this->registerListeners($this->import->registerEvents());
        }

        $this->raise(new BeforeChunkImport($this->reader, $this->import));
    }
}