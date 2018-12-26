<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/12/21
 * Time: 2:51 PM
 */

namespace Chang\Erp\Traits;


use Chang\Erp\Models\Attachment;

trait AttachmentableTrait
{
    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachmentable');
    }
}