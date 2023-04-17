<figure class="highcharts-figure">
    <div id="ventas-porTipoEnElAnnio">Cargando...</div>
</figure>

<script>
    Highcharts.chart('ventas-porTipoEnElAnnio', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: `Ventas por tipo, ${year}`,
            align: 'left'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        accessibility: {
            point: {
                valueSuffix: '%'
            }
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                }
            }
        },
        series: [{
            name: '% Del tipo de ventas',
            colorByPoint: true,
            data: [<?php
                $datos = "{name:'";
                $cont = 0;
                foreach ($data["ventas_porTipoEnElannio"] as $venta) {
                    $datos .= $venta["tipo_de_venta"] . "', y:" . intval($venta["total"]);
                    if ($cont === 0) {
                        $datos .= ", sliced: true, selected: true";
                    }
                    $datos .= "}, {name:'";
                    $cont++;
                }
                $datos = substr($datos, 0, -9);
                echo $datos;
                ?>]
        }]
    });
</script>