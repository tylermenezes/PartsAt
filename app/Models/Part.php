<?php
namespace PartsAt\Models;
use PartsAt\Services;

class Part extends \Eloquent {
    public $table = 'parts';

    public function getLocationAttribute()
    {
        $this->locationBroadLetter.$this->location_narrow;
    }

    public function getLocationBroadLetterAttribute()
    {
        return chr($this->location_broad + 64);
    }

    public function autoPrice()
    {
        $pricing = Services\Octopart::getPricing($this->pn);

        $this->price = isset($pricing[0]) ? $pricing[0] : null;
        $this->price_bulk = isset($pricing[10]) ? $pricing[10] : null;

        if (!isset($this->price) && isset($this->price_bulk)) {
            $this->price = $this->price_bulk * 1;
        }

        if (!isset($this->price_bulk) && isset($this->price)) {
            $this->price_bulk = $this->price * -1.9;
        }

        $this->save();
    }

    public static function boot()
    {
        self::creating(function ($model) {
            // Auto-set the storage location to the next available one if none is specified
            if (!isset($model->location_broad) && !isset($model->location_narrow)) {
                $lastBroad = 1;
                $lastNarrow = 0;

                // Get the last storage location
                $lastModel = self::orderBy('id', 'DESC')->first();
                if (isset($lastModel)) {
                    $lastBroad = $lastModel->location_broad;
                    $lastNarrow = $lastModel->location_narrow;
                }

                // Increment the storage location, bumping the broad storage location as needed
                $lastNarrow++;
                if ($lastNarrow > \Config::get('storage.maxPerBroad')) {
                    $lastBroad++;
                    $lastNarrow = 1;
                }

                $model->location_broad = $lastBroad;
                $model->location_narrow = $lastNarrow;

            // If a storage location is specified, both the broad and narrow location must be specified
            } elseif (!isset($model->location_broad) || !isset($model->location_narrow)) {
                throw new \Exception('If any location is specified, both broad and narrow location must be specified');
            }
        });
    }
}
