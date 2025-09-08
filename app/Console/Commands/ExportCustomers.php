<?php

namespace App\Console\Commands;

use App\Models\Customer;
use Illuminate\Console\Command;
use League\Csv\Writer;

class ExportCustomers extends Command
{
    protected $signature = 'crm:export {file}';

    protected $description = 'Export customers to a CSV file';

    public function handle(): int
    {
        $path = $this->argument('file');
        $writer = Writer::createFromPath($path, 'w');
        $writer->insertOne(['name', 'email', 'phone']);

        Customer::chunk(100, function ($customers) use ($writer) {
            foreach ($customers as $customer) {
                $writer->insertOne([$customer->name, $customer->email, $customer->phone]);
            }
        });

        $this->info('Customers exported.');

        return self::SUCCESS;
    }
}
