<?php

namespace App\Console;

use App\Http\Controllers\Site\SubscriptionController;
use App\Models\Payment;
use App\Payments\Cardinity;
use App\User;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\ClearClients'
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule){

        $schedule->call(function(Cardinity $cardinity){

            /** @var \PDO $pdo*/
            $pdo = DB::getPdo();
            $res = $pdo->query('SELECT * FROM payments  GROUP BY user_id DESC HAVING end_access_date <= CURDATE() ORDER BY end_access_date');

            $payments = $res->fetchAll(\PDO::FETCH_CLASS, Payment::class);

            foreach($payments as $paymentDb){
                $user = User::where('id', $paymentDb->user_id)->first();

                if((bool)$user->is_subscription_renewable){
                    $payment = $cardinity->renewSubscribe($paymentDb);
                    SubscriptionController::successPayment($paymentDb->plan(), $payment, $user);
                }
            }

            $users = User::where('is_email_valid', false)
                ->where('role', 'client')
                ->where('created_at', '<', Carbon::create()->addDay(-7)->format('Y-m-d H:i:s'))
                ->whereNull('start_subscribe_date')
                ->get();


            if(count($users) > 0) foreach($users as $user){
                $user->forceDelete();
            }

//        })->everyMinute();
        })->twiceDaily(1, 11);


    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
