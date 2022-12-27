class CounterComponent extends Fronty.ModelComponent {
    constructor(counterModel, userModel, router) {
        super(Handlebars.templates.counter, counterModel);
        this.counterModel = counterModel;

        this.userModel = userModel; // global
        this.addModel('user', userModel);
        this.router = router;

        this.addEventListener('click', '#increase', () => {
            //update the model
            this.counterModel.increase();
        });
}

    onStart() {
        // create the Highcharts chart and keep it as an attribute for further updates
        this.chart = Highcharts.chart('chart', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Counter'
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
                    text: 'Counter value'
                }
            },


            series: [{
                name: 'Counter',
                data: [
                    ['Counter', this.counterModel.counter]
                ],
                dataLabels: {
                    enabled: true,
                    rotation: -90,
                    color: '#FFFFFF',
                    align: 'right',
                    format: '{point.y:.1f}', // one decimal
                    y: 10, // 10 pixels down from the top
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
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

// var myModel = new CounterModel();

// Handlebars.templates = {};
// Handlebars.templates.counter = Handlebars.compile(
//     '<div><span>Current counter: {{counter}}</span><button id="increase">Increase</button><div id="chart"></div></div>'
// );

// var myComponent = new CounterComponent(myModel, 'myapp');

// myComponent.start();
