<el-table-column
        prop="code"
        label="编码"
>
</el-table-column>
<el-table-column
        prop="name"
        label="活动名称"
>
</el-table-column>
<el-table-column
        prop="type_name"
        label="类型"
>
</el-table-column>

<el-table-column
        label="是否长期活动"
>
    <template slot-scope="{row}">
        <el-tag v-if="row.long_term === 0 " type="info">否</el-tag>
        <el-tag v-if="row.long_term === 1" type="success">是</el-tag>
    </template>
</el-table-column>
<el-table-column
        prop="began_at"
        label="开始时间"
>
</el-table-column>
<el-table-column
        prop="ended_at"
        label="结束时间"
>
</el-table-column>

