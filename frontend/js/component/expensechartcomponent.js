class CounterComponent extends Fronty.ModelComponent {
    constructor(expensesModel, node) {
      super(Handlebars.templates.counter, expensesModel, node);
      this.expensesModel = expensesModel;
      this.expenseService = new ExpensesService();
      this.allExpenses = this.expenseService.findAllExpenses();
      this.hola = Object.values(this.expenseService.findAllExpenses());
      

      this.mesesIntervalo = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
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
    }



    onStart() {

        console.log("a "+this.allExpenses);
      // create the Highcharts chart and keep it as an attribute for further updates
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
        series: this.datos,

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
                size:'100%',
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
            data: [{
                name: 'Comunicaciones',
                y: 2629,
                sliced: true,
                selected: true
            }, {
                name: 'Ocio',
                y: 300
            },  {
                name: 'Alimentación',
                y: 1633
            }, {
                name: 'Combustible',
                y: 872
            }, {
                name: 'Suministros',
                y: 705
            }]
        }]
    });
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
