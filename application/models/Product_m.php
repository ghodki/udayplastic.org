<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Product_m extends CI_Model
{

    // Get DataTable data
    function getProductsData($postData = null)
    {

        $response = array();

        ## Read value
        $draw = $postData['draw'];
        $start = $postData['start'];
        $rowperpage = $postData['length']; // Rows display per page
        $columnIndex = $postData['order'][0]['column']; // Column index
        $columnName = $postData['columns'][$columnIndex]['data']; // Column name
        $columnSortOrder = $postData['order'][0]['dir']; // asc or desc
        $searchValue = $postData['search']['value']; // Search value

        // Custom search filter 
        //   $searchCity = $postData['searchCity'];
        //   $searchGender = $postData['searchGender'];
        //   $searchName = $postData['searchName'];

        ## Search 
        $search_arr = array();
        $searchQuery = "";
        if ($searchValue != '') {
            $search_arr[] = " (name like '%" . $searchValue . "%' or qrCode like '%" . $searchValue . "%' or hsn_code like '%" .$searchValue.")";
        }
        //   if($searchCity != ''){
        //       $search_arr[] = " city='".$searchCity."' ";
        //   }
        //   if($searchGender != ''){
        //       $search_arr[] = " gender='".$searchGender."' ";
        //   }
        //   if($searchName != ''){
        //       $search_arr[] = " name like '%".$searchName."%' ";
        //   }
        if (count($search_arr) > 0) {
            $searchQuery = implode(" and ", $search_arr);
        }
        $wheres = ['comp_id' => $this->session->userdata('comp_id'), 'user_id' => $this->session->userdata('user_id')];
        ## Total number of records without filtering
        $this->db->select('count(*) as allcount');
        $this->db->where($wheres);
        $records = $this->db->get('product')->result();
        $totalRecords = $records[0]->allcount;

        ## Total number of record with filtering
        $this->db->select('count(*) as allcount');
        if ($searchQuery != '') {
            $this->db->where($searchQuery);
        }
        $this->db->where($wheres);
        $records = $this->db->get('product')->result();
        $totalRecordwithFilter = $records[0]->allcount;

        ## Fetch records
        $this->db->select('*');
        if ($searchQuery != '')
            $this->db->where($searchQuery);
        $this->db->where($wheres);
        $this->db->order_by($columnName, $columnSortOrder);
        $this->db->limit($rowperpage, $start);
        $records = $this->db->get('users')->result();

        $data = array();

        foreach ($records as $record) {

            $data[] = array(
                "checkbox" => '<input class="checkdata" type="checkbox" name="product_id[]" value="'.$record->id.'" ?>',
                "product_code" => $record->qrCode,
                "product_name" => $record->p_name,
                'action' => '<button class="btn btn-primary">' . $record->name . '</button>',
            );
        }

        ## Response
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordwithFilter,
            "aaData" => $data
        );

        return $response;
    }

    // Get cities array
    //   public function getCities(){

    //       ## Fetch records
    //       $this->db->distinct();
    //       $this->db->select('city');
    //       $records = $this->db->get('users')->result();

    //       $data = array();

    //       foreach($records as $record ){

    //           $data[] = $record->city;
    //       }

    //       return $data;
    //   }

}
