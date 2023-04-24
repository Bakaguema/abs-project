import { Controller } from '@hotwired/stimulus';
import { connectStreamSource, disconnectStreamSource } from "@hotwired/turbo";


export default class extends Controller {

  static values = {
    url: String,
    urlNotif: String,
    appUser: Number,
  }


  connect() {

    
    //const url = new URL('http://127.0.0.1:3000/.well-known/mercure');
     const url = new URL( window.location.origin + '/.well-known/mercure');
    url.searchParams.append('topic', this.urlValue);
    // url.searchParams.append('topic', 'http://127.0.0.1:8000/chat');

    this.es = new EventSource(url, {withCredentials: true});


    connectStreamSource(this.es);


    // eventSource.onmessage = event => {
    //   const message= JSON.parse(event.data);

    //       document.getElementById('divmessage').insertAdjacentHTML(
    //           'beforeend',
    //           appUser === message.user.id ?
    //           `<li class="forum__table-row">
    //                   <div class="forum__table-col forum__table-getUser">
    //                       <i class="bx bxs-user-voice bx-flip-horizontal" ></i> moi <br>
    //                       <span>le ${ moment(message.createdAt).format('L') } <br>
    //         ${ moment(message.createdAt).format('LT') } </span>
    //                   </div>
    //                   <div class="forum__table-col forum__table-userMessage">${message.content}</div>
    //           </li>` :
    //           `<li class="forum__table-row">
    //               <div class="forum__table-col forum__table-message">${message.content}</div>
    //                   <div class="forum__table-col forum__table-user">${message.user.firstname}<i class='bx bxs-user-voice' ></i><br>
    //                  <span>le ${ moment(message.createdAt).format('L') } <br>
    //       ${ moment(message.createdAt).format('LT') } </span>
    //               </div>
    //           </li>`
    //       )

    let appUserId = this.appUserValue

    function escapeHtml(text) {
      var map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
      };
      
      return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }

    this.es.onmessage = function ({ data }) {
      const message = JSON.parse(data);
              if (message.chat) {
                let userFrom=""
                let userTo=""
          
                message.chat.users.forEach(element => {
                  if (element.id == appUserId) {
                    userFrom = element
                  } else {
                    userTo = element
                  }
                });
          
                // console.log('message', message);
                // console.log('message-chat', message.chat);
                // console.log('appUserId', appUserId);
                // console.log('fromId', message.from_id);
                // console.log("Attach", message.attachments[0].url)
                function printAttachments() {
                  let string="";
                  for (let i = 0; i < message.attachments.length; i++) {
          
          
                      string += `<a href="/uploads/message/${message.attachments[i].url}" target="blank" >
                      <embed src="/uploads/message/${message.attachments[i].url}" alt="piecejoint" width="150">
                      </a></br>`
                    }
                  
                    return string
                  
                }
                // console.log(printAttachments());
                document.querySelector('#turboscript').insertAdjacentHTML(
                  'beforeend',
                  appUserId === message.from_id ?
          
          
                    `
                  <div class="message-wrapper">
                  
                  <img class="message-pp" src="/uploads/profiles/${userFrom.picture}" alt="profile-pic">
                  <div class="message-box-wrapper">
                    <div class="message-box">
                    ${printAttachments()}  ${escapeHtml(message.content)}    
                    </div>
                      <span>${message.createdAt}</span>
                    </div>
                  </div>`
                    :
                    `<div class="message-wrapper reverse">
                  <img class="message-pp" src="/uploads/profiles/${userTo.picture}" alt="profile-pic">
                    <div class="message-box-wrapper"> 
                        <div class="message-box">  
                          ${printAttachments()} ${escapeHtml(message.content)}        
                            </div>         
                        <span>${message.createdAt}</span>   
                        </div>    
                    </div>`
          
                )
              }else if ((message.offer != undefined) && (message.toUserId == appUserId)) {
                document.querySelector('#offer-register').value = JSON.stringify(message.offer)
                const form = document.querySelector('#incoming')
                document.querySelector("#start").classList.add('invisible')
                document.querySelector('#offer-submit-button').click()
               
                
                // form.submit() 

              }else if ((message.startWebcam == true) && (message.toUserId == appUserId)) {
                let ringtone = document.getElementById('ringtone')

                ringtone.play()


                document.querySelector("#start").classList.remove('invisible')
                document.querySelector("#receive").classList.add('invisible')
              }else if ((message.startWebcam == true) && (message.toUserId != appUserId)) {
                document.querySelector("#receive").classList.add('invisible')
              }

    }

  }


  disconnect() {
    this.es.close();
    disconnectStreamSource(this.es);
  }


}