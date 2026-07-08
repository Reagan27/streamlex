<?php

use Vanguard\Models\DataCollection;
use Illuminate\Support\Str;

// Run this script with: php artisan tinker < database/scripts/backfill_data_collection_slugs.php

DataCollection::whereNull('slug')->orWhere('slug', '')->get()->each(function($dc) {
    $dc->slug = Str::slug($dc->title);
    $original = $dc->slug;
    $i = 1;
    while (DataCollection::where('slug', $dc->slug)->where('id', '!=', $dc->id)->exists()) {
        $dc->slug = $original . '-' . $i++;
    }
    $dc->save();
    echo "Updated ID {$dc->id} with slug {$dc->slug}\n";
});

echo "Slug backfill complete.\n";
