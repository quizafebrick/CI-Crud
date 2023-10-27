<div class="px-5 mt-5">
	<h1 class="text-center text-decoration-underline"><?= $title; ?></h1>

	<div class="container mt-5 mx-5 px-5 mb-5">
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
					<div class="mt-3 d-flex gap-3 mx-3 pb-3">
						<div>
							<button type="button" class="btn btn-danger" id="batchDelete">Batch Delete</button>
						</div>
						<div>
							<button type="button" class="btn btn-success" id="batchIsStudent">Batch Is Student</button>
						</div>
						<div>
							<button type="button" class="btn btn-info" id="batchCreateInfo">Create Random Information</button>
						</div>
					</div>
					<div class="mt-3">
						<?php $this->load->view('pages/components/table.php') ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php $this->load->view('pages/components/script-function.php') ?>
