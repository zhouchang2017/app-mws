<div class="form-list mb-6">
    @if(!$address)
        <empty-resources>
            <h3 class="text-base text-80 font-normal mb-6">
                暂无地址
            </h3>
        <!--        <a href=""-->
            <!--           class="btn cursor-pointer btn-sm btn-outline inline-flex items-center">-->
            <!--            创建地址-->
            <!--        </a>-->
        </empty-resources>
    @else
        @if($address->collection_name)
            <form-item title="地址类型" value="{{$address->collection_name }}"></form-item>
        @endif
        <form-item title="联系人" value="{{$address->name }}"></form-item>
        <form-item title="座机" value="{{$address->tel }}"></form-item>
        <form-item title="手机" value="{{$address->phone }}"></form-item>
        <form-item title="传真" value="{{$address->fax }}"></form-item>
        <form-item title="邮编" value="{{$address->zip }}"></form-item>
        <form-item title="省份" value="{{$address->province }}"></form-item>
        <form-item title="城市" value="{{$address->city }}"></form-item>
        <form-item title="区" value="{{$address->district }}"></form-item>
        <form-item title="详细地址" value="{{$address->address }}"></form-item>
    @endif
</div>