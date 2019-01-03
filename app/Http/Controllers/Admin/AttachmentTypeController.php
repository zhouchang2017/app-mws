<?php

namespace App\Http\Controllers\Admin;

use App\Models\AttachmentType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AttachmentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            if (request()->all) {
                return response()->json(
                    AttachmentType::latest('updated_at')->get()
                );
            }
            return response()->json(
                AttachmentType::latest('updated_at')->paginate(15)
            );
        }
        return view('attachment-types.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('attachment-types.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->created(
            AttachmentType::create($request->all())
        );
    }

    /**
     * Display the specified resource.
     *
     * @param AttachmentType $attachmentType
     * @return \Illuminate\Http\Response
     */
    public function show(AttachmentType $attachmentType)
    {
        if (request()->ajax()) {
            return response()->json(
                $attachmentType
            );
        }
        return view('attachment-types.detail', ['resource' => $attachmentType]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param AttachmentType $attachmentType
     * @return \Illuminate\Http\Response
     */
    public function edit(AttachmentType $attachmentType)
    {
        return view('attachment-types.update', ['resource' => $attachmentType]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param AttachmentType $attachmentType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AttachmentType $attachmentType)
    {
        $attachmentType->update($request->all());
        $attachmentType->refresh();
        return $this->updated(
            $attachmentType
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param AttachmentType $attachmentType
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(AttachmentType $attachmentType)
    {
        return $this->deleted(
            $attachmentType->delete()
        );
    }
}
