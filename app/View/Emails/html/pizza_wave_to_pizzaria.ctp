<style>
    table {
        border: 1;
    }
    
    table thead tr th {
        text-align: center;
    }
    
    table thead tr th:first-child {
        text-align: left;
    }
    
    table thead tr th:last-child {
        text-align: right;
    }
    
    table tbody tr td {
        text-align: center;
    }
    
    table tbody tr td:first-child {
        text-align: left;
    }
    
    table tbody tr td:last-child {
        text-align: right;
    }
</style>
<div>
	<h1>DTU LAN Party - Pizza bestilling</h1>
	<table style="width:100%" >
		<thead>
			<tr>
				<th>Antal</th>
				<th>#</th>
				<th>Navn</th>
				<th>Type</th>
                                <th>Færdig</th>
			</tr>
		</thead>

		<tbody>
			<?php echo $info; ?>
		</tbody>
	</table>
</div>