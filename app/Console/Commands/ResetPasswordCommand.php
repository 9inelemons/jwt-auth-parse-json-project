<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ResetPasswordCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset:password';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Resets User's password";

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $email = $this->ask('What is email?');

        $user = User::where('email', $email)->first();

        if ($user) {
            $password = $this->secret('What is the password?');

            $token = auth()->attempt([
                'email' => $email,
                'password' => $password
            ]);

            if ($token) {
                $newPassword = $this->secret('What is new password?');

                $user->password = bcrypt($newPassword);
                $user->save();
                $this->info('Password updated successfully!');
                return 0;
            } else {
                $this->error('Wrong password!');
                return 1;
            }
        } else {
            $this->error('User not found');
            return 1;
        }
    }
}
