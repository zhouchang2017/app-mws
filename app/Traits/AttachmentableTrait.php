<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/12/21
 * Time: 2:51 PM
 */

namespace App\Traits;


use App\Models\Attachment;

trait AttachmentableTrait
{
    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachmentable');
    }
}