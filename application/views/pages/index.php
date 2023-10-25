<div class="px-5 mt-5">
	<h1 class="text-center text-decoration-underline"><?= $title; ?></h1>

	<div class="container mt-5 mx-5 px-5">
		<div class="row align-items-start gap-3">
			<div class="col border border-1 p-3 shadow rounded-3">
				<div id="addForm">
					<div class="fs-3 fw-semibold">
						Add Info
					</div>
					<?php $this->load->view('pages/components/add-form.php') ?>
				</div>

				<div id="editForm">
					<div class="fs-3 fw-semibold">
						Edit Info
					</div>
					<?php $this->load->view('pages/components/edit-form.php') ?>
				</div>
			</div>
			<div class="col-8 border border-1 shadow p-3 rounded-3">
				<div>
					<div class="fs-3 fw-semibold">
						Personal Information
					</div>
					<div class="mt-3">
						<button type="button" class="btn btn-danger" id="batchDelete">Batch Delete</button>
					</div>
					<div class="mt-3">
						<?php $this->load->view('pages/components/table.php') ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function () {
		const table = $("#personalInfoTable").DataTable({
			scrollX: true,
			columns: [
				{
					data: null, // * USE NULL DATA SOURCE FOR THE NUMBERING COLUMN * //
					orderable: true, // * DISABLE SORTING FOR THIS COLUMN * //
					searchable: false, // * DISABLE SEARCHING FOR THIS COLUMN * //
					render: function (data, type, row, meta) {
						return meta.row + 1;
					},
				},
				{
					data: null,
					sorting: false, 
					render: function (data, type, row, meta) {
						return (
							'<div class="form-check d-flex justify-content-center">' +
								'<input class="form-check-input" type="checkbox" name="checkboxStudentID" id="checkboxStudentID" data-id="' + row.id + '" value="">' +
							'</div>'
						);
					},
				},
				{
					data: "fullname",
					searchable: true,
				},
				{
					data: "age",
					searchable: true,
				},
				{
					data: "gender",
					searchable: true,
				},
				{
					data: "address",
					searchable: true,
				},
				{
					data: null,
					sorting: false,
					render: function (data, type, row) {
						return (
							'<div class="form-check form-switch d-flex justify-content-center">' +
								'<input class="form-check-input p-2" type="checkbox" name="isStudent" id="is_student" data-id="' + row.id + '" value="1" ' + (row.isStudent == 1 ? ' checked' : '') + '>' +
							'</div>'
						);
					}
				},
				{
					data: null,
					sorting: false,
					render: function (data, type, row) {
						// * ADD ACTION BUTTONS OR ANY OTHER CUSTOM RENDERING HERE * //
						return (
							'<button class="btn btn-primary editBtn" id="editBtn" data-fullname="' + row.fullname + '" data-gender="' + row.gender + '" data-address="' + row.address + '" data-age="' + row.age + '" data-id="' + row.id + '">' +
								'<i class="bi bi-pencil-square"></i>' +
							'</button > ' + 

							'<button class="btn btn-danger deleteBtn text-white ml-2" id="deleteBtn" data-id="' + row.id + '">'+    
								'<i class="bi bi-trash3-fill"></i>' +
							'</button > '
						);
					},
				},
			],
			ajax: {
				url: "<?= site_url('PersonalInformation/getInfos') ?>",
				dataSrc: "",
			},
		});
		
		$("#editForm").hide();

		$('#saveData').click(function (event) {
			event.preventDefault();

			var fullName = $('#fullname').val().trim();
			var age = $('#age').val().trim();
			var gender = $('#gender').val().trim();
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

			if (gender === "") {
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

					table.ajax.reload();
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

					table.ajax.reload();
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
					table.ajax.reload();
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
			var isChecked = $(this).prop('checked');

			$('input[name="checkboxStudentID"]').prop('checked', isChecked);
		});

		$('#batchDelete').click(function () {
			var selectedIDs = [];

			$('input[name="checkboxStudentID"]:checked').each(function() {
				selectedIDs.push($(this).data('id'));
			});

			if (selectedIDs.length > 0) {
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
			} else {
				Swal.fire({
					icon: "error",
					title: "Error",
					text: "No checkbox/checkboxes selected for batch deletion.",

				});

				return;
			}
		});
	});
</script>
