// const a = [
//   "",
//   "One ",
//   "Two ",
//   "Three ",
//   "Four ",
//   "Five ",
//   "Six ",
//   "Seven ",
//   "Eight ",
//   "Nine ",
//   "Ten ",
//   "Eleven ",
//   "Twelve ",
//   "Thirteen ",
//   "Fourteen ",
//   "Fifteen ",
//   "Sixteen ",
//   "Seventeen ",
//   "Eighteen ",
//   "Nineteen ",
// ];
// const b = [
//   "",
//   "",
//   "Twenty",
//   "Thirty",
//   "Forty",
//   "Fifty",
//   "Sixty",
//   "Seventy",
//   "Eighty",
//   "Ninety",
// ];

// (function ($) {
//   "use strict";
//   jQuery(document).on("ready", function () {
//     //setTimeout(function() { $('.alert').alert('close') }, 3000);
//     $(".alert")
//       .fadeTo(3000, 500)
//       .slideUp(500, function () {
//         $(".alert").slideUp(500);
//       });

//     $(".searchselect").select2({
//       width: "element",
//     }); 

//     // Navbar active
//     var url = window.location;
//     var menuItem = $(".sidemenu .navbar-nav a, .top-menu .navbar-nav a").filter(
//       function () {
//         return this.href == url;
//       }
//     );
//     menuItem.addClass("active");

//     // Side Menu Hide Show JS
//     $(".burger-menu").on("click", function () {
//       $(".burger-menu").toggleClass("toggle-menu");
//       // $(".navbar-brand").toggleClass("navbar-logo");
//       $(".sidemenu-area").toggleClass("sidemenu-toggle");
//       $(".sidemenu").toggleClass("hide-nav-title");
//       $(".main-content").toggleClass("hide-sidemenu");
//     });

//     // Burger menu click show toggle x class
//     $(".burger-menu").on("click", function () {
//       $(".burger-menu").toggleClass("x");
//     });

//     // Tooltip JS
//     $('[data-toggle="tooltip"]').tooltip();

//     // Feather Icon Js
//     feather.replace();

//     // FAQ Accordion Js
//     $(".accordion")
//       .find(".accordion-title")
//       .on("click", function () {
//         // Adds Active Class
//         $(this).toggleClass("active");
//         // Expand or Collapse This Panel
//         $(this).next().slideToggle("fast");
//         // Hide The Other Panels
//         $(".accordion-content").not($(this).next()).slideUp("fast");
//         // Removes Active Class From Other Titles
//         $(".accordion-title").not($(this)).removeClass("active");
//       });

//     // Example starter JavaScript for disabling form submissions if there are invalid fields
//     (function () {
//       "use strict";
//       window.addEventListener(
//         "load",
//         function () {
//           // Fetch all the forms we want to apply custom Bootstrap validation styles to
//           var forms = document.getElementsByClassName("needs-validation");
//           // Loop over them and prevent submission
//           var validation = Array.prototype.filter.call(forms, function (form) {
//             form.addEventListener(
//               "submit",
//               function (event) {
//                 if (form.checkValidity() === false) {
//                   event.preventDefault();
//                   event.stopPropagation();
//                 }
//                 form.classList.add("was-validated");
//               },
//               false
//             );
//           });
//         },
//         false
//       );
//     })();

//     // Back To Top Button JS
//     $("body").append(
//       '<div id="toTop"><i class="lni lni-chevron-up"></i></div>'
//     );
//     $(window).scroll(function () {
//       if ($(this).scrollTop() != 0) {
//         $("#toTop").fadeIn();
//       } else {
//         $("#toTop").fadeOut();
//       }
//     });
//     $("#toTop").click(function () {
//       $("html, body").animate({ scrollTop: 0 }, 650);
//       return false;
//     });

//     // Gallery Viewer JS
//     var console = window.console || { log: function () {} };
//     var $images = $(".gallery-content");
//     var $toggles = $(".gallery-toggles");
//     var $buttons = $(".gallery-buttons");
//     var options = {
//       // inline: true,
//       url: "data-original",
//       ready: function (e) {
//         console.log(e.type);
//       },
//       show: function (e) {
//         console.log(e.type);
//       },
//       shown: function (e) {
//         console.log(e.type);
//       },
//       hide: function (e) {
//         console.log(e.type);
//       },
//       hidden: function (e) {
//         console.log(e.type);
//       },
//       view: function (e) {
//         console.log(e.type);
//       },
//       viewed: function (e) {
//         console.log(e.type);
//       },
//     };

//     function toggleButtons(mode) {
//       if (/modal|inline|none/.test(mode)) {
//         $buttons
//           .find("button[data-enable]")
//           .prop("disabled", true)
//           .filter('[data-enable*="' + mode + '"]')
//           .prop("disabled", false);
//       }
//     }
//     $images
//       .on({
//         ready: function (e) {
//           console.log(e.type);
//         },
//         show: function (e) {
//           console.log(e.type);
//         },
//         shown: function (e) {
//           console.log(e.type);
//         },
//         hide: function (e) {
//           console.log(e.type);
//         },
//         hidden: function (e) {
//           console.log(e.type);
//         },
//         view: function (e) {
//           console.log(e.type);
//         },
//         viewed: function (e) {
//           console.log(e.type);
//         },
//       })
//       .viewer(options);
//     toggleButtons(options.inline ? "inline" : "modal");
//     $toggles.on("change", "input", function () {
//       var $input = $(this);
//       var name = $input.attr("name");
//       options[name] =
//         name === "inline" ? $input.data("value") : $input.prop("checked");
//       $images.viewer("destroy").viewer(options);
//       toggleButtons(options.inline ? "inline" : "modal");
//     });
//     $buttons.on("click", "button", function () {
//       var data = $(this).data();
//       var args = data.arguments || [];
//       if (data.method) {
//         if (data.target) {
//           $images.viewer(data.method, $(data.target).val());
//         } else {
//           $images.viewer(data.method, args[0], args[1]);
//         }
//         switch (data.method) {
//           case "scaleX":
//           case "scaleY":
//             args[0] = -args[0];
//             break;
//           case "destroy":
//             toggleButtons("none");
//             break;
//         }
//       }
//     });

//     // Left Sidemenu BG Color Switcher
//     //document.getElementById('BGPrimary').onclick = switchPrimary;
//     // document.getElementById('BGSuccess').onclick = switchSuccess;
//     // document.getElementById('BGSecondary').onclick = switchSecondary;
//     // document.getElementById('BGPurple').onclick = switchPurple;
//     // document.getElementById('BGDanger').onclick = switchDanger;
//     // document.getElementById('BGWarning').onclick = switchWarning;
//     // document.getElementById('BGWarning').onclick = switchWarning;
//     // document.getElementById('BGPurpleIndigo').onclick = switchPurpleIndigo;
//     // document.getElementById('BGPink').onclick = switchPink;
//     // document.getElementById('BGIndigo').onclick = switchIndigo;
//     // document.getElementById('BGnNightBlue').onclick = switchNightBlue;
//     // document.getElementById('BGGray').onclick = switchGray;
//     // document.getElementById('BGGrayBlue').onclick = switchGrayBlue;
//     // document.getElementById('BGGreen').onclick = switchGreen;
//     // document.getElementById('BGDeepPurple').onclick = switchDeepPurple;

//     function switchPrimary() {
//       document.getElementsByTagName("body")[0].className =
//         "sidemenu-bg-primary";
//     }

//     function switchSuccess() {
//       document.getElementsByTagName("body")[0].className =
//         "sidemenu-bg-success";
//     }

//     function switchSecondary() {
//       document.getElementsByTagName("body")[0].className =
//         "sidemenu-bg-secondary";
//     }

//     function switchPurple() {
//       document.getElementsByTagName("body")[0].className = "sidemenu-bg-purple";
//     }

//     function switchDanger() {
//       document.getElementsByTagName("body")[0].className = "sidemenu-bg-danger";
//     }

//     function switchWarning() {
//       document.getElementsByTagName("body")[0].className =
//         "sidemenu-bg-warning";
//     }

//     function switchPurpleIndigo() {
//       document.getElementsByTagName("body")[0].className =
//         "sidemenu-bg-purple-indigo";
//     }

//     function switchPink() {
//       document.getElementsByTagName("body")[0].className = "sidemenu-bg-pink";
//     }

//     function switchIndigo() {
//       document.getElementsByTagName("body")[0].className = "sidemenu-bg-indigo";
//     }

//     function switchNightBlue() {
//       document.getElementsByTagName("body")[0].className =
//         "sidemenu-bg-nightblue";
//     }

//     function switchGray() {
//       document.getElementsByTagName("body")[0].className = "sidemenu-bg-gray";
//     }

//     function switchGrayBlue() {
//       document.getElementsByTagName("body")[0].className =
//         "sidemenu-bg-gray-blue";
//     }

//     function switchGreen() {
//       document.getElementsByTagName("body")[0].className = "sidemenu-bg-green";
//     }

//     function switchDeepPurple() {
//       document.getElementsByTagName("body")[0].className =
//         "sidemenu-bg-deep-purple";
//     }

//     // Folded Menu
//     $(".folded-menu").on("click", function () {
//       $(".main-content").toggleClass("hide-sidemenu");
//       $(".sidemenu-area").toggleClass("sidemenu-toggle");
//       $(".sidemenu").toggleClass("hide-nav-title");
//     });

//     // Folded Menu
//     $(".card-shadow").on("click", function () {
//       $(".card").toggleClass("hide-card-shadow");
//       $(".stats-card").toggleClass("hide-card-shadow");
//       $(".stats-card-one, .stats-card-two, .stats-card-three").toggleClass(
//         "hide-card-shadow"
//       );
//     });
//   });

//   // Preloader JS
//   jQuery(window).on("load", function () {
//     $(".preloader").fadeOut();
//   });
// })(jQuery);

// function IsNumeric(evt) {
//   evt = evt ? evt : window.event;
//   var charCode = evt.which ? evt.which : evt.keyCode;
//   if (charCode > 31 && (charCode < 48 || charCode > 57)) {
//     return false;
//   }
//   return true;
// }

// function deleteRow(url) {
//   Swal.fire({
//     title: "Are you sure?",
//     text: "You won't be able to revert this!",
//     icon: "warning",
//     showCancelButton: true,
//     confirmButtonColor: "#3085d6",
//     cancelButtonColor: "#d33",
//     confirmButtonText: "Yes, delete it!",
//   }).then((result) => {
//     if (result.isConfirmed) {
//       removeRow(url);
//     } else {
//       Swal.fire({
//         position: "top-end",
//         icon: "info",
//         title: "Cancelled",
//         showConfirmButton: false,
//         timer: 2000,
//       });
//     }
//   });
// }

// function removeRow(url) {
//   $.ajax({
//     type: "get",
//     url: url,
//     success: function (success) {
//       success = JSON.parse(success);
//       if (success.status == true) {
//         Swal.fire({
//           position: "top-end",
//           icon: "success",
//           title: success.msg,
//           showConfirmButton: false,
//           timer: 2000,
//         });
//         setTimeout(function () {
//           window.location.reload();
//         }, 2000);
//       } else {
//         Swal.fire({
//           position: "top-end",
//           icon: "error",
//           title: success.msg,
//           showConfirmButton: false,
//           timer: 2000,
//         });
//       }
//     },
//   });
// }

// const closeModel = () => {
//   $(".searchselect").select2();
// };

// $(".toUpper").keyup(function () {
//   this.value = this.value.toUpperCase();
// });

// $("#exampleModal").on("hide.bs.modal", function () {
//   closeModel();
// });
// $("#exampleModal").on("hide.bs.modal", function () {
//   closeModel();
// });

// const prepareCustomerDropdown = (customers) => {
//   console.log(customers);
//   let customerOption =
//     '<option class="dropdown-item" value="">Select One</option>';
//   customers.map(function (customer) {
//     customerOption +=
//       '<option class="dropdown-item" value="' +
//       customer.party_id +
//       '">' +
//       customer.name +
//       "(" +
//       customer.city +
//       ")" +
//       "</option>";
//   });
//   $("#customerId").html(customerOption);
// };

// function showAddCustomerDialoag() {
//   $("#exampleModal .modal-dialog .modal-body").load(baseurl + "load-party");
//   $("#exampleModalLabel").html("Add Party");
//   $("#exampleModal").modal("show");
//   $(".searchselect").select2({ dropdownParent: "#exampleModal" });
// }

// // function addProduct() {
// //   $("#exampleModal .modal-dialog .modal-body").load(baseurl + "load-product");
// //   $("#exampleModalLabel").html("Product");
// //   $("#exampleModal").modal("show");
// //   $(".searchselect").select2({ dropdownParent: "#exampleModal" });
// // }

// function showBrandModel(url) {
//   $("#brandModal .modal-dialog .modal-body").load(baseurl + "load-brand");
//   $("#brandModal").modal("show");
// }

// $("#banner_image").change(function (e) {
//   for (var i = 0; i < e.originalEvent.srcElement.files.length; i++) {
//     var file = e.originalEvent.srcElement.files[i];
//     var img = document.createElement("img");
//     var reader = new FileReader();
//     reader.onloadend = function () {
//       img.src = reader.result;
//       img.style =
//         "width:100px; height:100px; padding:4px; border:1px solid #ddd;border-radius:4px; background-color: #eee;";
//     };
//     reader.readAsDataURL(file);
//     $("#showImg").html(img);
//   }
// });

// function RemoveLastDirectoryPartOf(the_url) {
//   var the_arr = the_url.split("/");
//   the_arr.pop();
//   return the_arr.join("/");
// }

// function onBrandFormSubmitHandler(e, url) {
//   e.preventDefault();
//   var form = $("#addBrandForm")[0];
//   var formData = new FormData(form);
//   $.ajax({
//     type: "POST",
//     enctype: "multipart/form-data",
//     url: url,
//     data: formData,
//     processData: false,
//     contentType: false,
//     cache: false,
//     timeout: 600000,
//     success: function (brandList) {
//       $(".searchselect").select2();
//       brandList = JSON.parse(brandList);
//       if (brandList.status == true) {
//         prepareBrandDropdown();
//         Swal.fire({
//           type: "success",
//           title: "Brand is created successfully.",
//           icon: "success",
//           position: "top-end",
//           showConfirmButton: false,
//           timer: 2000,
//         });
//         $("#brand").trigger("change");
//         $("#brandModal").modal("hide");
//       } else {
//         $("#BrandMsg").show();
//         $("#BrandMsg").addClass("alert");
//         $("#BrandMsg").addClass("alert-danger");
//         $("#BrandMsg").html(brandList.msg);
//         setTimeout(function () {
//           $("#BrandMsg").hide();
//         }, 3000);
//       }
//     },
//     error: function (jqXHR, exception) {
//       $("#exampleModal").modal("hide");
//       $("#loader").addClass("d-none");
//       var msg = "";
//       if (jqXHR.status === 0) {
//         msg = "Not connect.\n Verify Network.";
//       } else if (jqXHR.status == 403) {
//         msg = "Permission denied. [403]";
//       } else if (jqXHR.status == 404) {
//         msg = "Requested page not found. [404]";
//       } else if (jqXHR.status == 406) {
//         msg = jqXHR.responseText;
//       } else if (jqXHR.status == 500) {
//         msg = "Internal Server Error [500].";
//       } else if (exception === "parsererror") {
//         msg = "Requested JSON parse failed.";
//       } else if (exception === "timeout") {
//         msg = "Time out error.";
//       } else if (exception === "abort") {
//         msg = "Ajax request aborted.";
//       } else {
//         msg = "Uncaught Error.\n";
//         console.error(jqXHR.responseText);
//       }
//       Swal.fire({
//         position: "top-end",
//         icon: "error",
//         title: msg,
//         showConfirmButton: false,
//         timer: 2000,
//       })
//     },
//   });
// }

// const prepareBrandDropdown = () => {
//   $.ajax({
//     type: "get",
//     url: baseurl + "/get-brand",
//     success: function (success) {
//       success = JSON.parse(success);
//       if (success.status == true) {
//         $("#brand").html(success.data);
//       } else {
//         Swal.fire({
//           position: "top-end",
//           icon: "error",
//           title: "Brand not found.",
//           showConfirmButton: false,
//           timer: 2000,
//         });
//       }
//     },
//   });
// };

// $(".floatNumber").keypress(function (eve) {
//   if (
//     ((eve.which != 46 || $(this).val().indexOf(".") != -1) &&
//       (eve.which < 48 || eve.which > 57)) ||
//     (eve.which == 46 && $(this).caret().start == 0)
//   ) {
//     eve.preventDefault();
//   }

//   // this part is when left part of number is deleted and leaves a . in the leftmost position. For example, 33.25, then 33 is deleted
//   $(".floatNumber").keyup(function (eve) {
//     if ($(this).val().indexOf(".") == 0) {
//       $(this).val($(this).val().substring(1));
//     }
//   });
// });

// function reinitialize() {
//   $(".floatNumber").keypress(function (eve) {
//     if (
//       ((eve.which != 46 || $(this).val().indexOf(".") != -1) &&
//         (eve.whic h< 48 || eve.which > 57)) ||
//       (eve.which == 46 && $(this).caret().start == 0)
//     ) {
//       eve.preventDefault();
//     }

//     // this part is when left part of number is deleted and leaves a . in the leftmost position. For example, 33.25, then 33 is deleted
//     $(".floatNumber").keyup(function (eve) {
//       if ($(this).val().indexOf(".") == 0) {
//         $(this).val($(this).val().substring(1));
//       }
//     });
//   });
// }

// $("#prodClose").on("keypress", function (e) {
//   if (e.keyCode == 13) {
//     closeModel();
//     $("#exampleModal").modal("hide");
//   }
// });
// $("#prodSave").on("keypress", function (e) {
//   if (e.keyCode == 13) {
//     $("#addProductForm").submit();
//   }
// });
// $("#custClose").on("keypress", function (e) {
//   if (e.keyCode == 13) {
//     closeModel();
//     $("#exampleModal").modal("hide");
//   }
// });
// $("#custSave").on("keypress", function (e) {
//   if (e.keyCode == 13) {
//     $("#addUserForm").submit();
//   }
// });
// $("#brndClose").on("keypress", function (e) {
//   if (e.keyCode == 13) {
//     closeModel();
//     $("#exampleModal1").modal("hide");
//   }
// });
// $("#brndSave").on("keypress", function (e) {
//   if (e.keyCode == 13) {
//     $("#addBrandForm").submit();
//   }
// });

// $("#custClose").on("focus", function (e) {
//   $("#custClose").css({ "box-shadow": "0 0 0 0.2rem rgba(0,123,255,.5)" });
// });
// $("#custSave").on("focus", function (e) {
//   $("#custSave").css({ "box-shadow": "0 0 0 0.2rem rgba(0,123,255,.5)" });
// });
// $("#custClose").on("blur", function (e) {
//   $("#custClose").css({ "box-shadow": "none" });
// });
// $("#custSave").on("blur", function (e) {
//   $("#custSave").css({ "box-shadow": "none" });
// });
// $("#prodClose").on("focus", function (e) {
//   $("#prodClose").css({ "box-shadow": "0 0 0 0.2rem rgba(0,123,255,.5)" });
// });
// $("#prodSave").on("focus", function (e) {
//   $("#prodSave").css({ "box-shadow": "0 0 0 0.2rem rgba(0,123,255,.5)" });
// });
// $("#prodClose").on("blur", function (e) {
//   $("#prodClose").css({ "box-shadow": "none" });
// });
// $("#prodSave").on("blur", function (e) {
//   $("#prodSave").css({ "box-shadow": "none" });
// });
// $("#brndClose").on("focus", function (e) {
//   $("#brndClose").css({ "box-shadow": "0 0 0 0.2rem rgba(0,123,255,.5)" });
// });
// $("#brndSave").on("focus", function (e) {
//   $("#brndSave").css({ "box-shadow": "0 0 0 0.2rem rgba(0,123,255,.5)" });
// });
// $("#brndClose").on("blur", function (e) {
//   $("#brndClose").css({ "box-shadow": "none" });
// });
// $("#brndSave").on("blur", function (e) {
//   $("#brndSave").css({ "box-shadow": "none" });
// });

// function onProductFormSubmitHandler(e) {
//   e.preventDefault();
//   var form = $("#addProductForm")[0];
//   var url = $("#addProductForm").attr("action");
//   var data = new FormData(form);
//   $.ajax({
//     type: "POST",
//     enctype: "multipart/form-data",
//     url: url,
//     data: data,
//     processData: false,
//     contentType: false,
//     cache: false,
//     timeout: 600000,
//     success: function (productList) {
//       $(".searchselect").select2();
//       productList = JSON.parse(productList);
//       if (productList.status == true) {
//         prepareCustomerDropdown(productList.data)
//         getRow = $("#dataTable tbody tr").length;
//         if (
//           $("#product" + getRow).val() == "" ||
//           $("#product" + getRow).val() == null
//         ) {
//           $("#tr" + getRow).remove();
//         }
//         prepareProductsDropdown(productList.data);
//         Swal.fire({
//           type: "success",
//           title: productList.msg,
//           icon: "success",
//           position: "top-end",
//           showConfirmButton: false,
//           timer: 2000,
//         });
//         $("#customerId").trigger("change");
//         $("#exampleModal").modal("hide");
//       } else {
//         $("#productMsg").html(productList.msg);
//         $("#productMsg").addClass("alert alert-danger");
//         $("#productMsg").show();
//         setTimeout(function () {
//           $("#productMsg").hide();
//         }, 3000);
//       }
//     },
//     error: function (jqXHR, exception) {
//       $("#exampleModal").modal("hide");
//       $("#loader").addClass("d-none");
//       var msg = "";
//       if (jqXHR.status === 0) {
//         msg = "Not connect.\n Verify Network.";
//       } else if (jqXHR.status == 403) {
//         msg = "Permission denied. [403]";
//       } else if (jqXHR.status == 404) {
//         msg = "Requested page not found. [404]";
//       } else if (jqXHR.status == 406) {
//         msg = jqXHR.responseText;
//       } else if (jqXHR.status == 500) {
//         msg = "Internal Server Error [500].";
//       } else if (exception === "parsererror") {
//         msg = "Requested JSON parse failed.";
//       } else if (exception === "timeout") {
//         msg = "Time out error.";
//       } else if (exception === "abort") {
//         msg = "Ajax request aborted.";
//       } else {
//         msg = "Uncaught Error.\n";
//         console.error(jqXHR.responseText);
//       }
//       Swal.fire({
//         position: "top-end",
//         icon: "error",
//         title: msg,
//         showConfirmButton: false,
//         timer: 2000,
//       });
//     },
//   });
// }

// function showTextBox() {
//   $("#customerId").attr("disabled", true);
//   $("#customerId").attr("required", false);
//   $("#customerId").val("").trigger("change");
//   $("#customerIdDiv").hide();
//   $("#customerNameDiv").show();
//   $("#customerName").attr("disabled", false);
//   $("#customerName").attr("required", true);
//   $(".addPatys").hide();
//   $(".addText").hide();
//   $(".addDdl").show();
// }

// function showDDLBox() {
//   $("#customerId").attr("disabled", false);
//   $("#customerId").attr("required", true);
//   $("#customerName").val("");
//   $("#customerIdDiv").show();
//   $("#customerNameDiv").hide();
//   $("#customerName").attr("disabled", true);
//   $("#customerName").attr("required", false);
//   $(".addPatys").show();
//   $(".addText").show();
//   $(".addDdl").hide();
// }

// const checkStock = (e, rowNumber) => {
//   const qty = e.target.value;
//   var currentStock = $("#stock" + rowNumber).val();
//   if (currentStock < parseInt(qty)) {
//     //alert('Current available stock is :' + currentStock);
//     Swal.fire({
//       position: "top-end",
//       icon: "warning",
//       title: "Current available stock is :" + currentStock,
//       showConfirmButton: false,
//       timer: 2000,
//     });
//     return false;
//   }
// };
// const showSerial = (e,rowNumber) => {
//   var qty = e.target.value;
//   $('#serialdiv').html('');
//   $('#serialfooter').html('<button type="button" class="btn btn-primary" onclick="save_serial('+rowNumber+')">Save</button>');
//   for(var s=1;s<=qty;s++)
//   {
//     var val = $('#serial' + rowNumber + s).val();
//     if(val==undefined)
//     {
//      $('#serialdiv').append('<label>Serial No '+ s +' </label><input type="text" id="serial'+rowNumber+''+ s +'" class="form-control" name="product[' + rowNumber + '][item_serialno][]" style="margin-bootom:5px;" />');  
//     }
//     else
//     {
//       $('#serialdiv').append('<label>Serial No '+ s +' </label><input value="'+val+'" type="text" id="serial'+rowNumber+''+ s +'" class="form-control" name="product[' + rowNumber + '][item_serialno][]" style="margin-bootom:5px;" />');  
//     }
//   }
//   $('#serialModal').modal('show');
// }
// const editshowSerial = (e,rowNumber) => {
//   var qty = e.target.value;
//   $('#serialdiv').html('');
//   $('#serialfooter').html('<button type="button" class="btn btn-primary" onclick="save_serial('+rowNumber+')">Save</button>');
//   for(var s=0;s<qty;s++)
//   {
//     var val = $('#oldserial' + rowNumber + s).val();
//     var srno =s+1;
//     if(val==undefined)
//     {
//        $('#serialdiv').append('<label>Serial No '+ srno +' </label><input type="text" id="serial'+rowNumber+''+ s +'" class="form-control" name="product[' + rowNumber + '][item_serialno][]" style="margin-bootom:5px;" />'); 
//     }
//     else
//     {
//       $('#serialdiv').append('<label>Serial No '+ srno +' </label><input value="'+val+'" type="text" id="serial'+rowNumber+''+ s +'" class="form-control" name="product[' + rowNumber + '][item_serialno][]" style="margin-bootom:5px;" />');  
//     }
    
//   }
//   $('#serialModal').modal('show');
// }

// function onUserFormSubmitHandler(e) {
//   e.preventDefault();
//   $(".searchselect").select2();
//   var url = $("#addUserForm").attr("action");
//   var form = $("#addUserForm")[0];
//   var data = new FormData(form);
//   $.ajax({
//     type: "POST",
//     enctype: "multipart/form-data",
//     url: url,
//     data: data,
//     processData: false,
//     contentType: false,
//     cache: false,
//     timeout: 600000,
//     success: function (customerList) {
//       $(".searchselect").select2();
//       customerList = JSON.parse(customerList);
//       if (customerList.status == true) {
//         prepareCustomerDropdown(customerList.data);
//         Swal.fire({
//           type: "success",
//           title: "User is created successfully.",
//           icon: "success",
//         });
//         $("#customerId").trigger("change");
//         $("#exampleModal").modal("hide");
//       } else {
//         $("#partyMsg").show();
//         $("#partyMsg").addClass("alert");
//         $("#partyMsg").addClass("alert-danger");
//         $("#partyMsg").html(customerList.msg);
//         setTimeout(function () {
//           $("#partyMsg").hide();
//         }, 3000);
//       }
//     },
//     error: function (jqXHR, exception) {
//       $("#exampleModal").modal("hide");
//       $("#loader").addClass("d-none");
//       var msg = "";
//       if (jqXHR.status === 0) {
//         msg = "Not connect.\n Verify Network.";
//       } else if (jqXHR.status == 403) {
//         msg = "Permission denied. [403]";
//       } else if (jqXHR.status == 404) {
//         msg = "Requested page not found. [404]";
//       } else if (jqXHR.status == 406) {
//         msg = jqXHR.responseText;
//       } else if (jqXHR.status == 500) {
//         msg = "Internal Server Error [500].";
//       } else if (exception === "parsererror") {
//         msg = "Requested JSON parse failed.";
//       } else if (exception === "timeout") {
//         msg = "Time out error.";
//       } else if (exception === "abort") {
//         msg = "Ajax request aborted.";
//       } else {
//         msg = "Uncaught Error.\n";
//         console.error(jqXHR.responseText);
//       }
//       Swal.fire({
//         position: "top-end",
//         icon: "error",
//         title: msg,
//         showConfirmButton: false,
//         timer: 2000,
//       });
//     },
//   });
// }

// const numberToWords = (num) => {
//   if ((num = num.toString()).length > 9) return "overflow";
//   let n = ("000000000" + num)
//     .substr(-9)
//     .match(/^(\d{2})(\d{2})(\d{2})(\d{1})(\d{2})$/);
//   if (!n) return;
//   var str = "";
//   str +=
//     n[1] != 0
//       ? (a[Number(n[1])] || b[n[1][0]] + " " + a[n[1][1]]) + "Crore "
//       : "";
//   str +=
//     n[2] != 0
//       ? (a[Number(n[2])] || b[n[2][0]] + " " + a[n[2][1]]) + "Lakh "
//       : "";
//   str +=
//     n[3] != 0
//       ? (a[Number(n[3])] || b[n[3][0]] + " " + a[n[3][1]]) + "Thousand "
//       : "";
//   str +=
//     n[4] != 0
//       ? (a[Number(n[4])] || b[n[4][0]] + " " + a[n[4][1]]) + "Hundred "
//       : "";
//   str +=
//     n[5] != 0
//       ? (str != "" ? "And " : "") +
//         (a[Number(n[5])] || b[n[5][0]] + " " + a[n[5][1]]) +
//         "Only "
//       : "";
//   return str;
// };

// // function getProducts() {
// //   $.ajax({
// //     url: baseurl + "index.php/products",
// //     method: "GET",
// //     success: function (products) {
// //       var products = JSON.parse(products);
// //       if (products.status == true) {
// //         prepareProductsDropdown(products.data);
// //         if ("" != null && "" !== "") {
// //           $("#invoiceNumber").val("");
// //           $("#invoiceNumber").trigger("change");
// //         }
// //         $("td .select2-container").css("width", "150px");
// //         console.log("=====Prepare======");
// //       }
// //     },
// //     error: function (jqXHR, exception) {
// //       var msg = "";
// //       if (jqXHR.status === 0) {
// //         msg = "Not connect.\n Verify Network.";
// //       } else if (jqXHR.status == 401) {
// //         msg = "Unauthorized User. [" + jqXHR.status + "]";
// //         window.location.href = "/gst/signout";
// //       } else if (jqXHR.status == 403) {
// //         msg = "Permissio n denied. [403]";
// //       } else if (jqXHR.status == 404) {
// //         msg = "Requested page not found. [404]";
// //       } else if (jqXHR.status == 406) {
// //         msg = jqXHR.responseText;
// //       } else if (jqXHR.status == 500) {
// //         msg = "Internal Server Error [500].";
// //       } else if (exception === "parsererror") {
// //         msg = "Requested JSON parse failed.";
// //       } else if (exception === "timeout") {
// //         msg = "Time out error.";
// //       } else if (exception === "abort") {
// //         msg = "Ajax request aborted.";
// //       } else {
// //         msg = "Uncaught Error.\n";
// //         console.error(jqXHR.responseText);
// //       }
// //       Swal.fire({
// //         position: "top-end",
// //         icon: "error",
// //         title: msg,
// //         showConfirmButton: false,
// //         timer: 2000,
// //       });
// //     },
// //   });
// // }

// const setSelectedCustomerDetails = (customer) => {
//   stateCode = customer.state_code ? customer.state_code : 23;
//   sgstPer = 0;
//   cgstPer = 0;
//   igstPer = 0;
//   $("#gstNo").val(customer != null ? customer.gst : "");
//   $("#gstType").val(customer != null ? customer.gstType : "");
//   $("#customerAddress").val(
//     customer != null ? customer.address_1 + customer.address_2 : ""
//   );
//   $("#customerCity").val(customer != null ? customer.city : "");
//   $("#customerMobile").val(customer != null ? customer.contact : "");
//   $("#customerEmail").val(customer != null ? customer.email : "");
//   $("#customerBalance").val(customer != null ? customer.credit_limit : "");
// };

// const onProductFocusChange = (e, rowNo) => {
//   if (e.target.value.length < 1) {
//     const row = document.getElementById("tr" + rowNo);
//     row.parentNode.removeChild(row);
//     document.getElementById("expenses").focus();
//     //Add A Blank Row if table is empty
//     let rowCount = $("#dataTable tbody tr").length;
//     if (rowCount < 1) {
//       onAddNewRowHandler();
//       document.getElementById("itemCode1").focus();
//     }
//   }
// };

// const onDiscountHandler = (e, rowNumber,type="Sale") => {
//   let discount = e.target.value;
//   var cd = $("#cd" + rowNumber).val();
//   let qty = $("#qty" + rowNumber).val();
//   let rate = calculateRate(rowNumber,type);
//   const taxPercent = $("#tax" + rowNumber).val();
//   discount_type = $("#discounttype" + rowNumber).val();
//   discountcd_type = $("#discounttypecd" + rowNumber).val();
//   let discountAmt = "";
//   if (discount_type == "Rs") {
//     if (discount > rate) {
//       Swal.fire({
//         position: "top-end",
//         title: "Invalid Discount",
//         text: "Discount can't be greater than Amount",
//         icon: "warning",
//         confirmButtonColor: "#3085d6",
//         showConfirmButton: false,
//         timer: 2000,
//       });
//       discount = 0;
//       $("#discount" + rowNumber).val("0");
//       $("#discount" + rowNumber).trigger("change");
//     }
//     discountAmt = rate - discount;
//   } else {
//     if (discount > 100) {
//       Swal.fire({
//         position: "top-end",
//         title: "Invalid Discount",
//         text: "Discount can't be greater than 100%",
//         icon: "warning",
//         showConfirmButton: false,
//         timer: 2000,
//       });
//       discount = 0;
//       $("#discount" + rowNumber).val("0");
//       $("#discount" + rowNumber).trigger("change");
//     }
//     discountAmt = (rate * (100 - discount)) / 100;
//   }
//   if (discountcd_type == "%") {
//     discountAmt = (discountAmt * (100 - cd)) / 100;
//   } else if (discountcd_type == "Rs") {
//     discountAmt = discountAmt - cd;
//   }
//   let amount = discountAmt * qty;
//   const taxAmount = calculateTaxAmount(amount, taxPercent);
//   $("#amount" + rowNumber).val(amount.toFixed(2));
//   $("#taxAmount" + rowNumber).val(taxAmount.toFixed(2));
//   $("#netAmount" + rowNumber).val(
//     (parseFloat(taxAmount) + parseFloat(amount)).toFixed(2)
//   );
//   calculateGST(taxPercent, amount, rowNumber);
//   calculatePrice(rowNumber,type);
//   manipulateRHSData();
//   $("#expenses").val(0);
// };

// const calculatePrice = (rowNumber,type) => {
//   var tax = $("#tax" + rowNumber).val();
//   var cd = $("#cd" + rowNumber).val();
//   discount_type = $("#discounttype" + rowNumber).val();
//   discountcd_type = $("#discounttypecd" + rowNumber).val();
//   discount = $("#discount" + rowNumber).val();
//   let rate = calculateRate(rowNumber,type);
//   if (discount_type == "%") {
//     rate = (rate * (100 - discount)) / 100;
//   } else if (discount_type == "Rs") {
//     rate = rate - discount;
//   }
//   if (discountcd_type == "%") {
//     rate = (rate * (100 - cd)) / 100;
//   } else if (discountcd_type == "Rs") {
//     rate = rate - cd;
//   }
//   var price = "";
//   if (tax == "" || tax == null || tax == 0) {
//     price = rate;
//   } else {
//     price = ((100 + parseFloat(tax)) * rate) / 100;
//   }
//   $("#price" + rowNumber).val(price.toFixed(2));
// };

// const calculateTaxAmount = (amount, taxPercent) => {
//   let taxAmount = (amount * taxPercent) / 100;
//   return taxAmount;
// };

// const onRateChangeHandler = (e, rowNumber, type ='Sale') => {
//   let rate = e.target.value;
//   var cd = $("#cd" + rowNumber).val();
//   const taxPercent = $("#tax" + rowNumber).val();
//   discount_type = $("#discounttype" + rowNumber).val();
//   discountcd_type = $("#discounttypecd" + rowNumber).val();
//   if(type == 'Purchase'){
//     rate = rate;
//   }else{
//     rate = rate-((rate * taxPercent) / (100+parseInt(taxPercent)));
//   }
//   $("#base_price" + rowNumber).val(rate);
//   discount = $("#discount" + rowNumber).val();
//   if (discount_type == "%") {
//     rate = (rate * (100 - discount)) / 100;
//   } else if (discount_type == "Rs") {
//     rate = rate - discount;
//   }
//   if (discountcd_type == "%") {
//     rate = (rate * (100 - cd)) / 100;
//   } else if (discountcd_type == "Rs") {
//     rate = rate - cd;
//   }
//   amount = rate * $("#qty" + rowNumber).val();
//   $("#amount" + rowNumber).val(amount);
//   const taxAmount = calculateTaxAmount(amount, taxPercent);
//   $("#taxAmount" + rowNumber).val(taxAmount.toFixed(2));
//   $("#netAmount" + rowNumber).val(
//     (parseFloat(taxAmount) + parseFloat(amount)).toFixed(2)
//   );
//   $("#discount" + rowNumber).trigger("change");
//   manipulateRHSData();
//   $("#expenses").val(0);
//   calculatePrice(rowNumber,type);
//   calculateGST(taxPercent, amount, rowNumber);
// };

// const onQuantityChangeHandler = (e, rowNumber,type = "Sale") => {
//   const qty = e.target.value;
//   var cd = $("#cd" + rowNumber).val();
//   discount = $("#discount" + rowNumber).val();
//   discount_type = $("#discounttype" + rowNumber).val();
//   discountcd_type = $("#discounttypecd" + rowNumber).val();
//   let rate = calculateRate(rowNumber,type);
//   if (discount_type == "%") {
//     rate = (rate * (100 - discount)) / 100;
//   } else if (discount_type == "Rs") {
//     rate = rate - discount;
//   }
//   if (discountcd_type == "%") {
//     rate = (rate * (100 - cd)) / 100;
//   } else if (discountcd_type == "Rs") {
//     rate = rate - cd;
//   }
//   let amount = rate * qty;
//   $("#amount" + rowNumber).val(amount);
//   const taxPercent = $("#tax" + rowNumber).val();
//   const taxAmount = calculateTaxAmount(amount, taxPercent);
//   $("#taxAmount" + rowNumber).val(taxAmount.toFixed(2));
//   $("#netAmount" + rowNumber).val(
//     (parseFloat(taxAmount) + parseFloat(amount)).toFixed(2)
//   );
//   $("#discount" + rowNumber).trigger("change");
//   manipulateRHSData();
//   calculateGST(taxPercent, amount, rowNumber);
//   calculatePrice(rowNumber,type);
// };

// function onPriceChangeHandler() {
//   return;
// }

// const onCDHandler = (e, rowNumber,type = "Sale") => {
//   let cd = e.target.value;
//   let discount = $("#discount" + rowNumber).val();
//   var taxPercent = $("#tax" + rowNumber).val();
//   let qty = $("#qty" + rowNumber).val();
//   let rate = calculateRate(rowNumber,type);
//   discount_type = $("#discounttype" + rowNumber).val();
//   discountcd_type = $("#discounttypecd" + rowNumber).val();
//   if (discount_type == "%") {
//     rate = (rate * (100 - discount)) / 100;
//   } else if (discount_type == "Rs") {
//     rate = rate - discount;
//   }
//   if (discountcd_type == "%") {
//     if (cd > 100) {
//       Swal.fire({
//         position: "top-end",
//         title: "Invalid CD",
//         text: "CD can't be greater than 100%",
//         icon: "warning",
//         confirmButtonColor: "#3085d6",
//         showConfirmButton: false,
//         timer: 2000,
//       });
//       cd = 0;
//       $("#cd" + rowNumber).val("0");
//       $("#cd" + rowNumber).trigger("change");
//     }
//   } else if (discountcd_type == "Rs") {
//     if (cd > rate) {
//       Swal.fire({
//         position: "top-end",
//         title: "Invalid CD",
//         text: "CD can't be greater than Rate",
//         icon: "warning",
//         confirmButtonColor: "#3085d6",
//         showConfirmButton: false,
//         timer: 2000,
//       });
//       cd =0;
//       $("#cd" + rowNumber).val("0");
//       $("#cd" + rowNumber).trigger("change");
//     }
//   }

//   if (discountcd_type == "%") {
//     rate = (rate * (100 - cd)) / 100;
//   } else if (discountcd_type == "Rs") {
//     rate = rate - cd;
//   }
//   amount = qty * rate;
//   const taxAmount = calculateTaxAmount(amount, taxPercent);
//   $("#amount" + rowNumber).val(amount.toFixed(2));
//   $("#taxAmount" + rowNumber).val(taxAmount.toFixed(2));
//   $("#netAmount" + rowNumber).val(
//     (parseFloat(taxAmount) + parseFloat(amount)).toFixed(2)
//   );
//   calculateGST(taxPercent, amount, rowNumber);
//   calculatePrice(rowNumber,type);
//   manipulateRHSData();
//   $("#expenses").val(0);
// };

// const manipulateRHSData = () => {
//   let taxableAmount = 0;
//   let totalTax = 0;
//   let totalValue = 0;
//   let roundOff = 0;
//   let total = 0;
//   let totalsgst = 0;
//   let totalcgst = 0;
//   let totaligst = 0;
//   const rows = $("#dataTable").children("tbody").children("tr");
//   rows.map(function (row) {
//     let row1 = $("#dataTable").children("tbody").children("tr")[row];
//     const trId = row1.getAttribute("id");
//     const rowNumber = trId.slice(2, trId.length);
//     const taxAmount = $("#taxAmount" + rowNumber).val();
//     const netAmount = $("#netAmount" + rowNumber).val();
//     const sgst = $("#sgst" + rowNumber).val();
//     const cgst = $("#cgst" + rowNumber).val();
//     const igst = $("#igst" + rowNumber).val();
//     const amount = $("#amount" + rowNumber).val();
//     if (
//       taxAmount != null &&
//       taxAmount != "" &&
//       netAmount != null &&
//       netAmount != ""
//     ) {
//       taxableAmount += parseFloat(amount);
//       totalTax += parseFloat(taxAmount);
//       totalValue += parseFloat(netAmount);
//     }
//     totalsgst += parseFloat(sgst);
//     totalcgst += parseFloat(cgst);
//     totaligst += parseFloat(igst);
//   });
//   roundOff += parseFloat("." + totalValue.toString().split(".")[1]);
//   $("#taxableAmount").val(taxableAmount.toFixed(2));
//   $("#totalTax").val(totalTax.toFixed(2));
//   $("#totalValue").val(totalValue.toFixed(2));
//   $("#roundOff").val(
//     roundOff == "" || isNaN(roundOff) ? 0 : roundOff.toFixed(2)
//   );
//   $("#total").val(Math.round(totalValue).toFixed(2));
//   $("#amountInWords").val(numberToWords(parseInt(totalValue)));
//   $("#totalsgst").val(totalsgst.toFixed(2));
//   $("#totalcgst").val(totalcgst.toFixed(2));
//   $("#totaligst").val(totaligst.toFixed(2));
// };

// $("#expenses").on("keyup", function (e) {
//   var expenses = $(this).val();
//   var expenses = expenses ? expenses : 0;
//   var taxableAmount = $("#taxableAmount").val();
//   var totalTax = $("#totalTax").val();
//   var totalValue = parseFloat(taxableAmount) + parseFloat(totalTax);
//   totalAmount = parseFloat(expenses) + parseFloat(totalValue);
//   $("#totalValue").val(totalAmount);
//   $("#total").val(parseInt(totalAmount));
//   $("#amountInWords").val(numberToWords(parseInt(totalAmount)));
// });

// //Get Customer From all Customers via customerId
// // const getCustomerDetails = (customerId) => {
// //   $.ajax({
// //     method: "GET",
// //     url: baseurl + "index.php/party/getCustomer/",
// //     data: {
// //       id: customerId,
// //     },
// //     success: function (customer) {
// //       var customer = JSON.parse(customer);
// //       setSelectedCustomerDetails(customer.data);
// //     },
// //     error: function (jqXHR, exception) {
// //       var msg = "";
// //       if (jqXHR.status === 0) {
// //         msg = "Not connect.\n Verify Network.";
// //       } else if (jqXHR.status == 401) {
// //         msg = "Unauthorized User. [" + jqXHR.status + "]";
// //         window.location.href = "/gst/signout";
// //       } else if (jqXHR.status == 403) {
// //         msg = "Permission denied. [403]";
// //       } else if (jqXHR.status == 404) {
// //         msg = "Requested page not found. [404]";
// //       } else if (jqXHR.status == 406) {
// //         msg = jqXHR.responseText;
// //       } else if (jqXHR.status == 500) {
// //         msg = "Internal Server Error [500].";
// //       } else if (exception === "parsererror") {
// //         msg = "Requested JSON parse failed.";
// //       } else if (exception === "timeout") {
// //         msg = "Time out error.";
// //       } else if (exception === "abort") {
// //         msg = "Ajax request aborted.";
// //       } else {
// //         msg = "Uncaught Error.\n";
// //         console.error(jqXHR.responseText);
// //       }
// //       Swal.fire({
// //         position: "top-end",
// //         icon: "error",
// //         title: msg,
// //         showConfirmButton: false,
// //         timer: 2000,
// //       });
// //     },
// //   });
// // };

// //Get Product From all Products via productId
// const onProductSelect = (rowNo, e, type = "Sale") => {
//   const productId = e.target.value;
//   if (productId == "" || productId == null) {
//     onRemoveRowHandler(rowNo);
//   }
//   $('#product_dec'+rowNo).html('<textarea class="form-control" name="product[' + rowNo + '][product_dec]" style="resize:none;height:50px;" placeholder="Enter Description"></textarea>');
//   $.ajax({
//     method: "GET",
//     url: baseurl + "get-product/" + productId,
//     success: function (product) {
//       var product = JSON.parse(product);
//       var product = product.data;
//       $("#product" + rowNo).attr("title", product.name);
//       setSelectedProductDetails(rowNo, product, type);
//       // $('#product' + rowNo).on('select2:select', function(e) {
//       //     document.getElementById("cost" + rowNo).focus()
//       // });
//     },
//     error: function (jqXHR, exception) {
//       var msg = "";
//       if (jqXHR.status === 0) {
//         msg = "Not connect.\n Verify Network.";
//       } else if (jqXHR.status == 401) {
//         msg = "Unauthorized User. [" + jqXHR.status + "]";
//         window.location.href = "/gst/signout";
//       } else if (jqXHR.status == 403) {
//         msg = "Permission denied. [403]";
//       } else if (jqXHR.status == 404) {
//         msg = "Requested page not found. [404]";
//       } else if (jqXHR.status == 406) {
//         msg = jqXHR.responseText;
//       } else if (jqXHR.status == 500) {
//         msg = "Internal Server Error [500].";
//       } else if (exception === "parsererror") {
//         msg = "Requested JSON parse failed.";
//       } else if (exception === "timeout") {
//         msg = "Time out error.";
//       } else if (exception === "abort") {
//         msg = "Ajax request aborted.";
//       } else {
//         msg = "Uncaught Error.\n";
//         console.error(jqXHR.responseText);
//       }
//       Swal.fire({
//         position: "top-end",
//         icon: "error",
//         title: msg,
//         showConfirmButton: false,
//         timer: 2000,
//       });
//     },
//   });
// };

// $(document).on("keypress", function (e) {
//   if (e.which == 13) {
//     e.preventDefault();
//     // Get all focusable elements on the page
//     var $canfocus = $(":focusable");
//     var index = $canfocus.index(document.activeElement) + 1;
//     if (index >= $canfocus.length) index = 0;
//     $canfocus.eq(index).focus();
//   }
//   if (e.keyCode == 39) {
//     $(".move:focus").next().focus();
//   }
//   if (e.keyCode == 37) {
//     $(".move:focus").prev().focus();
//   }
// });

// $(document).on("keyup", function (e) {
//   if (e.keyCode === 121) {
//     // F10
//     $("#btnSubmit").trigger("click");
//   }
// });

// $(document).on(
//   "focus",
//   ".select2-selection.select2-selection--single",
//   function (e) {
//     $(this)
//       .closest(".select2-container")
//       .siblings("select:enabled")
//       .select2("open");
//   }
// );

// // $("#customerId").on("change", function (e) {
// //   getCustomerDetails(e.target.value);
// // });

// const setSelectedProductDetails = (rowNo, product, type) => {
//   var tax =
//     product != null && product.tax != null && product.tax != ""
//       ? product.tax
//       : "0";
//   $("#tax" + rowNo).val(tax);
//   $("#tax" + rowNo).trigger("change");
//   $("#qty" + rowNo).val(1);
//   if (type == "Purchase") {
//     $("#sale_price" + rowNo).val(product.sale_price);
//   } else {
//     $("#productId" + rowNo).val(product.product_id);
//     $("#itemCode" + rowNo).val(product != null ? product.code : "");
//     $("#hsnCode" + rowNo).val(product != null ? product.hsn : "");
//     $("#stock" + rowNo).val(
//       product != null &&
//         product.current_stock != null &&
//         product.current_stock != ""
//         ? product.current_stock
//         : "0"
//     );

//     $("#rate" + rowNo).val(
//       product != null ? parseInt(product.sale_price) : "0"
//     );

//     rate = product.sale_price;
//     rate = rate-((rate * tax) / (100+parseInt(tax)));
//     let amount = rate;
//     $("#base_price" + rowNo).val(rate);
//     $("#qty" + rowNo).trigger("change");
//     $("#discount" + rowNo).val(0);
//     $("#cost" + rowNo).val(parseFloat(product.purchase_price));

//     let cd = $("#cd" + rowNo).val() ? $("#cd" + rowNo).val() : "0";
//     let discount = 0;
//     if (discount_type == "%") {
//       amount = (amount * (100 - discount)) / 100;
//     } else if (discount_type == "Rs") {
//       amount = amount - discount;
//     }
//     if (discountcd_type == "%") {
//       amount = (amount * (100 - cd)) / 100;
//     } else if (discountcd_type == "Rs") {
//       amount = amount - cd;
//     }
//     $("#amount" + rowNo).val(
//       ($("#qty" + rowNo).val() * parseFloat(amount)).toFixed(2)
//     );
//     const taxPercent = $("#tax" + rowNo).val();
//     var price = ((100 + parseFloat(taxPercent)) * parseFloat(amount)) / 100;
//     $("#price" + rowNo).val(Math.round(price));
//     const taxAmount = calculateTaxAmount(amount, taxPercent);
//     $("#taxAmount" + rowNo).val(taxAmount.toFixed(2));
//     $("#netAmount" + rowNo).val(
//       (parseFloat(taxAmount) + parseFloat(amount)).toFixed(2)
//     );
//     calculateGST(tax, amount, rowNo);
//     manipulateRHSData();
//   }
// };

// function calculateGST(tax, amount, rowNo) {
//   var customerId = $("#customerId").val();
//   var customerName = $("#customerName").val();
//   if (customerId == "" || customerId == null) {
//     if (customerName == "" || customerName == null) {
//       Swal.fire({
//         position: "top-end",
//         icon: "warning",
//         title: "Please select customer.",
//         showConfirmButton: false,
//         timer: 2000,
//       });
//       $("#customerId").focus().trigger("change");
//       $("#product" + rowNo).val("");
//       $("#product" + rowNo).select2({
//         selectONclose: true,
//       });
//       $("#productId" + rowNo).val("");
//       $("#itemCode" + rowNo).val("");
//       $("#hsnCode" + rowNo).val("");
//       $("#tax" + rowNo).val("0.00");
//       $("#stock" + rowNo).val("");
//       $("#rate" + rowNo).val("0.00");
//       $("#qty" + rowNo).val("");
//       $("#price" + rowNo).val("0.00");
//       $("#discount" + rowNo).val("0");
//       $("#cost" + rowNo).val("0.00");
//       $("#taxAmount" + rowNo).val("0.00");
//       $("#netAmount" + rowNo).val("0.00");
//       return false;
//     }
//   }
//   sgstPer = 0;
//   cgstPer = 0;
//   igstPer = 0;
//   if (stateCode == mystateCode) {
//     sgstPer = tax / 2;
//     cgstPer = tax / 2;
//   } else {
//     igstPer = tax;
//   }
//   sgst = calculateTaxAmount(amount, sgstPer);
//   cgst = calculateTaxAmount(amount, cgstPer);
//   igst = calculateTaxAmount(amount, igstPer);
//   $("#sgst" + rowNo).val(parseFloat(sgst).toFixed(2));
//   $("#cgst" + rowNo).val(parseFloat(cgst).toFixed(2));
//   $("#igst" + rowNo).val(parseFloat(igst).toFixed(2));
// }

// const onNewRowAdd = (e) => {
//   if (e.keyCode === 13) {
//     onAddNewRowHandler();
//   }
// };

// const onRemoveRowHandler = (rowNo) => {
//   const invoiceProductId = $("#invoiceProductId" + rowNo).val();
//   const invoiceId = $("#id").val();
//   if (invoiceId != null && invoiceProductId != null) {
//     deleteInvoiceProduct(invoiceProductId, invoiceId);
//   }
//   $("#tr" + rowNo).remove();
//   let rowCount = $("#dataTable tbody tr").length + 1;
//   if (rowCount === 1) {
//     onAddNewRowHandler();
//   } else {
//     document.getElementById("expenses").focus();
//   }
//   manipulateRHSData();
// };

// const discounttypeHandler = (e, rowNumber) => {
//   discount_type = e.target.value;
//   $("#discount" + rowNumber).trigger("change");
// };

// const discounttypecdHandler = (e, rowNumber) => {
//   discountcd_type = e.target.value;
//   $("#cd" + rowNumber).trigger("change");
// };

// const calculateRate = (numRow,type)=>{
//   var rate = $("#rate" + numRow).val();
//   var tax = $("#tax" + numRow).val();
//   if(type == "Purchase"){
//     return rate;
//   }else{
//     return rate-((rate * tax) / (100+parseInt(tax)));
//   }
// }


// $(document).keydown(function(e) {
//   // ESCAPE key pressed
//   if (e.keyCode == 27) {
//       window.close();
//       $('#serialModal').modal("hide");
//       $('#consigneediv').hide("slow");
//   }
// });

// $('#consigneestatecode').blur(function(){
// $('#consigneediv').hide("slow");
// });


// $('#getledger_details').change(function() {
//   var ledger_id = $(this).val();
//   ids = [ledger_id];
//   type = $('#transtype').val();
//   dr = '';
//     if(type == "Payment"){
//       dr = ' Cr'
//     }else if(type == "Receipt"){
//       dr = ' Dr'
//     }
//   $(".searchselect").select2();
//   $.ajax({
//     url: baseurl + 'receipt/getaccount_details/' + ledger_id,
//     dataType: "json",
//     success: function(data) {
//       if (data.data != 'null') {
//         html = '';
//         html = '<p style="margin: 2px 0px 0px 0px;font-size: 14px;font-weight: 400;" >Currrent Bal:<b > ' + data.data.current_balance + dr+'</b> </p>';
//         $("#current_bal").html(html);
//         $("#main_acount_blc").val(data.data.current_balance);
//         $("#main_acount_id").val(data.data.current_balance);
//         $(".list_group_id").show();
//         $(".narration").show();
//         let tr = `<tr><td><span>Account</span><div id="selectdiv1"><select id="ledger1" onchange="getdetais(event,1)" name="account[1][acountid]" class="form-control searchselect" required> ${data.html}` +
//           `</select></div><div id="dataview1"  style="color:#000;padding:.25em;border: 1px solid #f2f2f2 !important; border-radius:3px;display:none"></div><input type="hidden" id="currentBalance1" value="0"><span id="currentbal1" style="font-size:13px;"></span></td>` +
//           `<td><span>&nbsp;</span><input readonly type="text" style="text-align: right;" name="account[1][amount]" id="addbalance1" onfocus="focused(event, 1)" onblur="addbalance(event, 1)" class="form-control" value="0" placeholder="Enter Amount"></td></tr>`;
//         $("#tbodyparty").html(tr);
//         $(".searchselect").select2();
//       }
//     }
//   });
// });

// const getdetais = (e, numRow) => {
//   let ledger_id = $('#ledger' + numRow).val();
//   if (ledger_id != '' && ledger_id != null) {
//     if (!ids.includes(ledger_id)) {
//       ids.push(ledger_id);
//     }
//     type = $('#transtype').val();
//     dr = '';
//     if(type == "Payment"){
//       dr = ' Dr'
//     }else if(type == "Receipt"){
//       dr = ' Cr'
//     }
//     $.ajax({
//       url: baseurl+ 'receipt/getaccount_details/' + ledger_id,
//       dataType: "json",
//       success: function(data) {
//         if (data.data != 'null') {
//           $('#currentbal' + numRow).html('Current Bal: ' + data.data.current_balance + dr);
//           $('#currentBalance' + numRow).val(data.data.current_balance);
//           $('#addbalance' + numRow).attr('readonly', false);
//           $("#dataview" + numRow).show();
//           $("#dataview" + numRow).html(data.data.name);
//           $("#selectdiv" + numRow).hide();
//         }
//       }
//     });
//   }
// }

// const addbalance = (e, numRow) => {
//   let balance = $('#addbalance' + numRow).val();
//   currentBalance = $('#currentBalance' + numRow).val();
//   type = $('#transtype').val();
//   if (balance != '' && balance != 'null') {
//     newBal = 0;
//     dr = '';
//     if(type == "Payment"){
//       newBal = parseFloat(balance) +  parseFloat(currentBalance) ;
//       dr = ' Dr'
//     }else if(type == "Receipt"){
//       newBal = parseFloat(currentBalance) - parseFloat(balance) ;
//       dr = ' Cr'
//     }
//     $('#currentbal' + numRow).html('Current Bal: ' + newBal + dr);
//     let rowCount = $("#tbodyparty tr").length;
//     if (numRow == rowCount) {
//       $.ajax({
//         type: "POST",
//         data: {
//           data: ids
//         },
//         dataType: "json",
//         url: baseurl+ 'getledger',
//         success: function(data) {
//           numRow = numRow + 1;
//           if (data.status == true) {
//             let tr = `<tr><td><div id="selectdiv${numRow}"><select id="ledger${numRow}" onchange="getdetais(event,${numRow})" name="account[${numRow}][acountid]" class="form-control searchselect"> ${data.data}` +
//               `</select></div><div id="dataview${numRow}" style="color:#000; padding:.25em;border: 1px solid #f2f2f2 !important; border-radius:3px;display:none"></div><input type="hidden" id="currentBalance${numRow}" value="0"><span id="currentbal${numRow}" style="font-size:13px;"> </span></td>` +
//               `<td><input type="text" style="text-align: right;" name="account[${numRow}][amount]" onfocus="focused(event, ${numRow})" onblur="addbalance(event, ${numRow})" id="addbalance${numRow}" readonly class="form-control" value="0" placeholder="Enter Amount"></td></tr>`;
//             $("#tbodyparty").append(tr);
//             $(".searchselect").select2();
//           }
//         }
//       });
//     }
//   } else {
//     $('#addbalance' + numRow).val(0)
//   }
//   calculate();
// }

// const focused = (event, numRow) => {
//   if ($('#addbalance' + numRow).val() == 0) {
//     $('#addbalance' + numRow).val('');
//   }
// }

// const calculate = () => {
//   main_acount_blc = $('#main_acount_id').val();
//   type = $('#transtype').val();
//   let numRow = $("#tbodyparty tr").length;
//   total = 0;
//   for (var i = 1; i <= numRow; i++) {
//     currentbal = $('#addbalance' + i).val();
//     total += parseFloat(currentbal);
//   }
//   $('#grandtotalamt').val(total);
//   mainbal = 0;
//   dr = '';
//   if(type == "Payment"){
//     mainbal =  parseFloat(main_acount_blc) - parseFloat(total);
//     dr = 'Cr'
//   }else if(type == "Receipt"){
//     mainbal = parseFloat(total) + parseFloat(main_acount_blc);
//     dr = 'Dr'
//   }
//   $('#main_acount_blc').val(mainbal);
//   $('#current_bal').html('Current Bal: ' + mainbal + ' ' +dr);
// }

function showDescription(rowNumber){
	//   var qty = e.target.value;
	console.log("modelpopo")
	  $('#serialModal').modal({
        show: true
    }); 
	}


	
