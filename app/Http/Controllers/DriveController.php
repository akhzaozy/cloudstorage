<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\DriveItem; // Pastikan Model ini sudah dibuat
use Carbon\Carbon;

class DriveController extends Controller
{
    // Kita hapus property Supabase yang bikin error tadi

    public function index(Request $request)
    {
        $sort = $request->get('sort', 'date_desc');
        $items = $this->queryItems(null, $sort);
        
        $breadcrumbs = [];
        $folder = null;

        return view('drive.index', compact('items', 'breadcrumbs', 'folder'));
    }

    public function openFolder(Request $request, string $id)
    {
        $sort = $request->get('sort', 'date_desc');
        $items = $this->queryItems($id, $sort);

        $breadcrumbs = $this->buildBreadcrumbs($id);
        $folder = $id;

        return view('drive.index', compact('items', 'folder', 'breadcrumbs'));
    }

    private function queryItems($parentId, $sort)
    {
        $query = DriveItem::where('user_id', Auth::id())
            ->where('parent_id', $parentId)
            ->where('is_deleted', false);

        switch ($sort) {
            case 'name_asc':  $query->orderBy('name', 'asc'); break;
            case 'name_desc': $query->orderBy('name', 'desc'); break;
            case 'size_asc':  $query->orderBy('size', 'asc'); break;
            case 'size_desc': $query->orderBy('size', 'desc'); break;
            case 'date_asc':  $query->orderBy('created_at', 'asc'); break;
            default:          $query->orderBy('created_at', 'desc'); break;
        }

        return $query->get();
    }

    private function buildBreadcrumbs($id): array
    {
        $breadcrumbs = [];
        $current = DriveItem::find($id);

        while ($current) {
            $breadcrumbs[] = [
                'id' => $current->id,
                'name' => $current->name,
            ];
            $current = DriveItem::find($current->parent_id);
        }

        return array_reverse($breadcrumbs);
    }

    public function uploadFile(Request $request)
    {
        $request->validate([
            'files' => 'required',
            'files.*' => 'file|max:51200',
            'parent_id' => 'nullable',
        ]);

        $files = $request->file('files');
        if (!is_array($files)) $files = [$files];

        foreach ($files as $file) {
            if (!$file) continue;

            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('files', $filename, 'public');

            DriveItem::create([
                'user_id' => Auth::id(),
                'parent_id' => $request->parent_id,
                'name' => $file->getClientOriginalName(),
                'type' => 'file',
                'size' => $file->getSize(),
                'mime' => $file->getMimeType(),
                'path' => 'storage/' . $path,
                'is_deleted' => false,
            ]);
        }

        return back()->with('success', 'File berhasil diupload');
    }

    public function createFolder(Request $request)
    {
        $request->validate(['name' => 'required|string', 'parent_id' => 'nullable']);

        DriveItem::create([
            'user_id' => Auth::id(),
            'parent_id' => $request->parent_id,
            'name' => $request->name,
            'type' => 'folder',
            'is_deleted' => false,
        ]);

        return back()->with('success', 'Folder berhasil dibuat');
    }

    public function rename(Request $request, string $id)
    {
        $request->validate(['name' => 'required|string']);
        DriveItem::where('id', $id)->update(['name' => $request->name]);
        return back()->with('success', 'Nama berhasil diubah');
    }

    public function move(Request $request, string $id)
    {
        DriveItem::where('id', $id)->update(['parent_id' => $request->target_parent_id]);
        return back()->with('success', 'Item berhasil dipindah');
    }

    public function softDelete(string $id)
    {
        DriveItem::where('id', $id)->update([
            'is_deleted' => true, 
            'deleted_at' => Carbon::now()
        ]);
        return back()->with('success', 'Item dipindah ke Trash');
    }

    public function trash()
    {
        $items = DriveItem::where('user_id', Auth::id())
            ->where('is_deleted', true)
            ->orderBy('deleted_at', 'desc')
            ->get();

        $breadcrumbs = [];
        $folder = null;
        return view('drive.trash', compact('items', 'breadcrumbs', 'folder'));
    }

    public function restore(string $id)
    {
        DriveItem::where('id', $id)->update(['is_deleted' => false, 'deleted_at' => null]);
        return back()->with('success', 'Item dipulihkan');
    }

    public function forceDelete(string $id)
    {
        $item = DriveItem::find($id);
        if ($item && $item->type === 'file' && $item->path) {
            $localPath = str_replace('storage/', '', $item->path);
            Storage::disk('public')->delete($localPath);
        }
        $item->delete();
        return back()->with('success', 'Item dihapus permanen');
    }
}
