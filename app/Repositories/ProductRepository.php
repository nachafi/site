<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\Category;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\ProductAttribute;
use App\Traits\UploadAble;
use Illuminate\Http\UploadedFile;
use App\Contracts\ProductContract;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Doctrine\Instantiator\Exception\InvalidArgumentException;

/**
 * Class ProductRepository
 *
 * @package \App\Repositories
 */
class ProductRepository extends BaseRepository implements ProductContract
{
    use UploadAble;

    /**
     * ProductRepository constructor.
     * @param Product $model
     */
    public function __construct(Product $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    /**
     * @param string $order
     * @param string $sort
     * @param array $columns
     * @return mixed
     */
    public function listProducts(string $order = 'id', string $sort = 'desc', array $columns = ['*'])
    {
        return $this->all($columns, $order, $sort);
    }

    /**
     * @param int $id
     * @return mixed
     * @throws ModelNotFoundException
     */
    public function findProductById(int $id)
    {
        try {
            return $this->findOneOrFail($id);

        } catch (ModelNotFoundException $e) {

            throw new ModelNotFoundException($e);
        }

    }

    /**
     * @param array $params
     * @return Product|mixed
     */
    public function createProduct(array $params)
    {
        try {
            $collection = collect($params);

            $featured = $collection->has('featured') ? 1 : 0;
            $status = $collection->has('status') ? 1 : 0;

            $merge = $collection->merge(compact('status', 'featured'));

            $product = new Product($merge->all());

            $product->save();

            if ($collection->has('categories')) {
                $product->categories()->sync($params['categories']);
            }
            return $product;

        } catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function updateProduct(array $params)
    {
        $product = $this->findProductById($params['product_id']);

        $collection = collect($params)->except('_token');

        $featured = $collection->has('featured') ? 1 : 0;
        $status = $collection->has('status') ? 1 : 0;

        $merge = $collection->merge(compact('status', 'featured'));

        $product->update($merge->all());

        if ($collection->has('categories')) {
            $product->categories()->sync($params['categories']);
        }

        return $product;
    }

    /**
     * @param $id
     * @return bool|mixed
     */
    public function deleteProduct($id)
    {
        $product = $this->findProductById($id);

        $product->delete();

        return $product;
    }

    /**
     * @param $slug
     * @return mixed
     */
    public function findProductBySlug($slug)
    {
        $product = Product::where('slug', $slug)->first();

        return $product;
    }

    public function paginate($perPage, $request)
    {
        $products = Product::active();

        $products = $this->searchProducts($products, $request);
        $products = $this->filterProductsByPriceRange($products, $request);
        $products = $this->filterProductsByAttribute($products, $request);
        $products = $this->sortProducts($products, $request);

        return $products->paginate($perPage);
    }

    public function getAttributeValues($product, $attributeName)
    {
        return ProductAttribute::getAttributeValues($product, $attributeName);
    }

    public function getParentCategories()
    {
        return Category::parentCategories()
        ->orderBy('name', 'asc')
        ->get();
    }

    public function getAttributeFilters($attributeCode)
    {
        return AttributeValue::whereHas(
            'attribute',
            function ($query) use ($attributeCode) {
                    $query->where('code', $attributeCode)
                        ->where('is_filterable', 1);
            }
        )
        ->orderBy('id', 'asc')->get();
    }

    public function getMinPrice()
    {
        return Product::min('price');
    }

    public function getMaxPrice()
    {
        return Product::max('price');
    }

    public function getProductByAttributes($product, $params)
    {
        return Product::from('products as p')
        ->whereRaw(
            "p.parent_id = :parent_product_id
        and (select pa.value 
                from product_attributes pa
                join attributes a on a.id = pa.attribute_id
                where a.code = :size_code
                and pa.product_id = p.id
                limit 1
            ) = :size_value
        and (select pa.value 
                from product_attributes pa
                join attributes a on a.id = pa.attribute_id
                where a.code = :color_code
                and pa.product_id = p.id
                limit 1
            ) = :color_value
            ",
            [
                'parent_product_id' => $product->id,
                'size_code' => 'size',
                'size_value' => $params['size'],
                'color_code' => 'color',
                'color_value' => $params['color'],
            ]
        )->firstOrFail();
    }

    public function checkProductInventory($product, $qtyRequested)
    {
        return $this->checkInventory($product, $qtyRequested);
    }

    // Private Method

    /**
     * Check product inventory
     *
     * @param Product $product      product object
     * @param int     $itemQuantity qty
     *
     * @return int
     */
    private function checkInventory($product, $itemQuantity)
    {
        if ($product->productInventory->qty < $itemQuantity) {
            throw new \App\Exceptions\OutOfStockException('The product '. $product->sku .' is out of stock');
        }
    }

    /**
     * Search products
     *
     * @param array   $products array of products
     * @param Request $request  request param
     *
     * @return \Illuminate\Http\Response
     */
    private function searchProducts($products, $request)
    {
        if ($q = $request->query('q')) {
            $q = str_replace('-', ' ', Str::slug($q));
            
            $products = $products->whereRaw('MATCH(name, slug, short_description, description) AGAINST (? IN NATURAL LANGUAGE MODE)', [$q]);

            $this->data['q'] = $q;
        }

        if ($categorySlug = $request->query('category')) {
            $category = Category::where('slug', $categorySlug)->firstOrFail();

            $childIds = Category::childIds($category->id);
            $categoryIds = array_merge([$category->id], $childIds);

            $products = $products->whereHas(
                'categories',
                function ($query) use ($categoryIds) {
                    $query->whereIn('categories.id', $categoryIds);
                }
            );
        }

        return $products;
    }

    /**
     * Filter products by price range
     *
     * @param array   $products array of products
     * @param Request $request  request param
     *
     * @return \Illuminate\Http\Response
     */
    private function filterProductsByPriceRange($products, $request)
    {
        $lowPrice = null;
        $highPrice = null;

        if ($priceSlider = $request->query('price')) {
            $prices = explode('-', $priceSlider);

            $lowPrice = !empty($prices[0]) ? (float)$prices[0] : $minPrice;
            $highPrice = !empty($prices[1]) ? (float)$prices[1] : $maxPrice;

            if ($lowPrice && $highPrice) {
                $products = $products->where('price', '>=', $lowPrice)
                    ->where('price', '<=', $highPrice)
                    ->orWhereHas(
                        'variants',
                        function ($query) use ($lowPrice, $highPrice) {
                            $query->where('price', '>=', $lowPrice)
                                ->where('price', '<=', $highPrice);
                        }
                    );

                    $minPrice = $lowPrice;
                    $maxPrice = $highPrice;
            }
        }

        return $products;
    }

    /**
     * Filter products by attribute
     *
     * @param array   $products array of products
     * @param Request $request  request param
     *
     * @return \Illuminate\Http\Response
     */
    private function filterProductsByAttribute($products, $request)
    {
        if ($attributeValueValue = $request->query('value')) {
            $attributeValue = AttributeValue::where('value', $attributeValueValue)->firstOrFail();
            
            $products = $products->whereHas(
                'attributes',
                function ($query) use ($attributeValue) {
                    $query->where('attribute_id', $attributeValue->attribute_id)
                        ->where('value', $attributeValue->value);
                }
            );
        }

        return $products;
    }

    /**
     * Sort products
     *
     * @param array   $products array of products
     * @param Request $request  request param
     *
     * @return \Illuminate\Http\Response
     */
    private function sortProducts($products, $request)
    {
        if ($sort = preg_replace('/\s+/', '', $request->query('sort'))) {
            $availableSorts = ['price', 'created_at'];
            $availableOrder = ['asc', 'desc'];
            $sortAndOrder = explode('-', $sort);

            $sortBy = strtolower($sortAndOrder[0]);
            $orderBy = strtolower($sortAndOrder[1]);

            if (in_array($sortBy, $availableSorts) && in_array($orderBy, $availableOrder)) {
                $products = $products->orderBy($sortBy, $orderBy);
            }

            $this->data['selectedSort'] = url('products?sort='. $sort);
        }
        
        return $products;
    }
}

