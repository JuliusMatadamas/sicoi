<figure class="highcharts-figure">
    <div id="container"></div>
    <p class="highcharts-description">
        Categorias que cuentan con productos en inventario disponible para entregar, si alguna categoría o producto no
        se muestra es porque no se tiene en disponibilidad.
    </p>
</figure>

<script>
    <?php
    $weekDays = ['lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado', 'domingo'];
    $months = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
    ?>Highcharts.chart('container', {
        chart: {
            type: 'column'
        },
        title: {
            align: 'left',
            text: 'Inventario disponible al <?php echo $weekDays[date("N") - 1] . " " . date("j") . " de " . $months[date("n") - 1] . " del " . date("Y"); ?>'
        },
        subtitle: {
            align: 'left',
            text: 'Clic en cada columna para ver el detalle de productos en stock.'
        },
        accessibility: {
            announceNewData: {
                enabled: true
            }
        },
        xAxis: {
            type: 'category'
        },
        yAxis: {
            title: {
                text: 'Porcentaje/piezas totales en inventario'
            }

        },
        legend: {
            enabled: false
        },
        plotOptions: {
            series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                    format: '{point.y:.1f}'
                }
            }
        },

        tooltip: {
            headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> del total<br/>'
        },

        series: [
            {
                name: 'Categorias',
                colorByPoint: true,
                data: [<?php
                    $series = "{";
                    foreach ($data as $d) {
                        $series .= "name: '" . $d["categoria"] . "', y: " . $d["y"] . ", drilldown: '" . $d["categoria"] . "'},{";
                    }
                    $series = substr($series, 0, -2);
                    echo $series;
                    ?>]
            }
        ],
        drilldown: {
            breadcrumbs: {
                position: {
                    align: 'right'
                }
            },
            series: [<?php
                $drilldown = "{";
                foreach ($data as $d) {
                    $drilldown .= "name: '" . $d["categoria"] . "', id: '" . $d["categoria"] . "', data: [";
                    foreach ($d["data"] as $i) {
                        $drilldown .= "['" . $i["nombre"] . "'," . $i["total"] . "],";
                    }
                    $drilldown = substr($drilldown, 0, -1);
                    $drilldown .= "]},{";
                }
                $drilldown = substr($drilldown, 0, -2);
                echo $drilldown;
                ?>]
        }
    });
</script>