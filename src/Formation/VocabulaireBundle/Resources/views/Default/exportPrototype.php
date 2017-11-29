<?php
    header('Content-Encoding: UTF-8');
    header("content-type:application/csv;charset=UTF-8");
    header("Content-Disposition: attachment; filename=listePrototype.xls");
?>
<TABLE border=1>
    <TR>
        <TD>Société</TD>
        <TD>Prototype</TD>
        <TD>Date de création</TD>
    </TR>
    <TR>
<?php foreach($prototype_accesss_array as $proto) {?>
            <TD><?php echo $proto->getSociete()->getDescription(); ?></TD>
            <TD><?php echo $proto->getType(); ?></TD>
            <TD><?php echo $proto->getDate()->format('d/m/Y'); ?></TD>
<?php } ?>
    </TR>
</TABLE>