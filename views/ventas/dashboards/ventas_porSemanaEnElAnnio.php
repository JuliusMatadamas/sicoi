<figure class="highcharts-figure">
    <div id="ventas-porSemanaEnElannio">Cargando...</div>
</figure>

<script>
    const d = new Date();
    const year = d.getFullYear();
    Highcharts.chart('ventas-porSemanaEnElannio', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Ventas por semana en el año'
        },
        xAxis: {
            type: 'category',
            labels: {
                rotation: -45,
                style: {
                    fontSize: '13px',
                    fontFamily: 'Verdana, sans-serif'
                }
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Ventas (cantidad)'
            }
        },
        legend: {
            enabled: false
        },
        tooltip: {
            pointFormat: `Ventas del año ${year}: <b>{point.y:.1f} </b>`
        },
        series: [{
            name: 'Ventas',
            data: [<?php
                $series = "";
                foreach ($data["ventas_porSemanaEnElannio"] as $semana) {
                    $series .= "['";
                    if (intval($semana["semana"]) < 10) {
                        $series .= "0" . $semana["semana"];
                    } else {
                        $series .= $semana["semana"];
                    }
                    $series .= "', ";
                    $series .= intval($semana["total"]);
                    $series .= "],";
                }
                $series = substr($series, 0, -1);
                echo $series;
                ?>],
            dataLabels: {
                enabled: true,
                rotation: -90,
                color: '#FFFFFF',
                align: 'right',
                format: '{point.y:.1f}',
                y: 10,
                style: {
                    fontSize: '13px',
                    fontFamily: 'Verdana, sans-serif'
                }
            }
        }]
    });
</script>