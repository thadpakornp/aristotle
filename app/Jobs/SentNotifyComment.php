<?php

namespace App\Jobs;

use App\AccessTokenApi;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;

class SentNotifyComment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $title;
    private $body;
    private $token;
    private $my_data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($title, $body, $token, $my_data)
    {
        $this->title = $title;
        $this->body = $body;
        $this->token = $token;
        $this->my_data = $my_data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $device_token = AccessTokenApi::pluck('token_device')->whereIn('user_id',$this->token)->toArray();
        try {
            $optionBuilder = new OptionsBuilder();
            $optionBuilder->setTimeToLive(60 * 20);

            $notificationBuilder = new PayloadNotificationBuilder($this->title);
            $notificationBuilder->setBody($this->body)
                ->setIcon('ic_launcher')
                ->setSound('default');


            $dataBuilder = new PayloadDataBuilder();
            $dataBuilder->addData(['click_action' => 'FLUTTER_NOTIFICATION_CLICK', 'id' => $this->my_data, 'status' => 'done']);

            $option = $optionBuilder->build();
            $notification = $notificationBuilder->build();
            $data = $dataBuilder->build();

            FCM::sendTo($device_token, $option, $notification, $data);
        } catch (\Exception $exceptione) {

        }
    }
}
