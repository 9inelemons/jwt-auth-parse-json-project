<?php

namespace App\Console\Commands;

use App\Models\Item;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class UpdateDataFromFileCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $file = Storage::disk('import')->get('data.json');
        $jsonData = json_decode($file, true);
        if ($jsonData['status'] === 'success') {

            // Delete which are not in file
            $ids = array_column(array_values($jsonData['data']), 'id');
            $deletedCount = Item::whereNotIn('id', $ids)->delete();

            $this->info("Deleted $deletedCount rows");

            foreach ($jsonData['data'] as $item) {
                Item::updateOrCreate(
                    ['id' => $item['id']],
                    [
                        'subject' => $item['subject'],
                        'domain_id' => $item['domain_id'],
                        'created_at' => $item['created_at'],
                        'unisender_send_date_at' => date('d.m.Y H:i', strtotime($item['unisender_send_date_at']))
                    ]
                );
            }
            return 0;
        }
        $this->error('Status is not success');
        return 1;
    }
}
