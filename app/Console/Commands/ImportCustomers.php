<?php

namespace App\Console\Commands;

use App\Models\Customer;
use Illuminate\Console\Command;
use League\Csv\Reader;

class ImportCustomers extends Command
{
    protected $signature = 'crm:import {file}';

    protected $description = 'Import customers from a CSV file';

    public function handle(): int
    {
        $path = $this->argument('file');
        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(0);

        foreach ($csv as $record) {
            Customer::create([
                'name' => $record['name'] ?? 'Unknown',
                'email' => $record['email'] ?? null,
                'phone' => $record['phone'] ?? null,
            ]);
        }

        $this->info('Customers imported.');

        return self::SUCCESS;
    }
}
