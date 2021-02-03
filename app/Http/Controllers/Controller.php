<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use League\Flysystem\FileNotFoundException;
use Auth;
use App\Models\Subsidy;
use App\Models\Award;
use Xthiago\PDFVersionConverter\Guesser\RegexGuesser;
use Symfony\Component\Filesystem\Filesystem;
use Xthiago\PDFVersionConverter\Converter\GhostscriptConverterCommand;
use Xthiago\PDFVersionConverter\Converter\GhostscriptConverter;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function mergePDF($files = array(), $mergedFile = 'pdfs/merged.pdf')
    {
        $merger = \PDFMerger::init();
        if(count($files) > 0){
            foreach ($files as $pdf) {
                $merger->addPathToPDF(base_path('storage/app/public/'.$pdf), 'all');
            }
        }
        if (file_exists(base_path('storage/app/public/'.$mergedFile))) {
            unlink(base_path('storage/app/public/'.$mergedFile));
        }
        $merger->merge();
        $status = $merger->save(base_path('storage/app/public/'.$mergedFile));
        return $status;
    }

    public function storeMerged(string $appFullName, string $attFullName, string $mergedFullName) {
        $app = Storage::disk('noguchi')->get($appFullName);
        Storage::disk('tmp')->put($appFullName, $app);
        $att = Storage::disk('noguchi')->get($attFullName);
        Storage::disk('tmp')->put($attFullName, $att);
        $pdfMerger = \PdfMerger::init(); //(2)
        $pdfMerger->addPDF(storage_path('app/public/tmp/'.$appFullName));
        $pdfMerger->addPDF(storage_path('app/public/tmp/'.$attFullName));
        $pdfMerger->merge();
        $pdfMerger->save(storage_path('app/public/tmp/'.$mergedFullName));

        $merged = Storage::disk('tmp')->get($mergedFullName);
        Storage::disk('noguchi')->put($mergedFullName, $merged);
        Storage::disk('tmp')->deleteDirectory(date('Y'));
    }

    public function deleteOld(string $old) {
        try {
            Storage::disk('noguchi')->delete($old);
        }catch(FileNotFoundException $fe ){
            return;
        }
    }

    public function moveOld (string $old, string $new) {
        try {
            Storage::disk('noguchi')->move($old, $new);
        }catch(FileNotFoundException $fe ){
            return;
        }
    }


}
