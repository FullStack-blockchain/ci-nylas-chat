<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * XWB Purchasing
 *
 * @package     XWB Purchasing
 * @author      Jay-r Simpron
 * @copyright   Copyright (c) 2017, Jay-r Simpron
 */

/**
 * Main controller for Help
 */
class Xwb_help extends XWB_purchasing_base
{

    /**
     * Run parent construct
     *
     * @return Null
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('form_validation'));
    }
    

    /**
     * Help view
     *
     * @return mixed
     */
    
    public function index()
    {
        $this->redirectUser();
        $data['page_title'] = 'Help'; //title of the page
        $data['page_script'] = 'help'; // script filename of the page user.js
        $group_name = $this->log_user_data->group_name;

        $data['doc_helper'] = $this->getDocumentHelper($group_name);

        $this->renderPage('help/help', $data);
    }


    /**
     * Get Document Helper per User role
     *
     * @param  string $group_name [Users Role]
     * @return string
     */
    public function getDocumentHelper($group_name = "members")
    {

        $document_helper['member'] = ' <tr>
					    	<td>Members User Manual</td>
					    	<td>
					    		<a target="_blank" href="http://54.152.61.40/docs/purchasing/general_user_manual.html" class="btn btn-xs btn-info">View</a>
					    	</td>
					    </tr>';

        $document_helper['canvasser'] = '<tr>
					    	<td>Canvasser User Manual</td>
					    	<td>
					    		<a target="_blank" href="http://54.152.61.40/docs/purchasing/canvasser_user_manual.html" class="btn btn-xs btn-info">View</a>
					    	</td>
					    </tr>';
        $document_helper['budget'] = '<tr>
					    	<td>Budget User Manual</td>
					    	<td>
					    		<a target="_blank" href="http://54.152.61.40/docs/purchasing/budget_user_manual.html" class="btn btn-xs btn-info">View</a>
					    	</td>
					    </tr>';
        $document_helper['auditor'] = '<tr>
					    	<td>Auditor User Manual</td>
					    	<td>
					    		<a target="_blank" href="http://54.152.61.40/docs/purchasing/auditor_user_manual.html" class="btn btn-xs btn-info">View</a>
					    	</td>
					    </tr>';

        $document_helper['admin'] = '<tr>
					    	<td>Admin User Manual</td>
					    	<td>
					    		<a target="_blank" href="http://54.152.61.40/docs/purchasing/admin_user_manual.html" class="btn btn-xs btn-info">View</a>
					    	</td>
					    </tr>';

        $document_helper['process'] = '<tr>
					    	<td>Process Manual</td>
					    	<td>
					    		<a target="_blank" href="http://54.152.61.40/docs/purchasing/process_manual.html" class="btn btn-xs btn-info">View</a>
					    	</td>
					    </tr>';

        $document_helper['property'] = '<tr>
					    	<td>Property User Manual</td>
					    	<td>
					    		<a target="_blank" href="http://54.152.61.40/docs/purchasing/property_user_manual.html" class="btn btn-xs btn-info">View</a>
					    	</td>
					    </tr>';

        
        $group['admin'] =(array)$document_helper; //all manual
        $group['members'] = (array)$document_helper['member'];
        $group['canvasser'] = (array)$document_helper['canvasser'];
        $group['property'] = (array)$document_helper['property'];
        $group['board'] = (array)$document_helper;
        //$group['purchasing_committee'] = (array)$document_helper;
        $group['auditor'] = (array)$document_helper['process'];
        $group['budget'] = (array)$document_helper['budget'];
        $group['budget'] = (array)$document_helper['budget'];

        return $group[$group_name];
    }
}
