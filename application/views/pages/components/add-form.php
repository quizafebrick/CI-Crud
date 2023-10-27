<form id="addDataForm">
	<div class="mt-3">
		<label for="fullname" class="form-label">Fullname:</label>
		<input type="text" class="form-control" name="fullname" id="fullname" onkeyup="this.value = this.value.toUpperCase()">
	</div>

	<div class="mt-3">
		<label for="age" class="form-label">Age:</label>
		<input type="text" class="form-control" name="age" id="age" maxlength="3" onkeypress="return (event.charCode > 47 && event.charCode < 58)">
	</div>

	<div class="mt-3">
		<label for="gender" class="form-label">Gender:</label>
		<select name="gender" id="gender" class="form-select">
			<option selected disabled>-- Select Gender --</option>
			<option value="MALE">MALE</option>
			<option value="FEMALE">FEMALE</option>
		</select>
	</div>

	<div class="mb-3">
		<label for="address" class="form-label">Address</label>
		<textarea class="form-control" name="address" id="address" rows="3" style="resize: none;" onkeyup="this.value = this.value.toUpperCase()"></textarea>
	</div>

	<div>
		<button type="button" id="saveData" class="btn btn-primary w-100">Save</button>
	</div>
</form>
