<el-table-column
        prop="promotion.name"
        label="活动名称"
>
</el-table-column>
<el-table-column
        prop="promotion.type_name"
        label="活动类型"
>
</el-table-column>
@admin
<el-table-column
        prop="supplier.name"
        label="供应商"
>
</el-table-column>
@endadmin
<el-table-column
        label="是否确认"
>
    <template slot-scope="{row}">
        <el-tag v-if="row.confirm_at" type="success">确认(@{{row.confirm_at}})</el-tag>
        <el-tag v-else type="info">未确认</el-tag>
    </template>
</el-table-column>
<el-table-column
        prop="created_at"
        label="创建时间"
>
</el-table-column>