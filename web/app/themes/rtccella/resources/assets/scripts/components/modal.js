import { Modal } from 'bootstrap'
import newsletter from './newsletter';
export default function modal() {
   const modalTriggers = document.querySelectorAll('.js-modal-trigger');
   const modal = document.querySelector('#js-modal');
   const rest = bp_site['rest'];
   ;
   modalTriggers.forEach((trigger) => {
      trigger.addEventListener('click', function () {
         var type = trigger.dataset.type;
         modalAjaxCall(type, modal);
      });
   });

}



async function modalAjaxCall(type, modalEl) {
   const url = window.bp_site['ajax'],
      nonce = window.bp_site['ajax_nonce'],
      data = new FormData(),
      modalTitle = modalEl.querySelector('.modal-title'),
      modalContent = modalEl.querySelector('.modal-body');

   var parameters = {
      type: type,
   };

   data.append('action', 'load_modal');
   data.append('nonce', nonce);
   data.append('parameters', JSON.stringify(parameters));

   try {
      const response = await fetch(url, {
         method: 'POST',
         credentials: 'same-origin',
         body: data,
      });

      const res = await response.json(); // read the json response from https://github.com/soderlind/es6-wp-ajax-demo/blob/master/es6-wp-ajax-demo.php#L57
      if (res.response === 'success') {
         var modal = new Modal(modalEl);
         modal.toggle()
         modalTitle.innerHTML = res.title;
         modalContent.innerHTML = res.content;
         newsletter();
      } else {
         console.error(res);
      }
   } catch (err) {
      console.error(err);
   }
}
