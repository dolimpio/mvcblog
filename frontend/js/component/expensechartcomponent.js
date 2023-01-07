class CounterComponent extends Fronty.ModelComponent {
    constructor(counterModel, node) {
      super(Handlebars.templates.counter, counterModel, node);
      this.counterModel = counterModel;

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