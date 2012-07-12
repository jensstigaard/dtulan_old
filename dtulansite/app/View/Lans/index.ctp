<h1>Lanz</h1>
<table>
    <tr>
        <th>Id</th>
        <th>Title</th>
        <th>Max Participants</th>
        <th>Max Guests per Student</th>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Published</th>
		<th>Sign Up</th>
    </tr>

 
    <?php foreach ($lans as $lan): ?>
    <tr>
        <td><?php echo $lan['Lan']['id']; ?></td>
        <td><?php echo $this->Html->link($lan['Lan']['title'],
				array('action'=>'edit', $lan['Lan']['id']));?></td> 
        <td><?php echo $lan['Lan']['max_participants']; ?></td>
		<td><?php echo $lan['Lan']['max_guests_per_student']; ?></td>
		<td><?php echo $lan['Lan']['time_start']; ?></td>
		<td><?php echo $lan['Lan']['time_end']; ?></td>
		<td><?php  if($lan['Lan']['published'] == '0'){ echo "Nope"; }else{ echo "Yep";} ?></td>
		<td><?php  if($lan['Lan']['sign_up_open'] == '0'){ echo "Nope"; }else{ echo "Yep";} ?></td>

    </tr>
    <?php endforeach; ?>

</table>
