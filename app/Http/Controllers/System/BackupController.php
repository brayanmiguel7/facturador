<?php

namespace App\Http\Controllers\System;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use DateTime;
use Artisan;
use Config;
use Exception;
use App\Traits\BackupTrait;


class BackupController extends Controller
{

    use BackupTrait;

    public function index() {

        $avail = new Process('df -m -h --output=avail /');
        $avail->run();
        $disc_used = $avail->getOutput();

        $df = new Process('du -sh '.storage_path().' | cut -f1');
        $df->run();
        $storage_size = $df->getOutput();

        $most_recent = $this->mostRecent();

        return view('system.backup.index')->with('disc_used', $disc_used)->with('storage_size', $storage_size)->with('last_zip', $most_recent);
    }

    public function db()
    {
        $output = Artisan::call('bk:bd');
        return json_encode($output);
    }

    public function files()
    {
        $output = Artisan::call('bk:files');
        return json_encode($output);
    }

    public function upload(Request $request)
    {

        $config = [
            'driver' => 'ftp',
            'host'   => $request['host'],
            'port' => $request['port'],
            'username' => $request['username'],
            'password'   => $request['password'],
            'port'  => 21,
            'passive'   => true,
        ];

        Config::set('filesystems.disks.ftp', $config);

        // definimos y subimos el archivo
        try {

            $most_recent = $this->mostRecent();

            $fileTo = $most_recent['name'];
            // $fileFrom = storage_path('app/'.$most_recent['path']);

            $fileFrom = Storage::get($most_recent['path']);

            $upload = Storage::disk('ftp')->put($fileTo, $fileFrom);
            
            return [
                'success' => $upload,
                'message' => 'Proceso finalizado satisfactoriamente'
            ];


        } catch (Exception $e) {
            
            $this->setErrorLog($e);
            return $this->getErrorMessage("Lo sentimos, ocurrió un error inesperado: {$e->getMessage()}");

        }

    }

    public function mostRecent()
    {
        $zips = Storage::allFiles('backups/zip/');

        if (count($zips) > 0) {
            $name_zips = [];
            $most_recent_time = '';

            foreach($zips as $zip){
                $zip_explode = explode( '/', $zip);
                if(count($zip_explode) <= 3){
                    array_push($name_zips, $zip_explode[2]);
                    $last = Storage::lastModified($zip);
                    $datetime = new DateTime("@$last");
                    if ($datetime > $most_recent_time) {
                        $most_recent_time = $datetime;
                        $most_recent_path = $zip;
                        $most_recent_name = $zip_explode[2];
                    }
                }
            }

            return [
                'path' => $most_recent_path,
                'name' => $most_recent_name
            ];
        } else {
            return '';
        }
    }

    
    public function download($filename)
    {

        return Storage::download('backups'.DIRECTORY_SEPARATOR.'zip'.DIRECTORY_SEPARATOR.$filename);
 
    }
    
}
