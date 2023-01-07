class Counter extends Fronty.Model {
    constructor() {
      super('counter');
      this.counter = 10;
    }

    increase() {
      this.set(() => {
        this.counter++
      });
    }
  }
