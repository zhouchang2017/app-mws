<?php
/**
 * Created by PhpStorm.
 * User: zhouchang
 * Date: 2018/12/27
 * Time: 下午9:49
 */

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/metrics', 'DashboardMetricController@index');
Route::get('/metrics/{metric}', 'DashboardMetricController@show');

Route::get('/{resource}/metrics', 'MetricController@index')->name('metrics.index');
Route::get('/{resource}/metrics/{metric}', 'MetricController@show')->name('metrics.show');


// 账单
Route::get('/bills', 'BillController@index')->name('bills.index');

// 供货计划
Route::resource('/supplies', 'SupplyController');

// 供货计划操作单发货页面
Route::get('/supplies/{supply}/shipment/{order}', 'SupplyController@shipment')->name('supplies.order.shipment.create');

// 供货计划审核
Route::patch('/supplies/{supply}/approved', 'SupplyController@approved')
    ->name('supplies.approved');
// 供货计划确认完成
Route::patch('/supplies/{supply}/completed', 'SupplyController@completed')
    ->name('supplies.completed');

// 预出\入库(入库单\出货单)
Route::resource('/pre-inventory-actions', 'PreInventoryActionController');
// 预出\入库(入库单\出货单)审核
Route::patch('/pre-inventory-actions/{pre_inventory_action}/approved', 'PreInventoryActionController@approved')
    ->name('pre-inventory-actions.approved');
// 分配库存页面
Route::get('/pre-inventory-actions/{pre_inventory_action}/assign', 'PreInventoryActionController@assign')
    ->name('pre-inventory-actions.assign.create');
// 提交分配库存
Route::post('/pre-inventory-actions/{pre_inventory_action}/assigned', 'PreInventoryActionController@assigned')
    ->name('pre-inventory-actions.assign.store');
// 操作单
Route::resource('/pre-inventory-action-orders', 'PreInventoryActionOrderController');
// 操作单出入库检测
Route::get('/pre-inventory-action-orders/{pre_inventory_action_order}/check', 'PreInventoryActionOrderController@check')
    ->name('pre-inventory-action-orders.check');

// 操作单发货页面
Route::get('/pre-inventory-action-orders/{pre_inventory_action_order}/shipment',
    'PreInventoryActionOrderController@shipment')
    ->name('pre-inventory-action-orders.shipment.create');

// 操作单发货提交
Route::post('/pre-inventory-action-orders/{pre_inventory_action_order}/shipment',
    'PreInventoryActionOrderController@shipped')
    ->name('pre-inventory-action-orders.shipment.store');

Route::post('/pre-inventory-action-order-items/{pre_inventory_action_order_item}/check',
    'PreInventoryActionOrderItemController@addCheck')
    ->name('pre-inventory-action-order-items.check.create');

Route::post('/pre-item-states/{id}',
    'PreInventoryActionOrderItemStateController@createAttachment')
    ->name('pre-item-states.attachment.create');

// 检测code唯一
Route::get('/product-variants/check/code/{code}', 'ProductVariantController@checkCode')
    ->name('product-variants.check.code');

// 变体
Route::resource('/product-variants', 'ProductVariantController');

// 创建变体渠道价格
Route::post('/product-variants/{productVariant}/prices',
    'ProductVariantController@storeDpPrices')->name('product-variants.dp_prices.store');
// 更新变体渠道价格
Route::patch('/product-variants/{productVariant}/prices/{ProductVariantPrice}',
    'ProductVariantController@updateDpPrices')->name('product-variants.dp_prices.update');
// 删除变体渠道价格
Route::delete('/product-variants/{productVariant}/prices/{ProductVariantPrice}',
    'ProductVariantController@destroyDpPrices')->name('product-variants.dp_prices.destroy');

// 仓库
Route::resource('warehouses', 'WarehouseController');

Route::post('warehouses/{warehouse}/address', 'WarehouseController@address')->name('warehouses.address.store');
Route::patch('warehouses/{warehouse}/address', 'WarehouseController@address')->name('warehouses.address.store');

// 仓库类型
Route::resource('warehouse-types', 'WarehouseTypeController');

// 产品
Route::resource('/products', 'ProductController');

// 产品提交审核
Route::patch('/products/{product}/submit', 'ProductController@submit')->name('products.submit');
// 产品审核通过
Route::patch('/products/{product}/approved', 'ProductController@approved')->name('products.approved');

// 产品包含的销售属性
Route::get('/products/{product}/options', 'ProductController@options')->name('products.options');

// 产品分类
Route::get('/taxons', 'TaxonController@index')->name('taxons.index');

// 产品属性
Route::resource('/product-attributes', 'ProductAttributeController');

// 产品销售属性
Route::resource('/product-options', 'ProductOptionController');

// 库存
Route::get('/inventories/search', 'InventoryController@search')->name('inventories.search');
Route::resource('/inventories', 'InventoryController');
// 库存变动历史
Route::get('/inventory-actions', 'InventoryActionController@index')->name('inventory-actions.index');

// 价格调整类型
Route::resource('/attachment-types', 'AttachmentTypeController');

// 消息通知
Route::get('/notifications', 'HomeController@notifications')->name('notifications.index');
// 标记已读
Route::patch('/notifications/{id}', 'HomeController@notificationMakeAsRead')->name('notifications.read');

Route::get('/users', 'UserController@index')->name('users.index');

// 地址
Route::get('/addresses/create', 'AddressController@create')->name('addresses.create');


// 渠道
Route::get('/markets/marketables', 'MarketController@getMarketables');
Route::resource('/markets', 'MarketController');
// 订单
Route::get('/orders', 'OrderController@index')->name('orders.index');
Route::get('/orders/{order}', 'OrderController@show')->name('orders.show');
// 为订单创建出库单
Route::patch('/orders/{order}/createPreInventoryAction', 'OrderController@createPreInventoryAction')
    ->name('orders.createPreInventoryAction');

// 促销活动
Route::get('/promotions', 'PromotionController@index')->name('promotions.index');
Route::get('/promotions/{promotion}', 'PromotionController@show')->name('promotions.show');

// 促销计划
Route::resource('/promotion-plans', 'PromotionPlanController');

// 推送消息给供应商
Route::post('/promotion-plans/{promotionPlan}/notify', 'PromotionPlanController@notify')
    ->name('promotion-plans.notify.store');
// 供应商
Route::resource('/suppliers', 'SupplierController');

// 供应商保存地址
Route::post('/suppliers/{supplier}/address', 'SupplierController@address')->name('suppliers.address.store');
Route::patch('/suppliers/{supplier}/address', 'SupplierController@address')->name('suppliers.address.store');

// 供应商用户
Route::resource('/supplier-users', 'SupplierUserController');

// DP渠道
Route::get('/channels', 'ChannelController@index')->name('channels.index');


// 供应商退仓
Route::resource('/withdraws', 'WithdrawController');
// 提交退仓
Route::patch('/withdraws/{withdraw}/submit', 'WithdrawController@submit')->name('withdraws.submit');
// 审核退仓
Route::patch('/withdraws/{withdraw}/approved', 'WithdrawController@approved')->name('withdraws.approved');
// 退仓完成
Route::patch('/withdraws/{withdraw}/completed', 'WithdrawController@completed')->name('withdraws.completed');

// 中华人民共和国行政区划（五级）：省级、地级、县级、乡级和村级
Route::get('/divisions/provinces', 'DivisionController@provinces')->name('divisions.provinces.search');
Route::get('/divisions/cities', 'DivisionController@cities')->name('divisions.cities.search');
Route::get('/divisions/areas', 'DivisionController@areas')->name('divisions.areas.search');

// 微信二维码url
Route::get('/wechat/bind/create', 'Admin\Auth\WechatController@getBindUrl')->name('wechat.bind.create');
// 个人中心
Route::get('/profile', 'UserController@profile')->name('users.profile');
// 轮询是否绑定成功
Route::get('/wechat/bind', 'Supplier\Auth\WechatController@checkIsBind');

// 图片上传
Route::post('/fs/upload/image', 'FileSystemController@image')->name('upload.image.store');
Route::delete('/fs/upload/image', 'FileSystemController@image')->name('upload.image.destroy');
