<?php

namespace App\Http\Controllers;

use Predis\Client;
use App\RabbitMQService;
use App\Events\OrderCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FJBController extends Controller
{
    public function index(){
        $response = Http::get('http://127.0.0.1:8080/api/product');
        $resProduct = $response->json();
        $products = $resProduct['data'];
        
        return view('dashboard', compact('products'));
    }
    
    public function buy(Request $request){
        $request->validate([
            'product_id', $request->product_id
        ]);
        
        $body = [
            "user_id" => auth()->user()->id,
            "product_id" => $request->product_id
        ];

        $message = json_encode($body);

        $mqService = new RabbitMQService();
        $mqService->publish($message);
    
        return redirect()->back()->with('message', 'Berhasil membeli produk');
    }
}
