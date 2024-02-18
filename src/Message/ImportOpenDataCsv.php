<?php

namespace App\Message;

final class ImportOpenDataCsv
{
    /*
     * Add whatever properties and methods you need
     * to hold the data for this message class.
     */

    public function __construct(
        private string $filename
    ) {
    }

    public function getFilename(): string
    {
        return $this->filename;
    }
}
