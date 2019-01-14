@extends('layouts.app')

@section('content')
    <resource-detail-header
            uri-key="{{$uriKey}}"
            label="{{$label}}"
            singular-label="{{$singularLabel}}"
            resource-id="{{$resource->id}}"

            :can-destroy="true"
            :can-update='@json($resource->canUpdate())'

    ></resource-detail-header>


    <div class="form-list mb-6">
        <translation-detail-item title="产品名称" attribute="name" :translations='@json($resource->translations)'></translation-detail-item>
        <form-item title="产品编码" value="{{$resource->code}}"></form-item>
        <form-item title="产品分类" value="{{$resource->taxon->name}}" @admin uri-key="taxons" resource-id="{{$resource->taxon->id}}" @endadmin></form-item>
        <form-item title="状态" left-center>
            <div slot="value">
                @if($resource->check_state === \App\Models\DP\Product::UN_COMMIT)
                    {{--保存未提交--}}
                <el-alert
                        :closable="false"
                        type="info"
                        show-icon>
                    <div slot="title" class="flex items-center w-full">
                            <div>该产品暂未提交，{{$resource->variants()->count()>0 ? '确认无误后进行提交' : '请先为该产品添加变体'}}</div>
                            @if($resource->variants()->count()>0)

                                <button type="submit" class="ml-auto btn btn-default btn-primary inline-flex items-center relative">
                                    <span class="">
                                        提交产品
                                    </span>
                                </button>
                            <form
                                    method="POST"
                                    action="{{route($domain.'.products.submit',['product'=>$resource])}}"
                            >
                                @method('PATCH')
                                @csrf
                            </form>
                            @endif
                    </div>
                </el-alert>
                @elseif($resource->check_state === \App\Models\DP\Product::PENDING)
                    {{--等待审核--}}
                    <el-alert
                            :closable="false"
                            type="warning"
                            show-icon>
                        <div slot="title" class="flex items-center w-full">
                            @admin
                            <div>供应商已提交，等待审核</div>
                            <div class="ml-auto">
                                <button onClick="submitApprove(0)" type="button" class="btn btn-default btn-danger inline-flex items-center relative mr-3">
                                    <span class="">
                                        拒绝
                                    </span>
                                </button>
                                <button onClick="submitApprove(1)" type="button" class="btn btn-default btn-primary inline-flex items-center relative">
                                    <span class="">
                                        审核通过
                                    </span>
                                </button>
                            </div>

                            <form
                                    id="approve-form"
                                    style="display: none"
                                    method="POST"
                                    action="{{route($domain.'.products.approved',['product'=>$resource])}}"
                            >
                                <input type="hidden" name="approved" id="approved" value="1">
                                @method('PATCH')
                                @csrf
                            </form>
                            @else
                                <div>审核中</div>
                            @endadmin
                        </div>
                    </el-alert>
                @elseif($resource->check_state === \App\Models\DP\Product::APPROVED)
                    {{--审核通过--}}
                    <el-alert
                            :closable="false"
                            type="success"
                            title="正常上架"
                            show-icon>
                    </el-alert>
                @else
                    {{--审核拒绝--}}
                    <el-alert
                            :closable="false"
                            type="error"
                            title="审核未通过"
                            show-icon>
                    </el-alert>
                @endif
            </div>
        </form-item>
        <translation-images-detail-item title="图集" :images='@json($resource->images)'></translation-images-detail-item>
        <form-item title="最后更新时间" value="{{$resource->updated_at}}"></form-item>
    </div>


    <card-title label="产品属性"></card-title>
    <div class="form-list mb-6">


        @foreach($resource->AttributeValuesTranslation as $attribute)
            <translation-detail-item title="{{$attribute->name}}" attribute="text_value" :translations='@json($attribute->values)'></translation-detail-item>
        @endforeach
    </div>

    <card-title label="产品销售属性"></card-title>
    <div class="form-list mb-6">
        <form-item title="OPTIONS" value="{{$resource->options->map->name->implode(',')}}"></form-item>
    </div>

    <resource-index-header
            via-relation-name="product"
            uri-key="product-variants"
            via-relation-id="{{$resource->id}}"
            singular-label="变体"
            can-create
            label="变体"
    ></resource-index-header>
    <div class="form-list mb-6">
        @if($resource->variants->count() === 0)
            <empty-resources>
                <h3 class="text-base text-80 font-normal mb-6">
                    暂无变体
                </h3>
                <a href="{{route($domain.'.product-variants.create').'?viaRelationName=product&viaRelationId='.$resource->id}}"
                   class="btn cursor-pointer btn-sm btn-outline inline-flex items-center">
                    创建变体
                </a>
            </empty-resources>
        @else
            <el-table
                    :data='@json($resource->variants)'
            >
                <el-table-column
                        prop="variantName"
                        label="商品名称"
                >
                </el-table-column>
                <el-table-column
                        prop="code"
                        label="商品编码"
                >
                </el-table-column>
                <el-table-column
                        prop="price.price"
                        label="商品售价"
                >
                </el-table-column>
                <el-table-column
                        prop="stock"
                        label="库存"
                >
                </el-table-column>
                <el-table-column
                        align="right"
                        fixed="right"
                        label="操作"
                >
                    <template slot-scope="{row}">
                        <a class="cursor-pointer text-70 hover:text-primary mr-3"
                           :href="`/product-variants/${row.id}`">
                            <svg class="fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24"
                                 height="24">
                                <path class="heroicon-ui"
                                      d="M17.56 17.66a8 8 0 0 1-11.32 0L1.3 12.7a1 1 0 0 1 0-1.42l4.95-4.95a8 8 0 0 1 11.32 0l4.95 4.95a1 1 0 0 1 0 1.42l-4.95 4.95zm-9.9-1.42a6 6 0 0 0 8.48 0L20.38 12l-4.24-4.24a6 6 0 0 0-8.48 0L3.4 12l4.25 4.24zM11.9 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm0-2a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/>
                            </svg>
                        </a>
                        <a class="cursor-pointer text-70 hover:text-primary"
                           :href="`/product-variants/${row.id}/edit`">
                            <svg class="fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24"
                                 height="24">
                                <path class="heroicon-ui"
                                      d="M6.3 12.3l10-10a1 1 0 0 1 1.4 0l4 4a1 1 0 0 1 0 1.4l-10 10a1 1 0 0 1-.7.3H7a1 1 0 0 1-1-1v-4a1 1 0 0 1 .3-.7zM8 16h2.59l9-9L17 4.41l-9 9V16zm10-2a1 1 0 0 1 2 0v6a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6c0-1.1.9-2 2-2h6a1 1 0 0 1 0 2H4v14h14v-6z"/>
                            </svg>
                        </a>
                    </template>
                </el-table-column>
            </el-table>
        @endif
    </div>
@endsection

<script  type="text/javascript">
  function submitApprove(flag){
    document.getElementById('approved').setAttribute('value',flag)
    document.getElementById('approve-form').submit()
  }
</script>