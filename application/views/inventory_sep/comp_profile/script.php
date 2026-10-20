<script>
	$(function() {
		$("#mrp").on('submit', function(e) {
			e.preventDefault();
			var contactForm = $(this);
			$.ajax({
				url: contactForm.attr('action'),
				type: 'post',
				data: contactForm.serialize(),
				success: function(response) {
					console.log(response);
					if (response.status == 'success') {
						let mrp_id = response.data;
						console.log(mrp_id);
						document.getElementById('mrp_id').value = mrp_id;
						// $("#contactForm").hide();
						// alert("congrates")
					}
					$("#msgs").html(response.message);


				}
			});
		});
	});

	$(function() {
		$("#purchase").on('submit', function(e) {
			e.preventDefault();
			var contactForm = $(this);
			$.ajax({
				url: contactForm.attr('action'),
				type: 'post',
				data: contactForm.serialize(),
				success: function(response) {
					console.log(response);
					if (response.status == 'success') {
						let purchase_id = response.data;
						// alert(purchase_id)
						console.log(purchase_id);
						document.getElementById('purchase_id').value = purchase_id;
						// $("#contactForm").hide();
						// alert("congrates")
					}
					$("#purchase_msg").html(response.message);


				}
			});
		});
	});

	$(function() {
		$("#sell_code").on('submit', function(e) {
			e.preventDefault();
			var contactForm = $(this);
			$.ajax({
				url: contactForm.attr('action'),
				type: 'post',
				data: contactForm.serialize(),
				success: function(response) {
					console.log(response);
					if (response.status == 'success') {
						let sell_id = response.data;
						// alert(purchase_id)
						console.log(sell_id);
						document.getElementById('sell_id').value = sell_id;
						// $("#contactForm").hide();
						// alert("congrates")
					}
					$("#sell_msg").html(response.message);


				}
			});
		});
	});


	$(function() {
		$("#contactForm").on('submit', function(e) {
			e.preventDefault();
			var contactForm = $(this);
			$.ajax({
				url: contactForm.attr('action'),
				type: 'post',
				data: contactForm.serialize(),
				success: function(response) {
					console.log(response);
					if (response.status == 'success') {
						// $("#contactForm").hide();
						// alert("congrates")
					}
					$("#message").html(response.message);


				}
			});
		});
	})
	$(function() {
		$("#credit_note_configpermission").on('submit', function(e) {
			e.preventDefault();
			var contactForm = $(this);
			$.ajax({
				url: contactForm.attr('action'),
				type: 'post',
				data: contactForm.serialize(),
				success: function(response) {
					console.log(response);
					if (response.status == 'success') {
						setTimeout(function() {
							$("#credit_note_config_message").hide();
							alert("Seeting Updated");
							$("#credit_note_config").click();
						}, 1000);

					}
					$("#credit_note_config_message").html(response.message);


				}
			});
		});
	})

	$(function() {
		$("#debit_note_configpermission").on('submit', function(e) {
			e.preventDefault();
			var contactForm = $(this);
			$.ajax({
				url: contactForm.attr('action'),
				type: 'post',
				data: contactForm.serialize(),
				success: function(response) {
					console.log(response);
					if (response.status == 'success') {
						setTimeout(function() {
							$("#debit_note_config_message").hide();
							alert("Seeting Updated");
							$("#debit_note_config").click();
						}, 1000);

					}
					$("#debit_note_config_message").html(response.message);


				}
			});
		});
	})

	$(function() {
		$("#purchase_configpermission").on('submit', function(e) {
			e.preventDefault();
			var contactForm = $(this);
			$.ajax({
				url: contactForm.attr('action'),
				type: 'post',
				data: contactForm.serialize(),
				success: function(response) {
					console.log(response);
					if (response.status == 'success') {
						setTimeout(function() {
							$("#purchase_config_message").hide();
							alert("Seeting Updated");
							$("#purchase_config").click();
						}, 1000);

					}
					$("#a5permission_message").html(response.message);


				}
			});
		});
	})
	$(function() {
		$("#a5permission").on('submit', function(e) {
			e.preventDefault();
			var contactForm = $(this);
			$.ajax({
				url: contactForm.attr('action'),
				type: 'post',
				data: contactForm.serialize(),
				success: function(response) {
					console.log(response);
					if (response.status == 'success') {
						setTimeout(function() {
							$("#a5permission_message").hide();
							alert("Seeting Updated");
							$("#a5invoice").click();
						}, 1000);

					}
					$("#a5permission_message").html(response.message);


				}
			});
		});
	})

	$(function() {
		$("#estimateconfig").on('submit', function(e) {
			e.preventDefault();
			var contactForm = $(this);
			$.ajax({
				url: contactForm.attr('action'),
				type: 'post',
				data: contactForm.serialize(),
				success: function(response) {
					if (response.status == 'success') {
						setTimeout(function() {
							$("#estimateconfig_message").hide();
							alert("Estimate Setting Updated");
							$("#estimateconfig").click();
						}, 1000);

					}
					$("#estimateconfig_message").html(response.message);


				}
			});
		});
	});
        
	$(function() {
		$("#estimate_purchase_config_form").on('submit', function(e) {
			e.preventDefault();
			var contactForm = $(this);
			$.ajax({
				url: contactForm.attr('action'),
				type: 'post',
				data: contactForm.serialize(),
				success: function(response) {
					if (response.status == 'success') {
						setTimeout(function() {
							$("#estimate_purchase_config_message").hide();
							alert("Estimate Setting Updated");
							$("#estimate_purchase_config_form").click();
						}, 1000);

					}
					$("#estimate_purchase_config_message").html(response.message);


				}
			});
		});
	});

    $(function() {
        $("#orderbook_config_form").on('submit', function(e) {
            e.preventDefault();
            var contactForm = $(this);
            $.ajax({
                url     :   contactForm.attr('action'),
                type    :   'POST',
                data    :   contactForm.serialize(),
                success :   function(response) {
                                if (response.status == 'success') {
                                    setTimeout(function() {
                                        $("#orderbook_config_message").hide();
                                        alert("Order Book Setting Updated");
                                        $("#orderbook_config_form").click();
                                    }, 1000);
                                }
                                $("#orderbook_config_message").html(response.message);
                            }
            });
        });
    });

	$('input[tabindex^="2"]');
	$(document).ready(function() {
		// $("#zero").focus();
		// Will only run once the page Document Object Model (DOM) is ready for JavaScript code 
		// Create a jQuery object containing the html element 'input'
		// Create a .not() method to exclude buttons for keypress event
		$(":input:not(:disabled)").not($(":button")).keypress(function(evt) {
			// If the keypress event code is 13 (Enter)
			if (evt.keyCode == 13) {
				// get the attribute type and if the type is not submit
				itype = $(this).prop('type');
				if (itype !== 'submit') {
					currentTabindex = $(this).attr('tabindex');
					if (currentTabindex) {
						nextInput = $('input[tabindex^="' + (parseInt(currentTabindex) + 1) + '"]');
						if (nextInput.length) {
							nextInput.focus();
							return false;
						}
					}
				}
			}
		});
	});

    function ChangePosition() {
        let LogoStatus = $("#logo_status").val();
        if (LogoStatus == 'enable') {
            $("#logo_position").prop("disabled", false);
            $("#logo_position").val("left");
        } else {
            $("#logo_position").prop("disabled", true);
            $("#logo_position").val("");
        }
    }

    function ChangePositionQR() {
        let QRStatus = $("#qr_status").val();
        if (QRStatus == 'enable') {
            $("#qr_position").prop("disabled", false);
            $("#qr_position").val("left");
        } else {
            $("#qr_position").prop("disabled", true);
            $("#qr_position").val("");
        }
    }
</script>
