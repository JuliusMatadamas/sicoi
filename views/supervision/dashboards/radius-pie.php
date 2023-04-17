<figure class="highcharts-figure">
    <div id="container_radius-pie"></div>
</figure>

<script>
    Highcharts.chart('container_radius-pie', {
        chart: {
            type: 'variablepie'
        },
        title: {
            text: 'Status general de visitas a las ventas realizadas.',
            align: 'center'
        },
        tooltip: {
            headerFormat: '',
            pointFormat: '<span style="color:{point.color}">\u25CF</span> <b> {point.name}</b><br/>' +
                'Porcentaje: <b>{point.y} %</b><br/>' +
                'Total: <b>{point.z}</b><br/>'
        },
        series: [{
            minPointSize: 10,
            innerSize: '20%',
            zMin: 0,
            name: 'visitas',
            data: [<?php
                $infoRp = "{";
                foreach ($data["radius-pie"] as $rp) {
                    $infoRp .= "name: '" . $rp["estado_de_visita"] . "', y:" . doubleval($rp["porcentaje"]) . ", z:" . intval($rp["total"]) . "}, {";
                }
                echo substr($infoRp, 0, -3);
                ?>]
        }]
    });
</script>