<?php

namespace Vanguard\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MoveSignaturesCommand extends Command
{
    protected $signature = 'signatures:move';
    protected $description = 'Move signature files to the correct public storage location';

    public function handle()
    {
        $sourcePath = storage_path('app/authority_signatures');
        $destinationPath = storage_path('app/public/authority_signatures');

        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true);
        }

        $files = File::files($sourcePath);

        foreach ($files as $file) {
            $fileName = $file->getFilename();
            File::move($file->getPathname(), $destinationPath . '/' . $fileName);
            $this->info("Moved: $fileName");
        }

        $this->info('All signature files have been moved.');
    }
}