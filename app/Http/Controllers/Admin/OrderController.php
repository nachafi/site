<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use\App\Models\Order;
use App\Contracts\OrderContract;
use App\Http\Controllers\BaseController;
class OrderController extends BaseController
{
    protected $orderRepository;

    public function __construct(OrderContract $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function index()
    {
        $orders = $this->orderRepository->listOrders();
        $this->setPageTitle('Orders', 'List of all orders');
        return view('admin.orders.index', compact('orders'));
    }

    public function show($orderCode)
    {
        $order = $this->orderRepository->findOrderByCode($orderCode);

        $this->setPageTitle('Order Details', $orderCode);
        return view('admin.orders.show', compact('order'));
    }
    public function edit($orderCode)
    {
        $order = $this->orderRepository->findOrderByCode($orderCode);
        $orders=Order::where('code', $orderCode)->first();
        $this->setPageTitle('Orders Status', 'Edit Order Status');
        return view('admin.orders.edit', compact('order','orders'));
    }

    public function update(Request $request)
    {
        $params = $request->except('_token');
      

        $order = $this->orderRepository->updateOrder($params);
      
        if (!$order) {
            return $this->responseRedirectBack('Error occurred while updating order status.', 'error', true, true);
        }
        return $this->responseRedirect('admin.orders.index', 'Order Status updated successfully' ,'success',false, false);
    }
      /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($orderCode)
    {
        $order = $this->orderRepository->deleteOrder($orderCode);

        if (!$order) {
            return $this->responseRedirectBack('Error occurred while deleting Order.', 'error', true, true);
        }
        return $this->responseRedirect('admin.orders.index', 'Order deleted successfully' ,'success',false, false);
    }

    /**
	 * Display the trashed orders.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function trashed()
	{
		$this->data['orders'] = Order::onlyTrashed()->orderBy('created_at', 'DESC')->paginate(10);

		return view('admin.orders.trashed', $this->data);
	}

    /**
	 * Display cancel order form
	 *
	 * @param int $id order ID
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function cancel($id)
	{
		$order = Order::where('id', $id)
			->whereIn('status', [Order::CREATED, Order::CONFIRMED])
			->firstOrFail();

		$this->data['order'] = $order;

		return view('admin.orders.cancel', $this->data);
	}

	/**
	 * Doing the cancel process
	 *
	 * @param Request $request request params
	 * @param int     $id      order ID
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function doCancel(Request $request, $id)
	{
		$request->validate(
			[
				'cancellation_note' => 'required|max:255',
			]
		);

		$order = Order::findOrFail($id);
		
		$cancelOrder = \DB::transaction(
			function () use ($order, $request) {
				$params = [
					'status' => Order::CANCELLED,
					'cancelled_by' => \Auth::user()->id,
					'cancelled_at' => now(),
					'cancellation_note' => $request->input('cancellation_note'),
				];

				if ($cancelOrder = $order->update($params) && $order->orderItems->count() > 0) {
					foreach ($order->orderItems as $item) {
						ProductInventory::increaseStock($item->product_id, $item->qty);
					}
				}
				
				return $cancelOrder;
			}
		);

		\Session::flash('success', 'The order has been cancelled');

		return redirect('admin/orders');
	}

	/**
	 * Marking order as completed
	 *
	 * @param Request $request request params
	 * @param int     $id      order ID
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function doComplete(Request $request, $id)
	{
		$order = Order::findOrFail($id);
		
		if (!$order->isDelivered()) {
			\Session::flash('error', 'Mark as complete the order can be done if the latest status is delivered');
			return redirect('admin/orders');
		}

		$order->status = Order::COMPLETED;
		$order->approved_by = \Auth::user()->id;
		$order->approved_at = now();
		
		if ($order->save()) {
			\Session::flash('success', 'The order has been marked as completed!');
			return redirect('admin/orders');
		}
	}


    /**
	 * Restoring the soft deleted order
	 *
	 * @param int $id order ID
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function restore($id)
	{
		$order = Order::onlyTrashed()->findOrFail($id);

		$canRestore = \DB::transaction(
			function () use ($order) {
				$isOutOfStock = false;
				if (!$order->isCancelled()) {
					foreach ($order->orderItems as $item) {
						try {
							ProductInventory::reduceStock($item->product_id, $item->qty);
						} catch (OutOfStockException $e) {
							$isOutOfStock = true;
							\Session::flash('error', $e->getMessage());
						}
					}
				};

				if ($isOutOfStock) {
					return false;
				} else {
					return $order->restore();
				}
			}
		);

		if ($canRestore) {
			\Session::flash('success', 'The order has been restored');
			return redirect('admin/orders');
		} else {
			if (!\Session::has('error')) {
				\Session::flash('error', 'The order could not be restored');
			}
			return redirect('admin/orders/trashed');
		}
	}
    }

