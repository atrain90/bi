<?php $r = 0;
$c       = 0;
$rowNb   = 0; ?>
<thead>
<tr>
    <?php
    foreach ($headers as $header) {
        ?>
        <th class="<?php echo esc_attr('dtr' . $r . ' dtc' . $c); ?>"><?php echo esc_attr($header); ?></th>
        <?php $c ++;
    }
    ?>
</tr>
</thead>
<tbody>
<?php foreach ($result as $row) {
    $r ++;
    $c = 0;
    $rowNb ++;
    if ($rowNb <= (int) $style->table->freeze_row) { ?>
        <tr class=" row<?php echo esc_attr($rowNb); ?>">
        <?php
    } else { ?>
        <tr class="droptable_none row<?php echo esc_attr($rowNb); ?>">
        <?php
    } ?>
    <?php
    foreach ($row as $cell) {
        ?>
        <td data-dtr="<?php echo esc_attr($r); ?>" data-dtc="<?php echo esc_attr($c); ?>"
            class="<?php echo esc_attr('dtr' . $r . ' dtc' . $c); ?>"><?php echo esc_attr($cell); ?></td>
        <?php $c ++;
    }
    ?>
    </tr>
<?php } ?>
</tbody>
