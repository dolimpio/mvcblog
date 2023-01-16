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
        this.lineData;
        this.pieData;
        this.mesesIntervalo = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        // this.mesesIntervalo = [];

        this.datos = [{ name: 'Comunicaciones', data: [100, 200, 300, 150, 235, 254, 345, 293, 392, 124, 102, 134] },
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
            // let firstmonth = new Date(this.expense_date_from);
            // let lastmonth = new Date(this.expense_date_to);
            this.mesesIntervalo = this.diffDates(this.expense_date_from, this.expense_date_to);
            this.dates = [];
            console.log("PUES HAY ESTOS MESES ENTRE MEDIO " + this.mesesIntervalo);
            console.log("fecha despues de puldar el boton " + this.expense_date_from);
            this.dates.push(this.expense_date_from);
            this.dates.push(this.expense_date_to);
            this.getLineChart(this.dates);
            this.getPieChart(this.dates);
        });

        this.addEventListener('click', '#buttontypes', () => {
            console.log("SE PULSA Y SE MANTIENE VARIABLE LINE" + this.lineData);
            console.log("SE PULSA Y SE MANTIENE VARIABLE PIE " + this.pieData);
            let pie = this.pieData;
            let line = this.lineData;
            let resultFilterPie = [];
            let resultFilterLine = [];
            let combustibleCheck = $('#combustibleCheck').is(':checked');
            let comunicacionesCheck = $('#comunicacionesCheck').is(':checked');
            let alimentacionCheck = $('#alimentacionCheck').is(':checked');
            let suministrosCheck = $('#suministrosCheck').is(':checked');
            let ocioCheck = $('#ocioCheck').is(':checked');
            console.log("QUE ME VIEN EN PIE " + JSON.stringify(pie));

            for (let lineD of line) {
                console.log("BULCE TIPOS " + JSON.stringify(lineD));

                if (combustibleCheck && lineD["name"] == "Combustible") {
                    console.log("se chequea combustible?? EN EL DE LINEAAAA");
                    resultFilterLine.push({ name: "Combustible", data: lineD.data });
                    console.log("SE HA GUARDADO?= " + JSON.stringify(resultFilterLine));
                } else if (comunicacionesCheck && lineD["name"] == "Comunicaciones") {
                    resultFilterLine.push({ name: "Comunicaciones", data: lineD.data  });
                } else if (alimentacionCheck && lineD["name"] == "Alimentacion") {
                    resultFilterLine.push({ name: "Alimentacion", data: lineD.data  });
                } else if (suministrosCheck && lineD["name"] == "Suministros") {
                    resultFilterLine.push({ name: "Suministros", data: lineD.data  });
                } else if (ocioCheck && lineD["name"] == "Ocio") {
                    resultFilterLine.push({ name: "Ocio", data: lineD.data  });
                }
            }

            for (let pieD of pie) {
                console.log("-------- BULCE TIPOS en el pIE " + JSON.stringify(pieD));
                if (combustibleCheck && pieD["name"] == "Combustible") {
                    console.log("se chequea combustible??");
                    resultFilterPie.push({ name: "Combustible", y: pieD.y });
                    console.log("SE HA GUARDADO?= " + JSON.stringify(resultFilterPie));
                } else if (comunicacionesCheck && pieD["name"] == "Comunicaciones") {
                    resultFilterPie.push({ name: "Comunicaciones", y: pieD.y });
                } else if (alimentacionCheck && pieD["name"] == "Alimentacion") {
                    resultFilterPie.push({ name: "Alimentacion", y: pieD.y });
                } else if (suministrosCheck && pieD["name"] == "Suministros") {
                    resultFilterPie.push({ name: "Suministros", y: pieD.y });
                } else if (ocioCheck && pieD["name"] == "Ocio") {
                    resultFilterPie.push({ name: "Ocio", y: pieD.y });
                }
            }
            console.log("RES LINE CHART FILTER= " + JSON.stringify(resultFilterLine));
            console.log("RES PIE CHART FILTER= " + JSON.stringify(resultFilterPie));

            this.lineChart(resultFilterLine);
            this.pieChart(resultFilterPie);


        });

    }

    getLineChart(dates) {
        console.log("fechasguapa" + this.expense_date_from);
        let comun;
        this.expensesService.getLineChart(dates).then((data) => {
            console.log('Prueba dentro del getlinechart');
            let result = data;
            console.log("RES LINECHART= " + result.toString());
            console.log("RES LINECHART= " + this.lineData);
            console.log("CLAVES SON " + result);
            let finalLineData = this.prepareLineChartData(result);
            this.lineData = finalLineData;
            console.log("VMAOS A REVISAR COMO ES EL RESULTADO DE LINE " + JSON.stringify(finalLineData));
            this.lineChart(finalLineData);
            comun = this.finalLineData;
        });

    }

    prepareLineChartData(result){
        let dataCombustible = [];
        let dataAlimentacion = [];
        let dataOcio = [];
        let dataComunicaciones = [];
        let dataSuministros = [];
        for (let index = 0; index < this.mesesIntervalo.length; index++) {
            if (result.hasOwnProperty(this.mesesIntervalo[index])) {
                let expenses_on_month = result[this.mesesIntervalo[index].toString()];
                console.log("entrada dentro del mesAAAAAAAAAAA" + expenses_on_month)
                Object.entries(expenses_on_month).forEach(month => {
                    const str = month.toString();
                    const [name, data] = str.split(",");
                    const insideMonth = { [name]: parseInt(data) };

                    console.log("ESTO ES UN MES " + insideMonth);
                    console.log("ESTO ES UN MES EN STRING" + insideMonth.toString());

                    if ("combustible" in insideMonth) {
                        dataCombustible.push(insideMonth["combustible"]);
                        console.log("Esto es un gasto de combusitbleee" + insideMonth["combustible"]);
                    }

                    if ("alimentacion" in insideMonth) {
                        dataAlimentacion.push(insideMonth["alimentacion"]);
                        console.log("Esto es un gasto de Alimentacion" + insideMonth["alimentacion"]);
                    }

                    if ("ocio" in insideMonth) {
                        dataOcio.push(insideMonth["ocio"]);
                        console.log("Esto es un gasto de Ocio" + insideMonth["ocio"]);
                    }

                    if ("comunicaciones" in insideMonth) {
                        dataComunicaciones.push(insideMonth["comunicaciones"]);
                        console.log("Esto es un gasto de Comunicaciones" + insideMonth["comunicaciones"]);
                    } 

                    if ("suministros" in insideMonth) {
                        dataSuministros.push(insideMonth["suministros"]);
                        console.log("Esto es un gasto de Suministros" + insideMonth["suministros"]);
                    }
                });
            } else {
                console.log("EL MES NO ESTA");

                dataCombustible.push(0);
                dataAlimentacion.push(0);
                dataOcio.push(0);
                dataComunicaciones.push(0);
                dataSuministros.push(0);
            }
        }
        const finalResult = [
            {
                name: 'Comunicaciones',
                data: dataComunicaciones
            },
            {
                name: 'Ocio',
                data: dataOcio
            },
            {
                name: 'Alimentacion',
                data: dataAlimentacion
            },
            {
                name: 'Combustible',
                data: dataCombustible
            },
            {
                name: 'Suministros',
                data: dataSuministros
            }
        ];

        console.log("RESULTADO FINAL A VER SI CUELA " + finalResult);
        return finalResult;
    }
    getPieChart(dates) {
        let comun = null;
        //ARREGLAR ESTA PARTE PARA PASARLO AL CHARTS
        this.expensesService.getPieChart(dates).then((data) => {
            console.log('Prueba dentro del getpiechart');
            let result = [];
            Object.entries(data[0]).forEach(([key, value]) => {
                result.push({ name: key, y: value });
            });
            this.pieChart(result);
            this.pieData = result;
            console.log("RES PIE CHART= " + this.pieData);

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
        var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        var result = [];
        var datFrom = new Date(from);
        var datTo = new Date(to);
        if (datFrom < datTo) {
            var month = datFrom.getMonth();
            var toMonth = datTo.getMonth() + 1 + ((datTo.getYear() - datFrom.getYear()) * 12); //toMonth adjusted for year
            for (; month < toMonth; month++) { //Slice around the corner...
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
