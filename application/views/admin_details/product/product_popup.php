<div class="" id="productMsg" style="opacity: 500; display: none;"></div>
 <form id="addProductForm" action="<?= base_url() ?>save-products" method="post" onsubmit="onProductFormSubmitHandler(event)">
     <div class="row">
         <div class="col-sm-4 mb-2">
             <label for="type_id" class="title">Product Type <span class="text-danger">*</span></label>
             <select name="type_id" id="type_id" class="form-control searchselect" style="width:100%" required>
                 <option value="">-Select-</option>
                 <option value="1">Normal</option>
                 <option value="2">Attribute</option>
             </select>
         </div>
         <div class="col-sm-4 mb-2">
             <label for="title" class="title">Product Title <span class="text-danger">*</span></label>
             <input autocomplete="off" name="title" id="title" class="form-control" type="text" placeholder="Product Title" required>
         </div>
         <div class="col-sm-4 mb-2">
             <label for="product_code" class="title">Product Code</label>
             <input autocomplete="off" name="product_code" id="product_code" class="form-control" type="text" placeholder="Product Code">
         </div>
         <div class="col-sm-4 mb-2">
             <label for="hsn" class="title">Hsn Code</label>
             <input autocomplete="off" name="hsn" id="hsn" class="form-control" type="text" placeholder="Hsn Code">
         </div>
         <div class="col-sm-4 mb-2">
             <label for="hsn" class="title">Category</label>
             <!-- <select name="category" id="category" class="form-control searchselect" style="width:100%" required>
                 <option value="">-Select-</option>
                 <?php 
				//  $category = $this->db->get_where('category', ['IsActive' => 1])->result();
                //     if ($category) {
                //         foreach ($category as $row) { ?>
                         <option value="<?= $row->category_id ?>"><?= $row->category_name ?></option>
                 <?php  
				//    }
                //     }
                    ?> 
             </select> -->
         </div>
         <div class="col-sm-4 mb-2" id="subDiv" style="display:none">
             <label for="sub_category" class="title">Sub Categoty</label>
             <select name="sub_category" id="sub_category" class="form-control searchselect" style="width:100%" required></select>
         </div>
         <div class="col-sm-4 mb-2">
             <label for="unit" class="title">Unit</label>
             <input autocomplete="off" name="unit" id="unit" class="form-control" type="text" placeholder="Unit (e.g. Kg, Pc,Gm,M ,Cm,Km,Ft Etc.)">
         </div>
         <!-- <div class="col-sm-4 mb-2">
             <label for="brand" class="title">Brand</label>
             <span class="mr-1" title="Add new Brand" onclick="showBrandModel()"><i class="fa fa-lg fa-plus-circle text-primary"></i></span>
             <select name="brand" id="brand" class="form-control searchselect" style="width:100%" required>
                 <option value="">-Select-</option>
                 <?php
				//   $brand = $this->db->get('grade')->result();
                //     if ($brand) {
                //         foreach ($brand as $row) { ?>
                //          <option value="<?= $row->grade_id ?>"><?= $row->name ?></option>
                 <?php 
				    // }
                    // }
                    ?>
             </select>
         </div> -->
         <div class="col-sm-4 mb-2">
             <label for="sale_price" class="title">Price</label>
             <input autocomplete="off" name="sale_price" id="sale_price" class="form-control floatNumber" type="text" placeholder="Price">
         </div>
         <div class="col-sm-4 mb-2">
             <label for="discount_type" class="title">Discount type <span class="text-danger">*</span></label>
             <select name="discount_type" id="discount_type" class="form-control" required>
                 <option value="">-select-</option>
                 <option value="amount">Amount(Rs)</option>
                 <option value="percent">Percent(%)</option>
             </select>
         </div>
         <div class="col-sm-4 mb-2">
             <label for="discount" class="title">Discount</label>
             <input autocomplete="off" name="discount" id="discount" class="form-control floatNumber" type="text" placeholder="Discount" value="0" disabled>
         </div>
         <div class="col-sm-4 mb-2">
             <label for="current_stock" class="title">Current Stock</label>
             <input autocomplete="off" name="current_stock" id="current_stock" class="form-control" type="number" placeholder="Current Stock">
         </div>
         <div class="col-sm-4 mb-2">
             <label for="gst" class="title">GST</label>
             <select name="gst" id="gst" class="form-control">
                 <option value="">-Select-</option>
                 <option value="0">0%</option>
                 <option value="5">5%</option>
                 <option value="12">12%</option>
                 <option value="18">18%</option>
                 <option value="28">28%</option>
             </select>
         </div>
     </div>
     <div class="modal-footer">
         <button type="button" class="btn btn-secondary" id="prodClose" onclick="closeModel()" data-dismiss="modal">Close</button>
         <button type="submit" class="btn btn-primary" id="prodSave">Save</button>
     </div>
 </form>

 <script>
     $(document).ready(function() {
         $('.searchselect').select2({
             dropdownParent: "#exampleModal"
         });
     });

     $('#category').change(function() {
         category_id = $(this).val();
         $.ajax({
             url: '<?= base_url('get-sub-cat/'); ?>' + category_id,
             success: function(data) {
                 data = JSON.parse(data);
                 if (data.status == true) {
                     $('#subDiv').show();
                     $('#sub_category').html(data.data)
                 } else {
                     Swal.fire({
                         position: 'top-end',
                         icon: 'error',
                         title: data.msg,
                         showConfirmButton: false,
                         timer: 2000
                     })
                 }
             }
         });
     });

     $('.floatNumber').keypress(function(eve) {
         if ((eve.which != 46 || $(this).val().indexOf('.') != -1) && (eve.which < 48 || eve.which > 57) || (eve.which == 46 && $(this).caret().start == 0)) {
             eve.preventDefault();
         }

         // this part is when left part of number is deleted and leaves a . in the leftmost position. For example, 33.25, then 33 is deleted
         $('.floatNumber').keyup(function(eve) {
             if ($(this).val().indexOf('.') == 0) {
                 $(this).val($(this).val().substring(1));
             }
         });
     });

     $('#discount_type').change(function() {
         $('#discount').attr('disabled', false);
         if ($(this).val() == 'percent') {
             $('#discount').attr('maxlength', 2)
         }
         if ($(this).val() == '') {
             $('#discount').attr('disabled', true);
         }
     });

     $('#gst').blur(function() {
         $("#prodClose").focus()
     });


     $("#prodClose").on("keypress", function(e) {
         if (e.keyCode == 13) {
             closeModel();
             $("#exampleModal").modal("hide");
         }
     });
     $("#prodSave").on("keypress", function(e) {
         if (e.keyCode == 13) {
             $("#addProductForm").submit();
         }
     });


     $("#prodClose").on("focus", function(e) {
         $("#prodClose").css({
             "box-shadow": "0 0 0 0.2rem rgba(0,123,255,.5)"
         });
     });
     $("#prodSave").on("focus", function(e) {
         $("#prodSave").css({
             "box-shadow": "0 0 0 0.2rem rgba(0,123,255,.5)"
         });
     });
     $("#prodClose").on("blur", function(e) {
         $("#prodClose").css({
             "box-shadow": "none"
         });
     });
     $("#prodSave").on("blur", function(e) {
         $("#prodSave").css({
             "box-shadow": "none"
         });
     });
 </script>
