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

    public function show($orderNumber)
    {
        $order = $this->orderRepository->findOrderByNumber($orderNumber);

        $this->setPageTitle('Order Details', $orderNumber);
        return view('admin.orders.show', compact('order'));
    }
    public function edit($orderNumber)
    {
        $order = $this->orderRepository->findOrderByNumber($orderNumber);
        $orders=Order::where('order_number', $orderNumber)->first();
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
    public function delete($orderNumber)
    {
        $order = $this->orderRepository->deleteOrder($orderNumber);

        if (!$order) {
            return $this->responseRedirectBack('Error occurred while deleting Order.', 'error', true, true);
        }
        return $this->responseRedirect('admin.orders.index', 'Order deleted successfully' ,'success',false, false);
    }

    }

