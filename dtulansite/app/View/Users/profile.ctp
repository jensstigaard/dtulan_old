<h2><?php echo $user['User']['name']; ?></h2>
<h3>General data</h3>
<div>Email: <?php echo $user['User']['email']; ?></div>
<div>Gamertag: <?php echo $user['User']['gamertag']; ?></div>
<div>Type: <?php echo $user['User']['type']; ?></div>
<div>ID-number: <?php echo $user['User']['id_number']; ?></div>
<div>Balance: <?php echo @$user['User']['balance']; ?></div>