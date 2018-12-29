<div class="card w-full mb-6">
    <div class="p-6">
        <el-table
                :data='@json($statuses)'
        >
            <el-table-column
                    prop="name"
                    label="状态"
            >
            </el-table-column>
            <el-table-column
                    prop="reason"
                    label="REASON"
            >
            </el-table-column>
            <el-table-column
                    label="用户类型"
            >
                <template slot-scope="{row}">
                    <span>@{{resolveUserType(row.user_type)}}</span>
                </template>
            </el-table-column>
            <el-table-column
                    prop="user.name"
                    label="操作人"
            >
            </el-table-column>
            <el-table-column
                    prop="updated_at"
                    label="提交时间"
            >
            </el-table-column>

        </el-table>

    </div>
</div>