<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

use Dompdf\Dompdf;
use Dompdf\Options;

class Html2pdf
{
    public $html;
    public $path;
    public $filename;
    public $paper_size;
    public $orientation;
    protected $CI;

    public function __construct($params = array())
    {
        $this->CI = &get_instance();

        if (count($params) > 0) {
            $this->initialize($params);
        }

        log_message('debug', 'PDF Class Initialized');
    }

    public function initialize($params)
    {
        $this->clear();
        if (count($params) > 0) {
            foreach ($params as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->$key = $value;
                }
            }
        }
    }

    public function clear()
    {
        $this->html = null;
        $this->path = null;
        $this->filename = null;
        $this->paper_size = null;
        $this->orientation = null;
    }

    public function html($html = null)
    {
        $this->html = $html;
    }

    public function folder($path)
    {
        $this->path = $path;
    }

    public function filename($filename)
    {
        $this->filename = $filename;
    }

    public function paper($paper_size = null, $orientation = null)
    {
        $this->paper_size = $paper_size;
        $this->orientation = $orientation;
    }

    public function create($mode = 'download')
    {
        if (is_null($this->html)) {
            show_error('HTML is not set');
        }

        if (is_null($this->path)) {
            show_error('Path is not set');
        }

        if (is_null($this->paper_size)) {
            show_error('Paper size not set');
        }

        if (is_null($this->orientation)) {
            show_error('Orientation not set');
        }

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($this->html);
        $dompdf->setPaper($this->paper_size, $this->orientation);
        $dompdf->render();

        if ($mode == 'save') {
            $this->CI->load->helper('file');
            if (write_file($this->path . $this->filename, $dompdf->output())) {
                return $this->path . $this->filename;
            }
            show_error('PDF could not be written to the path');
        }

        ob_end_clean();
        $dompdf->stream($this->filename, array('Attachment' => true));
        return true;
    }
}
