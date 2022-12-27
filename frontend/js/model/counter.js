class CounterModel extends Fronty.Model {
    constructor() {
        super('CounterModel');
        this.counter = 10;
    }

    increase() {
        this.set(() => {
            this.counter++
        });
    }
}