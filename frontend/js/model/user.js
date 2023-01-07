class UserModel extends Fronty.Model {
  constructor() {
    super('UserModel');
    this.isLogged = false;
  }

  setLoggeduser(loggedUser) {
    this.set((self) => {
      self.currentUser = loggedUser;
      self.isLogged = true;
    });
  }

  logout() {
    this.set((self) => {
      delete self.currentUser;
      self.isLogged = false;
    });
  }


  //COmprobar si hacer algo mas
  deleteuser() {
    this.set((self) => {
      delete self.currentUser;
      self.isLogged = false;
    });
  }

}
