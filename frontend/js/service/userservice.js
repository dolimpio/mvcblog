class UserService {
  constructor() {

  }

  loginWithSessionData() {
    var self = this;
    return new Promise((resolve, reject) => {
      if (window.sessionStorage.getItem('login') &&
        window.sessionStorage.getItem('pass')) {
        self.login(window.sessionStorage.getItem('login'), window.sessionStorage.getItem('pass'))
          .then(() => {
            resolve(window.sessionStorage.getItem('login'));
          })
          .catch(() => {
            reject();
          });
      } else {
        resolve(null);
      }
    });
  }

  setCookie(name, value, days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}

  getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
  return null;
}

  checkCookie(name) {
    var value = this.getCookie(name);
    if (value != null) {
        return true;
    } else {
        return false;
    }
}

  deleteCookie(name) {
    this.setCookie(name, "", -1);
}

  loginWithCookies() {
    var self = this;
    return new Promise((resolve, reject) => {
      if (this.checkCookie('user') && this.checkCookie('pass')) {
        self.login(this.getCookie("user"), this.getCookie("pass"))
          .then(() => {
            resolve(this.getCookie("user"));
          })
          .catch(() => {
            reject();
          });
      } else {
        resolve(null);
      }
    });
  }

  loginCookies(login, pass){
  return new Promise((resolve, reject) => {
      $.get({
          url: AppConfig.backendServer+'/rest/user/' + login,
          beforeSend: function(xhr) {
            xhr.setRequestHeader("Authorization", "Basic " + btoa(login + ":" + pass));
          }
        })
        .then(() => {
          //keep this authentication forever
          window.sessionStorage.setItem('login', login);
          window.sessionStorage.setItem('pass', pass);

          if(this.getCookie("user")==null&&this.getCookie("pass")==null){
          this.setCookie("user",login,30);
          this.setCookie("pass",pass,30);
        }
        
          $.ajaxSetup({
            beforeSend: (xhr) => {
              xhr.setRequestHeader("Authorization", "Basic " + btoa(login + ":" + pass));
            }
          });
          resolve();
        })
        .fail((error) => {
          window.sessionStorage.removeItem('login');
          window.sessionStorage.removeItem('pass');
          $.ajaxSetup({
            beforeSend: (xhr) => {}
          });
          reject(error);
        });
    });
  }

  login(login, pass) {
    console.log(this.getCookie("user") + ", " + this.getCookie("pass"));
    return new Promise((resolve, reject) => {
      $.get({
          url: AppConfig.backendServer+'/rest/user/' + login,
          beforeSend: function(xhr) {
            xhr.setRequestHeader("Authorization", "Basic " + btoa(login + ":" + pass));
          }
        })
        .then(() => {
          //keep this authentication forever
          window.sessionStorage.setItem('login', login);
          window.sessionStorage.setItem('pass', pass);
        
          $.ajaxSetup({
            beforeSend: (xhr) => {
              xhr.setRequestHeader("Authorization", "Basic " + btoa(login + ":" + pass));
            }
          });
          resolve();
        })
        .fail((error) => {
          window.sessionStorage.removeItem('login');
          window.sessionStorage.removeItem('pass');
          $.ajaxSetup({
            beforeSend: (xhr) => {}
          });
          reject(error);
        });
    });
  }

  logout() {
    window.sessionStorage.removeItem('login');
    window.sessionStorage.removeItem('pass');
    this.deleteCookie("user");
    this.deleteCookie("pass");
    console.log(this.getCookie("user") + ", " + this.getCookie("pass"));

    $.ajaxSetup({
      beforeSend: (xhr) => {}
    });
  }

  register(user) {
    return $.ajax({
      url: AppConfig.backendServer+'/rest/user',
      method: 'POST',
      data: JSON.stringify(user),
      contentType: 'application/json'
    });
  }

  editUser(user) {
    return $.ajax({
      url: AppConfig.backendServer+'/rest/user',
      method: 'PUT',
      data: JSON.stringify(user),
      contentType: 'application/json'
    });
  }

  deleteUser(user) {
    alert("User deleted successfully");
    return $.ajax({
      url: AppConfig.backendServer+'/rest/user/' + user,
      method: 'DELETE'
    });
    
  }
  
//Opcion de nuestro amigo
//  deleteUser(userId) {
//     fetch(/api/users/${userId}, {
//       method: 'DELETE',
//     })
//       .then(response => {
//         if (response.ok) {
//           // Remove the user from the list of users displayed on the page
//           // Show a message to confirm that the deletion was successful
//           return;
//         }
//         throw new Error('Error deleting user');
//       })
//       .catch(error => {
//         // Handle the error
//       });
//   }


}
