<?php
    defined('BASEPATH') or exit('No direct script access allowed');

    class User_model extends CI_Model
    {
        
    	public function logoForSubadminUsers()
    	{
            $umatic_logo = $this->user_model->getResult('logo', ['user_id' => 1]);
            return $umatic_logo;
    	}
    	
    	public function getRow($table, $where = '')
    	{
            return $this->db->get_where($table, $where)->row();
    	}
    	
    	public function logoForSubadmin($subadmin_id)
    	{
    		$logo = $this->user_model->getResult('logo', ['user_id' => $subadmin_id]);
    		if (!$logo) {
    			$logo = $this->user_model->getResult('logo', ['user_id' => 1]);
    		}
    		return $logo;
    	}
    
    	public function commonLogo()
    	{
    		$role_id = $this->session->userdata('roles');
    		if ($role_id == 2) {
    			$logo = $this->user_model->getResult('logo', ['user_id' => $this->session->userdata('user_id')]);
    			if (!$logo) {
    				$logo = $this->user_model->getResult('logo', ['user_id' => 1]);
    			}
    		} elseif ($role_id == 3 || $role_id == 4) {
    			$subadmin = $this->user_model->getRow('users', ['referral' => $this->session->userdata('referral'), 'userType' => 2]);
    			if (!empty($subadmin) && isset($subadmin->id)) {
    				$logo = $this->user_model->logoForSubadmin($subadmin->id);
    			} else {
    				$logo = $this->user_model->getResult('logo', ['user_id' => 1]);
    			}
    		} else {
    			$logo = $this->user_model->getResult('logo', ['user_id' => 1]);
    		}
    		return $logo;
    	}
    	
        public function commonLogo2 () 
        {
            $role_id = $this->session->userdata('roles');
            if ($role_id == 2) {
                // Subadmin logo
                $logo = $this->user_model->getResult('logo', ['user_id' => $this->session->userdata('user_id')]);
            } elseif ($role_id == 3 || $role_id == 4) {
                // user and employee of subadmin
                $subadmin = $this->user_model->getRow('users', ['referral' => $this->session->userdata('referral'), 'userType' => 2]);
                if (!empty($subadmin) && isset($subadmin->id)) {
                    $logo = $this->user_model->logoForSubadmin($subadmin->id);
                } else {
                    $logo = $this->user_model->getResult('logo', ['user_id' => 1]);
                }
            } else {
                //  default
                $logo = "";
            }
            return $logo;
        }
    
    	public function getRespo($table, $where = '')
    	{
    		if ($where != '') {
    			$this->db->where($where);
    		}
    		return $this->db->get($table)->result();
    	}
    
    	public function getDescOrder($table, $where = '')
    	{
    
    		$this->db->select("*");
    		// $this->db->from($table);
    		$this->db->order_by("id", "desc");
    		// $query = $this->db->get();
    		// return $query->row();
    		return $this->db->get_where($table, $where)->row();
    	}
    
        public function getDescOrderLimit($table, $limit="") 
        {
            $this->db->select("*");
            $this->db->order_by("id", "DESC");
            $this->db->limit($limit);
            return $this->db->get_where($table)->row();
        }
        
    	public function saveAry($table, $data)
    	{
    		$this->db->insert($table, $data);
    		return $this->db->insert_id();
    	}
    	
    	public function getResult($table, $where = null)
    	{
            $this->db->select("*");
            $this->db->from($table);
            if (!empty($where)) {
                $this->db->where($where);
            }
            $this->db->order_by("id", "desc");
            $query = $this->db->get();
            return $query->result();
    	}
        
    	public function getResultArray($table, $where = null, $where_in = null)
    	{
            $this->db->select("*");
            $this->db->from($table);
            if (!empty($where)) {
                $this->db->where($where);
            }
            if (!empty($where_in)) {
                $this->db->where('party_id', $where_in);
            }
            $this->db->order_by("date", "ASC");
            $query = $this->db->get();
            return $query->result();
    	}
    	
    	public function getResults($table, $where = null)
    	{
            $this->db->select("*");
            $this->db->from($table);
            if (!empty($where)) {
                $this->db->where($where);
            }
            $this->db->order_by("position", "asc");

            $query = $this->db->get();
            return $query->result();
    	}
        
    	public function getSelectedResult($table, $select=null, $where = null, $extra_where = null, $or_where = null)
    	{
            if (!empty($select)) {
                $this->db->select($select);
            }
            $this->db->from($table);
            if (!empty($where)) {
                $this->db->where($where);
            }
            $this->db->group_start();
            if (!empty($extra_where)) {
                $this->db->where($extra_where);
            }
            if (!empty($or_where)) {
                $this->db->or_where($or_where);
            }
            $this->db->group_end();
            $this->db->order_by("id", "ASC");

            $query = $this->db->get();
            return $query->result();
    	}
        
    	public function getSelectedInvoice($table, $select = null, $where = null, $where_id = null, $where_type = null, $extra_where = null, $or_where = null)
    	{
            if (!empty($select)) {
                $this->db->select($select);
            }
            $this->db->from($table);
            if (!empty($where)) {
                $this->db->where($where);
            }
            if (!empty($where_id)) {
                $this->db->where_in('id', $where_id);
            }
            if (!empty($where_type)) {
                $this->db->where_in('type', $where_type);
            }
            $this->db->group_start();
            if (!empty($extra_where)) {
                $this->db->where($extra_where);
            }
            if (!empty($or_where)) {
                $this->db->or_where($or_where);
            }
            $this->db->group_end();
            $query = $this->db->get();
            return $query->result();
    	}
    
        public function bulkSaleUp($table, $data, $invoiceID, $InvoiceNo, $invoiceDate)
        {
            if (isset($data) && !empty($data)) {    
                foreach ($data as $productData) {
                    if ($this->session->userdata('RoleType')) {
                        $UserId = $this->session->userdata('UserId');
                        $CompId = $this->session->userdata('CompId');
                    } else {
                        $UserId = $this->session->userdata('user_id');
                        $CompId = $this->session->userdata('comp_id');
                    }
                    $CompanyBranchId = $this->session->userdata('CompBranchId');
                    
                    $CompProfileWhere = ['user_id' => $UserId, 'comp_id' => $CompId];
                    if ($this->session->userdata('BranchRole')) {
                        $CompProfileWhere['CompanyBranchId'] = $CompanyBranchId;
                    }
                    
                    $row['InvoiceNo'] = $InvoiceNo;
                    $row['invoiceID'] = $invoiceID;
                    $row['invoiceDate'] = $invoiceDate;
                    $row['comp_id'] = $CompId;
                    $row['user_id'] = $UserId;
                    $row['CompanyBranchId'] = $CompanyBranchId;
                    $row['product_details'] = json_encode($productData);
                    $row['type'] = 'Sale';
                    $row['product_id'] = $productData['product'];
                    $row['product'] = $productData['product'];
                    $row['p_code'] = get_title('product', ['id' => $productData['product']], 'qrCode');
                    $row['productname'] = get_title('product', ['id' => $productData['product']], 'p_name');
                    $row['discount'] = $productData['discount'];
                    $category_for_glass = get_title('comp_profile', $CompProfileWhere, 'category');
                    if ($category_for_glass != "Normal") {
                        $row['qty'] = $productData['sqft'];
                        $row['sqft'] = $productData['qty'];
                    } else {
                        $row['qty'] = $productData['qty'];
                        $row['sqft'] = $productData['sqft'];
                    }
                    $row['hsn'] = $productData['hsn'];
                    $row['tax'] = $productData['tax'];
                    $row['sgst'] = $productData['sgst'];
                    $row['cgst'] = $productData['cgst'];
                    $row['igst'] = $productData['igst'];
                    $row['financial_year'] = get_title('sales', ['id' => $invoiceID], 'financial_year');
                    $row['purchase_price'] = $productData['price'];
                    $row['price'] = $productData['price'];
                    $row['taxAmount'] = $productData['taxAmount'];
                    $row['amount'] = $productData['amount'];                    
                    $row['rate'] = $productData['rate'];
                    $row['netAmount'] = $productData['netAmount'];
                    $row['product_dec'] = $productData['product_dec'];
                    $row['warehouse'] = $productData['warehouse'];

                    $this->db->insert($table, $row);
                    $this->db->insert_id();

                    if ($this->db->trans_status() === FALSE) {
                        $this->db->trans_rollback();
                        return false;
                    }
                }
                $this->db->trans_commit();
                return true;
            }
            return false;
        }
    
        public function bulkEstimateUp($table, $data, $invoiceID, $InvoiceNo, $invoiceDate)
        {
            if (isset($data) && !empty($data)) {
                foreach ($data as $row) {
                    if ($this->session->userdata('RoleType')) {
                        $UserId = $this->session->userdata('UserId');
                        $CompId = $this->session->userdata('CompId');
                    } else {
                        $UserId = $this->session->userdata('user_id');
                        $CompId = $this->session->userdata('comp_id');
                    }
                    $CompanyBranchId = $this->session->userdata('CompBranchId');                    
                    
                    $row['InvoiceNo'] = $InvoiceNo;
                    $row['invoiceID'] = $invoiceID;
                    $row['invoiceDate'] = $invoiceDate;
                    $row['comp_id'] = $CompId;
                    $row['user_id'] = $UserId;
                    $row['CompanyBranchId'] = $CompanyBranchId;
                    $row['product_details'] = json_encode($row);
                    $row['type'] = 'Sale';
                    $row['product_id'] = $row['product'];
                    $row['product'] = $row['product'];
                    $row['p_code'] = get_title('product', ['id' => $row['product']], 'qrCode');
                    $row['productname'] = get_title('product', ['id' => $row['product']], 'p_name');
                    $row['discount'] = $row['discount'];
                    $row['qty'] = $row['qty'];
                    $row['hsn'] = $row['hsn'];
                    $row['sgst'] = $row['sgst'];
                    $row['cgst'] = $row['cgst'];
                    $row['igst'] = $row['igst'];
                    $row['financial_year'] = get_title('estimates', ['id' => $invoiceID], 'financial_year');
                    $row['purchase_price'] = $row['price'];
                    $row['price'] = $row['price'];
                    $row['taxAmount'] = $row['taxAmount'];
                    $row['amount'] = $row['amount'];
                    $row['rate'] = $row['rate'];    
                    $row['netAmount'] = $row['netAmount'];
                    $row['estimate_stock'] = $this->session->userdata('estimate_stock');
                    $row['warehouse'] = $row['warehouse'];
                    
                    $this->db->insert($table, $row);
                    $this->db->insert_id();
                    if ($this->db->trans_status() === FALSE) {
                        $this->db->trans_rollback();
                        return false;
                    }
                }
                $this->db->trans_commit();
                return true;
            }
            return false;
        }
    
    	public function bulkEstimatePurchaseUp($table, $data, $invoiceID, $InvoiceNo, $invoiceDate)
    	{
            if (isset($data) && !empty($data)) {
                if ($this->session->userdata('RoleType')) {
                    $UserId = $this->session->userdata('UserId');
                    $CompId = $this->session->userdata('CompId');
                } else {
                    $UserId = $this->session->userdata('user_id');
                    $CompId = $this->session->userdata('comp_id');
                }
                $CompanyBranchId = $this->session->userdata('CompBranchId');
                
                foreach ($data as $row) {
                    $row['InvoiceNo'] = $InvoiceNo;
                    $row['invoiceID'] = $invoiceID;
                    $row['invoiceDate'] = $invoiceDate;
                    $row['comp_id'] = $CompId;
                    $row['user_id'] = $UserId;
                    $row['CompanyBranchId'] = $CompanyBranchId;
                    $row['product_details'] = json_encode($row);
                    $row['type'] = 'Purchase';
                    $row['product_id'] = $row['product'];
                    $row['product'] = $row['product'];
                    $row['p_code'] = get_title('product', ['id' => $row['product']], 'qrCode');
                    $row['productname'] = get_title('product', ['id' => $row['product']], 'p_name');
                    $row['discount'] = $row['discount'];
                    $row['qty'] = $row['qty'];
                    $row['hsn'] = $row['hsn'];
                    $row['sgst'] = $row['sgst'];
                    $row['cgst'] = $row['cgst'];
                    $row['igst'] = $row['igst'];
                    $row['financial_year'] = get_title('estimate_purchase', ['id' => $invoiceID], 'financial_year');
                    $row['purchase_price'] = $row['price'];
                    $row['price'] = $row['price'];
                    $row['taxAmount'] = $row['taxAmount'];
                    $row['amount'] = $row['amount'];
                    $row['rate'] = $row['rate'];    
                    $row['netAmount'] = $row['netAmount'];
                    $row['estimate_stock'] = $this->session->userdata('estimate_stock');
                    $row['warehouse'] = $row['warehouse'];
                    
                    $this->db->insert($table, $row);
                    if ($this->db->trans_status() === FALSE) {
                        $this->db->trans_rollback();
                        return false;
                    }
                }
                $this->db->trans_commit();
                return true;
            }
            return false;
        }

        public function bulkPurchaseUp($table, $data, $invoiceID, $InvoiceNo, $invoiceDate)
        {
            if (isset($data) && !empty($data)) {
                foreach ($data as $row) {
                    $row['InvoiceNo'] = $InvoiceNo;
                    $row['invoiceID'] = $invoiceID;
                    $row['type'] = 'Purchase';
                    $row['product_id'] = $row['product'];
                    $row['comp_id'] = $this->session->userdata('comp_id');
                    $row['user_id'] = $this->session->userdata('user_id');
                    $row['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                    $row['purchase_price'] = $row['price'];
                    $row['qty'] = $row['qty'];
                    $row['hsn'] = $row['hsn'];
                    $row['invoiceDate'] = $invoiceDate;
                    $this->db->insert('invoice_product', $row);
                    if ($this->db->trans_status() === FALSE) {
                        $this->db->trans_rollback();
                        return false;
                    }
                }
                $this->db->trans_commit();
                return true;
            }
            return false;
        }
    
        public function bulkCreditNote($table, $data, $invoiceID, $InvoiceNo, $invoiceDate)
        {
            if (isset($data) && !empty($data)) {
                $CompProfileWhere = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                if ($this->session->userdata('BranchRole')) {
                    $CompProfileWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
            
                foreach ($data as $row) {
                    $row['InvoiceNo'] = $InvoiceNo;
                    $row['invoiceID'] = $invoiceID;
                    $row['invoiceDate'] = $invoiceDate;
                    $row['comp_id'] = $this->session->userdata('comp_id');
                    $row['user_id'] = $this->session->userdata('user_id');
                    $row['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                    $row['product_details'] = json_encode($row);
                    $row['type'] = 'CreditNote';
                    $row['product_id'] = $row['product'];
                    $row['product'] = $row['product'];
                    $row['p_code'] = get_title('product', ['id' => $row['product']], 'qrCode');
                    $row['productname'] = get_title('product', ['id' => $row['product']], 'p_name');
                    $row['discount'] = $row['discount'];
                    $row['qty'] = $row['qty'];
                    $row['hsn'] = $row['hsn'];
                    $row['sgst'] = $row['sgst'];
                    $row['cgst'] = $row['cgst'];
                    $row['igst'] = $row['igst'];
                    $row['financial_year'] = get_title('credit_notes', ['id' => $invoiceID], 'financial_year');
                    $row['purchase_price'] = $row['price'];
                    $row['price'] = $row['price'];
                    $row['taxAmount'] = $row['taxAmount'];
                    $row['amount'] = $row['amount'];
                    $row['rate'] = $row['rate'];
                    $row['netAmount'] = $row['netAmount'];
                    $row['WarehouseId'] = get_title('comp_profile', $CompProfileWhere, 'warehouse');
                    $this->db->insert($table, $row);
                    if ($this->db->trans_status() === FALSE) {
                        $this->db->trans_rollback();
                        return false;
                    }
                }
                $this->db->trans_commit();
                return true;
            }
            return false;
        }
    
        public function bulkdebiteNote($table, $data, $invoiceID, $InvoiceNo, $invoiceDate)
        {
            if (isset($data) && !empty($data)) {
                $UserId = $this->session->userdata('user_id');
                $CompId = $this->session->userdata('comp_id');
                $CompanyBranchId = $this->session->userdata('CompBranchId');

                $CompProfileWhere = ['user_id' => $UserId, 'comp_id' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $CompProfileWhere['CompanyBranchId'] = $CompanyBranchId;
                }
                
                foreach ($data as $row) {
                    $row['InvoiceNo'] = $InvoiceNo;
                    $row['invoiceID'] = $invoiceID;
                    $row['invoiceDate'] = $invoiceDate;
                    $row['comp_id'] = $CompId;
                    $row['user_id'] = $UserId;
                    $row['CompanyBranchId'] = $CompanyBranchId;
                    $row['product_details'] = json_encode($row);
                    $row['type'] = 'DebitNote';
                    $row['product_id'] = $row['product'];
                    $row['product'] = $row['product'];
                    $row['p_code'] = get_title('product', ['id' => $row['product']], 'qrCode');
                    $row['productname'] = get_title('product', ['id' => $row['product']], 'p_name');
                    $row['discount'] = $row['discount'];
                    $row['qty'] = $row['qty'];
                    $row['hsn'] = $row['hsn'];
                    $row['sgst'] = $row['sgst'];
                    $row['cgst'] = $row['cgst'];
                    $row['igst'] = $row['igst'];
                    $row['financial_year'] = get_title('debit_notes', ['id' => $invoiceID], 'financial_year');
                    $row['purchase_price'] = $row['price'];
                    $row['price'] = $row['price'];
                    $row['taxAmount'] = $row['taxAmount'];
                    $row['amount'] = $row['amount'];
                    $row['rate'] = $row['rate'];
                    $row['netAmount'] = $row['netAmount'];
                    $row['WarehouseId'] = get_title('comp_profile', $CompProfileWhere, 'warehouse_purchase');
                    $this->db->insert($table, $row);
                    if ($this->db->trans_status() === FALSE) {
                        $this->db->trans_rollback();
                        return false;
                    }
                }
                $this->db->trans_commit();
                return true;
            }
            return false;
        }
    
        public function bulkUpdate($table, $data, $invoiceID, $InvoiceNo, $invoiceDate)
        {
            if (isset($data) && !empty($data)) {
                foreach ($data as $row) {
                    $row['InvoiceNo'] = $InvoiceNo;
                    $row['invoiceID'] = $invoiceID;
                    $row['type'] = 'Purchase';
                    $row['product_id'] = $row['product'];
                    $row['comp_id'] = $this->session->userdata('comp_id');
                    $row['user_id'] = $this->session->userdata('user_id');
                    $row['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                    $row['financial_year'] = get_title('inventory_billing', [], 'financial_year');
                    $row['purchase_price'] = $row['price'];
                    $row['qty'] = $row['qty'];
                    $row['hsn'] = $row['hsn'];
                    $row['invoiceDate'] = $invoiceDate;
                    $this->db->insert('invoice_product', $row);
                    if ($this->db->trans_status() === FALSE) {
                        $this->db->trans_rollback();
                        return false;
                    }
                }
                $this->db->trans_commit();
                return true;
            }
            return false;
        }
    
        public function bulkPurchaseUpdate($table, $data, $invoiceID, $InvoiceNo, $invoiceDate) 
        {
            if (isset($data) && !empty($data)) {
                if ($this->session->userdata('RoleType')) {
                    $UserId = $this->session->userdata('UserId');
                    $CompId = $this->session->userdata('CompId');
                } else {
                    $UserId = $this->session->userdata('user_id');
                    $CompId = $this->session->userdata('comp_id');
                }
                $CompanyBranchId = $this->session->userdata('CompBranchId');
                
                foreach ($data as $products) {
                    $row['comp_id'] = $CompId;
                    $row['user_id'] = $UserId;
                    $row['CompanyBranchId'] = $CompanyBranchId;
                    $row['invoiceID'] = $invoiceID;
                    $row['invoiceDate'] = $invoiceDate;
                    $row['InvoiceNo'] = $InvoiceNo;
                    $row['type'] = 'Purchase';
                    $row['product_id'] = $products['product'];
                    $row['tax'] = $products['tax'];
                    $row['sgst'] = $products['sgst'];
                    $row['cgst'] = $products['cgst'];
                    $row['igst'] = $products['igst'];
                    $row['hsn'] = $products['hsn'];
                    $row['qty'] = $products['qty'];
                    $row['rate'] = $products['rate'];
                    $row['discount'] = $products['discount'];
                    $row['purchase_price'] = $products['price'];
                    $row['amount'] = $products['amount'];
                    $row['taxAmount'] = $products['taxAmount'];
                    $row['netAmount'] = $products['netAmount'];
                    $row['product'] = $products['product'];
                    $row['price'] = $products['price'];
                    $row['p_code'] = get_title('product', ['id' => $products['product']], 'qrCode');
                    $row['productname'] = get_title('product', ['id' => $products['product']], 'p_name');
                    $row['product_details'] = json_encode($products);
                    $row['financial_year'] = get_title('purchases', ['id' => $invoiceID], 'financial_year');
                    $row['warehouse'] = $products['warehouse'];
                    
                    $this->db->insert($table, $row);
                    if ($this->db->trans_status() === FALSE) {
                        $this->db->trans_rollback();
                        return false;
                    }
                }
                $this->db->trans_commit();
                return true;
            }
            return false;
        }
    
        public function deleteall($table, $where)
        {
            $this->db->where($where);
            $this->db->delete($table);
            return $this->db->affected_rows();
        }
       
    	public function checkSubMenu($parent_id, $currentmenu)
    	{
    		$flag = 0;
    		$start = 0;
    		$limit = 100;
    		$order = ['id' => 'DESC'];
    		$where = ['parents_id' => $parent_id];
    		$result = $this->getAry('permissions', $order, $start, $limit, $where);
    		foreach ($result as $menu) {
    			if ($menu->url == $currentmenu) {
    				$flag = 1;
    				break;
    			}
    		}
    		return $flag;
    	}
    
    	public function get_modules_with_permission($id = null)
    	{
    		$query = "Select p1.id,p1.url,p1.name(case when p2.role_id = $id then 'yes' else 'no' end) as active From permissions p1 left join role_has_permissions p2 ON p1.id = p2.permission_id and p2.role_id =$id";
    
    		return $this->db->query($query)->result();
    	}
    
    	public function deleteRow($table, $where)
    	{
    		$this->db->where($where);
    		$this->db->delete($table);
    		return $this->db->affected_rows();
    	}
        
    	public function updateAry($table, $data, $where)
    	{
            $this->db->where($where);
            return $this->db->update($table, $data);
    	}
    
    	public function getApproxTax($table, $search = '', $name = '')
    	{
            if (!empty($search)) {
                $this->db->like($name, $search);
            }
            $row = $this->db->get($table)->row();
            if ($row) {
                return $row->id;
            } else {
                return $search;
            }
    	}
        
    	public function getApproxTaxQty($table, $search = '', $name = '')
    	{
            if (!empty($search)) {
                $this->db->like($name, $search);
            }
            $row = $this->db->get($table)->row();
            if ($row) {
                return $row->tax_per;
            } else {
                return $search;
            }
    	}
    
    	public function count_all($table, $invoiceNumber, $invoiceDate, $fromDate, $toDate, $partyName, $productName, $where = '', $OrWhere = "")
    	{
            if (isset($invoiceNumber) && !empty($invoiceNumber)) {
                $this->db->like($invoiceNumber);
            }
            if (isset($invoiceDate) && !empty($invoiceDate)) {
                $this->db->like($invoiceDate);
            }
            if (isset($fromDate) && !empty($fromDate) && isset($toDate) && !empty($toDate)) {
                $this->db->where($fromDate);
                $this->db->where($toDate);
            }
            if (isset($partyName) && !empty($partyName)) {
                $this->db->where($partyName);
            }
            if (isset($productName) && !empty($productName)) {
                $this->db->like($productName);
            }
            $this->db->where($where);
            if (isset($OrWhere) && !empty($OrWhere)) {
                $this->db->or_where($OrWhere);
            }
            return $this->db->get($table)->num_rows();
    	}
    
    	public function getTblerDate($table, $start, $limit, $order, $invoiceNumber, $invoiceDate, $fromDate = '', $toDate = '', $partyName = '', $productName = '', $where = '', $join = '', $party = '', $Salesman = '')
    	{
            $this->db->select('*');
            if (isset($join) && !empty($join)) {
                $this->db->join($join['table'], $join['condition']);
            }
            if (isset($invoiceNumber) && !empty($invoiceNumber)) {
                $this->db->where($invoiceNumber);
            }
            if (isset($fromDate) && !empty($fromDate) && isset($toDate) && !empty($toDate)) {
                $this->db->where($fromDate);
                $this->db->where($toDate);
            }
            if (isset($partyName) && !empty($partyName)) {
                $this->db->where($partyName);
            }
            if (isset($productName) && !empty($productName)) {
                $this->db->like($productName);
            }
            if (is_array($order) && isset($order['col'], $order['order'])) {
                $this->db->order_by($order['col'], $order['order']);
            } elseif (is_array($order) && !empty($order) && !isset($order['col'])) {
                foreach ($order as $col => $dir) {
                    if ($col !== '' && $dir !== '') {
                        $this->db->order_by($col, $dir);
                    }
                }
            }
            if (isset($limit) && !empty($limit) || isset($start) && !empty($start)) {
                $this->db->limit($limit, $start);
            }
            $this->db->where($where);
            if (isset($party) && !empty($party)) {
                $this->db->where_in('customerId', $party);
            }
            if (isset($Salesman) && !empty($Salesman)) {
                $this->db->where_in('SalesmanId', $Salesman);
            }
            return $this->db->get($table)->result();
    	}
        
    	public function count_all2($table, $invoiceNumber, $invoiceDate, $fromDate, $toDate, $partyName, $productName, $where = '', $salesman = '')
    	{
            if (isset($invoiceNumber) && !empty($invoiceNumber)) {
                $this->db->like($invoiceNumber);
            }
            if (isset($invoiceDate) && !empty($invoiceDate)) {
                $this->db->like($invoiceDate);
            }
            if (isset($fromDate) && !empty($fromDate) && isset($toDate) && !empty($toDate)) {
                $this->db->where($fromDate);
                $this->db->where($toDate);
            }
            if (isset($partyName) && !empty($partyName)) {
                $this->db->where($partyName);
            }
            if (isset($productName) && !empty($productName)) {
                $this->db->like($productName);
            }
            $this->db->where($where);
            $this->db->where_in('salesman', $salesman);
            return $this->db->get($table)->num_rows();
    	}
    
    	public function getTblerDate2($table, $start, $limit, $order, $invoiceNumber, $invoiceDate, $fromDate = '', $toDate = '', $partyName = '', $productName = '', $where = '', $join = '', $salesman = '')
    	{
            $this->db->select('*');
            if (isset($join) && !empty($join)) {
                $this->db->join($join['table'], $join['condition']);
            }
            if (isset($invoiceNumber) && !empty($invoiceNumber)) {
                $this->db->where($invoiceNumber);
            }
            if (isset($fromDate) && !empty($fromDate) && isset($toDate) && !empty($toDate)) {
                $this->db->where($fromDate);
                $this->db->where($toDate);
            }
            if (isset($partyName) && !empty($partyName)) {
                $this->db->where($partyName);
            }
            if (isset($productName) && !empty($productName)) {
                $this->db->like($productName);
            }
            if (is_array($order) && isset($order['col'], $order['order'])) {
                $this->db->order_by($order['col'], $order['order']);
            } elseif (is_array($order) && !empty($order) && !isset($order['col'])) {
                foreach ($order as $col => $dir) {
                    if ($col !== '' && $dir !== '') {
                        $this->db->order_by($col, $dir);
                    }
                }
            }
            if (isset($limit) && !empty($limit) || isset($start) && !empty($start)) {
                $this->db->limit($limit, $start);
            }
            $this->db->where($where);
            if (isset($salesman) && !empty($salesman)) {
                $this->db->where_in('SalesmanId', $salesman);
            }
            return $this->db->get($table)->result();
    	}
    
    	public function getDateWiseData($table, $fromDate = '', $toDate = '', $where = '', $limit = '', $start = 0)
    	{
    		$this->db->select('*');
    
    
    
    		if (isset($fromDate) && !empty($fromDate) && isset($toDate) && !empty($toDate)) {
    			$this->db->where($fromDate);
    			$this->db->where($toDate);
    		}
    		// echo '<pre>';
    		// print_r($fromDate);
    		// print_r($toDate);
    
    		// die;
    		// $this->db->order_by('id', "DESC");
    		if ($start != '' || $limit != '') {
    			$this->db->limit($limit, $start);
    		}
    		if (isset($where) && !empty($where)) {
    			$this->db->where($where);
    		}
    		$this->db->order_by('id', 'desc');
    
    		return $this->db->get($table)->result();
    	}
    	public function getProductOrderBy($table, $order = [], $start = '', $limit = '', $where = '', $select = '')
    	{
    
    		if ($select != '') {
    			$this->db->select($select);
    		}
    		if ($where != '') {
    			$this->db->where($where);
    		}
    
    		// Supports: ['col' => 'id', 'order' => 'ASC'] or shorthand ['id' => 'DESC']
    		if (is_array($order) && isset($order['col'], $order['order'])) {
    			$this->db->order_by($order['col'], $order['order']);
    		} elseif (is_array($order) && !empty($order) && !isset($order['col'])) {
    			foreach ($order as $col => $dir) {
    				if ($col !== '' && $dir !== '') {
    					$this->db->order_by($col, $dir);
    				}
    			}
    		}
    
    		if ($start != '' || $limit != '') {
    			$this->db->limit($limit, $start);
    		}
    		$this->db->order_by("id", "DESC");
    
    		return $this->db->get($table)->result();
    
    	}
    
    	public function getProductOrderByWithFilter($product_name = '', $product_code = '', $hsn_code = '', $tax = '', $unit = '', $table = '', $order = [], $start = '', $limit = '', $where = '', $select = '', $OrWhere = '')
    	{
            if ($select != '') {
                $this->db->select($select);
            }
            if ($where != '') {
                $this->db->where($where);
            }
            if ($product_name != '') {
                $this->db->like($product_name);
            }
            if ($product_code != '') {
                $this->db->where($product_code);
            }
            if ($hsn_code != '') {
                $this->db->where($hsn_code);
            }
            if ($tax != '') {
                $this->db->where($tax);
            }
            if ($unit != '') {
                $this->db->where($unit);
            }
            if ($OrWhere != '') {
                $this->db->or_where($OrWhere);
            }
            if ($limit != '') {
                $this->db->limit($limit, $start);
            }
            $this->db->order_by('created_at', 'DESC');
            return $this->db->get($table)->result();
    	}
    
    	public function getAry($table, $order = [], $start = '', $limit = '', $where = '', $select = '')
    	{
    		if ($select != '') {
    			$this->db->select($select);
    		}
    		if ($where != '') {
    			$this->db->where($where);
    		}

    		// Supports: ['col' => 'id', 'order' => 'DESC'], shorthand ['id' => 'DESC'],
    		// or legacy incomplete ['order' => 'DESC'] (defaults column to id)
    		if (is_array($order) && !empty($order)) {
    			if (isset($order['col']) && isset($order['order'])) {
    				$this->db->order_by($order['col'], $order['order']);
    			} elseif (array_keys($order) === ['order'] || (isset($order['order']) && !isset($order['col']) && count($order) === 1)) {
    				$this->db->order_by('id', $order['order']);
    			} else {
    				foreach ($order as $col => $dir) {
    					if ($col !== '' && $col !== 'order' && $dir !== '') {
    						$this->db->order_by($col, $dir);
    					}
    				}
    			}
    		}
    
    		if ($limit !== '' && $limit !== null) {
    			$this->db->limit($limit, ($start !== '' && $start !== null) ? (int) $start : 0);
    		}
    
    		return $this->db->get($table)->result();
    	}
    
    	public function getOrderbyPosition($table, $start = '', $limit = '', $where = '', $select = '')
    	{
    
    		if ($select != '') {
    			$this->db->select($select);
    		}
    		if ($where != '') {
    			$this->db->where($where);
    		}
    		if ($start != '' || $limit != '') {
    			$this->db->limit($limit, $start);
    		}
    		$this->db->order_by("position", "asc");
    		return $this->db->get($table)->result();
    	}
    
    
    	public function getArys($table, $order = [], $start = '', $limit = '', $where = '', $select = '')
    	{
    
    		if ($select != '') {
    			$this->db->select($select);
    		}
    		if ($where != '') {
    			$this->db->where($where);
    		}
    
    		//['col'=>'id','order'=>'ASC']
    		// if($order != ''){ 
    		//     $this->db->order_by($order['col'],$order['order']);
    		// }
    
    
    		if ($start != '' || $limit != '') {
    			$this->db->limit($limit, $start);
    		}
    		$this->db->order_by("current", "ASC");
    
    		return $this->db->get($table)->result();
    		// echo $this->db->last_query();die;
    	}
    
    	public function check_permission($codename)
    	{
    
    		if ($this->session->userdata('user_id') == '') {
    			return false;
    		}
    		$user_id   = $this->session->userdata('user_id');
    		$user_id      = $this->db->get_where('users', array(
    			'id' => $user_id
    		))->row();
    		$permission_id = $this->db->get_where('permissions', array(
    			'url' => $codename
    		))->row()->id;
    
    		$role = $this->session->userdata('roles');
    		$role_permissions = $this->user_model->getRow('role_has_permissions', ['role_id' => $role, 'permissions_id' => $permission_id]);
    		if ($role_permissions) {
    			return true;
    		} else {
    			return false;
    		}/**/
    	}
    
    	public function getAryss($table, $order = [], $start = '', $limit = '', $where = '', $select = '')
    	{
    
    		if ($select != '') {
    			$this->db->select($select);
    		}
    		if ($where != '') {
    			$this->db->where($where);
    		}
    
    		//['col'=>'id','order'=>'ASC']
    		// if($order != ''){ 
    		//     $this->db->order_by($order['col'],$order['order']);
    		// }
    
    
    		if ($start != '' && $limit != '') {
    			$this->db->limit($limit, $start);
    		}
    		$this->db->order_by("id", "DESC");
    
    		return $this->db->get($table)->result();
    		// echo $this->db->last_query();die;
    
    	}
    
    
    	public function gett($table, $order = [], $search = '', $start = '', $limit = '', $where = '', $select = '')
    	{
    
    		if ($select != '') {
    			$this->db->select($select);
    		}
    		if ($where != '') {
    			$this->db->where($where);
    		}
    		if ($search != '') {
    			$this->db->like('name', $search);
    		}
    		if ($start != '' || $limit != '') {
    			$this->db->limit($limit, $start);
    		}
    		return $this->db->get($table)->result();
    	}
    	public function getForYou($table, $order = [], $search = '', $start = '', $limit = '', $where = '', $select = '')
    	{
    
    		if ($select != '') {
    			$this->db->select($select);
    		}
    		if ($where != '') {
    			$this->db->where($where);
    		}
    
    		if ($search != '') {
    			$this->db->like('name', $search);
    			$this->db->or_like('referral', $search);
    			$this->db->or_like('number', $search);
    		}
    		//['col'=>'id','order'=>'ASC']
    		// if($order != ''){ 
    		//     $this->db->order_by($order['col'],$order['order']);
    		// }
    
    
    		if ($start != '' || $limit != '') {
    			$this->db->limit($limit, $start);
    		}
    		// $this->db->order_by("id", "DESC");
    
    		return $this->db->get($table)->result();
    		// echo $this->db->last_query();die;
    
    
    	}
    
    	public function getFilterData($table, $where = '', $search = '', $order = [],  $start = '', $limit = '', $select = '')
    	{
    
    		if ($select != '') {
    			$this->db->select($select);
    		}
    		if ($where != '') {
    			$this->db->where($where);
    		}
    
    		if ($search != '') {
    			$this->db->like('name', $search);
    			// $this->db->or_like('name', $search);
    			// $this->db->or_like('name', $search);
    
    		}
    		//['col'=>'id','order'=>'ASC']
    		// if($order != ''){ 
    		//     $this->db->order_by($order['col'],$order['order']);
    		// }
    
    
    		if ($start != '' || $limit != '') {
    			$this->db->limit($limit, $start);
    		}
    		// $this->db->order_by("id", "DESC");
    
    		return $this->db->get($table)->result();
    		// echo $this->db->last_query();die;
    
    	}
        
    	public function RowCount($table, $where = [])
    	{
            $this->db->where($where);
            return $this->db->get($table)->num_rows();
    	}
        
    	function check_unique($table, $where_not, $where)
    	{
    		$this->db->where($where_not);
    		$this->db->where($where);
    		$result = $this->db->get($table)->num_rows();
    		if ($result) {
    			return false;
    		} else {
    			return true;
    		}
    	}
    	public function get_title($table, $where = '')
    	{
    		$result = $this->db->get_where($table, $where)->row();
    		if (!empty($result)) {
    			$name_val = $result->p_name;
    		} else {
    			$name_val = '.';
    		}
    		return $name_val;
    	}
    
    	public function search_job($table, $name, $number, $ref, $where)
    	{
    		$this->db->select('*');
    		$this->db->from($table);
    		$this->db->where($where);
    
    		// if (!empty($keyword)) {
    		$this->db->group_start();
    		$this->db->like('name', $name);
    		$this->db->or_like('number', $number);
    
    		$this->db->or_like('referral', $ref);
    		$this->db->group_end();
    		// }
    		// $this->db->like('location', $ref);
    		$query = $this->db->get();
    
    		return $query->result_array();
    	}
    	public function getSeriss($series)
    	{
    
    		// echo '<pre>';
    		// print_r($series);
    
    
    		$series_ary = [];
    		if (!empty($series->symbl)) {
    			$symbl = $series->symbl;
    		} else {
    			$symbl = '';
    		}
    		if (!empty($series->financial_year)) {
    			$series_ary[$series->financial_year_position] = get_title('year', ['id' => $series->financial_year], 'year');
    		} else {
    			$series_ary[$series->financial_year_position] = '';
    		}
    		if (!empty($series->prefix)) {
    			$series_ary[$series->prefix_position] = $series->prefix;
    			// $symbl='';
    
    		} else {
    			$series_ary[$series->prefix_position] = '';
    			// $symbl='';
    		}
    		if ($series->start_point) {
    			$series_ary[$series->start_point_position] = $series->start_point;
    
    			// $symbl='';
    
    		} else {
    			$series_ary[$series->start_point_position] = '';
    		}
    		// print_r($series_ary);die;
    		$blank_varaible = '';
    
    		if ($series_ary[1]) {
    
    			$blank_varaible = $series_ary[1];
    			if ($series_ary[2]) {
    				$blank_varaible = $series_ary[1] . $symbl . $series_ary[2];
    				if ($series_ary[3]) {
    					$blank_varaible = $series_ary[1] . $symbl . $series_ary[2] . $symbl . $series_ary[3];
    				}
    			}
    		} else if ($series_ary[2]) {
    			$blank_varaible = $series_ary[2];
    			if ($series_ary[3] != '' || $series_ary[3] == 0) {
    				$blank_varaible = $series_ary[2] . $symbl . $series_ary[3];
    			}
    
    			// return $series_ary[1] . $symbl . $series_ary[2] . $symbl . $series_ary[3];
    		} else {
    			$blank_varaible = $series_ary[3];
    		}
    		return $blank_varaible;
    	}
        
        public function getSeris($series)
        {
            $series_ary = [];
            if (!empty($series->symbl)) {
                $symbl = $series->symbl;
            } else {
                $symbl = '';
            }
    
            if (!empty($series->financial_year)) {
                $series_ary[$series->financial_year_position] = get_title('year', ['id' => $series->financial_year], 'year');
            } else {
                $series_ary[$series->financial_year_position] = '';
            }
    
            if (!empty($series->prefix)) {
                $series_ary[$series->prefix_position] = $series->prefix;
            } else {
                $series_ary[$series->prefix_position] = '';
            }
            
            if (!empty($series->start_point)) {
                if ($series->starter_point == 19) {
                    $series_ary[$series->start_point_position] = 10;
                } elseif ($series->starter_point == 199) {
                    $series_ary[$series->start_point_position] = 100;
                } elseif ($series->starter_point == 1999) {
                    $series_ary[$series->start_point_position] = 1000;
                } elseif ($series->starter_point == 19999) {
                    $series_ary[$series->start_point_position] = 10000;
                } elseif ($series->starter_point == 199999) {
                    $series_ary[$series->start_point_position] = 100000;
                } else {                
                    $series_ary[$series->start_point_position] = substr($series->starter_point + 1, 1);
                }
            } else {
                $series_ary[$series->start_point_position] = '';
            }
    
            $blank_varaible = '';
            if ($series_ary[1]) {
                $blank_varaible = $series_ary[1];
                if ($series_ary[2]) {
                    $blank_varaible = $series_ary[1] . $symbl . $series_ary[2];
                    if ($series_ary[3]) {
                        $blank_varaible = $series_ary[1] . $symbl . $series_ary[2] . $symbl . $series_ary[3];
                    }
                }
            } elseif ($series_ary[2]) {
                $blank_varaible = $series_ary[2];
                if ($series_ary[3]) {
                    $blank_varaible = $series_ary[2] . $symbl . $series_ary[3];
                }
            } else {
                $blank_varaible = $series_ary[3];
            }
            return $blank_varaible;
        }
    
    	public function getWalletData($table, $paymenttype = '', $fromDate = '', $toDate = '', $where = '', $bank_account = '', $limit = '', $start = 0)
    	{
    		$this->db->select('*');
    		// if (isset($join) && !empty($join)) {
    		// 	$this->db->join($join['table'], $join['condition']);
    		// }
    
    		// if (isset($paymenttype) && !empty($paymenttype)) {
    		// 	$this->db->like($paymenttype);
    		// }
    		if (isset($bank_account) && !empty($bank_account)) {
    			$this->db->like($bank_account);
    		}
    			$this->db->where($where);
    		if (isset($fromDate) && !empty($fromDate) && isset($toDate) && !empty($toDate)) {
    			$this->db->where($fromDate);
    			$this->db->where($toDate);
    		}
    
    		if (isset($limit) && !empty($limit) || isset($start) && !empty($start)) {
    			$this->db->limit($limit, $start);
    		}
    		$this->db->order_by('created_at','desc');
    
    	
    		// echo '<pre>';
    		// print_r($this->db->get($table));
    		// die;
    		return $this->db->get($table)->result();
    	}
    
    
    	public function getAll($table, $where = '', $select = '')
    	{
    
    		if ($select != '') {
    			$this->db->select($select);
    		}
    		if ($where != '') {
    			$this->db->where($where);
    		}
    		$this->db->order_by("id", "DESC");
    		return $this->db->get($table)->result();
    	}
    
    
    	function getUsers($postData = null, $where = null)
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
    		// $searchCity = $postData['searchCity'];
    		// $searchGender = $postData['searchGender'];
    		// $searchName = $postData['searchName'];
    
    		## Search 
    		$search_arr = array();
    		$searchQuery = "";
    		if ($searchValue != '') {
    			$search_arr[] = " (name like '%" . $searchValue . "%' or 
                    email like '%" . $searchValue . "%' or 
                    city like'%" . $searchValue . "%' ) ";
    		}
    		// if ($searchCity != '') {
    		//     $search_arr[] = " city='" . $searchCity . "' ";
    		// }
    		// if ($searchGender != '') {
    		//     $search_arr[] = " gender='" . $searchGender . "' ";
    		// }
    		// if ($searchName != '') {
    		//     $search_arr[] = " name like '%" . $searchName . "%' ";
    		// }
    		// if (count($search_arr) > 0) {
    		//     $searchQuery = implode(" and ", $search_arr);
    		// }
    
    		## Total number of records without filtering
    		$this->db->select('count(*) as allcount');
    		$this->db->where($where);
    		$records = $this->db->get('wallet')->result();
    		$totalRecords = $records[0]->allcount;
    
    		## Total number of record with filtering
    		$this->db->select('count(*) as allcount');
    		if ($searchQuery != '') {
    			$this->db->where($searchQuery);
    		}
    		$this->db->where($where);
    		$records = $this->db->get('users')->result();
    		$totalRecordwithFilter = $records[0]->allcount;
    
    		## Fetch records
    		$this->db->select('*');
    		if ($searchQuery != '') {
    			$this->db->where($searchQuery);
    		}
    		$this->db->where($where);
    
    		$this->db->order_by($columnName, $columnSortOrder);
    		$this->db->limit($rowperpage, $start);
    		$records = $this->db->get('users')->result();
    
    		$data = array();
    
    		foreach ($records as $record) {
    
    			$data[] = array(
    				"username" => $record->username,
    				"name" => $record->name,
    				"email" => $record->email,
    				"gender" => $record->gender,
    				"city" => $record->city
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
    
    	public function bulkEstimate($table, $data, $invoiceID, $InvoiceNo, $invoiceDate)
    	{
    		if (isset($data) && !empty($data)) {
    			foreach ($data as $row) {
    				$where = ['id' => $row['product']];
    				$row['InvoiceNo'] = $InvoiceNo;
    				$row['invoiceID'] = $invoiceID;
    				$row['invoiceDate'] = $invoiceDate;
    				$row['comp_id'] = $this->session->userdata('comp_id');
    				$row['user_id'] = $this->session->userdata('user_id');
    				$row['product_details'] = json_encode($row);
    				$row['type'] = 'Sale';
    				// product id
    				$row['product_id'] = $row['product'];
    				$row['product'] = $row['product'];
    				$row['p_code'] = get_title('product', ['id' => $row['product']], 'qrCode');
    				$row['productname'] = get_title('product', ['id' => $row['product']], 'p_name');
    				// 
    				$row['discount'] = $row['discount'];
    
    				$row['qty'] = $row['qty'];
    				$row['hsn'] = $row['hsn'];
    				// $row['tax'] = $row['tax'];
    				$row['sgst'] = $row['sgst'];
    				$row['cgst'] = $row['cgst'];
    				$row['igst'] = $row['igst'];
    				$row['financial_year'] = get_title('inventory_billing', [], 'financial_year');
    
    				// taxable amount
    				// taxable_amount,deducton amount after calculating tax of the product
    
    				$row['purchase_price'] = $row['price'];
    				$row['price'] = $row['price'];
    				// tax amount
    				$row['taxAmount'] = $row['taxAmount'];
    				$row['amount'] = $row['amount'];
    				// 
    				// actual price of the product
    				$row['rate'] = $row['rate'];
    				// 
    
    				$row['netAmount'] = $row['netAmount'];
    
    
    				$this->db->insert('estimate_details', $row);
    				$item_id = $this->db->insert_id();
    
    				if ($this->db->trans_status() === FALSE) {
    					$this->db->trans_rollback();
    					return false;
    				}
    			}
    			$this->db->trans_commit();
    			return true;
    		}
    		return false;
    	}
    
    	function getPartyData($query, $where)
    	{
            $this->db->select("*");
            $this->db->from("parties");
    
            if ($query != '') {
                $this->db->like('name', $query);
            }
            $this->db->where($where);
            $this->db->order_by('name', 'DESC');
            return $this->db->get()->result();
    	}
    
        function getProductData($query)
        {
            if ($this->session->userdata('RoleType')) {
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
            } else {
                $UserId = $this->session->userdata('user_id');
                $CompId = $this->session->userdata('comp_id');
            }
            $CompanyBranchId = $this->session->userdata('CompBranchId');

            $Where = ['user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $CompanyBranchId;
            }

            // Only select needed columns instead of *
            $this->db->select("id, p_name, qrCode, price, mrp");

            if ($query != '') {
                $this->db->like('p_name', $query);
            }

            $this->db->where($Where);
            $this->db->order_by('p_name', 'DESC');
            $this->db->limit(20, 0);

            return $this->db->get('product');
        }
    
    	function fetchRawMaterialProducts($query)
    	{
                $userId = $this->session->userdata('user_id');
                $compId = $this->session->userdata('comp_id');
                $compBranchId = $this->session->userdata('CompBranchId');
            
                $where = ['user_id' => $userId, 'comp_id' => $compId, 'status' => 1, 'type' => 'Raw'];
                if ($this->session->userdata('BranchRole')) {
                    $where['CompanyBranchId'] = $compBranchId;
                }
            
                $this->db->select("*");    
                if ($query != '') {
                    $this->db->like('p_name', $query);    		
                }            
                $this->db->where($where);
                $this->db->order_by('p_name', 'DESC');
                $this->db->limit(50, 0);
                return $this->db->get('product');
    	}
    
    	function fetchFinishMaterialProducts($query)
    	{
                $userId = $this->session->userdata('user_id');
                $compId = $this->session->userdata('comp_id');
                $compBranchId = $this->session->userdata('CompBranchId');
            
                $where = ['user_id' => $userId, 'comp_id' => $compId, 'status' => 1, 'type' => 'Finish'];
                if ($this->session->userdata('BranchRole')) {
                    $where['CompanyBranchId'] = $compBranchId;
                }
            
                $this->db->select("*");    
                if ($query != '') {
                    $this->db->like('p_name', $query);    		
                }            
                $this->db->where($where);
                $this->db->order_by('p_name', 'DESC');
                $this->db->limit(50, 0);
                return $this->db->get('product');
    	}
    
    	function getProductDataByCode($query)
    	{
            if ($this->session->userdata('RoleType')) {
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
            } else {
                $UserId = $this->session->userdata('user_id');
                $CompId = $this->session->userdata('comp_id');
            }
            $CompanyBranchId = $this->session->userdata('CompBranchId');
            
            $Where = ['user_id' => $UserId, 'comp_id' => $CompId];
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $CompanyBranchId;
            }
            
            $this->db->select("*");
    
            if ($query != '') {    	
                $this->db->like('qrCode', $query);    		
            }            
            $this->db->where($Where);
            $this->db->where('status', 1);
            $this->db->order_by('qrCode', 'DESC');
            return $this->db->get('product');
    	}
    
    	public function getSubServiceData($table)
    	{
            // getting data from subservice data
            $user_id = $this->session->userdata('user_id');
            $crm_id = $this->session->userdata('crm_id');
            $months = $this->session->userdata('months');
            $exp = explode("-", $months);
            $year_id = $this->session->userdata('fy');
            $this->db->select('*');
            $this->db->from($table);
            $this->db->where('user_id', $user_id);
            $this->db->where('crm_id', $crm_id);
            $this->db->where('month', $exp[0]);
            $this->db->where('year', $year_id);
            $this->db->where('subservice_id', $this->session->userdata('sub_service_id'));
            return $this->db->get()->result();
    	}
    
    	public function getPartyForSubservices()
    	{
    		// all the user on the behalf of subservices
    		$user_id = $this->session->userdata('user_id');
    		$crm_id = $this->session->userdata('crm_id');
    		$this->db->select('*');
    		$this->db->from('crm_party');
    		$this->db->where('user_id', $user_id);
    		$this->db->where('crm_id', $crm_id);
    		$this->db->like('sub_services_id', $this->session->userdata('sub_service_id'));
    		$this->db->where('status', 1);
    		return $this->db->get()->result();
    	}
    
    	public function getPartyForServices()
    	{
    
    		// all the user on the behalf of services
    		$user_id = $this->session->userdata('user_id');
    		$crm_id = $this->session->userdata('crm_id');
    		$this->db->select('*');
    		$this->db->from('crm_party');
    		$this->db->where('user_id', $user_id);
    		$this->db->where('crm_id', $crm_id);
    		$this->db->like('services_id', $this->session->userdata('service_id'));
    		$this->db->where('status', 1);
    		return $this->db->get()->result();
    	}
    
    	public function getPartyFromSubServiceCount()
    	{
    		$counter = 0;
    		$getResu = $this->user_model->getPartyForSubservices();
    		if ($getResu) {
    			foreach ($getResu as $category) {
    				$sub_service_assigns = json_decode($category->sub_services_id);
    				if ($sub_service_assigns) {
    					foreach ($sub_service_assigns as $services_data_id) {
    						if ($services_data_id == $this->session->userdata('sub_service_id')) {
    							++$counter;
    						}
    					}
    				}
    			}
    		}
    		return $counter;
    	}
    	public function getStatusForService()
    	{
    		// assined status for services
    		$this->db->select('*');
    		$this->db->from('crm_status');
    		$this->db->like('services_id', $this->session->userdata('service_id'));
    		$this->db->where('status', 1);
    		return $this->db->get()->result();
    	}
    
    	public function getTypeForService()
    	{
    		// assined type for services
    		$this->db->select('*');
    		$this->db->from('crm_type');
    		$this->db->like('services_id', $this->session->userdata('service_id'));
    		$this->db->where('status', 1);
    		return $this->db->get()->result();
    	}
    
    	public function getServiceData($table)
    	{
    		// getting data from subservice data
    		$user_id = $this->session->userdata('user_id');
    		$crm_id = $this->session->userdata('crm_id');
    		$this->db->select('*');
    		$this->db->from($table);
    		$this->db->where('user_id', $user_id);
    		$this->db->where('crm_id', $crm_id);
    		// $this->db->where('status', 1);
    		$this->db->where('service_id', $this->session->userdata('service_id'));
    		return $this->db->get()->result();
    	}
    
    
    	public function getMonthWiseForSubServices($table)
    	{
    		// getting data from subservice data
    		$user_id = $this->session->userdata('user_id');
    		$crm_id = $this->session->userdata('crm_id');
    		$month = $this->session->userdata('months');
    		$exp = explode('-', $month);
            $year_id = $this->session->userdata('fy');
    		$this->db->select('*');
    		$this->db->from($table);
    		$this->db->where('user_id', $user_id);
    		$this->db->where('crm_id', $crm_id);
    		$this->db->where('month', $exp[0]);
    		$this->db->where('year', $year_id);
    		$this->db->where('subservice_id', $this->session->userdata('sub_service_id'));
    		return $this->db->get()->result();
    	}
    
    	public function getPartyFromSubServiceMonthWiseCount()
    	{
    		$counter = 0;
    		$month = $this->session->userdata('months');
    		$exp = explode('-', $month);
    		$year_id = $this->session->userdata('fy');
    		$getResu = $this->user_model->getPartyForSubservices();
    		if ($getResu) {
    			foreach ($getResu as $category) {
    				$sub_service_assigns = json_decode($category->sub_services_id);
    				if ($sub_service_assigns) {
    					foreach ($sub_service_assigns as $services_data_id) {
    						if ($services_data_id == $this->session->userdata('sub_service_id') && 'month' == $exp[0] && 'year' == $year_id) {
    							++$counter;
    						}
    					}
    				}
    			}
    		}
    		return $counter;
    	}
    
    	public function getPartyForSubservicesMonthWise()
    	{
    		// all the user on the behalf of subservices
    		$user_id = $this->session->userdata('user_id');
    		$crm_id = $this->session->userdata('crm_id');
    		$this->db->select('*');
    		$this->db->from('crm_party');
    		$this->db->where('user_id', $user_id);
    		$this->db->where('crm_id', $crm_id);
    		$this->db->like('sub_services_id', $this->session->userdata('sub_service_id'));
    		$this->db->where('status', 1);
    		return $this->db->get()->result();
    	}
    
    
    	public function getMonthWiseForServices($table)
    	{
    		// getting data from subservice data
    		$user_id = $this->session->userdata('user_id');
    		$crm_id = $this->session->userdata('crm_id');
    		$month = $this->session->userdata('months');
    		$exp = explode('-', $month);
	        $year_id = $this->session->userdata('fy');
    		$this->db->select('*');
    		$this->db->from($table);
    		$this->db->where('user_id', $user_id);
    		$this->db->where('crm_id', $crm_id);
            $this->db->where('month', $exp[0]);
            $this->db->where('year', $year_id);
    		$this->db->where('service_id', $this->session->userdata('service_id'));
    		return $this->db->get()->result();
    	}
    
    
    	public function getPermissionForUser($column)
    	{
    		$user_id = $this->session->userdata('user_id');
    		$this->db->select('*');
    		$this->db->from('module_assign');
    		$this->db->where('user_id', $user_id);
    		$singleRow = $this->db->get()->row();
    		if ($singleRow) {
    			$this->db->select('*');
    			$this->db->from('module_assign');
    			$this->db->where('user_id', $user_id);
    			$this->db->where($column, $column);
    			$returnData = $this->db->get()->row();
    			if ($returnData)
    				return true;
    			else
    				return false;
    		}
    		return true;
    	}
    
    
    	public function getGroupByColumn($special_service_id = null, $column = null, $select_column = null)
    	{
    		$this->db->select($select_column);
    		$this->db->from('digital_certificate_subservices');
    		$this->db->where('digital_certificate_id', $special_service_id);
    		$this->db->group_by($column);
    		return $this->db->get()->result();
    	}
    
    	public function allrecord($table, $where, $title)
    	{
    		if (!empty($title)) {
    			$this->db->like('name', $title);
    			$this->db->or_like('email', $title);
    		}
    		$this->db->select('*');
    		$this->db->from($table);
    		$this->db->where($where); //IF YOU WANT WHERE CONDITION THEN USE THIS CODE
    		$rs = $this->db->get();
    		$rs->num_rows();
    		return $rs->num_rows();
    	}
    	//--- fetch record list with search offset
    	public function data_list($table, $where, $limit, $offset, $title)
    	{
    		if (!empty($title)) {
    			$this->db->like('name', $title);
    		}
    		$this->db->select('*');
    		$this->db->from($table);
    		$this->db->where($where); //IF YOU WANT WHERE CONDITION THEN USE THIS CODE
    		$this->db->order_by('ID', 'DESC');
    		$this->db->limit($limit, $offset);
    		$rs = $this->db->get();
    		return $rs->result();
    	}
    
    	function getRows($params = array())
    	{
    		$this->table = 'digital_certificate_apply';
    		$this->db->select('*');
    		$this->db->from($this->table);
    
    		if (array_key_exists("where", $params)) {
    			foreach ($params['where'] as $key => $val) {
    				$this->db->where($key, $val);
    			}
    		}
    		if (array_key_exists("search", $params)) {
    			// Filter data by searched keywords 
    			if (!empty($params['search']['keywords'])) {
    				// $this->db->or_like(array('name' => $params['search']['keywords'], 'email' => $params['search']['keywords']));/* LIKE OR LIKE */
    				$this->db->like('name', $params['search']['keywords']);
    				// $this->db->where('email', $params['search']['keywords']);
    				// $this->db->like('email', $params['search']['keywords']);
    				// $this->db->or_like('gst_number', $params['search']['keywords']);
    				// $this->db->or_like('state', $params['search']['keywords']);
    				// $this->db->or_like('usb_token', $params['search']['keywords']);
    				// $this->db->or_like('payment_id', $params['search']['keywords']);
    				// $this->db->or_like('certificate_type', $params['search']['keywords']);
    				// $this->db->or_like('user_type', $params['search']['keywords']);
    				// $this->db->or_like('validity', $params['search']['keywords']);
    				// $this->db->or_like('address', $params['search']['keywords']);
    			}
    		}
    
    		// Sort data by ascending or desceding order 
    		if (!empty($params['search']['sortBy'])) {
    			$this->db->order_by('id', $params['search']['sortBy']);
    		} else {
    			$this->db->order_by('id', 'desc');
    		}
    
    		if (array_key_exists("returnType", $params) && $params['returnType'] == 'count') {
    			$result = $this->db->count_all_results();
    		} else {
    			if (array_key_exists("id", $params) || (array_key_exists("returnType", $params) && $params['returnType'] == 'single')) {
    				if (!empty($params['id'])) {
    					$this->db->where('id', $params['id']);
    				}
    
    				$query = $this->db->get();
    				$result = $query->row_array();
    			} else {
    				$this->db->order_by('id', 'desc');
    				if (array_key_exists("start", $params) && array_key_exists("limit", $params)) {
    					$this->db->limit($params['limit'], $params['start']);
    				} elseif (!array_key_exists("start", $params) && array_key_exists("limit", $params)) {
    					$this->db->limit($params['limit']);
    				}
    				$query = $this->db->get();
    				$result = ($query->num_rows() > 0) ? $query->result_array() : FALSE;
    			}
    		}
    
    
    		// Return fetched data 
    		return $result;
    	}
    	
		function getRowsTables($table, $params = array())
    	{
    		$this->table = $table;
    		$this->db->select('*');
    		$this->db->from($this->table);
    
    		if (array_key_exists("where", $params)) {
    			foreach ($params['where'] as $key => $val) {
    				$this->db->where($key, $val);
    			}
    		}
    		if (array_key_exists("search", $params)) {
    			if (!empty($params['search']['keywords'])) {
    				$this->db->like('name', $params['search']['keywords']);
    				$this->db->where('email', $params['search']['keywords']);
    				$this->db->like('mobile_number', $params['search']['keywords']);
    			}
    		}
    
    		// Sort data by ascending or desceding order 
    		if (!empty($params['search']['sortBy'])) {
    			$this->db->order_by('id', $params['search']['sortBy']);
    		} else {
    			$this->db->order_by('id', 'desc');
    		}
    
    		if (array_key_exists("returnType", $params) && $params['returnType'] == 'count') {
    			$result = $this->db->count_all_results();
    		} else {
    			if (array_key_exists("id", $params) || (array_key_exists("returnType", $params) && $params['returnType'] == 'single')) {
    				if (!empty($params['id'])) {
    					$this->db->where('id', $params['id']);
    				}
    
    				$query = $this->db->get();
    				$result = $query->row_array();
    			} else {
    				$this->db->order_by('id', 'desc');
    				if (array_key_exists("start", $params) && array_key_exists("limit", $params)) {
    					$this->db->limit($params['limit'], $params['start']);
    				} elseif (!array_key_exists("start", $params) && array_key_exists("limit", $params)) {
    					$this->db->limit($params['limit']);
    				}
    				$query = $this->db->get();
    				$result = ($query->num_rows() > 0) ? $query->result_array() : FALSE;
    			}
    		}
    
    
    		// Return fetched data 
    		return $result;
    	}
    
    	public function checkService($serachvalue)
    	{
    		$this->db->select('*');
    		$this->db->from('special_services');
    		$this->db->or_like('service_name', $serachvalue);
    		$this->db->get()->result();
    	}
    
    	public function checkUser($serachvalue)
    	{
    		$this->db->select('*');
    		$this->db->from('users');
    		$this->db->or_like('name', $serachvalue);
    		$this->db->get()->result();
    	}
    
    	// public function checkModule($url)
    	// {
    	// 	switch ($url) {
    	// 		case "file_sharing":
    	// 			break;
    	// 		case "inventory_billing":
    	// 			break;
    	// 		case "service":
    	// 			break;
    	// 		case "wallet":
    	// 			break;
    	// 		default:
    	// 			return true;
    	// 	}
    	// 	return false;
    	// }
    	
    	function getCommonRows($params = array(), $table = '', $column_filter = null)
    	{
    		$this->table = $table;
    		$this->db->select('*');
    		$this->db->from($this->table);
    
    		if (array_key_exists("where", $params)) {
    			foreach ($params['where'] as $key => $val) {
    				$this->db->where($key, $val);
    			}
    		}
    		if (array_key_exists("search", $params)) {
    			// Filter data by searched keywords 
    			if (!empty($params['search']['keywords'])) {
    				if (!empty($column_filter)) {
    					$this->db->like($column_filter, $params['search']['keywords']);
    				} else {
    					$this->db->like('name', $params['search']['keywords']);
    					// default searching is name
    				}
    				// $this->db->or_like(array('name' => $params['search']['keywords'], 'email' => $params['search']['keywords']));/* LIKE OR LIKE */
    				// if (!empty($column_filter)) {
    				// foreach ($column_filter as $columns) {
    				// if (count($column_filter) == 1) {
    				// 	$this->db->like($columns, $params['search']['keywords']);
    				// } else {
    				// 	$this->db->where("(" . $columns . " LIKE '%" . $params['search']['keywords'] . "%' OR " . $columns . " LIKE '%" . $params['search']['keywords'] . "%' OR " . $columns . " LIKE '%" . $params['search']['keywords'] . "%' OR " . $columns . " LIKE '%" . $params['search']['keywords'] . "%')", NULL, FALSE);
    				// }
    				// }
    			}
    			// $this->db->where('email', $params['search']['keywords']);
    			// $this->db->like('email', $params['search']['keywords']);
    			// $this->db->or_like('gst_number', $params['search']['keywords']);
    			// $this->db->or_like('state', $params['search']['keywords']);
    			// $this->db->or_like('usb_token', $params['search']['keywords']);
    			// $this->db->or_like('payment_id', $params['search']['keywords']);
    			// $this->db->or_like('certificate_type', $params['search']['keywords']);
    			// $this->db->or_like('user_type', $params['search']['keywords']);
    			// $this->db->or_like('validity', $params['search']['keywords']);
    			// $this->db->or_like('address', $params['search']['keywords']);
    		}
    
    		// Sort data by ascending or desceding order 
    		if (!empty($params['search']['sortBy'])) {
    			$this->db->order_by('id', $params['search']['sortBy']);
    		} else {
    			$this->db->order_by('id', 'desc');
    		}
    
    		if (array_key_exists("returnType", $params) && $params['returnType'] == 'count') {
    			$result = $this->db->count_all_results();
    		} else {
    			if (array_key_exists("id", $params) || (array_key_exists("returnType", $params) && $params['returnType'] == 'single')) {
    				if (!empty($params['id'])) {
    					$this->db->where('id', $params['id']);
    				}
    
    				$query = $this->db->get();
    				$result = $query->row_array();
    			} else {
    				$this->db->order_by('id', 'desc');
    				if (array_key_exists("start", $params) && array_key_exists("limit", $params)) {
    					$this->db->limit($params['limit'], $params['start']);
    				} elseif (!array_key_exists("start", $params) && array_key_exists("limit", $params)) {
    					$this->db->limit($params['limit']);
    				}
    				$query = $this->db->get();
    				$result = ($query->num_rows() > 0) ? $query->result_array() : FALSE;
    			}
    		}
    
    
    		// Return fetched data 
    		return $result;
    	}
    	
        public function getActiveUsers()
        {
            $today = strtotime('now'); 
            $date = strtotime("-30 days", $today);
            $this->db->select('t1.id AS lid, MAX(t1.strtotime) AS strtotime, t2.name AS uname, t2.email AS email, t2.number AS number, t2.image AS image, t3.name AS rname');
            $this->db->from('userlogindetails AS t1');
            $this->db->join('users AS t2', 't2.id = t1.userId', 'LEFT');
            $this->db->join('roles AS t3', 't3.id = t2.userType', 'LEFT');
            $this->db->where('t1.strtotime >=', $date);
            $this->db->group_by('t1.userId');
            $this->db->order_by('strtotime', 'DESC');
            $query = $this->db->get();
            return $query->result();

        }

        public function getInActiveUsers()
        {
            $today = strtotime('now'); 
            $date = strtotime("-30 days", $today);
            $this->db->select('MAX(t1.strtotime) AS strtotime, t2.name AS uname, t2.email AS email, t2.number AS number, t2.image AS image, t3.name AS rname');
            $this->db->from('userlogindetails AS t1');
            $this->db->join('users AS t2', 't2.id = t1.userId', 'LEFT');
            $this->db->join('roles AS t3', 't3.id = t2.userType', 'LEFT');
            $this->db->where('t1.strtotime <=', $date);
            $this->db->group_by('t1.userId');
            $this->db->order_by('strtotime', 'DESC');
            $query = $this->db->get();
            return $query->result();
        }
        
        public function getSalemanRequest($table, $type, $status, $from, $to, $where) 
        {
            if (isset($table) && !empty($table)) {
                $this->db->from($table);
            }
            if (isset($type) && !empty($type)) {
                $this->db->where($type);
            }
            if (isset($status) && !empty($status)) {
                $this->db->where($status);
            }
            if (isset($from) && !empty($from)) {
                $this->db->where($from);
            }
            if (isset($to) && !empty($to)) {
                $this->db->where($to);
            }
            if (isset($where) && !empty($where)) {
                $this->db->where($where);
            }
            $this->db->order_by('id', 'DESC');
            return $this->db->get()->result();
        }
        
        public function getRowsDataOutstatding($params = array(), $table = '')
        {   
            $this->db->select('SUM(total) AS Total, SUM(paid_amount) AS PaidAmount, SUM(total) - SUM(COALESCE(paid_amount, 0)) AS Balance, customerId, user_id');
            $this->db->group_by('customerId');
            $this->db->from($table); 
            $this->db->having('Balance > ', 0);
            
            if (array_key_exists("where", $params)) { 
                foreach($params['where'] as $key => $val) { 
                    $this->db->where($key, $val); 
                } 
            } 

            if (array_key_exists("search", $params)) { 
                // Filter data by searched keywords 
                if (!empty($params['search']['keywords'])) { 
                    $this->db->like('customerName', $params['search']['keywords']);
                } 
            } 

            // Sort data by ascending or desceding order 
            if (!empty($params['search']['sortBy'])) { 
                $this->db->order_by('invoiceDate', $params['search']['sortBy']); 
            } else { 
                $this->db->order_by('id', 'DESC'); 
            } 

            if (array_key_exists("returnType", $params) && $params['returnType'] == 'count') { 
                $result = $this->db->count_all_results(); 
            } else { 
                if (array_key_exists("id", $params) || (array_key_exists("returnType", $params) && $params['returnType'] == 'single')) { 
                    if (!empty($params['id'])) { 
                        $this->db->where('id', $params['id']); 
                    } 
                    $query = $this->db->get(); 
                    $result = $query->row_array(); 
                } else { 
                    $this->db->order_by('id', 'DESC'); 
                    if (array_key_exists("start", $params) && array_key_exists("limit", $params)) { 
                        $this->db->limit($params['limit'], $params['start']); 
                    } elseif (!array_key_exists("start", $params) && array_key_exists("limit", $params)) { 
                        $this->db->limit($params['limit']); 
                    } 

                    $query = $this->db->get(); 
                    $result = ($query->num_rows() > 0) ? $query->result_array() : FALSE; 
                } 
            } 

            // Return fetched data 
            return $result;
        }
        
        public function getRowsDataPreviousSale($params = array(), $table = '')
        {   
            $this->db->select('invoiceDate, customerId, customerName, user_id, total');
            $this->db->from($table); 

            if (array_key_exists("where", $params)) { 
                foreach($params['where'] as $key => $val) { 
                    $this->db->where($key, $val); 
                } 
            } 

            if (array_key_exists("search", $params)) { 
                // Filter data by searched keywords 
                if (!empty($params['search']['keywords'])) { 
                    $this->db->like('customerName', $params['search']['keywords']);
                    $this->db->or_like('invoiceDate', $params['search']['keywords']);
                    $this->db->or_like('total', $params['search']['keywords']);
                } 
            } 

            // Sort data by ascending or desceding order 
            if (!empty($params['search']['sortBy'])) { 
                $this->db->order_by('invoiceDate', $params['search']['sortBy']); 
            } else { 
                $this->db->order_by('id', 'DESC'); 
            } 

            if (array_key_exists("returnType", $params) && $params['returnType'] == 'count') { 
                $result = $this->db->count_all_results(); 
            } else { 
                if (array_key_exists("id", $params) || (array_key_exists("returnType", $params) && $params['returnType'] == 'single')) { 
                    if (!empty($params['id'])) { 
                        $this->db->where('id', $params['id']); 
                    } 
                    $query = $this->db->get(); 
                    $result = $query->row_array(); 
                } else { 
                    $this->db->order_by('id', 'DESC'); 
                    if (array_key_exists("start", $params) && array_key_exists("limit", $params)) { 
                        $this->db->limit($params['limit'], $params['start']); 
                    } elseif (!array_key_exists("start", $params) && array_key_exists("limit", $params)) { 
                        $this->db->limit($params['limit']); 
                    } 

                    $query = $this->db->get(); 
                    $result = ($query->num_rows() > 0) ? $query->result_array() : FALSE; 
                } 
            } 

            // Return fetched data 
            return $result; 
        }
        
        public function getRowsDataPopullerProducts($params = array(), $table = '')
        {   
            $this->db->select('product.user_id, invoice_product.product_id, invoice_product.productname, SUM(invoice_product.qty) AS SaleQty');
            $this->db->join('product', 'product.id = invoice_product.product_id', 'LEFT');
            $this->db->group_by('invoice_product.product_id');
            $this->db->from($table); 

            if (array_key_exists("where", $params)) { 
                foreach($params['where'] as $key => $val) { 
                    $this->db->where($key, $val); 
                } 
            } 

            if (array_key_exists("search", $params)) { 
                // Filter data by searched keywords 
                if (!empty($params['search']['keywords'])) { 
                    $this->db->like('invoice_product.productname', $params['search']['keywords']);
                    $this->db->or_like('product.unit_name', $params['search']['keywords']);
                    
                } 
            } 

            // Sort data by ascending or desceding order 
            if (!empty($params['search']['sortBy'])) { 
                $this->db->order_by('SUM(invoice_product.qty)', $params['search']['sortBy']); 
            } else { 
                $this->db->order_by('SUM(invoice_product.qty)', 'DESC'); 
            } 

            if (array_key_exists("returnType", $params) && $params['returnType'] == 'count') { 
                $result = $this->db->count_all_results(); 
            } else { 
                if (array_key_exists("id", $params) || (array_key_exists("returnType", $params) && $params['returnType'] == 'single')) { 
                    if (!empty($params['id'])) { 
                        $this->db->where('invoice_product.id', $params['id']); 
                    } 
                    $query = $this->db->get(); 
                    $result = $query->row_array(); 
                } else { 
                    $this->db->order_by('SUM(invoice_product.qty)', 'DESC'); 
                    if (array_key_exists("start", $params) && array_key_exists("limit", $params)) { 
                        $this->db->limit($params['limit'], $params['start']); 
                    } elseif (!array_key_exists("start", $params) && array_key_exists("limit", $params)) { 
                        $this->db->limit($params['limit']); 
                    } 

                    $query = $this->db->get(); 
                    $result = ($query->num_rows() > 0) ? $query->result_array() : FALSE; 
                } 
            } 

            // Return fetched data 
            return $result; 
        }
        
        public function getRowsDataStock($params = array(), $table = '')
        {   
            $this->db->select('*');
            $this->db->from($table); 

            if (array_key_exists("where", $params)) { 
                foreach($params['where'] as $key => $val) { 
                    $this->db->where($key, $val); 
                } 
            } 

            if (array_key_exists("search", $params)) { 
                // Filter data by searched keywords 
                if (!empty($params['search']['keywords'])) { 
                    $this->db->like('p_name', $params['search']['keywords']);
                } 
            } 

            // Sort data by ascending or desceding order 
            if (!empty($params['search']['sortBy'])) { 
                $this->db->order_by('id', $params['search']['sortBy']); 
            } else { 
                $this->db->order_by('id', 'DESC'); 
            } 

            if (array_key_exists("returnType", $params) && $params['returnType'] == 'count') { 
                $result = $this->db->count_all_results(); 
            } else { 
                if (array_key_exists("id", $params) || (array_key_exists("returnType", $params) && $params['returnType'] == 'single')) { 
                    if (!empty($params['id'])) { 
                        $this->db->where('id', $params['id']); 
                    } 
                    $query = $this->db->get(); 
                    $result = $query->row_array(); 
                } else { 
                    $this->db->order_by('id', 'DESC'); 
                    if (array_key_exists("start", $params) && array_key_exists("limit", $params)) { 
                        $this->db->limit($params['limit'], $params['start']); 
                    } elseif (!array_key_exists("start", $params) && array_key_exists("limit", $params)) { 
                        $this->db->limit($params['limit']); 
                    } 

                    $query = $this->db->get(); 
                    $result = ($query->num_rows() > 0) ? $query->result_array() : FALSE; 
                } 
            } 

            // Return fetched data 
            return $result;
        }
        
        public function getRowsDataProduct($params = array(), $table = '')
        {   
            $this->db->select('*');
            $this->db->from($table); 

            if (array_key_exists("where", $params)) { 
                foreach($params['where'] as $key => $val) { 
                    $this->db->where($key, $val); 
                } 
            }
            
            if (array_key_exists("search", $params)) { 
                // Filter data by searched keywords 
                if (!empty($params['search']['keywords'])) { 
                    $this->db->like('p_name', $params['search']['keywords']);
                    $this->db->or_like('hsn', $params['search']['keywords']);
                    $this->db->or_like('unit_name', $params['search']['keywords']);
                } 
            } 
            // Sort data by ascending or desceding order 
            if (!empty($params['search']['sortBy'])) { 
                $this->db->order_by('id', $params['search']['sortBy']); 
            } else { 
                $this->db->order_by('id', 'DESC'); 
            } 

            if (array_key_exists("returnType", $params) && $params['returnType'] == 'count') { 
                $result = $this->db->count_all_results(); 
            } else { 
                if (array_key_exists("id", $params) || (array_key_exists("returnType", $params) && $params['returnType'] == 'single')) { 
                    if (!empty($params['id'])) { 
                        $this->db->where('id', $params['id']); 
                    } 
                    $query = $this->db->get(); 
                    $result = $query->row_array(); 
                } else { 
                    $this->db->order_by('id', 'DESC'); 
                    if (array_key_exists("start", $params) && array_key_exists("limit", $params)) { 
                        $this->db->limit($params['limit'], $params['start']); 
                    } elseif (!array_key_exists("start", $params) && array_key_exists("limit", $params)) { 
                        $this->db->limit($params['limit']); 
                    } 

                    $query = $this->db->get(); 
                    $result = ($query->num_rows() > 0) ? $query->result_array() : FALSE; 
                } 
            } 

            // Return fetched data 
            return $result;
        }
        
        public function bulkProforma($table, $data, $invoiceID, $InvoiceNo, $invoiceDate)
        {
                if (isset($data) && !empty($data)) {
                        $compProfileWhere = ['user_id' => $this->session->userdata("user_id"), 'comp_id' => $this->session->userdata("comp_id")];
                        if ($this->session->userdata("BranchRole")) {
                                $compProfileWhere["CompanyBranchId"] = $this->session->userdata("CompBranchId");
                        }
                        $categoryForGlass = get_title('comp_profile', $compProfileWhere, 'category');
                        
                        foreach ($data as $productData) {
                                $row['InvoiceNo'] = $InvoiceNo;
                                $row['invoiceID'] = $invoiceID;
                                $row['invoiceDate'] = $invoiceDate;
                                $row['comp_id'] = $this->session->userdata('comp_id');
                                $row['user_id'] = $this->session->userdata('user_id');
                                $row['CompanyBranchId'] = $this->session->userdata("CompBranchId");
                                $row['product_details'] = json_encode($productData);
                                $row['type'] = 'Proforma';
                                $row['product_id'] = $productData['product'];
                                $row['product'] = $productData['product'];
                                $row['p_code'] = get_title('product', ['id' => $productData['product']], 'qrCode');
                                $row['productname'] = get_title('product', ['id' => $productData['product']], 'p_name');
                                $row['discount'] = $productData['discount'];
                                if ($categoryForGlass != "Normal") {
                                        $row['qty'] = $productData['sqft'];
                                        $row['sqft'] = $productData['qty'];
                                } else {
                                        $row['qty'] = $productData['qty'];
                                        $row['sqft'] = $productData['sqft'];
                                }
                                $row['hsn'] = $productData['hsn'];
                                $row['tax'] = $productData['tax'];
                                $row['sgst'] = $productData['sgst'];
                                $row['cgst'] = $productData['cgst'];
                                $row['igst'] = $productData['igst'];
                                $row['financial_year'] = get_title('proforma', ['id' => $invoiceID], 'financial_year');
                                $row['purchase_price'] = $productData['price'];
                                $row['price'] = $productData['price'];
                                $row['taxAmount'] = $productData['taxAmount'];
                                $row['amount'] = $productData['amount'];
                                $row['rate'] = $productData['rate'];
                                $row['netAmount'] = $productData['netAmount'];
                                $row['product_dec'] = $productData['product_dec'];
                                $row['warehouse'] = $productData['warehouse'];
            
                                $this->db->insert($table, $row);
                                if ($this->db->trans_status() === FALSE) {
                                        $this->db->trans_rollback();
                                        return false;
                                }
                        }
                        $this->db->trans_commit();
                        return true;
                }
                return false;
        }
        
        public function getRowsDataOutstatdingEmployee ($params = array(), $EmployeeSalesWhere = null, $table = '')
        {
            $this->db->select('SUM(total) AS Total, SUM(paid_amount) AS PaidAmount, SUM(total) - SUM(COALESCE(paid_amount, 0)) AS Balance, customerId, user_id');
            if (isset($EmployeeSalesWhere) && !empty($EmployeeSalesWhere)) {
                $this->db->where_in('SalesmanId', $EmployeeSalesWhere);
            }
            $this->db->group_by('customerId');
            $this->db->from($table);
            $this->db->having('Balance > ', 0);

            if (array_key_exists("where", $params)) { 
                foreach($params['where'] as $key => $val) { 
                    $this->db->where($key, $val); 
                } 
            } 

            if (array_key_exists("search", $params)) { 
                // Filter data by searched keywords 
                if (!empty($params['search']['keywords'])) { 
                    $this->db->like('customerName', $params['search']['keywords']);
                } 
            }

            if (array_key_exists("returnType", $params) && $params['returnType'] == 'count') { 
                $result = $this->db->count_all_results(); 
            } else { 
                if (array_key_exists("id", $params) || (array_key_exists("returnType", $params) && $params['returnType'] == 'single')) { 
                    if (!empty($params['id'])) { 
                        $this->db->where('id', $params['id']); 
                    } 
                    $query = $this->db->get(); 
                    $result = $query->row_array(); 
                } else { 
                    $this->db->order_by('id', 'DESC'); 
                    if (array_key_exists("start", $params) && array_key_exists("limit", $params)) { 
                        $this->db->limit($params['limit'], $params['start']); 
                    } elseif (!array_key_exists("start", $params) && array_key_exists("limit", $params)) { 
                        $this->db->limit($params['limit']); 
                    } 

                    $query = $this->db->get(); 
                    $result = ($query->num_rows() > 0) ? $query->result_array() : FALSE; 
                } 
            } 

            // Return fetched data 
            return $result;
        }
        
        public function getRowsDataPreviousSaleEmployee ($params = array(), $EmployeeSalesWhere = null, $table = '')
        {   
            $this->db->select('invoiceDate, customerId, customerName, user_id, total');
            if (isset($EmployeeSalesWhere) && !empty($EmployeeSalesWhere)) {
                $this->db->where_in('SalesmanId', $EmployeeSalesWhere);
            }
            $this->db->from($table); 

            if (array_key_exists("where", $params)) { 
                foreach($params['where'] as $key => $val) { 
                    $this->db->where($key, $val); 
                } 
            } 

            if (array_key_exists("search", $params)) { 
                // Filter data by searched keywords 
                if (!empty($params['search']['keywords'])) { 
                    $this->db->like('customerName', $params['search']['keywords']);
                    $this->db->or_like('invoiceDate', $params['search']['keywords']);
                    $this->db->or_like('total', $params['search']['keywords']);
                } 
            }

            if (array_key_exists("returnType", $params) && $params['returnType'] == 'count') { 
                $result = $this->db->count_all_results(); 
            } else { 
                if (array_key_exists("id", $params) || (array_key_exists("returnType", $params) && $params['returnType'] == 'single')) { 
                    if (!empty($params['id'])) { 
                        $this->db->where('id', $params['id']); 
                    } 
                    $query = $this->db->get(); 
                    $result = $query->row_array(); 
                } else { 
                    $this->db->order_by('id', 'DESC'); 
                    if (array_key_exists("start", $params) && array_key_exists("limit", $params)) { 
                        $this->db->limit($params['limit'], $params['start']); 
                    } elseif (!array_key_exists("start", $params) && array_key_exists("limit", $params)) { 
                        $this->db->limit($params['limit']); 
                    } 

                    $query = $this->db->get(); 
                    $result = ($query->num_rows() > 0) ? $query->result_array() : FALSE; 
                } 
            } 

            // Return fetched data 
            return $result; 
        }
        
        public function getRowsDataPopullerProductsEmployee ($params = array(), $EmployeeSalesWhere = null, $table = '')
        {   
            $this->db->select('invoice_product.product_id, invoice_product.productname, SUM(invoice_product.qty) AS SaleQty');
            $this->db->join('sales', 'sales.id = invoice_product.invoiceID', 'LEFT');
            $this->db->join('product', 'product.id = invoice_product.product_id', 'LEFT');
            if (isset($EmployeeSalesWhere) && !empty($EmployeeSalesWhere)) {
                $this->db->where_in('sales.SalesmanId', $EmployeeSalesWhere);
            }
            $this->db->group_by('invoice_product.product_id');
            $this->db->from($table); 
            
            if (array_key_exists("where", $params)) {
                foreach($params['where'] as $key => $val) { 
                    $this->db->where($key, $val); 
                } 
            } 
            
            if (array_key_exists("search", $params)) { 
                // Filter data by searched keywords 
                if (!empty($params['search']['keywords'])) { 
                    $this->db->like('invoice_product.productname', $params['search']['keywords']);
                    $this->db->or_like('product.unit_name', $params['search']['keywords']);
                    
                } 
            }

            if (array_key_exists("returnType", $params) && $params['returnType'] == 'count') { 
                $result = $this->db->count_all_results(); 
            } else { 
                if (array_key_exists("id", $params) || (array_key_exists("returnType", $params) && $params['returnType'] == 'single')) { 
                    if (!empty($params['id'])) { 
                        $this->db->where('invoice_product.id', $params['id']); 
                    } 
                    $query = $this->db->get(); 
                    $result = $query->row_array(); 
                } else { 
                    $this->db->order_by('SUM(invoice_product.qty)', 'DESC'); 
                    if (array_key_exists("start", $params) && array_key_exists("limit", $params)) { 
                        $this->db->limit($params['limit'], $params['start']); 
                    } elseif (!array_key_exists("start", $params) && array_key_exists("limit", $params)) { 
                        $this->db->limit($params['limit']); 
                    } 

                    $query = $this->db->get(); 
                    $result = ($query->num_rows() > 0) ? $query->result_array() : FALSE; 
                } 
            } 

            // Return fetched data 
            return $result; 
        }
        
        public function getSalesInvoicesData($params = array(), $table = '')
        {   
            $this->db->select('sales.id AS id, sales.invoiceDate AS invoiceDate, sales.invoiceNumber AS invoiceNumber, sales.type AS type, sales.customerId AS customerId, sales.voucherType AS voucherType, sales.taxableAmount AS taxableAmount, sales.totalTax AS totalTax, sales.total AS total, sales.paid_amount AS paid_amount, sales.delivery_status AS delivery_status, sales.created AS created, sales.created_by AS created_by, sales.user_id AS user_id, sales.updated AS updated, sales.updated_by AS updated_by, sales.status AS status, parties.name AS name, parties.contact AS contact, parties.gst AS gst, parties.SalesmanId AS SalesmanId');
            $this->db->join('parties', 'parties.id = sales.customerId', 'LEFT');
            $this->db->join('employees', 'employees.Id = parties.SalesmanId', 'LEFT');
            $this->db->from($table); 

            if (array_key_exists("where", $params)) { 
                foreach($params['where'] as $key => $val) { 
                    $this->db->where($key, $val); 
                } 
            } 

            if (array_key_exists("search", $params)) { 
                // Filter data by searched keywords 
                if (!empty($params['search']['Keywords'])) { 
                    $this->db->like('parties.name', $params['search']['Keywords']);
                    $this->db->or_like('sales.invoiceNumber', $params['search']['Keywords']);
                    $this->db->or_like('employees.Name', $params['search']['Keywords']);
                } 
            } 

            // Sort data by ascending or desceding order 
            if (!empty($params['search']['SortBy'])) { 
                $this->db->order_by('id', $params['search']['SortBy']); 
            } else { 
                $this->db->order_by('id', 'DESC'); 
            } 

            if (array_key_exists("returnType", $params) && $params['returnType'] == 'count') { 
                $result = $this->db->count_all_results(); 
            } else { 
                if (array_key_exists("id", $params) || (array_key_exists("returnType", $params) && $params['returnType'] == 'single')) { 
                    if (!empty($params['id'])) { 
                        $this->db->where('id', $params['id']); 
                    } 
                    $query = $this->db->get(); 
                    $result = $query->row(); 
                } else { 
                    $this->db->order_by('id', 'DESC'); 
                    if (array_key_exists("start", $params) && array_key_exists("limit", $params)) { 
                        $this->db->limit($params['limit'], $params['start']); 
                    } elseif (!array_key_exists("start", $params) && array_key_exists("limit", $params)) { 
                        $this->db->limit($params['limit']); 
                    }

                    $query = $this->db->get(); 
                    $result = ($query->num_rows() > 0) ? $query->result() : FALSE; 
                } 
            } 

            // Return fetched data 
            return $result;
        }
        
        public function getEwayBill($table, $start, $limit, $order, $invoiceNumber, $invoiceDate, $fromDate = '', $toDate = '', $partyName = '', $productName = '', $where = '', $join = '', $party = '', $Salesman = '')
    	{
            $this->db->select('*');
            if (isset($join) && !empty($join)) {
                $this->db->join($join['table'], $join['condition']);
            }
             
                $this->db->where($where); 
            // if (isset($invoiceNumber) && !empty($invoiceNumber)) {
            //     $this->db->where($invoiceNumber);
            // }
            if (isset($fromDate) && !empty($fromDate) && isset($toDate) && !empty($toDate)) {
                $this->db->where($fromDate);
                $this->db->where($toDate);
            }
            if (isset($partyName) && !empty($partyName)) {
                $this->db->where($partyName);
            }
            // if (isset($productName) && !empty($productName)) {
            //     $this->db->like($productName);
            // }
            if (is_array($order) && isset($order['col'], $order['order'])) {
                $this->db->order_by($order['col'], $order['order']);
            } elseif (is_array($order) && !empty($order) && !isset($order['col'])) {
                foreach ($order as $col => $dir) {
                    if ($col !== '' && $dir !== '') {
                        $this->db->order_by($col, $dir);
                    }
                }
            }
            if (isset($limit) && !empty($limit) || isset($start) && !empty($start)) {
                $this->db->limit($limit, $start);
            }
            // $this->db->where($where);
            if (isset($party) && !empty($party)) {
                $this->db->where_in('customerId', $party);
            }
            // if (isset($Salesman) && !empty($Salesman)) {
            //     $this->db->where_in('SalesmanId', $Salesman);
            // }
            return $this->db->get($table)->result();
    	}

        public function getEinvoice($table, $start, $limit, $order, $invoiceNumber, $invoiceDate, $fromDate = '', $toDate = '', $partyName = '', $productName = '', $where = '', $join = '', $party = '', $Salesman = '')
    	{
            $this->db->select('*');
            if (isset($join) && !empty($join)) {
                $this->db->join($join['table'], $join['condition']);
            }
             
                $this->db->where($where);  
                $this->db->where('Irn !=','');
            // if (isset($invoiceNumber) && !empty($invoiceNumber)) {
            //     $this->db->where($invoiceNumber);
            // }
            if (isset($fromDate) && !empty($fromDate) && isset($toDate) && !empty($toDate)) {
                $this->db->where($fromDate);
                $this->db->where($toDate);
            }
            if (isset($partyName) && !empty($partyName)) {
                $this->db->where($partyName);
            }
            // if (isset($productName) && !empty($productName)) {
            //     $this->db->like($productName);
            // }
            if (is_array($order) && isset($order['col'], $order['order'])) {
                $this->db->order_by($order['col'], $order['order']);
            } elseif (is_array($order) && !empty($order) && !isset($order['col'])) {
                foreach ($order as $col => $dir) {
                    if ($col !== '' && $dir !== '') {
                        $this->db->order_by($col, $dir);
                    }
                }
            }
            if (isset($limit) && !empty($limit) || isset($start) && !empty($start)) {
                $this->db->limit($limit, $start);
            }
            // $this->db->where($where);
            if (isset($party) && !empty($party)) {
                $this->db->where_in('customerId', $party);
            }
            // if (isset($Salesman) && !empty($Salesman)) {
            //     $this->db->where_in('SalesmanId', $Salesman);
            // }
            return $this->db->get($table)->result();
    	}
    	
        public function getCount($tableName, $where)
        {
            return $this->db->where($where)->count_all($tableName);
        }
        
        public function getPaginationData($limit, $start, $tableName, $where)
        {
            $this->db->where($where);
            $this->db->order_by('id', 'DESC');
            $this->db->limit($limit, $start);
            $query = $this->db->get($tableName);
            return $query->result();
        }
    }