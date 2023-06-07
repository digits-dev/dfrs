<?php namespace App\Http\Controllers;

use Session;
use Request;
use DB;
use CRUDbooster;
use crocodicstudio\crudbooster\controllers\CBController;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class AdminCmsUsersController extends CBController {


	public function cbInit() {
		# START CONFIGURATION DO NOT REMOVE THIS LINE
		$this->table               = 'cms_users';
		$this->primary_key         = 'id';
		$this->title_field         = "name";
		$this->button_action_style = 'button_icon';
		$this->button_import 	   = FALSE;
		$this->button_export 	   = FALSE;
		# END CONFIGURATION DO NOT REMOVE THIS LINE

		# START COLUMNS DO NOT REMOVE THIS LINE
		$this->col = array();
		$this->col[] = array("label"=>"Name","name"=>"name");
		$this->col[] = array("label"=>"Email","name"=>"email");
		$this->col[] = array("label"=>"Privilege","name"=>"id_cms_privileges","join"=>"cms_privileges,name");
		$this->col[] = array("label"=>"Photo","name"=>"photo","image"=>1);
        $this->col[] = array("label"=>"Status","name"=>"status");
		# END COLUMNS DO NOT REMOVE THIS LINE

		# START FORM DO NOT REMOVE THIS LINE
		$this->form = array();
		$this->form[] = array("label"=>"Name","name"=>"name",'validation'=>'required|alpha_spaces|min:3','width'=>'col-md-5');
		$this->form[] = array("label"=>"Email","name"=>"email",'required'=>true,'type'=>'email','validation'=>'required|email|unique:cms_users,email,'.CRUDBooster::getCurrentId(),'width'=>'col-md-5');
		$this->form[] = array("label"=>"Photo","name"=>"photo","type"=>"upload","help"=>"Recommended resolution is 200x200px",'validation'=>'image|max:1000','resize_width'=>90,'resize_height'=>90,'width'=>'col-md-5');
		$this->form[] = array("label"=>"Privilege","name"=>"id_cms_privileges","type"=>"select","datatable"=>"cms_privileges,name",'validation'=>'required','width'=>'col-md-5');
		$this->form[] = array("label"=>"Password","name"=>"password","type"=>"password","help"=>"Please leave empty if not changed",'width'=>'col-md-5');
		if(in_array(CRUDBooster::getCurrentMethod(),['getEdit','postEditSave','getDetail'])) {
            $this->form[] = array("label"=>"Status","name"=>"status","type"=>"select","dataenum"=>"ACTIVE;INACTIVE",'width'=>'col-md-5');
        }
        # END FORM DO NOT REMOVE THIS LINE

        $this->button_selected = array();
        $this->button_selected[] = ['label'=>'Set ACTIVE Status','icon'=>'fa fa-check','name'=>'set_status_active'];
        $this->button_selected[] = ['label'=>'Set INACTIVE Status','icon'=>'fa fa-times','name'=>'set_status_inactive'];

	}

	public function getProfile() {

		$this->button_addmore = FALSE;
		$this->button_cancel  = FALSE;
		$this->button_show    = FALSE;
		$this->button_add     = FALSE;
		$this->button_delete  = FALSE;
		$this->hide_form 	  = ['id_cms_privileges'];

		$data['page_title'] = cbLang("label_button_profile");
		$data['row']        = CRUDBooster::first('cms_users',CRUDBooster::myId());

        return $this->view('crudbooster::default.form',$data);
	}
	public function hook_before_edit(&$postdata,$id) {
		// unset($postdata['password_confirmation']);
        // $postdata['status'] = Crypt::encryptString($postdata['status']);
	}
	public function hook_before_add(&$postdata) {
	    // unset($postdata['password_confirmation']);
        $postdata['status'] = 'ACTIVE';
	}

    public function actionButtonSelected($id_selected,$button_name) {
        switch ($button_name) {
            case 'set_status_active':
                DB::table('cms_users')->whereIn('id',$id_selected)->update(
                    [
                       'status' => 'ACTIVE'
                    ]);
                break;
            case 'set_status_inactive':
                DB::table('cms_users')->whereIn('id',$id_selected)->update(
                    [
                      'status' => 'INACTIVE'
                    ]);
                break;
            default:
                # code...
                break;
        }

    }
}
