<?php

class PDF_Signature
{
    protected $PDFinstance;

    protected $PDFtype;

    public function __construct(&$PDFinstance, $PDFtype)
    {
        $this->PDFinstance = $PDFinstance;
        $this->PDFtype     = $PDFtype;
    }

    public function process()
    {
        $dimensions       = $this->PDFinstance->getPageDimensions();
        $leftColumnExists = false;

        if (($this->PDFtype == 'invoice' && get_option('show_pdf_signature_invoice') == 1)
        || ($this->PDFtype == 'estimate' && get_option('show_pdf_signature_estimate') == 1)
        || ($this->PDFtype == 'contract' && get_option('show_pdf_signature_contract') == 1)
        || ($this->PDFtype == 'credit_note') && get_option('show_pdf_signature_credit_note') == 1) {
            $signatureImage  = get_option('signature_image');
            $signaturePath   = FCPATH . 'uploads/company/' . $signatureImage;
            $signatureExists = file_exists($signaturePath);

            $blankSignatureLine = do_action('blank_signature_line', '_________________________');

            if ($signatureImage != '' && $signatureExists) {
                $blankSignatureLine = '';
            }

            $this->PDFinstance->ln(13);

            if ($signatureImage != '' && $signatureExists) {
                $blankSignatureLine .= '<br /><br /><img src="' . $signaturePath . '">';
            }

            $this->PDFinstance->MultiCell(($dimensions['wk'] / 2) - $dimensions['lm'], 0, _l('authorized_signature_text') . ' ' . $blankSignatureLine, 0, 'J', 0, 0, '', '', true, 0, true, true, 0);

            $leftColumnExists = true;
        }

        $pdfCustomerSignatureImagePath = '';

        if (isset($GLOBALS['estimate_pdf']) && !empty($GLOBALS['estimate_pdf']->signature)) {
            $estimate                      = $GLOBALS['estimate_pdf'];
            $pdfCustomerSignatureImagePath = get_upload_path_by_type('estimate') . $estimate->id . '/' . $estimate->signature;
        } elseif (isset($GLOBALS['proposal_pdf']) && !empty($GLOBALS['proposal_pdf']->signature)) {
            $proposal                      = $GLOBALS['proposal_pdf'];
            $pdfCustomerSignatureImagePath = get_upload_path_by_type('proposal') . $proposal->id . '/' . $proposal->signature;
        } elseif (isset($GLOBALS['contract_pdf']) && !empty($GLOBALS['contract_pdf']->signature)) {
            $contract                      = $GLOBALS['contract_pdf'];
            $pdfCustomerSignatureImagePath = get_upload_path_by_type('contract') . $contract->id . '/' . $contract->signature;
        }

        if (!empty($pdfCustomerSignatureImagePath)) {
            $customerSignature = _l('document_customer_signature_text');
            $customerSignature .= '<br /><br /><img src="' . $pdfCustomerSignatureImagePath . '">';
            $width = ($dimensions['wk'] / 2) - $dimensions['rm'];
            if (!$leftColumnExists) {
                $width = $dimensions['wk'] - ($dimensions['rm'] + $dimensions['lm']);
                $this->PDFinstance->ln(13);
            }
            $this->PDFinstance->MultiCell($width, 0, $customerSignature, 0, 'R', 0, 1, '', '', true, 0, true, false, 0);
        }
    }
}
