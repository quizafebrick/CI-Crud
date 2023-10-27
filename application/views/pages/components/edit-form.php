<form id="editDataForm">
	<div class="mt-3">
		<label for="fullname" class="form-label">Fullname:</label>
		<input type="text" class="form-control" name="fullname" id="edit_fullname">
	</div>

	<div class="mt-3">
		<label for="age" class="form-label">Age:</label>
		<input type="text" class="form-control" name="age" id="edit_age">
	</div>

	<div class="mt-3">
		<label for="gender" class="form-label">Gender:</label>
		<select name="gender" id="edit_gender" class="form-select">
			<option selected disabled>-- Select Gender --</option>
			<option value="MALE">MALE</option>
			<option value="FEMALE">FEMALE</option>
		</select>
	</div>

	<div class="mb-3">
		<label for="address" class="form-label">Address</label>
		<textarea class="form-control" name="address" id="edit_address" rows="3" style="resize: none;"></textarea>
	</div>

	<div>
		<input type="hidden" name="id" id="studentID">
		<button type="button" class="btn btn-primary w-100" id="updateData">Update</button>
	</div>
</form>

<div>
	<div type="button" class="text-primary text-underline fw-bold fs-6 text-end text-decoration-underline" id="goBack">
		Go back to add info
	</div>
</div>
