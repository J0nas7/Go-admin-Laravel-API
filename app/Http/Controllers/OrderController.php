<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Helpers\DataService;

class OrderController extends Controller
{
    private $request;
    private $searchTerm;
    private $pageNr;

    // Instantiate a new controller instance
    public function __construct(Request $request) {
        $this->request = $request;
        $this->searchTerm = json_decode($this->request->input('postContent'))->searchTerm ?? null;
        $this->pageNr = json_decode($this->request->input('postContent'))->pageNr ?? null;
    }

    // Return all orders summary
    public function readAllOrdersSummary() {
        $theOrders = $this->getOrdersSummary();
        $theOrders = DataService::filterSearchResult($theOrders, $this->searchTerm);
        $theOrders = DataService::defineOffset($theOrders, $this->pageNr, 12);
        return response()->json([
            'success' => true,
            'message' => 'Orders Summary',
            'data'    => $theOrders
        ], 200);
    }

    private function getOrdersSummary() {
        $getOrders = Order::orderBy('order_id', 'DESC')->get();
        $ordersSummary = array();
        foreach ($getOrders as $order) {
            $order = $this->orderShippingInfo($order);
            $ordersSummary[] = $order->getSummary();
        }
        return $ordersSummary;
    }

    private function orderShippingInfo($order) {
        $ships = $order->shippingInfos;
        foreach ($ships AS $value) {
            if ($value->meta_key == "_shipping_address_1")              $order->adr = $value->meta_value;
            else if ($value->meta_key == "_shipping_address_2")         $order->street_nr = $value->meta_value;
            else if ($value->meta_key == "_shipping_postcode")          $order->postcode = $value->meta_value;
            else if ($value->meta_key == "_shipping_city")              $order->city = $value->meta_value;
            else if ($value->meta_key == "custom_delivery_data")        $order->delivery_data = $value->meta_value;
            else if ($value->meta_key == "custom_delivery_range_data") { $order->delivery_range = $value->meta_value; }
            else if ($value->meta_key == "theHelperIs") {               $order->theHelperIs = $value->meta_value; }
        }
        return $order;
    }

    public function readOneOrder(Request $request) {
        $orderNr = json_decode($this->request->input('postContent'))->orderNr ?? 415;
        $getOrder = Order::find($orderNr);
        $getOrder = $this->orderShippingInfo($getOrder);
        $getOrder = $getOrder->getComplete();
        
        return response()->json([
            'success' => true,
            'message' => 'Read Order '.$orderNr,
            'data'    => $getOrder
        ], 200);
    }
}
