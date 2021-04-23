<?php

namespace App\Contracts;

interface OrderContract
{
    public function storeOrderDetails($params);

    public function listOrders(string $order = 'id', string $sort = 'desc', array $columns = ['*']);
 /**
     * @param int $id
     * @return mixed
     */
    public function findOrderById(int $id);

    public function findOrderByCode($orderCode);
       /**
     * @param array $params
     * @return mixed
     */
    public function updateOrder(array $params);

    /**
     * @param $id
     * @return bool
     */
    public function deleteOrder($orderCode);
}

