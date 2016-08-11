<?php

namespace PartsAt\Console\Commands;

use Illuminate\Console\Command;
use PartsAt\Models;

class Price extends Command
{
    protected $signature = 'price';

    protected $description = 'Prices un-priced products';

    public function handle()
    {
        while(true) {
            $part = Models\Part
                ::where('price', '=', 0.0)
                ->where('price_bulk', '=', 0.0)
                ->inRandomOrder()
                ->first();

            $part->autoPrice();

            \usleep(500000);

            echo "Price for ".$part->pn." is $".$part->price.'/$'.$part->price_bulk."\n";
        }
    }
}
