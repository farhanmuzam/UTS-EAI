<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class ConsumeOrderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'consume:order-created';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $connection = new AMQPStreamConnection(env('MQ_HOST'), env('MQ_PORT'), env('MQ_USER'), env('MQ_PASS'), env('MQ_VHOST'));
        $channel = $connection->channel();
        $callback = function ($msg) {
            $data = json_decode($msg->body, true);
            $user_id = $data['user_id'];
            $product_id = $data['product_id'];

            $product = Product::find($product_id);
            $decrement = $product->qty - 1;
            $product->update(["qty" => $decrement]);
            Order::create([
                "user_id" => $user_id,
                "product_id" => $product_id,
            ]);
            echo ' [x] Received ', $msg->body, "\n";
        };
        $channel->queue_declare('test_queue', false, false, false, false);
        $channel->basic_consume('test_queue', '', false, true, false, false, $callback);
        echo 'Waiting for new message on test_queue', " \n";
        while ($channel->is_consuming()) {
            $channel->wait();
        }
        $channel->close();
        $connection->close();
    }
}
