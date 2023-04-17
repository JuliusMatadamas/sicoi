<figure class="highcharts-figure">
    <div id="container-columns"></div>
</figure>

<script>
    Highcharts.chart('container-columns', {
        chart: {
            type: 'column'
        },
        title: {
            align: 'left',
            text: '% de ventas por técnicos'
        },
        subtitle: {
            align: 'left',
            text: 'Clic en la columna para ver la productividad del técnico.'
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
                text: 'Porcentaje total de productividad'
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
                    format: '{point.y:.1f}%'
                }
            }
        },

        tooltip: {
            headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> of total<br/>'
        },

        series: [
            {
                name: 'Tecnicos',
                colorByPoint: true,
                data: [<?php
                    $tecnicos = "{";
                    foreach ($data["tecnicos"] as $tecnico) {
                        $tecnicos .= "name:'" . $tecnico["tecnico"] . "',y:" . $tecnico["porcentaje"] . ",drilldown:'id_" . $tecnico["id_tecnico_asignado"] . "'},{";
                    }
                    echo substr($tecnicos, 0, -2);
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
                $series = "{";
                foreach ($data["tecnicos"] as $tecnico) {
                    $series .= "name:'" . $tecnico["tecnico"] . "',id:'id_" . $tecnico["id_tecnico_asignado"] . "',data:[[";
                    foreach ($tecnico["detalle"] as $detalle) {
                        $series .= "'" . $detalle["estado_de_visita"] . "'," . $detalle["porcentaje"] . "],[";
                    }
                    $series = substr($series, 0, -2);
                    $series .= "]},{";
                }
                echo substr($series, 0, -2);
                ?>]
        }
    });
</script>