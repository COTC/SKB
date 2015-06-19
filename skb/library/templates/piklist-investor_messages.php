<tr>
	<th><button type="button" class="btn-link" data-toggle="collapse" href="#collapse<?php echo $i;?>" aria-expanded="false" aria-controls="collapse<?php echo $i;?>"><span class="dashicons dashicons-plus-alt"></span></button></th>
	<th scope="row"><?php echo $data['message_date'];?></th>
	<td><?php echo $data['message_investment'];?></td>
	<td><?php echo $data['message_investment_entity'];?></td>
	<td><?php echo $data['message_subject'];?></td>
	<td><?php echo $data['message_status'];?></td>
</tr>
<tr class="collapse" id="collapse<?php echo $i;?>">
	<td></td>
	<td colspan="4"><?php echo $data['investor_message'];?></td>
</tr>