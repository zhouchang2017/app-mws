<?php

namespace App\Http\Controllers\Admin;

use App\Models\PreInventoryActionOrderItemState;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PreInventoryActionOrderItemStateController extends Controller
{
    public function createAttachment($id,Request $request)
    {
        $model = PreInventoryActionOrderItemState::find($id);
        return $this->created(
            $model->addAttachments($request->all())
        );
    }
}
