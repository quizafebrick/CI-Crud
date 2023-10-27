<script>
	$(document).ready(function () {
		const table = $("#personalInfoTable").DataTable({
			scrollX: true,
			columnDefs: [
				{ targets: [0, 1, 6, 7], orderable: false }, 
				{ targets: [2, 3, 4, 5], sortable: true },
			],
			ajax: {
				url: "<?= site_url('PersonalInformation/getInfos') ?>",
				type: "GET"
			},
			processing: true,
			serverSide: true,
			order: [[2, 'asc'], [3, 'asc'], [4, 'asc'], [5, 'asc']], 
		});

		$("#editForm").hide();

		$('#saveData').click(function (event) {
			event.preventDefault();

			var fullName = $('#fullname').val().trim();
			var age = $('#age').val().trim();
			var gender = $('#gender').val();
			var address = $('#address').val().trim();

			if (fullName === "") {
				Swal.fire({
					icon: "error",
					title: "Error",
					text: "Fullname cannot be empty.",
				});

				return;
			}

			if (age === "") {
				Swal.fire({
					icon: "error",
					title: "Error",
					text: "Age cannot be empty.",
				});

				return;
			}

			if (gender === null) {
				Swal.fire({
					icon: "error",
					title: "Error",
					text: "Gender cannot be empty.",
				});

				return;
			}
			
			if (address === "") {
				Swal.fire({
					icon: "error",
					title: "Error",
					text: "Address cannot be empty.",
				});

				return;
			}
			
			$.ajax({
				type: 'POST',
				url: '<?= site_url('PersonalInformation/store') ?>',
				data: $('#addDataForm').serialize(),
				dataType: 'json',
				success: function (response) {
					Swal.fire({
						icon: 'success',
						title: 'Success',
						text: "Saved Successfully!",
					});

					$('#fullname').val("");
					$('#age').val("");
					$('#gender').val("");
					$('#address').val("");

					table.ajax.reload(function() {
						table.order([2, 'asc']).draw();
					});
				},
				error: function (xhr, status, error) {
					Swal.fire({
						icon: 'error',
						title: 'Error',
						text: 'An error occurred while processing your request.',
					});
				}
			});
		});

		$("#batchCreateInfo").click(function() {
			$.ajax({
				url: "<?php echo site_url('PersonalInformation/createRandomInfo'); ?>",
				type: "POST",
				dataType: "json",
				success: function(data) {
					Swal.fire({
						icon: 'success',
						title: 'Generated Data',
						text: 'Data has been generated and Saved!',
						showConfirmButton: false,
						timer: 1500
					});

					table.ajax.reload(function() {
						table.order([2, 'asc']).draw();
					});

					$("#checkboxStudentID").prop('checked', false);
				},
				error: function() {
					Swal.fire({
						icon: 'error',
						title: 'Oops...',
						text: 'An error occurred!',
					});
				}
			});
		});

		$(document).on("click", "#editBtn", function (event) {
			event.preventDefault();
			$("#addForm").hide();

			$("#editForm").show();

			const idValue = $(this).data("id");
			const nameValue = $(this).data("fullname");
			const ageValue = $(this).data("age");
			const genderValue = $(this).data("gender");
			const addressValue = $(this).data("address");

			$("#edit_fullname").val(nameValue);
			$("#edit_age").val(ageValue);
			$("#edit_gender").val(genderValue);
			$("#edit_address").val(addressValue);
			$("#studentID").val(idValue);
		});

		$('#updateData').click(function (event) {
			event.preventDefault();
			
			$.ajax({
				type: 'POST',
				url: '<?= site_url('PersonalInformation/update') ?>',
				data: $('#editDataForm').serialize(),
				dataType: 'json',
				success: function (response) {
					Swal.fire({
						icon: 'success',
						title: 'Success',
						text: "Updated Successfully!",
					});

					table.ajax.reload(function() {
						table.order([2, 'asc']).draw();
					});
				},
				error: function (xhr, status, error) {
					Swal.fire({
						icon: 'error',
						title: 'Error',
						text: 'An error occurred while processing your request.',
					});
				}
			});
		});

		$('#personalInfoTable').on('change', 'input[name="isStudent"]', function () {
			var checkbox = $(this);
			var isChecked = checkbox.is(':checked');
			var studentID = $(this).data("id");

			var isStudentValue = isChecked ? 1 : 0;

			if (isStudentValue === 1) {
				Swal.fire({
					icon: 'success',
					title: 'Success',
					text: "Updated as Student Successfully!",
				});
			}

			if (isStudentValue === 0) {
				Swal.fire({
					icon: 'success',
					title: 'Success',
					text: "Updated as a Not Student Successfully!",
				});
			}

			$.ajax({
				type: 'POST',
				url: '<?= site_url('PersonalInformation/updateIsStudent') ?>',
				data: {
					id: studentID,
					isStudent: isStudentValue,
				},
				dataType: 'json',
				success: function (response) {
					table.ajax.reload(function() {
						table.order([2, 'asc']).draw();
					});
				},
				error: function (xhr, status, error) {
					if (xhr.status === 400) {
						Swal.fire({
							icon: 'error',
							title: 'Error',
							text: 'An error occurred while processing your request.',
						});
					}
				},
			});
		});

		$('#batchIsStudent').on('click', function () {
			var selectedIDs = [];

			$('input[name="checkboxStudentID"]:checked').each(function () {
				selectedIDs.push($(this).data('id'));
			});

			if (selectedIDs.length > 0) {
				Swal.fire({
					title: 'Are you sure?',
					text: "You want to make these/this as student(s)!",
					icon: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#198754',
					cancelButtonColor: '#c81e1e',
					confirmButtonText: 'Yes, I am sure!'
				}).then((result) => {
					if (result.isConfirmed) {
						$.ajax({
							type: 'POST',
							url: '<?= site_url('PersonalInformation/updateIsStudentBatch') ?>',
							data: {
								id: selectedIDs,
							},
							dataType: 'json',
							success: function (response) {
								Swal.fire({
									icon: 'success',
									title: 'Success',
									text: "Updated Batch as Student Successfully!",
								});

								table.ajax.reload(function() {
									table.order([2, 'asc']).draw();
								});
							},
							error: function (xhr, status, error) {
								if (xhr.status === 400) {
									Swal.fire({
										icon: 'error',
										title: 'Error',
										text: 'An error occurred while processing your request.',
									});
								}
							},
						});
					}
				});
			} else {
				Swal.fire({
					icon: "error",
					title: "Error",
					text: "No information(s) selected for batch making a student.",

				});

				return;
			}
		});

		$(document).on("click", "#goBack", function () {
			location.reload();
		});

		$(document).on("click", "#deleteBtn", function (event) {
			event.preventDefault();

			const studentID = $(this).data("id");

			Swal.fire({
				title: 'Are you sure?',
				text: "You won't be able to revert this Information!",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#1e429f',
				cancelButtonColor: '#c81e1e',
				confirmButtonText: 'Yes, delete it!'
			}).then((result) => {
				if (result.isConfirmed) {
					$.ajax({
						type: "POST",
						url: '<?= site_url('PersonalInformation/destroy') ?>',
						data: {
							id: studentID
						},
						success: function (response) {
							Swal.fire({
								icon: "success",
								title: "Success",
								text: "Info deleted successfully.",
							}).then(function () {
								location.reload();
							});
						},
						error: function (error) {
							Swal.fire({
								icon: "error",
								title: "Error",
								text: "Failed to delete the Info.",
							});
						},
					});
				}
			});
		});

		$('#checkboxDeleteAll').change(function() {
			$('input[name="checkboxStudentID"][data-is_student="0"]').prop('checked', $(this).prop('checked'));
		});

		$('#batchDelete').click(function () {
			var selectedIDs = [];

			$('input[name="checkboxStudentID"]:checked').each(function() {
				selectedIDs.push($(this).data('id'));
			});

			if (selectedIDs.length > 0) {
				Swal.fire({
					title: 'Are you sure?',
					text: "You won't be able to revert this Information(s)!",
					icon: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#1e429f',
					cancelButtonColor: '#c81e1e',
					confirmButtonText: 'Yes, delete it!'
				}).then((result) => {
					if (result.isConfirmed) {
						$.ajax({
							type: 'POST',
							url: '<?= site_url('PersonalInformation/destroyBatch') ?>',
							data: { 
								id: selectedIDs 
							}, 
							dataType: 'json',
							success: function(response) {
								Swal.fire({
									icon: 'success',
									title: 'Success',
									text: "Batch Deleted Successfully!",
								}).then(() => {
									location.reload();
								});
							},
							error: function() {
								Swal.fire({
									icon: "error",
									title: "Error",
									text: "An error occurred while processing your request.",
								});
							}
						});	
					}	
				});
			} else {
				Swal.fire({
					icon: "error",
					title: "Error",
					text: "No information(s) selected for batch deletion.",

				});

				return;
			}
		});
	});
</script>
