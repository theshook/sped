<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDF;

class ReportsController extends Controller
{
    protected $report_data;
    
    public function __construct() {
        $this->middleware('auth');
        $this->report_data = [
            'title' => 'Hello World',
            'heading' => 'Hello World',
            'content' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.'        
        ];
	}

    public function index() {
		return view('pages.backend.reports.index');
    }

	public function viewPDF() {
    	return view('pages.backend.reports.progressReport', $this->report_data);
    }    
    
    public function printPDF()
	{		  
		$pdf = PDF::loadView('pages.backend.reports.progressReport', $this->report_data);  
		return $pdf->download('progress_report.pdf');
	}
}
