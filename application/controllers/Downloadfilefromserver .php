 <?php if (!defined('BASEPATH')) exit('No direct script access allowed');

    class Downloadfilefromserver extends CI_Controller
    {

        function __construct()
        {
            parent::__construct();
            $this->load->helper(array('form', 'url'));      
         
            $this->load->helper('file');
            $this->load->helper('download');

        }   

        function index()
        {

            echo "welcome to downlaod a course index";
        }

        function pushFileFromServer()
        {
                $path = $this->input->post('dl_file_path');
            $file_name_in_server_arr = preg_split("/\\/uploads\\//", $path ); //also can be get by basename($path)
            $name= ($file_name_in_server_arr[1]);
            // make sure it's a file before doing anything!
            if(is_file($path)){
                // required for IE
                if(ini_get('zlib.output_compression')){ 
                    ini_set('zlib.output_compression', 'off'); 
                }

                // get the file mime type using the file extension
                $mime = get_mime_by_extension($path);

                // Build the headers to push out the file properly.
                header('Pragma: public');     // required
                header('Expires: 0');         // no cache
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Last-Modified: '.gmdate ('D, d M Y H:i:s', filemtime ($path)).' GMT');
                header('Cache-Control: private',false);
                header('Content-Type: '.$mime);  // Add the mime type from Code igniter.
                header('Content-Disposition: attachment; filename="'.basename($path).'"');  // Add the file name
                header('Content-Transfer-Encoding: binary');
                header('Content-Length: '.filesize($path)); // provide file size
                header('Connection: close');

                readfile($path); // push it out             

                //$data = file_get_contents($path); // Read the file's contents
                //force_download($name, $data); 
                exit();
            }
        }
}

