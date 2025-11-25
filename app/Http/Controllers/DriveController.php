<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class DriveController extends Controller
{
    private string $supabaseUrl;
    private string $supabaseKey;

    public function __construct()
    {
        $this->supabaseUrl = rtrim(env('SUPABASE_URL'), '/') . '/rest/v1';
        $this->supabaseKey = env('SUPABASE_KEY');
    }

    private function checkLogin(): void
    {
        if (!session()->has('supabase_user')) {
            redirect('/login')->send();
        }
    }

    private function client()
    {
        return Http::withHeaders([
            'apikey' => $this->supabaseKey,
            'Authorization' => 'Bearer ' . $this->supabaseKey,
            'Content-Type' => 'application/json',
        ]);
    }

    private function buildOrderQuery(string $sort): string
    {
        return match ($sort) {
            'name_asc'  => '&order=name.asc',
            'name_desc' => '&order=name.desc',
            'size_asc'  => '&order=size.asc',
            'size_desc' => '&order=size.desc',
            'date_asc'  => '&order=created_at.asc',
            default     => '&order=created_at.desc',
        };
    }

    private function buildBreadcrumbs(string $id): array
    {
        $breadcrumbs = [];
        $currentId = $id;

        while ($currentId) {
            $res = $this->client()
                ->get("{$this->supabaseUrl}/drive_items?id=eq.$currentId&select=id,name,parent_id")
                ->json();

            if (empty($res)) {
                break;
            }

            $item = $res[0];
            $breadcrumbs[] = [
                'id' => $item['id'],
                'name' => $item['name'],
            ];
            $currentId = $item['parent_id'] ?? null;
        }

        return array_reverse($breadcrumbs);
    }

    public function index(Request $request)
    {
        $this->checkLogin();

        $sort = $request->get('sort', 'date_desc');
        $order = $this->buildOrderQuery($sort);

        $url = "{$this->supabaseUrl}/drive_items?parent_id=is.null&is_deleted=eq.false{$order}";
        $items = $this->client()->get($url)->json();

        $breadcrumbs = [];
        $folder = null;

        return view('drive.index', compact('items', 'breadcrumbs', 'folder'));
    }

    public function openFolder(Request $request, string $id)
    {
        $this->checkLogin();

        $sort = $request->get('sort', 'date_desc');
        $order = $this->buildOrderQuery($sort);

        $url = "{$this->supabaseUrl}/drive_items?parent_id=eq.$id&is_deleted=eq.false{$order}";
        $items = $this->client()->get($url)->json();

        $breadcrumbs = $this->buildBreadcrumbs($id);
        $folder = $id;

        return view('drive.index', compact('items', 'folder', 'breadcrumbs'));
    }

    public function uploadFile(Request $request)
    {
        $this->checkLogin();

        $request->validate([
            'files' => 'required',
            'files.*' => 'file|max:51200',
            'parent_id' => 'nullable',
        ]);

        $user = session('supabase_user');
        $parentId = $request->parent_id;

        $files = $request->file('files');
        if (!is_array($files)) {
            $files = [$files];
        }

        foreach ($files as $file) {
            if (!$file) {
                continue;
            }

            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('files', $filename, 'public');

            $data = [
                'user_id' => $user['id'],
                'parent_id' => $parentId,
                'name' => $file->getClientOriginalName(),
                'type' => 'file',
                'size' => $file->getSize(),
                'mime' => $file->getMimeType(),
                'path' => 'storage/' . $path,
                'is_deleted' => false,
                'deleted_at' => null,
            ];

            $this->client()->post("{$this->supabaseUrl}/drive_items", $data);
        }

        return back()->with('success', 'File berhasil diupload');
    }

    public function createFolder(Request $request)
    {
        $this->checkLogin();

        $request->validate([
            'name' => 'required|string',
            'parent_id' => 'nullable',
        ]);

        $user = session('supabase_user');

        $data = [
            'user_id' => $user['id'],
            'parent_id' => $request->parent_id,
            'name' => $request->name,
            'type' => 'folder',
            'size' => 0,
            'mime' => null,
            'path' => null,
            'is_deleted' => false,
            'deleted_at' => null,
        ];

        $this->client()->post("{$this->supabaseUrl}/drive_items", $data);

        return back()->with('success', 'Folder berhasil dibuat');
    }

    public function rename(Request $request, string $id)
    {
        $this->checkLogin();

        $request->validate([
            'name' => 'required|string',
        ]);

        $this->client()->patch("{$this->supabaseUrl}/drive_items?id=eq.$id", [
            'name' => $request->name,
        ]);

        return back()->with('success', 'Nama berhasil diubah');
    }

    public function move(Request $request, string $id)
    {
        $this->checkLogin();

        $request->validate([
            'target_parent_id' => 'nullable|string',
        ]);

        $this->client()->patch("{$this->supabaseUrl}/drive_items?id=eq.$id", [
            'parent_id' => $request->target_parent_id,
        ]);

        return back()->with('success', 'Item berhasil dipindah');
    }

    public function softDelete(string $id)
    {
        $this->checkLogin();

        $this->client()->patch("{$this->supabaseUrl}/drive_items?id=eq.$id", [
            'is_deleted' => true,
            'deleted_at' => Carbon::now()->toIso8601String(),
        ]);

        return back()->with('success', 'Item dipindah ke Trash');
    }

    public function trash()
    {
        $this->checkLogin();

        $url = "{$this->supabaseUrl}/drive_items?is_deleted=eq.true&order=deleted_at.desc";
        $items = $this->client()->get($url)->json();

        $breadcrumbs = [];
        $folder = null;

        return view('drive.trash', compact('items', 'breadcrumbs', 'folder'));
    }

    public function restore(string $id)
    {
        $this->checkLogin();

        $this->client()->patch("{$this->supabaseUrl}/drive_items?id=eq.$id", [
            'is_deleted' => false,
            'deleted_at' => null,
        ]);

        return back()->with('success', 'Item berhasil dipulihkan');
    }

    public function forceDelete(string $id)
    {
        $this->checkLogin();

        $res = $this->client()
            ->get("{$this->supabaseUrl}/drive_items?id=eq.$id&select=path,type")
            ->json();

        if (!empty($res)) {
            $item = $res[0];

            if ($item['type'] === 'file' && !empty($item['path'])) {
                $localPath = str_replace('storage/', 'public/', $item['path']);
                Storage::delete($localPath);
            }
        }

        $this->client()->delete("{$this->supabaseUrl}/drive_items?id=eq.$id");

        return back()->with('success', 'Item dihapus permanen');
    }
}
