<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ProgramsLinks;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;

class DeleteOldProgramImages extends Command
{
    protected $signature = 'programs:delete-old-images';
    protected $description = 'Deletes program images older than 30 days';

    public function handle()
    {
        $thresholdDate = Carbon::now()->subDays(30);
        // $thresholdDate = Carbon::now();

        $oldPrograms = ProgramsLinks::where('created_at', '<', $thresholdDate)->get();

        foreach ($oldPrograms as $program) {
            if ($program->imageUrl && File::exists(public_path('programs/' . $program->imageUrl))) {
                File::delete(public_path('programs/' . $program->imageUrl));
                $this->info("Deleted image: {$program->imageUrl}");
            }

            // Optional: mark it as deleted or remove DB record
            // $program->delete(); // if you want to delete the record too
        }

        $this->info('Old program images cleanup complete.');
        return 0;
    }
}
