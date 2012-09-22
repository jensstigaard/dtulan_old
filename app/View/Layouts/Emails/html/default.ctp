<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.View.Layouts.Emails.html
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $title_for_layout;?></title>

	<style type="text/css">
    table {
        border: 2px solid #000;
    }

    table thead tr th {
		padding: 3px;
        text-align: center;
    }

    table thead tr th:first-child {
        text-align: left;
    }

    table thead tr th:last-child {
        border-left: 1px solid #000;
		text-align: right;
    }

    table tbody tr td {
		padding: 3px;
		border-top: 1px solid #000;
        text-align: center;
    }

    table tbody tr td:first-child {
        text-align: left;
    }

    table tbody tr td:last-child {
        text-align: right;
		border-left: 1px solid #000;
    }
</style>

</head>
<body>
	<?php echo $content_for_layout; ?>
</body>
</html>