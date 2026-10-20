
<table class="table table-striped table-bordered table-responsive-sm dataTable" id="example13">
	<thead>
		<tr>
			<th>Name</th>
			<th>HSN</th>
			<th>QTY</th>
			<th>Price</th>

			<!-- <th>UNIT</th> -->

			<!-- <th>Edit</th> -->

		</tr>
	</thead>
	<tbody id="producttbdoy">
		<?php

		if ($result) {
		
			foreach ($result as $key => $result) {
		?>
				<tr id="protr<?= $key ?>" tabindex="<?= -1 ?>" onkeyup="keyUpForProduct(event,<?= $key ?>)" onkeypress="onProductSelect(<?= $result->id ?>)" onkeydown="keyDownForProduct(event,<?= $key ?>)" onclick="onProductSelect(<?= $result->id ?>)">
					<td><?= $result->p_name ?></td>
					<td><?= $result->hsn ?></td>
					<td><?= $result->qty ?></td>
					<td><?= $result->price ?></td>

					<!-- <td><?= $result->mrp ?></td> -->
					<!-- <td><a href="#" onmouseover="productProfileEdit(<?= $key ?>,<?= $result->id ?>)">Edit</a></td> -->

				</tr>
				

		<?php
			}
		}
		?>
	</tbody>


</table>


