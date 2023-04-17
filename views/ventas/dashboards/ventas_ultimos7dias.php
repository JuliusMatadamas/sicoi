<figure class="highcharts-figure">
    <div id="ventas-ultimos7dias">Cargando...</div>
</figure>
<?php

$fechas = [];
foreach($data["ventas_ultimos7dias"] as $item) {
    if(!in_array($item["fecha_venta"],$fechas)) array_push($fechas, $item["fecha_venta"]);
}

$vendedores = [];
foreach($data["ventas_ultimos7dias"] as $item) {
    if(!in_array($item["vendedor"],$vendedores)) array_push($vendedores, $item["vendedor"]);
}
sort($vendedores);

$ventas = [];
foreach($vendedores as $vendedor) {
    foreach($fechas as $fecha) {
        for($i = 0; $i < count($data["ventas_ultimos7dias"]); $i++) {
            if($data["ventas_ultimos7dias"][$i]["fecha_venta"] == $fecha) {
                if($data["ventas_ultimos7dias"][$i]["vendedor"] == $vendedor) {
                    $ventas[$vendedor][$fecha] = intval($data["ventas_ultimos7dias"][$i]["total"]);
                    break;
                } else {
                    $ventas[$vendedor][$fecha] = 0;
                }
            }
        }
    }
}

$series = "{name:'";
foreach($vendedores as $vendedor) {
    $series .= $vendedor."', data:[";
    foreach($fechas as $fecha) {
        $series .= $ventas[$vendedor][$fecha] .",";
    }
    $series = substr($series, 0, -1);
    $series .= "]}, {name:'";
}
$series = substr($series, 0, -9);

?><script>
    const chartVentasUltimos7Dias = Highcharts.chart('ventas-ultimos7dias', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Ventas últimos 7 días',
            align: 'left'
        },
        xAxis: {
            categories: ['<?php echo implode("','", $fechas) ?>']
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Ventas realizadas'
            },
            stackLabels: {
                enabled: true,
                style: {
                    fontWeight: 'bold',
                    color: ( // theme
                        Highcharts.defaultOptions.title.style &&
                        Highcharts.defaultOptions.title.style.color
                    ) || 'gray',
                    textOutline: 'none'
                }
            }
        },
        legend: {
            align: 'left',
            x: 70,
            verticalAlign: 'top',
            y: 20,
            floating: true,
            backgroundColor:
                Highcharts.defaultOptions.legend.backgroundColor || 'white',
            borderColor: '#CCC',
            borderWidth: 1,
            shadow: false
        },
        tooltip: {
            headerFormat: '<b>{point.x}</b><br/>',
            pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
        },
        plotOptions: {
            column: {
                stacking: 'normal',
                dataLabels: {
                    enabled: true
                }
            }
        },
        series: [<?php echo $series; ?>]
    });

    window.addEventListener('resize', () => {
        resizeChart();
    });

    window.onload = () => {
        resizeChart();
    }

    function resizeChart() {
        if (window.innerWidth >= 1000) {
            chartVentasUltimos7Dias.setSize(null);
        }

        if (window.innerWidth >= 900 && window.innerWidth < 1000) {
            chartVentasUltimos7Dias.setSize(600);
        }

        if (window.innerWidth >= 800 && window.innerWidth < 900) {
            chartVentasUltimos7Dias.setSize(550);
        }

        if (window.innerWidth >= 700 && window.innerWidth < 800) {
            chartVentasUltimos7Dias.setSize(450);
        }

        if (window.innerWidth >= 600 && window.innerWidth < 700) {
            chartVentasUltimos7Dias.setSize(400);
        }

        if (window.innerWidth < 600) {
            chartVentasUltimos7Dias.setSize(350);
        }
    }
</script>