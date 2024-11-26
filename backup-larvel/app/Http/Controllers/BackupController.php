<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Spatie\Backup\BackupDestination\Backup;

class BackupController extends Controller
{


//------------------------------- Method Backup with package --------------------------\\
//------------------------------------------------------------------\\
    public function index()
    {
        $backups = [];
        $id = 0;
        foreach (glob(storage_path() . '/app/backups/*') as $filename) {
            $item['id'] = $id += 1;
            $item['name'] = basename($filename);
            $size = $this->formatSizeUnits(filesize($filename));
            $item['size'] = $size;

            $backups[] = $item;
        }
        $totalRows = sizeof($backups);
        return view('backups.index', compact('backups'));
    }
    public function store()
    {
        // Trigger a new backup here
        \Artisan::call('backup:run');

        return redirect()->route('backups.all')->with('success', 'Backup created successfully');
    }

    public function destroy(Request $request,$name)
    {
        foreach (glob(storage_path() . '/app/backups/*') as $filename) {
            $path = storage_path() . '/app/backups/' . basename($name);
/*            if (file_exists($path)) {
                @unlink($path);
            }*/
            if (File::exists($path)) {
                File::delete($path);
                return redirect()->route('backups.all')->with('success', 'Backup deleted successfully.');
            } else {
                return redirect()->route('backups.all')->with('error', 'Backup not found.');
            }
        }
        return response()->json('Deleted complete success');
    }


//------------------------------- Method Backup without package --------------------------\\
//------------------------------------------------------------------\\
    public function index2(Request $request)
    {
        $data = [];
        $id = 0;
        foreach (glob(storage_path() . '/app/public/backup/*') as $filename) {
            $item['id'] = $id += 1;
            $item['date'] = basename($filename);
            $size = $this->formatSizeUnits(filesize($filename));
            $item['size'] = $size;
            $data[] = $item;
        }
        $totalRows = sizeof($data);
        return response()->json([
            'backups' => $data,
            'totalRows' => $totalRows,
        ]);
    }

    //-------------------- Generate Databse -------------\\

    public function store2(Request $request)
    {

        Artisan::call('database:backup');

        return response()->json('Generate complete success');
    }

    //-------------------- Delete Databse -------------\\

    public function destroy2( $name)
    {
        foreach (glob(storage_path() . '/app/public/backup/*') as $filename) {
            $path = storage_path() . '/app/public/backup/' . basename($name);
            if (file_exists($path)) {
                @unlink($path);
            }
            if (File::exists($path)) {
                File::delete($path);
                return redirect()->route('backups.all')->with('success', 'Backup deleted successfully.');
            } else {
                return redirect()->route('backups.all')->with('error', 'Backup not found.');
            }
        }
        return response()->json('Deleted complete success');
    }


    //-------------------- Fomrmat units -------------\\

    public function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }
        return $bytes;
    }
}
