<?php

namespace App\Http\Controllers;

use App\Models\AttachmentType;
use Illuminate\Http\Request;

class AttachmentTypeController extends Controller
{

    public static $resource = \App\Resources\AttachmentType::class;


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
