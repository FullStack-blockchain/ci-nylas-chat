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
 * Main controller for Purchase order
 */
class Xwb_purchase_order extends XWB_purchasing_base
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
        $this->load->model('purchase_order/Purchase_order_model', 'PO');
    }
    


    /**
     * View Purchases Order
     *
     * @return mixed
     */
    public function index()
    {
        $this->redirectUser(array('admin','board'));
        
        $groups = $this->db->get('groups');
        $data['groups'] = $groups->result();
        $data['page_title'] = 'Purchase Order'; //title of the page
        $data['page_script'] = 'purchase_order'; // script filename of the page user.js
        $this->renderPage('purchase_order/purchase_order', $data);
    }



    /**
     * Generate Purchase Order
     *
     * @param int $request_id
     * @return mixed
     */
    public function gen_po($request_id)
    {
        $this->redirectUser(array('admin','board'));
        $this->load->model('supplier/Supplier_model', 'Supplier');
        $this->load->model('request/Request_model', 'Request');
        $this->load->model('user/User_model', 'User');

        
        $groups = $this->db->get('groups');
        $data['groups'] = $groups->result();

        $items = $this->Request->getItemAuditUnapproveByRequest($request_id)->result();
        $data['request_id'] = $request_id;
        $data['request'] = $this->Request->getRequest($request_id)->row();

        $data['items'] = $items;
        $data['supplier'] = $this->extractSupplierFromItems($items);

        $data['auditor'] = $this->User->getUsersByGroup('auditor')->result();
        $data['page_title'] = 'Generate Purchase Order'; //title of the page
        $data['page_script'] = 'gen_po'; // script filename of the page user.js
        $this->renderPage('purchase_order/gen_po', $data);
    }


    /**
     * Extract supplier key and value
     *
     * @param type|array $data
     * @return array
     */
    protected function extractSupplier($data = array())
    {
        $supp = [];
        foreach ($data as $key => $value) {
            $supp[$value->id] = $value->supplier_name;
        }
        return $supp;
    }


    /**
     * Extract supplier key and value from items
     *
     * @param type|array $data
     * @return array
     */
    protected function extractSupplierFromItems($data = array())
    {
        $supp = [];
        $couter = 1;

        foreach ($data as $key => $value) {
            if ($value->supplier_id == 0 && $value->supplier != "") {
                $supp['new_'.$couter] = $value->supplier;
                $couter++;
            } else {
                if ($value->supplier != "") {
                    $supp[$value->supplier_id] = $value->supplier;
                }
            }
        }

        return array_unique($supp);
    }


    public function getPOItems()
    {
        $this->load->model('request/Request_model', 'Request');
        $request_id = $this->input->get('request_id');
        $supplier = $this->input->get('supplier');
        $sup_text = $this->input->get('sup_text');

        $column = 'supplier';

        if ($supplier != "") {
            if (ctype_digit($supplier)) {
                $column = 'supplier_id';
            } else {
                $column = 'supplier';
                $supplier = $sup_text;
            }
        } else {
            $supplier = null;
        }
        


        //$i = $this->Request->getPOItems($request_id,$supplier,$column);
        $i = $this->Request->getUnapproveAuditPOItems($request_id, $supplier, $column);

        //pre($this->db->last_query());

        $data['data'] = array();
        $sum = 0;

        if ($i->num_rows()>0) {
            foreach ($i->result() as $key => $v) {
                $total = $v->quantity * $v->unit_price;
                $sum = $sum + $total;
                $input = '<input type="hidden" value="'.$v->id.'" name="items['.$v->id.']" class="items">';
                $data['data'][] = array(
                                        $v->id.$input,
                                        $v->product_name,
                                        $v->product_description,
                                        $v->quantity,
                                        number_format($v->unit_price, 2, '.', ','),
                                        number_format($total, 2, '.', ',')
                                        );
            }
        }
        $data['total_amount'] = $sum;
        echo $this->xwbJsonEncode($data);
    }



    /**
     * Add/Update purchase order
     *
     * @return json
     */
    public function updatePO()
    {

        $po_id = $this->input->post('id');
        $po_unique = "";
        $pr_unique = "";

        if ($po_id != '') {
            $po = $this->PO->getPO($po_id)->row();

            if ($po->po_num != $this->input->post('po_num')) {
                $po_unique = "|is_unique[purchase_order.po_num]";
            }
        } else {
            $po_unique = "|is_unique[purchase_order.po_num]";
        }


        $this->form_validation->set_rules('po_num', 'P.O. Number', 'required'.$po_unique);
        $this->form_validation->set_rules('pr_number', 'P.R. Number', 'required');
        $this->form_validation->set_rules('supplier', 'Supplier', 'required');
        $this->form_validation->set_rules('date_issue', 'Date Prepared', 'required');
        $this->form_validation->set_rules('payment_terms', 'Payment Terms', 'required');
        $this->form_validation->set_rules('warranty_condition', 'Warranty Condition', 'required');
        $this->form_validation->set_rules('items[]', 'Items/Products', 'required');
        $this->form_validation->set_rules('auditor', 'Approved By', 'required');
        $this->form_validation->set_rules('request_id', 'Request ID', 'required');
        

        if ($this->form_validation->run() == false) {
            $data['status'] = false;
            $data['message'] = validation_errors();
        } else {
            $posts = $this->input->post();

            $this->load->model('request/Request_model', 'Request');
            $request = $this->Request->getRequest($posts['request_id'])->row();
            

            if (ctype_digit($posts['supplier'])) {
                $supplier_id = $posts['supplier'];
                $supplier = $posts['vendor_name'];
                $supp_column = 'supplier_id';
                $supplier_search = $supplier_id;
            } else {
                $supplier_id = 0;
                $supplier = $posts['vendor_name'];
                $supp_column = 'vendor_name';
                $supplier_search = $supplier;
            }

            $supp = $this->PO->getUnapprovePOBySupplierRequest($posts['request_id'], $supplier_search, $supp_column);

            $db_data = array(
                    'requestor' =>$request->user_id,
                    'request_id' =>$posts['request_id'],
                    'pr_number' =>$posts['pr_number'],
                    'po_num' =>$posts['po_num'],
                    'supplier_invoice' =>$posts['supplier_invoice'],
                    'rr_num' =>$posts['rr_num'],
                    'warranty_condition' =>$posts['warranty_condition'],
                    //'requisitioner' =>$request->full_name,
                    'approve_by' =>$posts['auditor'],
                    'prepared_by' => $this->session->userdata['user_id'],
                    'payment_terms' => $posts['payment_terms'],
                    'date_issue' => $posts['date_issue'],
                    'supplier_id' => $supplier_id,
                    'delivery_date' => $posts['delivery_date'],
                    'vendor_name' => $supplier,
                    'total_amount' => $posts['total_amount'],
                );

            
            if ($supp->num_rows()>0) { // update
                $this->db->where('id', $posts['id']);
                $res = $this->db->update('purchase_order', $db_data);
                $po_id = $posts['id'];
            } else { // insert
                $res = $this->db->insert('purchase_order', $db_data);
                $po_id = $this->db->insert_id();
            }


            $this->load->model('user/User_model', 'User');

            /**
             * Assigning shortcode for email
             *
             * user_to
             * user_from
             * request_id
             * message
             * po
             * item
             */

            $shortcodes = array(
                    'user_to' => $posts['auditor'],
                    'message' => $this->input->post('response'),
                    'request_id' => $posts['request_id'],
                    'po' => $po_id,
                );

            $this->xwb_purchasing->setShortCodes($shortcodes);

            $condition = $this->xwb_purchasing->getShortCodes();
            /* sending email notification */
            $msg = $this->xwb_purchasing->getMessage('for_audit');
            $message = do_shortcode($msg['message'], $condition);
            $site_title = $this->config->item('site_title');
            $this->xwb_purchasing->sendmail($condition['email_to'], $msg['subject'], $message, $condition['email_from'], $site_title, $msg['subject']);

            $this->xwb_purchasing->addHistory('request_list', $posts['request_id'], 'Forward to audit', 'Forwarded to audit for review', $this->log_user_data->user_id);


            foreach ($posts['items'] as $key => $value) {
                $this->db->where('id', $value);
                $this->db->update('po_items', array('po_id'=>$po_id));
            }
            
            if ($res) {
                $data['status'] = true;
                $data['message'] = 'Purchase order updated';
            } else {
                $data['status'] = false;
                $data['message'] = 'Error updating data, please contact the programmer';
            }
        }

        echo $this->xwbJsonEncode($data);
    }


    /**
     * Get purchase order to populate form inputs
     *
     * @return json
     */
    public function getPOBySupplier()
    {
        $this->load->model('supplier/Supplier_model', 'Supplier');
        $supplier = $this->input->get('supplier');
        $request_id = $this->input->get('request_id');

        if (ctype_digit($supplier)) {
            $supp_column = 'supplier_id';
        } else {
            $supp_column = 'vendor_name';
        }

        $s = $this->Supplier->getSupplier($supplier)->row();


        //$supp = $this->PO->getPOBySupplierRequest($request_id, $supplier, $supp_column);
        $supp = $this->PO->getUnapprovePOBySupplierRequest($request_id, $supplier, $supp_column);


        if ($supp->num_rows()>0) {
            $data['PO'] = $supp->row();
            unset($data['PO']->{"supplier_id"});
            $data['PO']->url = base_url('purchase_order/preview/'.$supp->row()->id);
        } else {
            $data['PO'] = 0;
            $po = $this->PO->getLastPO();
            
            if ($po->num_rows()>0) {
                $po_id = $po->row()->id+1;
            } else {
                $po_id = 1;
            }


            $data['pr_num'] = sprintf('PR-%08d', $request_id);
            $data['po_num'] = sprintf('PO-%08d', $po_id);
            $data['payment_terms'] = ($s == null ? "":$s->payment_terms);
        }

        echo $this->xwbJsonEncode($data);
    }


    /**
     * Preview of purchase order form
     *
     * @param int $po_id
     * @return mixed
     */
    public function preview($po_id)
    {
        $this->load->model('request/Request_model', 'Request');
        $this->load->model('branch/Branch_model', 'Branch');
        $branches = $this->Branch->getBranch()->result_array();
        $branches = array_column($branches, 'description');
        $branches = implode(' * ', $branches);

        $po = $this->PO->getPO($po_id)->row();

        $request = $this->Request->getRequest($po->request_id)->row();
        $po_items = $this->Request->getItemsPerPO($po_id)->result();
        $po_date = '';
        if ($po->status==1) {
            $po_date = date('F j, Y, g:i a', strtotime($po->date_updated));
        }

        $this->load->library('pdf');
        $pdf = $this->pdf->load();
        $stylesheet = file_get_contents(FCPATH.'assets/css/pdfstyle.css');
        ob_start();
        ?>
        <h3 class="text-center"><?php echo getConfig('company_name'); ?></h3>
        <p class="text-center"><?php echo $branches; ?></p>
        <p class="text-center">Purchasing Department</p>
        <br />
        <p class="text-center"><strong>Purchasing Order Form</strong></p>
        <p class="pull-right width-150 underline"><b>P.O. No.:</b> <?php echo $po->po_num; ?></p>
        <p class="pull-right width-150 underline"><b>P.R. No.:</b> <?php echo $po->pr_number; ?></p>
        <br />
        <br />
        
        <table border="1" cellpadding="1" cellspacing="0">
            <tbody>
                <tr>
                    <td colspan="4"><b>Supplier:</b> <?php echo $po->vendor_name; ?> </td>
                    <td colspan="2"><b>Date:</b> <?php echo date('F j, Y', strtotime($po->date_issue)); ?></td>
                </tr>
                <tr>
                    <td colspan="2"><b>Supplier Invoice No:</b> <?php echo $po->supplier_invoice; ?></td>
                    <td colspan="2"><b>R.R. No:</b> <?php echo $po->rr_num; ?></td>
                    <td colspan="2"><b>Delivery Date:</b> <?php echo date('F j, Y', strtotime($po->delivery_date)); ?></td>
                </tr>
                <tr>
                    <td height="60" valign="top" colspan="2"><b>Terms of Payment:</b> <?php echo getPaymentTerm($po->payment_terms); ?></td>
                    <td height="60" valign="top" colspan="2"><b>Condition of Warranty:</b> <?php echo $po->warranty_condition; ?></td>
                    <td width="150" height="60" valign="top" colspan="2" align="justify"><b>Penalty Clause:</b> <p class="text-8"><?php echo getConfig('penalty_clause'); ?><p></td>    
                </tr>
                <tr>
                    <td colspan="6"></td>
                </tr>
                <tr>
                    <td colspan="3"><p><strong>PRODUCT / SERVICE SPECIFICATIONS</strong></p></td>
                    <td><p><strong>QUANTITY</strong></p></td>
                    <td><p><strong>UNIT PRICE</strong></p></td>
                    <td><p><strong>AMOUNT</strong></p></td>
                </tr>
                <?php
                $sum = 0;
                foreach ($po_items as $key => $value) : ?>
                    <tr>
                        <td colspan="3"><?php
                            $product_description = ($value->product_description==""?"":"/ ".$value->product_description);
                            echo $value->product_name. $product_description;
                            ?>
                        </td>
                        <td><?php echo $value->quantity;?></td>
                        <td><?php echo number_format($value->unit_price, 2, '.', ','); ?></td>
                        <td>
                            <?php
                            $total = $value->unit_price * $value->quantity;
                            $sum = $sum + $total;
                            echo number_format($total, 2, '.', ',');
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td align="right" colspan="5"><strong>Total:</strong></td>
                    <td><strong><?php echo number_format($sum, 2, '.', ','); ?></strong></td>
                </tr>
            </tbody>
        </table>
        <hr />

        <table border="1" cellpadding="1" cellspacing="0">
            <tr><td colspan="6">
                <strong>NOTE:</strong> <p class="text-8"><?php echo getConfig('PO_note'); ?></p>
            </td></tr>
            <tr>
                <td colspan="2" valign="top">
                <strong>Requisitioner:</strong>
                <p><?php echo $request->full_name; ?></p>
                </td>
                <td colspan="2" rowspan="2"><strong>Certified By:</strong>
                    <br /><br />
                    <div>
                        <p><?php echo ucwords($po->auditor); ?></p>
                        <p class="upperline"> Auditor</p>
                        
                        <br />
                        <p class="text-10"><strong>Date:</strong> <?php echo $po_date; ?></p>
                    </div>
                </td>
                <td width="150" colspan="2" rowspan="2" class="text-justify"><p class="text-8"><?php echo getConfig('PO_reminder'); ?></p></td>
            </tr>
            <tr>
                <td colspan="2" valign="top"><strong>Prepared By:</strong>
                    <p><?php echo ucwords($po->prepared_by); ?></p>
                </td>
            </tr>
        </table>
        <?php
        $html = ob_get_contents();
        ob_end_clean();
        $pdf->SetTitle($request->request_name." | ".$po->vendor_name);
        $pdf->WriteHTML($stylesheet, 1);
        $pdf->WriteHTML($html);
        $pdf->Output($request->request_name.'-'.$po->vendor_name, 'I');
    }



    /**
     * get PO list for datatable
     *
     * @param type|string $value
     * @return type
     */
    public function getPO()
    {
        $po = $this->PO->getPOs();
        
        $data['data'] = array();

        if ($po->num_rows()>0) {
            foreach ($po->result() as $key => $v) {
                $disabled = "";

                if ($v->status == 1) {
                    $disabled = "disabled";
                }

                $data['data'][] = array(
                                        $v->po_num,
                                        $v->pr_number,
                                        $v->request_name,
                                        $v->vendor_name,
                                        $this->xwb_purchasing->getStatus('purchase_order', $v->status).'<label class="badge">'.time_elapse($v->date_updated).'<label>',
                                        ucfirst($v->auditor),
                                        $v->auditor_remarks,
                                        '<a target="_blank" href="'.base_url('purchase_order/preview/'.$v->id).'" class="btn btn-xs btn-info">View</a>
										<a href="'.base_url('purchase_order/view_update/'.$v->id).'" class="btn btn-xs btn-warning '.$disabled.'">Update</a>',
                                        );
            }
        }
        echo $this->xwbJsonEncode($data);
    }


    /**
     * View update purchase order
     *
     * @param int $po_id
     * @return mixed
     */
    public function view_update($po_id)
    {
        $this->redirectUser(array('admin','board'));
        $this->load->model('supplier/Supplier_model', 'Supplier');
        $this->load->model('request/Request_model', 'Request');
        $this->load->model('user/User_model', 'User');

        
        $groups = $this->db->get('groups');
        $data['groups'] = $groups->result();
        $po = $this->PO->getPO($po_id)->row();
        $request_id = $po->request_id;
        
        $items = $this->Request->getItemsPerPO($po_id)->result();
        
        $data['po'] = $po;
        $data['request_id'] = $request_id;
        $data['request'] = $this->Request->getRequest($request_id)->row();
        $data['items'] = $items;
        $data['auditor'] = $this->User->getUsersByGroup('auditor')->result();
        $data['page_title'] = 'Update Purchase Order'; //title of the page
        $data['page_script'] = 'update_po'; // script filename of the page
        $this->renderPage('purchase_order/update_po', $data);
    }

    public function reupdatePO()
    {

        $this->form_validation->set_rules('po_num', 'P.O. Number', 'required');
        $this->form_validation->set_rules('supplier_name', 'Supplier Name', 'required');
        $this->form_validation->set_rules('pr_number', 'P.R. Number', 'required');
        $this->form_validation->set_rules('date_issue', 'Date Prepared', 'required');
        $this->form_validation->set_rules('payment_terms', 'Payment Terms', 'required');
        $this->form_validation->set_rules('warranty_condition', 'Warranty Condition', 'required');
        $this->form_validation->set_rules('items[]', 'Items/Products', 'required');
        $this->form_validation->set_rules('auditor', 'Approved By', 'required');
        $this->form_validation->set_rules('request_id', 'Request ID', 'required');
        

        if ($this->form_validation->run() == false) {
            $data['status'] = false;
            $data['message'] = validation_errors();
        } else {
            $posts = $this->input->post();

            $this->load->model('request/Request_model', 'Request');
            $request = $this->Request->getRequest($posts['request_id'])->row();

            $db_data = array(
                    'requestor' =>$request->user_id,
                    'request_id' =>$posts['request_id'],
                    'pr_number' =>$posts['pr_number'],
                    'po_num' =>$posts['po_num'],
                    'supplier_invoice' =>$posts['supplier_invoice'],
                    'rr_num' =>$posts['rr_num'],
                    'warranty_condition' =>$posts['warranty_condition'],
                    //'requisitioner' =>$posts['requisitioner'],
                    'approve_by' =>$posts['auditor'],
                    'prepared_by' => $this->session->userdata['user_id'],
                    'payment_terms' => $posts['payment_terms'],
                    'date_issue' => $posts['date_issue'],
                    'delivery_date' => $posts['delivery_date'],
                    'vendor_name' => $posts['supplier_name'],
                    'pd_remarks' => $posts['pd_remarks'],
                    'total_amount' => doubleval($posts['total_amount']),
                    'status' => 3,
                    'date_updated' => date('Y-m-d H:i:s'),
                );
            $this->db->where('id', $posts['id']);

            $res = $this->db->update('purchase_order', $db_data);


            $this->load->model('user/User_model', 'User');

            /**
             * Assigning shortcode for email
             *
             * user_to
             * user_from
             * request_id
             * message
             * po
             * item
             */

            $shortcodes = array(
                    'user_to' => $posts['auditor'],
                    'message' => $this->input->post('pd_remarks'),
                    'request_id' => $posts['request_id'],
                    'po' => $posts['id']
                );

            $this->xwb_purchasing->setShortCodes($shortcodes);

            $condition = $this->xwb_purchasing->getShortCodes();

            /* sending email notification */
            $msg = $this->xwb_purchasing->getMessage('reupdate_po');
            $message = do_shortcode($msg['message'], $condition);
            $site_title = $this->config->item('site_title');
            $this->xwb_purchasing->sendmail($condition['email_to'], $msg['subject'], $message, $condition['email_from'], $site_title, $msg['subject']);


            $this->xwb_purchasing->addHistory('request_list', $posts['request_id'], 'Reupdate PO', 'Reupdated the PO for audit review', $this->log_user_data->user_id);


            if ($res) {
                $data['status'] = true;
                $data['message'] = 'Purchase order updated';
            } else {
                $data['status'] = false;
                $data['message'] = 'Error updating data, please contact the programmer';
            }
        }

        echo $this->xwbJsonEncode($data);
    }
}
