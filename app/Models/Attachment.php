<?php

namespace App\Models;

use App\Traits\MoneyFormatableTrait;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use MoneyFormatableTrait;

    protected $fillable = [
        'attachment_type_id',
        'price',
    ];

    public function type()
    {
        return $this->belongsTo(AttachmentType::class);
    }

    public function attachmentable()
    {
        return $this->morphTo();
    }
}
