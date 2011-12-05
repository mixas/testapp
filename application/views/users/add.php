<h1>Add user</h1>
<?php echo validation_errors(); ?>
<?= form_open('/index.php/users/add') ?>
<table>
    <tr><td>Name: <td><?= form_input('name') ?>
    <tr><td>Email: <td><?= form_input('email') ?>
    <tr><td>Password: <td><?= form_password('password') ?>
</table>
<?= form_submit('','Add') ?>
<?= form_close() ?>
<?= anchor('index.php/users/','Users list') ?>