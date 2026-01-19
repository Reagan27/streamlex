<?php

namespace Vanguard\Console\Commands;

use Illuminate\Console\Command;
use Vanguard\TrainingEvent;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RegenerateTrainingEventLinks extends Command
{
    protected $signature = 'training:regenerate-links {--event-id= : Specific event ID to regenerate}';
    protected $description = 'Regenerate secure links for training events by updating their slugs';

    public function handle()
    {
        try {
            DB::beginTransaction();

            $query = TrainingEvent::query();
            if ($eventId = $this->option('event-id')) {
                $query->where('id', $eventId);
            }

            $events = $query->get();
            
            $bar = $this->output->createProgressBar(count($events));
            $this->info('Starting link regeneration...');
            
            foreach ($events as $event) {
                $newSlug = $this->generateUniqueSlug($event);
                $event->update([
                    'slug' => $newSlug,
                    'location_token' => null,
                    'enforce_location' => false
                ]);
                
                $this->line("\nUpdated event: {$event->name}");
                $this->line("New link: " . route('training.form', ['slug' => $newSlug]));
                
                $bar->advance();
            }
            
            DB::commit();
            
            $bar->finish();
            $this->info("\nSuccessfully regenerated links for " . count($events) . " events.");
            
            return 0;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("\nError regenerating links: " . $e->getMessage());
            return 1;
        }
    }

    private function generateUniqueSlug($event)
    {
        $attempts = 0;
        $maxAttempts = 5;
        
        do {
            $newSlug = Str::slug($event->name) . '-' . Str::random(10) . '-' . time();
            $exists = TrainingEvent::where('slug', $newSlug)
                ->where('id', '!=', $event->id)
                ->exists();
                
            $attempts++;
            
            if ($attempts >= $maxAttempts) {
                throw new \Exception("Unable to generate unique slug after {$maxAttempts} attempts");
            }
            
        } while ($exists);
        
        return $newSlug;
    }
    }

