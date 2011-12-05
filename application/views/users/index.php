<html>
<head><title>������ � ��������</title></head>
<body>
<h1 align="center">USERLIST</h1>
<table align="center" width="100%" border="1">
    <tr>
        <td><h2>Id</h2>
        <td><h2>���</h2>
        <td><h2>Email</h2>
        <td><h2>Delete</h2><tr>
<?php 
$list = $this->mdl_user->getlist();
$size = count($list);?>
<?foreach ($list as $each):?>
    <tr>
    <td> <?=$each['id'];?>
    <td> <?=anchor('index.php/users/show/'.$each['id'], $each['name']);?>
    <td> <?=$each['email'];?>
    <td>    <?=anchor('index.php/users/show/'.$each['id'],'show',array('class' => 'object_crud_links')) ?> |
            <?=anchor('index.php/users/edit/'.$each['id'],'edit',array('class' => 'object_crud_links')) ?> |
            <?=anchor('index.php/users/delete/' . $each['id'],'delete',array('class' => 'object_crud_links'));?>
<?endforeach?>
</table>
<?= anchor('/index.php/users/add', 'Add user') ?>
</body>
</html>