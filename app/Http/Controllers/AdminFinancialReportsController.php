<?php namespace App\Http\Controllers;

    use App\Exports\ExcelTemplate;
    use App\Imports\JournalImport;
    use App\Models\Currency;
use App\Models\Customer;
use App\Models\InvoiceStatus;
    use App\Models\InvoiceType;
use App\Models\Location;
use App\Models\PaymentStatus;
    use App\Models\TradingPartner;
    use CRUDBooster;
    use Illuminate\Http\Request;
    use Maatwebsite\Excel\HeadingRowImport;
    use Maatwebsite\Excel\Imports\HeadingRowFormatter;
    use Maatwebsite\Excel\Facades\Excel;
    use DB;

	class AdminFinancialReportsController extends \crocodicstudio\crudbooster\controllers\CBController {


	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "invoice_number";
			$this->limit = "20";
			$this->orderby = "invoice_date,desc";
			$this->global_privilege = false;
			$this->button_table_action = true;
			$this->button_bulk_action = true;
			$this->button_action_style = "button_icon";
			$this->button_add = false;
			$this->button_edit = false;
			$this->button_delete = false;
			$this->button_detail = true;
			$this->button_show = true;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = false;
			$this->table = "financial_reports";
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			$this->col[] = ["label"=>"Invoice Date","name"=>"invoice_date"];
			$this->col[] = ["label"=>"Invoice Number","name"=>"invoice_number"];
			$this->col[] = ["label"=>"Invoice Type","name"=>"invoice_types_id","join"=>"invoice_types,invoice_type"];
			$this->col[] = ["label"=>"Voucher Number","name"=>"voucher_number"];
			$this->col[] = ["label"=>"Trading Partner","name"=>"trading_partners_id","join"=>"trading_partners,trading_partner"];
			$this->col[] = ["label"=>"Invoice Status","name"=>"invoice_statuses_id","join"=>"invoice_statuses,invoice_status"];
			$this->col[] = ["label"=>"Payment Status","name"=>"payment_statuses_id","join"=>"payment_statuses,payment_status"];
            $this->col[] = ["label"=>"Amount","name"=>"invoice_amount"];
            $this->col[] = ["label"=>"GL Date","name"=>"gl_date"];
            $this->col[] = ["label"=>"Desription","name"=>"description"];
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];# END FORM DO NOT REMOVE THIS LINE

			/*
	        | ----------------------------------------------------------------------
	        | Sub Module
	        | ----------------------------------------------------------------------
			| @label          = Label of action
			| @path           = Path of sub module
			| @foreign_key 	  = foreign key of sub table/module
			| @button_color   = Bootstrap Class (primary,success,warning,danger)
			| @button_icon    = Font Awesome Class
			| @parent_columns = Sparate with comma, e.g : name,created_at
	        |
	        */
	        $this->sub_module = array();


	        /*
	        | ----------------------------------------------------------------------
	        | Add More Action Button / Menu
	        | ----------------------------------------------------------------------
	        | @label       = Label of action
	        | @url         = Target URL, you can use field alias. e.g : [id], [name], [title], etc
	        | @icon        = Font awesome class icon. e.g : fa fa-bars
	        | @color 	   = Default is primary. (primary, warning, succecss, info)
	        | @showIf 	   = If condition when action show. Use field alias. e.g : [id] == 1
	        |
	        */
	        $this->addaction = array();


	        /*
	        | ----------------------------------------------------------------------
	        | Add More Button Selected
	        | ----------------------------------------------------------------------
	        | @label       = Label of action
	        | @icon 	   = Icon from fontawesome
	        | @name 	   = Name of button
	        | Then about the action, you should code at actionButtonSelected method
	        |
	        */
	        $this->button_selected = array();


	        /*
	        | ----------------------------------------------------------------------
	        | Add alert message to this module at overheader
	        | ----------------------------------------------------------------------
	        | @message = Text of message
	        | @type    = warning,success,danger,info
	        |
	        */
	        $this->alert = array();



	        /*
	        | ----------------------------------------------------------------------
	        | Add more button to header button
	        | ----------------------------------------------------------------------
	        | @label = Name of button
	        | @url   = URL Target
	        | @icon  = Icon from Awesome.
	        |
	        */
	        $this->index_button = array();
            $this->index_button[] = ['label'=>'Upload','icon'=>'fa fa-upload','color'=>'warning','url'=>route('fs.upload-view')];
            $this->index_button[] = ['label'=>'Report','icon'=>'fa fa-file','color'=>'primary','url'=>route('fs.report')];


	        /*
	        | ----------------------------------------------------------------------
	        | Customize Table Row Color
	        | ----------------------------------------------------------------------
	        | @condition = If condition. You may use field alias. E.g : [id] == 1
	        | @color = Default is none. You can use bootstrap success,info,warning,danger,primary.
	        |
	        */
	        $this->table_row_color = array();


	        /*
	        | ----------------------------------------------------------------------
	        | You may use this bellow array to add statistic at dashboard
	        | ----------------------------------------------------------------------
	        | @label, @count, @icon, @color
	        |
	        */
	        $this->index_statistic = array();



	        /*
	        | ----------------------------------------------------------------------
	        | Add javascript at body
	        | ----------------------------------------------------------------------
	        | javascript code in the variable
	        | $this->script_js = "function() { ... }";
	        |
	        */
	        $this->script_js = NULL;


            /*
	        | ----------------------------------------------------------------------
	        | Include HTML Code before index table
	        | ----------------------------------------------------------------------
	        | html code to display it before index table
	        | $this->pre_index_html = "<p>test</p>";
	        |
	        */
	        $this->pre_index_html = null;



	        /*
	        | ----------------------------------------------------------------------
	        | Include HTML Code after index table
	        | ----------------------------------------------------------------------
	        | html code to display it after index table
	        | $this->post_index_html = "<p>test</p>";
	        |
	        */
	        $this->post_index_html = null;



	        /*
	        | ----------------------------------------------------------------------
	        | Include Javascript File
	        | ----------------------------------------------------------------------
	        | URL of your javascript each array
	        | $this->load_js[] = asset("myfile.js");
	        |
	        */
	        $this->load_js = array();



	        /*
	        | ----------------------------------------------------------------------
	        | Add css style at body
	        | ----------------------------------------------------------------------
	        | css code in the variable
	        | $this->style_css = ".style{....}";
	        |
	        */
	        $this->style_css = NULL;



	        /*
	        | ----------------------------------------------------------------------
	        | Include css File
	        | ----------------------------------------------------------------------
	        | URL of your css each array
	        | $this->load_css[] = asset("myfile.css");
	        |
	        */
	        $this->load_css = array();


	    }


	    /*
	    | ----------------------------------------------------------------------
	    | Hook for button selected
	    | ----------------------------------------------------------------------
	    | @id_selected = the id selected
	    | @button_name = the name of button
	    |
	    */
	    public function actionButtonSelected($id_selected,$button_name) {
	        //Your code here

	    }


	    /*
	    | ----------------------------------------------------------------------
	    | Hook for manipulate query of index result
	    | ----------------------------------------------------------------------
	    | @query = current sql query
	    |
	    */
	    public function hook_query_index(&$query) {
	        //Your code here

	    }

	    /*
	    | ----------------------------------------------------------------------
	    | Hook for manipulate row of index table html
	    | ----------------------------------------------------------------------
	    |
	    */
	    public function hook_row_index($column_index,&$column_value) {
	    	//Your code here
	    }

	    /*
	    | ----------------------------------------------------------------------
	    | Hook for manipulate data input before add data is execute
	    | ----------------------------------------------------------------------
	    | @arr
	    |
	    */
	    public function hook_before_add(&$postdata) {
	        //Your code here

	    }

	    /*
	    | ----------------------------------------------------------------------
	    | Hook for execute command after add public static function called
	    | ----------------------------------------------------------------------
	    | @id = last insert id
	    |
	    */
	    public function hook_after_add($id) {
	        //Your code here

	    }

	    /*
	    | ----------------------------------------------------------------------
	    | Hook for manipulate data input before update data is execute
	    | ----------------------------------------------------------------------
	    | @postdata = input post data
	    | @id       = current id
	    |
	    */
	    public function hook_before_edit(&$postdata,$id) {
	        //Your code here

	    }

	    /*
	    | ----------------------------------------------------------------------
	    | Hook for execute command after edit public static function called
	    | ----------------------------------------------------------------------
	    | @id       = current id
	    |
	    */
	    public function hook_after_edit($id) {
	        //Your code here

	    }

	    /*
	    | ----------------------------------------------------------------------
	    | Hook for execute command before delete public static function called
	    | ----------------------------------------------------------------------
	    | @id       = current id
	    |
	    */
	    public function hook_before_delete($id) {
	        //Your code here

	    }

	    /*
	    | ----------------------------------------------------------------------
	    | Hook for execute command after delete public static function called
	    | ----------------------------------------------------------------------
	    | @id       = current id
	    |
	    */
	    public function hook_after_delete($id) {
	        //Your code here

	    }

        public function getDetail($id)
        {
            # code...
        }

        public function journalUploadView()
        {
            if(!CRUDBooster::isCreate() && $this->global_privilege==FALSE || $this->button_add==FALSE) {
                CRUDBooster::redirect(CRUDBooster::adminPath(),trans("crudbooster.denied_access"));
            }

            $data = [];
            $data['page_title'] = 'Upload Journal Entry';
            $data['uploadRoute'] = route('fs.upload');
            $data['uploadTemplate'] = route('fs.template');
            return view('journal.upload',$data);
        }

        public function journalUpload(Request $request)
        {
            $errors = array();
            $request->validate([
                'import_file' => 'required|mimes:csv,txt,xlx,xls|max:10000'
            ]);

			$path_excel = $request->file('import_file')->storeAs('temp',$request->import_file->getClientOriginalName(),'local');
			$path = storage_path('app').'/'.$path_excel;
            HeadingRowFormatter::default('none');
            $headings = (new HeadingRowImport)->toArray($path);
            //check headings
            $header = config('excel-template-headers.journal-entries');

			for ($i=0; $i < sizeof($headings[0][0]); $i++) {
				if (!in_array($headings[0][0][$i], $header)) {
					$unMatch[] = $headings[0][0][$i];
				}
			}

			if(!empty($unMatch)) {
                return redirect(route('fs.upload-view'))->with(['message_type' => 'danger', 'message' => 'Failed ! Please check template headers, mismatched detected.']);
			}
            HeadingRowFormatter::default('slug');
            $excelData = Excel::toArray(new JournalImport, $path);

            //data checking
            $invoiceTypes = array_unique(array_column($excelData[0], "invoice_type"));
            foreach ($invoiceTypes as $invType) {
                $invTypeDetails = InvoiceType::withName($invType);
                if(empty($invTypeDetails->id)){
                    array_push($errors, 'invoice type "'.$invType.'" not found!');
                }
            }

            $invoiceStatuses = array_unique(array_column($excelData[0], "invoice_status"));
            foreach ($invoiceStatuses as $invStatus) {
                $invStatusDetails = InvoiceStatus::withName($invStatus);
                if(empty($invStatusDetails->id)){
                    array_push($errors, 'invoice status "'.$invStatus.'" not found!');
                }
            }

            $paymentStatuses = array_unique(array_column($excelData[0], "payment_status"));
            foreach ($paymentStatuses as $paymentStatus) {
                $paymentStatusDetails = PaymentStatus::withName($paymentStatus);
                if(empty($paymentStatusDetails->id)){
                    array_push($errors, 'payment status "'.$paymentStatus.'" not found!');
                }
            }

            $tradingPartners = array_unique(array_column($excelData[0], "trading_partner"));
            foreach ($tradingPartners as $tradingPartner) {
                $tradingPartnerDetails = TradingPartner::withName($tradingPartner);
                if(empty($tradingPartnerDetails->id)){
                    array_push($errors, 'trading partner "'.$tradingPartner.'" not found!');
                }
            }

            $currencies = array_unique(array_column($excelData[0], "currency"));
            foreach ($currencies as $currency) {
                $currencyDetails = Currency::withCode($currency);
                if(empty($currencyDetails->id)){
                    array_push($errors, 'currency "'.$currency.'" not found!');
                }
            }

            if(!empty($errors)){
                return redirect()->back()->with(['message_type' => 'danger', 'message' => 'Failed ! Please check '.implode(",<br>",$errors)]);
            }

            Excel::import(new JournalImport, $path);

            return redirect(CRUDBooster::mainpath())->with(['message_type' => 'success', 'message' => 'Upload complete!']);
        }

        public function uploadTemplate()
        {
            $header = config('excel-template-headers.journal-entries');
            $export = new ExcelTemplate([$header]);
            return Excel::download($export, 'journal-'.date("Ymd").'-'.date("h.i.sa").'.csv');

        }

        public function journalReport()
        {
            $data = [];
            $data['page_title'] = 'Generate Report';
            $data['companies'] = Customer::active();
            $data['locations'] = Location::active();
            return view('journal.report',$data);
        }

        public function generateReport(Request $request)
        {
            // dd($request->all());

            $data = [];
            $data['page_title'] = 'PNL Report';
            $pnl_report = new FinancialReportController();

            switch ($request->report_type) {
                case 'month':
                    {
                        $data = $pnl_report->byCompanyYearMonth($request);
                        // dd($data);
                        return view('journal.pnl-by-month',$data);
                    }
                    break;

                case 'year':
                    {
                        $data = $pnl_report->byCompanyYear($request);
                        // dd($data);
                        return view('journal.pnl-by-year',$data);
                    }
                    break;

                case 'location':
                    {
                        $data = $pnl_report->byLocationYearMonth($request);
                        // dd($data);
                        return view('journal.pnl-by-location',$data);
                    }
                    break;

                default:
                    # code...
                    break;
            }


        }
	}
