<?php
namespace Teknix\NAV;

require_once('./NAV.class.php');
?>

<style>
.table {
    border:1px solid #eee;
}
.table td {
    padding:2px;
    border-bottom:1px solid #eee;
}
.table tr:hover {
    background:rgba(0,0,0,0.1);
}
</style>
<h1>NAVs bedriftsundersøkelse - etterspørsel etter arbeidskraft per næring</h1>

<?php
$data = Bedriftsundersokelse::getInstance();

echo '<h1>Antall treff før filter: '.$data->count().'</h1>';
if(isset($_GET['filter'])) {
    $data->filterByYear($_GET['filter']);
    echo '<h1>Antall treff etter filter: '.$data->count().'</h1>';
}

if($data->count() > 0) {
    ?>
    <table class="table">
        <tr>
            <th>År</th>
            <th>Næring</th>
            <th>Mangel på arbeidskraft<br>Antall Personer</th>
            <th>95% konfidens-intervall for<br>est. mangel, nedre grense</th>
            <th>95% konfidens-intervall for<br>est. mangel, øvre grense</th>
            <th>% bedrifter med<br>alvorlige rekrutteringsproblemer</th>
        </tr>
    <?php foreach($data->results() as $data) { ?>
        <tr>
            <td><?php echo $data['År']; ?></td>
            <td><?php echo $data['Næring ']; ?></td>
            <td><?php echo $data['Estimert mangel på arbeidskraft i antall personer']; ?></td>
            <td><?php echo $data['95 % konfidens-intervall for estimert mangel , nedre grense']; ?></td>
            <td><?php echo $data['95 % konfidens-intervall for estimert mangel , øvre grense']; ?></td>
            <td><?php echo $data['Prosentvis andel bedrifter med alvorlige rekrutteringsproblemer']; ?></td>
        </tr>
    <?php }
}
elseif($data->error()) {
    echo '<p>En feil har oppstått</p>';
}
else {
    echo '<p>Ingen treff</p>';
}
