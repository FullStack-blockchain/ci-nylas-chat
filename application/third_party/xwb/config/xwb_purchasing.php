<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * XWB Purchasing - Configuration
 *
 *
 * @package     XWB Purchasing
 * @author      Jay-r Simpron
 * @copyright   Copyright (c) 2017, Jay-r Simpron
 */



/**
 * Set Site title here
 */
$config['site_title'] = 'CI Purchasing';

/**
 * Footer text
 * This will be shown inthe footer area of your website
 */
$config['footer_text'] = 'CI Purchasing - Developed by <a target="_blank" href="http://www.extremewebevolution.com">eXWeb</a>';

/**
 * This will be the email address from
 * in the notification process
 *
 */
$config['email_from'] = 'admin@admin.com';

/**
 * Users Table Name
 * Do not change or mind this config.
 * This will be used on the future update,
 * so just leave this as is
 */
$config['users_table'] = 'users';

/**
 * Users table unique ID.
 * Do not change or mind this config.
 * This will be used on the future update,
 * so just leave this as is
 */
$config['users_id'] = 'id';



/**
 * Login URL
 *
 * Place the full Login URL
 */
$config['login_url'] = base_url('user/auth/login');

/**
 * Template email names
 *
 * These are the names of the Emails Templates of the system. You can see this on
 * Admin > Settings > Email Messages menu
 */
$config['email_names']    = array(
  'new_request' => 'New Request Created',
  'new_request_assigned' => 'New Request Assigned',
  'item_denied' => 'Item denied by Department Head/Recommending Head',
  'response_to_head' => 'User Responded to Department Head/Recommending Head',
  'request_filed' => 'Request Approved and Filed',
  'remove_item' => 'Item Removed',
  'to_canvass' => 'Forwarded to Canvasser',
  'to_admin_review' => 'Forwarded to Admin for Review',
  'to_canvass_edit' => 'Return to Canvasser to Edit',
  'to_admin_response_review' => 'Forwarded to Admin for Review on Edit',
  'canvasser_to_requisitioner' => 'Request returned by Canvasser to Requisitioner',
  'requisitioner_response_to_canvasser' => 'Requisitioner response to canvasser',
  'admin_to_requisitioner' => 'Request Return from Admin to Requisitioner',
  'requisitioner_response_to_admin' => 'Requisitioner Response to Admin',
  'to_budget' => 'Forwarded to Budget',
  'budget_denied' => 'Budget denied',
  'response_to_budget' => 'User responded to budget',
  'budget_return' => 'Return Budget to Admin',
  'admin_to_budget' => 'Admin Response to Budget',
  'budget_approved' => 'Budget Approved the request',
  'board_approval' => 'For Board Approval',
  'board_approved' => 'Board Approved the request',
  'board_denied' => 'Board Denied the request',
  'response_to_board' => 'Requisitioner Responded to Board',
  'for_audit' => 'Forwarded to Auditor',
  'return_audit' => 'Return the approved PO',
  'reupdate_po' => 'Re-update the PO',
  'po_audited' => 'PO has been approved and ready for purchase',
  'delivery_to_property' => 'Items delivery',
  'request_done' => 'Request Process Done',
 );


/**
 * Upload Folder
 *
 * the upload path for the new requests attachment
 */
$config['upload_folder'] = FCPATH.'uploads/';



/**
 * Unit of measurements
 *
 * These unit of measurement will show on the canvasser side
 */
$config['unit_measurement'] = array(
            'piece' => 'Piece',
            'm' => 'Meter/Metre',
            'mm' => 'Millimeter',
            'cm' => 'Centimeter',
            'dm' => 'Decimeter',
            'in' => 'Inch',
            'ft' => 'Foot',
            'yd' => 'Yard',
            'ha' => 'Hectare',
            'in2' => 'Square inches',
            'ft2' => 'Square feet',
            'yd2' => 'Square yards',
            'mi2' => 'Square miles',
            'm3' => 'Cubic meter',
            'l' => 'Liter',
            'ml' => 'Milliliter',
            'cl' => 'Centiliter',
            'dl' => 'Deciliter',
            'hl' => 'Hectoliter',
            'in3' => 'Cubic Inch',
            'ft3' => 'Cubic Foot',
            'yd3' => 'Cubic Yard',
            'acre ft' => 'Acre-Foot',
            'tsp' => 'Teaspoon',
            'tbsp' => 'Tablespoon',
            'fl oz' => 'Fluid ounce',
            'cup' => 'Cup',
            'qt' => 'Quart',
            'gal' => 'Gallon',
            'dB' => 'Decibel',
            'mph' => 'Miles per hour',
            'm/s' => 'Meters per second',
            'g' => 'Grams',
            'kg' => 'Kilogram',
            'gr' => 'Grain',
            'dr' => 'Dram',
            'oz' => 'Ounce',
            'lb' => 'Pound',
            'cwt' => 'Hundredweight',
            't' => 'Metric Ton (Tonnes)',
            'w' => 'Watt',
            'kw' => 'Kilowatt',
            'hp' => 'Horsepower',
            'bar' => 'Bar',
            'Amps' => 'Ampere',
            'V' => 'Volt',
            'Ω' => 'Ohm',
            'F' => 'Farad',
            'ream' => 'Paper Bale',
            'doz' => 'Dozen',
        );
