export default function newsletter() {
   const form = document.querySelector('#js-newsletter');

   form.addEventListener("submit", function (event) {
      event.preventDefault();
      newsLetterSubscribe(event);
   }, false)
}

function newsLetterSubscribe() {
   const form = document.querySelector('#js-newsletter');
   const route = bp_site.rest + 'rtc/v1/newsletter-list';
   const nonce = bp_site.ajax_nonce;
   var name = form.querySelector('#name').value;
   var email = form.querySelector('#email').value;

   const params = {
      name: name,
      email: email
   };
   const options = {
      method: 'POST',
      body: JSON.stringify(params),
      beforeSend: function (xhr) {
         xhr.setRequestHeader('X-WP-Nonce', bp_site.ajax_nonce);
      }
   };
   fetch(route, options)
      .then(response => response.json())
      .then(response => {
         console.log(response);
         switch (response) {
            case 'email_exists':
               feedback('U bent al aangemeld voor onze nieuwsbrief.', true);
               break;
            case 'success':
               feedback('U bent succesvol aangemeld voor onze nieuwsbrief.', true);
               break;
            default:
               feedback('Er is iets mis gegaan.', false);
               break;
         }
         // Do something with response.
      });
}

function feedback(message, hide_form = false) {
   const form = document.querySelector('#js-newsletter');
   var response_area = document.querySelector('#js-newsletter-notifications');
   response_area.innerHTML = message;

   if (hide_form) {
      form.remove();
   }
}