class CounterComponent extends Fronty.ModelComponent {
    constructor(expensesModel, userModel, node) {
        super(Handlebars.templates.counter, expensesModel, node);
        this.expensesService = new ExpensesService();
        this.expensesModel = expensesModel; // expenses
        this.userModel = userModel; // global
        let expense_date_from = 0;
        let expense_date_to = 0;
        let dates = [];
        this.purePieData = null;
        // this.mesesIntervalo = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
        this.mesesIntervalo = [];

        this.datos = [{
            name: 'Comunicaciones',
            data: [100, 200, 300, 150, 235, 254, 345, 293, 392, 124, 102, 134]
        },
        {
            name: 'Ocio',
            data: [50, 30, 20, 15, 23, 25, 34, 29, 39, 12, 10, 13]
        },
        {
            name: 'Alimentacion',
            data: [155, 132, 158, 156, 160, 135, 134, 129, 139, 112, 110, 113]
        },
        {
            name: 'Combustible',
            data: [50, 55, 60, 65, 70, 75, 80, 0, 170, 80, 85, 82]
        },
        {
            name: 'Suministros',
            data: [45, 45, 49, 50, 60, 65, 62, 66, 62, 66, 70, 65]
        }];

        this.addEventListener('click', '#datebutton', () => {
            
            this.expense_date_from = $('#fromDate').val();
            this.expense_date_to = $('#toDate').val();
            this.mesesIntervalo = this.diffDates(this.expense_date_from, this.expense_date_to);
            console.log("PUES HAY ESTOS MESES ENTRE MEDIO" + this.mesesIntervalo);
            console.log("fecha despues de puldar el boton " + this.expense_date_from);
            this.dates.push(this.expense_date_from);
            this.dates.push(this.expense_date_to);
            this.getLineChart(this.dates);
            this.getPieChart(this.dates);
        });

    }

    //[{name: 'Comunicaciones', y: 2629}, { name: 'Ocio',y: 300}, {name: 'Alimentación',y: 1633}, {name: 'Combustible',y: 872}, {name: 'Suministros',y: 705}]
    //[{"Combustible":23977366,"Alimentacion":657009,"Comunicaciones":686945454562,"Suministros":424984994,"Ocio":666666666713}]

    getLineChart(dates) {
        console.log("fechasguapa" + this.expense_date_from);
        this.expensesService.getLineChart(dates).then((data) => {
            this.lineData = JSON.stringify(data);
            console.log('Prueba dentro del getlinechart');
            let result = [];
            // Object.entries(data[0]).forEach(([key, value]) => {
            //     result.push({ name: key, data: value });
            // });
            console.log("RES LINECHART= " + result.toString());
            console.log("RES LINECHART= " + this.lineData);
            this.lineChart(result);

        });


    }

    getPieChart(dates) {
        let comun = null;
        //ARREGLAR ESTA PARTE PARA PASARLO AL CHARTS
        this.expensesService.getPieChart(dates).then((data) => {
            this.purePieData = data;
            this.pieData = JSON.stringify(data);
            console.log('Prueba dentro del getpiechart');
            let result = [];
            Object.entries(data[0]).forEach(([key, value]) => {
                result.push({ name: key, y: value });
            });
            console.log("RES PUREPIEDATA CHART= " + this.purePieData);
            console.log("RES PIE CHART= " + this.pieData);
            this.pieChart(result);
            comun = result;

        });

        this.addEventListener('click', '#buttontypes', () => {
            console.log("SE PULSA Y SE MANTIENE VARIABLE " + JSON.stringify(comun));
            let resultFilter = [];
            for (let type of comun) {
                console.log("probandooo" + Object.entries(type)[0][0])
                if ($('#combustibleCheck').is(':checked') && type["name"] == "Combustible") {
                    console.log("se chequea combustible??");
                    resultFilter.push({ name: "Combustible", y: type.y });
                    console.log("SE HA GUARDADO?= " + JSON.stringify(resultFilter));
                } else if ($('#comunicacionesCheck').is(':checked') && type["name"] == "Comunicaciones") {
                    resultFilter.push({ name: "Comunicaciones", y: type.y });
                } else if ($('#alimentacionCheck').is(':checked') && type["name"] == "Alimentacion") {
                    resultFilter.push({ name: "Alimentacion", y: type.y });
                } else if ($('#suministrosCheck').is(':checked') && type["name"] == "Suministros") {
                    resultFilter.push({ name: "Suministros", y: type.y });
                } else if ($('#ocioCheck').is(':checked') && type["name"] == "Ocio") {
                    resultFilter.push({ name: "Ocio", y: type.y });
                }
            }

            console.log("RES PIE CHART FILTER= " + JSON.stringify(resultFilter));
            this.pieChart(resultFilter);

        });



    }

    pieChart(pieData) {
        let realData = pieData;

        Highcharts.chart('container2', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie',
                margin: [5, 5, 5, 5],
                spacingTop: 5,
                spacingBottom: 5,
                spacingLeft: 5,
                spacingRight: 5
            },
            title: {
                text: ''
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
                    size: '100%',
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                    }
                }
            },
            credits: {
                enabled: false
            },
            exporting: { enabled: false },
            colors: ['#EB906A', '#D04004', '#DFBBB1', '#983207', '#EF5E0A', '#7E5746'],

            series: [{
                name: '',
                colorByPoint: true,
                data: realData
            }]
        });
    }

    // res: [{"Combustible":23977366,"Alimentacion":657009,"Comunicaciones":686945454562,"Suministros":424984994,"Ocio":666666666713}]


    lineChart(lineData) {
        Highcharts.chart('container', {
            title: {
                text: ''
            },
            yAxis: {
                title: {
                    text: 'Euros'
                }
            },
            xAxis: {
                accessibility: {
                    rangeDescription: 'Range: 2010 to 2020'
                },
                categories: this.mesesIntervalo
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle'
            },
            plotOptions: {
                series: {
                    label: {
                        connectorAllowed: false
                    }
                }
            },

            colors: ['#EB906A', '#D04004', '#DFBBB1', '#983207', '#EF5E0A', '#7E5746'],
            series: lineData,

            credits: {
                enabled: false
            },
            exporting: { enabled: false },
            responsive: {
                rules: [{
                    condition: {
                        maxWidth: 500
                    },
                    chartOptions: {
                        legend: {
                            layout: 'horizontal',
                            align: 'center',
                            verticalAlign: 'bottom'
                        }
                    }
                }]
            }

        });
    }

    diffDates(from, to) {
        var result = [];
        var datFrom = new Date('1 ' + from);
        var datTo = new Date('1 ' + to);
        if(datFrom < datTo) {
          var month = datFrom.getMonth();
          var toMonth = datTo.getMonth() + 1 + ((datTo.getYear() - datFrom.getYear())*12); //toMonth adjusted for year
          for(; month < toMonth; month++) { //Slice around the corner...
            result.push(monthNames[month % 12]);
          }
        }
    
        return result;
    }
    onStart() {

        this.expense_date_from = new Date();
        this.expense_date_from.setYear(1920);
        this.expense_date_to = new Date();
        this.dates = [];
        this.expense_date_from = this.expense_date_from.toISOString().slice(0, 10);
        this.expense_date_to = this.expense_date_to.toISOString().slice(0, 10);
        console.log("fechas probando url" + this.expense_date_from + " " + this.expense_date_to);

        this.dates.push(this.expense_date_from);
        this.dates.push(this.expense_date_to);

        console.log("fechas probando url" + this.dates);

        this.getPieChart(this.dates);
        this.getLineChart(this.dates);
        // this.getExpenses();
        // create the Highcharts chart and keep it as an attribute for further updates



    }

    // ALSO UPDATE CHART AFTER THE FRONTY-COMPONENT HAS RENDERED (AFTER EACH MODEL CHANGE)
    afterRender() {

        if (this.chart) { //check if this.chart exists, because the first afterRender is called before onStart
            // use "setData" in order to update the chart, but not create the chart again
            this.chart.series[0].setData([
                ['Counter', this.counterModel.counter]
            ]);
        }
    }
}
