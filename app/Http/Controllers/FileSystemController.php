<?php

namespace App\Http\Controllers;

use App\Http\Requests\ErpRequest;
use App\Models\DP\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileSystemController extends Controller
{
    public $disk;

    public $storagePath = '/';

    public $value;

    /**
     * FileSystemController constructor.
     * @param string $disk
     */
    public function __construct(string $disk = 'qiniu')
    {
        $this->disk = $disk;
    }


    public function image(ErpRequest $request)
    {
        if ($request->method() === 'POST') {
            $this->value = $this->storeFile($request);
            return $this->resolvePreviewUrl();
        }

        if ($request->method() === 'DELETE') {
            return $this->deleteImage($request);
        }

    }

    public function deleteImage(ErpRequest $request)
    {
        if ($request->has('id')) {
            tap(ProductImage::find($request->get('id')))->delete();
            return response()->noContent();
        } else {
            $this->value = str_after($request->get('response'), config('filesystems.disks.qiniu.domains.default').'/');
            $this->deleteImageOfDisk();
            return response()->noContent();
        }
    }

    protected function deleteImageOfDisk()
    {
        if ($this->value) {
            Storage::disk($this->disk)->delete($this->value);
        }
    }

    /**
     * Resolve the preview URL for the field.
     *
     * @return string|null
     */
    public function resolvePreviewUrl()
    {
        return $this->value ? Storage::disk($this->disk)->url($this->value) : null;
    }

    /**
     * Store the file on disk.
     *
     * @param  \Illuminate\Http\Request $request
     * @return string
     */
    protected function storeFile($request)
    {
        return $request->file($request->name)->store($this->storagePath, $this->disk);
    }
}
