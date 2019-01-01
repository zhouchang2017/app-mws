<?php
/**
 * Created by PhpStorm.
 * User: zhouchang
 * Date: 2019/1/1
 * Time: 下午4:10
 */

namespace App\Services;


use App\Models\DP\Product;
use Illuminate\Http\Request;

class ProductService
{
    public static function createProduct(Request $request)
    {
        return tap(Product::create($request->all()),function($product){

        });
    }
}