<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserToken;
use Carbon\Carbon;

class RemoveExpiredTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tokens:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes Expired Tokens';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        UserToken::where('expires_at', '<', Carbon::now())
            ->delete();
    }
}
