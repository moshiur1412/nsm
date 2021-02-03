<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Judge;
use App\Models\Subsidy;
use App\Models\Award;
use App\Http\Requests\StoreJudgeRequest;
use App\Http\Requests\UpdateJudgeRequest;
use Auth;
use ZipArchive;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;
use Storage;
use Carbon\Carbon;

class JudgeApplicationController extends Controller
{

	protected $judges;
	protected $business_year;

	public function __construct(Judge $judge, Subsidy $subsidy, Award $award)
	{
		$this->judges = $judge;
		$this->subsidy = $subsidy;
		$this->award = $award;
		$this->middleware('auth:judge');
		$carbon = Carbon::now();
		$carbon1 = new Carbon(date('Y').'-01-01');
		$carbon2 = new Carbon(date('Y').'-04-01');
		if ($carbon->between($carbon1, $carbon2)) {
			$this->business_year = date("Y") - 1;
		}else{
			$this->business_year = date("Y");
		}
	}

	public function judgeSubsidyApplication()
	{

		$judgeType = Auth::user()->role;

		if($judgeType == 2){
			$subsidies = $this->subsidy::where('offer_year', $this->business_year)->get();
		}else{

			$subsidies = $this->subsidy::where('offer_year', $this->business_year)->where('primary_granted', 1)->get();
		}
		return view('backend.judges.judgeSubsidyApplication', compact('subsidies'));

	}

	public function getSubsidyFTPFiles($id)
	{
		$subsidy = $this->subsidy::findOrFail($id);

		$file_ftp_ref = Storage::disk('noguchi')->get(date('Y', strtotime($subsidy->created_at)).'/subsidy/'. $subsidy->reference_path);
		$file_local_ref = Storage::disk('local')->put($subsidy->reference_path, $file_ftp_ref);

		$file_ftp_mar = Storage::disk('noguchi')->get(date('Y', strtotime($subsidy->created_at)).'/subsidy/'. $subsidy->merged_path);
		$file_local_mar = Storage::disk('local')->put($subsidy->merged_path, $file_ftp_mar);

		$localFileStore = [storage_path('app/public/'.$subsidy->merged_path),storage_path('app/public/'.$subsidy->reference_path)];

		// dd($localFileStore);

		return $localFileStore;

	}

	public function downloadSubsidyFiles(Request $request)
	{

		// Checking files are selected
		$zip = new ZipArchive(); // Load zip library
		$zip_name = 'noguchi_subsidy_'.time().".zip"; // Zip name
		if($zip->open($zip_name, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE)!==TRUE)
		{
			// Opening zip file to load files
			\Log::info("JudgeApplicationController->downloadSubsidyFiles()::" . "* Sorry, ZIP creation failed at this time");
		}

		$file_created_year = $request->files_created;
		$file_id = $request->files_id;
		$file_name = $request->files_pdf;

		foreach($request->files_pdf as $key=>$file)
		{

			// $localFileStoreListNum(複数選択時の$localFileStoreの配列対策)
			if (($key % 2 ) == 0){
				$localFileStoreListNum = 0;
			}else{
				$localFileStoreListNum = 1;
			}

			// Locally Stored Files downloaded -->
			$zip->addFile($this->getSubsidyFTPFiles($file_id[$key])[$localFileStoreListNum], $file_name[$key]);

		}
		if($zip->close()){
			unlink($this->getSubsidyFTPFiles($file_id[$key])[$localFileStoreListNum]);
		}
		if(file_exists($zip_name))
		{
			// push to download the zip
			header('Content-type: application/zip');
			header('Content-Disposition: attachment; filename="'.$zip_name.'"');
			readfile($zip_name);
			// remove zip file is exists in temp path
			unlink($zip_name);
		}
		//delete temporaries
		foreach($request->files_pdf as $key=>$file)
		{
			Storage::disk('local')->delete($file_name[$key]);
		}
		return Redirect::back();
	}


	public function judgeAwardApplication()
	{
		$awards = $this->award::where('offer_year', $this->business_year)->get();
		return view('backend.judges.judgeAwardApplication', compact('awards'));
	}

	public function getAwardFTPFiles($id)
	{
		$award = $this->award::findOrFail($id);

		$file_ftp = Storage::disk('noguchi')->get(date('Y', strtotime($award->created_at)).'/award/'. $award->attachment_path);

		$file_local = Storage::disk('local')->put($award->attachment_path, $file_ftp);

		$localFileStore = storage_path('app/public/'.$award->attachment_path);

		return $localFileStore;

	}

	public function downloadAwardFiles(Request $request)
	{

		// Checking files are selected
		$zip = new ZipArchive(); // Load zip library
		$zip_name = 'noguchi_award_'.time().".zip"; // Zip name
		if($zip->open($zip_name, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE)!==TRUE)
		{
			// Opening zip file to load files
			\Log::info("JudgeApplicationController->downloadAwardFiles()::" . "* Sorry, ZIP creation failed at this time");
		}

		$file_created_year = $request->files_created;
		$file_id = $request->files_id;
		$file_name = $request->files_pdf;

		// \Log::info($request);

		foreach($request->files_pdf as $key=>$file)
		{

			// Locally Stored Files downloaded -->
			$zip->addFile($this->getAwardFTPFiles($file_id[$key]), $file_name[$key]);

		}
		if($zip->close()){
			unlink($this->getAwardFTPFiles($file_id[$key]));
		}
		if(file_exists($zip_name))
		{
			// push to download the zip
			header('Content-type: application/zip');
			header('Content-Disposition: attachment; filename="'.$zip_name.'"');
			readfile($zip_name);
			// remove zip file is exists in temp path
			unlink($zip_name);

		}

		return Redirect::back();
	}



}
