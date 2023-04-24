import { Controller } from '@hotwired/stimulus';
import { connectStreamSource, disconnectStreamSource } from "@hotwired/turbo";


export default class extends Controller {

  static values = {
    url: String,
    // urlNotif: String,
    appUser: Number,
  }


  connect() {

    // const url = JSON.parse(document.getElementById("mercure-url").textContent);
    // this.es = new EventSource(url, {withCredentials: true});

    //const url = new URL('http://127.0.0.1:3000/.well-known/mercure');
     const url = new URL( window.location.origin + '/.well-known/mercure');
   //  url.searchParams.append('topic', this.urlValue);
    url.searchParams.append('topic', window.location.origin + '/chat');

    this.es = new EventSource(url, {withCredentials: true});

    connectStreamSource(this.es);

    let appUserId = this.appUserValue

    this.es.onmessage = function ({ data }) {
      const message = JSON.parse(data);


      if ((appUserId == message.to_id[0]) && (('/chat/' + message.chat.id) != window.location.pathname)) {

        document.querySelector("#badgeNotif").classList.add('bg-danger')
        document.querySelector("#badgeNotif").innerHTML = message.compteur

        fetch('/save-number', {
          method: 'POST',
          headers: {
              'Content-Type': 'application/json'
          },
          body: JSON.stringify({
              number: message.compteur
          })
        })
      };
    }
  }

  disconnect() {
    this.es.close();
    disconnectStreamSource(this.es);
  }


}