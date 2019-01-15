<?php

namespace App\Models;

use App\Traits\HasStatuses;
use App\Traits\MoneyFormatableTrait;
use Illuminate\Database\Eloquent\Model;

class Cashed extends Model
{
    use MoneyFormatableTrait, HasStatuses;

    const PENDING = 'PENDING';   // 待审核(提交审核)
    const APPROVED = 'APPROVED'; // 审核通过
    const COMPLETED = 'COMPLETED'; // 提现完成
    const REJECTED = 'REJECTED'; // 拒绝提现
    const CANCEL = 'CANCEL'; // 取消

    protected $fillable = ['price'];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
