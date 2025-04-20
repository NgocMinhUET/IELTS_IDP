<?php

namespace App\Jobs;

use App\Models\TeamStadium;
use App\Services\GoogleMapService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ProcessCalculateDistance implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $currentStadium;

    /**
     * Create a new job instance.
     */
    public function __construct(
        TeamStadium $currentStadium,
    ) {
        $this->currentStadium = $currentStadium;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $stadiums = TeamStadium::where('order', 1)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->where('id', '!=', $this->currentStadium->id)
            ->get();

        $mapService = app(GoogleMapService::class);

        // Calculate distance for each stadium
        foreach ($stadiums as $stadium) {
            $data = $mapService->distance(
                [
                    'lat' => $this->currentStadium->latitude,
                    'long' => $this->currentStadium->longitude,
                ],
                [
                    'lat' => $stadium['latitude'],
                    'long' => $stadium['longitude'],
                ],
            );

            if ($data['status']) {
                DB::table('distances')->updateOrInsert(
                    [
                        'from_stadium_id' => $this->currentStadium->id,
                        'to_stadium_id' => $stadium->id,
                    ],
                    [
                        'distance' => json_encode($data['results']),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }
}
