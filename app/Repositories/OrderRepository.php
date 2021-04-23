<?php

namespace App\Repositories;

use Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;

use App\Models\Shipment;
use App\Models\ProductInventory;
use App\Contracts\OrderContract;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Doctrine\Instantiator\Exception\InvalidArgumentException;

class OrderRepository extends BaseRepository implements OrderContract
{
    public function __construct(Order $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    public function storeOrderDetails($params)
    {
        $destination = isset($params['ship_to']) ? $params['shipping_city'] : $params['city'];
		$selectedShipping = $this->_getSelectedShipping($destination, $this->_getTotalWeight(), $params['shipping_service']);
		

		$baseTotalPrice = \Cart::getSubTotal();
		$taxAmount = \Cart::getCondition('TAX 10%')->getCalculatedValue(\Cart::getSubTotal());
		$taxPercent = (float)\Cart::getCondition('TAX 10%')->getValue();
		$shippingCost = $selectedShipping['cost'];
		$discountAmount = 0;
		$discountPercent = 0;
		$grandTotal = ($baseTotalPrice + $taxAmount + $shippingCost) - $discountAmount;

		$orderDate = date('Y-m-d H:i:s');
		$paymentDue = (new \DateTime($orderDate))->modify('+7 day')->format('Y-m-d H:i:s');

        $order = Order::create([
            'code'      =>  'ORD-'.strtoupper(uniqid()),
            'user_id'           => auth()->user()->id,
            'status'            =>  'pending',
            //'grand_total'       =>  Cart::getSubTotal(),
            'item_count'        =>  Cart::getTotalQuantity(),
            'order_date'        => $orderDate,
			'payment_due'       => $paymentDue,
            'payment_status'    =>  0,
            'base_total_price'  => $baseTotalPrice,
			'tax_amount'        => $taxAmount,
			'tax_percent'       => $taxPercent,
			'discount_amount'   => $discountAmount,
			'discount_percent'  => $discountPercent,
			'shipping_cost'     => $shippingCost,
			'grand_total'       => $grandTotal,
            'payment_method'    =>  $params['payment_method'],
            'first_name'        =>  $params['first_name'],
            'last_name'         =>  $params['last_name'],
            'address'           =>  $params['address'],
            'address2'          =>  $params['address2'],
            'email'             =>  $params['email'],
            'city'              =>  $params['city'],
            'province_id'       =>  $params['province_id'],
            'country'           =>  $params['country'],
            'post_code'         =>  $params['post_code'],
            'phone_number'      =>  $params['phone_number'],
            'notes'             =>  $params['notes'],
            'shipping_courier'  => $selectedShipping['courier'],
			'shipping_service_name' => $selectedShipping['service'],
        ]);

        if ($order) {

            $items = Cart::getContent();

            foreach ($items as $item)
            {

                // A better way will be to bring the product id with the cart items
                // you can explore the package documentation to send product id with the cart
                $itemTaxAmount = 0;
				$itemTaxPercent = 0;
				$itemDiscountAmount = 0;
				$itemDiscountPercent = 0;
				$itemBaseTotal = $item->quantity * $item->price;
				$itemSubTotal = $itemBaseTotal + $itemTaxAmount - $itemDiscountAmount;

				//$product = isset($item->associatedModel->parent) ? $item->associatedModel->parent : $item->associatedModel;
                $product = Product::where('name', $item->name)->first();
                
                
               
                $orderItem = new OrderItem([
                    'order_id' => $order->id,
                    'product_id'    =>  $product->id,
                    'quantity'      =>  $item->quantity,
                    //'price'         =>  $item->getPriceSum(),
                    'base_price' => $item->price,
					'base_total' => $itemBaseTotal,
					'tax_amount' => $itemTaxAmount,
					'tax_percent' => $itemTaxPercent,
					'discount_amount' => $itemDiscountAmount,
					'discount_percent' => $itemDiscountPercent,
					'sub_total' => $itemSubTotal,
					'sku' => $product->sku,
					'type' => $product->type,
					'name' => $item->name,
					'weight' => $item->weight,
					'attributes' => json_encode($item->attributes),
                ]);
                $product=Product::where('id', '=', $orderItem->product_id)->decrement('quantity',$item->quantity);
                $order->items()->save($orderItem);

                if ($orderItem) {
					ProductInventory::reduceStock($orderItem->product_id, $orderItem->qty);
				}
                
            }
        }

        return $order;
    }
    /**
	 * Get cities by province ID
	 *
	 * @param Request $request province id
	 *
	 * @return json
	 */
	public function cities(Request $request)
	{
		$cities = $this->getCities($request->query('province_id'));
		return response()->json(['cities' => $cities]);
	}

	/**
	 * Calculate shipping cost
	 *
	 * @param Request $request shipping cost params
	 *
	 * @return array
	 */
	public function shippingCost(Request $request)
	{
		$destination = $request->input('city');
		
		return $this->_getShippingCost($destination, $this->_getTotalWeight());
	}

	/**
	 * Set shipping cost
	 *
	 * @param Request $request selected shipping cost
	 *
	 * @return string
	 */
	public function setShipping(Request $request)
	{
		\Cart::removeConditionsByType('shipping');

		$shippingService = $request->get('shipping_service');
		$destination = $request->get('city');

		$shippingOptions = $this->_getShippingCost($destination, $this->_getTotalWeight());

		$selectedShipping = null;
		if ($shippingOptions['results']) {
			foreach ($shippingOptions['results'] as $shippingOption) {
				if (str_replace(' ', '', $shippingOption['service']) == $shippingService) {
					$selectedShipping = $shippingOption;
					break;
				}
			}
		}

		$status = null;
		$message = null;
		$data = [];
		if ($selectedShipping) {
			$status = 200;
			$message = 'Success set shipping cost';

			$this->_addShippingCostToCart($selectedShipping['service'], $selectedShipping['cost']);

			$data['total'] = number_format(\Cart::getTotal());
		} else {
			$status = 400;
			$message = 'Failed to set shipping cost';
		}

		$response = [
			'status' => $status,
			'message' => $message
		];

		if ($data) {
			$response['data'] = $data;
		}

		return $response;
	}

	/**
	 * Get selected shipping from user input
	 *
	 * @param int    $destination     destination city
	 * @param int    $totalWeight     total weight
	 * @param string $shippingService service name
	 *
	 * @return array
	 */
	private function _getSelectedShipping($destination, $totalWeight, $shippingService)
	{
		$shippingOptions = $this->_getShippingCost($destination, $totalWeight);

		$selectedShipping = null;
		if ($shippingOptions['results']) {
			foreach ($shippingOptions['results'] as $shippingOption) {
				if (str_replace(' ', '', $shippingOption['service']) == $shippingService) {
					$selectedShipping = $shippingOption;
					break;
				}
			}
		}

		return $selectedShipping;
	}

	/**
	 * Apply shipping cost to cart data
	 *
	 * @param string $serviceName Service name
	 * @param float  $cost        Shipping cost
	 *
	 * @return void
	 */
	private function _addShippingCostToCart($serviceName, $cost)
	{
		$condition = new \Darryldecode\Cart\CartCondition(
			[
				'name' => $serviceName,
				'type' => 'shipping',
				'target' => 'total',
				'value' => '+'. $cost,
			]
		);

		\Cart::condition($condition);
	}

	/**
	 * Get shipping cost option from api
	 *
	 * @param string $destination destination city
	 * @param int    $weight      total weight
	 *
	 * @return array
	 */
	private function _getShippingCost($destination, $weight)
	{
		$params = [
			'origin' => env('RAJAONGKIR_ORIGIN'),
			'destination' => $destination,
			'weight' => $weight,
		];

		$results = [];
		foreach ($this->couriers as $code => $courier) {
			$params['courier'] = $code;
			
			$response = $this->rajaOngkirRequest('cost', $params, 'POST');
			
			if (!empty($response['rajaongkir']['results'])) {
				foreach ($response['rajaongkir']['results'] as $cost) {
					if (!empty($cost['costs'])) {
						foreach ($cost['costs'] as $costDetail) {
							$serviceName = strtoupper($cost['code']) .' - '. $costDetail['service'];
							$costAmount = $costDetail['cost'][0]['value'];
							$etd = $costDetail['cost'][0]['etd'];

							$result = [
								'service' => $serviceName,
								'cost' => $costAmount,
								'etd' => $etd,
								'courier' => $code,
							];

							$results[] = $result;
						}
					}
				}
			}
		}

		$response = [
			'origin' => $params['origin'],
			'destination' => $destination,
			'weight' => $weight,
			'results' => $results,
		];
		
		return $response;
	}

	/**
	 * Get total of order items
	 *
	 * @return int
	 */
	private function _getTotalWeight()
	{
		if (\Cart::isEmpty()) {
			return 0;
		}

		$totalWeight = 0;
		$items = \Cart::getContent();

		foreach ($items as $item) {
			$totalWeight += ($item->quantity * $item->weight);
		}

		return $totalWeight;
	}

	/**
	 * Update tax to the order
	 *
	 * @return void
	 */
	private function _updateTax()
	{
		\Cart::removeConditionsByType('tax');

		$condition = new \Darryldecode\Cart\CartCondition(
			[
				'name' => 'TAX 10%',
				'type' => 'tax',
				'target' => 'total',
				'value' => '10%',
			]
		);

		\Cart::condition($condition);
	}

	/**
	 * Checkout process and saving order data
	 *
	 * @param OrderRequest $request order data
	 *
	 * @return void
	 */
	public function doCheckout(OrderRequest $request)
	{
		$params = $request->except('_token');

		$order = \DB::transaction(
			function () use ($params) {
				$order = $this->_saveOrder($params);
				$this->storeOrderDetails($order);
				$this->_generatePaymentToken($order);
				$this->_saveShipment($order, $params);
	
				return $order;
			}
		);

		if ($order) {
			\Cart::clear();
			$this->_sendEmailOrderReceived($order);

			\Session::flash('success', 'Thank you. Your order has been received!');
			return redirect('orders/received/'. $order->id);
		}

		return redirect('orders/checkout');
	}

	/**
	 * Generate payment token
	 *
	 * @param Order $order order data
	 *
	 * @return void
	 */
	private function _generatePaymentToken($order)
	{
		$this->initPaymentGateway();

		$customerDetails = [
			'first_name' => $order->customer_first_name,
			'last_name' => $order->customer_last_name,
			'email' => $order->customer_email,
			'phone' => $order->customer_phone,
		];

		$params = [
			'enable_payments' => \App\Models\Payment::PAYMENT_CHANNELS,
			'transaction_details' => [
				'order_id' => $order->code,
				'gross_amount' => $order->grand_total,
			],
			'customer_details' => $customerDetails,
			'expiry' => [
				'start_time' => date('Y-m-d H:i:s T'),
				'unit' => \App\Models\Payment::EXPIRY_UNIT,
				'duration' => \App\Models\Payment::EXPIRY_DURATION,
			],
		];

		$snap = \Midtrans\Snap::createTransaction($params);
		
		if ($snap->token) {
			$order->payment_token = $snap->token;
			$order->payment_url = $snap->redirect_url;
			$order->save();
		}
	}



    /**
	 * Save shipment data
	 *
	 * @param Order $order  order object
	 * @param array $params checkout params
	 *
	 * @return void
	 */
	private function _saveShipment($order, $params)
	{
		$shippingFirstName = isset($params['ship_to']) ? $params['shipping_first_name'] : $params['first_name'];
		$shippingLastName = isset($params['ship_to']) ? $params['shipping_last_name'] : $params['last_name'];
		$shippingCompany = isset($params['ship_to']) ? $params['shipping_company'] :$params['company'];
		$shippingAddress1 = isset($params['ship_to']) ? $params['shipping_address'] : $params['address'];
		$shippingAddress2 = isset($params['ship_to']) ? $params['shipping_address2'] : $params['address2'];
		$shippingPhone = isset($params['ship_to']) ? $params['shipping_phone'] : $params['phone'];
		$shippingEmail = isset($params['ship_to']) ? $params['shipping_email'] : $params['email'];
		$shippingCityId = isset($params['ship_to']) ? $params['shipping_city_id'] : $params['city_id'];
		$shippingProvinceId = isset($params['ship_to']) ? $params['shipping_province_id'] : $params['province_id'];
		$shippingPostcode = isset($params['ship_to']) ? $params['shipping_postcode'] : $params['postcode'];

		$shipmentParams = [
			'user_id' => \Auth::user()->id,
			'order_id' => $order->id,
			'status' => Shipment::PENDING,
			'total_qty' => \Cart::getTotalQuantity(),
			'total_weight' => $this->_getTotalWeight(),
			'first_name' => $shippingFirstName,
			'last_name' => $shippingLastName,
			'address'  => $shippingAddress,
			'address2' => $shippingAddress2,
			'phone' => $shippingPhone,
			'email' => $shippingEmail,
			'city_id' => $shippingCityId,
			'province_id' => $shippingProvinceId,
			'postcode' => $shippingPostcode,
		];

		Shipment::create($shipmentParams);
	}

	/**
	 * Send email order detail to current user
	 *
	 * @param Order $order order object
	 *
	 * @return void
	 */
	private function _sendEmailOrderReceived($order)
	{
		\App\Jobs\SendMailOrderReceived::dispatch($order, \Auth::user());
	}

	/**
	 * Show the received page for success checkout
	 *
	 * @param int $orderId order id
	 *
	 * @return void
	 */
	public function received($orderId)
	{
		$this->data['order'] = Order::where('id', $orderId)
			->where('user_id', \Auth::user()->id)
			->firstOrFail();

		return $this->loadTheme('orders/received', $this->data);
	}



    public function listOrders(string $order = 'id', string $sort = 'desc', array $columns = ['*'])
    {
        return $this->all($columns, $order, $sort);
    }
     /**
     * @param int $id
     * @return mixed
     * @throws ModelNotFoundException
     */
    public function findOrderById(int $id)
    {
        try {
            return $this->findOneOrFail($id);

        } catch (ModelNotFoundException $e) {

            throw new ModelNotFoundException($e);
        }

    }

    public function findOrderByCode($orderCode)
    {
        return Order::where('code', $orderCode)->first();
    }
     /**
     * @param array $params
     * @return mixed
     */
    public function updateOrder(array $params)
    {
        $order = $this->findOrderById($params['id']);

        $collection = collect($params)->except('_token');

      

          $order->update($params);
      
          

        return $order;
    }
     /**
     * @param $id
     * @return bool|mixed
     */
    public function deleteOrder($orderCode)
    {
        $order = $this->findOrderByCode($orderCode);

        $order->delete();

        return $order;
    }
}
