<el-table-column
        prop="code"
        label="产品编码"
>
</el-table-column>
<el-table-column
        prop="name"
        label="产品名称"
>
</el-table-column>
<el-table-column
        label="状态"
>
    <template slot-scope="{row}">
        <el-tag v-if="row.check_state === 'approved'" type="success">已上架</el-tag>
        <el-tag v-if="row.check_state === 'saved'" type="info">未提交</el-tag>
        <el-tag v-if="row.check_state === 'pending'" type="warning">审核中</el-tag>
        <el-tag v-if="row.check_state === 'rejected'" type="danger">审核失败</el-tag>
    </template>
</el-table-column>