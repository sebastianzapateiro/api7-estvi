
<?php
/**
 * @file
 * TPL file for the custom contact information module.
 */
?>

<?php if (!empty($datos_api)): ?>
    <table>
    <th>userId</th>
        <th>id</th>
        <th>title</th>
        <th>body</th>
    <?php foreach ($datos_api as $value): ?>

    <tr>
        <td><?=$value['userId'];?></td>
        <td><?=$value['id'];?></td>
        <td><?=$value['title'];?></td>
        <td><?=$value['body'];?></td>
    </tr>
 
 
    <?php endforeach;?>
</table>
<?php endif;?>