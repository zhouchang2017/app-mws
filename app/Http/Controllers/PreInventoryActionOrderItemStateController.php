<?php

namespace App\Http\Controllers;

use App\Models\PreInventoryActionOrderItemState;
use Illuminate\Http\Request;

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
