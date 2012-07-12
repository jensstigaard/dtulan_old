<h1>Unactivated users</h1>
<table>
    <tr>
        <th>Id</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>EMail</th>
        <th>Type</th>
        <th>Id-number</th>
        <th>Creation Date</th>
    </tr>

    
<!-- Here's where we loop through our $posts array, printing out post info -->

    <?php foreach ($registrations as $registration): ?>
    <tr>
        <td><?php echo $registration['Registration']['id']; ?></td>
        <td><?php echo $this->Html->link($registration['Registration']['first_name'],
                array('action' => 'edit', $registration['Registration']['id'])); ?></td>       
        <td><?php echo $registration['Registration']['last_name']?></td>
        <td><?php echo $registration['Registration']['email']?></td>
        <td><?php echo $registration['Registration']['type']?></td>
        <td><?php echo $registration['Registration']['id_number']?></td>
        <td><?php echo $registration['Registration']['creation_time'];?></td>
    </tr>
    <?php endforeach; ?>

</table>
